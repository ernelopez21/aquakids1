<?php require '../../views/layouts/main.php'; ?>

<div class="container">
    <h2>+ Nuevo Pago</h2>
    <div class="card p-4">
        <form method="POST">
            <div class="row g-3">
                <div class="col-md-6">
                    <label>Alumno</label>
                    <select name="id_alumno" class="form-select" required>
                        <option value="">Selecciona Alumno</option>
                        <?php foreach ($alumnos as $a): ?>
                            <option value="<?= $a['id_alumno'] ?>"><?= htmlspecialchars($a['nombre'] . ' ' . $a['apellido']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label>Fecha Pago</label>
                    <input type="date" name="fecha_pago" class="form-control" required>
                </div>
                <div class="col-md-2">
                    <label>Monto</label>
                    <input type="number" name="monto" class="form-control" value="500" step="0.01">
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-success">Registrar Pago</button>
                <a href="listar.php" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>

<?php require '../../views/layouts/footer.php'; ?>