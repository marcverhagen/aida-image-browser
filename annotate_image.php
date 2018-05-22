<?php

/*

This is now supposed to be an all-purpose annotator for all the tasks available
and it should only be accessed from an image as presented in the browser (and not
from a task list).

Because of that, the code below can be simplified (before annotate_icrel.php this
script tried to deal with all annotation modes).

*/


include 'directories.php';
include 'database.php';
include 'utils.php';

$DEBUG = false;

debug_on();
session_start();

debug_vars();

$file = $_GET['file'];
$mode = $_GET['mode'];

if isset($_GET['next'])
    $file = $_GET['next'];

$connection = db_connect();

$logged_in = false;
$login_failed = false;

// TODO: use login() in utils
if (isset($_GET['logging_in'])) {
    $result = db_validate_annotator($connection, $_GET['login'], $_GET['password']);
    if ($result) {
        $login_failed = false;
        $_SESSION['annotator'] = $_GET['login']; }
    else {
        $login_failed = true; }
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

// Used if we are running this off a list of images that are assigned to the
// annotator. In that case, $_GET['next'] will point to the next image in the
// task list. This is currently only used in the ImageCaptionRelation mode.
$next_file = null;
if (isset($_GET['next']))
    $next_file = $_GET['next'];


$image = new Image($file, $DATA,  $connection);

$connection->close();

?>

<html>
<head>
<link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>

<?php

echo "<h1>Image annotator - $file</h1>\n\n";

$image->display();//$show_current=false);

if ($logged_in) {
    if ($mode == 'ImageCaptionRelation')
        $image->display_image_caption_relation_form($mode, $next_file);
    elseif ($mode == 'VoxML')
        $image->display_voxml_form($mode);
    else {
        $image->display_image_caption_relation_form($mode, $next_file);
        $image->display_voxml_form($mode); }
} else {
    display_login_form('annotate_image.php', $file, $login_failed);
}

?>

</body>
</html>
