<?php
session_start();
include '../config/koneksi.php';

if(!isset($_SESSION['email'])){
    header("Location:../auth/login.php");
    exit();
}

$id_user = $_SESSION['id_user'];

$sql = "SELECT keranjang.*, produk.*
        FROM keranjang
        JOIN produk
        ON keranjang.id_produk = produk.id_produk
        WHERE keranjang.id_user='$id_user'
        ORDER BY keranjang.id_keranjang DESC";

$query = mysqli_query($koneksi,$sql);

$total_item = mysqli_num_rows($query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Keranjang Saya</title>
</head>
<body>

    <h2>🛒 Keranjang Saya</h2>

    <a href="index.php">
        ← Kembali
    </a>

    <hr>

    <?php if($total_item>0){ ?>

    <table border="1" cellpadding="10">

        <tr>

            <th>No</th>
            <th>Produk</th>
            <th>Ukuran</th>
            <th>Jumlah</th>
            <th>Addon</th>
            <th>Total</th>
            <th>Aksi</th>

        </tr>

        <?php
        $no=1;
        while($data=mysqli_fetch_assoc($query)){
        ?>

        <tr>
            <td><?= $no++; ?></td>
                <td>
                    <?= $data['nama_produk']; ?>
                </td>
            <td>

            <?= $data['ukuran']; ?>

            </td>

            <td>
                <?= $data['jumlah']; ?>
            </td>

            <td>
                <?php
                if($data['boneka']) echo "Boneka<br>";
                if($data['balon']) echo "Balon<br>";
                if($data['kartu_ucapan']) echo "Kartu Ucapan";
                ?>
            </td>

            <?php

            if($data['ukuran']=="Small"){
                $harga = $data['harga_small'];
            }elseif($data['ukuran']=="Medium"){
                $harga = $data['harga_medium'];
            }else{
                $harga = $data['harga_large'];
            }

            $total = $harga * $data['jumlah'];

            if($data['boneka']) $total += 25000;
            if($data['balon']) $total += 15000;
            if($data['kartu_ucapan']) $total += 5000;

            ?>

            <td>
                Rp <?= number_format($total); ?>
            </td>

            <td>
                <a href="hapus_keranjang.php?id=<?= $data['id_keranjang']; ?>">
                    Hapus
                </a>
                    |
                <a href="checkout.php?id_keranjang=<?= $data['id_keranjang']; ?>">
                    Checkout
                </a>
            </td>

        </tr>

        <?php } ?>

    </table>

    <br><br>

    <a href="kategori.php">
        ← Belanja Lagi
    </a>

    <?php }else{ ?>

    <p>Keranjang masih kosong.</p>

    <?php } ?>

</body>
</html>