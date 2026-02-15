<?php require '../../views/layouts/main.php'; ?>

<?php
// Función para calcular edad (por si la quieres volver a usar)
function calcularEdad($fecha) {
    if (!$fecha) return '-';
    $dob = new DateTime($fecha);
    $hoy = new DateTime();
    return $hoy->diff($dob)->y;
}
// Función último pago - Versión FINAL (mensualidad)
function ultimoPago($id_alumno, $pdo) {
    $stmt = $pdo->prepare("SELECT MAX(fecha_pago) AS ultimo FROM pagos WHERE id_alumno = ?");
    $stmt->execute([$id_alumno]);
    $fecha = $stmt->fetchColumn();

    if (!$fecha) {
        return '<span class="badge bg-danger">Sin pagos</span>';
    }

    $ultimo = new DateTime($fecha);
    $hoy    = new DateTime();
    $dias   = $hoy->diff($ultimo)->days;

    $fechaFormateada = date('d/m/Y', strtotime($fecha));

    if ($dias > 30) {
        return '<span class="badge bg-danger">Vencido</span> ' . $fechaFormateada;
    } else {
        return '<span class="badge bg-success">Al día</span> ' . $fechaFormateada;
    }
}

// Función resumen de horarios
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
                <th>Último Pago</th>
                <th>Horarios Asignados</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $pdo = getPDO();
            
            // Consulta corregida (sin NULLS LAST)
            $stmt = $pdo->query("
                SELECT a.* 
                FROM alumnos a 
                ORDER BY (SELECT MAX(p.fecha_pago) FROM pagos p WHERE p.id_alumno = a.id_alumno) DESC, 
                         a.nombre ASC
            ");

            while ($a = $stmt->fetch()) {
                echo "<tr>
                    <td>" . htmlspecialchars($a['nombre'] . ' ' . $a['apellido']) . "</td>
                    <td>" . ultimoPago($a['id_alumno'], $pdo) . "</td>
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