<?php

include 'directories.php';
include 'utils.php';

?>

<html>
<head>
<link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>

<?php

$files = read_files($IMAGES_DIR);
write_images($IMAGES_DIR, $CAPTIONS_DIR, $ANNOTATIONS_DIR, $files);

?>

</body>
</html>
