<?php require '../../views/layouts/main.php'; ?>

<div class="container">
    <h2>+ Nuevo Horario</h2>
    <div class="card p-4">
        <form method="POST">
            <div class="row g-3">
                <div class="col-md-3">
                    <label>Hora Inicio</label>
                    <input type="time" name="hora_inicio" class="form-control" required>
                </div>
                <div class="col-md-3">
                    <label>Hora Fin</label>
                    <input type="time" name="hora_fin" class="form-control" required>
                </div>
                <div class="col-md-3">
                    <label>Capacidad máxima</label>
                    <input type="number" name="capacidad" class="form-control" value="15" min="5" required>
                </div>
                <div class="col-md-3">
                    <label>Tipo</label>
                    <select name="tipo" class="form-select" required>
                        <option value="semanal">Semanal</option>
                        <option value="especial">Especial</option>
                    </select>
                </div>
                <div class="col-12">
                    <label>Descripción (opcional)</label>
                    <input type="text" name="descripcion" class="form-control" placeholder="Clase de natación nivel intermedio">
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-success">Guardar Horario</button>
                <a href="listar.php" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>

<?php require '../../views/layouts/footer.php'; ?>