<?php

$host = 'localhost';
$user = 'root';
$pass = 'Canelahernandez#676';
$dbname = 'users_system';

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>