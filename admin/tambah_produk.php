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

// Ambil data kategori
$query_kategori = mysqli_query($koneksi,"
SELECT *
FROM kategori
ORDER BY nama_kategori ASC
");

// Simpan Produk
if(isset($_POST['simpan'])){

    $id_kategori  = $_POST['id_kategori'];
    $nama_produk = mysqli_real_escape_string($koneksi,$_POST['nama_produk']);

    $harga_small  = $_POST['harga_small'];
    $harga_medium = $_POST['harga_medium'];
    $harga_large  = $_POST['harga_large'];

    $stok_small   = $_POST['stok_small'];
    $stok_medium  = $_POST['stok_medium'];
    $stok_large   = $_POST['stok_large'];

    $deskripsi = mysqli_real_escape_string($koneksi,$_POST['deskripsi']);

    // Upload gambar
    $tmp_file = $_FILES['gambar']['tmp_name'];

    $ext = strtolower(pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION));

    $nama_file = strtolower($nama_produk);

    $nama_file = str_replace(" ", "-", $nama_file);

    $nama_file = str_replace("'", "", $nama_file);

    $nama_file = str_replace("&", "dan", $nama_file);

    $nama_file = preg_replace("/[^a-z0-9\-]/", "", $nama_file);

    $nama_file .= ".".$ext;

    // Folder penyimpanan
    $folder = "../assets/img/";

    // Upload
    if(!move_uploaded_file($tmp_file, $folder.$nama_file)){

        echo "<script>
        alert('Upload gambar gagal!');
        history.back();
        </script>";

        exit();

    }

    $sql = "INSERT INTO produk
    (
        id_kategori,
        nama_produk,
        gambar,
        harga_small,
        harga_medium,
        harga_large,
        stok_small,
        stok_medium,
        stok_large,
        deskripsi
    )
    VALUES
    (
        '$id_kategori',
        '$nama_produk',
        '$nama_file',
        '$harga_small',
        '$harga_medium',
        '$harga_large',
        '$stok_small',
        '$stok_medium',
        '$stok_large',
        '$deskripsi'
    )";

    $query = mysqli_query($koneksi,$sql);

    if($query){

        echo "<script>

        alert('Produk berhasil ditambahkan');

        window.location='produk.php';

        </script>";

        exit();

    }else{

        echo "<script>

        alert('Produk gagal ditambahkan');

        </script>";

    }

}
?>

<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1">

<title>Tambah Produk | Bloomify</title>

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

    <h2>Tambah Produk</h2>

    <p>

    Tambahkan bouquet baru ke katalog Bloomify.

    </p>

    </div>

    <a
    href="produk.php"
    class="btn btn-outline-bloom">

    <i class="bi bi-arrow-left me-2"></i>

    Kembali

    </a>

    </div>

    <div class="form-admin-card">

    <form
    method="POST"
    enctype="multipart/form-data">

    <div class="row">

        <!-- Nama Produk -->

<div class="col-lg-6 mb-4">

    <label class="form-label">

        Nama Produk

    </label>

    <input
    type="text"
    name="nama_produk"
    class="form-control"
    placeholder="Masukkan nama bouquet"
    required>

</div>

<!-- Kategori -->

<div class="col-lg-6 mb-4">

    <label class="form-label">

        Kategori

    </label>

    <select
    name="id_kategori"
    class="form-select"
    required>

        <option value="">

            -- Pilih Kategori --

        </option>

        <?php while($kategori=mysqli_fetch_assoc($query_kategori)){ ?>

        <option
        value="<?= $kategori['id_kategori']; ?>">

            <?= $kategori['nama_kategori']; ?>

        </option>

        <?php } ?>

    </select>

</div>

<!-- Upload -->

<div class="col-lg-12 mb-4">

    <label class="form-label">

        Gambar Produk

    </label>

    <input
    type="file"
    name="gambar"
    class="form-control"
    accept="image/*"
    id="gambar"
    required>

    <div class="preview-image mt-3">

    <img
    id="preview"
    src="../assets/img/no-image.png">

    </div>

</div>

<!-- Harga -->

<div class="col-lg-4 mb-4">

    <label class="form-label">

        Harga Small

    </label>

    <input
    type="number"
    name="harga_small"
    class="form-control"
    placeholder="Rp"
    required>

</div>

<div class="col-lg-4 mb-4">

    <label class="form-label">

        Harga Medium

    </label>

    <input
    type="number"
    name="harga_medium"
    class="form-control"
    placeholder="Rp"
    required>

</div>

<div class="col-lg-4 mb-4">

    <label class="form-label">

        Harga Large

    </label>

    <input
    type="number"
    name="harga_large"
    class="form-control"
    placeholder="Rp"
    required>

</div>

<!-- STOK -->

<div class="col-lg-4 mb-4">

    <label class="form-label">

        Stok Small

    </label>

    <input
    type="number"
    name="stok_small"
    class="form-control"
    required>

</div>

<div class="col-lg-4 mb-4">

    <label class="form-label">

        Stok Medium

    </label>

    <input
    type="number"
    name="stok_medium"
    class="form-control"
    required>

</div>

<div class="col-lg-4 mb-4">

    <label class="form-label">

        Stok Large

    </label>

    <input
    type="number"
    name="stok_large"
    class="form-control"
    required>

</div>

<!-- Deskripsi -->

<div class="col-lg-12 mb-4">

    <label class="form-label">

        Deskripsi

    </label>

    <textarea
    name="deskripsi"
    rows="5"
    class="form-control"
    placeholder="Masukkan deskripsi bouquet..."></textarea>

</div>

<!-- BUTTON -->

<div class="col-lg-12">

    <div class="d-flex gap-3">

        <button
        type="submit"
        name="simpan"
        class="btn btn-bloom">

            <i class="bi bi-check-circle me-2"></i>

            Simpan Produk

        </button>

        <button
        type="reset"
        class="btn btn-outline-secondary">

            Reset

        </button>

        <a
        href="produk.php"
        class="btn btn-outline-bloom">

            Batal

        </a>

    </div>

</div>

</div>

</form>

</div>

</main>

</div>

    <script>

    gambar.onchange = evt =>{

    const[file]=gambar.files;

    if(file){

    preview.src=URL.createObjectURL(file);

    }

    }

    </script>

</body>
</html>