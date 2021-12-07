<?php 
require_once 'config/db.php';

function selectJoinAllExamenes() {
    global $db;

    $result = mysqli_query($db, 
    'SELECT ex.*, ext.nombre AS tipo_nombre, pac.nombre AS paciente_nombre, pac.apellido, pac.cedula, pac.email FROM examenes ex JOIN examen_tipos ext ON ex.tipo_id = ext.id JOIN pacientes pac ON ex.paciente_id = pac.id');

    if (!$result) {
        return false;
    }

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

function selectJoinIdExamenes($id) {
    global $db;

    $result = mysqli_query($db, 
    "SELECT ex.*, ext.nombre AS tipo_nombre, pac.nombre AS paciente_nombre, pac.apellido, pac.cedula, pac.email FROM examenes ex JOIN examen_tipos ext ON ex.tipo_id = ext.id JOIN pacientes pac ON ex.paciente_id = pac.id WHERE ex.id = $id");

    if (!$result) {
        return false;
    }

    return mysqli_fetch_assoc($result);
}