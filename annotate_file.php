<?php

include 'utils.php';

$IMAGES_DIR = 'data/images_captions_50/';

$files = read_files($IMAGES_DIR);
$file = $_GET['file']

?>

<html>
<head>
<link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>

<?php write_file($IMAGES_DIR, $file, $files[$file]); ?>

</body>
</html>