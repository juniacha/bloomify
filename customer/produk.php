<?php
session_start();
include '../config/koneksi.php';

if(!isset($_SESSION['email'])){
    header("Location:../auth/login.php");
    exit();
}

$id = $_GET['id'];
$sql = "SELECT * FROM produk WHERE id_kategori='$id'";

$query = mysqli_query($koneksi, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Produk</title>
</head>
<body>
    <h2>Daftar Produk</h2>
    <a href="kategori.php">Kembali</a>
    <hr>
    <?php while($data = mysqli_fetch_assoc($query)){ ?>
    <div style="border:1px solid #ccc;padding:20px;margin:15px;display:inline-block;">
        <?php if(!empty($data['gambar'])){ ?>
        <img src="../images/<?= $data['gambar']; ?>" width="180"><br><br>
        <?php } ?>
        <b><?= $data['nama_produk']; ?></b><br><br>
        Rp<?= number_format($data['harga_small']); ?>
        -
        Rp<?= number_format($data['harga_large']); ?><br><br>
        <a href="detail_produk.php?id=<?= $data['id_produk']; ?>">
        Lihat Detail
        </a>
    </div>
    <?php } ?>

</body>
</html>