<?php

include 'connection.php';

$ANNOTATORS = "`ib-annotators`";
$ANNOTATIONS = "`ib-annotations-voxml`";
$TYPES = "`ib-annotations-icrels`";
$TASKS = "`ib-tasks`";


function db_connect() {
    global $host, $port, $user, $password, $db;
    //debug("$host:$port $user $password $db");
    $host = ($port == null) ? $host : "$host:$port";
    $conn = new mysqli($host, $user, $password, $db);
    if ($conn->connect_error)
        die("Connection failed: " . $conn->connect_error);
    return $conn;
}

function db_select($conn, $query) {
    //debug($query);
    $result = $conn->query($query);
    $objects = array();
    if ($result->num_rows > 0) {
        while($object = $result->fetch_object())
            $objects[] = $object;
        $result->free(); }
    return $objects;
}

function db_insert($conn, $query) {
    debug($query);
    $result = $conn->query($query);
}

function db_update($conn, $query) {
    debug($query);
    $result = $conn->query($query);
}

// Note that for db_get_type() we need to take into account that there can be
// multiple annotations, this is probably not the case for db_get_annotation()
function db_get_type($conn, $image_id) {
    // Get the most recent type for the image
    // *** Add other annotator types ***
    global $TYPES;
    $query = "SELECT * FROM $TYPES WHERE image_id='$image_id' ORDER BY timestamp DESC;";
    return db_select($conn, $query);
}

function db_get_annotation($conn, $image_id) {
    // Get the most recent annotation for the image
    global $ANNOTATIONS;
    $query = "SELECT * FROM $ANNOTATIONS WHERE image_id='$image_id' ORDER BY timestamp DESC LIMIT 1;";
    return db_select($conn, $query);
}

function db_get_annotations($conn, $image_id) {
    // Get all annotations for the image
    global $ANNOTATIONS;
    $query = "SELECT * FROM $ANNOTATIONS WHERE image_id='$image_id';";
    return db_select($conn, $query);
}

function db_get_all_annotations($conn) {
    global $ANNOTATIONS;
    $query = "SELECT * FROM $ANNOTATIONS;";
    return db_select($conn, $query);
}

function db_insert_type($connection, $file, $relations, $annotator) {
    global $TYPES;
    $query =
        "INSERT INTO $TYPES (image_id, relation, annotator)\n" .
        "VALUES ('$file', '$relations', '$annotator');";
    db_insert($connection, $query);
}

function db_insert_annotation($connection, $file, $annotation, $annotator) {
    global $ANNOTATIONS;
    $objects = $annotation->objects;
    $attributes = $annotation->attributes;
    $relations = $annotation->relations;
    $events = $annotation->events;
    $habitat = $annotation->habitat;
    $comment = $annotation->comment;
    $query =
        "INSERT INTO $ANNOTATIONS \n" .
        "(image_id, objects, attributes, relations, events, habitat, annotator, comment) \n" .
        "VALUES ('$file', '$objects', '$attributes', '$relations', '$events', '$habitat', '$annotator', '$comment');";
    db_insert($connection, $query);
}

function db_validate_annotator($connection, $annotator, $password) {
    global $ANNOTATORS;
    $query = "SELECT password FROM $ANNOTATORS WHERE annotator='$annotator';";
    $result = db_select($connection, $query);
    //debug($result);
    if (! $result) return false;
    return $result[0]->password == $password;
}

function db_get_tasks($connection, $annotator) {
    global $TASKS;
    $query = "SELECT * FROM $TASKS WHERE annotator='$annotator';";
    return db_select($connection, $query);
}

function db_update_task($connection, $task_id) {
    global $TASKS;
    $query = "UPDATE $TASKS SET done=1 WHERE id=$task_id;";
    return db_update($connection, $query);
}


?>
