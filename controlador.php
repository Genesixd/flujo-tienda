<?php
session_start();
include "conectar.inc.php";

$flujo    = $_POST["flujo"] ?? '';
$proceso  = $_POST["proceso"] ?? '';
$usuario  = $_SESSION["usuario"] ?? '';
$rol      = $_SESSION["rol"] ?? '';
$fecha    = date("Y-m-d H:i:s");

// Intenta capturar el nrotramite de todas las formas posibles
$nrotramite = $_POST["nrotramite"] ?? $_GET["nrotramite"] ?? $_SESSION["nrotramite"] ?? null;

// ========== CLIENTE selecciona producto ==========
if ($flujo === "F1_venta_cliente" && $proceso === "seleccionaProducto" && $rol === "CLIENTE") {
    $producto_id = $_POST["producto"] ?? null;
    $cantidad    = $_POST["cantidad"] ?? 0;

    if ($cantidad <= 0) {
        header("Location: procesos/seleccionaProducto.php?error=cantidad");
        exit;
    }

    $cliente_id = $conn->query("SELECT id FROM usuario WHERE usuario = '$usuario'")
                       ->fetch_assoc()['id'];

    $conn->query("INSERT INTO tramite (cliente_id, fecha_inicio) VALUES ($cliente_id, NOW())");
    $nrotramite = $conn->insert_id;

    $conn->query("INSERT INTO detalle_pedido (nro_tramite, producto_id, cantidad)
                  VALUES ($nrotramite, $producto_id, $cantidad)");

    // Redirige con éxito
    header("Location: procesos/seleccionaProducto.php?exito=1");
    exit;
}

// ========== VENDEDOR confirma venta ==========
if ($flujo === "F1_venta_cliente" && $proceso === "confirmaVenta" && $rol === "VENDEDOR") {
    if (!$nrotramite || !is_numeric($nrotramite)) {
        header("Location: procesos/confirmaVenta.php?error=nrotramite");
        exit;
    }

    $yaConfirmado = $conn->query("
        SELECT 1 FROM flujoseguimiento
        WHERE nro_tramite = $nrotramite AND proceso = 'confirmaVenta'
        LIMIT 1
    ")->num_rows > 0;

    if ($yaConfirmado) {
        header("Location: procesos/confirmaVenta.php?error=ya_confirmado&nro=$nrotramite");
        exit;
    }

    $conn->query("INSERT INTO flujoseguimiento (nro_tramite, flujo, proceso, usuario, fecha, observacion)
                  VALUES ($nrotramite, '$flujo', 'confirmaVenta', '$usuario', '$fecha', 'Venta confirmada por vendedor')");

    // Continuar con el flujo
   echo "<div style='padding: 20px; border: 1px solid #ccc; border-radius: 8px; max-width: 600px; margin: 30px auto; text-align: center; font-family: sans-serif;'>
        <p style='color:green; font-weight:bold; font-size: 18px;'>✅ Venta confirmada correctamente.<br>Ahora el trámite pasa al almacén.</p>

        <a href='usuarios/vendedor.php' style='display:inline-block; padding:10px 20px; margin:10px; background-color:#4CAF50; color:white; text-decoration:none; border-radius:5px;'>⬅️ Volver al panel</a>

        <a href='logout.php' style='display:inline-block; padding:10px 20px; margin:10px; background-color:#f44336; color:white; text-decoration:none; border-radius:5px;'>🔙 Cerrar sesión</a>
     </div>";
exit;}
// ========== ALMACÉN verifica stock ==========
if ($flujo === "F1_venta_cliente" && $proceso === "verificaStock" && $rol === "ALMACEN") {
    if (!$nrotramite || !is_numeric($nrotramite)) {
        echo "<p style='color:red;'>❌ Trámite no válido.</p>";
        exit;
    }

    $producto_id = $_POST["producto_id"] ?? null;
    $stock       = $_POST["stock"] ?? 0;
    $cantidad    = $_POST["cantidad"] ?? 0;

    if ($stock >= $cantidad) {
        $nuevoStock = $stock - $cantidad;

        $conn->query("UPDATE producto SET stock = $nuevoStock WHERE id = $producto_id");

        $conn->query("INSERT INTO flujoseguimiento (nro_tramite, flujo, proceso, usuario, fecha, observacion)
                      VALUES ($nrotramite, '$flujo', 'verificaStock', '$usuario', '$fecha', 'Stock verificado')");

        echo "<p style='color:green; font-weight:bold;'>✅ Stock verificado correctamente. El trámite ahora pasa al CAJERO.</p>";
    } else {
        echo "<p style='color:red;'>❌ Stock insuficiente. Informe al vendedor para modificar el pedido.</p>";
    }

    echo "<p><a href='usuarios/almacen.php'><button>⬅️ Volver al panel</button></a></p>";
    echo "<p><a href='logout.php'><button>🔙 Cerrar sesión</button></a></p>";
    exit;
}
// ========== CAJERO cobra cliente ==========
if ($flujo === "F1_venta_cliente" && $proceso === "cobraCliente" && $rol === "CAJERO") {
    $metodo = $_POST["metodo_pago"] ?? null;

    if (!$nrotramite || !$metodo) {
        echo "<p style='color:red;'>❌ Faltan datos.</p>";
        exit;
    }

    $conn->query("INSERT INTO flujoseguimiento (nro_tramite, flujo, proceso, usuario, fecha, observacion)
                  VALUES ($nrotramite, '$flujo', 'cobraCliente', '$usuario', '$fecha', 'Pago $metodo')");

    $conn->query("UPDATE tramite SET estado = 'FINALIZADO' WHERE nro_tramite = $nrotramite");

    echo "<div style='padding:20px;text-align:center;font-family:sans-serif;'>
            <h3 style='color:green;'>✅ Trámite finalizado correctamente.</h3>
            <a href='/procesos/generar_comprobante.php?nro=$nrotramite' target='_blank'>📄 Descargar comprobante en PDF</a>

            <a href='usuarios/cajero.php' style='padding:10px 20px;background-color:#4CAF50;color:white;border-radius:5px;text-decoration:none;'>⬅️ Volver al panel</a>
            <a href='logout.php' style='padding:10px 20px;background-color:#f44336;color:white;border-radius:5px;text-decoration:none;'>🔙 Cerrar sesión</a>
          </div>";
    exit;
}



   

