<?php
session_start();
include '../config/koneksi.php';

if(!isset($_SESSION['email'])){
    header("Location:../auth/login.php");
    exit();
}

$id = $_GET['id'];

$sql = "SELECT transaksi.*, produk.nama_produk
        FROM transaksi
        JOIN produk
        ON transaksi.id_produk = produk.id_produk
        WHERE transaksi.id_transaksi='$id'";

$query = mysqli_query($koneksi,$sql);
$data = mysqli_fetch_assoc($query);

if(isset($_POST['update'])){

    $status = $_POST['status'];

    if($data['status']=="Selesai"){

        echo "<script>
                alert('Pesanan yang sudah selesai tidak dapat diubah lagi.');
                window.location='transaksi.php';
            </script>";

        exit();

    }

    // Validasi pembatalan
    if($status=="Dibatalkan" && $data['status']!="Menunggu Pembatalan"){

        echo "<script>
                alert('Pesanan ini belum mengajukan pembatalan');
                window.location='transaksi.php';
              </script>";
        exit();

    }

    $update = "UPDATE transaksi
               SET status='$status'
               WHERE id_transaksi='$id'";

    mysqli_query($koneksi,$update);

    // Kembalikan stok jika pembatalan disetujui
    if($status=="Dibatalkan" && $data['status']!="Dibatalkan"){

        if($data['ukuran']=="Small"){

            mysqli_query($koneksi,"
            UPDATE produk
            SET stok_small = stok_small + ".$data['jumlah']."
            WHERE id_produk='".$data['id_produk']."'
            ");

        }elseif($data['ukuran']=="Medium"){

            mysqli_query($koneksi,"
            UPDATE produk
            SET stok_medium = stok_medium + ".$data['jumlah']."
            WHERE id_produk='".$data['id_produk']."'
            ");

        }else{

            mysqli_query($koneksi,"
            UPDATE produk
            SET stok_large = stok_large + ".$data['jumlah']."
            WHERE id_produk='".$data['id_produk']."'
            ");

        }

    }

    echo "<script>
            alert('Status berhasil diperbarui');
            window.location='transaksi.php';
          </script>";
    exit();

}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Status Pesanan</title>
</head>
<body>

    <h2>Update Status Pesanan</h2>
    <a href="transaksi.php">Kembali</a>
    <hr>

    <table border="1" cellpadding="8">
        <tr>
            <td>Nama Pemesan</td>
            <td><?= $data['nama_pemesan']; ?></td>
        </tr>
        <tr>
            <td>Produk</td>
            <td><?= $data['nama_produk']; ?></td>
        </tr>
        <tr>
            <td>Ukuran</td>
            <td><?= $data['ukuran']; ?></td>
        </tr>
        <tr>
            <td>Jumlah</td>
            <td><?= $data['jumlah']; ?></td>
        </tr>
        <tr>
            <td>Total Harga</td>
            <td>
                Rp <?= number_format($data['total_harga'],0,',','.'); ?>
            </td>
        </tr>
        <tr>
            <td>Status Saat Ini</td>
            <td><?= $data['status']; ?></td>
        </tr>
    </table>

    <br>

    <form method="POST">
        <label>Status Baru</label><br><br>

        <select name="status">

            <?php if($data['status']=="Selesai"){ ?>

                <option value="Selesai" selected>
                    Selesai
                </option>

            <?php }elseif($data['status']=="Menunggu Pembatalan"){ ?>

                <option value="Dibatalkan">
                    Setujui Pembatalan
                </option>

                <option value="Pesanan Masuk">
                    Tolak Pembatalan
                </option>

            <?php }else{ ?>

                <option value="Pesanan Masuk"
                <?= ($data['status']=="Pesanan Masuk") ? "selected" : ""; ?>>
                    Pesanan Masuk
                </option>

                <option value="Diproses"
                <?= ($data['status']=="Diproses") ? "selected" : ""; ?>>
                    Diproses
                </option>

                <option value="Selesai"
                <?= ($data['status']=="Selesai") ? "selected" : ""; ?>>
                    Selesai
                </option>

            <?php } ?>

        </select>

        <br><br>

        <button type="submit" name="update">Simpan</button>
        <a href="transaksi.php">Batal</a>

    </form>

</body>
</html>