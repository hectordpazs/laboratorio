<?php
require_once 'config/auth.php';

if ($usuario_actual['rol'] !== 'secretaria') {
    header('Location: index.php');
    exit();
}

if ($_POST['agregar-tipo-examen']) {
    $nombre = $_POST['nombre'];

    if (!$nombre || strlen($nombre) < 4 || strlen($nombre) > 100) {
        $_SESSION['mensaje'] = "<div class=\"alert alert-danger\" role=\"alert\">Tipo de examen debe tener entre 4 y 100 letras</div>";
        header('Location: agregar_tipo_examen.php');
        die();
    }

    $tipos_examenes = selectWhereEqual('examen_tipos', 'nombre', $nombre);

    if ($tipos_examenes !== false && count($tipos_examenes) > 0) {
        $_SESSION['mensaje'] = "<div class=\"alert alert-danger\" role=\"alert\">Tipo de examen ya existe</div>";
        header('Location: agregar_tipo_examen.php');
        die();
    } else if ($tipos_examenes === false) {
        $error = mysqli_error($db);
        $_SESSION['mensaje'] = "<div class=\"alert alert-danger\" role=\"alert\">$error</div>";
        header('Location: agregar_tipo_examen.php');
        die();
    } else {
        $id = insert('examen_tipos', ['nombre'], [agregarSQuotes(sanitizar($nombre))]);
    
        if (!$id) {
            $error = mysqli_error($db);
            $_SESSION['mensaje'] = "<div class=\"alert alert-danger\" role=\"alert\">$error</div>";
            header('Location: agregar_tipo_examen.php');
            die();
        }


        $_SESSION['mensaje'] = "<div class=\"alert alert-success\" role=\"alert\">Nuevo tipo de examen disponible</div>";
        header('Location: agregar_examen.php');
        die();        

    }

  

}

?>


<?php
require_once 'plantillas/head.php';
?>


<div class="container min-vh-100 mt-2">
    <div class="card col-sm-12 col-md-8 col-lg-6 m-auto">
        <div class="card-header">
            Registro de tipo de examen
        </div>
        <div class="card-body">
            <?php
            if ($_SESSION['mensaje']) {
                echo $_SESSION['mensaje'];
                unset($_SESSION['mensaje']);
            }
            ?>
            <form method="post" action="agregar_tipo_examen.php">
                <label class="form-label">Nombre de tipo de examen</label>
                <input type="text" name="nombre" class="form-control mb-2">

                <input type="submit" class="btn btn-primary" name="agregar-tipo-examen" value="Registrarse">
            </form>
        </div>
    </div>
</div>

<?php
require_once 'plantillas/foot.php';
?>