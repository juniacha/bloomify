<?php
session_start();

if(!isset($_SESSION['email'])){
    header("Location:../auth/login.php");
    exit();
}

if($_SESSION['role'] != "customer"){
    header("Location:../admin/dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Home Customer</title>
</head>
<body>

    <h2>Selamat Datang di Bloomify</h2>

    <p>Halo, <b><?= $_SESSION['nama']; ?></b></p>

    <hr>

    <a href="kategori.php">Kategori</a><br><br>
    <a href="pesanan_saya.php">Pesanan Saya</a><br><br>
    <a href="../auth/logout.php">Logout</a>

</body>
</html>