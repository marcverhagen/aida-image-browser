<?php

include 'utils.php';

$IMAGES_DIR = 'data/images_captions_50/';

?>

<html>
<head>
<link rel="stylesheet" type="text/css" href="style.css">
</head>

<?php

$files = read_files($IMAGES_DIR);
print_files($IMAGES_DIR, $files, 10);

?>

</pre>