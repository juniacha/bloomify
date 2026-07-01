<?php
session_start();
include '../config/koneksi.php';

if (!isset($_SESSION['email'])) {
    header("Location:../auth/login.php");
    exit();
}

$id = $_GET['id'];
$id_user = $_SESSION['id_user'];

$sql = "SELECT transaksi.*, produk.nama_produk, produk.gambar
        FROM transaksi
        JOIN produk
        ON transaksi.id_produk = produk.id_produk
        WHERE transaksi.id_transaksi='$id'
        AND transaksi.id_user='$id_user'";

$query = mysqli_query($koneksi, $sql);

if (mysqli_num_rows($query) == 0) {
    echo "Pesanan tidak ditemukan.";
    exit();
}

$data = mysqli_fetch_assoc($query);
?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Detail Pesanan | Bloomify</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="../assets/css/style.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link
        href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600;700&family=Poppins:wght@300;400;500;600&display=swap"
        rel="stylesheet">

</head>

<body>

    <nav class="navbar navbar-expand-lg sticky-top">

        <div class="container">

            <a class="navbar-brand" href="index.php">

                <i class="bi bi-flower1 me-2"></i>

                Bloomify

            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarBloomify">

                <span class="navbar-toggler-icon"></span>

            </button>

            <div class="collapse navbar-collapse" id="navbarBloomify">

                <ul class="navbar-nav mx-auto">

                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="produk.php">Produk</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link active" href="pesanan_saya.php">Riwayat Pesanan</a>
                    </li>

                </ul>

                <div class="d-flex align-items-center gap-3">

                    <a href="keranjang.php" class="nav-cart">
                        <i class="bi bi-bag"></i>
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

            <a href="pesanan_saya.php" class="back-link">

                <i class="bi bi-arrow-left me-2"></i>

                Kembali

            </a>

            <span class="section-subtitle d-block mt-4">

                DETAIL ORDER

            </span>

            <h1 class="mt-3">

                Detail Pesanan

            </h1>

            <p class="text-secondary">

                Lihat detail lengkap pesanan bouquet-mu.

            </p>

        </div>

    </section>


    <div class="container py-5">

        <div class="order-card">

            <div class="row g-5 align-items-start">

                <div class="col-lg-5">

                    <img src="../assets/img/<?= $data['gambar']; ?>" class="img-fluid rounded-4 shadow-sm detail-img">

                </div>

                <div class="col-lg-7">

                    <?php
                    if ($data['status'] == "Pesanan Masuk") {

                        $badge = "warning";

                    } elseif ($data['status'] == "Diproses") {

                        $badge = "info";

                    } elseif ($data['status'] == "Sedang Diantar") {

                        $badge = "primary";

                    } elseif ($data['status'] == "Selesai") {

                        $badge = "success";

                    } else {

                        $badge = "secondary";

                    }
                    ?>

                    <span class="badge bg-<?= $badge; ?> px-3 py-2">

                        <?= $data['status']; ?>

                    </span>

                    <h2 class="mt-3">

                        <?= $data['nama_produk']; ?>

                    </h2>

                    <p class="text-secondary mb-4">

                        Bouquet pilihan spesial untuk momen berhargamu.

                    </p>

                    <div class="row mb-4">

                        <div class="col-4">

                            <small class="text-secondary">

                                Ukuran

                            </small>

                            <h6>

                                <?= $data['ukuran']; ?>

                            </h6>

                        </div>

                        <div class="col-4">

                            <small class="text-secondary">

                                Jumlah

                            </small>

                            <h6>

                                <?= $data['jumlah']; ?>

                            </h6>

                        </div>

                        <div class="col-4">

                            <small class="text-secondary">

                                Total

                            </small>

                            <h5 class="text-primary">

                                Rp <?= number_format($data['total_harga'], 0, ",", "."); ?>

                            </h5>

                        </div>

                    </div>

                    <hr>

                    <h5 class="mb-3">

                        <i class="bi bi-gift me-2"></i>

                        Custom Bouquet

                    </h5>

                    <div class="row g-3 mb-4">

                        <div class="col-md-4">

                            <div class="checkout-info-box text-center">

                                🧸 Boneka

                                <br>

                                <strong>

                                    <?= $data['boneka'] ? "Ya" : "Tidak"; ?>

                                </strong>

                            </div>

                        </div>

                        <div class="col-md-4">

                            <div class="checkout-info-box text-center">

                                🎈 Balon

                                <br>

                                <strong>

                                    <?= $data['balon'] ? "Ya" : "Tidak"; ?>

                                </strong>

                            </div>

                        </div>

                        <div class="col-md-4">

                            <div class="checkout-info-box text-center">

                                💌 Kartu

                                <br>

                                <strong>

                                    <?= $data['kartu_ucapan'] ? "Ya" : "Tidak"; ?>

                                </strong>

                            </div>

                        </div>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">

                            Warna Buket

                        </label>

                        <div class="form-control">

                            <?= !empty($data['warna_buket']) ? htmlspecialchars($data['warna_buket']) : "-"; ?>

                        </div>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">

                            Isi Surat

                        </label>

                        <div class="form-control" style="min-height:90px;">

                            <?= !empty($data['isi_surat']) ? nl2br(htmlspecialchars($data['isi_surat'])) : "-"; ?>

                        </div>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">

                            Catatan Tambahan

                        </label>

                        <div class="form-control" style="min-height:90px;">

                            <?= !empty($data['catatan']) ? nl2br(htmlspecialchars($data['catatan'])) : "-"; ?>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <div class="order-card mt-4">

            <h4 class="mb-4">

                <i class="bi bi-truck me-2"></i>

                Informasi Pengiriman

            </h4>

            <div class="row g-4">


                <div class="col-md-6">

                    <label class="form-label">

                        <i class="bi bi-telephone me-2"></i>

                        Nomor HP

                    </label>

                    <div class="form-control">

                        <?= htmlspecialchars($data['no_hp']); ?>

                    </div>

                </div>

                <div class="col-md-6">

                    <label class="form-label">

                        <i class="bi bi-truck me-2"></i>

                        Metode Pengiriman

                    </label>

                    <div class="form-control">

                        <?= htmlspecialchars($data['metode_pengiriman']); ?>

                    </div>

                </div>

                <div class="col-md-6">

                    <label class="form-label">

                        <i class="bi bi-credit-card me-2"></i>

                        Metode Pembayaran

                    </label>

                    <div class="form-control">

                        <?= !empty($data['metode_pembayaran']) ? htmlspecialchars($data['metode_pembayaran']) : '-'; ?>

                    </div>

                </div>

                <div class="col-12">

                    <label class="form-label">

                        <i class="bi bi-geo-alt me-2"></i>

                        Alamat Pengiriman

                    </label>

                    <div class="form-control" style="min-height:90px;">

                        <?php
                        if ($data['metode_pengiriman'] == "Ambil di Toko") {

                            echo "Pesanan diambil langsung di toko.";

                        } else {

                            echo !empty($data['alamat'])
                                ? nl2br(htmlspecialchars($data['alamat']))
                                : "-";

                        }
                        ?>
                    </div>
                </div>

            </div>

        </div>

        <div class="text-end mt-4">

            <a href="pesanan_saya.php" class="btn btn-outline-bloom me-2">

                <i class="bi bi-arrow-left me-2"></i>

                Kembali

            </a>

        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>