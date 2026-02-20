<?php
// controllers/tarjetas_controller.php
// Ruta corregida: sube un nivel a config/
require_once dirname(__FILE__) . '/../config/database.php';

function crearTarjeta($nombre_tutor, $monto_inscripcion, $alumnos_ids, $notas = '') {
    $pdo = getPDO();
    try {
        $pdo->beginTransaction();

        $stmt = $pdo->prepare("INSERT INTO tarjetas_pago 
            (nombre_tutor, monto_inscripcion, fecha_creacion, notas) 
            VALUES (:nombre, :monto, CURDATE(), :notas)");
        $stmt->execute([
            ':nombre' => htmlspecialchars(trim($nombre_tutor)),
            ':monto'  => (float)$monto_inscripcion,
            ':notas'  => htmlspecialchars(trim($notas))
        ]);
        $id_ficha = $pdo->lastInsertId();

        // Agregar alumnos (sin límite)
        if (!empty($alumnos_ids)) {
            $stmt = $pdo->prepare("INSERT INTO tarjetas_alumnos (id_ficha, id_alumno) 
                                   VALUES (:ficha, :alumno)");
            foreach ($alumnos_ids as $id_alumno) {
                $stmt->execute([':ficha' => $id_ficha, ':alumno' => (int)$id_alumno]);
            }
        }

        $pdo->commit();
        return ['success' => true, 'id_ficha' => $id_ficha];
    } catch (Exception $e) {
        $pdo->rollBack();
        return ['success' => false, 'error' => $e->getMessage()];
    }
}

function listarTarjetas() {
    $pdo = getPDO();
    $stmt = $pdo->query("SELECT * FROM tarjetas_pago ORDER BY fecha_creacion DESC");
    $tarjetas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($tarjetas as &$tarjeta) {
        // Alumnos asociados
        $stmt_alumnos = $pdo->prepare("SELECT a.id_alumno, a.nombre FROM tarjetas_alumnos ta 
                                       JOIN Alumnos a ON ta.id_alumno = a.id_alumno 
                                       WHERE ta.id_ficha = :id_ficha");
        $stmt_alumnos->execute([':id_ficha' => $tarjeta['id_ficha']]);
        $tarjeta['alumnos'] = $stmt_alumnos->fetchAll(PDO::FETCH_ASSOC);

        // Pagos mensuales (inicializa 12 meses vacíos)
        $tarjeta['pagos'] = array_fill(1, 12, ['monto' => 0, 'fecha_pago' => null]);
        $stmt_pagos = $pdo->prepare("SELECT mes, monto, fecha_pago FROM pagos_mensuales 
                                     WHERE id_ficha = :id_ficha AND anio = YEAR(CURDATE())");
        $stmt_pagos->execute([':id_ficha' => $tarjeta['id_ficha']]);
        $pagos = $stmt_pagos->fetchAll(PDO::FETCH_ASSOC);
        foreach ($pagos as $pago) {
            $tarjeta['pagos'][$pago['mes']] = $pago;
        }
    }
    return $tarjetas;
}

function registrarPagoMensual($id_ficha, $mes, $anio, $monto, $fecha_pago) {
    $pdo = getPDO();
    try {
        $pdo->beginTransaction();

        $stmt = $pdo->prepare("INSERT INTO pagos_mensuales (id_ficha, mes, anio, monto, fecha_pago) 
                               VALUES (:ficha, :mes, :anio, :monto, :fecha_pago) 
                               ON DUPLICATE KEY UPDATE monto = :monto, fecha_pago = :fecha_pago");
        $stmt->execute([
            ':ficha' => (int)$id_ficha,
            ':mes' => (int)$mes,
            ':anio' => (int)$anio,
            ':monto' => (float)$monto,
            ':fecha_pago' => $fecha_pago  // Formato YYYY-MM-DD
        ]);

        $pdo->commit();
        return ['success' => true];
    } catch (Exception $e) {
        $pdo->rollBack();
        return ['success' => false, 'error' => $e->getMessage()];
    }
}
?>