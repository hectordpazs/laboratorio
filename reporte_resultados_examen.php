<?php

require_once 'fpdf184/fpdf.php';

$fpdf = new FPDF();
$fpdf->AddPage();
$fpdf->SetFont('Arial','B', 10);
$y = $fpdf->GetY();
$x = $fpdf->GetX();
$fpdf->MultiCell(160, 25, "Paciente: {$examen['paciente_nombre']} {$examen['apellido']}");
$last_y = $fpdf->GetY();
$fpdf->SetXY($x + 160, $y);
$fpdf->Cell(85, 25, "C.I. {$examen['cedula']}");
$fpdf->SetFont('Arial','B', 12);
$fpdf->SetXY(0, $y+10);
$fpdf->Cell(210, 25, "RESULTADOS DE EXAMEN #{$examen['id']}", 
    0, 0, 'C');
$fpdf->SetXY(0, $y+20);
$fpdf->SetFont('Arial','B', 10);
$fpdf->Cell(10, 25);
$fpdf->Cell(160, 25, utf8_decode('DESCRIPCIÓN'));
$fpdf->SetXY(0, $y+35);
$fpdf->SetFont('Arial','', 10);
$fpdf->Cell(10, 25);
$fpdf->MultiCell(180, 25, utf8_decode($examen['descripcion']), 1);
$fpdf->SetFont('Arial','B', 10);
$fpdf->Cell(160, 25, utf8_decode('RESULTADOS'));
$y = $fpdf->GetY();
$fpdf->SetFont('Arial','', 10);
$fpdf->SetXY(10, $y+25);
$fpdf->MultiCell(180, 25, utf8_decode($examen['resultados']), 1);
$y = $fpdf->GetY();
$fpdf->SetXY(0, $y+25);
$pdf = $fpdf->Output('', 'S');