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

// Tambah kategori
if(isset($_POST['tambah'])){

    $nama_kategori = trim($_POST['nama_kategori']);

    if($nama_kategori != ""){

        mysqli_query($koneksi,"
        INSERT INTO kategori(nama_kategori)
        VALUES('$nama_kategori')
        ");

        header("Location:kategori.php");
        exit();

    }

}

// Statistik
$totalKategori = mysqli_fetch_assoc(
mysqli_query($koneksi,"
SELECT COUNT(*) AS total
FROM kategori
"));

// Data kategori
$query = mysqli_query($koneksi,"
SELECT *
FROM kategori
ORDER BY id_kategori DESC
");
?>

<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1">

<title>Kelola Kategori</title>

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

    <a href="kategori.php" class="active">

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

    <main class="content">

    <div class="topbar">

    <div>

    <h2>Kelola Kategori</h2>

    <p>

    Kelola kategori bouquet Bloomify.

    </p>

    </div>

    </div>

    <div class="row mb-4">

    <div class="col-lg-4">

    <div class="mini-card">

    <i class="bi bi-tags"></i>

    <div>

    <span>Total Kategori</span>

    <h3>

    <?= $totalKategori['total']; ?>

    </h3>

    </div>

    </div>

    </div>

    </div>

    <div class="form-admin-card">

    <form method="POST">

    <div class="row align-items-end">
        <!-- INPUT -->

        <div class="col-lg-9">

        <label class="form-label">

        Nama Kategori

        </label>

        <input
        type="text"
        name="nama_kategori"
        class="form-control"
        placeholder="Contoh : Graduation Bouquet"
        required>

        </div>

        <!-- BUTTON -->

        <div class="col-lg-3">

        <button
        type="submit"
        name="tambah"
        class="btn btn-bloom w-100">

        <i class="bi bi-plus-circle me-2"></i>

        Tambah Kategori

        </button>

        </div>

        </div>

        </form>

        </div>

        <!-- SEARCH -->

        <div class="product-toolbar mt-4">

        <div class="search-product">

        <i class="bi bi-search"></i>

        <input
        type="text"
        id="searchKategori"
        placeholder="Cari kategori...">

        </div>

        </div>

        <!-- LIST KATEGORI -->

        <div class="row g-4">

        <?php

        if(mysqli_num_rows($query)>0){

        while($data=mysqli_fetch_assoc($query)){

        ?>

        <div class="col-lg-4 kategori-item">

        <div class="kategori-card">

        <div class="kategori-icon">

        <i class="bi bi-tags-fill"></i>

        </div>

        <h4>

        <?= $data['nama_kategori']; ?>

        </h4>

        <p>

        ID Kategori :
        <?= $data['id_kategori']; ?>

        </p>

        <div class="kategori-action">

        <button
            class="btn btn-outline-bloom"

            data-bs-toggle="modal"

            data-bs-target="#edit<?= $data['id_kategori']; ?>">

            <i class="bi bi-pencil-square"></i>

            Edit

        </button>

        <a
        href="hapus_kategori.php?id=<?= $data['id_kategori']; ?>"
        onclick="return confirm('Yakin ingin menghapus kategori ini?')"
        class="btn btn-danger">

        <i class="bi bi-trash"></i>

        Hapus

        </a>

        </div>

        </div>

        </div>

        <!-- Modal Edit -->

        <div
        class="modal fade"
        id="edit<?= $data['id_kategori']; ?>"
        tabindex="-1">

        <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">

        <div class="modal-header flex-column text-center position-relative">

        <button
        type="button"
        class="btn-close position-absolute top-0 end-0 m-3"
        data-bs-dismiss="modal"></button>

        <div class="modal-icon">

        <i class="bi bi-tags-fill"></i>

        </div>

        <h3 class="modal-title">

        Edit Kategori

        </h3>

        <p class="modal-subtitle">

        Perbarui nama kategori bouquet Bloomify.

        </p>

        </div>

        <form
        action="edit_kategori.php?id=<?= $data['id_kategori']; ?>"
        method="POST">

        <div class="modal-body">

        <label class="form-label">

        Nama Kategori

        </label>

        <input
        type="text"
        name="nama_kategori"
        class="form-control"

        value="<?= $data['nama_kategori']; ?>"

        required>

        </div>

        <div class="modal-footer justify-content-end gap-2">

        <button
        type="button"
        class="btn btn-light"

        data-bs-dismiss="modal">

        Batal

        </button>

        <button
        type="submit"
        name="update"
        class="btn btn-bloom">

        Simpan

        </button>

        </div>

        </form>

        </div>

        </div>

        </div>

        <?php

        }

        }else{

        ?>

        <div class="col-12">

        <div class="empty-product">

        <i class="bi bi-tags"></i>

        <h3>

        Belum Ada Kategori

        </h3>

        <p>

        Silakan tambahkan kategori pertama.

        </p>

        </div>

        </div>

        <?php } ?>

        </div>

        </main>

</div>

<script>

// ==========================
// Live Search
// ==========================

const searchKategori = document.getElementById("searchKategori");

searchKategori.addEventListener("keyup",function(){

    let keyword = this.value.toLowerCase();

    let kategori = document.querySelectorAll(".kategori-item");

    kategori.forEach(function(item){

        let isi = item.innerText.toLowerCase();

        if(isi.indexOf(keyword) > -1){

            item.style.display = "";

        }else{

            item.style.display = "none";

        }

    });

});

</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>