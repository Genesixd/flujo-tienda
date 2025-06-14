<?php
session_start();

$flujo = $_GET["flujo"] ?? '';
$proceso = $_GET["proceso"] ?? '';
$nrotramite = $_GET["nrotramite"] ?? '';

if (!$flujo || !$proceso) {
    echo "❌ Parámetros incompletos.";
    exit;
}

// ✅ Marca la sesión como válida
$_SESSION["nrotramite"] = $nrotramite;
$_SESSION["desde_flujo"] = true;

header("Location: procesos/$proceso.php?flujo=$flujo&nrotramite=$nrotramite");
exit;
