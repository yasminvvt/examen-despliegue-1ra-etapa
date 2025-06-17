<?php
header('Content-Type: application/json');
require_once 'db_connection.php';

$response = array(
    'success' => false,
    'data' => array(),
    'message' => ''
);

try {
    $stmt = $pdo->query("SELECT * FROM students ORDER BY id DESC");
    $response['data'] = $stmt->fetchAll();
    $response['success'] = true;
    
    if (empty($response['data'])) {
        $response['message'] = 'No hay estudiantes registrados';
    }
} catch (Exception $e) {
    $response['message'] = 'Error al obtener estudiantes';
}

echo json_encode($response);
?> 