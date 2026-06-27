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

    <form action="" method="POST" enctype="multipart/form-data">

        <label>Nama Produk</label><br>
        <input type="text" name="nama_produk" required>
        <br><br>

        <label>Gambar Produk</label><br>
        <input type="file" name="gambar" accept="image/*" required>
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

        <label>Stok Small</label><br>
        <input type="number" name="stok_small" required>

        <br><br>

        <label>Stok Medium</label><br>
        <input type="number" name="stok_medium" required>

        <br><br>

        <label>Stok Large</label><br>
        <input type="number" name="stok_large" required>

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
    $stok_small = $_POST['stok_small'];
    $stok_medium = $_POST['stok_medium'];
    $stok_large = $_POST['stok_large'];
    $deskripsi = $_POST['deskripsi'];
    $nama_file = $_FILES['gambar']['name'];
    $tmp = $_FILES['gambar']['tmp_name'];

    move_uploaded_file($tmp, "../images/".$nama_file);

    $sql = "INSERT INTO produk
            (
            id_kategori,
            nama_produk,
            gambar,
            harga_small,
            harga_medium,
            harga_large,
            stok_small,
            stok_medium,
            stok_large,
            deskripsi
            )
            VALUES
            (
            '$id_kategori',
            '$nama_produk',
            '$nama_file',
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
        <th>Gambar</th>
        <th>Produk</th>
        <th>Kategori</th>
        <th>Small</th>
        <th>Medium</th>
        <th>Large</th>
        <th>Stok S</th>
        <th>Stok M</th>
        <th>Stok L</th>
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
    <td>
        <img src="../images/<?= $data['gambar']; ?>"
             width="80"
             height="80"
             style="object-fit:cover;">
    </td>
    <td><?= $data['nama_produk']; ?></td>
    <td><?= $data['nama_kategori']; ?></td>
    <td><?= $data['harga_small']; ?></td>
    <td><?= $data['harga_medium']; ?></td>
    <td><?= $data['harga_large']; ?></td>
    <td><?= $data['stok_small']; ?></td>
    <tb><?= $data['stok_medium'];?></tb>
    <tb><?= $data['stok_large'];?></tb>
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