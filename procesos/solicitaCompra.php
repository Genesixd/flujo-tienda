<?php
session_start();
include "../conectar.inc.php";

if ($_SESSION["rol"] !== "ALMACEN") {
    echo "<p style='color:red;'>❌ Acceso denegado.</p>";
    exit;
}

$mensaje = "";

// Obtener lista de productos
$productos = $conn->query("SELECT id, nombre, stock FROM producto");
?>

<h2>📦 Solicitar compra a proveedor</h2>

<?= $mensaje ?>

<form method="POST" action="../controlador.php">
    <input type="hidden" name="flujo" value="F2_compra_proveedor">
    <input type="hidden" name="proceso" value="solicitaCompra">

    <label>Producto:</label>
    <select name="producto_id" required>
        <option value="">-- Seleccione --</option>
        <?php while ($row = $productos->fetch_assoc()): ?>
            <option value="<?= $row['id'] ?>">
                <?= $row['nombre'] ?> (Stock: <?= $row['stock'] ?>)
            </option>
        <?php endwhile; ?>
    </select><br><br>

    <label>Cantidad a solicitar:</label>
    <input type="number" name="cantidad" required min="1"><br><br>

    <label>Motivo de la solicitud:</label><br>
    <textarea name="motivo" rows="3" cols="40" required></textarea><br><br>

    <input type="submit" value="📥 Enviar solicitud">
</form>

<p><a href="../usuarios/almacen.php">⬅️ Volver</a></p>
<p><a href="../logout.php">🔙 Cerrar sesión</a></p>
