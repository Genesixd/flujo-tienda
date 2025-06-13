<?php
session_start();
include "../conectar.inc.php";

if ($_SESSION["rol"] !== "VENDEDOR") {
    echo "<p style='color:red;'>❌ Acceso denegado.</p>";
    exit;
}

// Consulta de trámites pendientes para el vendedor
$query = "
SELECT t.nro_tramite, tu.usuario AS cliente
FROM tramite t
JOIN usuario tu ON tu.id = t.cliente_id
WHERE t.estado = 'EN_PROCESO'
AND NOT EXISTS (
    SELECT 1 FROM flujoseguimiento f
    WHERE f.nro_tramite = t.nro_tramite AND f.proceso = 'confirmaVenta'
)
ORDER BY t.nro_tramite DESC
";

$result = $conn->query($query);
?>

<h2>📋 Tareas pendientes - VENDEDOR</h2>
<!-- Nueva opción de menú -->
<p>
    <a href="../procesos/apruebaDevolucion.php">📥 Ver solicitudes de devolución</a>
    <a href="../procesos/apruebaCompra.php">📋 Aprobar compras solicitadas</a>
</p>
<?php if ($result->num_rows > 0): ?>
<table border="1" cellpadding="5">
    <tr>
        <th>N° Trámite</th>
        <th>Cliente</th>
        <th>Acción</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?= $row['nro_tramite'] ?></td>
        <td><?= $row['cliente'] ?></td>
        <td>
            <a href="../procesos/confirmaVenta.php?nro=<?= $row['nro_tramite'] ?>">🛠️ Atender</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>
<?php else: ?>
    <p>No hay tareas pendientes por ahora.</p>
<?php endif; ?>

<p><a href="../logout.php">🔙 Cerrar sesión</a></p>
