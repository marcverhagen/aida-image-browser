<?php

function debug($string) {
    echo("<pre class=dotted>$string</pre>\n\n");
}


function read_files($dir) {
    $dir_content = array_diff(scandir($dir), array('.', '..'));
    $files = array();
    foreach ($dir_content as $file) {
        $path_parts = pathinfo($file);
        $name = $path_parts['filename'];
        $files[] = $name; }
    return $files;
}


function read_annotations($annotation_file) {
    if (file_exists($annotation_file)) {
        $json = file_get_contents($annotation_file);
        return json_decode($json);
    } else {
        return null; }
}


function write_images($data, $files, $start, $length) {

    $slice = array_slice($files, $start, $tart + $length);
    foreach ($slice as $name) {
        if ($limit && $count > $limit) break;
        $image_file = $data->IMAGES .  $name . '.jpg';
        $caption_file = $data->CAPTIONS . $name . '.txt';
        $annotation_file = $data->ANNOTATIONS .  $name . '.ann';
        $caption = file_get_contents($caption_file);
        $has_annotation = file_exists($annotation_file);
        write_name($name, 'h3');
        echo("<blockquote>\n");
        write_image($image_file, $caption);
        if ($has_annotation) {
            $json = trim(file_get_contents($annotation_file));
            write_annotation($json); }
        echo("</blockquote>\n");
    }
}


function write_annotated_images($data, $files) {

    foreach ($files as $name) {
        $image_file = $data->IMAGES .  $name . '.jpg';
        $caption_file = $data->CAPTIONS . $name . '.txt';
        $annotation_file = $data->ANNOTATIONS .  $name . '.ann';
        $caption = file_get_contents($caption_file);
        $has_annotation = file_exists($annotation_file);
        if ($has_annotation) {
            $json = trim(file_get_contents($annotation_file));
            write_name($name, 'h3');
            echo("<blockquote>\n");
            write_image($image_file, $caption);
            write_annotation($json);
            echo("</blockquote>\n"); }
    }
}

function write_commented_images($data, $files) {

    foreach ($files as $name) {
        $image_file = $data->IMAGES .  $name . '.jpg';
        $caption_file = $data->CAPTIONS . $name . '.txt';
        $annotation_file = $data->ANNOTATIONS .  $name . '.ann';
        $caption = file_get_contents($caption_file);
        $has_annotation = file_exists($annotation_file);
        if ($has_annotation) {
            $json = trim(file_get_contents($annotation_file));
            $annotation = json_decode($json);
            if ($annotation->comments) {
                write_name($name, 'h3');
                echo("<blockquote>\n");
                write_image($image_file, $caption);
                write_annotation($json);
                echo("</blockquote>\n");
            }
        }
    }
}


function write_annotation_list($data, $files) {

    echo("<table cellpadding=3 cellspacing=0 border=1 width=800>\n");
    foreach ($files as $name) {
        $image_file = $data->IMAGES .  $name . '.jpg';
        $caption_file = $data->CAPTIONS . $name . '.txt';
        $annotation_file = $data->ANNOTATIONS .  $name . '.ann';
        $caption = file_get_contents($caption_file);
        $has_annotation = file_exists($annotation_file);
        echo("<tr valign=top>\n");
        if ($has_annotation) {
            echo("<td width=20>&#10003;</td>\n"); }
        else {
            echo("<td width=20>&nbsp</td>\n"); }
        echo("<td><a href=annotate.php?file=$name>$name</a></td>\n");
        echo("<td>$caption</td>\n");
        echo("</tr>\n");
    }
    echo("</table>\n");
}


function write_file($data, $name, $details) {
    $image_file = $data->IMAGES .  $name . '.jpg';
    $caption_file = $data->CAPTIONS . $name . '.txt';
    $annotation_file = $data->ANNOTATIONS .  $name . '.ann';
    $caption = file_get_contents($caption_file);
    $annotations = read_annotations($annotation_file);
    echo("<h1>Image Note Taker</h1>\n");
    write_navigation(
        array(
            array('index.php', 'Back home'),
            array('list.php', 'Back to list')));
    write_name($name, 'h4');
    write_image($image_file, $caption);
    write_space();
    write_form($name, $annotations);
}


function write_navigation($targets) {
    echo "\n<div class=navigation>\n";
    echo "|\n";
    foreach ($targets as $target) {
        $url = $target[0];
        $text = $target[1];
        echo "<a href=$url>$text</a> |\n";
    }
    echo "</div>\n\n";
}


function write_name($name, $header='h2') {
    echo("<$header>$name</$header>\n\n");
}


function write_space() {
    echo("<p/>\n\n");
}


function write_image($image_file, $caption) {
    echo("<table width=800 cellpadding=5>\n\n");
    echo("<tr><td><img src=$image_file></td></tr>\n\n");
    echo("<tr><td>$caption</td></tr>\n\n");
    echo("</table>\n\n");
}


function write_annotation($json) {
    $annotation = json_decode($json);
    if ($annotation != null) {
        echo "<p><table class=indented width=800 cellpadding=5 cellspacing=0 border=1>\n";
        write_row('objects', '<pre>' . $annotation->objects . '</pre>');
        write_row('attributes', '<pre>' . $annotation->attributes . '</pre>');
        write_row('relations', '<pre>' . $annotation->relations . '</pre>');
        write_row('events', '<pre>' . $annotation->events . '</pre>');
        write_row('habitat', '<pre>' . $annotation->habitat . '</pre>');
        write_row('comments', '<pre>' . $annotation->comments . '</pre>');
        echo "</table></p>\n"; }
}


function write_form($name, $annotation) {
    echo("<form action=annotate.php>\n\n");
    echo("<input type=hidden name=file value=$name method=get />\n");
    echo("<input type=hidden name=form method=get />\n");
    echo("<div class=bordered>\n");
    echo("<table>\n");
    $checked = $annotation->selected == 'on' ? 'checked' : '';
    //write_row('Selected', "<input name=selected type=checkbox $checked>");
    write_textarea_row('Objects', $annotation->objects);
    write_textarea_row('Attributes', $annotation->attributes);
    write_textarea_row('Relations', $annotation->relations);
    write_textarea_row('Events', $annotation->eventss);
    write_textarea_row('Habitat', $annotation->habitat);
    write_textarea_row('Comments', $annotation->comments, 4);
    echo("<table>\n");
    write_space();
    echo("<input class=button type=submit value='Save Annotations'>\n\n");
    echo("</div>\n");
    echo("</form>\n");
}

function write_row($td1, $td2) {
    echo("<tr valign=top>\n");
    echo("  <td width=50>$td1</td>\n");
    echo("  <td>$td2</td>\n");
    echo("<tr>\n");
}


function write_textarea_row($header, $annotation, $rows=2) {
    $name = strtolower($header);
    echo("<tr valign=top>\n");
    echo("  <td>$header</td>\n");
    echo("  <td><textarea name=$name rows=$rows cols=90 autofocus>$annotation</textarea></td>\n");
    echo("<tr>\n");
}

?>
