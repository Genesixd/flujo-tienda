<?php
session_start();
include "../conectar.inc.php";

if ($_SESSION["rol"] !== "VENDEDOR") {
    echo "<div class='error'>❌ Acceso denegado.</div>";
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

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel Vendedor</title>
    <link rel="stylesheet" href="../css/estilos.css">
</head>
<body>

<div class="panel-vendedor">
    <h2>📋 Tareas pendientes - VENDEDOR</h2>

    <p>
        <a href="../procesos/apruebaDevolucion.php">📥 Ver solicitudes de devolución</a> | 
        <a href="../procesos/apruebaCompra.php">📋 Aprobar compras solicitadas</a>
    </p>

    <?php if ($result->num_rows > 0): ?>
        <table>
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
                       <a href="../flujo.php?flujo=F1_venta_cliente&proceso=confirmaVenta&nrotramite=<?= $row['nro_tramite'] ?>">🛠️ Atender</a>

                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <div class="warning">⚠️ No hay tareas pendientes por ahora.</div>
    <?php endif; ?>

    <p><a href="../logout.php">🔙 Cerrar sesión</a></p>
</div>

</body>
</html>
