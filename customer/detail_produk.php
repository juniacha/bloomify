<?php
session_start();
include '../config/koneksi.php';

if(!isset($_SESSION['email'])){
    header("Location:../auth/login.php");
    exit();
}

$id = $_GET['id'];

$sql = "SELECT * FROM produk WHERE id_produk='$id'";

$query = mysqli_query($koneksi, $sql);
$data = mysqli_fetch_array($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Detail Produk</title>
</head>
<body>
    <h2><?= $data['nama_produk'];?></h2>

    <a href="javascript:history.back()">
        Kembali
    </a>
    <hr>
    <?php if(!empty($data['gambar'])) { ?>
        <img src="../images/<?= $data['gambar']; ?>" width="250">
        <?php } ?> <br><br>

        <p><?= $data['deskripsi']; ?></p>

        <label>
            <input type="radio" name="ukuran" value="Small" 
            onclick="ubahHarga(
            <?= $data['harga_small']; ?>, 
            <?= $data['stok_small']; ?>, 'Small')">
            Small
        </label><br><br>

        <label>
            <input type="radio" name="ukuran" value="Medium" 
            onclick="ubahHarga(
            <?= $data['harga_medium']; ?>, 
            <?= $data['stok_medium']; ?>, 'Medium')">
            Medium
        </label><br><br>

        <label>
            <input type="radio" name="ukuran" value="Large" 
            onclick="ubahHarga(
            <?= $data['harga_large']; ?>, 
            <?= $data['stok_large']; ?>, 'Large')">
            Large
        </label><br>

        <hr>
        <h3>Harga</h3>
        <p id="harga">
            Pilih ukuran terlebih dahulu
        </p>

        <hr>
        <h3>Stok</h3>
        <p id="stok">
            Pilih ukuran terlebih dahulu
        </p>

        <hr>
        <form action="checkout.php" method="GET">
            <input type="hidden" name="id_produk" value="<?= $data['id_produk']; ?>">
            <input type="hidden" name="ukuran" id="ukuran"> 
            Jumlah <br>
            <input type="number" name="jumlah" value="1" min="1" required><br><br> 
            Boneka (+25.000)
            <input type="checkbox" name="boneka" value="1"><br><br>
            Balon(+15.000)
            <input type="checkbox" name="balon" value="1"><br><br>
            Kartu Ucapan (+5000)
            <input type="checkbox" name="kartu" value="1"><br><br>
            Warna Buket
            <br>
            <input type="text" name="warna_buket"><br><br>
            Isi Surat<br>
            <textarea name="isi_surat" rows="4" cols="40"></textarea><br><br>
            Catatan<br>
            <textarea name="catatan" rows="4"></textarea><br><br>
            <button type="submit">Pesan Sekarang</button>
        </form>

        <script>
            function ubahHarga(harga,stok,ukuran){
            document.getElementById("harga").innerHTML =
            "Rp" + harga.toLocaleString('id-ID');

            document.getElementById("stok").innerHTML =
            stok;

            document.getElementById("ukuran").value =
            ukuran;
            }   
        </script>
</body>
</html>