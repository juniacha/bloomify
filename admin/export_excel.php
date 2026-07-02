<?php
session_start();
include '../config/koneksi.php';

if (!isset($_SESSION['email'])) {
    header("Location:../auth/login.php");
    exit();
}

$bulan = isset($_GET['bulan']) ? $_GET['bulan'] : date('m');
$tahun = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');
$status = isset($_GET['status']) ? $_GET['status'] : "";

$where = "WHERE MONTH(tanggal)='$bulan'
          AND YEAR(tanggal)='$tahun'";

if ($status != "") {
    $where .= " AND status='$status'";
}

$sql = "SELECT transaksi.*, produk.nama_produk
        FROM transaksi
        JOIN produk
        ON transaksi.id_produk = produk.id_produk
        $where
        ORDER BY tanggal DESC";

$query = mysqli_query($koneksi, $sql);

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=Laporan_Bloomify.xls");
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Laporan Bloomify</title>
</head>

<body>
    <h2>Laporan Penjualan Bloomify</h2>

    <p>
        Bulan : <?= $bulan; ?>
        <br>
        Tahun : <?= $tahun; ?>
        <br>
        Status :
        <?= ($status == "") ? "Semua" : $status; ?>
    </p>

    <table border="1">
        <tr>
            <th>ID</th>
            <th>Tanggal</th>
            <th>Nama Pemesan</th>
            <th>No HP</th>
            <th>Produk</th>
            <th>Ukuran</th>
            <th>Jumlah</th>
            <th>Total</th>
            <th>Sumber</th>
            <th>Status</th>
        </tr>

        <?php

        $total_semua = 0;
        if (mysqli_num_rows($query) > 0) {
            while ($data = mysqli_fetch_assoc($query)) {
                if ($data['status'] == "Selesai") {
                    $total_semua += $data['total_harga'];
                }
                ?>

                <tr>
                    <td><?= $data['id_transaksi']; ?></td>
                    <td><?= date('d-m-Y', strtotime($data['tanggal'])); ?></td>
                    <td><?= $data['nama_pemesan']; ?></td>
                    <td><?= $data['no_hp']; ?></td>
                    <td><?= $data['nama_produk']; ?></td>
                    <td><?= ucfirst(strtolower($data['ukuran'])); ?></td>
                    <td><?= $data['jumlah']; ?></td>

                    <td>
                        Rp <?= number_format($data['total_harga'], 0, ',', '.'); ?>
                    </td>
                    <td><?= $data['sumber']; ?></td>
                    <td><?= $data['status']; ?></td>
                </tr>

                <?php
            }
            ?>
            <tr>
                <td colspan="7" align="right">
                    <b>Total Pendapatan</b>
                </td>

                <td>
                    <b>Rp <?= number_format($total_semua, 0, ',', '.'); ?></b>
                </td>
                <td colspan="2"></td>
            </tr>

            <?php
        } else {
            ?>

            <tr>
                <td colspan="10" align="center">Data tidak ditemukan</td>
            </tr>
        <?php } ?>
    </table>
</body>

</html>