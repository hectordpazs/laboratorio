<?php
require_once 'config/auth.php';


if ($_POST['salir']) {
    session_destroy();
} 

header('Location: acceso.php');
die();