<?php
session_start();
include '../config/koneksi.php';

if(!isset($_SESSION['email'])){
    header("Location:../auth/login.php");
    exit();
}

$sql_kategori = "SELECT * FROM kategori";
$query_kategori = mysqli_query($koneksi,$sql_kategori);

if(isset($_POST['simpan'])){

    $id_kategori  = $_POST['id_kategori'];
    $nama_produk  = $_POST['nama_produk'];

    $harga_small  = $_POST['harga_small'];
    $harga_medium = $_POST['harga_medium'];
    $harga_large  = $_POST['harga_large'];

    $stok_small   = $_POST['stok_small'];
    $stok_medium  = $_POST['stok_medium'];
    $stok_large   = $_POST['stok_large'];

    $deskripsi    = $_POST['deskripsi'];

    $nama_file = $_FILES['gambar']['name'];
    $tmp       = $_FILES['gambar']['tmp_name'];

    if(move_uploaded_file($tmp,"../images/".$nama_file)){

        $query = mysqli_query($koneksi,$sql);

        if($query){

            echo "<script>
                    alert('Produk berhasil ditambahkan');
                    window.location='produk.php';
                </script>";
            exit();

        }else{

            echo "<script>
                    alert('Produk gagal ditambahkan');
                </script>";

        }

    }else{

        echo "<script>
                alert('Upload gambar gagal');
            </script>";

    }

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
        '$stok_small',
        '$stok_medium',
        '$stok_large',
        '$deskripsi'
    )";

    $query = mysqli_query($koneksi,$sql);

    if($query){

        echo "<script>

        alert('Produk berhasil ditambahkan');

        window.location='produk.php';

        </script>";

        exit();

    }else{

        echo "<script>

        alert('Produk gagal ditambahkan');

        </script>";

    }

}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Produk</title>
</head>
<body>

    <h2>Tambah Produk</h2>
    <a href="produk.php">Kembali</a>

    <hr>

    <form method="POST" enctype="multipart/form-data">
        <table>
            <tr>
                <td>Nama Produk</td>
                <td><input type="text" name="nama_produk" required></td>
            </tr>

            <tr>
                <td>Gambar Produk</td>
                <td><input type="file" name="gambar" accept="image/*" required></td>
            </tr>

            <tr>
                <td>Kategori</td>
                <td>
                    <select name="id_kategori" required>
                        <option value="">-- Pilih Kategori --</option>
                        <?php
                        while($kategori=mysqli_fetch_assoc($query_kategori)){
                        ?>
                        <option value="<?php echo $kategori['id_kategori']; ?>">
                            <?php echo $kategori['nama_kategori']; ?>
                        </option>
                        <?php } ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Harga Small</td>
                <td><input type="number" name="harga_small" required></td>
            </tr>

            <tr>
                <td>Harga Medium</td>
                <td><input type="number" name="harga_medium" required></td>
            </tr>

            <tr>
                <td>Harga Large</td>
                <td><input type="number" name="harga_large" required></td>
            </tr>

            <tr>
                <td>Stok Small</td>
                <td><input type="number" name="stok_small" required></td>

            </tr>

            <tr>
                <td>Stok Medium</td>
                <td><input type="number" name="stok_medium" required></td>
            </tr>

            <tr>
                <td>Stok Large</td>
                <td><input type="number" name="stok_large" required></td>
            </tr>

            <tr>
                <td>Deskripsi</td>
                <td><textarea name="deskripsi" rows="5" cols="40"></textarea></td>
            </tr>

            <tr>
                <tb></tb>
                <td><input type="submit" name="simpan" value="Simpan">
                    <input type="reset" value="Reset">
                    <a href="produk.php">Batal</a>
                </td>
            </tr>
        </table>
    </form>
</body>
</html>