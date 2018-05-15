<?php

function debug($string) {
    echo("<pre class=dotted>$string</pre>\n\n");
}


class Browser {

    function __construct($data) {
        // TODO: allow $data to be null in which case we do not have to read files
        $this->data = $data;
        $this->files = array();
        $this->read_files();
    }

    function read_files() {
        $dir_content = array_diff(scandir($this->data->IMAGES), array('.', '..'));
        foreach ($dir_content as $file) {
            $path_parts = pathinfo($file);
            $name = $path_parts['filename'];
            $this->files[] = $name; }
    }

    function display_images($start, $length) {
        $slice = array_slice($this->files, $start, $tart + $length);
        foreach ($slice as $name) {
            $image = new Image($name, $this->data);
            $this->display_image($image); }
     }

    function display_annotated_images() {
        foreach ($this->files as $name) {
            $image = new Image($name, $this->data);
            if ($image->has_annotation())
                $this->display_image($image); }
    }

    function display_commented_images() {
        foreach ($this->files as $name) {
            $image = new Image($name, $this->data);
            if ($image->has_annotation() && $image->annotation->comments)
                    $this->display_image($image); }
    }

    function display_image($image) {
        display_name($image->name, 'h3');
        echo("<blockquote>\n");
        $image->display();
        echo("</blockquote>\n");
    }

    function display_annotation_list() {
        echo("<table cellpadding=3 cellspacing=0 border=1 width=800>\n");
        foreach ($this->files as $name) {
            $image = new Image($name, $this->data);
            echo("<tr valign=top>\n");
            $check = $image->has_annotation() ? '&#10003;' : '&nbsp;';
            echo("<td width=20>$check</td>\n");
            echo("<td><a href=annotate.php?file=$name>$name</a></td>\n");
            echo("<td>$image->caption</td>\n");
            echo("</tr>\n");
        }
        echo("</table>\n");
    }
}


class Image {

    function __construct($name, $data) {
        $this->name = $name;
        $this->image_file = $data->IMAGES .  $name . '.jpg';
        $this->caption_file = $data->CAPTIONS . $name . '.txt';
        $this->annotation_file = $data->ANNOTATIONS .  $name . '.ann';
        $this->comments_file = $data->COMMENTS .  $name . '.ann';
        $this->caption = file_get_contents($this->caption_file);
        if ($this->has_annotation()) {
            $this->json = trim(file_get_contents($this->annotation_file));
            $this->annotation = json_decode($this->json);
        }
    }

    function has_annotation() {
        return file_exists($this->annotation_file);
    }

    function display($annotations=true) {
        echo("<table width=800 cellpadding=5>\n\n");
        echo("<tr><td><img src=$this->image_file></td></tr>\n\n");
        echo("<tr><td>$this->caption</td></tr>\n\n");
        echo("</table>\n\n");
        if ($annotations)
            $this->display_annotation();
    }

    function display_annotation() {
        if ($this->has_annotation() && $this->annotation != null) {
            echo "<p><table class=indented width=800 cellpadding=5 cellspacing=0 border=1>\n";
            display_row('objects', '<pre>' . $this->annotation->objects . '</pre>');
            display_row('attributes', '<pre>' . $this->annotation->attributes . '</pre>');
            display_row('relations', '<pre>' . $this->annotation->relations . '</pre>');
            display_row('events', '<pre>' . $this->annotation->events . '</pre>');
            display_row('habitat', '<pre>' . $this->annotation->habitat . '</pre>');
            display_row('comments', '<pre>' . $this->annotation->comments . '</pre>');
            echo "</table></p>\n"; }
    }
}


function display_file($data, $name, $details) {
    $image = new Image($name, $data);
    //$annotations = read_annotations($image->annotation_file);
    echo("<h1>Image Note Taker</h1>\n");
    display_navigation(
        array(array('index.php', 'Back home'), array('list.php', 'Back to list')));
    display_name($name, 'h4');
    $image->display($annotations=false);
    display_space();
    display_form($name, $image->annotation);
}

function display_form($name, $annotation) {
    echo("<form action=annotate.php>\n\n");
    echo("<input type=hidden name=file value=$name method=get />\n");
    echo("<input type=hidden name=form method=get />\n");
    echo("<div class=bordered>\n");
    echo("<table>\n");
    $checked = $annotation->selected == 'on' ? 'checked' : '';
    //display_row('Selected', "<input name=selected type=checkbox $checked>");
    display_textarea_row('Objects', $annotation->objects);
    display_textarea_row('Attributes', $annotation->attributes);
    display_textarea_row('Relations', $annotation->relations);
    display_textarea_row('Events', $annotation->eventss);
    display_textarea_row('Habitat', $annotation->habitat);
    display_textarea_row('Comments', $annotation->comments, 4);
    echo("<table>\n");
    display_space();
    echo("<input class=button type=submit value='Save Annotations'>\n\n");
    echo("</div>\n");
    echo("</form>\n");
}

function display_navigation($targets) {
    echo "\n<div class=navigation>\n";
    echo "|\n";
    foreach ($targets as $target) {
        $url = $target[0];
        $text = $target[1];
        echo "<a href=$url>$text</a> |\n";
    }
    echo "</div>\n\n";
}

function display_name($name, $header='h2') {
    echo("\n<$header>$name</$header>\n\n");
}

function display_space() {
    echo("<p/>\n\n");
}

function display_row($td1, $td2) {
    echo("<tr valign=top>\n");
    echo("  <td width=50>$td1</td>\n");
    echo("  <td>$td2</td>\n");
    echo("<tr>\n");
}

function display_textarea_row($header, $annotation, $rows=2) {
    $name = strtolower($header);
    echo("<tr valign=top>\n");
    echo("  <td>$header</td>\n");
    echo("  <td><textarea name=$name rows=$rows cols=90 autofocus>$annotation</textarea></td>\n");
    echo("<tr>\n");
}

?>
