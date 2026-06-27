<?php
session_start();
include'../config/koneksi.php';

if(!isset($_SESSION['email'])){
    header("Location:../auth/login.php");
    exit();
}
$sql = "SELECT * FROM kategori";
$query = mysqli_query($koneksi, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Kategori</title>
</head>
<body>
    <h2>Pilih Kategori</h2>

    <a href="index.php">Kembali</a>

    <hr>
    <?php while($kategori = mysqli_fetch_assoc($query)){ ?>
    <div style="border:1px solid #ccc;padding:15px;margin:10px;display:inline-block;">
    <h3><?= $kategori['nama_kategori']; ?></h3>

    <a href="produk.php?id=<?= $kategori['id_kategori']; ?>">
    Lihat Produk
    </a>

    </div>

    <?php } ?>

</body>
</html>