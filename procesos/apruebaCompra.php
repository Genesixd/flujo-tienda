<?php
session_start();
include "../conectar.inc.php";

// Solo ADMIN o VENDEDOR pueden aprobar compras
if (!in_array($_SESSION["rol"], ["ADMIN", "VENDEDOR"])) {
    echo "<p style='color:red;'>❌ Acceso denegado.</p>";
    exit;
}

$mensaje = "";

// Procesar acción
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["id"];
    $accion = $_POST["accion"];

    if ($accion === "aprobar") {
        $conn->query("UPDATE compra SET estado = 'APROBADA' WHERE id = $id");
        $mensaje = "<p style='color:green;'>✅ Solicitud de compra aprobada.</p>";
    } elseif ($accion === "rechazar") {
        $conn->query("UPDATE compra SET estado = 'RECHAZADA' WHERE id = $id");
        $mensaje = "<p style='color:orange;'>❌ Solicitud de compra rechazada.</p>";
    }
}

// Obtener compras pendientes
$query = "
SELECT c.id, p.nombre AS producto, c.cantidad, c.motivo, c.fecha
FROM compra c
JOIN producto p ON c.producto_id = p.id
WHERE c.estado = 'PENDIENTE'
ORDER BY c.fecha ASC
";
$result = $conn->query($query);
?>

<h2>📋 Solicitudes de Compra Pendientes</h2>

<?= $mensaje ?>

<?php if ($result->num_rows > 0): ?>
<table border="1" cellpadding="5">
    <tr>
        <th>Producto</th>
        <th>Cantidad</th>
        <th>Motivo</th>
        <th>Fecha</th>
        <th>Acción</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?= $row['producto'] ?></td>
        <td><?= $row['cantidad'] ?></td>
        <td><?= $row['motivo'] ?></td>
        <td><?= $row['fecha'] ?></td>
        <td>
            <form method="POST" action="../controlador.php" style="display:inline;">
                <input type="hidden" name="flujo" value="F2_compra_proveedor">
                <input type="hidden" name="proceso" value="apruebaCompra">
                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                <button type="submit" name="accion" value="aprobar">✅ Aprobar</button>
                <button type="submit" name="accion" value="rechazar">❌ Rechazar</button>
            </form>

        </td>
    </tr>
    <?php endwhile; ?>
</table>
<?php else: ?>
    <p>No hay solicitudes de compra pendientes.</p>
<?php endif; ?>

<p><a href="../menu.php">⬅️ Volver</a></p>
<p><a href="../logout.php">🔙 Cerrar sesión</a></p>
