<?php
// public/login.php → VERSIÓN DEBUG (solo para probar conexión)
session_start();
require '../config/database.php';

echo "<h2>DEBUG CONEXIÓN Y LOGIN</h2>";

// 1. Probar conexión
try {
    $pdo = getPDO();
    echo "<span style='color:green'>✅ Conexión a BD exitosa</span><br><br>";
} catch (Exception $e) {
    die("<span style='color:red'>❌ Error de conexión: " . htmlspecialchars($e->getMessage()) . "</span>");
}

// 2. Probar consulta del usuario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario  = trim($_POST['usuario']);
    $password = $_POST['password'];

    echo "Usuario ingresado: <b>" . htmlspecialchars($usuario) . "</b><br>";

    $stmt = $pdo->prepare('SELECT * FROM users WHERE usuario = :usuario');
    $stmt->execute([':usuario' => $usuario]);
    $user = $stmt->fetch();

    if ($user) {
        echo "✅ Usuario encontrado en BD: " . htmlspecialchars($user['usuario']) . "<br>";
        echo "Hash en BD: " . htmlspecialchars($user['pass']) . "<br>";

        if (password_verify($password, $user['pass'])) {
            echo "<span style='color:green;font-size:20px'>✅ Contraseña CORRECTA → Login exitoso</span>";
            $_SESSION['usuario'] = $user['usuario'];
            $_SESSION['rol'] = $user['rol'] ?? 'admin';
            header('Location: dashboard.php');
            exit;
        } else {
            echo "<span style='color:red'>❌ Contraseña INCORRECTA</span><br>";
            echo "Contraseña ingresada: <b>" . htmlspecialchars($password) . "</b>";
        }
    } else {
        echo "<span style='color:red'>❌ Usuario NO encontrado en la tabla users</span>";
    }
} else {
    echo "Página cargada correctamente (GET). Envía el formulario para probar login.";
}

// Mostrar formulario de nuevo
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>DEBUG Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-5">
    <div class="card">
        <div class="card-body">
            <form method="POST">
                <div class="mb-3">
                    <input type="text" name="usuario" class="form-control" placeholder="Usuario" value="admin" required>
                </div>
                <div class="mb-3">
                    <input type="password" name="password" class="form-control" placeholder="Contraseña" value="admin123" required>
                </div>
                <button type="submit" class="btn btn-primary">Probar Login</button>
            </form>
        </div>
    </div>
</body>
</html>