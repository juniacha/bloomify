<?php
session_start();
include '../config/koneksi.php';

if(!isset($_SESSION['email'])){
    header("Location:../auth/login.php");
    exit();
}

$id_user = $_SESSION['id_user'];

$sql = "SELECT
            keranjang.*,
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
<html lang="id">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Keranjang Saya</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="../assets/css/style.css">

<link rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

</head>

<body>

<!-- ===========================
NAVBAR
=========================== -->

<nav class="navbar navbar-expand-lg sticky-top">

<div class="container">

<a class="navbar-brand" href="index.php">
<i class="bi bi-flower1 me-2"></i>Bloomify
</a>

<button class="navbar-toggler"
type="button"
data-bs-toggle="collapse"
data-bs-target="#navbarBloomify">

<span class="navbar-toggler-icon"></span>

</button>

<div class="collapse navbar-collapse"
id="navbarBloomify">

<ul class="navbar-nav mx-auto">

<li class="nav-item">
<a class="nav-link" href="index.php">
Home
</a>
</li>

<li class="nav-item">
<a class="nav-link" href="produk.php">
Produk
</a>
</li>

<li class="nav-item">
<a class="nav-link" href="pesanan_saya.php">
Riwayat Pesanan
</a>
</li>

</ul>

<div class="d-flex align-items-center gap-3">

<?php
$jumlahKeranjang = mysqli_fetch_assoc(
    mysqli_query($koneksi,"
        SELECT COUNT(*) AS total
        FROM keranjang
        WHERE id_user='".$_SESSION['id_user']."'
    ")
);
?>

<a href="keranjang.php" class="nav-cart">

    <i class="bi bi-bag fs-5"></i>

    <?php if($jumlahKeranjang['total'] > 0){ ?>

        <span class="cart-badge">

            <?= $jumlahKeranjang['total']; ?>

        </span>

    <?php } ?>

</a>

<span>
Halo,
<strong><?= $_SESSION['nama']; ?></strong>
</span>

<a href="../auth/logout.php"
class="btn btn-bloom">
Logout
</a>

</div>

</div>

</div>

</nav>

<!-- ===========================
HEADER
=========================== -->

<section class="cart-header">

<div class="container text-center">

<span class="section-subtitle">

SHOPPING CART

</span>

<h1 class="section-title">

Keranjang Saya

</h1>

<p class="section-desc">

<?= $total_item; ?> Produk di Keranjang

</p>

<a href="produk.php"
class="back-link">

<i class="bi bi-arrow-left"></i>

Lanjut Belanja

</a>

</div>

</section>

<!-- ===========================
ISI KERANJANG
=========================== -->

<div class="container py-5">

<?php

if($total_item > 0){

$total_semua = 0;

while($data = mysqli_fetch_assoc($query)){

if($data['ukuran']=="Small"){

    $harga = $data['harga_small'];

}elseif($data['ukuran']=="Medium"){

    $harga = $data['harga_medium'];

}else{

    $harga = $data['harga_large'];

}

$subtotal = $harga * $data['jumlah'];

if($data['boneka']) $subtotal += 25000;
if($data['balon']) $subtotal += 15000;
if($data['kartu_ucapan']) $subtotal += 5000;

$total_semua += $subtotal;

?>

<div class="cart-card mb-5">

    <div class="row g-5 align-items-start">

        <!-- FOTO -->

        <div class="col-lg-5">

            <img
            src="../assets/img/<?= $data['gambar']; ?>"
            class="cart-image"
            alt="<?= $data['nama_produk']; ?>">

        </div>

        <!-- DETAIL -->

        <div class="col-lg-7">

            <form
            action="update_keranjang.php"
            method="POST">

            <input
            type="hidden"
            name="id_keranjang"
            value="<?= $data['id_keranjang']; ?>">

            <div class="d-flex justify-content-between align-items-start mb-4">

                <div>

                    <span class="size-badge">

                        <?= $data['ukuran']; ?>

                    </span>

                    <h2 class="cart-title mt-3">

                        <?= $data['nama_produk']; ?>

                    </h2>

                    <div class="cart-price mt-2">

                        Rp <?= number_format($harga,0,",","."); ?>

                    </div>

                </div>

                <a
                href="hapus_keranjang.php?id=<?= $data['id_keranjang']; ?>"
                class="delete-link"
                onclick="return confirm('Hapus produk ini?')">

                    <i class="bi bi-trash3"></i>

                    Hapus

                </a>

            </div>

            <hr>

            <div class="mb-4">

                <label class="form-label fw-semibold">

                    Jumlah

                </label>

                <div class="qty-box">

                    <a
                    href="kurang_jumlah.php?id=<?= $data['id_keranjang']; ?>">

                        <i class="bi bi-dash-lg"></i>

                    </a>

                    <span>

                        <?= $data['jumlah']; ?>

                    </span>

                    <a
                    href="tambah_jumlah.php?id=<?= $data['id_keranjang']; ?>">

                        <i class="bi bi-plus-lg"></i>

                    </a>

                </div>

            </div>

            <hr>

            <div class="cart-footer">

                    <div>

                        <span class="subtotal-label">

                            Subtotal

                        </span>

                        <h2 class="subtotal-price">

                            Rp <?= number_format($subtotal,0,",","."); ?>

                        </h2>

                    </div>

                    <div class="cart-button-group">

                        <a
                        href="hapus_keranjang.php?id=<?= $data['id_keranjang']; ?>"
                        class="btn btn-danger"
                        onclick="return confirm('Hapus produk ini?')">

                            <i class="bi bi-trash me-2"></i>

                            Hapus

                        </a>

                        <a
                            href="checkout.php?id_produk=<?= $data['id_produk']; ?>&ukuran=<?= urlencode($data['ukuran']); ?>"
                            class="btn btn-bloom">

                                <i class="bi bi-credit-card me-2"></i>

                                Checkout

                        </a>

                    </div>

                </div>

                </form>

                </div>

                </div>

                </div>

                <?php

                }

                ?>

                <div class="cart-summary">

                    <div>

                        <small>

                            Total Belanja

                        </small>

                        <h2>

                            Rp <?= number_format($total_semua,0,",","."); ?>

                        </h2>

                    </div>

                    <a
                    href="checkout_semua.php"
                    class="btn btn-bloom btn-lg">

                        <i class="bi bi-bag-check me-2"></i>

                        Checkout Semua

                    </a>

                </div>

                <?php

                }else{

                ?>

                <div class="empty-cart">

                    <i class="bi bi-bag-x"></i>

                    <h2>

                        Keranjang Masih Kosong

                    </h2>

                    <p>

                        Yuk pilih bouquet favoritmu.

                    </p>

                    <a
                    href="produk.php"
                    class="btn btn-bloom">

                        Belanja Sekarang

                    </a>

                </div>

                <?php

                }

                ?>

                </div>

                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>