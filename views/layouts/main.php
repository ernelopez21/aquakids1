<?php
// views/layouts/main.php - Layout común (completo y balanceado)
if (!isset($_SESSION['usuario'])) {
    header('Location: ../../public/index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AquaKids - Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/2.0.8/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        .sidebar { background: #1e3a8a; min-height: 100vh; }
        .flash { position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 300px; }
    </style>
</head> <!-- CIERRE EXPLÍCITO DE HEAD -->
<body class="d-flex">
    <!-- Menú lateral -->
    <div class="sidebar p-3" style="width: 260px;">
        <h4 class="text-white mb-4">AquaKids</h4>
        <ul class="nav flex-column">
            <li class="nav-item"><a href="../dashboard.php" class="nav-link text-white">🏠 Dashboard</a></li>
            <li class="nav-item"><a href="../alumnos/listar.php" class="nav-link text-white">👥 Alumnos</a></li>
            <li class="nav-item"><a href="../horarios/listar.php" class="nav-link text-white">⏰ Horarios</a></li>
            <li class="nav-item"><a href="#" class="nav-link text-white">👨‍🏫 Instructores</a></li>
            <li class="nav-item"><a href="../pagos/listar.php" class="nav-link text-white">💰 Pagos</a></li>
            <li class="nav-item"><a href="../logout.php" class="nav-link text-danger">🚪 Cerrar Sesión</a></li>
        </ul>
    </div>

    <!-- Contenido principal -->
    <div class="flex-grow-1 p-4">
        <!-- Mensajes flash -->
        <?php if (isset($_SESSION['flash'])): ?>
            <div class="alert alert-<?= $_SESSION['flash']['type'] ?> flash shadow">
                <?= htmlspecialchars($_SESSION['flash']['message']) ?>
            </div>
            <?php unset($_SESSION['flash']); ?>
        <?php endif; ?>

        <nav class="navbar navbar-light bg-light mb-4 rounded shadow-sm">
            <div class="container-fluid">
                <span class="navbar-brand mb-0 h1">Bienvenido, <?= htmlspecialchars($_SESSION['usuario']) ?></span>
            </div>
        </nav>
        <!-- Aquí va el contenido de cada vista -->