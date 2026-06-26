<?php
session_start();
include'../config/koneksi.php';

if(isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email='$email' AND password='$password'";
    $query = mysqli_query($koneksi, $sql);
    $cek = mysqli_num_rows($query);

    if($cek > 0){

        $_SESSION['email'] = $email;
        header("Location: ../admin/dashboard.php");
        exit;


    }else{
        echo "Login Gagal";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login Bloomify</title>
</head>
<body>
    
<h2>Login Bloomify</h2>

<form method="POST">
    <input type="email" name="email" placeholder="Email"><br><br>
    <input type="password" name="password" placeholder="Password"><br><br>

    <button type="submit" name="login">Login</button>
</form>

</body>
</html>

