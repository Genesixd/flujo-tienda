<?php
session_start();
include "../conectar.inc.php";

// Verificar acceso desde flujo.php usando la sesión
if ($_SERVER["REQUEST_METHOD"] === "GET") {
    if (!isset($_SESSION['desde_flujo']) || $_SESSION['desde_flujo'] !== true) {
        echo "<p style='color:red;'>❌ Acceso denegado. Debes ingresar desde <code>flujo.php</code>.</p>";
        exit;
    }
}


// Obtener datos necesarios
$flujo = $_GET["flujo"] ?? '';
$nrotramite = $_GET["nrotramite"] ?? $_SESSION["nrotramite"] ?? null;

// Validar sesión y rol
if ($_SESSION["rol"] !== "VENDEDOR") {
    echo "<p style='color:red;'>❌ Acceso denegado. Solo VENDEDOR puede confirmar ventas.</p>";
    exit;
}

if (!$nrotramite || !is_numeric($nrotramite)) {
    echo "<p style='color:red;'>❌ Trámite no especificado.</p>";
    exit;
}

// Guardar para siguientes pasos
$_SESSION["nrotramite"] = $nrotramite;

// Verificar si ya fue atendido
$verifica = $conn->query("
    SELECT 1 FROM flujoseguimiento
    WHERE nro_tramite = $nrotramite AND proceso = 'confirmaVenta'
    LIMIT 1
");

if ($verifica->num_rows > 0) {
    echo "<p style='color:orange;'>⚠️ Este trámite ya fue atendido por el VENDEDOR.</p>";
    echo "<p><a href='../usuarios/vendedor.php'>⬅️ Volver al panel</a></p>";
    exit;
}

// Cargar datos del pedido
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
?>

<h2>✅ Confirmación de Venta - Trámite #<?= $nrotramite ?></h2>
<p><strong>Producto:</strong> <?= $pedido['nombre'] ?></p>
<p><strong>Cantidad:</strong> <?= $pedido['cantidad'] ?></p>
<p><strong>Total:</strong> Bs <?= number_format($pedido['total'], 2) ?></p>

<form method="POST" action="../controlador.php">
    <input type="hidden" name="flujo" value="<?= $flujo ?>">
    <input type="hidden" name="proceso" value="confirmaVenta">
    <input type="hidden" name="nrotramite" value="<?= $nrotramite ?>">
    <input type="submit" value="Confirmar venta">
</form>

<p><a href="../usuarios/vendedor.php">⬅️ Volver</a></p>
<p><a href="../logout.php">🔙 Cerrar sesión</a></p>
