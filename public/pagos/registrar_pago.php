<?php
// public/pagos/registrar_pago.php
header('Content-Type: application/json');
require_once dirname(__FILE__) . '/../../controllers/tarjetas_controller.php';

$data = json_decode(file_get_contents('php://input'), true);

if (!empty($data['id_ficha']) && !empty($data['mes']) && !empty($data['anio']) && $data['monto'] > 0 && !empty($data['fecha_pago'])) {
    $resultado = registrarPagoMensual($data['id_ficha'], $data['mes'], $data['anio'], $data['monto'], $data['fecha_pago']);
    echo json_encode($resultado);
} else {
    echo json_encode(['success' => false, 'error' => 'Datos inválidos']);
}
?>