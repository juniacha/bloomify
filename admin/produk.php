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

// Statistik
$total_produk = mysqli_fetch_assoc(
mysqli_query($koneksi,"
SELECT COUNT(*) AS total
FROM produk
"));

$total_kategori = mysqli_fetch_assoc(
mysqli_query($koneksi,"
SELECT COUNT(*) AS total
FROM kategori
"));

$total_stok = mysqli_fetch_assoc(
mysqli_query($koneksi,"
SELECT
IFNULL(SUM(stok_small+stok_medium+stok_large),0) AS total
FROM produk
"));

// Produk
$query = mysqli_query($koneksi,"
SELECT produk.*, kategori.nama_kategori
FROM produk
JOIN kategori
ON produk.id_kategori = kategori.id_kategori
ORDER BY produk.id_produk DESC
");
?>

<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1">

<title>Kelola Produk</title>

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

    <!-- SIDEBAR -->

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

    <a href="produk.php" class="active">

    <i class="bi bi-box-seam"></i>

    Produk

    </a>

    <a href="kategori.php">

    <i class="bi bi-tags"></i>

    Kategori

    </a>

    <a href="transaksi.php">

    <i class="bi bi-bag"></i>

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

    <!-- CONTENT -->

    <main class="content">

    <div class="topbar">

    <div>

    <h2>Kelola Produk</h2>

    <p>

    Manage all bouquet products.

    </p>

    </div>

    <a
    href="tambah_produk.php"
    class="btn btn-bloom">

    <i class="bi bi-plus-circle me-2"></i>

    Tambah Produk

    </a>

    </div>

    <!-- CARD STATISTIK -->

    <div class="row gx-4 gy-4">

    <div class="col-lg-6 col-xl-4">

    <div class="mini-card">

    <i class="bi bi-box-seam"></i>

    <div>

    <span>Total Produk</span>

    <h3>

    <?= $total_produk['total']; ?>

    </h3>

    </div>

    </div>

    </div>

    <div class="col-lg-4">

    <div class="mini-card">

    <i class="bi bi-box2-heart"></i>

    <div>

    <span>Total Stok</span>

    <h3>

    <?= $total_stok['total']; ?>

    </h3>

    </div>

    </div>

    </div>

    <div class="col-lg-4">

    <div class="mini-card">

    <i class="bi bi-tags"></i>

    <div>

    <span>Kategori</span>

    <h3>

    <?= $total_kategori['total']; ?>

    </h3>

    </div>

    </div>

    </div>

    </div>

    <!-- SEARCH -->

    <div class="product-toolbar">

    <div class="search-box">

    <i class="bi bi-search"></i>

    <input
    type="text"
    id="searchProduk"
    placeholder="Cari produk...">

    </div>

    </div>

    <!-- PRODUCT -->

    <div class="row g-4">
        <?php

        if(mysqli_num_rows($query) > 0){

        while($data = mysqli_fetch_assoc($query)){

        ?>

        <div class="col-lg-6 col-xl-4 product-item">

            <div class="product-card-admin">

                <div class="product-image">

                    <?php if(!empty($data['gambar'])){ ?>

                        <img
                        src="../assets/img/<?= $data['gambar']; ?>"
                        class="card-img-top"
                        alt="<?= $data['nama_produk']; ?>">

                    <?php } else { ?>

                        <img
                        src="../assets/img/no-image.png"
                        class="card-img-top"
                        alt="No Image">

                    <?php } ?>

                    <span class="badge-category">
                        <?= $data['nama_kategori']; ?>
                    </span>

                </div>
                <?php

                $totalStok =
                $data['stok_small'] +
                $data['stok_medium'] +
                $data['stok_large'];

                if($totalStok <= 5){

                ?>

                <span class="badge-low">

                Low Stock

                </span>

                <?php } ?>

                <div class="product-content">

                    <h4>

                        <?= $data['nama_produk']; ?>

                    </h4>

                    <p>

                        <?= substr($data['deskripsi'],0,90); ?>...

                    </p>

                    <div class="price-box">

                        Mulai dari

                        <h5>

                            Rp <?= number_format($data['harga_small'],0,',','.'); ?>

                        </h5>

                    </div>

                    <div class="stock-area">

                        <div>

                            <small>Small</small>

                            <b><?= $data['stok_small']; ?></b>

                        </div>

                        <div>

                            <small>Medium</small>

                            <b><?= $data['stok_medium']; ?></b>

                        </div>

                        <div>

                            <small>Large</small>

                            <b><?= $data['stok_large']; ?></b>

                        </div>

                    </div>

                    <div class="product-action">

                        <a
                        href="edit_produk.php?id=<?= $data['id_produk']; ?>"
                        class="btn btn-outline-bloom">

                            <i class="bi bi-pencil-square"></i>

                            Edit

                        </a>

                        <a
                        href="hapus_produk.php?id=<?= $data['id_produk']; ?>"
                        class="btn btn-danger"
                        onclick="return confirm('Yakin ingin menghapus produk ini?')">

                            <i class="bi bi-trash"></i>

                            Hapus

                        </a>

                    </div>

                </div>

            </div>

        </div>

        <?php

        }

        }else{

        ?>

        <div class="col-12">

            <div class="empty-product">

                <i class="bi bi-box-seam"></i>

                <h3>Belum Ada Produk</h3>

                <p>

                    Tambahkan produk pertama Bloomify.

                </p>

                <a
                href="tambah_produk.php"
                class="btn btn-bloom">

                    Tambah Produk

                </a>

            </div>

        </div>

        <?php } ?>

        </div>

    </main>
    </div>

    <script>

        const searchProduk = document.getElementById("searchProduk");

        searchProduk.addEventListener("keyup", function(){

            let keyword = this.value.toLowerCase();

            let items = document.querySelectorAll(".product-item");

            items.forEach(function(item){

                let text = item.innerText.toLowerCase();

                if(text.includes(keyword)){

                    item.style.display = "";

                }else{

                    item.style.display = "none";

                }

            });

        });

    </script>

    <footer class="admin-footer">

        © <?= date('Y'); ?> Bloomify Florist Management System

    </footer>

</body>
</html>