<?php
session_start();

if ($_SESSION["rol"] !== "CAJERO") {
    echo "<p style='color:red;'>❌ Acceso denegado.</p>";
    exit;
}

include "../conectar.inc.php";

$query = "
SELECT t.nro_tramite, tu.usuario AS cliente
FROM tramite t
JOIN usuario tu ON tu.id = t.cliente_id
JOIN flujoseguimiento f1 ON f1.nro_tramite = t.nro_tramite AND f1.proceso = 'verificaStock'
WHERE t.estado = 'EN_PROCESO'
AND NOT EXISTS (
    SELECT 1 FROM flujoseguimiento f2
    WHERE f2.nro_tramite = t.nro_tramite AND f2.proceso = 'cobraCliente'
)
ORDER BY t.nro_tramite DESC
";

$result = $conn->query($query);
?>

<h2>💰 Tareas pendientes - CAJERO</h2>

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
            <a href="../procesos/cobraCliente.php?nro=<?= $row['nro_tramite'] ?>">🧾 Atender</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>
<?php else: ?>
    <p>No hay tareas pendientes por ahora.</p>
<?php endif; ?>

<p><a href="../logout.php">🔙 Cerrar sesión</a></p>
