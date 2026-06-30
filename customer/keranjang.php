<?php
session_start();
include '../config/koneksi.php';

if(!isset($_SESSION['email'])){
    header("Location:../auth/login.php");
    exit();
}

$id_user = $_SESSION['id_user'];

$sql = "SELECT keranjang.*,
        produk.nama_produk,
        produk.gambar,
        produk.harga_small,
        produk.harga_medium,
        produk.harga_large
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
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="../assets/css/style.css">

<link rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">

            <a class="navbar-brand" href="index.php">
                <i class="bi bi-flower1 me-2"></i>Bloomify
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarBloomify">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarBloomify">

                <ul class="navbar-nav mx-auto">

                    <li class="nav-item">
                        <a class="nav-link active" href="index.php">Home</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#kategori">Kategori</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#produk">New Produk</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="pesanan_saya.php">Pesanan Saya</a>
                    </li>

                </ul>

                <div class="d-flex align-items-center gap-3">

                    <a href="keranjang.php" class="text-dark">
                        <i class="bi bi-bag fs-5"></i>
                    </a>

                    <span>
                        Halo,
                        <strong><?= $_SESSION['nama']; ?></strong>
                    </span>

                    <a href="../auth/logout.php" class="btn btn-bloom">
                        Logout
                    </a>

                </div>

            </div>

        </div>
    </nav>

    <section class="py-5" style="background:var(--section);">

        <div class="container text-center">

        <span class="section-subtitle">

        SHOPPING CART

        </span>

        <h1>

        Keranjang Saya

        </h1>

        <p class="text-secondary">

        <?= $total_item; ?> Produk di Keranjang

        </p>

        <a href="kategori.php"
        class="btn btn-outline-bloom mt-3">

        <i class="bi bi-arrow-left me-2"></i>

        Lanjut Belanja

        </a>

        </div>

    </section>

    <?php
    if($total_item>0){

    $total_semua = 0;
    while($data=mysqli_fetch_assoc($query)){

        if($data['ukuran']=="Small"){

            $harga=$data['harga_small'];

        }elseif($data['ukuran']=="Medium"){

            $harga=$data['harga_medium'];

        }else{

            $harga=$data['harga_large'];

        }

        $subtotal=$harga*$data['jumlah'];

        if($data['boneka']) $subtotal+=25000;
        if($data['balon']) $subtotal+=15000;
        if($data['kartu_ucapan']) $subtotal+=5000;

        $total_semua += $subtotal;
    ?>

    <div class="card product-card mb-4">
        <div class="row g-4 align-items-center p-3">

            <div class="col-lg-3">
                <?php if(!empty($data['gambar'])){ ?>
                <img
                    src="../assets/img/<?= $data['gambar']; ?>"
                    class="img-fluid rounded-4">
                <?php } ?>
            </div>

            <div class="col-lg-9">
                <form id="form<?= $data['id_keranjang']; ?>"
                    action="update_keranjang.php"
                    method="POST">

                    <input
                    type="hidden"
                    name="id_keranjang"
                    value="<?= $data['id_keranjang']; ?>">
                    
                    <h3 class="mb-2">
                        <?= $data['nama_produk']; ?>
                    </h3>

                    <span class="size-badge">
                        <?= $data['ukuran']; ?>
                    </span>

                    <div class="d-flex align-items-center gap-2">

                        <a
                        class="btn btn-outline-bloom"

                        href="kurang_jumlah.php?id=<?= $data['id_keranjang'];?>">

                        <i class="bi bi-dash"></i>

                        </a>

                        <span>

                        <?= $data['jumlah']; ?>

                        </span>

                        <a
                        class="btn btn-outline-bloom"

                        href="tambah_jumlah.php?id=<?= $data['id_keranjang'];?>">

                        <i class="bi bi-plus"></i>

                        </a>

                    </div>

                    <div class="card p-3 mt-4">
                        <label>
                            <input
                            type="checkbox"
                            name="boneka"

                            <?= $data['boneka'] ? "checked" : ""; ?>>

                            Boneka (+Rp25.000)
                        </label>

                        <br><br>

                        <label>
                            <input
                            type="checkbox"
                            name="balon"

                            <?= $data['balon'] ? "checked" : ""; ?>>

                            Balon (+Rp15.000)
                        </label>

                        <br><br>

                        <label>
                            <input
                            type="checkbox"
                            name="kartu_ucapan"

                            <?= $data['kartu_ucapan'] ? "checked" : ""; ?>>

                            Kartu Ucapan (+Rp5.000)
                        </label>
                    </div>

                    <label>Warna Buket</label>

                    <br>

                    <input class="form-control"
                    type="text"
                    name="warna_buket"
                    value="<?= htmlspecialchars($data['warna_buket'] ?? ''); ?>">

                    <br><br>

                    <label>Isi Surat</label>

                    <br>

                    <textarea class="form-control"
                    name="isi_surat"
                    rows="4"><?= htmlspecialchars($data['isi_surat'] ?? ''); ?></textarea>

                    <br><br>

                    <label>Catatan</label>

                    <br>

                    <textarea class="form-control"
                    name="catatan"
                    rows="3"><?= htmlspecialchars($data['catatan'] ?? ''); ?></textarea>

                   <div class="mt-4">

                        <h4 class="product-price">

                        Subtotal

                        Rp <?= number_format($subtotal,0,',','.');?>

                        </h4>

                    </div>

                        <button class="btn btn-bloom"
                        type="submit"
                        class="btn"
                        name="simpan">
                        <i class="bi bi-floppy"></i>
                            Simpan Perubahan
                        </button>

                        &nbsp;

                        <a class="btn btn-outline-danger"
                        href="hapus_keranjang.php?id=<?= $data['id_keranjang']; ?>"
                        onclick="return confirm('Hapus produk ini dari keranjang?')">
                        <i class="bi bi-trash"></i>
                            Hapus
                        </a>

                        &nbsp;

                        <button class="btn btn-success"
                            type="button"
                            class="btn"
                            onclick="checkout(<?= $data['id_keranjang']; ?>)">
                            <i class="bi bi-credit-card"></i>
                            Checkout
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <?php
        }
        ?>

        <div class="card p-4 mt-4">

            <h4>

            Order Summary

            </h4>

            <hr>

            <h3 class="product-price">

            Rp <?= number_format($total_semua,0,',','.');?>

            </h3>

        </div>

        <?php

        }else{

        ?>

        <div class="text-center py-5">

            <i class="bi bi-bag-x display-1"></i>

            <h3 class="mt-4">

            Keranjang Masih Kosong

            </h3>

            <p class="text-secondary">

            Yuk mulai pilih bouquet favoritmu.

            </p>

            <a
            href="kategori.php"

            class="btn btn-bloom">

            Mulai Belanja

            </a>

        </div>
        <?php } ?>

        <script>
            function checkout(id){

                var form = document.getElementById("form"+id);

                form.action = "update_keranjang.php?checkout=1";
                form.submit();
            }
        </script>
</body>
</html>

