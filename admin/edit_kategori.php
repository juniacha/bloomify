<?php

session_start();

if(!isset($_SESSION['email'])){
    header("Location: ../auth/login.php");
    exit;
}

include '../config/koneksi.php';

$id = $_GET['id'];

$sql = "SELECT * FROM kategori
        WHERE id_kategori='$id'";

$query = mysqli_query($koneksi, $sql);

$data = mysqli_fetch_assoc($query);

if(isset($_POST['update'])){

    $nama_kategori = $_POST['nama_kategori'];

    $sql = "UPDATE kategori
            SET nama_kategori='$nama_kategori'
            WHERE id_kategori='$id'";

    $query = mysqli_query($koneksi, $sql);

    if($query){
        header("Location: kategori.php");
        exit;
    }else{
        echo "Gagal Update";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Kategori</title>
</head>
<body>
    <h2>Edit Kategori</h2>

    <form method="POST">
        <input type="text" name="nama_kategori" value="<?php echo $data['nama_kategori']; ?>">
        <button type="submit" name="update">Simpan</button>
    </form>
</body>
</html>