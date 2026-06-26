<?php
session_start();
include'../config/koneksi.php';

if(!isset($_SESSION['email'])) {
    header("Location:../auth/login.php");
    exit();
}

$sql_kategori = "SELECT * FROM kategori";
$query_kategori = mysqli_query($koneksi, $sql_kategori);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Kelola Produk</title>
</head>
<body>
    <h2>Kelola Produk</h2>

    <a href="dashboard.php">Kembali ke Dashboard</a>

    <hr>

    <h3>Tambah Produk</h3>

    <form action="" method="POST">

        <label>Nama Produk</label><br>
        <input type="text" name="nama_produk" required>
        <br><br>

        <label>Kategori</label><br>
        <select name="id_kategori" required>

            <option value="">-- Pilih Kategori --</option>

            <?php
            while($kategori = mysqli_fetch_assoc($query_kategori)){
            ?>
                <option value="<?= $kategori['id_kategori']; ?>">
                    <?= $kategori['nama_kategori']; ?>
                </option>
            <?php
            }
            ?>

        </select>
        <br><br>

        <label>Harga Small</label><br>
        <input type="number" name="harga_small" required>
        <br><br>

        <label>Harga Medium</label><br>
        <input type="number" name="harga_medium" required>
        <br><br>

        <label>Harga Large</label><br>
        <input type="number" name="harga_large" required>
        <br><br>

        <label>Stok</label><br>
        <input type="number" name="stok" required>
        <br><br>

        <label>Deskripsi</label><br>
        <textarea name="deskripsi"></textarea>
        <br><br>

        <button type="submit" name="simpan">
            Simpan
        </button>

    </form>

<?php

if(isset($_POST['simpan'])){

    $id_kategori = $_POST['id_kategori'];
    $nama_produk = $_POST['nama_produk'];
    $harga_small = $_POST['harga_small'];
    $harga_medium = $_POST['harga_medium'];
    $harga_large = $_POST['harga_large'];
    $stok = $_POST['stok'];
    $deskripsi = $_POST['deskripsi'];

    $sql = "INSERT INTO produk
            (
            id_kategori,
            nama_produk,
            harga_small,
            harga_medium,
            harga_large,
            stok,
            deskripsi
            )
            VALUES
            (
            '$id_kategori',
            '$nama_produk',
            '$harga_small',
            '$harga_medium',
            '$harga_large',
            '$stok',
            '$deskripsi'
            )";

    $query = mysqli_query($koneksi, $sql);

    if($query){
        echo "<script>
                alert('Produk berhasil ditambahkan');
                window.location='produk.php';
              </script>";
    }else{
        echo "Gagal menambahkan produk";
    }
}
?>
<br>
<hr>

<h3>Data Produk</h3>

<table border="1" cellpadding="10">

    <tr>
        <th>ID</th>
        <th>Produk</th>
        <th>Kategori</th>
        <th>Small</th>
        <th>Medium</th>
        <th>Large</th>
        <th>Stok</th>
        <th>Deskripsi</th>
        <th>Aksi</th>
    </tr>

<?php

$sql_produk = "SELECT *
               FROM produk
               JOIN kategori
               ON produk.id_kategori = kategori.id_kategori";

$query_produk = mysqli_query($koneksi, $sql_produk);

while($data = mysqli_fetch_assoc($query_produk)){
?>

<tr>
    <td><?= $data['id_produk']; ?></td>
    <td><?= $data['nama_produk']; ?></td>
    <td><?= $data['nama_kategori']; ?></td>
    <td><?= $data['harga_small']; ?></td>
    <td><?= $data['harga_medium']; ?></td>
    <td><?= $data['harga_large']; ?></td>
    <td><?= $data['stok']; ?></td>
    <td><?= $data['deskripsi'];?></td>

    <td>
        <a href="edit_produk.php?id=<?= $data['id_produk']; ?>">
            Edit
        </a>

        |

        <a href="hapus_produk.php?id=<?= $data['id_produk']; ?>"
           onclick="return confirm('Yakin ingin menghapus produk ini?')">
            Hapus
        </a>
    </td>
</tr>

<?php
}
?>

</table>

</body>
</html>