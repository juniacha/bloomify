<?php
session_start();
include '../config/koneksi.php';

if(!isset($_SESSION['email'])){
    header("Location:../auth/login.php");
    exit();
}

$id = $_GET['id'];
$id_user = $_SESSION['id_user'];

$sql = "SELECT transaksi.*, produk.nama_produk, produk.gambar
        FROM transaksi
        JOIN produk
        ON transaksi.id_produk = produk.id_produk
        WHERE transaksi.id_transaksi='$id'
        AND transaksi.id_user='$id_user'";

$query = mysqli_query($koneksi,$sql);

if(mysqli_num_rows($query)==0){
    echo "Pesanan tidak ditemukan.";
    exit();
}

$data = mysqli_fetch_assoc($query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Detail Pesanan</title>
</head>
<body>

    <h2>Detail Pesanan</h2>

    <a href="pesanan_saya.php">
        ← Kembali
    </a>

    <hr>

    <?php if(!empty($data['gambar'])){ ?>
    <img src="../images/<?= $data['gambar']; ?>" width="250"><br><br>

    <?php } ?>

    <p>
        <b>Produk :</b>
        <?= $data['nama_produk']; ?>
    </p>

    <p>
        <b>Status :</b>
        <?= $data['status']; ?>
    </p>

    <p>
        <b>Ukuran :</b>
        <?= $data['ukuran']; ?>
    </p>

    <p>
        <b>Jumlah :</b>
        <?= $data['jumlah']; ?>
    </p>

    <p>
        <b>Boneka :</b>
        <?= ($data['boneka']) ? "Ya" : "Tidak"; ?>
    </p>

    <p>
        <b>Balon :</b>
        <?= ($data['balon']) ? "Ya" : "Tidak"; ?>
    </p>

    <p>
        <b>Kartu Ucapan :</b>
        <?= ($data['kartu_ucapan']) ? "Ya" : "Tidak"; ?>
    </p>

    <p>
        <b>Warna Buket :</b>
        <?= $data['warna_buket']; ?>
    </p>

    <p>
        <b>Isi Surat :</b><br>
        <?= nl2br($data['isi_surat']); ?>
    </p>

    <p>
        <b>Catatan :</b><br>
        <?= nl2br($data['catatan']); ?>
    </p>

    <hr>

    <h3>Total</h3>

    <p>
        Rp <?= number_format($data['total_harga']); ?>
    </p>

</body>
</html>