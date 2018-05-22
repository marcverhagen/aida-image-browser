<?php

/*

This will only be run from the annotate.php page so we can assume the annotator
is logged in and that there is a tasks list in a session variable. Each time we
annotate three things must happen:

1. update the task list in the session variable
2. add the annotation to the database
3. update the task in the database

What image is annotated is determined by the task list by taking the first
unannotated image.

Currently, this is tuned to ImageCaptionRelation annotation, should at some
point be generalized (or we just create a file like this for any task).

*/

include 'directories.php';
include 'database.php';
include 'utils.php';

//debug_on();
session_start();

$MODE = 'EventCaptionRelation';

$RELATIONS = array(
    'event', 'result', 'person', 'non-person', 'location', 'not-english', 'other');

function set_current_task() {
    // Set the current task to the first tasks from the list that is not done yet
    // or set it to null if all tasks are done
    global $_SESSION;
    foreach ($_SESSION['tasks'] as $task) {
        if ($task->done == 0) {
            $_SESSION['current_task'] = $task;
            return; }}
    $_SESSION['current_task'] = null;
}

function save_relations($connection, $relations) {
    global $_GET, $_SESSION;
    $file = $_GET['file'];
    $annotator = $_SESSION['annotator'];
    $task_id = $_SESSION['current_task']->id;
    // 1. update the task list in the session variable
    $_SESSION['current_task']->done = 1;
    // 2. add the annotation to the database
    $selected = array();
    foreach ($relations as $relation) {
        if (isset($_GET[$relation]))
            $selected[] = $relation; }
    $selected = implode(' ', $selected);
    db_insert_type($connection, $file, strtoupper($selected), $annotator);
    // 3. update the task in the database
    db_update_task($connection, $task_id);
}

$connection = db_connect();

if (isset($_GET['save_relation']))
    save_relations($connection, $RELATIONS);

set_current_task();
$current_task = $_SESSION['current_task'];
debug($current_task);
if ($current_task != null)
    $image = new Image($current_task->image, $DATA,  $connection);

$connection->close();

?>

<html>
<head>
<link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>

<?php

if ($current_task == null)
    echo "<h1>Image annotator</h1>";
else
    echo "<h1>Image annotator - $current_task->image</h1>\n\n";

display_navigation(
    array(
        array('index.php', 'home'),
        array('annotate.php', 'annotator home'),
        // removed this because now we need the back button and that might spur
        // another database update, need to add parent/blank for the html
        // array('guidelines-EventCaptionRelations.html', 'guidelines'),
        array('logout.php', 'log out')));

if ($current_task == null) {
    echo "<p class=indented>All assigned images are annotated.</p>\n\n"; }
else {
    $image->display($annotations=false);
    display_space();
    $image->display_image_caption_relation_form(
        'annotate_icrel.php', $MODE, $RELATIONS, false);
}

?>

</body>
</html>
