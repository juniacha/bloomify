<?php
session_start();
include '../config/koneksi.php';

if(!isset($_SESSION['email'])){
    header("Location:../auth/login.php");
    exit();
}

$id = $_GET['id'];

$sql = "SELECT *
        FROM transaksi
        JOIN produk
        ON transaksi.id_produk = produk.id_produk
        WHERE id_transaksi='$id'";

$query = mysqli_query($koneksi,$sql);

$data = mysqli_fetch_assoc($query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Detail Pesanan</title>
</head>
<body>

    <h2>Detail Pesanan</h2>

    <a href="transaksi.php">Kembali</a>

    <hr>

    <table border="1" cellpadding="10">
        <tr>
            <th width="200">Data</th>
            <th>Informasi</th>
        </tr>
        <tr>
            <td>ID Transaksi</td>
            <td><?= $data['id_transaksi']; ?></td>
        </tr>
        <tr>
            <td>Tanggal</td>
            <td><?= date('d-m-Y H:i', strtotime($data['tanggal'])); ?></td>
        </tr>
        <tr>
            <td>Sumber Pesanan</td>
            <td><?= $data['sumber']; ?></td>
        </tr>
        <tr>
            <td>Nama Pemesan</td>
            <td><?= $data['nama_pemesan']; ?></td>
        </tr>
        <tr>
            <td>No. HP</td>
            <td><?= $data['no_hp']; ?></td>
        </tr>
        <tr>
            <td>Produk</td>
            <td><?= $data['nama_produk']; ?></td>
        </tr>
        <tr>
            <td>Ukuran</td>
            <td><?= $data['ukuran']; ?></td>
        </tr>
        <tr>
            <td>Jumlah</td>
            <td><?= $data['jumlah']; ?></td>
        </tr>
        <tr>
            <td>Boneka</td>
            <td><?= ($data['boneka']==1) ? "Ya" : "Tidak"; ?></td>
        </tr>
        <tr>
            <td>Balon</td>
            <td><?= ($data['balon']==1) ? "Ya" : "Tidak"; ?></td>
        </tr>
        <tr>
            <td>Kartu Ucapan</td>
            <td><?= ($data['kartu_ucapan']==1) ? "Ya" : "Tidak"; ?></td>
        </tr>
        <tr>
            <td>Warna Buket</td>
            <td><?= $data['warna_buket']; ?></td>
        </tr>
        <tr>
            <td>Isi Surat</td>
            <td><?= nl2br($data['isi_surat']); ?></td>
        </tr>
        <tr>
            <td>Catatan</td>
            <td><?= nl2br($data['catatan']); ?></td>
        </tr>
        <tr>
            <td>Total Harga</td>
            <td>
                <strong>
                    Rp <?= number_format($data['total_harga'],0,',','.'); ?>
                </strong>
            </td>
        </tr>
        <tr>
            <td>Status</td>
            <td>
                <strong><?= $data['status']; ?></strong>
            </td>
        </tr>
    </table>
    <br>
    <a href="edit_status.php?id=<?= $data['id_transaksi']; ?>">Update Status</a>
    |
    <a href="transaksi.php">Kembali ke Transaksi</a>
</body>
</html>