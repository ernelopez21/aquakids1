<?php
// views/pagos/listar_tarjetas.view.php
require_once dirname(__FILE__) . '/../../controllers/tarjetas_controller.php';
$tarjetas = listarTarjetas() ?? [];  // Evita null con array vacío
?>
<div class="container mt-5">
    <h2 class="mb-4 text-center">Listado de Tarjetas de Pago</h2>
    <table class="table table-striped table-hover">
        <thead class="thead-dark">
            <tr>
                <th>ID Ficha</th>
                <th>Tutor</th>
                <th>Monto Mensual</th>
                <th>Alumnos</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($tarjetas)): ?>
                <tr><td colspan="5" class="text-center">No hay tarjetas registradas aún.</td></tr>
            <?php else: ?>
                <?php foreach ($tarjetas as $tarjeta): ?>
                    <tr x-data="{ expanded: false }">
                        <td><?= $tarjeta['id_ficha'] ?></td>
                        <td><?= htmlspecialchars($tarjeta['nombre_tutor']) ?></td>
                        <td>$<?= number_format($tarjeta['monto_inscripcion'], 2) ?></td>
                        <td>
                            <?php
                            // Muestra nombres de alumnos separados por coma
                            $nombres = array_column($tarjeta['alumnos'] ?? [], 'nombre');
                            echo implode(', ', array_map('htmlspecialchars', $nombres)) ?: 'Ninguno';
                            ?>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-info" @click="expanded = !expanded">
                                <span x-text="expanded ? 'Ocultar Detalles' : 'Ver Detalles'"></span>
                            </button>
                            <button class="btn btn-sm btn-success" @click="$dispatch('open-pago-modal', { id_ficha: <?= $tarjeta['id_ficha'] ?>, monto_default: <?= $tarjeta['monto_inscripcion'] ?> })">
                                Registrar Pago
                            </button>
                        </td>
                    </tr>
                    <tr x-show="expanded">
                        <td colspan="5">
                            <div class="card p-3">
                                <h5>Alumnos:</h5>
                                <ul>
                                    <?php foreach ($tarjeta['alumnos'] ?? [] as $alumno): ?>
                                        <li><?= htmlspecialchars($alumno['nombre']) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                                <h5>Pagos Mensuales (Año Actual):</h5>
                                <div class="row">
                                    <?php $meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre']; ?>
                                    <?php foreach ($meses as $mes => $nombre): ?>
                                        <div class="col-md-2 mb-2">
                                            <div class="card text-center <?= ($tarjeta['pagos'][$mes+1]['monto'] ?? 0) > 0 ? 'bg-success text-white' : 'bg-light' ?>">
                                                <div class="card-body">
                                                    <h6><?= $nombre ?></h6>
                                                    <p><?= ($tarjeta['pagos'][$mes+1]['monto'] ?? 0) > 0 ? 'Pagado: $' . number_format($tarjeta['pagos'][$mes+1]['monto'], 2) : 'Pendiente' ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Modal para registrar pago (con fecha modificable y monto prellenado) -->
<div x-data="{ open: false, id_ficha: 0, mes: new Date().getMonth() + 1, monto: 0, fecha_pago: new Date().toISOString().split('T')[0] }" 
     @open-pago-modal.window="open = true; id_ficha = $event.detail.id_ficha; monto = $event.detail.monto_default" 
     class="modal fade" :class="{ 'show d-block': open }" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Registrar Pago para Ficha #<span x-text="id_ficha"></span></h5>
                <button type="button" class="close" @click="open = false">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Mes a Pagar:</label>
                    <select x-model="mes" class="form-control">
                        <option value="1">Enero</option>
                        <option value="2">Febrero</option>
                        <option value="3">Marzo</option>
                        <option value="4">Abril</option>
                        <option value="5">Mayo</option>
                        <option value="6">Junio</option>
                        <option value="7">Julio</option>
                        <option value="8">Agosto</option>
                        <option value="9">Septiembre</option>
                        <option value="10">Octubre</option>
                        <option value="11">Noviembre</option>
                        <option value="12">Diciembre</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Monto:</label>
                    <input type="number" step="0.01" x-model="monto" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Fecha de Pago:</label>
                    <input type="date" x-model="fecha_pago" class="form-control" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" @click="open = false">Cancelar</button>
                <button type="button" class="btn btn-primary" @click="registrarPago(id_ficha, mes, monto, fecha_pago); open = false">Guardar</button>
            </div>
        </div>
    </div>
</div>

<!-- Script para AJAX (actualizado con fecha_pago) -->
<script>
function registrarPago(id_ficha, mes, monto, fecha_pago) {
    fetch('registrar_pago.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({id_ficha, mes, anio: new Date().getFullYear(), monto, fecha_pago})
    }).then(response => response.json())
      .then(data => {
        if (data.success) {
            alert('Pago registrado correctamente. El estado se ha actualizado a Pagado.');
            location.reload();  // Recarga para actualizar grid
        } else {
            alert('Error: ' + data.error);
        }
      });
}
</script>

<script src="https://cdn.jsdelivr.net/npm/alpinejs@3/dist/cdn.min.js" defer></script>