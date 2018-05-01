<?php

include 'utils.php';

$IMAGES_DIR = 'data/images_captions_50/';

$files = read_files($IMAGES_DIR);

?>

<html>
<head>
<link rel="stylesheet" type="text/css" href="style.css">
</head>

<?php

write_annotation_list($IMAGES_DIR, $files);

?>

</pre>