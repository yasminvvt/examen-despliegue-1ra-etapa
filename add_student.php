<?php
header('Content-Type: application/json');
require_once 'db_connection.php';

$response = array(
    'success' => false,
    'message' => ''
);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $apellido = trim($_POST['apellido'] ?? '');
    $curso = trim($_POST['curso'] ?? '');
    $email = trim($_POST['email'] ?? '');

    if (empty($nombre) || empty($apellido) || empty($curso) || empty($email)) {
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
        $stmt = $conn->prepare("SELECT id FROM students WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $response['message'] = 'El email ya está registrado';
            echo json_encode($response);
            exit;
        }

        $stmt = $conn->prepare("INSERT INTO students (nombre, apellido, curso, email) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $nombre, $apellido, $curso, $email);

        if ($stmt->execute()) {
            $response['success'] = true;
            $response['message'] = 'Estudiante agregado exitosamente';
        } else {
            throw new Exception("Error al agregar estudiante");
        }
    } catch (Exception $e) {
        $response['message'] = 'Error al agregar estudiante';
    }
}

$conn->close();
echo json_encode($response);
?> 