<?php
require_once 'config/auth.php';


if ($_POST['agregar-paciente']) {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $email = $_POST['email'];
    $cedula = $_POST['cedula'];

    if (!$nombre || !$apellido) {
        $_SESSION['mensaje'] = '<div class="alert alert-danger" role="alert">Nombre o apellido inválidos</div>';
        header('Location: agregar_paciente.php');
        die();
    }

    if (!is_numeric($cedula) || strlen($cedula) < 7) {
        $_SESSION['mensaje'] = '<div class="alert alert-danger" role="alert">Cédula inválida</div>';
        header('Location: agregar_paciente.php');
        die();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['mensaje'] = '<div class="alert alert-danger" role="alert">Email inválido</div>';
        header('Location: agregar_paciente.php');
        die();
    }

    $id = insert('pacientes', ['nombre', 'apellido', 'cedula', 'email'], [
        agregarSQuotes(sanitizar($nombre)),
        agregarSQuotes(sanitizar($apellido)),
        agregarSQuotes(sanitizar($cedula)),
        agregarSQuotes(sanitizar($email))
    ]);

    if ($id) {
        $_SESSION['mensaje'] = "<div class=\"alert alert-success\" role=\"alert\">Registro exitoso de paciente!</div>";
        header('Location: agregar_examen.php');
        die();
    } else {
        $error = mysqli_error($db);
        $_SESSION['mensaje'] = "<div class=\"alert alert-danger\" role=\"alert\">$error</div>";
        header('Location: agregar_paciente.php');
        die();
    }
}

$pacientes = selectAll('pacientes');

?>

<?php
require_once 'plantillas/head.php';
?>

<div class="container min-vh-100 mt-2">
    <div class="card col-sm-12 col-md-8 col-lg-6 m-auto">
        <div class="card-header">
            Registro de paciente
        </div>
        <div class="card-body">
            <?php
            if ($_SESSION['mensaje']) {
                echo $_SESSION['mensaje'];
                unset($_SESSION['mensaje']);
            }
            ?>
            <form method="post" action="agregar_paciente.php">
                <label class="form-label">Nombre de paciente</label>
                <input type="text" name="nombre" class="form-control">
                <label class="form-label">Apellido de paciente</label>
                <input type="text" name="apellido" class="form-control">
                <label class="form-label">Cédula de paciente</label>
                <input type="text" name="cedula" class="form-control">
                <label class="form-label">Email de paciente</label>
                <input type="email" name="email" class="form-control">
                <input type="submit" class="btn btn-primary mt-2" name="agregar-paciente" value="Registrar paciente">
            </form>
        </div>
    </div>
</div>

<?php
require_once 'plantillas/foot.php';
?>