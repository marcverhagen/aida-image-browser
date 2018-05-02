<?php

include 'directories.php';
include 'utils.php';

$mode = $_GET['mode'];
$files = read_files($IMAGES_DIR);

?>

<html>
<head>
<link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>

<?php

if ($mode == 'all') {
    echo "<h1>All Images</h1>";
    write_navigation(array(array('index.php', 'Back home')));
    write_images($DATA, $files);
} elseif ($mode == 'annotated') {
    echo "<h1>Annotated Images</h1>";
    write_navigation(array(array('index.php', 'Back home')));
    write_annotated_images($DATA, $files);
} elseif ($mode == 'commented') {
    echo "<h1>Commented Images</h1>";
    write_navigation(array(array('index.php', 'Back home')));
    write_commented_images($DATA, $files);
} elseif ($mode == 'list') {
    echo "<h1>List of all Images</h1>";
    write_navigation(array(array('index.php', 'Back home')));
    write_annotation_list($DATA, $files);
}

?>

</body>
</html>
