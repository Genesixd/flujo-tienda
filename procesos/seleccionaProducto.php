<?php
// ======= ARCHIVO: procesos/seleccionaProducto.php =======
session_start();
include "../conectar.inc.php";

if ($_SESSION["rol"] !== "CLIENTE") {
    echo "<p style='color:red;'>❌ Acceso denegado.</p>";
    exit;
}

$mensaje = "";
if (isset($_GET["exito"])) {
    $mensaje = "<div class='success'>✅ Pedido registrado correctamente. Ahora el vendedor debe atenderlo.</div>";
} elseif (isset($_GET["error"]) && $_GET["error"] === "cantidad") {
    $mensaje = "<div class='error'>❌ La cantidad debe ser mayor a cero.</div>";
}

$result = $conn->query("SELECT * FROM producto");
if (!$result) {
    die("<p style='color:red;'>❌ Error en la consulta: " . $conn->error . "</p>");
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Selecciona Producto</title>
    <link rel="stylesheet" href="../css/estilos.css">
</head>
<body>

<div class="panel-cliente">
    <h2>🛒 Selección de Producto</h2>

    <?= $mensaje ?>

    <form method="POST" action="../controlador.php">
        <input type="hidden" name="flujo" value="F1_venta_cliente">
        <input type="hidden" name="proceso" value="seleccionaProducto">

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
</div>

</body>
</html>


<?php
