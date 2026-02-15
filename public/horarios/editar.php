<?php
session_start();
require '../../config/database.php';

$pdo = getPDO();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $inicio = $_POST['hora_inicio'];
    $fin    = $_POST['hora_fin'];

    if ($fin <= $inicio) {
        $_SESSION['flash'] = ['type' => 'danger', 'message' => 'La hora de fin debe ser posterior a la de inicio.'];
        header('Location: editar.php?id=' . $_POST['id_horario']);
        exit;
    }

    $stmt = $pdo->prepare("UPDATE horarios SET 
        hora_inicio=?, hora_fin=?, capacidad=?, tipo=?, descripcion=? 
        WHERE id_horario=?");
    
    $stmt->execute([
        $inicio, $fin, (int)$_POST['capacidad'],
        $_POST['tipo'], trim($_POST['descripcion']),
        (int)$_POST['id_horario']
    ]);

    $_SESSION['flash'] = ['type' => 'success', 'message' => 'Horario actualizado correctamente.'];
    header('Location: listar.php');
    exit;
}

// Cargar datos
$id = (int)$_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM horarios WHERE id_horario = ?");
$stmt->execute([$id]);
$horario = $stmt->fetch();

require '../../views/horarios/editar.view.php';
?>