<?php
session_start();
include '../config/koneksi.php';

if(!isset($_SESSION['email'])){
    header("Location:../auth/login.php");
    exit();
}

$sql_produk = "SELECT * FROM produk";
$query_produk = mysqli_query($koneksi, $sql_produk);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Kelola Pesanan</title>
</head>
<body>

    <h2>Kelola Pesanan</h2>

    <a href="dashboard.php">Kembali ke Dashboard</a>

    <hr>

    <h3>Tambah Pesanan</h3>

    <form method="POST">

        Nama Pemesan <br>
        <input type="text" name="nama_pemesan" required>
        <br><br>

        No HP <br>
        <input type="text" name="no_hp" required>
        <br><br>

        Produk <br>
        <select name="id_produk" required>

            <option value="">--- Pilih Produk ---</option>

            <?php while($produk = mysqli_fetch_assoc($query_produk)){ ?>

                <option value="<?= $produk['id_produk']; ?>">
                    <?= $produk['nama_produk']; ?>
                </option>

            <?php } ?>

        </select>

        <br><br>

        Jumlah <br>
        <input type="number" name="jumlah" value="1" required>

        <br><br>

        Ukuran <br>
        <select name="ukuran">
            <option value="Small">Small</option>
            <option value="Medium">Medium</option>
            <option value="Large">Large</option>
        </select>

        <br><br>

        Boneka (+25000)
        <input type="checkbox" name="boneka" value="1">

        <br><br>

        Balon (+15000)
        <input type="checkbox" name="balon" value="1">

        <br><br>

        Kartu Ucapan (+5000)
        <input type="checkbox" name="kartu_ucapan" value="1">

        <br><br>

        Warna Buket <br>
        <input type="text" name="warna_buket">

        <br><br>

        Isi Surat <br>
        <textarea name="isi_surat"></textarea>

        <br><br>

        Catatan <br>
        <textarea name="catatan"></textarea>

        <br><br>

        <button type="submit" name="pesan">
            Simpan Pesanan
        </button>


    </form>

    <?php

    if(isset($_POST['pesan'])){

        $nama_pemesan = $_POST['nama_pemesan'];
        $no_hp = $_POST['no_hp'];
        $id_produk = $_POST['id_produk'];
        $jumlah = $_POST['jumlah'];
        $ukuran = $_POST['ukuran'];

        $boneka = isset($_POST['boneka']) ? 1 : 0;
        $balon = isset($_POST['balon']) ? 1 : 0;
        $kartu_ucapan = isset($_POST['kartu_ucapan']) ? 1 : 0;

        $warna_buket = $_POST['warna_buket'];
        $isi_surat = $_POST['isi_surat'];
        $catatan = $_POST['catatan'];

        $sql_harga = "SELECT * FROM produk
                    WHERE id_produk='$id_produk'";

        $query_harga = mysqli_query($koneksi, $sql_harga);

        $produk = mysqli_fetch_assoc($query_harga);

        if($ukuran == 'Small'){
            $harga_produk = $produk['harga_small'];
        }
        elseif($ukuran == 'Medium'){
            $harga_produk = $produk['harga_medium'];
        }
        else{
            $harga_produk = $produk['harga_large'];
        }

        $total = $harga_produk * $jumlah;

        if($boneka){
            $total += 25000;
        }

        if($balon){
            $total += 15000;
        }

        if($kartu_ucapan){
            $total += 5000;
        }

        $sql = "INSERT INTO transaksi
                (
                    nama_pemesan,
                    no_hp,
                    id_produk,
                    jumlah,
                    ukuran,
                    boneka,
                    balon,
                    kartu_ucapan,
                    warna_buket,
                    isi_surat,
                    catatan,
                    total_harga
                )
                VALUES
                (
                    '$nama_pemesan',
                    '$no_hp',
                    '$id_produk',
                    '$jumlah',
                    '$ukuran',
                    '$boneka',
                    '$balon',
                    '$kartu_ucapan',
                    '$warna_buket',
                    '$isi_surat',
                    '$catatan',
                    '$total'
                )";

        $query = mysqli_query($koneksi, $sql);

        if($query){
            echo "
            <script>
                alert('Pesanan berhasil ditambahkan');
                window.location='transaksi.php';
            </script>
            ";
        }else{
            echo 'Gagal menyimpan pesanan';
        }
    }
    ?>

    <hr>

    <h3>Daftar Pesanan</h3>

    <table border="1" cellpadding="10">

    <tr>
        <th>ID</th>
        <th>Pemesan</th>
        <th>Produk</th>
        <th>Total</th>
        <th>Status</th>
        <th>Aksi</th>
    </tr>

    <?php

    $sql = "SELECT *
            FROM transaksi
            JOIN produk
            ON transaksi.id_produk = produk.id_produk
            ORDER BY id_transaksi DESC";

    $query = mysqli_query($koneksi, $sql);

    while($data = mysqli_fetch_assoc($query)){
    ?>

    <tr>
        <td><?= $data['id_transaksi']; ?></td>
        <td><?= $data['nama_pemesan']; ?></td>
        <td><?= $data['nama_produk']; ?></td>
        <td>Rp <?= number_format($data['total_harga']); ?></td>
        <td><?= $data['status']; ?></td>
        <td>

        <?php if($data['status'] != 'Selesai'){ ?>

        <a href="edit_status.php?id=<?= $data['id_transaksi']; ?>">
            Update
        </a>

        |

        <?php } ?>

        <a href="detail_pesanan.php?id=<?= $data['id_transaksi']; ?>">
            Detail
        </a>

        </td>
    </tr>

    <?php } ?>

    </table>

</body>
</html>
