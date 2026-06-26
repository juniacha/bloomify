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

$query = mysqli_query($koneksi,$sql);

$data = mysqli_fetch_assoc($query);

if(isset($_POST['update'])){

    $status = $_POST['status'];

    $update = "UPDATE transaksi
               SET status='$status'
               WHERE id_transaksi='$id'";

    mysqli_query($koneksi,$update);

    header("Location: transaksi.php");
    exit();
}
?>

<h2>Update Status Pesanan</h2>

<form method="POST">

<select name="status">

    <option value="Pesanan Masuk"
    <?= ($data['status']=='Pesanan Masuk')?'selected':'' ?>>
    Pesanan Masuk
    </option>

    <option value="Sedang Dirangkai"
    <?= ($data['status']=='Sedang Dirangkai')?'selected':'' ?>>
    Sedang Dirangkai
    </option>

    <option value="Siap Diambil"
    <?= ($data['status']=='Siap Diambil')?'selected':'' ?>>
    Siap Diambil
    </option>

    <option value="Selesai"
    <?= ($data['status']=='Selesai')?'selected':'' ?>>
    Selesai
    </option>

</select>

<br><br>

<button type="submit" name="update">
    Simpan
</button>


</form>
