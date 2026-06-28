<?php
session_start();
include '../config/koneksi.php';

if(!isset($_SESSION['email'])){
    header("Location:../auth/login.php");
    exit();
}

$total_produk = mysqli_fetch_assoc(
    mysqli_query($koneksi,"SELECT COUNT(*) AS total FROM produk"));

$total_kategori = mysqli_fetch_assoc(
    mysqli_query($koneksi,"SELECT COUNT(*) AS total FROM kategori"));

$total_pesanan = mysqli_fetch_assoc(
    mysqli_query($koneksi,"SELECT COUNT(*) AS total FROM transaksi"));

$pesanan_masuk = mysqli_fetch_assoc(
    mysqli_query($koneksi,"SELECT COUNT(*) AS total FROM transaksi WHERE status='Pesanan Masuk'"));

$diproses = mysqli_fetch_assoc(
    mysqli_query($koneksi,"SELECT COUNT(*) AS total FROM transaksi WHERE status='Diproses'"));

$selesai = mysqli_fetch_assoc(
    mysqli_query($koneksi,"SELECT COUNT(*) AS total FROM transaksi WHERE status='Selesai'"));

$menunggu = mysqli_fetch_assoc(
    mysqli_query($koneksi,"SELECT COUNT(*) AS total FROM transaksi WHERE status='Menunggu Pembatalan'"));

$dibatalkan = mysqli_fetch_assoc(
    mysqli_query($koneksi,"SELECT COUNT(*) AS total FROM transaksi WHERE status='Dibatalkan'"));

$pendapatan = mysqli_fetch_assoc(
    mysqli_query($koneksi,"
        SELECT IFNULL(SUM(total_harga),0) AS total
        FROM transaksi
        WHERE status='Selesai'
    "));
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Admin</title>
</head>
<body>

    <h2>Dashboard Admin Bloomify</h2>
    <hr>
    <h3>Master Data</h3>

    <ul>
        <li>Total Kategori :
        <b><?= $total_kategori['total']; ?></b></li>

        <li>Total Produk :
        <b><?= $total_produk['total']; ?></b></li>
    </ul>

    <hr>

    <h3>Transaksi</h3>
    <ul>

        <li>Total Pesanan :
        <b><?= $total_pesanan['total']; ?></b></li>

        <li>Pesanan Masuk :
        <b><?= $pesanan_masuk['total']; ?></b></li>

        <li>Diproses :
        <b><?= $diproses['total']; ?></b></li>

        <li>Menunggu Pembatalan :
        <b><?= $menunggu['total']; ?></b></li>

        <li>Dibatalkan :
        <b><?= $dibatalkan['total']; ?></b></li>

        <li>Selesai :
        <b><?= $selesai['total']; ?></b></li>

    </ul>

        <hr>
        <h3>Pendapatan</h3>
        <h2>Rp <?= number_format($pendapatan['total'],0,',','.'); ?></h2>

        <hr>

        <h3>Menu Admin</h3>

        <ul>
            <li>
                <a href="kategori.php">Kelola Kategori</a>
            </li>
            <li>
                <a href="produk.php">Kelola Produk</a>
            </li>
            <li>
                <a href="transaksi.php">Kelola Pesanan</a>
            </li>
            <li>
                <a href="laporan.php">Laporan Penjualan</a>
            </li>
            <li>
                <a href="../auth/logout.php">Logout</a>
            </li>
        </ul>
</body>
</html>