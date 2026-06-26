<?php
session_start();
include '../config/koneksi.php';

if(!isset($_SESSION['email'])){
    header("Location: ../auth/login.php");
    exit();
}

$id = $_GET['id'];

$sql = "SELECT * FROM produk
        WHERE id_produk='$id'";

$query = mysqli_query($koneksi, $sql);

$data = mysqli_fetch_assoc($query);

if(isset($_POST['update'])){

    $nama_produk = $_POST['nama_produk'];
    $harga_small = $_POST['harga_small'];
    $harga_medium = $_POST['harga_medium'];
    $harga_large = $_POST['harga_large'];
    $stok = $_POST['stok'];
    $deskripsi = $_POST['deskripsi'];

    $sql_update = "UPDATE produk
                   SET
                   nama_produk='$nama_produk',
                   harga_small='$harga_small',
                   harga_medium='$harga_medium',
                   harga_large='$harga_large',
                   stok='$stok',
                   deskripsi='$deskripsi'
                   WHERE id_produk='$id'";

    $query_update = mysqli_query($koneksi, $sql_update);

    if($query_update){
        header("Location: produk.php");
        exit();
    }else{
        echo "Gagal mengupdate produk";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Produk</title>
</head>
<body>

    <h2>Edit Produk</h2>

    <form method="POST">

        <label>Nama Produk</label><br>
        <input type="text" name="nama_produk" value="<?= $data['nama_produk']; ?>" required>
        <br><br>

        <label>Harga Small</label><br>
        <input type="number" name="harga_small" value="<?= $data['harga_small']; ?>" required>
        <br><br>

        <label>Harga Medium</label><br>
        <input type="number" name="harga_medium" value="<?= $data['harga_medium']; ?>" required>
        <br><br>

        <label>Harga Large</label><br>
        <input type="number" name="harga_large" value="<?= $data['harga_large']; ?>" required>
        <br><br>

        <label>Stok</label><br>
        <input type="number" name="stok" value="<?= $data['stok']; ?>" required>
        <br><br>

        <label>Deskripsi</label><br>
        <textarea name="deskripsi"><?= $data['deskripsi']; ?></textarea>
        <br><br>

        <button type="submit" name="update">
            Update
        </button>

    </form>

    <br>

    <a href="produk.php">
        Kembali
    </a>

</body>
</html>
