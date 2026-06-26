<?php

session_start();
include '../config/koneksi.php';

if(!isset($_SESSION['email'])){
    header("Location:../auth/login.php");
    exit();
}

$id = $_GET['id'];

$sql = "SELECT * FROM transaksi
        WHERE id_transaksi='$id'";

$query = mysqli_query($koneksi, $sql);

$data = mysqli_fetch_assoc($query);

$status = $data['status'];

if($status == 'Pesanan Masuk'){
    $status_baru = 'Sedang Dirangkai';
}
elseif($status == 'Sedang Dirangkai'){
    $status_baru = 'Siap Diambil';
}
elseif($status == 'Siap Diambil'){
    $status_baru = 'Selesai';
}
else{
    $status_baru = 'Selesai';
}

$update = "UPDATE transaksi
           SET status='$status_baru'
           WHERE id_transaksi='$id'";

mysqli_query($koneksi, $update);

header("Location: transaksi.php");
exit();

?>