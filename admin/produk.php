<?php
session_start();
include'../config/koneksi.php';

if(!isset($_SESSION['email'])) {
    header("Location:../auth/login.php");
    exit();
}

$sql = "SELECT produk.*, kategori.nama_kategori
        FROM produk
        JOIN kategori
        ON produk.id_kategori = kategori.id_kategori
        ORDER BY produk.id_produk DESC";

$query = mysqli_query($koneksi,$sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Kelola Produk</title>
</head>
<body>
    <h2>Kelola Produk</h2>

    <a href="dashboard.php">Kembali ke Dashboard</a>
    |
    <a href="tambah_produk.php">Tambah Produk</a>

    <hr>

    <h3>Data Produk</h3>

    <table border="1" cellpadding="10">

        <tr>
            <th>ID</th>
            <th>Gambar</th>
            <th>Nama Produk</th>
            <th>Kategori</th>
            <th>Harga Small</th>
            <th>Harga Medium</th>
            <th>Harga Large</th>
            <th>Stok Small</th>
            <th>Stok Medium</th>
            <th>Stok Large</th>
            <th>Deskripsi</th>
            <th>Aksi</th>
        </tr>

        <?php
        while($data = mysqli_fetch_assoc($query)){
        ?>
        <tr>
            <td>
                <?php echo $data['id_produk']; ?>
            </td>
            <td><img src="../images/<?php echo $data['gambar']; ?>"
                width="80"
                height="80"
                style="object-fit:cover;">
            </td>
            <td><?php echo $data['nama_produk']; ?></td>
            <td><?php echo $data['nama_kategori']; ?></td>
            <td>Rp <?php echo number_format($data['harga_small'],0,',','.'); ?></td>
            <td>Rp <?php echo number_format($data['harga_medium'],0,',','.'); ?></td>
            <td>Rp <?php echo number_format($data['harga_large'],0,',','.'); ?></td>
            <td><?php echo $data['stok_small']; ?></td>
            <td><?php echo $data['stok_medium']; ?></td>
            <td><?php echo $data['stok_large']; ?></td>
            <td><?php echo $data['deskripsi']; ?></td>
            <td>
                <a href="edit_produk.php?id=<?php echo $data['id_produk']; ?>">Edit</a>
                |
                <a href="hapus_produk.php?id=<?php echo $data['id_produk']; ?>"
                    onclick="return confirm('Yakin ingin menghapus produk ini?')">Hapus</a>
            </td>
        </tr>
        <?php } ?>
    </table>
</body>
</html>