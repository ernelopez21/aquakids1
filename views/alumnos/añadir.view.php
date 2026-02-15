<?php require '../../views/layouts/main.php'; ?>

<div class="container">
    <h2>+ Nuevo Alumno</h2>
    <div class="card p-4">

        <form method="POST">

            <!-- Datos del alumno (igual que antes) -->
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nombre</label>
                    <input type="text" name="nombre" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Apellido</label>
                    <input type="text" name="apellido" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Fecha de Nacimiento</label>
                    <input type="date" name="fecha_nacimiento" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Nivel</label>
                    <input type="text" name="nivel" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Teléfono</label>
                    <input type="tel" name="telefono" class="form-control">
                </div>
                <div class="col-12">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control">
                </div>
            </div>

            <!-- === NUEVA SECCIÓN: ASIGNAR HORARIO (igual a tu imagen) === -->
            <h3 class="mt-5 mb-3">Asignar Horario</h3>
            <p class="text-muted">Marca los días y selecciona el horario correspondiente.</p>

            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th width="20%">Día</th>
                        <th width="10%">Asignar</th>
                        <th>Horario</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($dias as $dia): 
                        $dia_id = $dia['id_dia'];
                        $dia_nombre = $dia['nombre_dia'];
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($dia_nombre) ?></td>
                        <td>
                            <input type="checkbox" name="asignacion[<?= $dia_id ?>][check]" value="1" class="form-check-input">
                        </td>
                        <td>
                            <select name="asignacion[<?= $dia_id ?>][horario]" class="form-select">
                                <option value="">-- Selecciona un horario --</option>
                                <?php foreach ($horarios as $h): 
                                    $hora = date('H:i', strtotime($h['hora_inicio'])) . ' - ' . date('H:i', strtotime($h['hora_fin']));
                                ?>
                                    <option value="<?= $h['id_horario'] ?>"><?= $hora ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="mt-4">
                <button type="submit" class="btn btn-success btn-lg">Guardar Alumno y Asignaciones</button>
                <a href="listar.php" class="btn btn-secondary btn-lg">Cancelar</a>
            </div>
        </form>
    </div>
</div>

<?php require '../../views/layouts/footer.php'; ?>