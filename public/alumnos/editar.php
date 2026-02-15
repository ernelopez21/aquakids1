<?php
session_start();
require '../../config/database.php';

$pdo = getPDO();

// Cargar alumno
$id = (int)$_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM alumnos WHERE id_alumno = ?");
$stmt->execute([$id]);
$alumno = $stmt->fetch();

// Cargar asignaciones actuales (array id_dia => id_horario)
$asignaciones = [];
$stmt = $pdo->prepare("SELECT id_dia, id_horario FROM alumnos_dia_horario WHERE id_alumno = ?");
$stmt->execute([$id]);
while ($row = $stmt->fetch()) {
    $asignaciones[$row['id_dia']] = $row['id_horario'];
}

// Cargar días y horarios
$dias = $pdo->query("SELECT * FROM dias ORDER BY nombre_dia")->fetchAll();
$horarios = $pdo->query("SELECT * FROM horarios ORDER BY hora_inicio ASC")->fetchAll();

// Guardar
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validación mínima: al menos 1 día
    if (empty($_POST['dias'])) {
        $error = "Debe seleccionar al menos un día.";
    } else {
        // Actualizar datos personales
        $stmt = $pdo->prepare("UPDATE alumnos SET 
            nombre=?, apellido=?, fecha_nacimiento=?, nivel=?, telefono=?, email=? 
            WHERE id_alumno=?");
        $stmt->execute([
            trim($_POST['nombre']),
            trim($_POST['apellido']),
            $_POST['fecha_nacimiento'],
            trim($_POST['nivel']),
            trim($_POST['telefono']),
            trim($_POST['email']),
            $id
        ]);

        // Borrar y recrear asignaciones
        $pdo->prepare("DELETE FROM alumnos_dia_horario WHERE id_alumno = ?")->execute([$id]);
        $insert = $pdo->prepare("INSERT INTO alumnos_dia_horario (id_alumno, id_dia, id_horario) VALUES (?, ?, ?)");
        foreach ($_POST['dias'] as $id_dia) {
            $id_horario = $_POST['horario'][$id_dia] ?? null;
            if ($id_horario) {
                $insert->execute([$id, $id_dia, $id_horario]);
            }
        }

        header('Location: listar.php?success=1');
        exit;
    }
}

require '../../views/alumnos/editar.view.php';
?>