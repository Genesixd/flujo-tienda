<?php
session_start();

if ($_SESSION["rol"] !== "CLIENTE") {
    echo "<p style='color:red;'>❌ Acceso denegado.</p>";
    exit;
}
?>

<h2>👤 Bienvenido, <?= $_SESSION["usuario"] ?> (Cliente)</h2>

<ul>
  <li><a href="../procesos/seleccionaProducto.php">🛒 Hacer nuevo pedido</a></li>
  <li><a href="mis_pedidos.php">📋 Ver mis pedidos</a></li>
  <li><a href="../logout.php">🔙 Cerrar sesión</a></li>
</ul>
