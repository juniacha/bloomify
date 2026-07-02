<?php
session_start();
include '../config/koneksi.php';

if (!isset($_SESSION['email'])) {
    header("Location:../auth/login.php");
    exit();
}

if ($_SESSION['role'] != "admin") {
    header("Location:../customer/index.php");
    exit();
}

$sql = "SELECT transaksi.*, produk.nama_produk
        FROM transaksi
        JOIN produk
        ON transaksi.id_produk = produk.id_produk
        ORDER BY transaksi.id_transaksi DESC";

$query = mysqli_query($koneksi, $sql);

// Statistik
$totalPesanan = mysqli_fetch_assoc(
    mysqli_query($koneksi, "
SELECT COUNT(*) AS total
FROM transaksi
")
);

$totalOnline = mysqli_fetch_assoc(
    mysqli_query($koneksi, "
SELECT COUNT(*) AS total
FROM transaksi
WHERE sumber='Online'
")
);

$totalOffline = mysqli_fetch_assoc(
    mysqli_query($koneksi, "
SELECT COUNT(*) AS total
FROM transaksi
WHERE sumber='Offline'
")
);

$totalPendapatan = mysqli_fetch_assoc(
    mysqli_query($koneksi, "
SELECT IFNULL(SUM(total_harga),0) AS total
FROM transaksi
WHERE status='Selesai'
")
);
?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Kelola Pesanan | Bloomify</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="../assets/css/style.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link
        href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600;700&family=Poppins:wght@300;400;500;600&display=swap"
        rel="stylesheet">

</head>

<body class="admin-bg">

    <div class="admin-wrapper">

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

        <main class="content">

            <div class="topbar">

                <div>

                    <h2>Kelola Pesanan</h2>

                    <p>

                        Kelola seluruh pesanan customer Bloomify.

                    </p>

                </div>

                <a href="tambah_transaksi.php" class="btn btn-bloom">

                    <i class="bi bi-plus-circle me-2"></i>

                    Pesanan Offline

                </a>

            </div>
            <!-- =========================
    STATISTIK
    ========================= -->

            <div class="row g-4 mb-4">

                <div class="col-lg-3">

                    <div class="mini-card">

                        <i class="bi bi-bag-heart"></i>

                        <div>

                            <span>Total Pesanan</span>

                            <h3><?= $totalPesanan['total']; ?></h3>

                        </div>

                    </div>

                </div>

                <div class="col-lg-3">

                    <div class="mini-card">

                        <i class="bi bi-globe2"></i>

                        <div>

                            <span>Online</span>

                            <h3><?= $totalOnline['total']; ?></h3>

                        </div>

                    </div>

                </div>

                <div class="col-lg-3">

                    <div class="mini-card">

                        <i class="bi bi-shop"></i>

                        <div>

                            <span>Offline</span>

                            <h3><?= $totalOffline['total']; ?></h3>

                        </div>

                    </div>

                </div>

                <div class="col-lg-3">

                    <div class="mini-card">

                        <i class="bi bi-wallet2"></i>

                        <div>

                            <span>Pendapatan</span>

                            <h3>

                                Rp <?= number_format($totalPendapatan['total'], 0, ',', '.'); ?>

                            </h3>

                        </div>

                    </div>

                </div>

            </div>


            <!-- =========================
    SEARCH
    ========================= -->
            <div class="top-right">

                <div class="search-box">

                    <i class="bi bi-search"></i>

                    <input type="text" id="searchPesanan" placeholder="Search...">

                </div>
            </div>


            <!-- =========================
    LIST PESANAN
    ========================= -->

            <div class="row g-4" id="pesananList">

                <?php while ($data = mysqli_fetch_assoc($query)) { ?>

                    <div class="col-lg-6 pesanan-item-search">

                        <div class="product-admin-card">

                            <div class="product-body">

                                <div class="d-flex justify-content-between align-items-start mb-3">

                                    <div>

                                        <span class="product-category">

                                            #TRX<?= str_pad($data['id_transaksi'], 4, "0", STR_PAD_LEFT); ?>

                                        </span>

                                        <h4 class="mt-2">

                                            <?= $data['nama_pemesan']; ?>

                                        </h4>

                                        <p class="product-desc mb-0">

                                            <?= $data['nama_produk']; ?>

                                        </p>

                                    </div>

                                    <div>

                                        <?php

                                        $status = $data['status'];

                                        if ($status == "Pesanan Masuk") {

                                            echo "<span class='badge bg-warning text-dark'>$status</span>";

                                        } elseif ($status == "Diproses") {

                                            echo "<span class='badge bg-primary'>$status</span>";

                                        } elseif ($status == "Sedang Diantar") {

                                            echo "<span class='badge bg-info'>$status</span>";

                                        } elseif ($status == "Selesai") {

                                            echo "<span class='badge bg-success'>$status</span>";

                                        } else {

                                            echo "<span class='badge bg-danger'>$status</span>";

                                        }

                                        ?>

                                    </div>

                                </div>

                                <div class="row mb-3">

                                    <div class="col-6">

                                        <small class="text-muted">

                                            Tanggal

                                        </small>

                                        <br>

                                        <?= date('d M Y', strtotime($data['tanggal'])); ?>

                                    </div>

                                    <div class="col-6">

                                        <small class="text-muted">

                                            Total

                                        </small>

                                        <br>

                                        <strong>

                                            Rp <?= number_format($data['total_harga'], 0, ',', '.'); ?>

                                        </strong>

                                    </div>

                                </div>

                                <div class="row mb-3">
                                    <div class="col-6">
                                        <small class="text-muted">
                                            Ukuran
                                        </small>

                                        <br>
                                        <?= ucfirst(strtolower($data['ukuran'])); ?>
                                    </div>

                                    <div class="col-6">
                                        <small class="text-muted">
                                            Sumber
                                        </small>
                                        <br>
                                        <?= $data['sumber']; ?>
                                    </div>

                                </div>

                                <div class="product-action">

                                    <a href="detail_pesanan.php?id=<?= $data['id_transaksi']; ?>"
                                        class="btn btn-outline-bloom">

                                        <i class="bi bi-eye me-1"></i>

                                        Detail

                                    </a>

                                    <a href="edit_status.php?id=<?= $data['id_transaksi']; ?>" class="btn btn-bloom">

                                        <i class="bi bi-arrow-repeat me-1"></i>

                                        Status

                                    </a>

                                </div>

                            </div>

                        </div>

                    </div>

                <?php } ?>

            </div>

            <script>

                // ==============================
                // SEARCH PESANAN
                // ==============================

                const searchPesanan = document.getElementById("searchPesanan");

                searchPesanan.addEventListener("keyup", function () {

                    let keyword = this.value.toLowerCase();

                    let card = document.querySelectorAll(".pesanan-item-search");

                    card.forEach(function (item) {

                        let isi = item.innerText.toLowerCase();

                        if (isi.indexOf(keyword) > -1) {

                            item.style.display = "";

                        } else {

                            item.style.display = "none";

                        }

                    });

                });

            </script>

        </main>

    </div>

</body>

</html>