<?php
session_start();
require '../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $inicio = $_POST['hora_inicio'];
    $fin    = $_POST['hora_fin'];

    if ($fin <= $inicio) {
        $_SESSION['flash'] = ['type' => 'danger', 'message' => 'La hora de fin debe ser posterior a la de inicio.'];
        header('Location: añadir.php');
        exit;
    }

    $pdo = getPDO();
    $stmt = $pdo->prepare("INSERT INTO horarios 
        (hora_inicio, hora_fin, capacidad, tipo, descripcion) 
        VALUES (?, ?, ?, ?, ?)");
    
    $stmt->execute([
        $inicio, $fin, (int)$_POST['capacidad'],
        $_POST['tipo'], trim($_POST['descripcion'])
    ]);

    $_SESSION['flash'] = ['type' => 'success', 'message' => 'Horario creado correctamente.'];
    header('Location: listar.php');
    exit;
}

require '../../views/horarios/añadir.view.php';
?>