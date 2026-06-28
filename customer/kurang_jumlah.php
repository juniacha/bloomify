<?php
session_start();
include '../config/koneksi.php';

if(!isset($_SESSION['id_user'])){
    header("Location:../auth/login.php");
    exit();
}

$id_user = $_SESSION['id_user'];
$id = $_GET['id'];

$data = mysqli_fetch_assoc(mysqli_query($koneksi,"
SELECT jumlah
FROM keranjang
WHERE id_keranjang='$id'
AND id_user='$id_user'
"));

if($data['jumlah']>1){

    mysqli_query($koneksi,"
    UPDATE keranjang
    SET jumlah = jumlah - 1
    WHERE id_keranjang='$id'
    ");

}

header("Location:keranjang.php");
exit();
?>