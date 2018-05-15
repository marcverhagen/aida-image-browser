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
    $result = file_put_contents($annotation_file, $json_string);
    debug($result);
    debug("Annotation saved to $annotation_file\n\n" . $json_string);
}

$browser = new Browser($DATA);


?>

<html>
<head>
<link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>

<?php display_file($DATA, $file, $browser->files[$file]); ?>

</body>
</html>
