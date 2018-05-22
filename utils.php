<?php

$DEBUG = false;

function debug_on() {
    global $DEBUG;
    $DEBUG = true;
    ini_set('display_errors', 'On');
    error_reporting(E_WARNING | E_STRICT);
}

function debug($var) {
    global $DEBUG;
    if (! $DEBUG) return;
    echo "<pre class=dotted>\n";
    if (is_array($var) || is_object($var))
        print_r($var);
        //echo var_export($var);
    else
        echo "$var\n";
    echo "</pre>\n";
}

function debug_vars() {
    debug($_GET);
    debug($_SESSION);
}

function read_term_index() {
    $index_file = "term_index.tab";
    $index = array();
    $lines = file($index_file);
    foreach ($lines as $line) {
        $fields = explode("\t", $line);
        $index[$fields[0]] = $fields[1]; }
    return $index;
}

function login($connection) {
    // Login with the credentials handed in and set the annotator and login_failed
    // session variables as appropriate.
    global $_GET, $_SESSION;
    $result = db_validate_annotator($connection, $_GET['login'], $_GET['password']);
    if ($result) {
        $_SESSION['annotator'] = $_GET['login'];
        $_SESSION['login_failed'] = false; }
    else {
        $_SESSION['login_failed'] = true; }
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

    function display_images($start, $length, $connection) {
        $slice = array_slice($this->files, $start, $tart + $length);
        foreach ($slice as $name) {
            $image = new Image($name, $this->data, $connection);
            $this->display_image($image); }
     }

    function display_annotated_images($connection) {
        foreach ($this->files as $name) {
            $image = new Image($name, $this->data, $connection);
            if ($image->has_annotation())
                $this->display_image($image); }
    }

    function display_commented_images($connection) {
        foreach ($this->files as $name) {
            $image = new Image($name, $this->data, $connection);
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

    function display_list_of_images($images, $connection) {
        foreach ($this->files as $name) {
            if (in_array($name, $images)) {
                $image = new Image($name, $this->data, $connection);
                $this->display_image($image); }
        }
    }
}


class Image {

    /*
    An instance of Image has a name which serves as a unique identifiers and
    is associated with an image file (a jpg) and a caption file. In addition,
    on initialization the databse will be checked for any annotations.
    */

    function __construct($name, $data, $connection) {
        $this->name = $name;
        $this->image_file = $data->IMAGES .  $name . '.jpg';
        $this->caption_file = $data->CAPTIONS . $name . '.txt';
        $this->caption = file_get_contents($this->caption_file);
        $this->annotation = null;
        $this->type = null;
        $annotations = db_get_annotation($connection, $name);
        if ($annotations)
            $this->annotation = $annotations[0];
        $types = db_get_type($connection, $name);
        if ($types)
            $this->type = $types[0]->type;
    }

    function has_annotation() {
        return ! is_null($this->annotation);
    }

    function display($annotations=true) {
        echo("<table width=800 cellpadding=5>\n\n");
        echo("<tr><td><img src=$this->image_file></td></tr>\n\n");
        echo("<tr><td>$this->caption</td></tr>\n\n");
        echo("</table>\n\n");
        if ($annotations)
            $this->display_annotations();
    }

    function display_annotation() {
        if ($this->has_annotation() && $this->annotation != null) {
            echo "<p><table class=indented width=800 cellpadding=5 cellspacing=0 border=1>\n";
            display_row('type',  $this->type);
            display_row('objects',  $this->annotation->objects);
            display_row('attributes', $this->annotation->attributes);
            display_row('relations', $this->annotation->relations);
            display_row('events',  $this->annotation->events);
            display_row('habitat', $this->annotation->habitat);
            display_row('comments', $this->annotation->comment);
            echo "</table></p>\n"; }
    }

    function display_annotations() {
        if (($this->has_annotation() && $this->annotation != null) || $this->type != null) {
            echo "<p><table class=indented width=800 cellpadding=5 cellspacing=0 border=1>\n";
            //display_row('type',  $this->type);
            if ($this->has_annotation() && $this->annotation != null) {
                display_row('objects',  $this->annotation->objects);
                display_row('attributes', $this->annotation->attributes);
                display_row('relations', $this->annotation->relations);
                display_row('events',  $this->annotation->events);
                display_row('habitat', $this->annotation->habitat);
                display_row('comments', $this->annotation->comment); }
            echo "</table></p>\n"; }
    }

    function display_image_caption_relation_form($action, $mode, $relations) {
        echo("<form action=$action method=get class=indented>\n\n");
        echo("<input type=hidden name=file value=$this->name />\n");
        if ($mode != null)
            echo("<input type=hidden name=mode value=$mode />\n");
        echo("<input type=hidden name=save_relation value=1 />\n");
        echo("<div class=bordered>\n");
        echo("<table>\n");
        display_radio_button($mode, $relations);
        echo("</table>\n");
        echo("</div>\n");
        echo("</form>\n");
    }

    function display_voxml_form($mode) {
        echo("<form action=annotate_image.php method=get class=indented>\n\n");
        echo("<input type=hidden name=file value=$this->name />\n");
        echo("<input type=hidden name=mode value=$mode />\n");
        echo("<input type=hidden name=save_annotation value=1/>\n");
        echo("<div class=bordered>\n");
        echo("<table>\n");
        display_textarea_row('Objects', $this->annotation->objects);
        display_textarea_row('Attributes', $this->annotation->attributes);
        display_textarea_row('Relations', $this->annotation->relations);
        display_textarea_row('Events', $this->annotation->eventss);
        display_textarea_row('Habitat', $this->annotation->habitat);
        display_textarea_row('Comment', $this->annotation->comment, 4);
        display_row('&nbsp;', "<input class=button type=submit value='Save Annotation'>");
        echo("</table>\n");
        echo("</div>\n");
        echo("</form>\n");
    }

}


function display_file($data, $name, $details) {
    $image = new Image($name, $data);
    //$annotations = read_annotations($image->annotation_file);
    echo("<h1>Image Editor - $name</h1>\n");
    display_navigation(
        array(array('index.php', 'Back home'), array('list.php', 'Back to list')));
    //display_name($name, 'h4');
    $image->display($annotations=false);
    display_space();
    display_form($name, $image->annotation);
}

function display_navigation($targets) {
    echo "\n<div class=navigation>";
    foreach ($targets as $target) {
        $url = $target[0];
        $text = $target[1];
        echo "\n| <a href=$url>$text</a>"; }
    echo " |\n</div>\n\n";
}

function display_name($name, $header='h2') {
    echo("\n<$header>$name</$header>\n\n");
}

function display_space() {
    echo("<p/>\n\n");
}

function display_row($td1, $td2, $pre=true) {
    $preopen = $pre ? '<pre>' : '';
    $preclose = $pre ? '</pre>' : '';
    echo("<tr valign=top>\n");
    echo("  <td width=50>$preopen$td1$preclose</td>\n");
    echo("  <td>$preopen$td2$preclose</td>\n");
    echo("</tr>\n");
}

function display_textarea_row($header, $annotation, $rows=2) {
    $name = strtolower($header);
    echo("<tr valign=top>\n");
    echo("  <td>$header</td>\n");
    echo("  <td><textarea name=$name rows=$rows cols=90 autofocus>$annotation</textarea></td>\n");
    echo("</tr>\n");
}

function display_radio_button($header, $values) {
    echo("<tr valign=top height=50>\n");
    echo("  <td colspan=2>$header&nbsp;(select all that apply)</td>\n");
    echo("</tr>\n");
    echo("<tr valign=top height=50>\n");
    echo("  <td>\n");
    foreach ($values as $value) {
        echo "    <input type=radio name=$value value=1>$value\n"; }
    echo("  </td>\n");
    echo("  <td><input class=button type=submit value='Save $header'></td>\n");
    echo("</tr>\n");
}

function display_login_form($action, $file, $login_failed) {
    if ($login_failed)
        echo "<h3 class=indented>Login failed, try again...</h3>\n\n";
    else
        echo "<h3 class=indented>You need to log in before you can annotate.</h3>\n\n";
    echo("<form action=$action method=get class=indented>\n\n");
    if ($file != null)
      echo("<input type=hidden name=file value=$file />\n");
    echo("<input type=hidden name=logging_in value=1 />\n");
    echo("<div class=bordered>\n");
    echo("<table>\n");
    display_row('login', '<input name=login />', $pre=false);
    display_row('password', '<input name=password />', $pre=false);
    echo("</table>\n");
    display_space();
    echo("<input class=button type=submit value='Submit'>\n\n");
    echo("</div>\n");
    echo("</form>\n");
}


?>
