<?php
session_start();
include "../conectar.inc.php";

// Verificar rol
if ($_SESSION["rol"] !== "CLIENTE") {
    echo "<p style='color:red;'>❌ Acceso denegado.</p>";
    exit;
}

$usuario = $_SESSION["usuario"];
$res = $conn->query("SELECT id FROM usuario WHERE usuario = '$usuario'");
$cliente_id = $res->fetch_assoc()['id'];

$mensaje = "";

// Registrar devolución
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nro = $_POST["nro_tramite"];
    $motivo = trim($_POST["motivo"]);

    // Verificar si ya existe una devolución para ese trámite
    $verifica = $conn->query("SELECT 1 FROM devolucion WHERE nro_tramite = $nro");
    if ($verifica->num_rows > 0) {
        $mensaje = "<p style='color:red;'>❌ Ya existe una solicitud de devolución para ese trámite.</p>";
    } else {
        $conn->query("INSERT INTO devolucion (nro_tramite, motivo) VALUES ($nro, '$motivo')");
        $mensaje = "<p style='color:green;'>✅ Solicitud de devolución enviada. Será evaluada por el vendedor.</p>";
    }
}

// Obtener trámites finalizados
$query = "
SELECT t.nro_tramite
FROM tramite t
WHERE t.estado = 'FINALIZADO' AND t.cliente_id = $cliente_id
AND NOT EXISTS (
    SELECT 1 FROM devolucion d WHERE d.nro_tramite = t.nro_tramite
)
ORDER BY t.nro_tramite DESC
";
$result = $conn->query($query);
?>

<h2>🔁 Solicitar Devolución</h2>

<?= $mensaje ?>

<?php if ($result->num_rows > 0): ?>
<form method="POST" action="../controlador.php">
    <input type="hidden" name="flujo" value="F3_devolucion">
    <input type="hidden" name="proceso" value="solicitaDevolucion">

    <label>Seleccione el trámite:</label>
    <select name="nro_tramite" required>
        <?php while ($row = $result->fetch_assoc()): ?>
            <option value="<?= $row['nro_tramite'] ?>">Trámite #<?= $row['nro_tramite'] ?></option>
        <?php endwhile; ?>
    </select><br><br>

    <label>Motivo de devolución:</label><br>
    <textarea name="motivo" rows="4" cols="50" required></textarea><br><br>

    <input type="submit" value="Enviar solicitud">
</form>

<?php else: ?>
    <p>No tienes trámites finalizados disponibles para devolución.</p>
<?php endif; ?>

<p><a href="../usuarios/cliente.php">⬅️ Volver</a></p>
<p><a href="../logout.php">🔙 Cerrar sesión</a></p>
