<?php
session_start();
include '../config/koneksi.php';

if(isset($_POST['login'])){

    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users
            WHERE email='$email'
            AND password='$password'";

    $query = mysqli_query($koneksi,$sql);

    if(mysqli_num_rows($query) > 0){

        $user = mysqli_fetch_assoc($query);

        $_SESSION['id_user'] = $user['id_user'];
        $_SESSION['nama'] = $user['nama'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = $user['role'];

        if($user['role'] == "admin"){

            header("Location: ../admin/dashboard.php");
            exit();

        }else{

            header("Location: ../customer/index.php");
            exit();

        }

    }else{

        echo "<script>
                alert('Email atau Password salah!');
              </script>";

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

    Email <br>
    <input type="email" name="email" required>

    <br><br>

    Password <br>
    <input type="password" name="password" required>

    <br><br>

    <button type="submit" name="login">
        Login
    </button>

</form>

<br>

Belum punya akun?

<a href="register.php">
    Buat Akun Baru
</a>

</body>
</html>