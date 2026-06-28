<?php
session_start();
include '../config/koneksi.php';

if (!isset($_SESSION['email'])) {
    header("Location:../auth/login.php");
    exit();
}

$produk = mysqli_query($koneksi,"SELECT * FROM produk");

if(isset($_POST['pesan'])){

    $nama_pemesan = mysqli_real_escape_string($koneksi,$_POST['nama_pemesan']);
    $no_hp = mysqli_real_escape_string($koneksi,$_POST['no_hp']);
    $id_produk = $_POST['id_produk'];
    $jumlah = $_POST['jumlah'];
    if($jumlah <= 0){
        echo "<script>
                alert('Jumlah harus lebih dari 0');
                history.back();
            </script>";
        exit();
    }
    $ukuran = $_POST['ukuran'];

    $boneka = isset($_POST['boneka']) ? 1 : 0;
    $balon = isset($_POST['balon']) ? 1 : 0;
    $kartu_ucapan = isset($_POST['kartu_ucapan']) ? 1 : 0;

    $warna_buket = mysqli_real_escape_string($koneksi,$_POST['warna_buket']);
    $isi_surat = mysqli_real_escape_string($koneksi,$_POST['isi_surat']);
    $catatan = mysqli_real_escape_string($koneksi,$_POST['catatan']);

    $qProduk = mysqli_query($koneksi,"SELECT * FROM produk WHERE id_produk='$id_produk'");
    $dataProduk = mysqli_fetch_assoc($qProduk);

    if($ukuran=="Small"){
        $harga = $dataProduk['harga_small'];
        $stok  = $dataProduk['stok_small'];
    }elseif($ukuran=="Medium"){
        $harga = $dataProduk['harga_medium'];
        $stok  = $dataProduk['stok_medium'];
    }else{
        $harga = $dataProduk['harga_large'];
        $stok  = $dataProduk['stok_large'];
    }

    if($jumlah > $stok){
        echo "<script>alert('Stok tidak mencukupi');history.back();</script>";
        exit();
    }

    $total = $harga * $jumlah;

    if($boneka){
        $total += 25000;
    }

    if($balon){
        $total += 15000;
    }

    if($kartu_ucapan){
        $total += 5000;
    }

    $status = "Pesanan Masuk";
    $sumber = "Offline";
        $insert = mysqli_query($koneksi,"INSERT INTO transaksi
    (
        id_user,
        nama_pemesan,
        id_produk,
        jumlah,
        ukuran,
        boneka,
        balon,
        kartu_ucapan,
        warna_buket,
        isi_surat,
        catatan,
        total_harga,
        status,
        tanggal,
        no_hp,
        sumber
    )
    VALUES
    (
        NULL,
        '$nama_pemesan',
        '$id_produk',
        '$jumlah',
        '$ukuran',
        '$boneka',
        '$balon',
        '$kartu_ucapan',
        '$warna_buket',
        '$isi_surat',
        '$catatan',
        '$total',
        '$status',
        NOW(),
        '$no_hp',
        '$sumber'
    )");

    if($insert){

        if($ukuran=="Small"){

            $stokBaru = $stok - $jumlah;

            mysqli_query($koneksi,"
                UPDATE produk
                SET stok_small='$stokBaru'
                WHERE id_produk='$id_produk'
            ");

        }elseif($ukuran=="Medium"){

            $stokBaru = $stok - $jumlah;

            mysqli_query($koneksi,"
                UPDATE produk
                SET stok_medium='$stokBaru'
                WHERE id_produk='$id_produk'
            ");

        }else{

            $stokBaru = $stok - $jumlah;

            mysqli_query($koneksi,"
                UPDATE produk
                SET stok_large='$stokBaru'
                WHERE id_produk='$id_produk'
            ");

        }

        echo "<script>
                alert('Pesanan berhasil ditambahkan');
                window.location='transaksi.php';
              </script>";

        exit();

    }else{

        echo "<script>
                alert('Pesanan gagal disimpan');
              </script>";

    }

}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Pesanan Offline</title>
</head>
<body>

    <h2>Tambah Pesanan Offline</h2>

    <a href="transaksi.php">Kembali</a>

    <hr>

    <form method="POST">

        <table>
            <tr>
                <td>Nama Pemesan</td>
                <td>
                    <input type="text" name="nama_pemesan" required>
                </td>
            </tr>

            <tr>
                <td>No HP</td>
                <td>
                    <input type="tel" name="no_hp" required>
                </td>
            </tr>

            <tr>
                <td>Produk</td>
                <td>
                    <select name="id_produk" required>
                        <option value="">-- Pilih Produk --</option>

                        <?php
                        mysqli_data_seek($produk,0);
                        while($p=mysqli_fetch_assoc($produk)){
                        ?>

                        <option value="<?php echo $p['id_produk']; ?>">
                            <?php echo $p['nama_produk']; ?>
                        </option>

                        <?php } ?>

                    </select>
                </td>
            </tr>

            <tr>
                <td>Ukuran</td>
                <td>
                    <select name="ukuran" required>
                        <option value="Small">Small</option>
                        <option value="Medium">Medium</option>
                        <option value="Large">Large</option>
                    </select>
                </td>
            </tr>

            <tr>
                <td>Jumlah</td>
                <td>
                    <input type="number" name="jumlah" value="1" min="1" required>
                </td>
            </tr>

            <tr>
                <td>Boneka (+25000)</td>
                <td>
                    <input type="checkbox" name="boneka">
                </td>
            </tr>

            <tr>
                <td>Balon (+15000)</td>
                <td>
                    <input type="checkbox" name="balon">
                </td>
            </tr>

            <tr>
                <td>Kartu Ucapan (+5000)</td>
                <td>
                    <input type="checkbox" name="kartu_ucapan">
                </td>
            </tr>

            <tr>
                <td>Warna Buket</td>
                <td>
                    <input type="text" name="warna_buket">
                </td>
            </tr>

            <tr>
                <td>Isi Surat</td>
                <td>
                    <textarea name="isi_surat" rows="4" cols="40"></textarea>
                </td>
            </tr>

            <tr>
                <td>Catatan</td>
                <td>
                    <textarea name="catatan" rows="4" cols="40"></textarea>
                </td>
            </tr>

            <tr>
                <td></td>
                <td>
                    <input type="submit" name="pesan" value="Simpan">

                    <input type="reset" value="Reset">

                    <a href="transaksi.php">Kembali</a>
                </td>
            </tr>

        </table>

    </form>

</body>
</html>