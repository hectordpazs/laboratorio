<?php
require_once 'config/noauth.php';

if ($_POST['registro']) {
    require_once 'sql/sql_functions.php';
    require_once 'utils/validacion.php';


    $usuario = $_POST['usuario'];
    $password = $_POST['password'];
    $rol = $_POST['rol'];

    if (validarRol($rol)) {
        $usuario_array = selectWhereEqual('usuarios', 'usuario', sanitizar($usuario));

        if ($usuario_array === []) {
            $id = insert('usuarios', ['usuario', 'password', 'rol'], [
                agregarSQuotes(sanitizar($usuario)),
                agregarSQuotes(sanitizar(password_hash($password, PASSWORD_BCRYPT))),
                agregarSQuotes(sanitizar($rol))
            ]);

            if ($id) {
                $_SESSION['usuario'] = $id;
                header('Location: index.php');
                die();
            } else {
                $error = mysqli_error($db);
                $_SESSION['mensaje'] = "<div class=\"alert alert-danger\" role=\"alert\">$error</div>";;
                header('Location: registro.php');
                die();
            }
        } else if ($usuario_array === false) {
            $error = mysqli_error($db);
            $_SESSION['mensaje'] = "<div class=\"alert alert-danger\" role=\"alert\">$error</div>";
            header('Location: acceso.php');
            die();
        } else {
            $_SESSION['mensaje'] = "<div class=\"alert alert-danger\" role=\"alert\">Usuario ya existe</div>";;
            header('Location: acceso.php');
            die();
        }
    } else {
        $_SESSION['mensaje'] = '';
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
            Registro al Sistema
        </div>
        <div class="card-body">
            <?php
            if ($_SESSION['mensaje']) {
                echo $_SESSION['mensaje'];
                unset($_SESSION['mensaje']);
            }
            ?>
            <form method="post" action="registro.php">
                <input type="text" name="usuario" class="form-control mb-2" placeholder="Usuario">
                <input type="password" name="password" class="form-control mb-2" placeholder="Password">
                <select name="rol" class="form-select mb-2">
                    <option value="">Elija un rol</option>
                    <option value="secretaria">Secretaria</option>
                    <option value="bioanalista">Bioanalista</option>
                </select>
                <div class="w-100 text-center">
                    <a href="acceso.php">¿Ya tiene cuenta? ¡Acceda aquí!</a>
                </div>
                <input type="submit" class="btn btn-primary" name="registro" value="Registrarse">
            </form>
        </div>
    </div>
</div>


<?php
require_once 'plantillas/foot.php';
?>