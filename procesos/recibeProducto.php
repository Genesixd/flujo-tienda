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
    $id = $_POST["id"];

    // Marcar devolución como FINALIZADA
    $conn->query("UPDATE devolucion SET estado = 'FINALIZADA' WHERE id = $id");

    $mensaje = "<p style='color:green;'>✅ Producto recibido correctamente y devolución finalizada.</p>";
}

// Devoluciones aprobadas y no finalizadas
$query = "
SELECT d.id, d.nro_tramite, d.motivo, d.fecha, u.usuario AS cliente
FROM devolucion d
JOIN tramite t ON d.nro_tramite = t.nro_tramite
JOIN usuario u ON t.cliente_id = u.id
WHERE d.estado = 'APROBADA'
ORDER BY d.fecha DESC
";
$result = $conn->query($query);
?>

<h2>📦 Recepción de Devoluciones - ALMACÉN</h2>

<?= $mensaje ?>

<?php if ($result->num_rows > 0): ?>
<table border="1" cellpadding="5">
    <tr>
        <th>Trámite</th>
        <th>Cliente</th>
        <th>Motivo</th>
        <th>Fecha</th>
        <th>Acción</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()): ?>
    <tr>
        <td>#<?= $row['nro_tramite'] ?></td>
        <td><?= $row['cliente'] ?></td>
        <td><?= $row['motivo'] ?></td>
        <td><?= $row['fecha'] ?></td>
        <td>
            <form method="POST" style="display:inline;">
                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                <button type="submit">📦 Confirmar recepción</button>
            </form>
        </td>
    </tr>
    <?php endwhile; ?>
</table>
<?php else: ?>
    <p>No hay devoluciones aprobadas pendientes de recepción.</p>
<?php endif; ?>

<p><a href="../usuarios/almacen.php">⬅️ Volver</a></p>
<p><a href="../logout.php">🔙 Cerrar sesión</a></p>
