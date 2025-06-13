<?php
session_start();
include "../conectar.inc.php";

if ($_SESSION["rol"] !== "ALMACEN") {
    echo "<p style='color:red;'>❌ Acceso denegado.</p>";
    exit;
}

$nrotramite = $_GET["nro"] ?? null;
if (!$nrotramite) {
    echo "<p style='color:red;'>❌ Trámite no especificado.</p>";
    exit;
}
// Verificar si ya fue atendido
$verifica = $conn->query("
    SELECT 1 FROM flujoseguimiento
    WHERE nro_tramite = $nrotramite AND proceso = 'verificaStock'
    LIMIT 1
");
if ($verifica->num_rows > 0) {
    echo "<p style='color:orange;'>⚠️ Este trámite ya fue atendido por ALMACÉN.</p>";
    echo "<p><a href='../usuarios/almacen.php'>⬅️ Volver al panel</a></p>";
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
$producto_id = $pedido["producto_id"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_SESSION["usuario"];
    $fecha = date("Y-m-d H:i:s");

    if ($stock >= $solicitado) {
        $nuevoStock = $stock - $solicitado;
        $conn->query("UPDATE producto SET stock = $nuevoStock WHERE id = $producto_id");

        $conn->query("INSERT INTO flujoseguimiento (nro_tramite, flujo, proceso, usuario, fecha, observacion)
                      VALUES ($nrotramite, 'F1_venta_cliente', 'verificaStock', '$usuario', '$fecha', 'Stock verificado')");

        echo "<p style='color:green;'>✅ Stock verificado. El trámite pasa al CAJERO.</p>";
    } else {
        echo "<p style='color:red;'>❌ Stock insuficiente. Informe al vendedor para modificar el pedido.</p>";
    }

    echo "<p><a href='../usuarios/almacen.php'>🔄 Volver al panel</a></p>";
    exit;
}
?>

<h2>📦 Verificación de Stock - Trámite #<?= $nrotramite ?></h2>
<p><strong>Producto:</strong> <?= $pedido['nombre'] ?></p>
<p><strong>Stock disponible:</strong> <?= $stock ?></p>
<p><strong>Solicitado:</strong> <?= $solicitado ?></p>

<form method="POST">
    <input type="submit" value="Verificar y Descontar Stock">
</form>

<p><a href="../usuarios/almacen.php">⬅️ Volver</a></p>
<p><a href="../logout.php">🔙 Cerrar sesión</a></p>
