<?php
$pageTitle = 'Nuevo Alumno';
require_once '../../includes/header.php';

// Carga días y horarios (agrega esto en public/alumnos/añadir.php antes del require de la vista)
$dias = $pdo->query("SELECT * FROM dias ORDER BY id_dia")->fetchAll(PDO::FETCH_ASSOC);
$horarios = $pdo->query("SELECT * FROM horarios ORDER BY hora_inicio")->fetchAll(PDO::FETCH_ASSOC);
?>

<h1 class="mb-4">+ Nuevo Alumno</h1>

<form method="POST" action="añadir.php" class="row g-3">

    <div class="col-md-6">
        <label class="form-label">Nombre *</label>
        <input type="text" name="nombre" class="form-control" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">Apellido *</label>
        <input type="text" name="apellido" class="form-control" required>
    </div>
    <div class="col-md-4">
        <label class="form-label">Fecha de nacimiento *</label>
        <input type="date" name="fecha_nacimiento" class="form-control" required>
    </div>
    <div class="col-md-4">
        <label class="form-label">Nivel *</label>
        <input type="text" name="nivel" class="form-control" placeholder="Principiante / Intermedio" required>
    </div>
    <div class="col-md-4">
        <label class="form-label">Teléfono *</label>
        <input type="tel" name="telefono" class="form-control" required>
    </div>
    <div class="col-12">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control">
    </div>

    <hr class="my-4">

    <h5 class="mb-3">Asignar Días y Horarios</h5>
    <div class="row">
        <?php foreach ($dias as $dia): ?>
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-primary text-white fw-bold">
                    <?= htmlspecialchars($dia['nombre_dia']) ?>
                </div>
                <div class="card-body">
                    <?php foreach ($horarios as $h): ?>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" 
                               name="asignacion[<?= $dia['id_dia'] ?>][]" 
                               value="<?= $h['id_horario'] ?>" 
                               id="d<?= $dia['id_dia'] ?>h<?= $h['id_horario'] ?>">
                        <label class="form-check-label" for="d<?= $dia['id_dia'] ?>h<?= $h['id_horario'] ?>">
                            <?= substr($h['hora_inicio'],0,5) ?> - <?= substr($h['hora_fin'],0,5) ?>
                            <?= $h['descripcion'] ? ' — ' . htmlspecialchars($h['descripcion']) : '' ?>
                        </label>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <div class="col-12 mt-4">
        <button type="submit" class="btn btn-success btn-lg">Guardar Alumno y Asignaciones</button>
        <a href="listar.php" class="btn btn-secondary btn-lg">Cancelar</a>
    </div>
</form>

<?php require_once '../../includes/footer.php'; ?>