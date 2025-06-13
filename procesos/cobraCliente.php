<?php
session_start();
include "../conectar.inc.php";

if ($_SESSION["rol"] !== "CAJERO") {
    echo "<p style='color:red;'>❌ Acceso denegado.</p>";
    exit;
}

$nrotramite = $_GET["nro"] ?? null;
if (!$nrotramite) {
    echo "<p style='color:red;'>❌ Trámite no especificado.</p>";
    exit;
}

$query = "
    SELECT p.nombre, p.precio, d.cantidad, (p.precio * d.cantidad) AS total
    FROM detalle_pedido d
    JOIN producto p ON p.id = d.producto_id
    WHERE d.nro_tramite = $nrotramite
";
$pedido = $conn->query($query)->fetch_assoc();

if (!$pedido) {
    echo "<p style='color:red;'>❌ No se encontró información del pedido.</p>";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $metodo = $_POST["metodo_pago"];
    $usuario = $_SESSION["usuario"];
    $fecha = date("Y-m-d H:i:s");

    $conn->query("INSERT INTO flujoseguimiento (nro_tramite, flujo, proceso, usuario, fecha, observacion)
                  VALUES ($nrotramite, 'F1_venta_cliente', 'cobraCliente', '$usuario', '$fecha', 'Pago $metodo')");

    $conn->query("UPDATE tramite SET estado = 'FINALIZADO' WHERE nro_tramite = $nrotramite");

    echo "<h3 style='color:green;'>✅ Trámite finalizado correctamente.</h3>";
    echo "<p><a href='../usuarios/cajero.php'>🔄 Volver al panel</a></p>";
    exit;
}
?>

<h2>💵 Cobro - Trámite #<?= $nrotramite ?></h2>
<p><strong>Producto:</strong> <?= $pedido['nombre'] ?></p>
<p><strong>Cantidad:</strong> <?= $pedido['cantidad'] ?></p>
<p><strong>Total:</strong> Bs <?= number_format($pedido['total'], 2) ?></p>

<form method="POST">
    <label>Método de Pago:</label>
    <select name="metodo_pago" required>
        <option value="EFECTIVO">Efectivo</option>
        <option value="QR">QR</option>
        <option value="TARJETA">Tarjeta</option>
    </select><br><br>
    <input type="submit" value="Confirmar pago y finalizar venta">
</form>

<p><a href="../usuarios/cajero.php">⬅️ Volver</a></p>
<p><a href="../logout.php">🔙 Cerrar sesión</a></p>
