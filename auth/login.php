<?php
session_start();
include "../config.php";

if($_SERVER['REQUEST_METHOD'] == "POST"){
    $email = mysqli_real_escape_string($conn,$_POST['email']);
    $password = $_POST['password'];

    $result = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
    if(mysqli_num_rows($result) == 1){
        $user = mysqli_fetch_assoc($result);
        if(password_verify($password, $user['password'])){
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            if($user['role']=='admin' || $user['role']=='staff'){
                header("Location: ../dashboard/dashboard.php");
            } else {
                header("Location: ../index.php");
            }
            exit();
        } else { echo "Wrong password"; }
    } else { echo "User not found"; }
}
?>

<form method="post">
    <input type="email" name="email" required placeholder="Email"><br>
    <input type="password" name="password" required placeholder="Password"><br>
    <button type="submit">Login</button>
</form>