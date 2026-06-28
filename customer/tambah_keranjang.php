<?php
session_start();
include '../config/koneksi.php';

if(!isset($_SESSION['id_user'])){
    header("Location:../auth/login.php");
    exit();
}

$id_user   = $_SESSION['id_user'];
$id_produk = $_GET['id_produk'];
$ukuran    = $_GET['ukuran'];

if(empty($ukuran)){
    echo "<script>
            alert('Silakan pilih ukuran terlebih dahulu.');
            history.back();
          </script>";
    exit();
}

// Cek apakah produk sudah ada di keranjang
$cek = mysqli_query($koneksi,"
SELECT * FROM keranjang
WHERE id_user='$id_user'
AND id_produk='$id_produk'
AND ukuran='$ukuran'
");

if(mysqli_num_rows($cek)>0){

    mysqli_query($koneksi,"
    UPDATE keranjang
    SET jumlah = jumlah + 1
    WHERE id_user='$id_user'
    AND id_produk='$id_produk'
    AND ukuran='$ukuran'
    ");

}else{

    mysqli_query($koneksi,"
    INSERT INTO keranjang
    (
        id_user,
        id_produk,
        jumlah,
        ukuran
    )
    VALUES
    (
        '$id_user',
        '$id_produk',
        1,
        '$ukuran'
    )
    ");

}

echo "<script>
        alert('Produk berhasil ditambahkan ke keranjang');
        window.location='keranjang.php';
      </script>";
exit();
?>