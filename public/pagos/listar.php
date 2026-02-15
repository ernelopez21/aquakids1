<?php
session_start();
require '../../config/database.php';

$pdo = getPDO();

// Función vencido (rojo si > 30 días desde último pago)
function esVencido($fecha_pago) {
    if (!$fecha_pago) return true;
    $ultimo = new DateTime($fecha_pago);
    $hoy = new DateTime();
    return $hoy->diff($ultimo)->days > 30;
}

$pagos = $pdo->query("
    SELECT p.*, a.nombre, a.apellido 
    FROM pagos p 
    JOIN alumnos a ON p.id_alumno = a.id_alumno 
    ORDER BY p.fecha_pago DESC
")->fetchAll();

require '../../views/pagos/listar.view.php';
?>