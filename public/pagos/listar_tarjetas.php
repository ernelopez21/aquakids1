<?php
// public/pagos/listar_tarjeta.php
// Loader para el listado

session_start();

require_once dirname(__FILE__) . '/../../includes/header.php';  // Ajusta a tu header
require_once dirname(__FILE__) . '/../../views/pagos/listar_tarjetas.view.php';
require_once dirname(__FILE__) . '/../../includes/footer.php';  // Ajusta a tu footer
?>