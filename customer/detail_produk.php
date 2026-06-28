<?php
session_start();
include '../config/koneksi.php';

if(!isset($_SESSION['email'])){
    header("Location:../auth/login.php");
    exit();
}

$id = $_GET['id'];

$sql = "SELECT produk.*, kategori.nama_kategori
        FROM produk
        JOIN kategori
        ON produk.id_kategori = kategori.id_kategori
        WHERE produk.id_produk='$id'";

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

    <p>
        <b>Kategori :</b>
        <?= $data['nama_kategori']; ?>
    </p>

    <p>
        <b>Deskripsi :</b><br>
        <?= $data['deskripsi']; ?>
    </p>

        <hr>
        <h3>Pilih Ukuran</h3>

        <?php if($data['stok_small'] > 0){ ?>
        <label>
            <input type="radio" name="pilihUkuran"
            onclick="ubahHarga(
            <?= $data['harga_small']; ?>,
            <?= $data['stok_small']; ?>,'Small')">
            Small 
            (Stok <?= $data['stok_small']; ?>)
        </label>
        <?php }else{ ?>
        <label style="color:gray">
            <input type="radio" disabled>
            Small (Habis)
        </label>
        <?php } ?> <br><br>

        <?php if($data['stok_medium'] > 0){ ?>
        <label>
            <input type="radio" name="pilihUkuran"
            onclick="ubahHarga(
            <?= $data['harga_medium']; ?>,
            <?= $data['stok_medium']; ?>, 'Medium')">
            Medium
            (Stok <?= $data['stok_medium']; ?>)
        </label>
        <?php }else{ ?>
        <label style="color:gray">
            <input type="radio" disabled>
            Medium (Habis)
        </label>
        <?php } ?> <br><br>

        <?php if($data['stok_large'] > 0){ ?>
        <label>
            <input type="radio" name="pilihUkuran"
            onclick="ubahHarga(
            <?= $data['harga_large']; ?>,
            <?= $data['stok_large']; ?>, 'Large')">
            Large
            (Stok <?= $data['stok_large']; ?>)
        </label>
        <?php }else{ ?>
        <label style="color:gray">
            <input type="radio" disabled>
            Large (Habis)
        </label>
        <?php } ?>

        <hr>
        <h3>Stok</h3>
        <p id="stok">
            Pilih ukuran terlebih dahulu
        </p>

        <hr>
        <h3>Harga</h3>
        <p id="harga">
            Pilih ukuran terlebih dahulu
        </p>

        <hr>

        <form action="form_pemesanan.php" method="GET">

            <input type="hidden"
                name="id_produk"
                value="<?= $data['id_produk']; ?>">

            <input type="hidden"
                name="ukuran"
                id="ukuran">

            <button type="submit"
                id="btnKeranjang"
                formaction="tambah_keranjang.php" disabled>
                🛒 Tambah ke Keranjang
            </button>

            <button type="submit"
                id="btnCheckout"
                formaction="checkout.php" disabled>
                ⚡ Checkout Sekarang
            </button>

        </form>

        <p style="color:gray;">
            Silakan pilih ukuran terlebih dahulu untuk melanjutkan pemesanan.
        </p>

        <script>

            function ubahHarga(harga, stok, ukuran){

                // Tampilkan harga
                document.getElementById("harga").innerHTML =
                "Rp " + harga.toLocaleString('id-ID');

                // Tampilkan stok
                document.getElementById("stok").innerHTML =
                stok + " tersedia";

                // Simpan ukuran yang dipilih
                document.getElementById("ukuran").value =
                ukuran;

                // Aktifkan tombol Pesan Sekarang
                document.getElementById("btnKeranjang").disabled = false;
                document.getElementById("btnCheckout").disabled = false;

            }

        </script>

</body>
</html>