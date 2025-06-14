<?php
session_start();

if ($_SESSION["rol"] !== "CAJERO") {
    echo "<div class='error'>❌ Acceso denegado.</div>";
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

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel Cajero</title>
    <link rel="stylesheet" href="../css/estilos.css">
</head>
<body>

<div class="panel-cajero">
    <h2>💰 Tareas pendientes - CAJERO</h2>

    <p>
        <a href="../procesos/procesaReembolso.php">💸 Reembolsar devoluciones</a> |
        <a href="../procesos/pagaProveedor.php">💰 Pagar al proveedor</a>
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
                       <a href="../flujo.php?flujo=F1_venta_cliente&proceso=cobraCliente&nrotramite=<?= $row['nro_tramite'] ?>">🧾 Atender</a>

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
