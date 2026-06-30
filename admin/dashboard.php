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

$total_produk = mysqli_fetch_assoc(
    mysqli_query($koneksi,"SELECT COUNT(*) AS total FROM produk"));

$total_kategori = mysqli_fetch_assoc(
    mysqli_query($koneksi,"SELECT COUNT(*) AS total FROM kategori"));

$total_pesanan = mysqli_fetch_assoc(
    mysqli_query($koneksi,"SELECT COUNT(*) AS total FROM transaksi"));

$pesanan_masuk = mysqli_fetch_assoc(
    mysqli_query($koneksi,"SELECT COUNT(*) AS total FROM transaksi WHERE status='Pesanan Masuk'"));

$diproses = mysqli_fetch_assoc(
    mysqli_query($koneksi,"SELECT COUNT(*) AS total FROM transaksi WHERE status='Diproses'"));

$selesai = mysqli_fetch_assoc(
    mysqli_query($koneksi,"SELECT COUNT(*) AS total FROM transaksi WHERE status='Selesai'"));

$menunggu = mysqli_fetch_assoc(
    mysqli_query($koneksi,"SELECT COUNT(*) AS total FROM transaksi WHERE status='Menunggu Pembatalan'"));

$dibatalkan = mysqli_fetch_assoc(
    mysqli_query($koneksi,"SELECT COUNT(*) AS total FROM transaksi WHERE status='Dibatalkan'"));

$pendapatan = mysqli_fetch_assoc(
    mysqli_query($koneksi,"
        SELECT IFNULL(SUM(total_harga),0) AS total
        FROM transaksi
        WHERE status='Selesai'
    "));

$pesanan_terbaru = mysqli_query($koneksi,"
SELECT transaksi.*, produk.nama_produk
FROM transaksi
JOIN produk
ON transaksi.id_produk = produk.id_produk
ORDER BY transaksi.tanggal DESC
LIMIT 5
");

$produk_terlaris = mysqli_query($koneksi,"
SELECT
produk.id_produk,
produk.nama_produk,
produk.gambar,
COUNT(transaksi.id_produk) AS total_terjual
FROM transaksi
JOIN produk
ON transaksi.id_produk = produk.id_produk
WHERE transaksi.status='Selesai'
GROUP BY transaksi.id_produk
ORDER BY total_terjual DESC
LIMIT 5
");

$chart = mysqli_query($koneksi,"
SELECT
    MONTH(tanggal) AS bulan,
    SUM(total_harga) AS total
FROM transaksi
WHERE status='Selesai'
GROUP BY MONTH(tanggal)
");

$hasilChart = [];

while($row = mysqli_fetch_assoc($chart)){

    $hasilChart[$row['bulan']] = $row['total'];

}

$namaBulan = [
    "Jan","Feb","Mar","Apr","Mei","Jun",
    "Jul","Agu","Sep","Okt","Nov","Des"
];

$labelChart = [];
$dataChart = [];

for($i=1;$i<=12;$i++){

    $labelChart[] = $namaBulan[$i-1];

    if(isset($hasilChart[$i])){

        $dataChart[] = (int)$hasilChart[$i];

    }else{

        $dataChart[] = 0;

    }

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard Admin | Bloomify</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

            <a href="dashboard.php" class="active">

                <i class="bi bi-grid-1x2-fill"></i>

                Dashboard

            </a>

            <a href="produk.php">

                <i class="bi bi-box-seam"></i>

                Produk

            </a>

            <a href="kategori.php">

                <i class="bi bi-tags-fill"></i>

                Kategori

            </a>

            <a href="transaksi.php">

                <i class="bi bi-bag-heart-fill"></i>

                Pesanan

            </a>

            <a href="laporan.php">

                <i class="bi bi-bar-chart-fill"></i>

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

        <!-- TOPBAR -->

        <div class="topbar">

            <div>

                <h2>

                    Welcome Back,
                    <?= $_SESSION['nama']; ?> 

                </h2>

                <p>

                    Bloomify Florist Management Dashboard

                </p>

            </div>

            <div class="top-right">

                <div class="profile-box">

                    <i class="bi bi-person-circle"></i>

                    <div>

                        <b><?= $_SESSION['nama']; ?></b>

                        <small>Administrator</small>

                    </div>

                </div>

            </div>

        </div>

        <!-- CARD -->

        <div class="row g-4">

            <div class="col-xl-3 col-md-6">

                <div class="stat-card">

                    <div class="icon-box">

                        <i class="bi bi-bag-heart"></i>

                    </div>

                    <div>

                        <span>Total Pesanan</span>

                        <h3><?= $total_pesanan['total']; ?></h3>

                    </div>

                </div>

            </div>

            <div class="col-xl-3 col-md-6">

                <div class="stat-card">

                    <div class="icon-box">

                        <i class="bi bi-box"></i>

                    </div>

                    <div>

                        <span>Total Produk</span>

                        <h3><?= $total_produk['total']; ?></h3>

                    </div>

                </div>

            </div>

            <div class="col-xl-3 col-md-6">

                <div class="stat-card">

                    <div class="icon-box">

                        <i class="bi bi-tags"></i>

                    </div>

                    <div>

                        <span>Kategori</span>

                        <h3><?= $total_kategori['total']; ?></h3>

                    </div>

                </div>

            </div>

            <div class="col-xl-3 col-md-6">

                <div class="stat-card">

                    <div class="icon-box income">

                        <i class="bi bi-wallet2"></i>

                    </div>

                    <div>

                        <span>Pendapatan</span>

                        <h3>

                            Rp <?= number_format($pendapatan['total'],0,',','.');?>

                        </h3>

                    </div>

                </div>

            </div>

        </div>

        <!-- ===========================
    GRAFIK & STATUS
    =========================== -->

    <div class="row mt-4">

        <div class="col-lg-8">

            <div class="dashboard-panel">

                <h4 class="mb-4">

                    <i class="bi bi-graph-up-arrow me-2"></i>

                    Pendapatan Bulanan

                </h4>

                <canvas id="chartPendapatan"></canvas>

            </div>

        </div>

        <div class="col-lg-4">

            <div class="dashboard-panel">

                <div class="panel-header">

                    <div>

                        <h4>Status Pesanan</h4>

                        <span>Current Orders</span>

                    </div>

                </div>

                <canvas id="statusChart"></canvas>

                <div class="status-info mt-4">

                    <div>

                        <span class="dot pink"></span>

                        Pesanan Masuk

                        <b><?= $pesanan_masuk['total']; ?></b>

                    </div>

                    <div>

                        <span class="dot green"></span>

                        Diproses

                        <b><?= $diproses['total']; ?></b>

                    </div>

                    <div>

                        <span class="dot peach"></span>

                        Selesai

                        <b><?= $selesai['total']; ?></b>

                    </div>

                    <div>

                        <span class="dot red"></span>

                        Dibatalkan

                        <b><?= $dibatalkan['total']; ?></b>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <!-- ===========================
    QUICK MENU
    =========================== -->

    <div class="row mt-4">

        <div class="col-lg-3">

            <a href="produk.php" class="quick-menu">

                <i class="bi bi-box-seam"></i>

                <div>

                    <h6>Produk</h6>

                    <small>Kelola Produk</small>

                </div>

            </a>

        </div>

        <div class="col-lg-3">

            <a href="kategori.php" class="quick-menu">

                <i class="bi bi-tags"></i>

                <div>

                    <h6>Kategori</h6>

                    <small>Data Master</small>

                </div>

            </a>

        </div>

        <div class="col-lg-3">

            <a href="transaksi.php" class="quick-menu">

                <i class="bi bi-bag-heart"></i>

                <div>

                    <h6>Pesanan</h6>

                    <small>Manage Order</small>

                </div>

            </a>

        </div>

        <div class="col-lg-3">

            <a href="laporan.php" class="quick-menu">

                <i class="bi bi-bar-chart"></i>

                <div>

                    <h6>Laporan</h6>

                    <small>View Report</small>

                </div>

            </a>

        </div>

    </div>

    <!-- ===========================
    TABLE
    =========================== -->

    <div class="row mt-4">

        <div class="col-lg-8">

            <div class="dashboard-panel">

                <div class="panel-header">

                    <h4>Ringkasan Dashboard</h4>

                </div>

                <table class="table align-middle mt-3">

                    <thead>

                        <tr>

                            <th>Informasi</th>

                            <th>Total</th>

                        </tr>

                    </thead>

                    <tbody>

                        <tr>

                            <td>Total Pesanan</td>

                            <td><?= $total_pesanan['total']; ?></td>

                        </tr>

                        <tr>

                            <td>Total Produk</td>

                            <td><?= $total_produk['total']; ?></td>

                        </tr>

                        <tr>

                            <td>Total Kategori</td>

                            <td><?= $total_kategori['total']; ?></td>

                        </tr>

                        <tr>

                            <td>Pendapatan</td>

                            <td>

                                Rp <?= number_format($pendapatan['total'],0,',','.'); ?>

                            </td>

                        </tr>

                    </tbody>

                </table>

            </div>

        </div>

        <div class="col-lg-4">

            <div class="dashboard-panel">

                <h4>Bloomify</h4>

                <p class="text-secondary mt-3">

                    Welcome to Bloomify Admin Dashboard.

                    Kelola produk, kategori, pesanan,
                    dan laporan penjualan dengan lebih
                    mudah melalui satu dashboard.

                </p>

            </div>

        </div>

    </div>

    <!-- ======================================
    PESANAN TERBARU & AKTIVITAS
    ====================================== -->

    <div class="row mt-4">

        <div class="col-lg-8">

            <div class="dashboard-panel">

                <div class="panel-header">

                    <div>

                        <h4>Pesanan Terbaru</h4>

                        <span>Latest Transactions</span>

                    </div>

                    <a href="transaksi.php" class="btn btn-sm btn-outline-bloom">
                        Lihat Semua
                    </a>

                </div>

                <table class="table table-hover align-middle">

                    <thead>

                        <tr>

                            <th>ID</th>

                            <th>Customer</th>

                            <th>Total</th>

                            <th>Status</th>

                        </tr>

                    </thead>

                    <tbody>

                    <?php
                    while($pesanan=mysqli_fetch_assoc($pesanan_terbaru)){
                    ?>

                    <tr>

                    <td>

                    #TRX<?= str_pad($pesanan['id_transaksi'],4,"0",STR_PAD_LEFT); ?>

                    </td>

                    <td>

                    <?= $pesanan['nama_pemesan']; ?>

                    </td>

                    <td>

                    Rp <?= number_format($pesanan['total_harga'],0,',','.'); ?>

                    </td>

                    <td>

                    <?php

                    if($pesanan['status']=="Pesanan Masuk"){

                    echo "<span class='status-badge pending'>Pesanan Masuk</span>";

                    }elseif($pesanan['status']=="Diproses"){

                    echo "<span class='status-badge waiting'>Diproses</span>";

                    }elseif($pesanan['status']=="Sedang Diantar"){

                    echo "<span class='status-badge info'>Sedang Diantar</span>";

                    }elseif($pesanan['status']=="Selesai"){

                    echo "<span class='status-badge success'>Selesai</span>";

                    }else{

                    echo "<span class='status-badge cancel'>Dibatalkan</span>";

                    }

                    ?>

                    </td>

                    </tr>

                    <?php } ?>

                    </tbody>

                </table>

            </div>

        </div>

        <div class="col-lg-4">

            <div class="dashboard-panel">

                <h4>Produk Terlaris</h4>

                <span class="text-secondary">
                    Best Seller
                </span>

                <div class="product-item">

                    <div class="product-icon">

                        🌸

                    </div>

                    <?php

                    if(mysqli_num_rows($produk_terlaris)>0){

                    while($produk=mysqli_fetch_assoc($produk_terlaris)){

                    ?>

                    <div class="d-flex align-items-center justify-content-between py-3 border-bottom">

                        <div class="d-flex align-items-center">

                            <img
                            src="../assets/img/<?= $produk['gambar']; ?>"
                            width="55"
                            height="55"
                            class="rounded-3 me-3"
                            style="object-fit:cover;">

                            <div>

                                <strong>

                                    <?= $produk['nama_produk']; ?>

                                </strong>

                                <br>

                                <small class="text-muted">

                                    <?= $produk['total_terjual']; ?> kali terjual

                                </small>

                            </div>

                        </div>

                        <i class="bi bi-fire text-danger fs-5"></i>

                    </div>

                    <?php

                    }

                    }else{

                    ?>

                    <div class="text-center py-5">

                    <i class="bi bi-box-seam fs-1 text-muted"></i>

                    <p class="mt-3">

                    Belum ada produk terjual.

                    </p>

                    </div>

                    <?php } ?>

                </div>

            </div>

        </div>

    </div>

    <!-- ======================================
    FOOTER
    ====================================== -->

    <div class="footer-admin">

        © <?= date('Y'); ?>

        Bloomify Florist Management System

    </div>

    </main>

    </div>

    <script>

        const status=document.getElementById('statusChart');

        new Chart(status,{

        type:'doughnut',

        data:{

        labels:['Masuk','Diproses','Selesai','Batal'],

        datasets:[{

        data:[

        <?= $pesanan_masuk['total'];?>,

        <?= $diproses['total'];?>,

        <?= $selesai['total'];?>,

        <?= $dibatalkan['total'];?>

        ],

        backgroundColor:[

        '#CB807D',

        '#9FA58D',

        '#F0B5B3',

        '#F6839C'

        ],

        borderWidth:0

        }]

        },

        options:{

        plugins:{
        legend:{display:false}
        }

        }

        });

    </script>

    <script>

        const ctx = document.getElementById('chartPendapatan');

        new Chart(ctx,{

            type:'line',

            data:{

                labels:<?= json_encode($labelChart); ?>,

                datasets:[{

                    data:<?= json_encode($dataChart); ?>,

                    borderColor:'#CB807D',

                    backgroundColor:'rgba(203,128,125,.18)',

                    borderWidth:3,

                    pointRadius:6,

                    fill:true,

                    tension:.2

                }]

            },

            options:{

                responsive:true,

                maintainAspectRatio:false,

                plugins:{
                    legend:{
                        display:false
                    }
                },

                scales: {

                    y: {

                        beginAtZero: true,

                        ticks: {

                            callback: function(value){

                                return 'Rp ' + value.toLocaleString('id-ID');

                            }

                        }

                    }

                }

            }

        });

    </script>

</body>
</html>