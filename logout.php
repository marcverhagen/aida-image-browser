<?php

// Log off by wiping out all session variables

include 'utils.php';

session_start();
session_destroy()

?>

<html>
<head>
<link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>

<?php

echo "<h1>Image annotator</h1>";

display_navigation(
    array(
        array('index.php', 'home'),
        array('annotate.php', 'annotator home')));

echo "<p class=indented>You are now logged off.</p>\n\n";

?>

</body>
</html>
