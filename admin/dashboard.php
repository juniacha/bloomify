<?php
session_start();

if(!isset($_SESSION['email'])){
    header("Location: ../auth/login.php");
    exit;
}
?>

<h1>Dashboard Admin Bloomify</h1>

<p>Selamat datang, <br>
    <?php echo $_SESSION['email']; ?>
</p>

<a href="kategori.php">Kelola Kategori</a><br>
<a href="produk.php">Kelola Produk</a><br>
<a href="transaksi.php">Kelola Pesanan</a><br>
<a href="laporan.php">Laporan</a><br>
<a href="../auth/logout.php">Logout</a>