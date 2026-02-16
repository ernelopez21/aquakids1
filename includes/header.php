<?php
// includes/header.php
//session_start();

require_once __DIR__ . '/../config/database.php';

// ← CRÍTICO: Creamos la variable $pdo aquí para que esté disponible en todas las vistas
$pdo = getPDO();   // Esta es la línea que faltaba

// Flash message
$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

$pageTitle = $pageTitle ?? 'Aquakids - Panel Administrativo';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($pageTitle) ?></title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        .sidebar { min-height: 100vh; background: #1a2634; width: 260px; }
        .nav-link { color: #adb5bd !important; padding: 0.75rem 1rem; border-radius: 6px; }
        .nav-link:hover, .nav-link.active { background: #0d6efd; color: white !important; }
        body { background: #f8f9fa; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold fs-4" href="../public/dashboard.php">AQUAKIDS</a>
        <div class="navbar-nav ms-auto">
            <a class="nav-link" href="../public/logout.php"><i class="bi bi-box-arrow-right"></i> Salir</a>
        </div>
    </div>
</nav>

<div class="d-flex">
    <!-- Sidebar -->
    <div class="sidebar text-white p-3">
        <div class="text-center mb-4">
            <i class="bi bi-person-circle fs-1 mb-2"></i>
            <p class="mb-0 fw-bold">Administrador</p>
        </div>
        <ul class="nav flex-column">
            <li><a href="../dashboard.php" class="nav-link <?= str_contains($_SERVER['REQUEST_URI'], 'dashboard') ? 'active' : '' ?>"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a></li>
            <li><a href="../alumnos/listar.php" class="nav-link <?= str_contains($_SERVER['REQUEST_URI'], 'alumnos') ? 'active' : '' ?>"><i class="bi bi-people me-2"></i>Alumnos</a></li>
            <li><a href="../instructores/listar.php" class="nav-link"><i class="bi bi-person-badge me-2"></i>Instructores</a></li>
            <li><a href="../horarios/listar.php" class="nav-link"><i class="bi bi-calendar3 me-2"></i>Horarios</a></li>
            <li><a href="../dias/listar.php" class="nav-link"><i class="bi bi-clock me-2"></i>Días</a></li>
            <li><a href="../pagos/listar.php" class="nav-link"><i class="bi bi-currency-dollar me-2"></i>Pagos</a></li>
        </ul>
    </div>

    <!-- Contenido principal -->
    <main class="flex-grow-1 p-4">
        <div class="container-fluid">