<?php
header('Content-Type: application/json');
require_once 'db_connection.php';

$response = array(
    'success' => false,
    'message' => ''
);

$data = json_decode(file_get_contents('php://input'), true);
$username = trim($data['username'] ?? '');
$email = trim($data['email'] ?? '');
$password = trim($data['password'] ?? '');

if (empty($username) || empty($email) || empty($password)) {
    $response['message'] = 'Por favor complete todos los campos';
    echo json_encode($response);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $response['message'] = 'Por favor ingrese un email válido';
    echo json_encode($response);
    exit;
}

if (strlen($password) < 6) {
    $response['message'] = 'La contraseña debe tener al menos 6 caracteres';
    echo json_encode($response);
    exit;
}

try {
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $response['message'] = 'El usuario o email ya existe';
        echo json_encode($response);
        exit;
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $hashed_password);

    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = 'Usuario registrado exitosamente';
    } else {
        throw new Exception("Error al registrar usuario");
    }
} catch (Exception $e) {
    $response['message'] = 'Error al registrar usuario';
}

$conn->close();
echo json_encode($response);
?> 