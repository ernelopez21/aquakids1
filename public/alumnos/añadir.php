<?php
session_start();
require '../../config/database.php';

// Siempre cargar PDO al inicio
$pdo = getPDO();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Insertar alumno
    $stmt = $pdo->prepare("INSERT INTO alumnos 
        (nombre, apellido, fecha_nacimiento, nivel, telefono, email) 
        VALUES (?, ?, ?, ?, ?, ?)");
    
    $stmt->execute([
        trim($_POST['nombre']),
        trim($_POST['apellido']),
        $_POST['fecha_nacimiento'],
        trim($_POST['nivel']),
        trim($_POST['telefono']),
        trim($_POST['email'])
    ]);

    $alumno_id = $pdo->lastInsertId();

    // 2. Guardar asignaciones (día + horario)
    if (isset($_POST['asignacion']) && is_array($_POST['asignacion'])) {
        foreach ($_POST['asignacion'] as $dia_id => $data) {
            if (!empty($data['check']) && !empty($data['horario'])) {
                $stmt = $pdo->prepare("INSERT INTO alumnos_dia_horario 
                    (id_alumno, id_dia, id_horario) VALUES (?, ?, ?)");
                $stmt->execute([$alumno_id, $dia_id, $data['horario']]);
            }
        }
    }

    $_SESSION['flash'] = ['type' => 'success', 'message' => 'Alumno registrado y asignado correctamente.'];
    header('Location: listar.php');
    exit;
}

// Cargar datos para el formulario (días y horarios) - siempre disponible
$dias = $pdo->query("SELECT * FROM dias ORDER BY id_dia")->fetchAll();
$horarios = $pdo->query("SELECT id_horario, hora_inicio, hora_fin 
                         FROM horarios ORDER BY hora_inicio")->fetchAll();

require '../../views/alumnos/añadir.view.php';
?>