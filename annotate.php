<?php

include 'database.php';
include 'utils.php';

//debug_on();
session_start();

$connection = db_connect();

if (isset($_GET['logging_in']))
    login($connection);

$logged_in = isset($_SESSION['annotator']) ? true : false;

if ($logged_in) {
    $annotator = $_SESSION['annotator'];
    $tasks = db_get_tasks($connection, $annotator);
    $_SESSION['tasks'] = $tasks;
    $images_assigned = count($tasks);
    $images_done = 0;
    foreach ($tasks as $task) {
        if ($task->done == 1) $images_done++; }
}

$connection->close();

?>

<html>
<head>
<link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>

<?php

echo "<h1>Image Annotator - Assigned Tasks</h1>\n\n";

display_navigation(array(array('index.php', 'home')));

if ($logged_in) {
    echo "<table cellpadding=5 cellspacing=0 border=1>\n";
    echo "<tr><td>annotator</td><td align=right>$annotator</td></tr>\n";
    echo "<tr><td>images assigned</td><td align=right>$images_assigned</td></tr>\n";
    echo "<tr><td>images done</td><td align=right>$images_done</td></tr>\n";
    echo "</table>\n\n";
    echo "<p><a href=annotate_icrel.php>Start or continue annotation</a></p>\n\n";
} else {
    display_login_form('annotate.php', $file, $_SESSION['login_failed']);
}

?>

</body>
</html>
