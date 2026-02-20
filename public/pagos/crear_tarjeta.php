<?php
// public/pagos/crear_tarjeta.php
session_start();
// Ruta corregida: sube dos niveles a controllers/
require_once dirname(__FILE__) . '/../../controllers/tarjetas_controller.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_tutor     = $_POST['nombre_tutor'] ?? '';
    $monto_inscripcion = $_POST['monto_inscripcion'] ?? 0;
    $alumnos_str      = $_POST['alumnos'] ?? '';
    $notas            = $_POST['notas'] ?? '';

    $alumnos_ids = array_filter(explode(',', $alumnos_str)); // elimina vacíos

    $resultado = crearTarjeta($nombre_tutor, $monto_inscripcion, $alumnos_ids, $notas);

    if ($resultado['success']) {
        $_SESSION['mensaje'] = "✅ Tarjeta de pago creada correctamente (ID: {$resultado['id_ficha']})";
        header('Location: ../pagos/listar_tarjeta.php');  // O donde tengas tu listado de pagos
        exit;
    } else {
        $_SESSION['error'] = "❌ Error: " . $resultado['error'];
        header('Location: ../pagos/nueva_tarjeta.php');
        exit;
    }
}

// Si alguien entra directo por GET, redirige al formulario
header('Location: ../pagos/nueva_tarjeta.php');
exit;
?>