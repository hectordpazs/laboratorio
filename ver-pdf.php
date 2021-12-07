<?php
require_once 'config/auth.php';

require_once 'sql/examenes_functions.php';

$examen_id = $_GET['examen'];

$examen = selectJoinIdExamenes($examen_id);

if (!$examen) {
    exit('Examen inexistente');
}

require_once 'reporte_resultados_examen.php';

header('Content-Type: application/pdf');
echo $pdf;
