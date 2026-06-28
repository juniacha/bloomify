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
    $stok_small = $_POST['stok_small'];
    $stok_medium = $_POST['stok_medium'];
    $stok_large = $_POST['stok_large'];
    $deskripsi = $_POST['deskripsi'];

    $sql_update = "UPDATE produk
                   SET
                   nama_produk='$nama_produk',
                   harga_small='$harga_small',
                   harga_medium='$harga_medium',
                   harga_large='$harga_large',
                   stok_small='$stok_small',
                   stok_medium='$stok_medium',
                   stok_large='$stok_large',
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

        <label>Stok Small</label><br>
        <input type="number" name="stok_small" value="<?= $data['stok_small']; ?>" required>
        <br><br>

        <label>Stok Medium</label><br>
        <input type="number" name="stok_medium" value="<?= $data['stok_medium']; ?>" required>
        <br><br>

        <label>Stok Large</label><br>
        <input type="number" name="stok_large" value="<?= $data['stok_large']; ?>" required>
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
