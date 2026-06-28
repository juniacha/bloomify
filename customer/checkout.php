<?php
session_start();
include '../config/koneksi.php';

if(!isset($_SESSION['email'])){
    header("Location:../auth/login.php");
    exit();
}

//USER

$email = $_SESSION['email'];

$query_user = mysqli_query($koneksi,"
SELECT *
FROM users
WHERE email='$email'
");

$user = mysqli_fetch_assoc($query_user);
if(!$user){
    session_destroy();
    header("Location:../auth/login.php");
    exit();
}

//DATA DARI FORM

if(isset($_POST['id_produk']) && !isset($_POST['buat_pesanan'])){

    $id_produk = $_POST['id_produk'];
    $ukuran = $_POST['ukuran'];
    $jumlah = $_POST['jumlah'];

    $boneka = isset($_POST['boneka']) ? 1 : 0;
    $balon = isset($_POST['balon']) ? 1 : 0;
    $kartu_ucapan = isset($_POST['kartu_ucapan']) ? 1 : 0;

    $warna_buket = trim($_POST['warna_buket']);
    $isi_surat = trim($_POST['isi_surat']);
    $catatan = trim($_POST['catatan']);

    $id_keranjang = null;
}

//DATA DARI KERANJANG

elseif(isset($_GET['id_keranjang'])){

    $id_keranjang = $_GET['id_keranjang'];

    $sql = "SELECT *
            FROM keranjang
            WHERE id_keranjang='$id_keranjang'
            AND id_user='".$user['id_user']."'";

    $query = mysqli_query($koneksi,$sql);

    $keranjang = mysqli_fetch_assoc($query);

    if(!$keranjang){
        die("Data keranjang tidak ditemukan");
    }

    $id_produk = $keranjang['id_produk'];
    $ukuran = $keranjang['ukuran'];
    $jumlah = $keranjang['jumlah'];

    $boneka = $keranjang['boneka'];
    $balon = $keranjang['balon'];
    $kartu_ucapan = $keranjang['kartu_ucapan'];

    $warna_buket = $keranjang['warna_buket'];
    $isi_surat = $keranjang['isi_surat'];
    $catatan = $keranjang['catatan'];
}

else{

    header("Location:index.php");
    exit();

}

//AMBIL PRODUK

$sql_produk = "SELECT produk.*, kategori.nama_kategori
               FROM produk
               JOIN kategori
               ON produk.id_kategori = kategori.id_kategori
               WHERE produk.id_produk='$id_produk'";

$query_produk = mysqli_query($koneksi,$sql_produk);

$produk = mysqli_fetch_assoc($query_produk);

if(!$produk){
    die("Produk tidak ditemukan");
}

//HARGA & STOK

if($ukuran=="Small"){

    $harga = $produk['harga_small'];
    $stok = $produk['stok_small'];

}
elseif($ukuran=="Medium"){

    $harga = $produk['harga_medium'];
    $stok = $produk['stok_medium'];

}
else{

    $harga = $produk['harga_large'];
    $stok = $produk['stok_large'];

}

if($stok<=0){

    echo "<script>
    alert('Produk habis');
    history.back();
    </script>";

    exit();

}

if($jumlah>$stok){

    echo "<script>
    alert('Stok tidak mencukupi');
    history.back();
    </script>";

    exit();

}

//TOTAL

$total = $harga * $jumlah;

if($boneka) $total += 25000;
if($balon) $total += 15000;
if($kartu_ucapan) $total += 5000;

?>

<!DOCTYPE html>
<html>
<head>
    <title>Checkout</title>
</head>
<body>

<h2>Ringkasan Checkout</h2>

<a href="javascript:history.back()">
    ← Kembali
</a>

<hr>

<?php if(!empty($produk['gambar'])){ ?>
    <img src="../images/<?= $produk['gambar']; ?>" width="220">
    <br><br>
<?php } ?>

<h3><?= $produk['nama_produk']; ?></h3>

<p>
    <b>Kategori :</b>
    <?= $produk['nama_kategori']; ?>
</p>

<p>
    <b>Ukuran :</b>
    <?= $ukuran; ?>
</p>

<p>
    <b>Jumlah :</b>
    <?= $jumlah; ?>
</p>

<p>
    <b>Harga Satuan :</b>
    Rp <?= number_format($harga); ?>
</p>

<p>
    <b>Boneka :</b>
    <?= $boneka ? "Ya (+Rp25.000)" : "Tidak"; ?>
</p>

<p>
    <b>Balon :</b>
    <?= $balon ? "Ya (+Rp15.000)" : "Tidak"; ?>
</p>

<p>
    <b>Kartu Ucapan :</b>
    <?= $kartu_ucapan ? "Ya (+Rp5.000)" : "Tidak"; ?>
</p>

<p>
    <b>Warna Buket :</b>
    <?= !empty($warna_buket) ? htmlspecialchars($warna_buket) : "-"; ?>
</p>

<p>
    <b>Isi Surat :</b><br>
    <?= !empty($isi_surat) ? nl2br(htmlspecialchars($isi_surat)) : "-"; ?>
</p>

<p>
    <b>Catatan :</b><br>
    <?= !empty($catatan) ? nl2br(htmlspecialchars($catatan)) : "-"; ?>
</p>

<hr>

<h2>
    Total Bayar
</h2>

<h1>
    Rp <?= number_format($total); ?>
</h1>

<hr>

<form method="POST">

    <input type="hidden" name="buat_pesanan" value="1">

    <input type="hidden" name="id_produk" value="<?= $id_produk; ?>">
    <input type="hidden" name="ukuran" value="<?= $ukuran; ?>">
    <input type="hidden" name="jumlah" value="<?= $jumlah; ?>">

    <input type="hidden" name="boneka" value="<?= $boneka; ?>">
    <input type="hidden" name="balon" value="<?= $balon; ?>">
    <input type="hidden" name="kartu_ucapan" value="<?= $kartu_ucapan; ?>">

    <input type="hidden" name="warna_buket" value="<?= htmlspecialchars($warna_buket); ?>">
    <input type="hidden" name="isi_surat" value="<?= htmlspecialchars($isi_surat); ?>">
    <input type="hidden" name="catatan" value="<?= htmlspecialchars($catatan); ?>">

    <?php if($id_keranjang != null){ ?>
        <input type="hidden"
               name="id_keranjang"
               value="<?= $id_keranjang; ?>">
    <?php } ?>

    <button type="submit">
        Buat Pesanan
    </button>

</form>

    <?php

    if(isset($_POST['buat_pesanan'])){

        // Ambil kembali data dari hidden input
        $id_produk = $_POST['id_produk'];
        $ukuran = $_POST['ukuran'];
        $jumlah = $_POST['jumlah'];

        $boneka = $_POST['boneka'];
        $balon = $_POST['balon'];
        $kartu_ucapan = $_POST['kartu_ucapan'];

        $warna_buket = $_POST['warna_buket'];
        $isi_surat = $_POST['isi_surat'];
        $catatan = $_POST['catatan'];

        $id_keranjang = isset($_POST['id_keranjang'])
                        ? $_POST['id_keranjang']
                        : null;

        // Ambil ulang data produk
        $sql_produk = "SELECT *
                    FROM produk
                    WHERE id_produk='$id_produk'";

        $query_produk = mysqli_query($koneksi,$sql_produk);
        $produk = mysqli_fetch_assoc($query_produk);

        // Tentukan harga & stok
        if($ukuran=="Small"){

            $harga = $produk['harga_small'];
            $stok = $produk['stok_small'];

        }elseif($ukuran=="Medium"){

            $harga = $produk['harga_medium'];
            $stok = $produk['stok_medium'];

        }else{

            $harga = $produk['harga_large'];
            $stok = $produk['stok_large'];

        }

        // Cek stok lagi
        if($jumlah > $stok){

            echo "<script>
            alert('Stok sudah berubah dan tidak mencukupi.');
            history.back();
            </script>";
            exit();

        }

        // Hitung total
        $total = $harga * $jumlah;

        if($boneka) $total += 25000;
        if($balon) $total += 15000;
        if($kartu_ucapan) $total += 5000;

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
            status,
            sumber
        )
        VALUES
        (
            '".$user['id_user']."',
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
            'Pesanan Masuk',
            'Online'
        )";

        $query = mysqli_query($koneksi,$sql);

        if($query){

            // Kurangi stok
            if($ukuran=="Small"){

                $stok_baru = max(0,$produk['stok_small']-$jumlah);

                mysqli_query($koneksi,"
                UPDATE produk
                SET stok_small='$stok_baru'
                WHERE id_produk='$id_produk'
                ");

            }elseif($ukuran=="Medium"){

                $stok_baru = max(0,$produk['stok_medium']-$jumlah);

                mysqli_query($koneksi,"
                UPDATE produk
                SET stok_medium='$stok_baru'
                WHERE id_produk='$id_produk'
                ");

            }else{

                $stok_baru = max(0,$produk['stok_large']-$jumlah);

                mysqli_query($koneksi,"
                UPDATE produk
                SET stok_large='$stok_baru'
                WHERE id_produk='$id_produk'
                ");

            }

            // Jika checkout dari keranjang, hapus itemnya
            if($id_keranjang){

                mysqli_query($koneksi,"
                DELETE FROM keranjang
                WHERE id_keranjang='$id_keranjang'
                ");

            }

            echo "<script>
            alert('Pesanan berhasil dibuat!');
            window.location='pesanan_saya.php';
            </script>";

            exit();

        }else{

            echo "Gagal menyimpan transaksi : ".mysqli_error($koneksi);

        }

    }
    ?>
</body>
</html>