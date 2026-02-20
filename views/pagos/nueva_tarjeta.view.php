<?php
// views/pagos/nueva_tarjeta.view.php
// Código completo con correcciones: paths robustos, nombres completos en lista, action relativo, spinner loading

require_once dirname(__FILE__) . '/../../config/database.php';
$pdo = getPDO();
$stmt = $pdo->query("SELECT id_alumno, nombre FROM Alumnos ORDER BY nombre ASC");
$alumnos_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="container mt-5" x-data="{ alumnosSeleccionados: [] }">
    <h2 class="mb-4 text-center">Nueva Tarjeta de Pago (exacta a la física)</h2>
    
    <form method="POST" action="crear_tarjeta.php" class="card p-4 shadow" x-data="{ loading: false }" @submit="loading = true">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Nombre del Tutor / Papá</label>
                <input type="text" name="nombre_tutor" class="form-control" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">Monto Inscripción (mensual)</label>
                <input type="number" step="0.01" name="monto_inscripcion" class="form-control" required>
            </div>
        </div>

        <!-- Alumnos (sin límite) -->
        <div class="mt-4">
            <label class="form-label">Alumnos en esta ficha</label>
            <div class="input-group">
                <select id="selectAlumno" class="form-select">
                    <option value="">-- Selecciona alumno --</option>
                    <?php foreach ($alumnos_list as $alumno): ?>
                        <option value="<?= $alumno['id_alumno'] ?>" data-nombre="<?= htmlspecialchars($alumno['nombre']) ?>">
                            <?= htmlspecialchars($alumno['nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="button" class="btn btn-success" 
                        @click="let select = document.getElementById('selectAlumno');
                               if(select.value) { 
                                   let nombre = select.options[select.selectedIndex].dataset.nombre;
                                   alumnosSeleccionados.push({id: select.value, nombre: nombre}); 
                                   select.value=''; 
                               }">
                    + Agregar
                </button>
            </div>

            <div class="mt-3">
                <ul class="list-group" style="max-height: 250px; overflow-y: auto;">
                    <template x-for="(alumno, index) in alumnosSeleccionados" :key="index">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span x-text="alumno.nombre"></span>
                            <button type="button" class="btn btn-sm btn-danger" 
                                    @click="alumnosSeleccionados.splice(index, 1)">×</button>
                        </li>
                    </template>
                </ul>
            </div>
            <!-- Campo oculto que envía solo IDs al POST -->
            <input type="hidden" name="alumnos" :value="alumnosSeleccionados.map(a => a.id).join(',')">
        </div>

        <div class="mt-4">
            <label class="form-label">Notas (opcional)</label>
            <textarea name="notas" class="form-control" rows="2"></textarea>
        </div>

        <button type="submit" class="btn btn-primary mt-4 w-100" :disabled="loading">
            <span x-show="!loading">Guardar Tarjeta de Pago</span>
            <span x-show="loading">Guardando... <span class="spinner-border spinner-border-sm" role="status"></span></span>
        </button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/alpinejs@3/dist/cdn.min.js" defer></script>