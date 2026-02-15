<?php
session_start();
require '../../config/database.php';

$pdo = getPDO();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("UPDATE pagos SET fecha_pago = ?, monto = ? WHERE id_pago = ?");
    $stmt->execute([
        $_POST['fecha_pago'],
        $_POST['monto'],
        (int)$_POST['id_pago']
    ]);
    header('Location: listar.php?success=1');
    exit;
}

// Cargar datos del pago
$id = (int)$_GET['id'];
$stmt = $pdo->prepare("SELECT p.*, a.nombre, a.apellido FROM pagos p JOIN alumnos a ON p.id_alumno = a.id_alumno WHERE p.id_pago = ?");
$stmt->execute([$id]);
$pago = $stmt->fetch();

require '../../views/pagos/editar.view.php';
?>