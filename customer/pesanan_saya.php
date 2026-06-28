<?php
session_start();
include '../config/koneksi.php';

if(!isset($_SESSION['email'])){
    header("Location:../auth/login.php");
    exit();
}

$id_user = $_SESSION['id_user'];

$sql = "SELECT transaksi.*, produk.nama_produk
        FROM transaksi
        JOIN produk
        ON transaksi.id_produk = produk.id_produk
        WHERE transaksi.id_user='$id_user'
        ORDER BY transaksi.id_transaksi DESC";

$query = mysqli_query($koneksi,$sql);
$total = mysqli_num_rows($query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Pesanan Saya</title>
</head>
<body>

    <h2>Pesanan Saya</h2>

    <a href="index.php">Kembali</a>
    <hr>

    <?php if($total > 0) { ?>

    <table border="1" cellpadding="10">
        <tr>
            <th>No</th>
            <th>ID Pesanan</th>
            <th>Produk</th>
            <th>Ukuran</th>
            <th>Jumlah</th>
            <th>Total</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>

        <?php
        $no=1;
        while($data=mysqli_fetch_assoc($query)){
        ?>

        <tr>
            <td><?= $no++; ?></td>
            <td>#<?= $data['id_transaksi']; ?></td>
            <td><?= $data['nama_produk']; ?></td>
            <td><?= $data['ukuran']; ?></td>
            <td><?= $data['jumlah']; ?></td>

            <td>
                Rp <?= number_format($data['total_harga']); ?>
            </td>

            <td>
                <?= $data['status']; ?>
            </td>

            <td>

                <a href="detail_pesanan.php?id=<?= $data['id_transaksi']; ?>">
                    Lihat
                </a>

                <?php if($data['status']=="Pesanan Masuk"){ ?>

                    |

                    <a href="batalkan_pesanan.php?id=<?= $data['id_transaksi']; ?>"
                    onclick="return confirm('Yakin ingin membatalkan pesanan?')">
                        Batalkan
                    </a>
                <?php } ?>

            </td>

    </tr>

    <?php } ?>

    </table>
    <?php }else{ ?>
    <p>Belum ada pesanan</p>
    <?php } ?>

</body>
</html>