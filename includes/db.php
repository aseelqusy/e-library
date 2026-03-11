<?php
// Direct DB bootstrap used by auth/dashboard pages.
$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'library-db';

$conn = mysqli_connect($host, $user, $pass, $db);
if (!$conn) {
    die('DB Connection failed: ' . mysqli_connect_error());
}

mysqli_set_charset($conn, 'utf8mb4');


