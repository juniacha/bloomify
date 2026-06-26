<?php
include'../config/koneksi.php';

$id = $_GET['id'];

$sql = "DELETE FROM kategori 
        WHERE id_kategori='$id'";

$query = mysqli_query($koneksi, $sql);

if($query) {
    header("Location: kategori.php");
    exit;
}else{
    echo "Gagal Hapus";
}
?>