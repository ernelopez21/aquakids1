<?php
// public/pagos/nueva_tarjeta.php
// Loader corregido con paths robustos y chequeo de archivos

// Inicia sesión para mensajes (si no está)
session_start();

// Paths corregidos: asumiendo includes/ para header/footer (basado en tu GitHub)
$header_path = dirname(__FILE__) . '/../../includes/header.php';
$vista_path = dirname(__FILE__) . '/../../views/pagos/nueva_tarjeta.view.php';
$footer_path = dirname(__FILE__) . '/../../includes/footer.php';

// Variante si están en views/layouts/ (descomenta si es tu caso)
// $header_path = dirname(__FILE__) . '/../../views/layouts/header.php';
// $footer_path = dirname(__FILE__) . '/../../views/layouts/footer.php';

// Chequeo para depuración
if (!file_exists($header_path)) { die('Error: Header no encontrado en ' . $header_path); }
if (!file_exists($vista_path)) { die('Error: Vista no encontrada en ' . $vista_path); }
if (!file_exists($footer_path)) { die('Error: Footer no encontrado en ' . $footer_path); }

require_once $header_path;  // Tu header común
require_once $vista_path;   // La vista del form
require_once $footer_path;  // Tu footer común
?>