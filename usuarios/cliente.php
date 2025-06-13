<?php
session_start();

// Verificar rol
if ($_SESSION["rol"] !== "CLIENTE") {
    echo "Acceso denegado.";
    exit;
}

// Redirige al inicio del flujo
header("Location: ../procesos/seleccionaProducto.php");
exit;
