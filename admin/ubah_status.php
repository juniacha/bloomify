<?php
session_start();
include '../config/koneksi.php';

if(!isset($_SESSION['email'])){
    header("Location:../auth/login.php");
    exit();
}

$id = $_GET['id'];

$data = mysqli_fetch_assoc(
    mysqli_query($koneksi,"
        SELECT transaksi.*,produk.nama_produk
        FROM transaksi
        JOIN produk
        ON transaksi.id_produk=produk.id_produk
        WHERE id_transaksi='$id'
    ")
);

if(isset($_POST['simpan'])){

    $status = $_POST['status'];

    if($status=="Dibatalkan" && $data['status']!="Menunggu Pembatalan"){

        echo "<script>
                alert('Pesanan ini belum mengajukan pembatalan');
                window.location='transaksi.php';
            </script>";

        exit();

    }

    mysqli_query($koneksi,"
        UPDATE transaksi
        SET status='$status'
        WHERE id_transaksi='$id'
    ");

    if($status=="Dibatalkan"){

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

    alert('Status berhasil diubah');

    window.location='transaksi.php';

    </script>";

}
?>

<!DOCTYPE html>
<html>
<head>
<title>Edit Status</title>
</head>

<body>

    <h2>Edit Status Pesanan</h2>

    <a href="transaksi.php">Kembali</a>

    <hr>

    <form method="POST">
        <table>
            <tr>
                <td>Nama Pemesan</td>
                <td><?php echo $data['nama_pemesan']; ?></td>
            </tr>
            <tr>
                <td>Produk</td>
                <td><?php echo $data['nama_produk']; ?></td>
            </tr>
            <tr>
                <td>Jumlah</td>
                <td><?php echo $data['jumlah']; ?></td>
            </tr>
            <tr>
                <td>Status</td>
                <td>
                    <select name="status">

                        <?php if($data['status']=="Menunggu Pembatalan"){ ?>

                            <option value="Dibatalkan">
                                Setujui Pembatalan
                            </option>

                            <option value="Pesanan Masuk">
                                Tolak Pembatalan
                            </option>

                        <?php }else{ ?>

                            <option value="Pesanan Masuk"
                            <?= $data['status']=="Pesanan Masuk" ? "selected" : "" ?>>
                                Pesanan Masuk
                            </option>

                            <option value="Diproses"
                            <?= $data['status']=="Diproses" ? "selected" : "" ?>>
                                Diproses
                            </option>

                            <option value="Selesai"
                            <?= $data['status']=="Selesai" ? "selected" : "" ?>>
                                Selesai
                            </option>

                        <?php } ?>

                    </select>
                </td>
            </tr>
            <tr>
                <td>
                <input type="submit" name="simpan"value="Simpan">
                <a href="transaksi.php">Batal</a>
                </td>
            </tr>
         </table>
    </form>
</body>
</html>