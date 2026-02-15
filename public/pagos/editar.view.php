<?php require '../../views/layouts/main.php'; ?>

<div class="container">
    <h2>Editar Pago de: <?= htmlspecialchars($pago['nombre'] . ' ' . $pago['apellido']) ?></h2>
    
    <div class="card p-4">
        <form method="POST">
            <input type="hidden" name="id_pago" value="<?= $pago['id_pago'] ?>">
            
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Fecha del Pago</label>
                    <input type="date" name="fecha_pago" class="form-control" value="<?= $pago['fecha_pago'] ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Monto ($)</label>
                    <input type="number" name="monto" class="form-control" value="<?= $pago['monto'] ?>" step="0.01" required>
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