<?php
header('Content-Type: application/json');
require_once 'db_connection.php';

$response = array(
    'success' => false,
    'message' => ''
);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = trim($_POST['id'] ?? '');
    $nombre = trim($_POST['nombre'] ?? '');
    $apellido = trim($_POST['apellido'] ?? '');
    $curso = trim($_POST['curso'] ?? '');
    $email = trim($_POST['email'] ?? '');

    if (empty($id) || empty($nombre) || empty($apellido) || empty($curso) || empty($email)) {
        $response['message'] = 'Por favor complete todos los campos';
        echo json_encode($response);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response['message'] = 'Por favor ingrese un email válido';
        echo json_encode($response);
        exit;
    }

    try {
        // Verificar si el email ya existe en otro registro
        $stmt = $pdo->prepare("SELECT id FROM students WHERE email = ? AND id != ?");
        $stmt->execute([$email, $id]);
        
        if ($stmt->rowCount() > 0) {
            $response['message'] = 'El email ya está registrado en otro estudiante';
            echo json_encode($response);
            exit;
        }

        $stmt = $pdo->prepare("UPDATE students SET nombre = ?, apellido = ?, curso = ?, email = ? WHERE id = ?");
        
        if ($stmt->execute([$nombre, $apellido, $curso, $email, $id])) {
            $response['success'] = true;
            $response['message'] = 'Estudiante actualizado exitosamente';
        } else {
            throw new Exception("Error al actualizar estudiante");
        }
    } catch (Exception $e) {
        $response['message'] = 'Error al actualizar estudiante';
    }
}

echo json_encode($response);
?> 