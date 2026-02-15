<?php
session_start();
require '../../config/database.php';

if (isset($_GET['id'])) {
    $pdo = getPDO();
    $stmt = $pdo->prepare("DELETE FROM pagos WHERE id_pago = ?");
    $stmt->execute([(int)$_GET['id']]);
    
    // Redirección con mensaje de éxito
    header('Location: listar.php?success=1');
    exit;
}

// Por seguridad, si alguien accede directamente
header('Location: listar.php');
exit;
?>