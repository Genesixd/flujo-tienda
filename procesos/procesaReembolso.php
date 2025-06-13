<?php
session_start();
include "../conectar.inc.php";

if ($_SESSION["rol"] !== "CAJERO") {
    echo "<p style='color:red;'>❌ Acceso denegado.</p>";
    exit;
}

$mensaje = "";

// Procesar reembolso
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["id"];
    $metodo = $_POST["metodo"];

    $conn->query("UPDATE devolucion SET estado = 'REEMBOLSADO' WHERE id = $id");

    $mensaje = "<p style='color:green;'>✅ Reembolso procesado exitosamente por $metodo.</p>";
}

// Obtener devoluciones finalizadas
$query = "
SELECT d.id, d.nro_tramite, d.motivo, d.fecha, u.usuario AS cliente
FROM devolucion d
JOIN tramite t ON d.nro_tramite = t.nro_tramite
JOIN usuario u ON t.cliente_id = u.id
WHERE d.estado = 'FINALIZADA'
ORDER BY d.fecha DESC
";
$result = $conn->query($query);
?>

<h2>💸 Reembolsos pendientes - CAJERO</h2>

<?= $mensaje ?>

<?php if ($result->num_rows > 0): ?>
<table border="1" cellpadding="5">
    <tr>
        <th>Trámite</th>
        <th>Cliente</th>
        <th>Motivo</th>
        <th>Fecha</th>
        <th>Reembolsar</th>
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
                <select name="metodo" required>
                    <option value="EFECTIVO">Efectivo</option>
                    <option value="QR">QR</option>
                    <option value="TARJETA">Tarjeta</option>
                </select>
                <button type="submit">💰 Reembolsar</button>
            </form>
        </td>
    </tr>
    <?php endwhile; ?>
</table>
<?php else: ?>
    <p>No hay devoluciones finalizadas pendientes de reembolso.</p>
<?php endif; ?>

<p><a href="../usuarios/cajero.php">⬅️ Volver</a></p>
<p><a href="../logout.php">🔙 Cerrar sesión</a></p>
