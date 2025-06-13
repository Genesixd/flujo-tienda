<?php
session_start();
include "conectar.inc.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST["usuario"];
    $contraseña = $_POST["contraseña"];

    $query = "SELECT * FROM usuario WHERE usuario = ? AND contraseña = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $usuario, $contraseña);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $_SESSION["usuario"] = $row["usuario"];
        $_SESSION["rol"] = $row["rol"];
        header("Location: menu.php");
    } else {
        echo "❌ Usuario o contraseña incorrectos.";
    }
}
?>

<form method="POST">
    <label>Usuario:</label>
    <input type="text" name="usuario" required><br>
    <label>Contraseña:</label>
    <input type="password" name="contraseña" required><br>
    <input type="submit" value="Ingresar">
</form>
