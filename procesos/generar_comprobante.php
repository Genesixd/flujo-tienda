<?php
require('../fpdf/fpdf.php');
include "../conectar.inc.php";

$nrotramite = $_GET['nro'] ?? null;
if (!$nrotramite) {
    die("❌ Trámite no especificado.");
}

// Obtener información del trámite
$query = "
SELECT u.usuario AS cliente, p.nombre, p.precio, d.cantidad,
       (p.precio * d.cantidad) AS total,
       t.fecha_inicio, f.observacion, f.usuario AS cajero
FROM tramite t
JOIN usuario u ON u.id = t.cliente_id
JOIN detalle_pedido d ON d.nro_tramite = t.nro_tramite
JOIN producto p ON p.id = d.producto_id
JOIN flujoseguimiento f ON f.nro_tramite = t.nro_tramite AND f.proceso = 'cobraCliente'
WHERE t.nro_tramite = $nrotramite
LIMIT 1
";

$res = $conn->query($query);
$data = $res->fetch_assoc();

if (!$data) {
    die("❌ No se encontraron datos del trámite.");
}

// Crear PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',16);
$pdf->Cell(0,10,utf8_decode('🧾 Comprobante de Venta'),0,1,'C');
$pdf->Ln(5);

$pdf->SetFont('Arial','',12);
$pdf->Cell(0,10,'N° de Trámite: ' . $nrotramite,0,1);
$pdf->Cell(0,10,'Cliente: ' . $data['cliente'],0,1);
$pdf->Cell(0,10,'Fecha: ' . $data['fecha_inicio'],0,1);
$pdf->Cell(0,10,'Producto: ' . $data['nombre'],0,1);
$pdf->Cell(0,10,'Cantidad: ' . $data['cantidad'],0,1);
$pdf->Cell(0,10,'Precio unitario: Bs ' . $data['precio'],0,1);
$pdf->Cell(0,10,'Total: Bs ' . $data['total'],0,1);
$pdf->Cell(0,10,'Método de pago: ' . $data['observacion'],0,1);
$pdf->Cell(0,10,'Cajero: ' . $data['cajero'],0,1);

$pdf->Ln(10);
$pdf->Cell(0,10,'Gracias por su compra.',0,1,'C');

$pdf->Output();
