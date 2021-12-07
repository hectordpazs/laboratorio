<?php
require_once 'config/auth.php';

require_once 'sql/examenes_functions.php';

$examenes = selectJoinAllExamenes();
?>


<?php
require_once 'plantillas/head.php';
?>

<div class="container">
    <?php
    if ($_SESSION['mensaje']) {
        echo $_SESSION['mensaje'];
        unset($_SESSION['mensaje']);
    }
    ?>
    <?php if ($usuario_actual['rol'] === 'secretaria') { ?>
        <a href="agregar_examen.php" class="btn btn-success">Agregar examen</a>
    <?php } ?>
    <a href="agregar_examen.php"></a>
    <table class="w-100">
        <tr>
            <th>ID</th>
            <th>Paciente</th>
            <th>Tipo</th>
            <th>Fecha</th>
            <th>Estado</th>
        </tr>
        <?php foreach ($examenes as $examen) { ?>
            <tr>
                <td>
                    <?php echo $examen['id']; ?>
                </td>
                <td>
                    <?php echo "{$examen['paciente_nombre']} {$examen['apellido']} ({$examen['cedula']})"; ?>
                </td>
                <td>
                    <?php echo $examen['tipo_nombre']; ?>
                </td>
                <td>
                    <?php echo "{$examen['fecha']} {$examen['hora']}"; ?>
                </td>
                <td>
                    <?php if ($usuario_actual['rol'] === 'bioanalista' && $examen['estado'] === 'creado') { ?>
                        <a href="registrar_resultados.php?id=<?php echo $examen['id'] ?>" class="btn btn-primary">
                            Registrar resultados
                        </a>
                    <?php } else { ?>
                        <?php if (!$examen['enviado']) { ?>
                            <a href="enviar_email.php?id=<?php echo $examen['id'] ?>" class="btn btn-danger">
                                Enviar resultados
                            </a>
                            <a href="ver-pdf.php?examen=<?php echo $examen['id'] ?>" class="btn btn-danger"
                                target="_blank">
                                Ver resultados
                            </a>
                        <?php } else { ?>
                            <?php echo $examen['estado']; ?>
                        <?php } ?>
                    <?php } ?>
                </td>
            </tr>
        <?php } ?>
    </table>
</div>


<?php
require_once 'plantillas/foot.php';
?>