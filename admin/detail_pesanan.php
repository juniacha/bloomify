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

if(!isset($_GET['id'])){

    header("Location:transaksi.php");
    exit();

}

$id = $_GET['id'];

$sql = mysqli_query($koneksi,"
SELECT transaksi.*, produk.nama_produk, produk.gambar
FROM transaksi
JOIN produk
ON transaksi.id_produk = produk.id_produk
WHERE transaksi.id_transaksi='$id'
");

$data = mysqli_fetch_assoc($sql);

if(!$data){

    header("Location:transaksi.php");
    exit();

}
?>

<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>Detail Pesanan | Bloomify</title>

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

    <!-- CONTENT -->

    <main class="content">

    <div class="topbar">

    <div>

    <h2>Detail Pesanan</h2>

    <p>

    Informasi lengkap pesanan customer.

    </p>

    </div>

    <a
    href="transaksi.php"
    class="btn btn-outline-bloom">

    <i class="bi bi-arrow-left me-2"></i>

    Kembali

    </a>

    </div>

    <div class="row g-4">

        <!-- =========================
        INFORMASI PEMESAN
        ========================== -->

        <div class="col-lg-5">

            <div class="form-admin-card">

                <h4 class="form-title">

                    <i class="bi bi-person-fill me-2"></i>

                    Informasi Pemesan

                </h4>

                <table class="table table-borderless detail-table">

                    <tr>

                        <td>Nama</td>

                        <td>: <?= $data['nama_pemesan']; ?></td>

                    </tr>

                    <tr>

                        <td>No. HP</td>

                        <td>: <?= $data['no_hp']; ?></td>

                    </tr>

                    <tr>

                        <td>Alamat</td>

                        <td>

                            :

                            <?= !empty($data['alamat']) ? nl2br($data['alamat']) : '-'; ?>

                        </td>

                    </tr>

                    <tr>

                        <td>Metode</td>

                        <td>: <?= $data['metode_pengiriman']; ?></td>

                    </tr>

                    <tr>

                        <td>Sumber</td>

                        <td>: <?= $data['sumber']; ?></td>

                    </tr>

                    <tr>

                        <td>Status</td>

                        <td>

                            :

                            <?php

                            if($data['status']=="Pesanan Masuk"){

                                echo "<span class='badge-status-masuk'>Pesanan Masuk</span>";

                            }elseif($data['status']=="Diproses"){

                                echo "<span class='badge bg-primary'>Diproses</span>";

                            }elseif($data['status']=="Sedang Diantar"){

                                echo "<span class='badge bg-info'>Sedang Diantar</span>";

                            }elseif($data['status']=="Selesai"){

                                echo "<span class='badge bg-success'>Selesai</span>";

                            }else{

                                echo "<span class='badge bg-danger'>Dibatalkan</span>";

                            }

                            ?>

                        </td>

                    </tr>

                </table>

            </div>

        </div>

        <!-- =========================
        INFORMASI PRODUK
        ========================== -->

        <div class="col-lg-7">

            <div class="form-admin-card">

                <h4 class="form-title">

                    <i class="bi bi-box-seam me-2"></i>

                    Informasi Produk

                </h4>

                <div class="row align-items-center">

                    <div class="col-lg-5 text-center">

                        <img
                        src="../assets/img/<?= $data['gambar']; ?>"
                        class="detail-image"
                        alt="<?= $data['nama_produk']; ?>">

                    </div>

                    <div class="col-lg-7">

                        <h3 class="mb-4">

                            <?= $data['nama_produk']; ?>

                        </h3>

                        <table class="table table-borderless detail-table">

                            <tr>

                                <td>Ukuran</td>

                                <td><?= $data['ukuran']; ?></td>

                            </tr>

                            <tr>

                                <td>Jumlah</td>

                                <td><?= $data['jumlah']; ?></td>

                            </tr>

                            <tr>

                                <td>Tanggal</td>

                                <td><?= date('d F Y H:i',strtotime($data['tanggal'])); ?></td>

                            </tr>

                            <tr>

                                <td>Total</td>

                                <td>

                                    <strong class="text-bloom">

                                        Rp <?= number_format($data['total_harga'],0,',','.'); ?>

                                    </strong>

                                </td>

                            </tr>

                        </table>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <!-- =========================
    CUSTOM BOUQUET
    ========================= -->

    <div class="form-admin-card mt-4">

        <h4 class="form-title">

            <i class="bi bi-gift-fill me-2"></i>

            Detail Custom Bouquet

        </h4>

        <div class="row">

            <div class="col-lg-4">

                <table class="table table-borderless detail-table">

                    <tr>

                        <td>Boneka</td>

                        <td>

                            :

                            <?= $data['boneka'] ? "✔ Ya" : "✖ Tidak"; ?>

                        </td>

                    </tr>

                    <tr>

                        <td>Balon</td>

                        <td>

                            :

                            <?= $data['balon'] ? "✔ Ya" : "✖ Tidak"; ?>

                        </td>

                    </tr>

                    <tr>

                        <td>Kartu</td>

                        <td>

                            :

                            <?= $data['kartu_ucapan'] ? "✔ Ya" : "✖ Tidak"; ?>

                        </td>

                    </tr>

                </table>

            </div>

            <div class="col-lg-8">

                <div class="mb-3">

                    <label class="form-label">

                        Warna Buket

                    </label>

                    <div class="detail-box">

                        <?= !empty($data['warna_buket']) ? $data['warna_buket'] : "-"; ?>

                    </div>

                </div>

                <div class="mb-3">

                    <label class="form-label">

                        Isi Surat

                    </label>

                    <div class="detail-box">

                        <?= !empty($data['isi_surat']) ? nl2br($data['isi_surat']) : "-"; ?>

                    </div>

                </div>

                <div>

                    <label class="form-label">

                        Catatan Tambahan

                    </label>

                    <div class="detail-box">

                        <?= !empty($data['catatan']) ? nl2br($data['catatan']) : "-"; ?>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <!-- =========================
    BUTTON
    ========================= -->

    <div class="form-admin-card mt-4">

        <div class="d-flex justify-content-end gap-3 flex-wrap">

            <a
            href="transaksi.php"
            class="btn btn-light">

                <i class="bi bi-arrow-left me-2"></i>

                Kembali

            </a>

            <a
            href="edit_status.php?id=<?= $data['id_transaksi']; ?>"
            class="btn btn-bloom">

                <i class="bi bi-arrow-repeat me-2"></i>

                Update Status

            </a>

        </div>

    </div>

    </main>

    </div>

</body>

</html>