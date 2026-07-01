<?php
session_start();
include '../config/koneksi.php';

if(!isset($_SESSION['email'])){
    header("Location:../auth/login.php");
    exit();
}

$id_user = $_SESSION['id_user'];

$sql = "SELECT transaksi.*, produk.nama_produk, produk.gambar
        FROM transaksi
        JOIN produk
        ON transaksi.id_produk = produk.id_produk
        WHERE transaksi.id_user='$id_user'
        ORDER BY transaksi.id_transaksi DESC";

$query = mysqli_query($koneksi,$sql);
$total = mysqli_num_rows($query);
?>

<!DOCTYPE html>
<html>
<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1">

<title>Pesanan Saya | Bloomify</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet"
href="../assets/css/style.css">

<link rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600;700&family=Poppins:wght@300;400;500;600&display=swap"
rel="stylesheet">

</head>
<body>

    <nav class="navbar navbar-expand-lg sticky-top">

    <div class="container">

    <a class="navbar-brand"
    href="index.php">

    <i class="bi bi-flower1 me-2"></i>

    Bloomify

    </a>

    <button
    class="navbar-toggler"
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

    <a class="nav-link"
    href="produk.php">

    Produk

    </a>

    </li>

    <li class="nav-item">

    <a class="nav-link active"
    href="pesanan_saya.php">

    Riwayat Pesanan

    </a>

    </li>

    </ul>

    <div class="d-flex align-items-center gap-3">

    <a
    href="keranjang.php"
    class="nav-cart">

    <i class="bi bi-bag"></i>

    </a>

    <span>

    Halo,

    <strong>

    <?= $_SESSION['nama']; ?>

    </strong>

    </span>

    <a
    href="../auth/logout.php"
    class="btn btn-bloom">

    Logout

    </a>

    </div>

    </div>

    </div>

    </nav>


    <section
    class="py-5"
    style="background:var(--section);">

        <div class="container">
            <a href="index.php"
            class="back-link d-inline-flex mb-4">
                <i class="bi bi-arrow-left me-2"></i>
                Kembali
            </a>

            <div class="text-center">
                <span class="section-subtitle">
                    MY ORDER
                </span>

                <h1 class="mt-3">
                    Pesanan Saya
                </h1>

                <p class="text-secondary">
                    Lihat status seluruh pesanan bouquet-mu.
                </p>

            </div>
        </div>
    </section>


    <div class="container py-5">

    <div class="row g-4">

    <?php if($total > 0){ ?>

    <?php while($data=mysqli_fetch_assoc($query)){ ?>

    <div class="col-12">

        <div class="order-card">

            <div class="row align-items-center g-4">

                <div class="col-lg-3">

                    <img
                    src="../assets/img/<?= $data['gambar']; ?>"
                    class="order-image"
                    alt="<?= $data['nama_produk']; ?>">

                </div>

                <div class="col-lg-6">

                    <span class="badge category-badge">

                        #<?= $data['id_transaksi']; ?>

                    </span>

                    <h3 class="mt-3">

                        <?= $data['nama_produk']; ?>

                    </h3>

                    <div class="order-info mt-3">

                        <div>

                            <small>Ukuran</small>

                            <strong>

                                <?= $data['ukuran']; ?>

                            </strong>

                        </div>

                        <div>

                            <small>Jumlah</small>

                            <strong>

                                <?= $data['jumlah']; ?>

                            </strong>

                        </div>

                        <div>

                            <small>Total</small>

                            <strong class="text-primary">

                                Rp <?= number_format($data['total_harga'],0,",","."); ?>

                            </strong>

                        </div>

                    </div>

                </div>

                <div class="col-lg-3 text-lg-end">

                    <?php

                    if($data['status']=="Pesanan Masuk"){

                        $badge="warning";

                    }elseif($data['status']=="Diproses"){

                        $badge="info";

                    }elseif($data['status']=="Selesai"){

                        $badge="success";

                    }else{

                        $badge="secondary";

                    }

                    ?>

                    <span class="badge bg-<?= $badge; ?> px-3 py-2 mb-3">

                        <?= $data['status']; ?>

                    </span>

                    <br>

                    <a
                    href="detail_pesanan.php?id=<?= $data['id_transaksi']; ?>"
                    class="btn btn-bloom w-100 mb-2">

                        <i class="bi bi-eye me-2"></i>

                        Detail

                    </a>

                    <?php if($data['status']=="Pesanan Masuk"){ ?>

                    <a
                    href="batalkan_pesanan.php?id=<?= $data['id_transaksi']; ?>"
                    onclick="return confirm('Batalkan pesanan ini?')"
                    class="btn btn-outline-danger w-100">

                        <i class="bi bi-x-circle me-2"></i>

                        Batalkan

                    </a>

                    <?php } ?>

                </div>

            </div>

        </div>

    </div>

    <?php } ?>

    <?php }else{ ?>

    <div class="col-12">

        <div class="empty-order text-center">

            <div class="empty-icon">

                <i class="bi bi-bag-heart"></i>

            </div>

            <h3 class="mt-4">

                Belum Ada Pesanan

            </h3>

            <p class="text-secondary mb-4">

                Kamu belum memiliki pesanan bouquet.
                Yuk mulai belanja dan buat momen spesialmu
                bersama Bloomify.

            </p>

            <a
            href="produk.php"
            class="btn btn-bloom">

                <i class="bi bi-flower1 me-2"></i>

                Belanja Sekarang

            </a>

        </div>

    </div>

    <?php } ?>

    </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>