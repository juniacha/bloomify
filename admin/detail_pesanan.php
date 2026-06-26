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

<h2>Detail Pesanan</h2>

<p>Nama Pemesan : <?= $data['nama_pemesan']; ?></p>

<p>No HP : <?= $data['no_hp']; ?></p>

<p>Produk : <?= $data['nama_produk']; ?></p>

<p>Jumlah : <?= $data['jumlah']; ?></p>

<p>Ukuran : <?= $data['ukuran']; ?></p>

<p>Warna Buket : <?= $data['warna_buket']; ?></p>

<p>Isi Surat : <?= $data['isi_surat']; ?></p>

<p>Catatan : <?= $data['catatan']; ?></p>

<p>Total Harga :
Rp <?= number_format($data['total_harga']); ?>
</p>

<p>Status :
<?= $data['status']; ?>
</p>

<a href="transaksi.php">
    Kembali
</a>
