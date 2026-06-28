<?php
session_start();
include '../config/koneksi.php';

if(!isset($_SESSION['email'])){
    header("Location:../auth/login.php");
    exit();
}

$email = $_SESSION['email'];
$sql_user = "SELECT * FROM users WHERE email='$email'";
$query_user = mysqli_query($koneksi,$sql_user);
$user = mysqli_fetch_assoc($query_user);

if(!isset($_GET['id_produk']) || !isset($_GET['ukuran'])){
    header("Location:kategori.php");
    exit();
}

$id_produk = $_GET['id_produk'];
$ukuran = $_GET['ukuran'];

$sql_produk = "SELECT produk.*, kategori.nama_kategori
               FROM produk
               JOIN kategori
               ON produk.id_kategori = kategori.id_kategori
               WHERE produk.id_produk='$id_produk'";

$query_produk = mysqli_query($koneksi,$sql_produk);
$produk = mysqli_fetch_assoc($query_produk);

if(!$produk){
    echo "Produk tidak ditemukan";
    exit();
}

if($ukuran == "Small"){
    $harga = $produk['harga_small'];
}elseif($ukuran == "Medium"){
    $harga = $produk['harga_medium'];
}else{
    $harga = $produk['harga_large'];
} ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Checkout Pesanan</title>
</head>
<body>
    <h2>Checkout Pesanan</h2>
    <a href="javascript:history.back()">← Kembali</a>
    <hr>
    <h3>Data Pemesan</h3>

    <p>
        <b>Nama :</b>
        <?= $user['nama']; ?>
    </p>

    <p>
        <b>No Hp :</b>
        <?= $user['no_hp']; ?>
    </p>
    <hr>

    <h3>Detail Produk</h3>
    <?php if(!empty($produk['gambar'])){ ?>
        <img src="../images/<?= $produk['gambar']; ?>" width="200">
        <br><br>
    <?php } ?>

    <p>
        <b>Produk :</b>
        <?= $produk['nama_produk']; ?>
    </p>

    <p>
        <b>Kategori :</b>
        <?= $produk['nama_kategori']; ?>
    </p>

    <p>
        <b>Deskripsi :</b>
        <?= $produk['deskripsi']; ?>
    </p>

    <p>
        <b>Ukuran :</b>
        <?= $ukuran; ?>
    </p>

    <p>
        <b>Harga :</b>
        Rp <?= number_format($harga); ?>
    </p>

    <?php
        if($ukuran=="Small"){
            $stok_tampil = $produk['stok_small'];
        }elseif($ukuran=="Medium"){
            $stok_tampil = $produk['stok_medium'];
        }else{
            $stok_tampil = $produk['stok_large'];
        }
        ?>

        <p>
            <b>Stok :</b>
            <?= $stok_tampil; ?>
        </p>
    
    <hr>

    <form action="checkout.php" method="POST">
        <input type="hidden" name="id_produk" value="<?= $produk ['id_produk']; ?>">
        <input type="hidden" name="ukuran" value="<?= $ukuran; ?>">
        Jumlah
        <br>

        <input type="number" name="jumlah" value="1" min="1" max="<?=
        ($ukuran=="Small") ? $produk['stok_small'] :
        (($ukuran=="Medium") ? $produk['stok_medium'] : $produk['stok_large']);
        ?>" required><br><br>

        Boneka (+25.000)
        <input type="checkbox" name="boneka" value="1"> <br><br>
        
        Balon (+15.000)
        <input type="checkbox" name="balon" value="1"><br><br>

        Kartu Ucapan (+5.000)
        <input type="checkbox" name="kartu_ucapan" value="1"><br><br>

        Warna Buket<br>
        <input type="text" name="warna_buket"><br><br>

        Isi Surat<br>
        <textarea name="isi_surat" rows="4" cols="40"></textarea><br><br>

        Catatan<br>
        <textarea name="catatan" rows="4" cols="40"></textarea><br><br>

        <button type="submit"
                formaction="checkout.php">
            ⚡ Checkout Sekarang
        </button>

    </form>

    <?php

    if(isset($_POST['checkout'])){

        $id_produk = $_POST['id_produk'];
        $ukuran = $_POST['ukuran'];
        $jumlah = $_POST['jumlah'];

        $boneka = isset($_POST['boneka']) ? 1 : 0;
        $balon = isset($_POST['balon']) ? 1 : 0;
        $kartu_ucapan = isset($_POST['kartu_ucapan']) ? 1 : 0;

        $warna_buket = $_POST['warna_buket'];
        $isi_surat = $_POST['isi_surat'];
        $catatan = $_POST['catatan'];

        // Ambil data produk terbaru
        $sql_produk = "SELECT * FROM produk
                    WHERE id_produk='$id_produk'";

        $query_produk = mysqli_query($koneksi,$sql_produk);
        $produk = mysqli_fetch_assoc($query_produk);

        // Tentukan harga & stok berdasarkan ukuran
        if($ukuran == "Small"){

            $harga = $produk['harga_small'];
            $stok = $produk['stok_small'];

        }elseif($ukuran == "Medium"){

            $harga = $produk['harga_medium'];
            $stok = $produk['stok_medium'];

        }else{

            $harga = $produk['harga_large'];
            $stok = $produk['stok_large'];

        }

        if($stok <= 0){
            echo "<script>
            alert('Produk habis');
            history.back();
            </script>";
            exit();
        }

        // Validasi stok
        if($jumlah > $stok){

            echo "<script>
                    alert('Stok tidak mencukupi!');
                    history.back();
                </script>";
            exit();

        }

        // Hitung total
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

        // Simpan transaksi
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
            status
        )
        VALUES
        (
            '".$_SESSION['id_user']."',
            '".$user['nama']."',
            '".$user['no_hp']."',
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
            'Pesanan Masuk'
        )";

        $query = mysqli_query($koneksi,$sql);

        if($query){

            // Kurangi stok sesuai ukuran
            if($ukuran == "Small"){

                $stok_baru = max(0, $produk['stok_small'] - $jumlah);

                mysqli_query($koneksi,"
                UPDATE produk
                SET stok_small='$stok_baru'
                WHERE id_produk='$id_produk'
                ");

            }elseif($ukuran == "Medium"){

                $stok_baru = max(0, $produk['stok_medium'] - $jumlah);

                mysqli_query($koneksi,"
                UPDATE produk
                SET stok_medium='$stok_baru'
                WHERE id_produk='$id_produk'
                ");

            }else{

                $stok_baru = max(0, $produk['stok_large'] - $jumlah);

                mysqli_query($koneksi,"
                UPDATE produk
                SET stok_large='$stok_baru'
                WHERE id_produk='$id_produk'
                ");

            }

            echo "<script>
                    alert('Checkout berhasil!');
                    window.location='pesanan_saya.php';
                </script>";

        }else{

            echo "Gagal menyimpan transaksi : ".mysqli_error($koneksi);

        }

    }
    ?>

</body>
</html>