<?php require '../../views/layouts/main.php'; ?>

<?php
function calcularEdad($fecha) {
    if (!$fecha) return '-';
    $dob = new DateTime($fecha);
    $hoy = new DateTime();
    return $hoy->diff($dob)->y;
}
?>

<!-- Bootstrap Icons CDN -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>📅 Resumen de Alumnos por Día</h2>
        <button id="toggleAllBtn" onclick="toggleAll()" class="btn btn-outline-secondary">
            Contraer Todos
        </button>
    </div>

    <?php
    $pdo = getPDO();
    $dias = $pdo->query("SELECT * FROM dias ORDER BY nombre_dia")->fetchAll();

    foreach ($dias as $dia):
        $id_dia = $dia['id_dia'];
        $nombre_dia = $dia['nombre_dia'];
    ?>
        <div class="card mb-4">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><?= htmlspecialchars($nombre_dia) ?></h5>
                
                <!-- Botón con icono -->
                <button class="btn btn-sm btn-light" 
                        data-bs-toggle="collapse" 
                        data-bs-target="#body-<?= $id_dia ?>" 
                        aria-expanded="true"
                        onclick="toggleIcon(this)">
                    <i class="bi bi-chevron-down"></i>
                </button>
            </div>

            <div id="body-<?= $id_dia ?>" class="card-body collapse show">

                <?php
                $horarios = $pdo->prepare("
                    SELECT h.*, 
                           (SELECT COUNT(*) FROM alumnos_dia_horario WHERE id_horario = h.id_horario) AS inscritos
                    FROM horarios h
                    ORDER BY h.hora_inicio
                ");
                $horarios->execute();
                $horariosDia = $horarios->fetchAll();

                if (empty($horariosDia)) {
                    echo '<p class="text-muted">No hay horarios para este día.</p>';
                    continue;
                }

                foreach ($horariosDia as $h):
                    $inscritos = $h['inscritos'];
                    $capacidad = $h['capacidad'] ?? 15;
                    $libres = $capacidad - $inscritos;
                    $rango = substr($h['hora_inicio'], 0, 5) . ' - ' . substr($h['hora_fin'], 0, 5);

                    $color = ($libres >= 5) ? 'bg-success' : (($libres >= 1) ? 'bg-warning text-dark' : 'bg-danger');
                ?>
                    <div class="mb-4 border-bottom pb-3">
                        <h6 class="text-primary">
                            <?= $rango ?> 
                            <span class="badge <?= $color ?>"><?= $libres ?> libres de <?= $capacidad ?></span>
                        </h6>

                        <?php
                        $stmt = $pdo->prepare("
                            SELECT a.*, MAX(p.fecha_pago) AS ultimo_pago
                            FROM alumnos a
                            JOIN alumnos_dia_horario ad ON a.id_alumno = ad.id_alumno
                            LEFT JOIN pagos p ON a.id_alumno = p.id_alumno
                            WHERE ad.id_dia = ? AND ad.id_horario = ?
                            GROUP BY a.id_alumno
                            ORDER BY a.nombre
                        ");
                        $stmt->execute([$id_dia, $h['id_horario']]);
                        $alumnos = $stmt->fetchAll();
                        ?>

                        <?php if (empty($alumnos)): ?>
                            <p class="text-muted">Sin alumnos inscritos</p>
                        <?php else: ?>
                            <table class="table table-sm table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Alumno</th>
                                        <th>Edad</th>
                                        <th>Último Pago</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($alumnos as $a):
                                        $edad = calcularEdad($a['fecha_nacimiento']);
                                        $ultimo = $a['ultimo_pago'] 
                                            ? '<span class="badge bg-success">' . date('d/m/Y', strtotime($a['ultimo_pago'])) . '</span>'
                                            : '<span class="badge bg-danger">Sin pagos</span>';
                                    ?>
                                        <tr>
                                            <td><?= htmlspecialchars($a['nombre'] . ' ' . $a['apellido']) ?></td>
                                            <td><?= $edad ?> años</td>
                                            <td><?= $ultimo ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<script>
// Cambiar icono del botón individual
function toggleIcon(btn) {
    const icon = btn.querySelector('i');
    if (icon.classList.contains('bi-chevron-down')) {
        icon.classList.remove('bi-chevron-down');
        icon.classList.add('bi-chevron-up');
    } else {
        icon.classList.remove('bi-chevron-up');
        icon.classList.add('bi-chevron-down');
    }
}

// Botón global
function toggleAll() {
    const btn = document.getElementById('toggleAllBtn');
    const isContraer = btn.textContent.includes('Contraer');
    const collapses = document.querySelectorAll('.collapse');

    collapses.forEach(collapse => {
        if (isContraer) {
            collapse.classList.remove('show');
        } else {
            collapse.classList.add('show');
        }
    });

    // Actualizar todos los iconos
    document.querySelectorAll('.card-header button i').forEach(icon => {
        if (isContraer) {
            icon.classList.remove('bi-chevron-down');
            icon.classList.add('bi-chevron-up');
        } else {
            icon.classList.remove('bi-chevron-up');
            icon.classList.add('bi-chevron-down');
        }
    });

    btn.textContent = isContraer ? 'Expandir Todos' : 'Contraer Todos';
}
</script>

<?php require '../../views/layouts/footer.php'; ?>