<?php

include 'directories.php';
include 'utils.php';

$file = $_GET['file'];

if (array_key_exists('form', $_GET)) {
    // this means we clicked "Save Annotations"
    $annotation_file = $ANNOTATIONS_DIR . $file . '.ann';
    $annotations = array(
        'selected' => $_GET['selected'],
        'objects' => $_GET['objects'],
        'attributes' => $_GET['attributes'],
        'relations' => $_GET['relations'],
        'events' => $_GET['events'],
        'habitat' => $_GET['habitat'],
        'comments' => $_GET['comments'] );
    $json_string = json_encode($annotations, JSON_PRETTY_PRINT);
    file_put_contents($annotation_file, $json_string);
    //debug("Annotation saved to $annotation_file\n\n" . $json_string);
}

$files = read_files($IMAGES_DIR);


?>

<html>
<head>
<link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>

<?php write_file($DATA, $file, $files[$file]); ?>

</body>
</html>
