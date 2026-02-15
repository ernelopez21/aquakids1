<?php
session_start();
require '../../config/database.php';

$pdo = getPDO();
$alumnos = $pdo->query("SELECT * FROM alumnos ORDER BY nombre")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("INSERT INTO pagos (id_alumno, fecha_pago, monto) VALUES (?, ?, ?)");
    $stmt->execute([$_POST['id_alumno'], $_POST['fecha_pago'], $_POST['monto'] ?? 500]);

    header('Location: listar.php?success=1');
    exit;
}

require '../../views/pagos/añadir.view.php';
?>