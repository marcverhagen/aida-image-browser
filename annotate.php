<?php

include 'directories.php';
include 'database.php';
include 'utils.php';

$DEBUG = false;

debug_on();
session_start();
//session_destroy();

$file = $_GET['file'];
$mode = $_GET['mode'];

$connection = db_connect();

$logged_in = false;
$login_failed = true;
if (isset($_GET['logging_in'])) {
    $result = db_validate_annotator($connection, $_GET['login'], $_GET['password']);
    if ($result) {
        $login_failed = false;
        $_SESSION['annotator'] = $_GET['login']; }
}

if (isset($_SESSION['annotator'])) {
    $annotator = $_SESSION['annotator'];
    $logged_in = true;
}

// When the annotator clicked "Save Type"
if ($logged_in && array_key_exists('save_type', $_GET)) {
    $type = $_GET['type'];
    db_insert_type($connection, $file, $type, $annotator);
}

// When the annotator clicked "Save Annotation"
if ($logged_in && array_key_exists('save_annotation', $_GET)) {
    $annotation = (object) array(
        'objects' => $_GET['objects'],
        'attributes' => $_GET['attributes'],
        'relations' => $_GET['relations'],
        'events' => $_GET['events'],
        'habitat' => $_GET['habitat'],
        'comment' => $_GET['comment'] );
    db_insert_annotation($connection, $file, $annotation, $annotator);
}

// This can be used if we are running this off a list of images that are assigned
// to the annotator. In that case, $_GET['next'] will be an integer that points to
// an element of a task list list. First time you run this for the annotator you
// need to check how far the annotator got the previous time. (Probably easiest when
// you just start off with presenting a list, the next is set accordingly).
if (isset($_GET['next']))
    $file = get_next_image_for_annotator($annotator, $_GET['next']);

$image = new Image($file, $DATA,  $connection);

$connection->close();

?>

<html>
<head>
<link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>

<?php

echo "<h1>Image Editor - $file</h1>\n\n";
$image->display();

if ($logged_in) {
    if ($mode == 'type')
        $image->display_type_form($mode);
    elseif ($mode == 'annotation')
        $image->display_annotations_form($mode);
    else {
        $image->display_type_form($mode);
        $image->display_annotations_form($mode); }
} else {
    display_login_form($file, $login_failed);
}

?>

</body>
</html>
