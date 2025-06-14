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
            <a href='procesos/generar_comprobante.php?nro=$nrotramite' target='_blank'>📄 Descargar comprobante en PDF</a>

            <a href='usuarios/cajero.php' style='padding:10px 20px;background-color:#4CAF50;color:white;border-radius:5px;text-decoration:none;'>⬅️ Volver al panel</a>
            <a href='logout.php' style='padding:10px 20px;background-color:#f44336;color:white;border-radius:5px;text-decoration:none;'>🔙 Cerrar sesión</a>
          </div>";
    exit;
}

// ========== ALMACÉN solicita compra a proveedor ==========
if ($flujo === "F2_compra_proveedor" && $proceso === "solicitaCompra" && $rol === "ALMACEN") {
    $producto_id = $_POST["producto_id"] ?? null;
    $cantidad = $_POST["cantidad"] ?? null;
    $motivo = $_POST["motivo"] ?? null;

    if (!$producto_id || !$cantidad || !$motivo) {
        echo "<p style='color:red;'>❌ Faltan datos en la solicitud.</p>";
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO compra (producto_id, cantidad, motivo) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $producto_id, $cantidad, $motivo);
    $stmt->execute();

    echo "<div style='padding:20px; text-align:center; font-family:sans-serif;'>
            <h3 style='color:green;'>✅ Solicitud de compra registrada correctamente.</h3>
            <a href='usuarios/almacen.php' style='padding:10px 20px; background-color:#4CAF50; color:white; border-radius:5px; text-decoration:none;'>⬅️ Volver al panel</a>
            <a href='logout.php' style='padding:10px 20px; background-color:#f44336; color:white; border-radius:5px; text-decoration:none;'>🔙 Cerrar sesión</a>
          </div>";
    exit;
}


   

// ========== ALMACEN recibe productos del proveedor ==========
if ($flujo === "F2_compra_proveedor" && $proceso === "recibeCompra" && $rol === "ALMACEN") {
    $compra_id   = $_POST["compra_id"] ?? null;
    $producto_id = $_POST["producto_id"] ?? null;
    $cantidad    = $_POST["cantidad"] ?? 0;

    if (!$compra_id || !$producto_id || $cantidad <= 0) {
        echo "<p style='color:red;'>❌ Datos inválidos para confirmar recepción.</p>";
        exit;
    }

    // 1. Actualizar estado de la compra
    $conn->query("UPDATE compra SET estado = 'ENTREGADA' WHERE id = $compra_id");

    // 2. Aumentar el stock
    $conn->query("UPDATE producto SET stock = stock + $cantidad WHERE id = $producto_id");

    echo "<div style='padding:20px;text-align:center;font-family:sans-serif;'>
            <h3 style='color:green;'>✅ Productos recibidos correctamente.</h3>
            <a href='usuarios/almacen.php' style='padding:10px 20px;background-color:#4CAF50;color:white;border-radius:5px;text-decoration:none;'>⬅️ Volver al panel</a>
            <a href='logout.php' style='padding:10px 20px;background-color:#f44336;color:white;border-radius:5px;text-decoration:none;'>🔙 Cerrar sesión</a>
          </div>";
    exit;
}
// ========== ADMIN o VENDEDOR aprueba o rechaza compra ==========
if ($flujo === "F2_compra_proveedor" && $proceso === "apruebaCompra" && in_array($rol, ["ADMIN", "VENDEDOR"])) {
    $id     = $_POST["id"] ?? null;
    $accion = $_POST["accion"] ?? null;

    if (!$id || !$accion) {
        echo "<p style='color:red;'>❌ Datos incompletos.</p>";
        exit;
    }

    if ($accion === "aprobar") {
        $conn->query("UPDATE compra SET estado = 'APROBADA' WHERE id = $id");
        echo "<p style='color:green;'>✅ Solicitud de compra aprobada.</p>";
    } elseif ($accion === "rechazar") {
        $conn->query("UPDATE compra SET estado = 'RECHAZADA' WHERE id = $id");
        echo "<p style='color:orange;'>❌ Solicitud de compra rechazada.</p>";
    }

    echo "<p><a href='procesos/apruebaCompra.php'>🔄 Volver a la lista</a></p>";
    echo "<p><a href='menu.php'>⬅️ Menú principal</a></p>";
    exit;
}
// ========== CLIENTE solicita devolución ==========
if ($flujo === "F3_devolucion" && $proceso === "solicitaDevolucion" && $rol === "CLIENTE") {
    $nro    = $_POST["nro_tramite"] ?? null;
    $motivo = trim($_POST["motivo"] ?? '');

    if (!$nro || !$motivo) {
        echo "<p style='color:red;'>❌ Datos incompletos.</p>";
        exit;
    }

    $yaExiste = $conn->query("SELECT 1 FROM devolucion WHERE nro_tramite = $nro");
    if ($yaExiste->num_rows > 0) {
        echo "<p style='color:red;'>❌ Ya existe una solicitud de devolución para este trámite.</p>";
    } else {
        $conn->query("INSERT INTO devolucion (nro_tramite, motivo) VALUES ($nro, '$motivo')");
        echo "<p style='color:green;'>✅ Solicitud de devolución enviada correctamente.</p>";
    }

    echo "<p><a href='procesos/solicitaDevolucion.php'>🔁 Volver a Devolución</a></p>";
    echo "<p><a href='usuarios/cliente.php'>⬅️ Volver al panel</a></p>";
    echo "<p><a href='logout.php'>🔙 Cerrar sesión</a></p>";
    exit;
}
// ========== VENDEDOR aprueba devolución ==========
if ($flujo === "F3_devolucion" && $proceso === "apruebaDevolucion" && $rol === "VENDEDOR") {
    $id     = $_POST["id"] ?? null;
    $accion = $_POST["accion"] ?? '';

    if (!$id || !in_array($accion, ["APROBADA", "RECHAZADA"])) {
        echo "<p style='color:red;'>❌ Parámetros inválidos.</p>";
        exit;
    }

    $conn->query("UPDATE devolucion SET estado = '$accion' WHERE id = $id");

    echo "<p style='color:green;'>✅ Devolución $accion correctamente.</p>";
    echo "<p><a href='procesos/apruebaDevolucion.php'>⬅️ Volver a solicitudes</a></p>";
    echo "<p><a href='usuarios/vendedor.php'>🏠 Panel Vendedor</a></p>";
    echo "<p><a href='logout.php'>🔙 Cerrar sesión</a></p>";
    exit;
}
// ========== ALMACÉN recibe producto devuelto ==========
if ($flujo === "F3_devolucion" && $proceso === "recibeProducto" && $rol === "ALMACEN") {
    $id = $_POST["id"] ?? null;

    if (!$id) {
        echo "<p style='color:red;'>❌ Falta el ID de devolución.</p>";
        exit;
    }

    $conn->query("UPDATE devolucion SET estado = 'FINALIZADA' WHERE id = $id");

    echo "<p style='color:green;'>✅ Producto recibido correctamente y devolución finalizada.</p>";
    echo "<p><a href='procesos/recibeProducto.php'>⬅️ Volver</a></p>";
    echo "<p><a href='usuarios/almacen.php'>🏠 Panel Almacén</a></p>";
    echo "<p><a href='logout.php'>🔙 Cerrar sesión</a></p>";
    exit;
}
// ========== CAJERO procesa reembolso ==========
if ($flujo === "F3_devolucion" && $proceso === "procesaReembolso" && $rol === "CAJERO") {
    $id = $_POST["id"] ?? null;
    $metodo = $_POST["metodo"] ?? null;

    if (!$id || !$metodo) {
        echo "<p style='color:red;'>❌ Faltan datos.</p>";
        exit;
    }

    $conn->query("UPDATE devolucion SET estado = 'REEMBOLSADO' WHERE id = $id");

    echo "<p style='color:green;'>✅ Reembolso procesado exitosamente por $metodo.</p>";
    echo "<p><a href='procesos/procesaReembolso.php'>⬅️ Volver</a></p>";
    echo "<p><a href='usuarios/cajero.php'>🏠 Panel Cajero</a></p>";
    echo "<p><a href='logout.php'>🔙 Cerrar sesión</a></p>";
    exit;
}
// ========== CAJERO paga proveedor ==========
if ($flujo === "F2_compra_proveedor" && $proceso === "pagaProveedor" && $rol === "CAJERO") {
    $compra_id   = $_POST["compra_id"] ?? null;
    $metodo_pago = $_POST["metodo_pago"] ?? null;

    if (!$compra_id || !$metodo_pago) {
        echo "<p style='color:red;'>❌ Faltan datos para procesar el pago.</p>";
        exit;
    }

    $conn->query("UPDATE compra SET estado = 'PAGADA' WHERE id = $compra_id");

    echo "<div style='padding:20px;text-align:center;font-family:sans-serif;'>
            <h3 style='color:green;'>✅ Pago registrado exitosamente por $metodo_pago.</h3>
            <a href='usuarios/cajero.php' style='padding:10px 20px;background-color:#4CAF50;color:white;border-radius:5px;text-decoration:none;'>⬅️ Volver al panel</a>
            <a href='logout.php' style='padding:10px 20px;background-color:#f44336;color:white;border-radius:5px;text-decoration:none;'>🔙 Cerrar sesión</a>
          </div>";
    exit;
}
