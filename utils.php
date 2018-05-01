<?php

function read_files($dir) {

    $dir_content = array_diff(scandir($dir), array('.', '..'));

    $files = array();
    foreach ($dir_content as $file) {
        $path_parts = pathinfo($file);
        $name = $path_parts['filename'];
        $extension = $path_parts['extension'];
        if (! array_key_exists($name, $files))
            $files[$name] = array();
        $files[$name][$extension] = $file;
    }
    return $files;
}

function print_files($dir, $files, $limit=False) {

    $count = 0;
    foreach ($files as $name => $details) {
        $count++;
        if ($limit && $count > $limit) break;
        $image_file = $dir . $details['jpg'];
        $caption_file = $dir . $details['txt'];
        $caption = file_get_contents($caption_file);
        $has_annotation = array_key_exists('ann', $details);
        if ($has_annotation) {
            $annotation_file = $dir . $details['ann'];
            $annotation = trim(file_get_contents($annotation_file)); }
        echo("<p><b>$name</b></p>\n");
        echo("<blockquote>\n");
        echo("<img src=$image_file>\n");
        echo("<p>$caption</p>\n\n");
        if ($has_annotation)
            echo("<pre>$annotation</pre>\n\n");
        echo("</blockquote>\n");
    }
}


function write_annotation_list($dir, $files) {

    echo("<table cellpadding=3 cellspacing=0 border=1 width=800>\n");
    foreach ($files as $name => $details) {
        $image_file = $dir . $details['jpg'];
        $caption_file = $dir . $details['txt'];
        $caption = file_get_contents($caption_file);
        $has_annotation = array_key_exists('ann', $details);
        echo("<tr valign=top>\n");
        if ($has_annotation) {
            $annotation_file = $dir . $details['ann'];
            $annotation = trim(file_get_contents($annotation_file));
            echo("<td>&#10003;</td>\n"); }
        else {
            echo("<td>&nbsp</td>\n"); }
        echo("<td><a href=annotate_file.php?file=$name>$name</a></td>\n");
        echo("<td>$caption</td>\n");
        echo("</tr>\n");
    }
    echo("</table>\n");
}


function write_file($dir, $name, $details) {

    $image_file = $dir . $details['jpg'];
    $caption_file = $dir . $details['txt'];
    $caption = file_get_contents($caption_file);
    $has_annotation = array_key_exists('ann', $details);
    if ($has_annotation) {
        $annotation_file = $dir . $details['ann'];
        $annotation = trim(file_get_contents($annotation_file)); }
    else {
        $annotation = ''; }
    echo("<h2>$name</h2>\n\n");
    echo("<table width=800 cellpadding=5>\n\n");
    echo("<tr><td><img src=$image_file width=800></td></tr>\n\n");
    echo("<tr><td>$caption</td></tr>\n\n");
    echo("</table>\n\n");

    echo("<p/>\n\n");
    echo("<form action=annotate_file.php>\n\n");
    echo("<input type=hidden name=file value=$name method=get />\n");
    echo("<textarea name=annotation rows=20 cols=130 autofocus>$annotation</textarea>\n\n");
    echo("<p><input class=button type=submit value=Save></p>\n");
    echo("</form>\n");
}



?>
