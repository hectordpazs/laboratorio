<?php

require_once 'config/auth.php';

require_once 'sql/examenes_functions.php';

$examen = selectJoinIdExamenes(agregarSQuotes(sanitizar($_GET['id'])));

if (!$examen) {
    $_SESSION['mensaje'] = '<div class="alert alert-danger" role="alert">Examen inexistente</div>';
    header('Location: examenes.php');
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
     $_SESSION['mensaje'] = "<div class=\"alert alert-danger\" role=\"alert\">Resultados de examen no se pudieron enviar: $error</div>";
     header('Location: examenes.php');
     die();
 }

 $_SESSION['mensaje'] = "<div class=\"alert alert-success\" role=\"alert\">Resultados de examen enviados</div>";
 header('Location: examenes.php');
 die();