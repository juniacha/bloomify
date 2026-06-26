<?php

session_start();
include '../config/koneksi.php';

if(!isset($_SESSION['email'])){
    header("Location: ../auth/login.php");
    exit;
}

$id = $_GET['id'];

$sql = "DELETE FROM produk
        WHERE id_produk='$id'";

$query = mysqli_query($koneksi,$sql);

if($query){
    header("Location: produk.php");
    exit;
}
?>