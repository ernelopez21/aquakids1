<?php
// public/reset_admin.php → ejecútalo UNA sola vez
require '../config/database.php';

$pdo = getPDO();

$password = 'admin123';
$hash = password_hash($password, PASSWORD_DEFAULT);

$pdo->exec("DELETE FROM users WHERE usuario = 'admin'");

$stmt = $pdo->prepare("INSERT INTO users (usuario, pass, rol) VALUES ('admin', ?, 'admin')");
$stmt->execute([$hash]);

echo "<h1 style='color:green'>✅ Usuario admin reiniciado correctamente</h1>";
echo "Usuario: <b>admin</b><br>";
echo "Contraseña: <b>$password</b><br><br>";
echo "<a href='index.php' class='btn btn-primary btn-lg'>→ Ir al login ahora</a>";
?>