<?php
session_start();
include "../conectar.inc.php";

if ($_SESSION["rol"] !== "CLIENTE") {
    echo "<p style='color:red;'>❌ Acceso denegado.</p>";
    exit;
}

$mensaje = "";

// Al enviar el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $producto_id = $_POST["producto"];
    $cantidad = $_POST["cantidad"];

    if ($cantidad <= 0) {
        $mensaje = "<p style='color:red;'>❌ La cantidad debe ser mayor a cero.</p>";
    } else {
        // Insertar trámite y pedido
        $cliente_id = $conn->query("SELECT id FROM usuario WHERE usuario = '{$_SESSION['usuario']}'")->fetch_assoc()['id'];
        $conn->query("INSERT INTO tramite (cliente_id, fecha_inicio) VALUES ($cliente_id, NOW())");
        $nrotramite = $conn->insert_id;

        $conn->query("INSERT INTO detalle_pedido (nro_tramite, producto_id, cantidad)
                      VALUES ($nrotramite, $producto_id, $cantidad)");

        $mensaje = "<p style='color:green;'>✅ Pedido registrado correctamente. Ahora el vendedor debe atenderlo.</p>";
    }
}

// Obtener productos disponibles
$result = $conn->query("SELECT * FROM producto");
?>

<h2>🛒 Selección de Producto</h2>

<?= $mensaje ?>

<form method="POST">
    <label>Producto:</label>
    <select name="producto" required>
        <?php while ($row = $result->fetch_assoc()): ?>
            <option value="<?= $row['id'] ?>">
                <?= $row['nombre'] ?> (Stock: <?= $row['stock'] ?>) - Bs <?= $row['precio'] ?>
            </option>
        <?php endwhile; ?>
    </select><br><br>

    <label>Cantidad:</label>
    <input type="number" name="cantidad" min="1" required><br><br>

    <input type="submit" value="Confirmar selección">
</form>

<p><a href="../logout.php">🔙 Cerrar sesión</a></p>
