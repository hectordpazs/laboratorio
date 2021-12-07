<?php

require_once __DIR__ . '/../config/db.php';

function sanitizar($str) {
    global $db;

    return mysqli_real_escape_string($db, $str);
}

function agregarSQuotes($str) {
    return "'$str'";
}

function sanitizarArray($array) {
    global $db;
    $array_sano = [];

    for ($i=0; $i < count($array); $i++) {
        array_push($array_sano, sanitizar($array[$i]));
    }

    return $array_sano;
}

function selectAll($table) {
    global $db;

    $result = mysqli_query($db, "SELECT * FROM $table");

    if (!$result) {
        return false;
    }

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

function selectId($id, $table) {
    global $db;

    $result = mysqli_query($db, "SELECT * FROM $table WHERE id = '$id'");


    return mysqli_fetch_assoc($result);
}

function selectWhereEqual($table, $col, $equal) {
    global $db;

    $result = mysqli_query($db, "SELECT * FROM $table WHERE $col = '$equal'");

    if (!$result) {
        return false;
    }

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

function insert($table, $cols, $vals) {
    global $db;

    $cols_array = implode(',', $cols);
    $vals_array = implode(',', $vals);

    $result = mysqli_query($db, "INSERT INTO $table ($cols_array) VALUES ($vals_array)");

    if (!$result) {
        return false;
    }

    return mysqli_insert_id($db);
}

function update($id, $table, $cols, $vals) {
    global $db;

    $sql = "UPDATE $table SET ";

    for ($i = 0; $i < count($cols); $i++) {
        $sql .= "{$cols[$i]} = {$vals[$i]}";

        if ($i < count($cols) - 1) {
            $sql .= ', ';
        }
    }

    $sql .= "WHERE id = $id";

    $result = mysqli_query($db, $sql);

    return $result;
}

function delete($id, $table) {
    global $db;

    $result = mysqli_query($db, "DELETE FROM $table WHERE id = $id");

    return $result;
}