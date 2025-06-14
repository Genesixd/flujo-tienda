<?php
session_start();

if ($_SESSION["rol"] !== "CLIENTE") {
    echo "<div class='error'>❌ Acceso denegado.</div>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel Cliente</title>
    <link rel="stylesheet" href="../css/estilos.css">
</head>
<body>

<div class="panel-cliente">
    <h2>👤 Bienvenido, <?= $_SESSION["usuario"] ?> (Cliente)</h2>

    <ul>
        <li><a href="../procesos/seleccionaProducto.php">🛒 Hacer nuevo pedido</a></li>
        <li><a href="mis_pedidos.php">📋 Ver mis pedidos</a></li>
        <li><a href="../procesos/solicitaDevolucion.php">🔁 Solicitar devolución</a></li>
        <li><a href="../logout.php">🔙 Cerrar sesión</a></li>
    </ul>
</div>

</body>
</html>
