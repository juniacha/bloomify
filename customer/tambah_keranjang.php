<?php
session_start();
include '../config/koneksi.php';

if(!isset($_SESSION['email'])){
    header("Location:../auth/login.php");
    exit();
}

$sql_produk = "SELECT * FROM produk";
$query_produk = mysqli_query($koneksi,$sql_produk);

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

    $query_harga = mysqli_query($koneksi,$sql_harga);

    $produk = mysqli_fetch_assoc($query_harga);

    if($ukuran=="Small"){

        $harga_produk = $produk['harga_small'];

    }elseif($ukuran=="Medium"){

        $harga_produk = $produk['harga_medium'];

    }else{

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

    $status = "Pesanan Masuk";
    $status = "Pesanan Masuk";
    $sumber = "Offline";
    $sql = "INSERT INTO transaksi
    (
        id_user,
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
        total_harga,
        status,
        sumber
    )
    VALUES
    (
        NULL,
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
        '$total',
        '$status',
        '$sumber'
    )";

    $query = mysqli_query($koneksi,$sql);

    if($query){
                // Update stok produk
                if($ukuran=="Small"){

                    $stok_baru = $produk['stok_small'] - $jumlah;

                    mysqli_query(
                        $koneksi,
                        "UPDATE produk
                        SET stok_small='$stok_baru'
                        WHERE id_produk='$id_produk'"
                    );

                }elseif($ukuran=="Medium"){

                    $stok_baru = $produk['stok_medium'] - $jumlah;

                    mysqli_query(
                        $koneksi,
                        "UPDATE produk
                        SET stok_medium='$stok_baru'
                        WHERE id_produk='$id_produk'"
                    );

                }else{

                    $stok_baru = $produk['stok_large'] - $jumlah;

                    mysqli_query(
                        $koneksi,
                        "UPDATE produk
                        SET stok_large='$stok_baru'
                        WHERE id_produk='$id_produk'"
                    );

                }

        mysqli_query($koneksi,$update);

        echo "<script>
                alert('Pesanan berhasil ditambahkan');
                window.location='transaksi.php';
              </script>";

    }else{

        echo "<script>
                alert('Pesanan gagal disimpan');
              </script>";

    }

}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
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
                    <input
                        type="text"
                        name="nama_pemesan"
                        required>
                </td>
            </tr>

            <tr>
                <td>No HP</td>
                <td>
                    <input
                        type="text"
                        name="no_hp"
                        required>
                </td>
            </tr>

            <tr>
                <td>Produk</td>
                <td>

                    <select name="id_produk" required>

                        <option value="">-- Pilih Produk --</option>

                        <?php

                        mysqli_data_seek($query_produk,0);

                        while($p=mysqli_fetch_assoc($query_produk)){

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

                    <select name="ukuran">

                        <option value="Small">Small</option>

                        <option value="Medium">Medium</option>

                        <option value="Large">Large</option>

                    </select>

                </td>

            </tr>

            <tr>

                <td>Jumlah</td>

                <td>

                    <input
                        type="number"
                        name="jumlah"
                        min="1"
                        value="1"
                        required>

                </td>

            </tr>

            <tr>

                <td>Boneka</td>

                <td>

                    <input
                        type="checkbox"
                        name="boneka"
                        value="1">

                </td>

            </tr>

            <tr>

                <td>Balon</td>

                <td>

                    <input
                        type="checkbox"
                        name="balon"
                        value="1">

                </td>

            </tr>

            <tr>

                <td>Kartu Ucapan</td>

                <td>

                    <input
                        type="checkbox"
                        name="kartu_ucapan"
                        value="1">

                </td>

            </tr>
            <tr>

                <td>Warna Buket</td>

                <td>

                    <input
                        type="text"
                        name="warna_buket"
                        placeholder="Contoh : Pink Putih"
                        required>

                </td>

            </tr>

            <tr>

                <td>Isi Surat</td>

                <td>

                    <textarea
                        name="isi_surat"
                        rows="4"
                        cols="40"></textarea>

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
                    <input
                        type="submit"
                        name="pesan"
                        value="Simpan Pesanan">

                    <a href="transaksi.php">

                        Batal

                    </a>

                </td>

            </tr>

        </table>

    </form>

</body>
</html>