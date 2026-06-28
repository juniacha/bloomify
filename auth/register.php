<?php
include'../config/koneksi.php';

if(isset($_POST['daftar'])){
    
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $no_hp = $_POST['no_hp'];

    $password = $_POST['password'];
    $konfirmasi = $_POST['konfirmasi'];

    // Validasi password
    if(
        strlen($password) < 8 ||
        !preg_match('/[A-Z]/', $password) ||
        !preg_match('/[a-z]/', $password) ||
        !preg_match('/[0-9]/', $password) ||
        !preg_match('/[^a-zA-Z0-9]/', $password)
    ){

        echo "<script>
                alert('Password minimal 8 karakter dan harus mengandung huruf besar, huruf kecil, angka, dan simbol.');
                history.back();
            </script>";

        exit();
    }

    //cek password
    if($password != $konfirmasi){
        echo "<script>
                alert('Konfirmasi password tidak sesuai!');
                </script>";
    }else{
        //cek email sudah ada atau belum
        $cek = mysqli_query($koneksi, "SELECT * FROM users WHERE email='$email'");

        if(mysqli_num_rows($cek) > 0){
            echo "<script>
                    alert('Email sudah digunakan!');
                    </script>";
        }else{

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

            $query = mysqli_query($koneksi,$sql);
            
            if($query){
                echo "<script>
                        alert('Registrasi berhasil');
                        window.location='login.php';
                        </script>";
            }else{
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
    <title>Register Bloomify</title>
</head>
<body>
    <h2>Daftar Akun</h2>
    <form method="POST">

        Nama <br>
        <input type="text" name="nama" required><br><br>

        Email<br>
        <input type="email" name="email" required><br><br>

        No Telp<br><br>
        <input type="text" name="no_hp" required><br><br>

        Password<br>
        <input type="password" name="password" required><br><br>

        Konfirmasi Password<br>
        <input type="password" name="konfirmasi" required><br><br>

        <button type="submit" name="daftar">
            Daftar
        </button>

    </form>

    <br>

    Sudah punya akun?
    <a href="login.php">login</a>

</body>
</html>