<?php
header('Content-Type: application/json');
require_once 'db_connection.php';

$response = array(
    'success' => false,
    'message' => ''
);

$data = json_decode(file_get_contents('php://input'), true);
$id = $data['id'] ?? '';

if (empty($id)) {
    $response['message'] = 'ID de estudiante no proporcionado';
    echo json_encode($response);
    exit;
}

try {
    $stmt = $pdo->prepare("DELETE FROM students WHERE id = ?");
    
    if ($stmt->execute([$id])) {
        $response['success'] = true;
        $response['message'] = 'Estudiante eliminado exitosamente';
    } else {
        throw new Exception("Error al eliminar estudiante");
    }
} catch (Exception $e) {
    $response['message'] = 'Error al eliminar estudiante';
}

echo json_encode($response);
?> 