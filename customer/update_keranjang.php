<?php
session_start();
include '../config/koneksi.php';

if(!isset($_SESSION['id_user'])){
    header("Location:../auth/login.php");
    exit();
}

$id_user = $_SESSION['id_user'];

$id = $_POST['id_keranjang'];

$boneka = isset($_POST['boneka']) ? 1 : 0;
$balon = isset($_POST['balon']) ? 1 : 0;
$kartu_ucapan = isset($_POST['kartu_ucapan']) ? 1 : 0;

$warna_buket = mysqli_real_escape_string($koneksi,$_POST['warna_buket']);
$isi_surat = mysqli_real_escape_string($koneksi,$_POST['isi_surat']);
$catatan = mysqli_real_escape_string($koneksi,$_POST['catatan']);

mysqli_query($koneksi,"
UPDATE keranjang
SET
boneka='$boneka',
balon='$balon',
kartu_ucapan='$kartu_ucapan',
warna_buket='$warna_buket',
isi_surat='$isi_surat',
catatan='$catatan'
WHERE id_keranjang='$id'
AND id_user='$id_user'
");

if(isset($_GET['checkout'])){

    header("Location:checkout.php?id_keranjang=".$id);

}else{

    header("Location:keranjang.php");

}

exit();
?>