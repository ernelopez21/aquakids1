<?php
session_start();
require '../../config/database.php';

if (isset($_GET['id'])) {
    $pdo = getPDO();
    $stmt = $pdo->prepare("DELETE FROM alumnos WHERE id_alumno = ?");
    $stmt->execute([(int)$_GET['id']]);
}

header('Location: listar.php');
exit;
?>