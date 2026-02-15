<?php
// public/index.php
session_start();
require '../config/database.php';

// Si ya está logueado → dashboard, sino → login
if (isset($_SESSION['usuario'])) {
    header('Location: dashboard.php');
    exit;
}
require '../views/auth/login.view.php';
?>