<?php
session_start();
include '../config/koneksi.php';

if(!isset($_SESSION['email'])){
    header("Location:../auth/login.php");
    exit();
}

$sql_total_pesanan = "SELECT COUNT(*) as total_pesanan
                      FROM transaksi";

$query_total_pesanan = mysqli_query($koneksi, $sql_total_pesanan);

$total_pesanan = mysqli_fetch_assoc($query_total_pesanan);

$sql_pendapatan = "SELECT SUM(total_harga) as total_pendapatan
                   FROM transaksi
                   WHERE status='Selesai'";

$query_pendapatan = mysqli_query($koneksi, $sql_pendapatan);

$pendapatan = mysqli_fetch_assoc($query_pendapatan);
?>

<!DOCTYPE html>

<html>
<head>
    <title>Laporan Bloomify</title>
</head>
<body>

    <h2>Laporan Bloomify</h2>

    <a href="dashboard.php">Kembali ke Dashboard</a>

    <hr>

    <h3>Total Pesanan</h3>

    <p>
        <?= $total_pesanan['total_pesanan']; ?>
    </p>

    <h3>Total Pendapatan</h3>

    <p>
        Rp <?= number_format($pendapatan['total_pendapatan'] ?? 0); ?>
    </p>

    <hr>

    <h3>Daftar Pesanan</h3>

    <table border="1" cellpadding="10">

    <tr>
        <th>ID</th>
        <th>Pemesan</th>
        <th>Produk</th>
        <th>Total</th>
        <th>Status</th>
    </tr>

    <?php

    $sql = "SELECT *
            FROM transaksi
            JOIN produk
            ON transaksi.id_produk = produk.id_produk";

    $query = mysqli_query($koneksi, $sql);

    while($data = mysqli_fetch_assoc($query)){
    ?>

    <tr>
        <td><?= $data['id_transaksi']; ?></td>
        <td><?= $data['nama_pemesan']; ?></td>
        <td><?= $data['nama_produk']; ?></td>
        <td>Rp <?= number_format($data['total_harga']); ?></td>
        <td><?= $data['status']; ?></td>
    </tr>

    <?php } ?>

    </table>

</body>
</html>
