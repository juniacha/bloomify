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
?>

<?php

$query_produk = mysqli_query($koneksi,"
SELECT
produk.*,
kategori.nama_kategori

FROM produk

LEFT JOIN kategori
ON produk.id_kategori = kategori.id_kategori

ORDER BY produk.id_produk DESC

LIMIT 4
");

$query_kategori = mysqli_query($koneksi,"
SELECT *
FROM kategori
ORDER BY nama_kategori ASC
");

?>

<!DOCTYPE html>
<html>
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Bloomify | Home</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <!-- CSS -->
    <link rel="stylesheet" href="../assets/css/style.css">

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


    <!-- Hero -->
    <section class="hero-section" id="home">

        <div class="container">

            <div class="row align-items-center">

                <div class="col-lg-6">

                    <span class="text-uppercase fw-semibold" style="color:var(--primary); letter-spacing:2px;">
                        Bloomify Florist
                    </span>

                    <h1 class="display-3 mt-3 mb-4">
                        Make Every <br>
                        Moment Bloom
                    </h1>

                    <p class="lead mb-4">
                        Hadirkan kebahagiaan melalui rangkaian bunga yang dirancang dengan penuh cinta untuk setiap momen spesial.
                    </p>

                    <div class="hero-info mt-4">

                        <div class="d-flex align-items-center gap-2">

                            <span class="text-warning fs-5">
                                ★★★★★
                            </span>

                            <small class="text-secondary">
                                Beautiful bouquets for every occasion
                            </small>

                        </div>

                    </div>

                    <div class="d-flex gap-3">

                        <a href="kategori.php" class="btn btn-bloom">
                            <i class="bi bi-bag me-2"></i>
                            Belanja Sekarang
                        </a>

                        <a href="#produk" class="btn btn-outline-bloom">
                            Jelajahi
                        </a>

                    </div>

                </div>

                <div class="col-lg-6 text-center">

                    <img src="../assets/img/hero.png"
                        class="img-fluid hero-img"
                        alt="Hero">

                </div>

            </div>

        </div>

    </section>

    <!--kategori-->
    <section class="py-5" id="kategori">
        <div class="container">
            <div class="section-title text-center">
                <span class="section-subtitle">
                    SHOP BY OCCASION
                </span>
                <h2>Explore Categories</h2>
                <p>
                    Pilih kategori bouquet sesuai kebutuhanmu.
                </p>
                <p class="text-secondary">
                    Pilih kategori bouquet sesuai kebutuhanmu.
                </p>
            </div>

            <div class="row g-4">
            <?php while($kategori=mysqli_fetch_assoc($query_kategori)){ ?>
                <div class="col-lg-4 col-md-6">
                    <div class="card category-card text-center p-5 h-100">
                        <div class="mb-3">
                            <i class="bi bi-flower1"
                            style="font-size:60px;color:var(--primary);"></i>
                        </div>

                        <h4><?= $kategori['nama_kategori']; ?></h4>

                        <a href="kategori.php?id=<?= $kategori['id_kategori']; ?>"
                        class="btn btn-outline-bloom mt-3">
                            Lihat Produk
                        </a>
                    </div>
                </div>
            <?php } ?>
            </div>
        </div>
    </section>


    <!-- Produk Terbaru -->
    <section class="py-5" id="produk">

        <div class="container">

            <div class="section-title text-center">
                <h2>New Arrivals</h2>
                <p class="text-secondary">
                    Koleksi bouquet terbaru dari Bloomify.
                </p>
            </div>

            <div class="row g-4">

            <?php while($produk = mysqli_fetch_assoc($query_produk)){ ?>

                <div class="col-lg-3 col-md-6">

                    <div class="card product-card position-relative h-100">

                        <span class="badge new-badge">
                            New Collection
                        </span>

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
                                <div class="mb-3">

                                    <i class="bi bi-star-fill text-warning"></i>

                                    <i class="bi bi-star-fill text-warning"></i>

                                    <i class="bi bi-star-fill text-warning"></i>

                                    <i class="bi bi-star-fill text-warning"></i>

                                    <i class="bi bi-star-fill text-warning"></i>

                                </div>

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

        </div>

    </section>

    <section class="py-5" style="background:var(--section);">

        <div class="container">

            <div class="section-title text-center">

                <span class="section-subtitle">
                    WHY CHOOSE US
                </span>

                <h2>
                    Why Choose Bloomify
                </h2>

                <p>
                    Kami menghadirkan bunga terbaik untuk setiap momen berharga.
                </p>

            </div>

            <div class="row text-center g-4">

                <div class="col-md-3">

                    <div class="card feature-card p-5 h-100">

                        <div class="feature-icon">
                            <i class="bi bi-flower2"></i>
                        </div>

                        <h5>Fresh Flowers</h5>

                        <p class="text-secondary">
                            Bunga segar berkualitas premium.
                        </p>

                    </div>

                </div>

                <div class="col-md-3">

                    <div class="card feature-card p-5 h-100">

                        <div class="feature-icon">
                            <i class="bi bi-truck"></i>
                        </div>

                        <h5></i>Fast Delivery</h5>

                        <p class="text-secondary">
                            Pengiriman cepat dan aman.
                        </p>

                    </div>

                </div>

                <div class="col-md-3">

                    <div class="card feature-card p-5 h-100">

                        <div class="feature-icon">
                            <i class="bi bi-stars"></i>
                        </div>

                        <h5>Premium Bouquet</h5>

                        <p class="text-secondary">
                            Dirangkai dengan penuh ketelitian.
                        </p>

                    </div>

                </div>

                <div class="col-md-3">

                    <div class="card feature-card p-5 h-100">

                        <div class="feature-icon">
                            <i class="bi bi-heart-fill"></i>
                        </div>

                        <h5>Made With Love</h5>

                        <p class="text-secondary">
                            Setiap bouquet dibuat sepenuh hati.
                        </p>

                    </div>

                </div>

            </div>

        </div>

    </section>

    <section class="cta-section py-5">

        <div class="container">

        <div class="text-center">

        <h2>

        Ready to Make Someone Smile?

        </h2>

        <p class="mt-3 mb-4">
            Craft your perfect bouquet and make every celebration unforgettable.
        </p>

        <a href="kategori.php"

        class="btn btn-light px-5">

        Shop Now

        </a>

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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/script.js"></script>
</body>
</html>