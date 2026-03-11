<?php
// Shared DB config for the whole project.
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'library-db';

if (!isset($conn) || !($conn instanceof mysqli)) {
    $conn = mysqli_connect($host, $user, $password, $database);

    if (!$conn) {
        die('Connection failed: ' . mysqli_connect_error());
    }

    mysqli_set_charset($conn, 'utf8mb4');
}