<?php
$host = 'localhost';
$dbname = 'u178928053_examen_zs';
$username = 'u178928053_zahara_santi';
$password = 'h7+WTkW3Je';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
?> 