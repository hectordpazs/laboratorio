<?php
session_start();

require_once __DIR__ .'/../utils/validacion.php';

if (!$_SESSION['usuario']) {
    header('Location: acceso.php');
    die();
}

require_once  __DIR__ .'/../sql/sql_functions.php';

$usuario_actual = selectId(sanitizar($_SESSION['usuario']), 'usuarios');

if (!$usuario_actual) {
    session_destroy();

    header('Location: acceso.php');
    die();
}

define('LAB_EMAIL', 'heectordpazs@gmail.com');
