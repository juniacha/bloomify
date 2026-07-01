<?php
include '../config/koneksi.php';

if (isset($_POST['daftar'])) {

    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $no_hp = $_POST['no_hp'];

    $password = $_POST['password'];
    $konfirmasi = $_POST['konfirmasi'];

    // Validasi password
    if (
        strlen($password) < 8 ||
        !preg_match('/[A-Z]/', $password) ||
        !preg_match('/[a-z]/', $password) ||
        !preg_match('/[0-9]/', $password) ||
        !preg_match('/[^a-zA-Z0-9]/', $password)
    ) {

        echo "<script>
                alert('Password minimal 8 karakter dan harus mengandung huruf besar, huruf kecil, angka, dan simbol.');
                history.back();
            </script>";

        exit();
    }

    //cek password
    if ($password != $konfirmasi) {
        echo "<script>
                alert('Konfirmasi password tidak sesuai!');
                </script>";
    } else {
        //cek email sudah ada atau belum
        $cek = mysqli_query($koneksi, "SELECT * FROM users WHERE email='$email'");

        if (mysqli_num_rows($cek) > 0) {
            echo "<script>
                    alert('Email sudah digunakan!');
                    </script>";
        } else {

            $password = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO users
                    (
                    nama,
                    email,
                    no_hp,
                    password,
                    role
                    )
                    VALUES
                    (
                    '$nama',
                    '$email',
                    '$no_hp',
                    '$password',
                    'customer'
                    )";

            $query = mysqli_query($koneksi, $sql);

            if ($query) {
                echo "<script>
                        alert('Registrasi berhasil');
                        window.location='login.php';
                        </script>";
            } else {
                echo "<script>
                        alert('Register gagal');
                        </script>";
            }

        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Register | Bloomify</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="../assets/css/style.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link
        href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600;700&family=Poppins:wght@300;400;500;600&display=swap"
        rel="stylesheet">

</head>

<body class="auth-page">

    <div class="container">

        <div class="auth-card">

            <div class="row g-0">

                <!-- FOTO -->

                <div class="col-lg-5 d-none d-lg-block">

                    <div class="auth-image">

                        <img src="../assets/img/register.jpg" alt="Bloomify Register">

                    </div>

                </div>

                <!-- FORM -->

                <div class="col-lg-7">

                    <div class="auth-form">

                        <h1>Create Account</h1>

                        <p>

                            Join Bloomify and start creating beautiful moments.

                        </p>

                        <form method="POST">

                            <div class="mb-3">

                                <label>Nama Lengkap</label>

                                <input type="text" name="nama" class="form-control" placeholder="Nama lengkap" required>

                            </div>

                            <div class="mb-3">

                                <label>Email</label>

                                <input type="email" name="email" class="form-control" placeholder="Email" required>

                            </div>

                            <div class="mb-3">

                                <label>No. Handphone</label>

                                <input type="text" name="no_hp" class="form-control" placeholder="08xxxxxxxxxx"
                                    required>

                            </div>

                            <div class="mb-3">

                                <label>Password</label>

                                <input type="password" name="password" class="form-control" placeholder="Password"
                                    required>

                            </div>

                            <div class="mb-4">

                                <label>Konfirmasi Password</label>

                                <input type="password" name="konfirmasi" class="form-control"
                                    placeholder="Konfirmasi Password" required>

                                <div class="small text-secondary mb-4">

                                    <i class="bi bi-info-circle me-2"></i>

                                    Minimal 8 karakter, huruf besar, huruf kecil, angka, dan simbol.

                                </div>

                            </div>

                            <button type="submit" name="daftar" class="btn btn-bloom w-100">

                                Create Account

                            </button>

                        </form>

                        <div class="text-center mt-4">

                            Sudah punya akun?

                            <a href="login.php">

                                Login

                            </a>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</body>

</html>