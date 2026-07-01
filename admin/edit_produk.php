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

$id = $_GET['id'];

// Ambil data kategori
$query_kategori = mysqli_query($koneksi, "
SELECT *
FROM kategori
ORDER BY nama_kategori ASC
");

// Ambil data produk
$query = mysqli_query($koneksi, "
SELECT *
FROM produk
WHERE id_produk='$id'
");

$data = mysqli_fetch_assoc($query);

if (!$data) {

    echo "<script>

    alert('Produk tidak ditemukan');

    window.location='produk.php';

    </script>";

    exit();

}

// Update Produk
if (isset($_POST['update'])) {

    $id_kategori = $_POST['id_kategori'];
    $nama_produk = $_POST['nama_produk'];

    $harga_small = $_POST['harga_small'];
    $harga_medium = $_POST['harga_medium'];
    $harga_large = $_POST['harga_large'];

    $stok_small = $_POST['stok_small'];
    $stok_medium = $_POST['stok_medium'];
    $stok_large = $_POST['stok_large'];

    $deskripsi = $_POST['deskripsi'];

    // gambar lama
    $gambar = $data['gambar'];

    // jika upload gambar baru
    if ($_FILES['gambar']['name'] != "") {

        $gambar = $_FILES['gambar']['name'];
        $tmp = $_FILES['gambar']['tmp_name'];

        move_uploaded_file(
            $tmp,
            "../images/" . $gambar
        );

    }

    $sql = "UPDATE produk
            SET
            id_kategori='$id_kategori',
            nama_produk='$nama_produk',
            gambar='$gambar',
            harga_small='$harga_small',
            harga_medium='$harga_medium',
            harga_large='$harga_large',
            stok_small='$stok_small',
            stok_medium='$stok_medium',
            stok_large='$stok_large',
            deskripsi='$deskripsi'
            WHERE id_produk='$id'";

    $update = mysqli_query($koneksi, $sql);

    if ($update) {
        echo "<script>
        alert('Produk berhasil diperbarui');
        window.location='produk.php';
        </script>";
        exit();
    } else {

        echo "<script>
        alert('Produk gagal diperbarui');
        </script>";
    }

}
?>
<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Produk | Bloomify</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600;700&family=Poppins:wght@300;400;500;600&display=swap"
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
                    <h2>Edit Produk</h2>
                    <p>
                        Perbarui informasi bouquet Bloomify.
                    </p>
                </div>

                <a href="produk.php" class="btn btn-outline-bloom">
                    <i class="bi bi-arrow-left me-2"></i>
                    Kembali
                </a>
            </div>

            <div class="form-admin-card">
                <form method="POST" enctype="multipart/form-data">
                    <div class="row">

                        <!-- Nama -->
                        <div class="col-lg-6 mb-4">
                            <label class="form-label">
                                Nama Produk
                            </label>
                            <input type="text" name="nama_produk" class="form-control"
                                value="<?= $data['nama_produk']; ?>" required>
                        </div>

                        <!-- Kategori -->
                        <div class="col-lg-6 mb-4">
                            <label class="form-label">
                                Kategori
                            </label>

                            <select name="id_kategori" class="form-select" required>
                                <?php while ($kategori = mysqli_fetch_assoc($query_kategori)) { ?>
                                    <option value="<?= $kategori['id_kategori']; ?>"
                                        <?= $kategori['id_kategori'] == $data['id_kategori'] ? "selected" : ""; ?>>
                                        <?= $kategori['nama_kategori']; ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>

                        <!-- Gambar -->
                        <div class="col-lg-12 mb-4">
                            <label class="form-label">
                                Gambar Produk
                            </label>

                            <input type="file" name="gambar" class="form-control" accept="image/*" id="gambar">
                            <div class="preview-image mt-3">
                                <img id="preview" src="../images/<?= $data['gambar']; ?>">
                            </div>
                        </div>

                        <!-- Harga -->
                        <div class="col-lg-4 mb-4">
                            <label class="form-label">
                                Harga Small
                            </label>

                            <input type="number" name="harga_small" class="form-control"
                                value="<?= $data['harga_small']; ?>" required>
                        </div>

                        <div class="col-lg-4 mb-4">
                            <label class="form-label">
                                Harga Medium
                            </label>

                            <input type="number" name="harga_medium" class="form-control"
                                value="<?= $data['harga_medium']; ?>" required>
                        </div>

                        <div class="col-lg-4 mb-4">
                            <label class="form-label">
                                Harga Large
                            </label>

                            <input type="number" name="harga_large" class="form-control"
                                value="<?= $data['harga_large']; ?>" required>
                        </div>

                        <!-- STOK -->
                        <div class="col-lg-4 mb-4">
                            <label class="form-label">
                                Stok Small
                            </label>

                            <input type="number" name="stok_small" class="form-control"
                                value="<?= $data['stok_small']; ?>" required>
                        </div>

                        <div class="col-lg4 mb-4">
                            <label class="form-label">
                                Stok Medium
                            </label>

                            <input type="number" name="stok_medium" class="form-control"
                                value="<?= $data['stok_medium']; ?>" required>
                        </div>

                        <div class="col-lg-4 mb-4">
                            <label class="form-label">
                                Stok Large
                            </label>

                            <input type="number" name="stok_large" class="form-control"
                                value="<?= $data['stok_large']; ?>" required>
                        </div>

                        <!-- Deskripsi -->

                        <div class="col-lg-12 mb-4">

                            <label class="form-label">

                                Deskripsi

                            </label>

                            <textarea name="deskripsi" rows="5"
                                class="form-control"><?= $data['deskripsi']; ?></textarea>

                        </div>

                        <!-- Button -->

                        <div class="col-lg-12">

                            <div class="d-flex gap-3">

                                <button type="submit" name="update" class="btn btn-bloom">

                                    <i class="bi bi-check-circle me-2"></i>

                                    Update Produk

                                </button>

                                <button type="reset" class="btn btn-outline-secondary">

                                    Reset

                                </button>

                                <a href="produk.php" class="btn btn-outline-bloom">

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

        const gambar = document.getElementById("gambar");
        const preview = document.getElementById("preview");

        gambar.addEventListener("change", function () {

            const file = this.files[0];

            if (file) {

                preview.src = URL.createObjectURL(file);

            }

        });

    </script>

</body>

</html>