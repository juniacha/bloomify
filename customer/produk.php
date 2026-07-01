<?php
session_start();
include '../config/koneksi.php';

if(!isset($_SESSION['email'])){
    header("Location:../auth/login.php");
    exit();
}

if($_SESSION['role'] != "customer"){
    header("Location:../admin/dashboard.php");
    exit();
}

// Filter kategori
$where = "";
if(isset($_GET['id_kategori'])){
    $id_kategori = $_GET['id_kategori'];
    $where = "WHERE produk.id_kategori='$id_kategori'";
}else{
    $where = "";
}

// Query produk
$query_produk = mysqli_query($koneksi,"
SELECT
produk.*,
kategori.nama_kategori

FROM produk

LEFT JOIN kategori
ON produk.id_kategori = kategori.id_kategori

$where

ORDER BY produk.id_produk DESC
");

// Judul halaman
$judul = "Semua Bouquet";

if(isset($id_kategori)){

    $kategori = mysqli_fetch_assoc(mysqli_query($koneksi,"
    SELECT nama_kategori
    FROM kategori
    WHERE id_kategori='$id_kategori'
    "));

    $judul = "Bouquet ".$kategori['nama_kategori'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?= $judul; ?> | Bloomify</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <!-- Navbar -->
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
                        <a class="nav-link"
                        href="index.php">
                            Home
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link active"
                        href="produk.php">
                            Produk
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link"
                        href="pesanan_saya.php">
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

    <section class="py-5">
        <div class="container">

            <a href="javascript:history.back()" class="back-link mb-4 d-inline-flex">
                <i class="bi bi-arrow-left"></i>
                Kembali
            </a>

            <div class="d-flex justify-content-between align-items-center mb-5">
                <div>
                    <h2><?= $judul; ?></h2>
                    <p class="text-secondary mb-0">
                        Temukan bouquet terbaik untuk setiap momen spesial.
                    </p>
                </div>

                <?php if(isset($_GET['id_kategori'])){ ?>

                <a href="produk.php" class="btn btn-outline-bloom">
                    Semua Produk
                </a>
                <?php } ?>
        </div>

    <div class="row g-4">
        <div class="row g-4">
            <?php while($produk = mysqli_fetch_assoc($query_produk)){ ?>

            <div class="col-lg-3 col-md-6">
                <div class="card product-card position-relative h-100">
                    <img
                        src="../assets/img/<?= $produk['gambar']; ?>"
                        class="card-img-top"
                        alt="<?= $produk['nama_produk']; ?>"
                    >

                    <div class="card-body d-flex flex-column">
                        <span class="badge category-badge align-self-start mb-3">
                            <?= $produk['nama_kategori']; ?>
                        </span>

                        <h5 class="product-title">
                            <?= $produk['nama_produk']; ?>
                        </h5>

                        <div class="product-price mb-4">
                            <p class="product-desc">
                                <?= substr($produk['deskripsi'],0,70); ?>...
                            </p>

                            Rp <?= number_format($produk['harga_small']); ?>
                            -
                            Rp <?= number_format($produk['harga_large']); ?>
                        </div>

                        <a
                            href="detail_produk.php?id=<?= $produk['id_produk']; ?>"
                            class="btn btn-bloom mt-auto w-100">

                            Lihat Detail
                        </a>
                    </div>
                </div>
            </div>

            <?php } ?>

        </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/script.js"></script>
</body>
</html>