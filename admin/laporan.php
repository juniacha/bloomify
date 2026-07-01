<?php
session_start();
include '../config/koneksi.php';

if (!isset($_SESSION['email'])) {
    header("Location:../auth/login.php");
    exit();
}

$bulan = isset($_GET['bulan']) ? $_GET['bulan'] : date('m');
$tahun = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');
$status = isset($_GET['status']) ? $_GET['status'] : "";

$where = "WHERE MONTH(tanggal)='$bulan'
          AND YEAR(tanggal)='$tahun'";

if ($status != "") {
    $where .= " AND status='$status'";
}

$sql = "SELECT transaksi.*, produk.nama_produk
        FROM transaksi
        JOIN produk
        ON transaksi.id_produk=produk.id_produk
        $where
        ORDER BY tanggal DESC";

$query = mysqli_query($koneksi, $sql);

$total_pesanan = mysqli_fetch_assoc(
    mysqli_query($koneksi, "
SELECT COUNT(*) total
FROM transaksi
$where")
);

$total_pendapatan = mysqli_fetch_assoc(
    mysqli_query($koneksi, "
SELECT IFNULL(SUM(total_harga),0) total
FROM transaksi
$where
AND status='Selesai'")
);

$pesanan_masuk = mysqli_fetch_assoc(
    mysqli_query($koneksi, "
SELECT COUNT(*) total
FROM transaksi
$where
AND status='Pesanan Masuk'")
);

$diproses = mysqli_fetch_assoc(
    mysqli_query($koneksi, "
SELECT COUNT(*) total
FROM transaksi
$where
AND status='Diproses'")
);

$selesai = mysqli_fetch_assoc(
    mysqli_query($koneksi, "
SELECT COUNT(*) total
FROM transaksi
$where
AND status='Selesai'")
);

$dibatalkan = mysqli_fetch_assoc(
    mysqli_query($koneksi, "
SELECT COUNT(*) total
FROM transaksi
$where
AND status='Dibatalkan'")
);

$menunggu = mysqli_fetch_assoc(
    mysqli_query($koneksi, "
SELECT COUNT(*) total
FROM transaksi
$where
AND status='Menunggu Pembatalan'")
);

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laporan Penjualan | Bloomify</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="../assets/css/style.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link
        href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600;700&family=Poppins:wght@300;400;500;600&display=swap"
        rel="stylesheet">

</head>

<body class="admin-bg">

    <div class="admin-wrapper">

        <!-- ==========================
SIDEBAR
========================== -->

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

                <a href="transaksi.php">

                    <i class="bi bi-bag-heart"></i>

                    Pesanan

                </a>

                <a href="laporan.php" class="active">

                    <i class="bi bi-bar-chart"></i>

                    Laporan

                </a>

                <a href="../auth/logout.php">

                    <i class="bi bi-box-arrow-right"></i>

                    Logout

                </a>

            </nav>

        </aside>

        <!-- ==========================
CONTENT
========================== -->

        <main class="content">

            <div class="topbar">

                <div>

                    <h2>Laporan Penjualan</h2>

                    <p>

                        Ringkasan transaksi dan pendapatan Bloomify.

                    </p>

                </div>

                <a href="export_excel.php?bulan=<?= $bulan; ?>&tahun=<?= $tahun; ?>&status=<?= $status; ?>"
                    class="btn btn-outline-bloom">

                    <i class="bi bi-download me-2"></i>

                    Export Excel

                </a>

            </div>

            <!-- ==========================
FILTER
========================== -->

            <div class="form-admin-card">

                <form method="GET">

                    <div class="row g-3 align-items-end">

                        <!-- BULAN -->

                        <div class="col-lg-3">

                            <label class="form-label">

                                Bulan

                            </label>

                            <select name="bulan" class="form-select">

                                <?php
                                for ($i = 1; $i <= 12; $i++) {
                                    ?>

                                    <option value="<?= sprintf("%02d", $i); ?>" <?= ($bulan == sprintf("%02d", $i)) ? "selected" : ""; ?>>

                                        <?= sprintf("%02d", $i); ?>

                                    </option>

                                <?php } ?>

                            </select>

                        </div>

                        <!-- TAHUN -->

                        <div class="col-lg-3">

                            <label class="form-label">

                                Tahun

                            </label>

                            <select name="tahun" class="form-select">

                                <?php
                                for ($i = 2024; $i <= 2035; $i++) {
                                    ?>

                                    <option value="<?= $i; ?>" <?= ($tahun == $i) ? "selected" : ""; ?>>

                                        <?= $i; ?>

                                    </option>

                                <?php } ?>

                            </select>

                        </div>

                        <!-- STATUS -->

                        <div class="col-lg-3">

                            <label class="form-label">

                                Status

                            </label>

                            <select name="status" class="form-select">

                                <option value="" <?= $status == "" ? "selected" : ""; ?>>

                                    Semua Status

                                </option>

                                <option value="Pesanan Masuk" <?= $status == "Pesanan Masuk" ? "selected" : ""; ?>>

                                    Pesanan Masuk

                                </option>

                                <option value="Diproses" <?= $status == "Diproses" ? "selected" : ""; ?>>

                                    Diproses

                                </option>

                                <option value="Sedang Diantar" <?= $status == "Sedang Diantar" ? "selected" : ""; ?>>

                                    Sedang Diantar

                                </option>

                                <option value="Selesai" <?= $status == "Selesai" ? "selected" : ""; ?>>

                                    Selesai

                                </option>

                                <option value="Menunggu Pembatalan" <?= $status == "Menunggu Pembatalan" ? "selected" : ""; ?>>

                                    Menunggu Pembatalan

                                </option>

                                <option value="Dibatalkan" <?= $status == "Dibatalkan" ? "selected" : ""; ?>>

                                    Dibatalkan

                                </option>

                            </select>

                        </div>

                        <!-- BUTTON -->

                        <div class="col-lg-3">

                            <button type="submit" class="btn btn-bloom w-100">

                                <i class="bi bi-funnel me-2"></i>

                                Filter

                            </button>

                        </div>

                    </div>

                </form>

            </div>

            <!-- ==========================
RINGKASAN LAPORAN
========================== -->

            <div class="row g-4 mb-4">

                <div class="col-lg-4">

                    <div class="mini-card">

                        <i class="bi bi-receipt"></i>

                        <div>

                            <span>Total Pesanan</span>

                            <h3>

                                <?= $total_pesanan['total']; ?>

                            </h3>

                        </div>

                    </div>

                </div>

                <div class="col-lg-4">

                    <div class="mini-card">

                        <i class="bi bi-wallet2"></i>

                        <div>

                            <span>Total Pendapatan</span>

                            <h3>

                                Rp <?= number_format($total_pendapatan['total'], 0, ',', '.'); ?>

                            </h3>

                        </div>

                    </div>

                </div>

                <div class="col-lg-4">

                    <div class="mini-card">

                        <i class="bi bi-check-circle-fill"></i>

                        <div>

                            <span>Pesanan Selesai</span>

                            <h3>

                                <?= $selesai['total']; ?>

                            </h3>

                        </div>

                    </div>

                </div>

            </div>

            <div class="row g-4 mb-4">

                <div class="col-lg-3">

                    <div class="mini-card">

                        <i class="bi bi-hourglass-split"></i>

                        <div>

                            <span>Pesanan Masuk</span>

                            <h3>

                                <?= $pesanan_masuk['total']; ?>

                            </h3>

                        </div>

                    </div>

                </div>

                <div class="col-lg-3">

                    <div class="mini-card">

                        <i class="bi bi-gear-fill"></i>

                        <div>

                            <span>Diproses</span>

                            <h3>

                                <?= $diproses['total']; ?>

                            </h3>

                        </div>

                    </div>

                </div>

                <div class="col-lg-3">

                    <div class="mini-card">

                        <i class="bi bi-clock-history"></i>

                        <div>

                            <span>Menunggu</span>

                            <h3>

                                <?= $menunggu['total']; ?>

                            </h3>

                        </div>

                    </div>

                </div>

                <div class="col-lg-3">

                    <div class="mini-card">

                        <i class="bi bi-x-circle-fill"></i>

                        <div>

                            <span>Dibatalkan</span>

                            <h3>

                                <?= $dibatalkan['total']; ?>

                            </h3>

                        </div>

                    </div>

                </div>

            </div>

            <!-- ==========================
DATA TRANSAKSI
========================== -->

            <div class="form-admin-card">

                <h4 class="mb-4">

                    Daftar Transaksi

                </h4>

                <div class="table-responsive">

                    <table class="table table-hover align-middle">

                        <thead>

                            <tr>

                                <th>ID</th>

                                <th>Tanggal</th>

                                <th>Pemesan</th>

                                <th>Produk</th>

                                <th>Total</th>

                                <th>Sumber</th>

                                <th>Status</th>

                            </tr>

                        </thead>

                        <tbody>

                            <?php

                            if (mysqli_num_rows($query) > 0) {

                                while ($data = mysqli_fetch_assoc($query)) {

                                    ?>

                                    <tr>

                                        <td>

                                            #TRX<?= str_pad($data['id_transaksi'], 4, "0", STR_PAD_LEFT); ?>

                                        </td>

                                        <td>

                                            <?= date('d M Y', strtotime($data['tanggal'])); ?>

                                        </td>

                                        <td>

                                            <div class="fw-semibold">

                                                <?= $data['nama_pemesan']; ?>

                                            </div>

                                            <small class="text-muted">

                                                <?= $data['no_hp']; ?>

                                            </small>

                                        </td>

                                        <td>

                                            <div class="fw-semibold">

                                                <?= $data['nama_produk']; ?>

                                            </div>

                                            <small class="text-muted">

                                                <?= $data['ukuran']; ?>

                                                •

                                                <?= $data['jumlah']; ?> pcs

                                            </small>

                                        </td>

                                        <td class="text-bloom">

                                            <strong>

                                                Rp <?= number_format($data['total_harga'], 0, ',', '.'); ?>

                                            </strong>

                                        </td>

                                        <td>

                                            <?php

                                            if ($data['sumber'] == "Online") {

                                                ?>

                                                <span class="badge bg-info">

                                                    Online

                                                </span>

                                                <?php

                                            } else {

                                                ?>

                                                <span class="badge bg-secondary">

                                                    Offline

                                                </span>

                                            <?php } ?>

                                        </td>

                                        <td>

                                            <?php

                                            if ($data['status'] == "Pesanan Masuk") {

                                                ?>

                                                <span class="badge bg-warning text-dark">

                                                    Pesanan Masuk

                                                </span>

                                                <?php

                                            } elseif ($data['status'] == "Diproses") {

                                                ?>

                                                <span class="badge bg-primary">

                                                    Diproses

                                                </span>

                                                <?php

                                            } elseif ($data['status'] == "Sedang Diantar") {

                                                ?>

                                                <span class="badge bg-info">

                                                    Sedang Diantar

                                                </span>

                                                <?php

                                            } elseif ($data['status'] == "Selesai") {

                                                ?>

                                                <span class="badge bg-success">

                                                    Selesai

                                                </span>

                                                <?php

                                            } elseif ($data['status'] == "Menunggu Pembatalan") {

                                                ?>

                                                <span class="badge bg-dark">

                                                    Menunggu Pembatalan

                                                </span>

                                                <?php

                                            } else {

                                                ?>

                                                <span class="badge bg-danger">

                                                    Dibatalkan

                                                </span>

                                            <?php } ?>

                                        </td>

                                    </tr>

                                    <?php

                                }

                            } else {

                                ?>

                                <tr>

                                    <td colspan="7" class="text-center py-5">

                                        <i class="bi bi-inbox fs-1 text-muted"></i>

                                        <p class="mt-3 mb-0">

                                            Belum ada data transaksi.

                                        </p>

                                    </td>

                                </tr>

                            <?php } ?>

                        </tbody>

                    </table>

                </div>
            </div>

        </main>

    </div>

</body>

</html>