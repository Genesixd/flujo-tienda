<?php
session_start();
include "conectar.inc.php";

// 🔒 Puedes proteger esto con una condición si solo debe verlo el ADMIN
// if ($_SESSION["rol"] !== "ADMIN") { exit("Acceso denegado."); }

$query = "
SELECT t.nro_tramite, t.fecha_inicio, t.estado, u.usuario AS cliente,
       (SELECT 1 FROM flujoseguimiento WHERE nro_tramite = t.nro_tramite AND proceso = 'confirmaVenta' LIMIT 1) AS vendedor,
       (SELECT 1 FROM flujoseguimiento WHERE nro_tramite = t.nro_tramite AND proceso = 'verificaStock' LIMIT 1) AS almacen,
       (SELECT 1 FROM flujoseguimiento WHERE nro_tramite = t.nro_tramite AND proceso = 'cobraCliente' LIMIT 1) AS cajero
FROM tramite t
JOIN usuario u ON t.cliente_id = u.id
ORDER BY t.nro_tramite DESC
";

$result = $conn->query($query);
?>

<h2>📊 Dashboard de Trámites</h2>

<?php if ($result->num_rows > 0): ?>
<table border="1" cellpadding="5">
    <tr>
        <th># Trámite</th>
        <th>Cliente</th>
        <th>Fecha</th>
        <th>Estado</th>
        <th>Progreso</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?= $row['nro_tramite'] ?></td>
        <td><?= $row['cliente'] ?></td>
        <td><?= $row['fecha_inicio'] ?></td>
        <td><?= $row['estado'] === 'FINALIZADO' ? '✅ Finalizado' : '🕓 En proceso' ?></td>
        <td>
            Cliente ✅ →
            Vendedor <?= $row['vendedor'] ? '✅' : '⏳' ?> →
            Almacén <?= $row['almacen'] ? '✅' : '⏳' ?> →
            Cajero <?= $row['cajero'] ? '✅' : '⏳' ?>
        </td>
    </tr>
    <?php endwhile; ?>
</table>
<?php else: ?>
    <p>No hay trámites aún.</p>
<?php endif; ?>

<p><a href="logout.php">🔙 Cerrar sesión</a></p>
