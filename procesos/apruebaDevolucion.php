<?php
session_start();
include "../conectar.inc.php";

if ($_SESSION["rol"] !== "VENDEDOR") {
    echo "<p style='color:red;'>❌ Acceso denegado.</p>";
    exit;
}

$mensaje = "";

// Aprobar o rechazar
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["id"];
    $accion = $_POST["accion"]; // APROBADA o RECHAZADA

    $conn->query("UPDATE devolucion SET estado = '$accion' WHERE id = $id");

    $mensaje = "<p style='color:green;'>✅ Devolución $accion correctamente.</p>";
}

// Obtener devoluciones pendientes
$query = "
SELECT d.id, d.nro_tramite, d.motivo, d.fecha, u.usuario AS cliente
FROM devolucion d
JOIN tramite t ON d.nro_tramite = t.nro_tramite
JOIN usuario u ON t.cliente_id = u.id
WHERE d.estado = 'PENDIENTE'
ORDER BY d.fecha DESC
";
$result = $conn->query($query);
?>

<h2>📥 Solicitudes de Devolución</h2>

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
            <form method="POST" action="../controlador.php" style="display:inline;">
                <input type="hidden" name="flujo" value="F3_devolucion">
                <input type="hidden" name="proceso" value="apruebaDevolucion">
                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                <button name="accion" value="APROBADA">✅ Aprobar</button>
                <button name="accion" value="RECHAZADA">❌ Rechazar</button>
            </form>

        </td>
    </tr>
    <?php endwhile; ?>
</table>
<?php else: ?>
    <p>No hay solicitudes pendientes.</p>
<?php endif; ?>

<p><a href="../usuarios/vendedor.php">⬅️ Volver</a></p>
<p><a href="../logout.php">🔙 Cerrar sesión</a></p>
