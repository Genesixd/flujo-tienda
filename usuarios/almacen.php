<?php
session_start();
include "../conectar.inc.php";

if ($_SESSION["rol"] !== "ALMACEN") {
    echo "<div class='error'>❌ Acceso denegado.</div>";
    exit;
}

$query = "
SELECT t.nro_tramite, tu.usuario AS cliente
FROM tramite t
JOIN usuario tu ON tu.id = t.cliente_id
JOIN flujoseguimiento f1 ON f1.nro_tramite = t.nro_tramite AND f1.proceso = 'confirmaVenta'
WHERE t.estado = 'EN_PROCESO'
AND NOT EXISTS (
    SELECT 1 FROM flujoseguimiento f2
    WHERE f2.nro_tramite = t.nro_tramite AND f2.proceso = 'verificaStock'
)
ORDER BY t.nro_tramite DESC
";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel Almacén</title>
    <link rel="stylesheet" href="../css/estilos.css">
</head>
<body>

<div class="panel-almacen">
    <h2>🏬 Tareas pendientes - ALMACÉN</h2>

    <p>
        <a href="../procesos/recibeProducto.php">📦 Ver devoluciones aprobadas</a> |
        <a href="../procesos/solicitaCompra.php">🛒 Solicitar compra a proveedor</a> |
        <a href="../procesos/recibeCompra.php">📦 Confirmar recepción de productos</a>
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
                        <a href="../flujo.php?flujo=F1_venta_cliente&proceso=verificaStock&nrotramite=<?= $row['nro_tramite'] ?>">🛠️ Atender</a>

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
