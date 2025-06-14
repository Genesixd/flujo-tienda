<?php
session_start();
include "../conectar.inc.php";

// Control de acceso desde flujo
if ($_SERVER["REQUEST_METHOD"] === "GET") {
    if (!isset($_SESSION['desde_flujo']) || $_SESSION['desde_flujo'] !== true) {
        echo "<p style='color:red;'>❌ Acceso denegado. Debes ingresar desde flujo.php.</p>";
        exit;
    }
}

if ($_SESSION["rol"] !== "ALMACEN") {
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

$query = "
    SELECT p.id AS producto_id, p.nombre, p.stock, d.cantidad
    FROM detalle_pedido d
    JOIN producto p ON p.id = d.producto_id
    WHERE d.nro_tramite = $nrotramite
";
$pedido = $conn->query($query)->fetch_assoc();

if (!$pedido) {
    echo "<p style='color:red;'>❌ No se encontró información del pedido.</p>";
    exit;
}

$stock = $pedido["stock"];
$solicitado = $pedido["cantidad"];
?>

<h2>📦 Verificación de Stock - Trámite #<?= $nrotramite ?></h2>
<p><strong>Producto:</strong> <?= $pedido['nombre'] ?></p>
<p><strong>Stock disponible:</strong> <?= $stock ?></p>
<p><strong>Solicitado:</strong> <?= $solicitado ?></p>

<form method="POST" action="../controlador.php">
    <input type="hidden" name="flujo" value="<?= $flujo ?>">
    <input type="hidden" name="proceso" value="verificaStock">
    <input type="hidden" name="nrotramite" value="<?= $nrotramite ?>">
    <input type="hidden" name="producto_id" value="<?= $pedido['producto_id'] ?>">
    <input type="hidden" name="stock" value="<?= $stock ?>">
    <input type="hidden" name="cantidad" value="<?= $solicitado ?>">
    <input type="submit" value="Verificar y Descontar Stock">
</form>

<p><a href="../usuarios/almacen.php">⬅️ Volver</a></p>
<p><a href="../logout.php">🔙 Cerrar sesión</a></p>
