<?php
session_start();
include '../config/koneksi.php';

if(!isset($_SESSION['email'])){
    header("Location:../auth/login.php");
    exit();
}

// Ambil semua kategori
$queryKategori = mysqli_query($koneksi,"
SELECT * FROM kategori
");

// Query produk
$where = "";

if(isset($_GET['kategori'])){
    $id = $_GET['kategori'];
    $where = "WHERE produk.id_kategori='$id'";
}

$queryProduk = mysqli_query($koneksi,"
SELECT produk.*, kategori.nama_kategori
FROM produk
JOIN kategori
ON produk.id_kategori = kategori.id_kategori
$where
");
?>

<!DOCTYPE html>
<html lang="id">
<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>Shop | Bloomify</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet"
href="../assets/css/style.css">

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
                        <a class="nav-link" href="#kategori">Shop</a>
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
        OUR COLLECTION
        </span>

        <h1 class="display-4 mt-3">

        Find Your Perfect Bouquet

        </h1>

        <p class="text-secondary">

        Temukan rangkaian bunga terbaik
        untuk setiap momen spesial.

        </p>

        </div>

    </section>

    <section class="py-4">

        <div class="container text-center">

        <a href="kategori.php"
        class="btn btn-outline-bloom rounded-pill m-1">

        Semua

        </a>

        <?php while($kat=mysqli_fetch_assoc($queryKategori)){ ?>

        <a
        href="?kategori=<?=$kat['id_kategori'];?>"
        class="btn btn-outline-bloom rounded-pill m-1">

        <?=$kat['nama_kategori'];?>

        </a>

        <?php } ?>

        </div>

    </section>

    <section class="pb-5">

        <div class="container">

        <div class="row g-4">

        <?php while($produk=mysqli_fetch_assoc($queryProduk)){ ?>

        <div class="col-lg-4 col-md-6">

        <div class="card product-card h-100">

        <img
        src="../assets/img/produk/<?=$produk['gambar'];?>"
        class="card-img-top"
        alt="<?=$produk['nama_produk'];?>">

        <div class="card-body">

        <span class="badge new-badge">

        <?=$produk['nama_kategori'];?>

        </span>

        <h5 class="product-title mt-3">

        <?=$produk['nama_produk'];?>

        </h5>

        <div class="product-price mb-3">

        Rp <?=number_format($produk['harga_small']);?>
        -
        Rp <?=number_format($produk['harga_large']);?>

        </div>

        <a
        href="detail_produk.php?id=<?=$produk['id_produk'];?>"
        class="btn btn-bloom w-100">

        Lihat Detail

        </a>

        </div>

        </div>

        </div>

        <?php } ?>

        </div>

        </div>

    </section>

        <!-- Footer -->
    <footer class="pt-5 pb-3">

        <div class="container">

            <div class="row">

                <div class="col-lg-4 mb-4">

                    <h3>Bloomify</h3>

                    <p class="text-secondary">
                        Bloomify hadir untuk membantu setiap momen spesial menjadi lebih berkesan melalui rangkaian bunga yang elegan dan berkualitas.
                    </p>

                </div>

                <div class="col-lg-2 mb-4">

                    <h5>Menu</h5>

                    <ul class="list-unstyled">

                        <li><a href="index.php">Home</a></li>
                        <li><a href="kategori.php">Kategori</a></li>
                        <li><a href="pesanan_saya.php">Pesanan Saya</a></li>

                    </ul>

                </div>

                <div class="col-lg-3 mb-4">

                    <h5>Customer Service</h5>

                    <ul class="list-unstyled">

                        <li>Instagram</li>
                        <li>WhatsApp</li>
                        <li>Email</li>

                    </ul>

                </div>

                <div class="col-lg-3 mb-4">

                    <h5>Contact</h5>

                    <p>
                        <i class="bi bi-envelope me-2"></i>
                        bloomify@email.com
                    </p>

                    <p>
                        <i class="bi bi-telephone me-2"></i>
                        0812-3456-7890
                    </p>

                    <p>
                        <i class="bi bi-geo-alt me-2"></i>
                        Jakarta, Indonesia
                    </p>

                </div>

            </div>

            <hr>

            <div class="text-center text-secondary">

                © <?= date('Y'); ?> Bloomify. All Rights Reserved.

            </div>

        </div>

    </footer>

</body>