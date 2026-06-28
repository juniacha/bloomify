<?php
session_start();
include '../config/koneksi.php';

if(!isset($_SESSION['email'])){
    header("Location:../auth/login.php");
    exit();
}

$sql = "SELECT transaksi.*, produk.nama_produk
        FROM transaksi
        JOIN produk
        ON transaksi.id_produk = produk.id_produk
        ORDER BY transaksi.id_transaksi DESC";

$query = mysqli_query($koneksi,$sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Kelola Transaksi</title>
</head>
<body>

    <h2>Kelola Transaksi</h2>

    <a href="dashboard.php">Kembali</a>
    |
    <a href="tambah_transaksi.php">
        Tambah Pesanan Offline
    </a>

    <hr>

    <table border="1" cellpadding="10">
        <tr>
            <th>ID</th>
            <th>Tanggal</th>
            <th>Nama Pemesan</th>
            <th>No HP</th>
            <th>Produk</th>
            <th>Jumlah</th>
            <th>Ukuran</th>
            <th>Total</th>
            <th>Sumber</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>

        <?php
        while($data=mysqli_fetch_assoc($query)){
        ?>
        <tr>
            <td><?php echo $data['id_transaksi']; ?></td>
            <td><?php echo date('d-m-Y H:i',strtotime($data['tanggal'])); ?></td>
            <td><?php echo $data['nama_pemesan']; ?></td>
            <td><?php echo $data['no_hp']; ?></td>
            <td><?php echo $data['nama_produk']; ?></td>
            <td><?php echo $data['jumlah']; ?></td>
            <td><?php echo $data['ukuran']; ?></td>
            <td>Rp <?php echo number_format($data['total_harga'],0,',','.'); ?></td>
            <td><?php echo $data['sumber']; ?></td>
            <td><?php echo $data['status']; ?></td>
            <td>
                <a href="detail_pesanan.php?id=<?php echo $data['id_transaksi']; ?>">Detail</a>
                |
                <a href="edit_status.php?id=<?php echo $data['id_transaksi']; ?>">Update Status</a>
            </td>
        </tr>
        <?php
        }
        ?>
    </table>

</body>
</html>