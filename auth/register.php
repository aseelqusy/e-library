<?php
include "../config.php";

if($_SERVER['REQUEST_METHOD'] == "POST"){
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = 'user';

    $query = "INSERT INTO users (username,email,password,role) VALUES ('$username','$email','$password','$role')";
    if(mysqli_query($conn, $query)){
        echo "Registered! <a href='login.php'>Login here</a>";
    } else {
        echo "Error: ".mysqli_error($conn);
    }
}
?>

<form method="post">
    <input type="text" name="username" required placeholder="Username"><br>
    <input type="email" name="email" required placeholder="Email"><br>
    <input type="password" name="password" required placeholder="Password"><br>
    <button type="submit">Register</button>
</form>