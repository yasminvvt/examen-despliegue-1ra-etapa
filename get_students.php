<?php
header('Content-Type: application/json');
require_once 'db_connection.php';

$response = array(
    'success' => false,
    'data' => array(),
    'message' => ''
);

try {
    $result = $conn->query("SELECT * FROM students ORDER BY id DESC");
    
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $response['data'][] = $row;
        }
        $response['success'] = true;
        if (empty($response['data'])) {
            $response['message'] = 'No hay estudiantes registrados';
        }
    } else {
        throw new Exception("Error al obtener estudiantes");
    }
} catch (Exception $e) {
    $response['message'] = 'Error al obtener estudiantes';
}

$conn->close();
echo json_encode($response);
?> 