<?php
session_start();
include '../config/koneksi.php';

if(!isset($_SESSION['email'])){
    header("Location:../auth/login.php");
    exit();
}

$id = $_GET['id'];

$sql = "SELECT produk.*, kategori.nama_kategori
        FROM produk
        JOIN kategori
        ON produk.id_kategori = kategori.id_kategori
        WHERE produk.id_produk='$id'";

$query = mysqli_query($koneksi, $sql);
$data = mysqli_fetch_array($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Detail Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
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
                        <a class="nav-link" href="produk.php">Shop</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="pesanan_saya.php">Riwayat Pesanan</a>
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

                    <a href="../auth/logout.php" class="btn btn-bloom">
                        Logout
                    </a>

                </div>

            </div>

        </div>
    </nav>

    <div class="container mt-4">

        <a href="javascript:history.back()"
        class="back-link">

            <i class="bi bi-arrow-left me-2"></i>

            Kembali

        </a>

    </div>

    <div class="container py-5">

        <div class="row g-5">

        <div class="col-lg-6">
            <img
            src="../assets/img/<?= $data['gambar'];?>"
            class="img-fluid rounded-4 shadow-lg detail-img">
        </div>

        <div class="col-lg-6">

        <span class="badge new-badge">

        <?= $data['nama_kategori']; ?>

        </span>

        <h2 class="mt-3">

        <?= $data['nama_produk']; ?>

        </h2>

        <p class="text-secondary mb-4">

        Perfect bouquet for your special moment.

        </p>

        <p class="product-description">

            <?= $data['deskripsi']; ?>

        </p>

        <div class="mt-3">

            <span class="badge bg-success">

            <?= $data['stok_small'] + $data['stok_medium'] + $data['stok_large']; ?>

            Bouquet Tersedia

            </span>

        </div><br>
   
        <h5 class="mb-3">Pilih Ukuran Bouquet</h5>

        <?php if($data['stok_small'] > 0){ ?>
        <div class="form-check border rounded-4 p-3 mb-3">
            <input
            class="form-check-input"
            type="radio"
            name="pilihUkuran"

            onclick="ubahHarga(
            <?= $data['harga_small']; ?>,
            <?= $data['stok_small']; ?>,
            'Small')">

            <label class="form-check-label ms-2">

            <b>Small</b>

            <br>

            <span class="text-secondary">

            Stok <?= $data['stok_small']; ?>

            </span>

            </label>

        </div>
        <?php }else{ ?>
        <label style="color:gray">
            <input type="radio" disabled>
            Small (Habis)
        </label>
        <?php } ?> 

        <?php if($data['stok_medium'] > 0){ ?>
        <div class="form-check border rounded-4 p-3 mb-3">

            <input
            class="form-check-input"
            type="radio"
            name="pilihUkuran"

            onclick="ubahHarga(
            <?= $data['harga_medium']; ?>,
            <?= $data['stok_medium']; ?>,
            'medium')">

            <label class="form-check-label ms-2">

            <b>Medium</b>

            <br>

            <span class="text-secondary">

            Stok <?= $data['stok_medium']; ?>

            </span>

            </label>

        </div>
        <?php }else{ ?>
        <label style="color:gray">
            <input type="radio" disabled>
            Medium (Habis)
        </label>
        <?php } ?> 

        <?php if($data['stok_large'] > 0){ ?>
        <div class="form-check border rounded-4 p-3 mb-3">
            <input
            class="form-check-input"
            type="radio"
            name="pilihUkuran"

            onclick="ubahHarga(
            <?= $data['harga_large']; ?>,
            <?= $data['stok_large']; ?>,
            'large')">

            <label class="form-check-label ms-2">

            <b>Large</b>

            <br>

            <span class="text-secondary">

            Stok <?= $data['stok_large']; ?>

            </span>

            </label>

        </div>
        <?php }else{ ?>
        <label style="color:gray">
            <input type="radio" disabled>
            Large (Habis)
        </label>
        <?php } ?>

    
        <div class="alert alert-light mt-4">

            <b>Stok :</b>

            <span id="stok">

            Pilih ukuran terlebih dahulu

            </span>

        </div>

        <div class="info-box">

            <b>Harga :</b>

            <span id="harga">

            Pilih ukuran

            </span>

        </div>

        <form method="GET">

            <input type="hidden"
                name="id_produk"
                value="<?= $data['id_produk']; ?>">

            <input type="hidden"
                name="ukuran"
                id="ukuran">

            <button

                class="btn btn-bloom w-100 mb-3"

                type="submit"

                id="btnKeranjang"

                formaction="tambah_keranjang.php"

                disabled>

                    <i class="bi bi-cart-plus me-2"></i>

                Tambah ke Keranjang

            </button>
            

            <button

                class="btn btn-outline-bloom w-100"

                type="submit"

                id="btnCheckout"

                formaction="checkout.php"

                disabled>

                    <i class="bi bi-credit-card me-2"></i>
                Checkout Sekarang

            </button>

        </form>

        <div class="text-secondary small mt-3">

            <i class="bi bi-info-circle me-2"></i>

            Silakan pilih ukuran bouquet untuk melanjutkan pemesanan.

        </div>

    </div>

</div>

</div>

        <script>

            function ubahHarga(harga, stok, ukuran){

                // Tampilkan harga
                document.getElementById("harga").innerHTML =
                "Rp " + harga.toLocaleString('id-ID');

                // Tampilkan stok
                document.getElementById("stok").innerHTML =
                stok + " tersedia";

                // Simpan ukuran yang dipilih
                document.getElementById("ukuran").value =
                ukuran;

                // Aktifkan tombol Pesan Sekarang
                document.getElementById("btnKeranjang").disabled = false;
                document.getElementById("btnCheckout").disabled = false;

            }

        </script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>