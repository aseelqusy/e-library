<?php
// includes/db.php
$host = "localhost";
$user = "root";
$pass = "";          // put your XAMPP MySQL password if you set one
$db   = "e_library"; // change to your database name

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("DB Connection failed: " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8mb4");