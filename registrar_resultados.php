<?php
require_once 'config/auth.php';

if ($usuario_actual['rol'] !== 'bioanalista') {
    header('Location: index.php');
    exit();
}

require_once 'sql/examenes_functions.php';

$examen = null;

if ($_POST['resultado']) {
    $examen = selectJoinIdExamenes(agregarSQuotes(sanitizar($_POST['id'])));

    if (!$examen) {
        $_SESSION['mensaje'] = '<div class="alert alert-danger" role="alert">Examen inexistente</div>';
        header('Location: examenes.php');
        die();
    }

    $resultados = $_POST['resultados'];

    if (strlen($resultados) < 30 || strlen($resultados) > 1500) {
        $_SESSION['mensaje'] = '<div class="alert alert-danger" role="alert">Resultados deben tener entre 30 y 1500 letras</div>';
        header('Location: registrar_resultados.php?id' . $examen['id']);
        die();
    }

    $result = update($examen['id'], 'examenes',  ['resultados', 'realizado_por', 'estado'], [
        agregarSQuotes(sanitizar($resultados)),
        agregarSQuotes(sanitizar($usuario_actual['id'])),
        "'completado'"
    ]);

    if (!$result) {
        $error = mysqli_error($db);
        $_SESSION['mensaje'] = "<div class=\"alert alert-danger\" role=\"alert\">$error</div>";
        header('Location: registrar_resultados.php?id' . $examen['id']);
        die();
    }

    // carriage return type (we use a PHP end of line constant)
    $eol = PHP_EOL;

    // attachment name
    $filename = "test.pdf";

    // a random hash will be necessary to send mixed content
    $separator = md5(time());

    require_once 'reporte_resultados_examen.php';

    $attachment = chunk_split(base64_encode($pdf));

    $messages = '';

    $from = LAB_EMAIL;

    $body = "--" . $separator . $eol;
    $body .= "Content-Transfer-Encoding: 7bit" . $eol . $eol;
    $body .= "This is a MIME encoded message." . $eol;

    // message
    $body .= "--" . $separator . $eol;
    $body .= "Content-Type: text/html; charset=\"iso-8859-1\"" . $eol;
    $body .= "Content-Transfer-Encoding: 8bit" . $eol . $eol;
    $body .= $message . $eol;

    // attachment
    $body .= "--" . $separator . $eol;
    $body .= "Content-Type: application/octet-stream; name=\"" . $filename . "\"" . $eol;
    $body .= "Content-Transfer-Encoding: base64" . $eol;
    $body .= "Content-Disposition: attachment" . $eol . $eol;
    $body .= $attachment . $eol;
    $body .= "--" . $separator . "--";

    // main header
    $headers  = "From: " . $from . $eol;
    $headers .= "MIME-Version: 1.0" . $eol;
    $headers .= "Content-Type: multipart/mixed; boundary=\"" . $separator . "\"";

    $result = mail(
        $examen['email'],
        "RESULTADOS DE EXAMEN DEL {$examen['fecha']} a las {$examen['hora']}",
        $body,
        $headers
    );

    if (!$result) {
        $_SESSION['mensaje'] = "<div class=\"alert alert-danger\" role=\"alert\">Resultados de examen listos, mas no se pudieron enviar</div>";
        header('Location: examenes.php');
        die();
    }

    $_SESSION['mensaje'] = "<div class=\"alert alert-success\" role=\"alert\">Resultados de examen listos</div>";
    header('Location: examenes.php');
    die();
} else {
    $examen = selectJoinIdExamenes(agregarSQuotes(sanitizar($_GET['id'])));

    if (!$examen) {
        $_SESSION['mensaje'] = '<div class="alert alert-danger" role="alert">Examen inexistente</div>';
        header('Location: examenes.php');
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
            Registro de resultados
        </div>
        <div class="card-body">
            <?php
            if ($_SESSION['mensaje']) {
                echo $_SESSION['mensaje'];
                unset($_SESSION['mensaje']);
            }
            ?>
            <form method="post" action="registrar_resultados.php">
                <input type="hidden" name="id" value="<?php echo $examen['id']; ?>">
                <label class="form-label">Tipo de examen</label>
                <div class="input-group">
                    <input type="text" readonly class="form-control" value="<?php echo $examen['tipo_nombre']; ?>">
                </div>
                <label class="form-label">Paciente</label>
                <div class="input-group">
                    <input type="text" readonly class="form-control" value="<?php echo "{$examen['nombre']} {$examen['apellido']} ({$examen['cedula']})"; ?>">
                </div>
                <label>Fecha</label>
                <input type="text" readonly class="form-control" value="<?php echo "{$examen['fecha']} {$examen['hora']}"; ?>">

                <label>Descripción</label>
                <textarea name="descripcion" cols="30" rows="10" class="form-control" readonly>
                    <?php echo $examen['descripcion']; ?>
                </textarea>
                <label>Resultados</label>
                <textarea name="resultados" id="" cols="30" rows="10" class="form-control"></textarea>
                <input type="submit" class="btn btn-primary" name="resultado" value="Registrarse">
            </form>
        </div>
    </div>
</div>


<?php
require_once 'plantillas/foot.php';
?>