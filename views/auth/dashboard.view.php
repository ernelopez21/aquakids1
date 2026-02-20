<?php
// views/auth/dashboard.view.php - Versión temporal SIMPLE (sin layout)
require '../config/database.php';
if (!isset($_SESSION['usuario'])) {
    header('Location: ../../public/index.php');
    exit;
}

// === CONSULTA PAGOS VENCIDOS ===
$pdo = getPDO();
$vencidos = $pdo->query("
    SELECT COUNT(*) FROM (
        SELECT a.id_alumno
        FROM alumnos a
        LEFT JOIN pagos p ON a.id_alumno = p.id_alumno
        GROUP BY a.id_alumno
        HAVING COALESCE(MAX(p.fecha_pago), '2000-01-01') < DATE_SUB(CURDATE(), INTERVAL 30 DAY)
    ) AS sub
")->fetchColumn();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AquaKids - Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <nav class="navbar navbar-dark bg-primary">
        <div class="container-fluid">
            <span class="navbar-brand">AquaKids</span>
            <a href="../../public/logout.php" class="btn btn-outline-light">Cerrar Sesión</a>
        </div>
    </nav>

    <div class="container py-4">
        <h1 class="mb-5">Panel de Administración</h1>
        
        <!-- TARJETA PAGOS VENCIDOS -->
        <div class="row mb-5">
            <div class="col-md-6">
                <div class="card text-white <?= $vencidos > 0 ? 'bg-danger' : 'bg-success' ?> shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Pagos Vencidos</h5>
                        <h2 class="mb-0"><?= $vencidos ?></h2>
                        <a href="../public/pagos/listar.php" class="text-white stretched-link">
                            Ver todos los vencidos →
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">

            <!-- Alumnos -->
            <div class="col-md-4">
                <a href="../public/alumnos/listar.php" 
                class="btn btn-primary w-100 py-5 fs-5 text-start shadow-sm d-flex align-items-center">
                    👥 Gestionar Alumnos
                </a>
            </div>

            <!-- Horarios -->
            <div class="col-md-4">
                <a href="../public/horarios/listar.php" 
                class="btn btn-primary w-100 py-5 fs-5 text-start shadow-sm d-flex align-items-center">
                    ⏰ Gestionar Horarios
                </a>
            </div>

            <!-- Pagos -->
            <div class="col-md-4">
                <a href="../public/pagos/listar.php" 
                class="btn btn-primary w-100 py-5 fs-5 text-start shadow-sm d-flex align-items-center">
                    💰 Gestionar Pagos
                </a>
            </div>

                        <!-- En views/dashboard.view.php o donde esté el dashboard, reemplaza la card de "Gestionar Pagos" -->
            <div class="col-md-4 mb-3">
                <div class="card text-white bg-primary" x-data="{ open: false }">
                    <div class="card-body">
                        <h5 class="card-title">Gestionar Pagos</h5>
                        <button class="btn btn-light btn-sm w-100" @click="open = !open">
                            <span x-text="open ? 'Ocultar Opciones' : 'Ver Opciones'"></span>
                        </button>
                        <div x-show="open" class="mt-3">
                            <a href="pagos/nueva_tarjeta.php" class="btn btn-secondary btn-block mb-2">Crear nueva tarjeta de pago</a>
                            <a href="pagos/listar_tarjetas.php" class="btn btn-secondary btn-block">Listar tarjetas de pagos</a>
                        </div>
                    </div>
                </div>
            </div>



            <!-- Instructores (desactivado por ahora) -->
            <div class="col-md-4">
                <a href="#" 
                class="btn btn-secondary w-100 py-5 fs-5 text-start shadow-sm d-flex align-items-center disabled">
                    👨‍🏫 Instructores
                </a>
            </div>

        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/alpinejs@3/dist/cdn.min.js" defer></script>
</body>
</html>

<?php require '../views/layouts/footer.php'; ?>