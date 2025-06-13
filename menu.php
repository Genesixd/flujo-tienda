<?php
session_start();

$usuario = $_SESSION["usuario"] ?? '';
$rol = $_SESSION["rol"] ?? '';

if (!$usuario || !$rol) {
    header("Location: login.php");
    exit;
}

echo "<h2>Bienvenido, $usuario</h2>";
echo "<h3>Rol: $rol</h3>";

switch ($rol) {
    case 'CLIENTE':
        header("Location: usuarios/cliente.php");
        break;
    case 'VENDEDOR':
        header("Location: usuarios/vendedor.php");
        break;
    case 'ALMACEN':
        header("Location: usuarios/almacen.php");
        break;
    case 'CAJERO':
        header("Location: usuarios/cajero.php");
        break;
    default:
        echo "⚠️ Rol no reconocido.";
}
