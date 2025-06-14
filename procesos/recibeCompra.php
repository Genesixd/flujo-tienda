<?php
session_start();
include "../conectar.inc.php";

if ($_SESSION["rol"] !== "ALMACEN") {
    echo "<p style='color:red;'>❌ Acceso denegado.</p>";
    exit;
}

$mensaje = "";

// Confirmar recepción
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $compra_id = $_POST["compra_id"];
    $producto_id = $_POST["producto_id"];
    $cantidad = $_POST["cantidad"];

    // 1. Actualizar estado de la compra
    $conn->query("UPDATE compra SET estado = 'ENTREGADA' WHERE id = $compra_id");

    // 2. Actualizar stock del producto
    $conn->query("UPDATE producto SET stock = stock + $cantidad WHERE id = $producto_id");

    $mensaje = "<p style='color:green;'>✅ Productos recibidos correctamente. Stock actualizado.</p>";
}

// Consultar compras aprobadas
$sql = "
SELECT c.id, c.producto_id, c.cantidad, c.fecha, p.nombre AS producto
FROM compra c
JOIN producto p ON c.producto_id = p.id
WHERE c.estado = 'APROBADA'
ORDER BY c.fecha ASC
";
$result = $conn->query($sql);
?>

<h2>📦 Recepción de productos del proveedor</h2>

<?= $mensaje ?>

<?php if ($result->num_rows > 0): ?>
<table border="1" cellpadding="5">
    <tr>
        <th>Producto</th>
        <th>Cantidad</th>
        <th>Fecha</th>
        <th>Acción</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?= $row['producto'] ?></td>
        <td><?= $row['cantidad'] ?></td>
        <td><?= $row['fecha'] ?></td>
        <td>
            <form method="POST" action="../controlador.php">
                <input type="hidden" name="flujo" value="F2_compra_proveedor">
                <input type="hidden" name="proceso" value="recibeCompra">
                <input type="hidden" name="compra_id" value="<?= $row['id'] ?>">
                <input type="hidden" name="producto_id" value="<?= $row['producto_id'] ?>">
                <input type="hidden" name="cantidad" value="<?= $row['cantidad'] ?>">
                <button type="submit">📥 Confirmar recepción</button>
            </form>

        </td>
    </tr>
    <?php endwhile; ?>
</table>
<?php else: ?>
    <p>No hay productos aprobados pendientes de recepción.</p>
<?php endif; ?>

<p><a href="../usuarios/almacen.php">⬅️ Volver</a></p>
<p><a href="../logout.php">🔙 Cerrar sesión</a></p>
