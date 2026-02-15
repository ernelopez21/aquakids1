<?php require '../../views/layouts/main.php'; ?>

<div class="container" x-data="{ toast: <?= isset($_GET['success']) ? 'true' : 'false' ?> }">
    <h2>Asignar Horario a: <?= htmlspecialchars($alumno['nombre'] . ' ' . $alumno['apellido']) ?></h2>

    <!-- Toast éxito con Alpine.js -->
    <div x-show="toast" x-transition class="alert alert-success alert-dismissible fade show position-fixed" style="top: 20px; right: 20px; z-index: 1050;">
        Cambios guardados correctamente
        <button type="button" class="btn-close" @click="toast = false"></button>
    </div>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST">
        <!-- Datos personales colapsados (igual) -->
        <div class="card mb-4">
            <div class="card-header">
                <button class="btn btn-link collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseDatos">
                    Datos Personales (click para expandir)
                </button>
            </div>
            <div id="collapseDatos" class="collapse">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6"><input type="text" name="nombre" class="form-control" value="<?= htmlspecialchars($alumno['nombre']) ?>" required></div>
                        <div class="col-md-6"><input type="text" name="apellido" class="form-control" value="<?= htmlspecialchars($alumno['apellido']) ?>" required></div>
                        <div class="col-md-4"><input type="date" name="fecha_nacimiento" class="form-control" value="<?= $alumno['fecha_nacimiento'] ?>" required></div>
                        <div class="col-md-4"><input type="text" name="nivel" class="form-control" value="<?= htmlspecialchars($alumno['nivel'] ?? '') ?>"></div>
                        <div class="col-md-4"><input type="tel" name="telefono" class="form-control" value="<?= htmlspecialchars($alumno['telefono'] ?? '') ?>"></div>
                        <div class="col-12"><input type="email" name="email" class="form-control" value="<?= htmlspecialchars($alumno['email'] ?? '') ?>"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Asignación horarios con Alpine.js -->
        <div class="card">
            <div class="card-header"><h5>Modificar Horario</h5></div>
            <div class="card-body">
                <?php foreach ($dias as $dia): 
                    $diaId = $dia['id_dia'];
                    $checked = isset($asignaciones[$diaId]);
                    $selectedHorario = $asignaciones[$diaId] ?? '';
                ?>
                    <div class="row align-items-center mb-3" x-data="{ checked: <?= $checked ? 'true' : 'false' ?> }">
                        <div class="col-md-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="dias[]" value="<?= $diaId ?>" id="dia<?= $diaId ?>" 
                                       x-model="checked" @change="checked = $event.target.checked">
                                <label class="form-check-label fw-bold" for="dia<?= $diaId ?>">
                                    <?= htmlspecialchars($dia['nombre_dia']) ?>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-10">
                            <select name="horario[<?= $diaId ?>]" class="form-select" :disabled="!checked">
                                <option value="">Selecciona Un Horario</option>
                                <?php foreach ($horarios as $horario): 
                                    $rango = substr($horario['hora_inicio'], 0, 5) . ' - ' . substr($horario['hora_fin'], 0, 5);
                                    $selected = ($selectedHorario == $horario['id_horario']) ? 'selected' : '';
                                ?>
                                    <option value="<?= $horario['id_horario'] ?>" <?= $selected ?>>
                                        <?= $rango ?> <?= htmlspecialchars($horario['descripcion'] ?? '') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                <?php endforeach; ?>
                <small class="text-muted">* Formato de 24 Hrs</small>
            </div>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-primary btn-lg">Guardar Todo</button>
            <a href="listar.php" class="btn btn-secondary btn-lg">Cancelar</a>
        </div>
    </form>
</div>

<?php require '../../views/layouts/footer.php'; ?>