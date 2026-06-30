<?php
session_start();
include '../config/koneksi.php';

if(!isset($_SESSION['email'])){
    header("Location:../auth/login.php");
    exit();
}

if($_SESSION['role'] != "admin"){
    header("Location:../customer/index.php");
    exit();
}

$produk = mysqli_query($koneksi,"
SELECT *
FROM produk
ORDER BY nama_produk ASC
");

if(isset($_POST['pesan'])){

    $nama_pemesan = mysqli_real_escape_string($koneksi,$_POST['nama_pemesan']);

    $no_hp = mysqli_real_escape_string($koneksi,$_POST['no_hp']);

    $alamat = mysqli_real_escape_string($koneksi,$_POST['alamat']);

    $metode_pengiriman = $_POST['metode_pengiriman'];

    $id_produk = $_POST['id_produk'];

    $jumlah = (int)$_POST['jumlah'];

    $ukuran = $_POST['ukuran'];

    $boneka = isset($_POST['boneka']) ? 1 : 0;

    $balon = isset($_POST['balon']) ? 1 : 0;

    $kartu_ucapan = isset($_POST['kartu_ucapan']) ? 1 : 0;

    $warna_buket = mysqli_real_escape_string($koneksi,$_POST['warna_buket']);

    $isi_surat = mysqli_real_escape_string($koneksi,$_POST['isi_surat']);

    $catatan = mysqli_real_escape_string($koneksi,$_POST['catatan']);

    if($jumlah < 1){

        echo "<script>
        alert('Jumlah minimal 1');
        history.back();
        </script>";

        exit();

    }

    if($metode_pengiriman=="Delivery" && empty($alamat)){

        echo "<script>
        alert('Alamat wajib diisi jika Delivery');
        history.back();
        </script>";

        exit();

    }

    $qProduk = mysqli_query($koneksi,"
    SELECT *
    FROM produk
    WHERE id_produk='$id_produk'
    ");

    $dataProduk = mysqli_fetch_assoc($qProduk);

    if($ukuran=="Small"){

        $harga = $dataProduk['harga_small'];
        $stok = $dataProduk['stok_small'];

    }elseif($ukuran=="Medium"){

        $harga = $dataProduk['harga_medium'];
        $stok = $dataProduk['stok_medium'];

    }else{

        $harga = $dataProduk['harga_large'];
        $stok = $dataProduk['stok_large'];

    }

    if($jumlah > $stok){

        echo "<script>
        alert('Stok tidak mencukupi');
        history.back();
        </script>";

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

    // Ongkir

    if($metode_pengiriman=="Delivery"){

        $total += 20000;

    }

    $status = "Pesanan Masuk";

    $sumber = "Offline";

    $insert = mysqli_query($koneksi,"
    INSERT INTO transaksi
    (
        id_user,
        nama_pemesan,
        no_hp,
        alamat,
        metode_pengiriman,
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
        sumber
    )
    VALUES
    (
        NULL,
        '$nama_pemesan',
        '$no_hp',
        '$alamat',
        '$metode_pengiriman',
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
        '$sumber'
    )
    ");

    if($insert){

        $stokBaru = $stok - $jumlah;

        if($ukuran=="Small"){

            mysqli_query($koneksi,"
            UPDATE produk
            SET stok_small='$stokBaru'
            WHERE id_produk='$id_produk'
            ");

        }elseif($ukuran=="Medium"){

            mysqli_query($koneksi,"
            UPDATE produk
            SET stok_medium='$stokBaru'
            WHERE id_produk='$id_produk'
            ");

        }else{

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
<html lang="id">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>Tambah Pesanan Offline | Bloomify</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet"
href="../assets/css/style.css">

<link rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600;700&family=Poppins:wght@300;400;500;600&display=swap"
rel="stylesheet">

</head>

<body class="admin-bg">

<div class="admin-wrapper">

<!-- =========================
SIDEBAR
========================= -->

<aside class="sidebar">

<div class="logo-area">

<h2>Bloomify</h2>

<p>Florist Management</p>

</div>

<span class="menu-text">

MAIN MENU

</span>

<nav>

<a href="dashboard.php">

<i class="bi bi-grid"></i>

Dashboard

</a>

<a href="produk.php">

<i class="bi bi-box-seam"></i>

Produk

</a>

<a href="kategori.php">

<i class="bi bi-tags"></i>

Kategori

</a>

<a href="transaksi.php" class="active">

<i class="bi bi-bag-heart"></i>

Pesanan

</a>

<a href="laporan.php">

<i class="bi bi-bar-chart"></i>

Laporan

</a>

<a href="../auth/logout.php">

<i class="bi bi-box-arrow-right"></i>

Logout

</a>

</nav>

</aside>

<!-- =========================
CONTENT
========================= -->

<main class="content">

<div class="topbar">

<div>

<h2>Tambah Pesanan Offline</h2>

<p>

Input pesanan pelanggan yang datang langsung ke toko.

</p>

</div>

<a
href="transaksi.php"
class="btn btn-outline-bloom">

<i class="bi bi-arrow-left me-2"></i>

Kembali

</a>

</div>

<form method="POST">

    <!-- =========================
    INFORMASI PEMESAN
    ========================= -->

    <div class="form-admin-card mb-4">

        <h4 class="form-title">

            <i class="bi bi-person-circle me-2"></i>

            Informasi Pemesan

        </h4>

        <div class="row">

            <div class="col-md-6 mb-3">

                <label class="form-label">

                    Nama Pemesan

                </label>

                <input
                type="text"
                name="nama_pemesan"
                class="form-control"
                placeholder="Masukkan nama customer"
                required>

            </div>

            <div class="col-md-6 mb-3">

                <label class="form-label">

                    No. HP

                </label>

                <input
                type="text"
                name="no_hp"
                class="form-control"
                placeholder="08xxxxxxxxxx"
                required>

            </div>

        </div>

        <div class="row">

            <div class="col-md-6 mb-3">

                <label class="form-label">

                    Metode Pengiriman

                </label>

                <select
                name="metode_pengiriman"
                id="metode_pengiriman"
                class="form-select"
                required>

                    <option value="Ambil di Toko">

                        Ambil di Toko

                    </option>

                    <option value="Delivery">

                        Delivery (+Rp20.000)

                    </option>

                </select>

            </div>

            <div class="col-md-6 mb-3">

                <label class="form-label">

                    Alamat Pengiriman

                </label>

                <textarea
                name="alamat"
                id="alamat"
                class="form-control"
                rows="2"
                placeholder="Isi alamat jika memilih Delivery"
                disabled></textarea>

            </div>

        </div>

    </div>

    <!-- =========================
    DETAIL PESANAN
    ========================= -->

    <div class="form-admin-card mb-4">

        <h4 class="form-title">

            <i class="bi bi-box-seam me-2"></i>

            Detail Pesanan

        </h4>

        <div class="row">

            <div class="col-lg-6 mb-3">

                <label class="form-label">

                    Produk Bouquet

                </label>

                <select
                name="id_produk"
                class="form-select"
                required>

                    <option value="">

                        -- Pilih Produk --

                    </option>

                    <?php
                    mysqli_data_seek($produk,0);

                    while($p=mysqli_fetch_assoc($produk)){
                    ?>

                    <option value="<?= $p['id_produk']; ?>">

                        <?= $p['nama_produk']; ?>

                    </option>

                    <?php } ?>

                </select>

            </div>

            <div class="col-lg-3 mb-3">

                <label class="form-label">

                    Ukuran

                </label>

                <select
                name="ukuran"
                class="form-select"
                required>

                    <option value="Small">

                        Small

                    </option>

                    <option value="Medium">

                        Medium

                    </option>

                    <option value="Large">

                        Large

                    </option>

                </select>

            </div>

            <div class="col-lg-3 mb-3">

                <label class="form-label">

                    Jumlah

                </label>

                <input
                type="number"
                name="jumlah"
                class="form-control"
                value="1"
                min="1"
                required>

            </div>

        </div>

    </div>

    <!-- =========================
    ADD ON & CUSTOM BOUQUET
    ========================= -->

    <div class="row">

        <!-- ADD ON -->

        <div class="col-lg-5">

            <div class="form-admin-card mb-4">

                <h4 class="form-title">

                    <i class="bi bi-gift-fill me-2"></i>

                    Add On

                </h4>

                <div class="form-check mb-3">

                    <input
                    class="form-check-input"
                    type="checkbox"
                    id="boneka"
                    name="boneka">

                    <label
                    class="form-check-label"
                    for="boneka">

                        Boneka Teddy
                        <span class="text-muted">
                            (+Rp25.000)
                        </span>

                    </label>

                </div>

                <div class="form-check mb-3">

                    <input
                    class="form-check-input"
                    type="checkbox"
                    id="balon"
                    name="balon">

                    <label
                    class="form-check-label"
                    for="balon">

                        Balon
                        <span class="text-muted">
                            (+Rp15.000)
                        </span>

                    </label>

                </div>

                <div class="form-check">

                    <input
                    class="form-check-input"
                    type="checkbox"
                    id="kartu_ucapan"
                    name="kartu_ucapan">

                    <label
                    class="form-check-label"
                    for="kartu_ucapan">

                        Kartu Ucapan
                        <span class="text-muted">
                            (+Rp5.000)
                        </span>

                    </label>

                </div>

            </div>

        </div>

        <!-- CUSTOM -->

        <div class="col-lg-7">

            <div class="form-admin-card mb-4">

                <h4 class="form-title">

                    <i class="bi bi-palette-fill me-2"></i>

                    Custom Bouquet

                </h4>

                <div class="mb-3">

                    <label class="form-label">

                        Warna Buket

                    </label>

                    <input
                    type="text"
                    name="warna_buket"
                    class="form-control"
                    placeholder="Contoh : Pink, White, Pastel">

                </div>

                <div class="mb-3">

                    <label class="form-label">

                        Isi Surat / Kartu

                    </label>

                    <textarea
                    name="isi_surat"
                    class="form-control"
                    rows="4"
                    placeholder="Tuliskan pesan untuk penerima..."></textarea>

                </div>

                <div>

                    <label class="form-label">

                        Catatan Tambahan

                    </label>

                    <textarea
                    name="catatan"
                    class="form-control"
                    rows="3"
                    placeholder="Contoh : Tolong kirim sebelum jam 5 sore"></textarea>

                </div>

            </div>

        </div>

    </div>

    <!-- =========================
    ACTION BUTTON
    ========================= -->

    <div class="form-admin-card">

        <div class="d-flex justify-content-end gap-3 flex-wrap">

            <a
            href="transaksi.php"
            class="btn btn-light">

                <i class="bi bi-arrow-left me-2"></i>

                Kembali

            </a>

            <button
            type="reset"
            class="btn btn-outline-bloom">

                <i class="bi bi-arrow-clockwise me-2"></i>

                Reset

            </button>

            <button
            type="submit"
            name="pesan"
            class="btn btn-bloom">

                <i class="bi bi-check-circle me-2"></i>

                Simpan Pesanan

            </button>

        </div>

    </div>

    </form>

    </main>

    </div>

    <!-- =========================
    JAVASCRIPT
    ========================= -->

    <script>

    const metode = document.getElementById("metode_pengiriman");

    const alamat = document.getElementById("alamat");

    // Kondisi awal
    if(metode.value=="Delivery"){

        alamat.disabled = false;

        alamat.required = true;

    }else{

        alamat.disabled = true;

        alamat.required = false;

    }

    // Saat metode berubah
    metode.addEventListener("change",function(){

        if(this.value=="Delivery"){

            alamat.disabled = false;

            alamat.required = true;

            alamat.focus();

        }else{

            alamat.disabled = true;

            alamat.required = false;

            alamat.value = "";

        }

    });

    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

    </body>

    </html>