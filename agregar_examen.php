<?php
require_once 'config/auth.php';

if ($usuario_actual['rol'] !== 'secretaria') {
    header('Location: index.php');
    exit();
}

$pacientes = selectAll('pacientes');
$examen_tipos = selectAll('examen_tipos');

if ($_POST['agregar-examen']) {
    $fecha = $_POST['fecha'];
    $hora = $_POST['hora'];
    $descripcion = $_POST['descripcion'];

    if (strlen($descripcion) < 30 || strlen($descripcion) > 255) {
        $_SESSION['mensaje'] = "<div class=\"alert alert-danger\" role=\"alert\">Descripción debe tener entre 30 y 255 letras</div>";
        header('Location: agregar_examen.php');
        die();
    }

    if (!DateTime::createFromFormat('Y-m-d H:i:s', "$fecha $hora:00")) {
        $_SESSION['mensaje'] = "<div class=\"alert alert-danger\" role=\"alert\">Fecha inválidar</div>";
        header('Location: agregar_examen.php');
        die();
    }

    $fecha_sana = sanitizar($fecha);
    $hora_sana = sanitizar($hora);

    $result = mysqli_query($db, "SELECT * FROM examenes WHERE fecha = '$fecha' AND '$hora_sana'");

    if (!$result) {
        $error = mysqli_error($db);
        $_SESSION['mensaje'] = "<div class=\"alert alert-danger\" role=\"alert\">$error</div>";
        header('Location: agregar_examen.php');
        die();
    }

    $id = insert('examenes', ['fecha', 'hora', 'descripcion', 'tipo_id', 'estado', 'paciente_id'], [
        agregarSQuotes(sanitizar($fecha)),
        agregarSQuotes(sanitizar($hora)),
        agregarSQuotes(sanitizar($descripcion)),
        agregarSQuotes(sanitizar($_POST['examen_tipo'])),
        agregarSQuotes('creado'),
        agregarSQuotes(sanitizar($_POST['paciente']))
    ]);

    if (!$id) {
        $error = mysqli_error($db);
        $_SESSION['mensaje'] = "<div class=\"alert alert-danger\" role=\"alert\">$error</div>";
        header('Location: agregar_examen.php');
        die();
    }

    $_SESSION['mensaje'] = "<div class=\"alert alert-success\" role=\"alert\">Examen registrado</div>";
        header('Location: examenes.php');
        die();
}

?>

<?php
require_once 'plantillas/head.php';
?>

<div class="container min-vh-100 mt-2">
    <div class="card col-sm-12 col-md-8 col-lg-6 m-auto">
        <div class="card-header">
            Registro de examen
        </div>
        <div class="card-body">
            <?php
            if ($_SESSION['mensaje']) {
                echo $_SESSION['mensaje'];
                unset($_SESSION['mensaje']);
            }
            ?>
            <form method="post" action="agregar_examen.php">
                <label class="form-label">Tipo de examen</label>
                <div class="input-group">
                    <select name="examen_tipo" class="form-select">
                        <option value="">Elija el tipo de examen</option>
                        <?php foreach ($examen_tipos as $examen_tipo) {?>
                            <option value="<?php echo $examen_tipo['id']?>">
                                <?php echo $examen_tipo['nombre']?>
                            </option>
                        <?php } ?>
                    </select>
                    <a class="btn btn-success" href="agregar_tipo_examen.php">Nuevo</a>
                </div>
                <label class="form-label">Paciente</label>
                <div class="input-group">
                    <select name="paciente" class="form-select">
                        <option value="">Elija el paciente</option>
                        <?php foreach ($pacientes as $paciente) { ?>
                            <option value="<?php echo $paciente['id']; ?>">
                                <?php echo "{$paciente['nombre']} {$paciente['apellido']}"; ?>
                                (<?php echo $paciente['cedula']; ?>)
                            </option>
                        <?php } ?>
                    </select>
                    <a class="btn btn-success" href="agregar_paciente.php">Nuevo</a>
                </div>
                <label>Fecha</label>
                <input type="date" name="fecha" class="form-control">
                <label>Hora</label>
                <input type="time" name="hora" class="form-control">
                <label>Descripción</label>
                <textarea name="descripcion" cols="30" rows="10" class="form-control"></textarea>
                <input type="submit" class="btn btn-primary" name="agregar-examen" value="Registrarse">
            </form>
        </div>
    </div>
</div>


<?php
require_once 'plantillas/foot.php';
?>