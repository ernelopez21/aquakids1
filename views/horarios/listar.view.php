<?php require '../../views/layouts/main.php'; ?>

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>⏰ Horarios de Clases</h2>
        <a href="añadir.php" class="btn btn-success">+ Nuevo Horario</a>
    </div>

    <table class="table table-hover" id="tablaHorarios">
        <thead class="table-dark">
            <tr>
                <th>Hora Inicio</th>
                <th>Hora Fin</th>
                <th>Duración</th>
                <th>Capacidad</th>
                <th>Tipo</th>
                <th>Descripción</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $pdo = getPDO();
            $stmt = $pdo->query("SELECT * FROM horarios ORDER BY hora_inicio");
            while ($h = $stmt->fetch()) {
                $duracion = (new DateTime($h['hora_fin']))->diff(new DateTime($h['hora_inicio']))->format('%H:%I');
                echo "<tr>
                    <td>" . date('H:i', strtotime($h['hora_inicio'])) . "</td>
                    <td>" . date('H:i', strtotime($h['hora_fin'])) . "</td>
                    <td>{$duracion} hrs</td>
                    <td>{$h['capacidad']} alumnos</td>
                    <td>" . ucfirst($h['tipo']) . "</td>
                    <td>" . htmlspecialchars($h['descripcion'] ?? '-') . "</td>
                    <td>
                        <a href='editar.php?id={$h['id_horario']}' class='btn btn-sm btn-warning'>Editar</a>
                        <a href='eliminar.php?id={$h['id_horario']}' class='btn btn-sm btn-danger' 
                           onclick=\"return confirm('¿Eliminar este horario?')\">Eliminar</a>
                    </td>
                </tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.datatables.net/2.0.8/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap5.min.js"></script>
<script>
    new DataTable('#tablaHorarios', {
        language: { url: 'https://cdn.datatables.net/plug-ins/2.0.8/i18n/es-ES.json' },
        pageLength: 15
    });
</script>

<?php require '../../views/layouts/footer.php'; ?>