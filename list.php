<?php

include 'directories.php';
include 'utils.php';

$files = read_files($DATA->IMAGES);

?>

<html>
<head>
<link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>

<?php

write_annotation_list($DATA, $files);

?>

</body>
</html>
