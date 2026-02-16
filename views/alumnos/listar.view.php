<?php require '../../views/layouts/main.php'; ?>

<?php
// Función para calcular edad (corregida y segura)
function calcularEdad($fecha) {
    if (!$fecha) return '-';
    $dob = new DateTime($fecha);
    $hoy = new DateTime();
    return $hoy->diff($dob)->y;
}

// Función resumen horarios (mejorada con rango)
function resumenHorarios($id_alumno, $pdo) {
    $stmt = $pdo->prepare("
        SELECT d.nombre_dia, h.hora_inicio, h.hora_fin, h.descripcion 
        FROM alumnos_dia_horario ad
        JOIN dias d ON ad.id_dia = d.id_dia
        JOIN horarios h ON ad.id_horario = h.id_horario
        WHERE ad.id_alumno = ?
        ORDER BY d.nombre_dia, h.hora_inicio
    ");
    $stmt->execute([$id_alumno]);
    $res = $stmt->fetchAll();
    if (empty($res)) return '-';
    $salida = [];
    foreach ($res as $r) {
        $rango = substr($r['hora_inicio'], 0, 5) . '-' . substr($r['hora_fin'], 0, 5);
        $desc = $r['descripcion'] ? ' (' . htmlspecialchars($r['descripcion']) . ')' : '';
        $salida[] = $r['nombre_dia'] . ' ' . $rango . $desc;
    }
    return implode(', ', $salida);
}
?>

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>👥 Alumnos Registrados</h2>
        <a href="añadir.php" class="btn btn-success">+ Nuevo Alumno</a>
        <a href="resumen.php" class="btn btn-info">📅 Resumen por Día</a>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">Cambios guardados correctamente</div>
    <?php endif; ?>

    <table class="table table-hover align-middle">
        <thead class="table-dark">
            <tr>
                <th>Nombre Completo</th>
                <th>Fecha Nacimiento</th>
                <th>Edad</th>
                <th>Horarios Asignados</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $pdo = getPDO();
            $stmt = $pdo->query("SELECT * FROM alumnos ORDER BY nombre, apellido");
            while ($a = $stmt->fetch()) {
                $edad = calcularEdad($a['fecha_nacimiento']);
                echo "<tr>
                    <td>" . htmlspecialchars($a['nombre'] . ' ' . $a['apellido']) . "</td>
                    <td>" . ($a['fecha_nacimiento'] ? date('d/m/Y', strtotime($a['fecha_nacimiento'])) : '-') . "</td>
                    <td><strong>$edad años</strong></td>
                    <td>" . resumenHorarios($a['id_alumno'], $pdo) . "</td>
                    <td>
                        <a href='editar.php?id={$a['id_alumno']}' class='btn btn-sm btn-warning'>Editar</a>
                        <a href='eliminar.php?id={$a['id_alumno']}' class='btn btn-sm btn-danger' 
                           onclick=\"return confirm('¿Eliminar a {$a['nombre']}?')\">Eliminar</a>
                    </td>
                </tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php require '../../views/layouts/footer.php'; ?>