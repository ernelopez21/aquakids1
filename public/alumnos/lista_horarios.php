<?php
session_start();
require '../../config/database.php';

$pdo = getPDO();

$dias = $pdo->query("SELECT * FROM dias ORDER BY nombre_dia")->fetchAll();
$horarios = $pdo->query("SELECT * FROM horarios ORDER BY hora_inicio")->fetchAll();
?>

<?php require '../../views/layouts/main.php'; ?>

<div class="container py-4">
    <h2 class="mb-4">📅 Horarios Disponibles</h2>

    <?php foreach ($dias as $dia): ?>
        <div class="mb-5">
            <h4 class="border-bottom pb-2 mb-3 text-primary">
                <?= htmlspecialchars($dia['nombre_dia']) ?>
            </h4>
            
            <div class="row g-4">
                <?php foreach ($horarios as $h): 
                    $stmt = $pdo->prepare("
                        SELECT COUNT(*) 
                        FROM alumnos_dia_horario 
                        WHERE id_dia = ? AND id_horario = ?
                    ");
                    $stmt->execute([$dia['id_dia'], $h['id_horario']]);
                    $ocupados = $stmt->fetchColumn();

                    $capacidad = (int)$h['capacidad'];
                    $libres = $capacidad - $ocupados;
                ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="card shadow-sm h-100">
                            <div class="card-body">
                                <!-- Header -->
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <h5 class="mb-0">
                                        <?= substr($h['hora_inicio'],0,5) ?> - <?= substr($h['hora_fin'],0,5) ?>
                                    </h5>
                                    <?php if ($libres > 0): ?>
                                        <span class="badge bg-success fs-6"><?= $libres ?> libres de <?= $capacidad ?></span>
                                    <?php else: ?>
                                        <span class="badge bg-danger fs-6">Lleno (0 de <?= $capacidad ?>)</span>
                                    <?php endif; ?>
                                </div>

                                <!-- Tabla con nombre clicable -->
                                <table class="table table-sm table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>Alumno</th>
                                            <th>Edad</th>
                                            <th>Último Pago</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $stmt = $pdo->prepare("
                                            SELECT a.*, 
                                                   (SELECT MAX(fecha_pago) FROM pagos WHERE id_alumno = a.id_alumno) AS ultimo_pago
                                            FROM alumnos a
                                            JOIN alumnos_dia_horario ad ON a.id_alumno = ad.id_alumno
                                            WHERE ad.id_dia = ? AND ad.id_horario = ?
                                            ORDER BY a.nombre, a.apellido
                                        ");
                                        $stmt->execute([$dia['id_dia'], $h['id_horario']]);
                                        $alumnos = $stmt->fetchAll();

                                        foreach ($alumnos as $a): 
                                            $edad = $a['fecha_nacimiento'] 
                                                ? (new DateTime($a['fecha_nacimiento']))->diff(new DateTime())->y 
                                                : '-';
                                        ?>
                                            <tr>
                                                <td>
                                                    <a href="editar.php?id=<?= $a['id_alumno'] ?>" 
                                                       class="text-decoration-none text-primary fw-bold">
                                                        <?= htmlspecialchars($a['nombre'].' '.$a['apellido']) ?>
                                                    </a>
                                                </td>
                                                <td><?= $edad ?> años</td>
                                                <td>
                                                    <?= $a['ultimo_pago'] 
                                                        ? '<span class="badge bg-success">'.date('d/m/Y', strtotime($a['ultimo_pago'])).'</span>' 
                                                        : '<span class="badge bg-danger">Sin pago</span>' ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>

                                        <?php if (empty($alumnos)): ?>
                                            <tr>
                                                <td colspan="3" class="text-center text-muted py-3">
                                                    Sin alumnos asignados
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php require '../../views/layouts/footer.php'; ?>