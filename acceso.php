<?php
require_once 'config/noauth.php';

if ($_POST['acceso']) {
    require_once 'sql/sql_functions.php';
    require_once 'utils/validacion.php';


    $usuario = $_POST['usuario'];
    $password = $_POST['password'];


    $usuario_array = selectWhereEqual('usuarios', 'usuario', sanitizar($usuario));

    if ($usuario_array && password_verify($password, $usuario_array[0]['password'])) {
        $_SESSION['usuario'] = $usuario_array[0]['id'];
        header('Location: index.php');
        die();
    } else {
        $_SESSION['mensaje'] = '<div class=\"alert alert-danger\" role=\"alert\">Usuario o contraseña incorrecta</div>';
        header('Location: acceso.php');
        die();
    }
}
?>

<?php
require_once 'plantillas/head.php';
?>

<div class="container min-vh-100 d-flex">

    <div class="card col-sm-12 col-md-8 col-lg-6 m-auto">
        <div class="card-header">
            Acceso al Sistema
        </div>
        <div class="card-body">
            <?php
            if ($_SESSION['mensaje']) {
                echo $_SESSION['mensaje'];
                unset($_SESSION['mensaje']);
            }
            ?>
            <form method="post" action="acceso.php">
                <input type="text" name="usuario" class="form-control mb-2" placeholder="Usuario">
                <input type="password" name="password" class="form-control mb-2" placeholder="Password">
                <div class="w-100 text-center">
                    <a href="registro.php">¿No tiene cuenta? ¡Cree una aquí!</a>
                </div>
                <input type="submit" class="btn btn-primary" name="acceso" value="Acceder">
            </form>
        </div>
    </div>
</div>


<?php
require_once 'plantillas/foot.php';
?>