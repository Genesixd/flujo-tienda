<?php
session_start();
include "../conectar.inc.php";

// Verificar que sea VENDEDOR
if ($_SESSION["rol"] !== "VENDEDOR") {
    echo "<p style='color:red;'>❌ Acceso denegado.</p>";
    exit;
}

// Obtener nro_tramite desde GET
$nrotramite = $_GET["nro"] ?? null;
if (!$nrotramite) {
    echo "<p style='color:red;'>❌ Trámite no especificado.</p>";
    exit;
}

// Cargar detalle del pedido
$query = "
    SELECT p.nombre, p.precio, d.cantidad, (p.precio * d.cantidad) AS total
    FROM detalle_pedido d
    JOIN producto p ON p.id = d.producto_id
    WHERE d.nro_tramite = $nrotramite
";
$pedido = $conn->query($query)->fetch_assoc();

if (!$pedido) {
    echo "<p style='color:red;'>❌ No se encontró información del trámite.</p>";
    exit;
}

// Procesar confirmación
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_SESSION["usuario"];
    $fecha = date("Y-m-d H:i:s");

    $conn->query("INSERT INTO flujoseguimiento (nro_tramite, flujo, proceso, usuario, fecha, observacion)
                  VALUES ($nrotramite, 'F1_venta_cliente', 'confirmaVenta', '$usuario', '$fecha', 'Venta confirmada por vendedor')");

    echo "<p style='color:green;'>✅ Venta confirmada correctamente. Este trámite ahora es tarea del ALMACÉN.</p>";
    echo "<p><a href='../usuarios/vendedor.php'>🔄 Volver al panel de tareas</a></p>";
    exit;
}
?>

<h2>✅ Confirmación de Venta - Trámite #<?= $nrotramite ?></h2>
<p><strong>Producto:</strong> <?= $pedido['nombre'] ?></p>
<p><strong>Cantidad:</strong> <?= $pedido['cantidad'] ?></p>
<p><strong>Total:</strong> Bs <?= number_format($pedido['total'], 2) ?></p>

<form method="POST">
    <input type="submit" value="Confirmar venta">
</form>

<p><a href="../usuarios/vendedor.php">⬅️ Volver</a></p>
<p><a href="../logout.php">🔙 Cerrar sesión</a></p>
