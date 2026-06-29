<?php
session_start();
include '../config/koneksi.php';

if(isset($_POST['login'])){

    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users
        WHERE email='$email'";

    $query = mysqli_query($koneksi,$sql);

    if(mysqli_num_rows($query) > 0){

        $user = mysqli_fetch_assoc($query);

        if(password_verify($password, $user['password'])){

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
    <meta charset="UTF-8">

    <meta name="viewport"
    content="width=device-width, initial-scale=1">

    <title>Login | Bloomify</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet"
    href="../assets/css/style.css">
    <link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600;700&family=Poppins:wght@300;400;500;600&display=swap"
    rel="stylesheet">
</head>
<body class="auth-page">
    <div class="container">
        <div class="auth-card">
            <div class="row g-0">

                <!-- FOTO -->
                <div class="col-lg-6 d-none d-lg-block">
                    <div class="auth-image">
                        <img
                        src="../assets/img/login.jpg"
                        alt="Bloomify Login">
                    </div>
                </div>

                    <!-- FORM -->
                <div class="col-lg-6">
                    <div class="auth-form">
                        <h1>Welcome Back</h1>
                        <p>Sign in to continue your Bloomify journey.</p>
                        <form method="POST">
                            <div class="mb-3">
                                <label>Email</label>
                                <input
                                type="email"
                                name="email"
                                class="form-control"
                                placeholder="Enter your email"
                                required>
                            </div>

                            <div class="mb-4">
                                <label>Password</label>
                                <input
                                type="password"
                                name="password"
                                class="form-control"
                                placeholder="Enter your password"
                                required>
                            </div>

                            <button
                            type="submit"
                            name="login"
                            class="btn btn-bloom w-100">Login</button>
                        </form>

                        <div class="text-center mt-4">
                            Belum punya akun?
                            <a href="register.php">Daftar Sekarang</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>