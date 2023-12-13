<?php
require 'tcpdf/tcpdf.php';

// Crear una instancia de TCPDF
$pdf = new TCPDF();

// Establecer metadatos del documento
$pdf->SetCreator('Ejemplo TCPDF');
$pdf->SetAuthor('Tu Nombre');
$pdf->SetTitle('Documento PDF con TCPDF');

// Agregar una página al PDF
$pdf->AddPage();

// Agregar texto al PDF
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, '¡Hola, este es mi documento PDF con TCPDF!', 0, 1);

// Agregar una imagen al PDF
$imagePath = 'ruta-de-la-imagen.jpg'; // Reemplaza con la ruta de tu imagen
$pdf->Image($imagePath, 10, 30, 80, 60, 'JPEG'); // Parámetros: URL/ruta, x, y, ancho, alto, formato

// Guardar el PDF en el servidor o mostrarlo en el navegador
$pdf->Output('documento_con_imagen.pdf', 'I');
?>