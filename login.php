<?php
session_start();
header('Content-Type: application/json');
require_once 'db_connection.php';

$response = array(
    'success' => false,
    'message' => ''
);

$data = json_decode(file_get_contents('php://input'), true);
$username = trim($data['username'] ?? '');
$password = trim($data['password'] ?? '');

if (empty($username) || empty($password)) {
    $response['message'] = 'Por favor complete todos los campos';
    echo json_encode($response);
    exit;
}

try {
    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $response['success'] = true;
            $response['message'] = 'Login exitoso';
        } else {
            $response['message'] = 'Contraseña incorrecta';
        }
    } else {
        $response['message'] = 'Usuario no encontrado';
    }
} catch (Exception $e) {
    $response['message'] = 'Error al iniciar sesión';
}

$conn->close();
echo json_encode($response);
?> 