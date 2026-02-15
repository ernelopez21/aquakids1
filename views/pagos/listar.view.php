<?php require '../../views/layouts/main.php'; ?>

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>💰 Pagos Registrados</h2>
        <a href="añadir.php" class="btn btn-success">+ Nuevo Pago</a>
    </div>

    <table class="table table-hover">
        <thead class="table-dark">
            <tr>
                <th>Alumno</th>
                <th>Fecha Pago</th>
                <th>Estado</th>
                <th>Monto</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($pagos as $p): 
                $vencido = esVencido($p['fecha_pago']);
            ?>
                <tr class="<?= $vencido ? 'table-danger' : '' ?>">
                    <td><?= htmlspecialchars($p['nombre'] . ' ' . $p['apellido']) ?></td>
                    <td><?= date('d/m/Y', strtotime($p['fecha_pago'])) ?></td>
                    <td>
                        <?php if ($vencido): ?>
                            <span class="badge bg-danger">Vencido</span>
                        <?php else: ?>
                            <span class="badge bg-success">Al día</span>
                        <?php endif; ?>
                    </td>
                    <td>$ <?= number_format($p['monto'] ?? 500, 2) ?></td>
                    <td>
                        <a href='editar.php?id=<?= $p['id_pago'] ?>' class='btn btn-sm btn-warning'>Editar</a>
                        <a href='eliminar.php?id=<?= $p['id_pago'] ?>' class='btn btn-sm btn-danger' onclick="return confirm('¿Eliminar pago?')">Eliminar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require '../../views/layouts/footer.php'; ?>