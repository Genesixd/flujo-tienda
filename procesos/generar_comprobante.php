<?php
require_once("../fpdf/fpdf.php");

include "../conectar.inc.php";

$nrotramite = $_GET["nro"] ?? null;
if (!$nrotramite) {
    die("❌ Trámite no especificado.");
}

// Obtener datos del cliente y la venta
$tramite = $conn->query("
    SELECT u.usuario AS cliente, t.fecha_inicio
    FROM tramite t
    JOIN usuario u ON u.id = t.cliente_id
    WHERE t.nro_tramite = $nrotramite
")->fetch_assoc();

$detalle = $conn->query("
    SELECT p.nombre, d.cantidad, p.precio, (p.precio * d.cantidad) AS total
    FROM detalle_pedido d
    JOIN producto p ON p.id = d.producto_id
    WHERE d.nro_tramite = $nrotramite
")->fetch_assoc();

if (!$tramite || !$detalle) {
    die("❌ Datos no encontrados.");
}

// Crear PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);

// Título
$pdf->Cell(0, 10, "Comprobante de Venta", 0, 1, 'C');
$pdf->Ln(5);

// Info general
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, "Trámite Nro: $nrotramite", 0, 1);
$pdf->Cell(0, 10, "Cliente: " . $tramite['cliente'], 0, 1);
$pdf->Cell(0, 10, "Fecha: " . $tramite['fecha_inicio'], 0, 1);
$pdf->Ln(5);

// Detalles de la venta
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(80, 10, "Producto", 1);
$pdf->Cell(30, 10, "Cantidad", 1);
$pdf->Cell(30, 10, "Precio", 1);
$pdf->Cell(40, 10, "Total", 1);
$pdf->Ln();

$pdf->SetFont('Arial', '', 12);
$pdf->Cell(80, 10, $detalle['nombre'], 1);
$pdf->Cell(30, 10, $detalle['cantidad'], 1);
$pdf->Cell(30, 10, number_format($detalle['precio'], 2), 1);
$pdf->Cell(40, 10, number_format($detalle['total'], 2), 1);
$pdf->Ln(20);

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, "Gracias por su compra", 0, 1, 'C');

// Salida del PDF
$pdf->Output("I", "Comprobante_Tramite_$nrotramite.pdf");
