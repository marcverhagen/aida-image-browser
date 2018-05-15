<?php

include 'directories.php';
include 'utils.php';

$terms = read_term_index();
$count = count($terms);
$split = ceil($count / 6);

?>
<html>

<head>
<link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>

<h1>Terms</h2>

<table cellspacing=0 cellpadding=8 border=1 width=1000  class=indented>
<tr>

<?php

display_navigation(array(array('index.php', 'Back home')));

echo "<p>&nbsp;Showing $count nouns and verbs that occur in the captions</p>\n\n";

$row_number = 0;
foreach ($terms as $term => $images) {
    if ($row_number % $split == 0)
        echo "<td valign=top>\n";
        $row_number++;
    echo("<span class=nobreak><a href=view.php?mode=term&term=$term>$term</a></span><br/>\n");
}

?>

</tr>
</table>

</body>
</html>
