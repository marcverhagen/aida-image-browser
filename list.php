<?php

include 'directories.php';
include 'utils.php';

$browser = new Browser($DATA);

?>

<html>
<head>
<link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>

<?php

$browser->display_annotation_list();

?>

</body>
</html>
