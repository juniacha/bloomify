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
<html lang="id">

<head>

<meta charset="UTF-8">

    <meta name="viewport"
    content="width=device-width, initial-scale=1">

    <title>Checkout | Bloomify</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet"
    href="../assets/css/style.css">

    <link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600;700&family=Poppins:wght@300;400;500;600&display=swap"
    rel="stylesheet">

</head>

<body>

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg sticky-top">

        <div class="container">
            <a class="navbar-brand"
            href="index.php">
            <i class="bi bi-flower1 me-2"></i>
            Bloomify
            </a>

            <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarBloomify">

            <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse"
                id="navbarBloomify">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link"
                        href="index.php">
                        Home
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link"
                        href="produk.php">
                        Produk
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link"
                        href="pesanan_saya.php">
                        Pesanan Saya
                        </a>
                    </li>

                </ul>

                <div class="d-flex align-items-center gap-3">
                    <a
                    href="keranjang.php"
                    class="nav-cart">
                        <i class="bi bi-bag"></i>
                    </a>

                    <span>
                        Halo,
                        <strong>
                        <?= $_SESSION['nama']; ?>
                        </strong>
                    </span>

                    <a href="../auth/logout.php" class="btn btn-bloom">
                        Logout
                    </a>

                </div>

            </div>

        </div>

    </nav>

    <!-- HEADER -->

    <section
    class="py-5"
    style="background:var(--section);">

        <div class="container text-center">

            <a
            href="javascript:history.back()"
            class="back-link">
            <i class="bi bi-arrow-left me-2"></i>
                Kembali
            </a>

            <span class="section-subtitle d-block mt-4">CHECKOUT</span>

            <h1 class="mt-2">Ringkasan Pesanan</h1>
            
            <p class="text-secondary">Periksa kembali pesanan sebelum melakukan checkout.</p>

        </div>

    </section>

    <div class="container py-5">

        <div class="checkout-card">

            <div class="row g-5 align-items-start">

                <!-- FOTO -->
                <div class="col-lg-5">
                    <img
                    src="../assets/img/<?= $produk['gambar']; ?>"
                    class="checkout-image"
                    alt="<?= $produk['nama_produk']; ?>">
                </div>

                <!-- DETAIL -->

                <div class="col-lg-7">
                    <span class="badge category-badge">
                        <?= $produk['nama_kategori']; ?>
                    </span>

                    <h2 class="mt-3">
                        <?= $produk['nama_produk']; ?>
                    </h2>

                    <p class="text-secondary">Bouquet pilihan terbaik untuk momen spesialmu.</p>

                    <hr>
                    <div class="checkout-info">

                        <div>
                            <span>Ukuran</span>
                            <strong><?= $ukuran; ?></strong>
                        </div>

                        <div>
                            <span>Jumlah</span>
                            <strong><?= $jumlah; ?></strong>
                        </div>

                        <div>
                            <span>Harga Satuan</span>
                            <strong>
                                Rp <?= number_format($harga,0,",","."); ?>
                            </strong>
                        </div>

                    </div>

                    <hr>

                    <h5 class="mb-3">Custom Bouquet</h5>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="checkout-option">
                                🧸 Boneka
                                <br>
                                <strong>
                                <?= $boneka ? "Ya" : "Tidak"; ?>
                                </strong>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="checkout-option">
                                🎈 Balon
                                <br>
                                <strong>
                                <?= $balon ? "Ya" : "Tidak"; ?>
                                </strong>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="checkout-option">
                                💌 Kartu
                                <br>
                                <strong>
                                <?= $kartu_ucapan ? "Ya" : "Tidak"; ?>
                                </strong>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <label class="form-label">
                            Warna Buket
                        </label>

                        <div class="checkout-box">
                            <?= $warna_buket ?: "-"; ?>
                        </div>
                    </div>

                    <div class="mt-3">
                        <label class="form-label">
                            Isi Surat
                        </label>

                        <div class="checkout-box">
                            <?= $isi_surat ?: "-"; ?>
                        </div>
                    </div>

                    <div class="mt-3">
                        <label class="form-label">
                            Catatan
                        </label>

                        <div class="checkout-box">
                            <?= $catatan ?: "-"; ?>
                        </div>
                    </div>

            </div>

        </div>

    </div>

<hr class="my-5">

<form method="POST">

    <div class="checkout-card mt-4">

        <h4 class="mb-4">

            <i class="bi bi-geo-alt me-2"></i>

            Informasi Pengiriman

        </h4>

        <div class="row g-3">

            <div class="col-md-6">

                <label class="form-label">

                    No. HP

                </label>

                <input
                type="text"
                class="form-control"
                name="no_hp"
                value="<?= $user['no_hp']; ?>"
                required>

            </div>

            <div class="col-md-6">

                <label class="form-label">

                    Metode Pengiriman

                </label>

                <select
                class="form-select"
                name="metode_pengiriman">

                    <option value="Delivery">

                        Delivery

                    </option>

                    <option value="Ambil di Toko">

                        Ambil di Toko

                    </option>

                </select>

            </div>

            <div class="col-12">

                <label class="form-label">

                    Alamat Lengkap

                </label>

                <textarea
                class="form-control"
                rows="4"
                name="alamat"
                placeholder="Masukkan alamat lengkap..."
                required><?= isset($user['alamat']) ? $user['alamat'] : ""; ?></textarea>

            </div>

            <div class="col-12">

                <label class="form-label">

                    Metode Pembayaran

                </label>

                <div class="d-flex gap-4 mt-2">

                    <div class="form-check">

                        <input
                        class="form-check-input"
                        type="radio"
                        name="metode_pembayaran"
                        value="Transfer Bank"
                        checked>

                        <label class="form-check-label">

                            Transfer Bank

                        </label>

                    </div>

                    <div class="form-check">

                        <input
                        class="form-check-input"
                        type="radio"
                        name="metode_pembayaran"
                        value="COD">

                        <label class="form-check-label">

                            COD

                        </label>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <div class="checkout-summary">

    <h3 class="mb-4">

        Ringkasan Pembayaran

    </h3>

    <div class="summary-row">

        <span>Harga Bouquet</span>

        <span>

            Rp <?= number_format($harga * $jumlah,0,",","."); ?>

        </span>

    </div>

    <?php if($boneka){ ?>

    <div class="summary-row">

        <span>🧸 Boneka</span>

        <span>Rp 25.000</span>

    </div>

    <?php } ?>

    <?php if($balon){ ?>

    <div class="summary-row">

        <span>🎈 Balon</span>

        <span>Rp 15.000</span>

    </div>

    <?php } ?>

    <?php if($kartu_ucapan){ ?>

    <div class="summary-row">

        <span>💌 Kartu Ucapan</span>

        <span>Rp 5.000</span>

    </div>

    <?php } ?>

    <hr>

    <div class="summary-total">

        <span>Total Pembayaran</span>

        <h2>

            Rp <?= number_format($total,0,",","."); ?>

        </h2>

    </div>


        <input
        type="hidden"
        name="buat_pesanan"
        value="1">

        <input
        type="hidden"
        name="id_produk"
        value="<?= $id_produk; ?>">

        <input
        type="hidden"
        name="ukuran"
        value="<?= $ukuran; ?>">

        <input
        type="hidden"
        name="jumlah"
        value="<?= $jumlah; ?>">

        <input
        type="hidden"
        name="boneka"
        value="<?= $boneka; ?>">

        <input
        type="hidden"
        name="balon"
        value="<?= $balon; ?>">

        <input
        type="hidden"
        name="kartu_ucapan"
        value="<?= $kartu_ucapan; ?>">

        <input
        type="hidden"
        name="warna_buket"
        value="<?= htmlspecialchars($warna_buket ?? ""); ?>">

        <input
        type="hidden"
        name="isi_surat"
        value="<?= htmlspecialchars($isi_surat ?? ""); ?>">

        <input
        type="hidden"
        name="catatan"
        value="<?= htmlspecialchars($catatan ?? ""); ?>">

        <?php if($id_keranjang != null){ ?>

        <input
        type="hidden"
        name="id_keranjang"
        value="<?= $id_keranjang; ?>">

        <?php } ?>

        <button
        type="submit"
        class="btn btn-bloom w-100 py-3">

            <i class="bi bi-credit-card me-2"></i>

            Buat Pesanan

        </button>

    </form>

</div>

    <?php

    if(isset($_POST['buat_pesanan'])){

        $no_hp = $_POST['no_hp'];
        $alamat = $_POST['alamat'];
        $metode_pengiriman = $_POST['metode_pengiriman'];
        $metode_pembayaran = $_POST['metode_pembayaran'];

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
        $sql ="INSERT INTO transaksi
        (
            id_user,
            nama_pemesan,
            no_hp,
            alamat,
            metode_pengiriman,
            metode_pembayaran,
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
            '$no_hp',
            '$alamat',
            '$metode_pengiriman',
            '$metode_pembayaran',
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