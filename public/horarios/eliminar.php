<?php
session_start();
require '../../config/database.php';

if (isset($_GET['id'])) {
    $pdo = getPDO();
    $stmt = $pdo->prepare("DELETE FROM horarios WHERE id_horario = ?");
    $stmt->execute([(int)$_GET['id']]);

    $_SESSION['flash'] = ['type' => 'success', 'message' => 'Horario eliminado correctamente.'];
}

header('Location: listar.php');
exit;
?>