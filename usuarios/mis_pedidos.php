<?php
session_start();
include "../conectar.inc.php";

if ($_SESSION["rol"] !== "CLIENTE") {
    echo "<div class='error'>❌ Acceso denegado.</div>";
    exit;
}

$usuario = $_SESSION["usuario"];
$res = $conn->query("SELECT id FROM usuario WHERE usuario = '$usuario'");
$cliente_id = $res->fetch_assoc()['id'];

$query = "
SELECT t.nro_tramite, t.fecha_inicio, t.estado,
       (SELECT 1 FROM flujoseguimiento WHERE nro_tramite = t.nro_tramite AND proceso = 'confirmaVenta' LIMIT 1) AS vendedor,
       (SELECT 1 FROM flujoseguimiento WHERE nro_tramite = t.nro_tramite AND proceso = 'verificaStock' LIMIT 1) AS almacen,
       (SELECT 1 FROM flujoseguimiento WHERE nro_tramite = t.nro_tramite AND proceso = 'cobraCliente' LIMIT 1) AS cajero
FROM tramite t
WHERE t.cliente_id = $cliente_id
ORDER BY t.nro_tramite DESC
";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis Pedidos</title>
    <link rel="stylesheet" href="../css/estilos.css">
</head>
<body>

<div class="panel-cliente">
    <h2>📋 Mis Pedidos</h2>

    <?php if ($result->num_rows > 0): ?>
        <table>
            <tr>
                <th>N° Trámite</th>
                <th>Fecha</th>
                <th>Estado</th>
                <th>Pasos del Flujo</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['nro_tramite'] ?></td>
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
        <div class="warning">⚠️ Aún no realizaste ningún pedido.</div>
    <?php endif; ?>

    <p><a href="../usuarios/cliente.php">⬅️ Volver</a></p>
    <p><a href="../logout.php">🔙 Cerrar sesión</a></p>
</div>

</body>
</html>
