<?php
session_start();
header('Content-Type: application/json');

$response = array(
    'logged_in' => isset($_SESSION['user_id']),
    'username' => $_SESSION['username'] ?? ''
);

echo json_encode($response);
?> 