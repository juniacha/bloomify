<?php

session_start();

include '../config/koneksi.php';

$id=$_GET['id'];

if(isset($_POST['update'])){

$nama=$_POST['nama_kategori'];

mysqli_query($koneksi,"
UPDATE kategori
SET nama_kategori='$nama'
WHERE id_kategori='$id'
");

header("Location:kategori.php");

}