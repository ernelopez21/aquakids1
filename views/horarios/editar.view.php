<?php require '../../views/layouts/main.php'; ?>

<div class="container">
    <h2>✏️ Editar Horario</h2>
    <div class="card p-4">
        <form method="POST">
            <input type="hidden" name="id_horario" value="<?= $horario['id_horario'] ?>">

            <div class="row g-3">
                <div class="col-md-3">
                    <label>Hora Inicio</label>
                    <input type="time" name="hora_inicio" class="form-control" value="<?= $horario['hora_inicio'] ?>" required>
                </div>
                <div class="col-md-3">
                    <label>Hora Fin</label>
                    <input type="time" name="hora_fin" class="form-control" value="<?= $horario['hora_fin'] ?>" required>
                </div>
                <div class="col-md-3">
                    <label>Capacidad máxima</label>
                    <input type="number" name="capacidad" class="form-control" value="<?= $horario['capacidad'] ?>" min="5" required>
                </div>
                <div class="col-md-3">
                    <label>Tipo</label>
                    <select name="tipo" class="form-select" required>
                        <option value="semanal" <?= $horario['tipo']=='semanal'?'selected':'' ?>>Semanal</option>
                        <option value="especial" <?= $horario['tipo']=='especial'?'selected':'' ?>>Especial</option>
                    </select>
                </div>
                <div class="col-12">
                    <label>Descripción (opcional)</label>
                    <input type="text" name="descripcion" class="form-control" 
                           value="<?= htmlspecialchars($horario['descripcion'] ?? '') ?>">
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-warning">Guardar Cambios</button>
                <a href="listar.php" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>

<?php require '../../views/layouts/footer.php'; ?>