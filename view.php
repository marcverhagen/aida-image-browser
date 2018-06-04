<?php

include 'directories.php';
include 'database.php';
include 'utils.php';

$DEBUG = false;
//debug_on();

$mode = $_GET['mode'];

$connection = db_connect();

$browser = new Browser($DATA);

?>

<html>
<head>
<link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>

<?php

function next_url($start, $length) {
    return " <a href=view.php?mode=all&start=$start>Show next $length</a>";
}

if ($mode == 'all') {
    echo "<h1>All Images</h1>";
    display_navigation(array(array('index.php', 'Back home')));
    $start = 0;
    $length = 10;
    if (array_key_exists('start', $_GET))
        $start = (int) $_GET['start'];
    $next = $start + $length;
    echo "<p>&nbsp;| Showing $length images starting at image $start | ";
    echo next_url($next, $length);
    echo " |</p>\n";
    $browser->display_images($start, $length, $connection);
    echo "<p>&nbsp;| ";
    echo next_url($next, $length);
    echo " |</p>\n";

} elseif ($mode == 'annotated') {
    echo "<h1>Annotated Images</h1>";
    display_navigation(array(array('index.php', 'Back home')));
    $browser->display_annotated_images($connection);

} elseif ($mode == 'commented') {
    echo "<h1>Commented Images</h1>";
    display_navigation(array(array('index.php', 'Back home')));
    $browser->display_commented_images($connection);

} elseif ($mode == 'iaa') {
    $cat = $_GET['cat'];
    echo "<h1>Images with clashing annotations</h1>";
    echo "<h4>Showing images with disagreement on the $cat category</h4>";
    display_navigation(array(array('index.php', 'Back home')));
    $browser->display_iaa_images($connection, $cat);

} elseif ($mode == 'list') {
    echo "<h1>List of all Images</h1>";
    display_navigation(array(array('index.php', 'Back home')));
    $browser->display_annotation_list();

} elseif ($mode == 'term') {
    $term = $_GET['term'];
    $term_index = read_term_index();
    $images = explode(" ", trim($term_index[$term]));
    $count = count($images);
    echo "<h1>Images for '$term'</h1>\n\n";
    display_navigation(array(array('index.php', 'Back home')));
    $img_txt = $count == 1 ? 'image' : 'images';
    echo "<p>Displaying $count $img_txt</p>\n\n";
    $browser->display_list_of_images($images, $connection);

}


$connection->close();

?>

</body>
</html>
