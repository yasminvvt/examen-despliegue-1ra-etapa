<?php
session_start();
header('Content-Type: application/json');
require_once 'db_connection.php';

$response = array(
    'success' => false,
    'message' => '',
    'redirect' => ''
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
    $stmt = $pdo->prepare("SELECT id, username, password FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $response['success'] = true;
        $response['message'] = 'Login exitoso';
        $response['redirect'] = 'pagina.html';
    } else {
        $response['message'] = 'Usuario o contraseña incorrectos';
    }
} catch (Exception $e) {
    $response['message'] = 'Error al iniciar sesión';
}

echo json_encode($response);
?> 