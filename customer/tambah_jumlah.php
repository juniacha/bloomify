<?php
session_start();
include '../config/koneksi.php';

if (!isset($_SESSION['id_user'])) {
    header("Location:../auth/login.php");
    exit();
}

$id_user = $_SESSION['id_user'];
$id = $_GET['id'];

// Ambil data keranjang + produk
$sql = "SELECT keranjang.*,
        produk.stok_small,
        produk.stok_medium,
        produk.stok_large
        FROM keranjang
        JOIN produk
        ON keranjang.id_produk = produk.id_produk
        WHERE keranjang.id_keranjang='$id'
        AND keranjang.id_user='$id_user'";

$query = mysqli_query($koneksi, $sql);
$data = mysqli_fetch_assoc($query);

if (!$data) {
    header("Location:keranjang.php");
    exit();
}

// Tentukan stok sesuai ukuran
if ($data['ukuran'] == "Small") {

    $stok = $data['stok_small'];

} elseif ($data['ukuran'] == "Medium") {

    $stok = $data['stok_medium'];

} else {

    $stok = $data['stok_large'];

}

// Kalau jumlah masih di bawah stok, baru boleh tambah
if ($data['jumlah'] < $stok) {

    mysqli_query($koneksi, "
    UPDATE keranjang
    SET jumlah = jumlah + 1
    WHERE id_keranjang='$id'
    ");

} else {

    echo "<script>
    alert('Jumlah sudah mencapai stok maksimal!');
    window.location='keranjang.php';
    </script>";
    exit();

}

header("Location:keranjang.php");
exit();
?>