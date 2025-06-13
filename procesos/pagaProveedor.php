<?php
session_start();
include "../conectar.inc.php";

if ($_SESSION["rol"] !== "CAJERO") {
    echo "<p style='color:red;'>❌ Acceso denegado.</p>";
    exit;
}

$mensaje = "";

// Procesar pago
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $compra_id = $_POST["compra_id"];
    $metodo_pago = $_POST["metodo_pago"];

    $conn->query("UPDATE compra SET estado = 'PAGADA' WHERE id = $compra_id");

    $mensaje = "<p style='color:green;'>✅ Pago al proveedor registrado por $metodo_pago.</p>";
}

// Consultar compras entregadas
$sql = "
SELECT c.id, p.nombre AS producto, c.cantidad, c.fecha
FROM compra c
JOIN producto p ON c.producto_id = p.id
WHERE c.estado = 'ENTREGADA'
ORDER BY c.fecha ASC
";
$result = $conn->query($sql);
?>

<h2>💰 Pagos pendientes a proveedor</h2>

<?= $mensaje ?>

<?php if ($result->num_rows > 0): ?>
<table border="1" cellpadding="5">
    <tr>
        <th>Producto</th>
        <th>Cantidad</th>
        <th>Fecha</th>
        <th>Registrar pago</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?= $row['producto'] ?></td>
        <td><?= $row['cantidad'] ?></td>
        <td><?= $row['fecha'] ?></td>
        <td>
            <form method="POST" style="display:inline;">
                <input type="hidden" name="compra_id" value="<?= $row['id'] ?>">
                <select name="metodo_pago" required>
                    <option value="EFECTIVO">Efectivo</option>
                    <option value="QR">QR</option>
                    <option value="TARJETA">Tarjeta</option>
                </select>
                <button type="submit">💳 Pagar</button>
            </form>
        </td>
    </tr>
    <?php endwhile; ?>
</table>
<?php else: ?>
    <p>No hay compras pendientes de pago.</p>
<?php endif; ?>

<p><a href="../usuarios/cajero.php">⬅️ Volver</a></p>
<p><a href="../logout.php">🔙 Cerrar sesión</a></p>
