<?php
session_start();
include "../conectar.inc.php";

// Acceso desde flujo
if ($_SERVER["REQUEST_METHOD"] === "GET") {
    if (!isset($_SESSION['desde_flujo']) || $_SESSION['desde_flujo'] !== true) {
        echo "<p style='color:red;'>❌ Acceso denegado. Debes ingresar desde flujo.php.</p>";
        exit;
    }
}

if ($_SESSION["rol"] !== "CAJERO") {
    echo "<p style='color:red;'>❌ Acceso denegado.</p>";
    exit;
}

$flujo = $_GET["flujo"] ?? '';
$nrotramite = $_GET["nrotramite"] ?? $_SESSION["nrotramite"] ?? null;
$_SESSION["nrotramite"] = $nrotramite;

if (!$nrotramite) {
    echo "<p style='color:red;'>❌ Trámite no especificado.</p>";
    exit;
}

$verifica = $conn->query("
    SELECT 1 FROM flujoseguimiento
    WHERE nro_tramite = $nrotramite AND proceso = 'cobraCliente'
    LIMIT 1
");
if ($verifica->num_rows > 0) {
    echo "<p style='color:orange;'>⚠️ Este trámite ya fue cobrado por el CAJERO.</p>";
    echo "<p><a href='../usuarios/cajero.php'>⬅️ Volver al panel</a></p>";
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
?>

<h2>💵 Cobro - Trámite #<?= $nrotramite ?></h2>
<p><strong>Producto:</strong> <?= $pedido['nombre'] ?></p>
<p><strong>Cantidad:</strong> <?= $pedido['cantidad'] ?></p>
<p><strong>Total:</strong> Bs <?= number_format($pedido['total'], 2) ?></p>

<form method="POST" action="../controlador.php">
    <input type="hidden" name="flujo" value="<?= $flujo ?>">
    <input type="hidden" name="proceso" value="cobraCliente">
    <input type="hidden" name="nrotramite" value="<?= $nrotramite ?>">

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
