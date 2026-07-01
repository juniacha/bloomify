<?php
session_start();
include '../config/koneksi.php';

if (!isset($_SESSION['email'])) {
    header("Location:../auth/login.php");
    exit();
}

$id = $_GET['id'];

$sql = "SELECT transaksi.*, produk.nama_produk
        FROM transaksi
        JOIN produk
        ON transaksi.id_produk = produk.id_produk
        WHERE transaksi.id_transaksi='$id'";

$query = mysqli_query($koneksi, $sql);
$data = mysqli_fetch_assoc($query);

if (isset($_POST['update'])) {

    $status = $_POST['status'];

    if ($data['status'] == "Selesai") {

        echo "<script>
                alert('Pesanan yang sudah selesai tidak dapat diubah lagi.');
                window.location='transaksi.php';
            </script>";

        exit();

    }

    // Validasi pembatalan
    if ($status == "Dibatalkan" && $data['status'] != "Menunggu Pembatalan") {

        echo "<script>
                alert('Pesanan ini belum mengajukan pembatalan');
                window.location='transaksi.php';
              </script>";
        exit();

    }

    $update = "UPDATE transaksi
               SET status='$status'
               WHERE id_transaksi='$id'";

    mysqli_query($koneksi, $update);

    // Kembalikan stok jika pembatalan disetujui
    if ($status == "Dibatalkan" && $data['status'] != "Dibatalkan") {

        if ($data['ukuran'] == "Small") {

            mysqli_query($koneksi, "
            UPDATE produk
            SET stok_small = stok_small + " . $data['jumlah'] . "
            WHERE id_produk='" . $data['id_produk'] . "'
            ");

        } elseif ($data['ukuran'] == "Medium") {

            mysqli_query($koneksi, "
            UPDATE produk
            SET stok_medium = stok_medium + " . $data['jumlah'] . "
            WHERE id_produk='" . $data['id_produk'] . "'
            ");

        } else {

            mysqli_query($koneksi, "
            UPDATE produk
            SET stok_large = stok_large + " . $data['jumlah'] . "
            WHERE id_produk='" . $data['id_produk'] . "'
            ");

        }

    }

    echo "<script>
            alert('Status berhasil diperbarui');
            window.location='transaksi.php';
          </script>";
    exit();

}
?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Update Status | Bloomify</title>

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

            <span class="menu-text">MAIN MENU</span>

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

            <div class="d-flex justify-content-between align-items-center mb-4">

                <div>

                    <h1 class="form-title mb-2">
                        Update Status Pesanan
                    </h1>

                    <p class="text-muted">
                        Perbarui status pesanan customer.
                    </p>

                </div>

                <a href="transaksi.php" class="btn btn-outline-bloom">

                    <i class="bi bi-arrow-left me-2"></i>

                    Kembali

                </a>

            </div>

            <div class="card border-0 shadow-sm rounded-4 p-4">

                <table class="table align-middle">

                    <tr>

                        <th width="220">Nama Pemesan</th>

                        <td><?= htmlspecialchars($data['nama_pemesan']); ?></td>

                    </tr>

                    <tr>

                        <th>Produk</th>

                        <td><?= htmlspecialchars($data['nama_produk']); ?></td>

                    </tr>

                    <tr>

                        <th>Ukuran</th>

                        <td><?= $data['ukuran']; ?></td>

                    </tr>

                    <tr>

                        <th>Jumlah</th>

                        <td><?= $data['jumlah']; ?></td>

                    </tr>

                    <tr>

                        <th>Total Harga</th>

                        <td class="text-bloom">

                            Rp <?= number_format($data['total_harga'], 0, ',', '.'); ?>

                        </td>

                    </tr>

                    <tr>

                        <th>Status Saat Ini</th>

                        <td>

                            <?php

                            if ($data['status'] == "Pesanan Masuk") {

                                $badge = "warning text-dark";

                            } elseif ($data['status'] == "Diproses") {

                                $badge = "info";

                            } elseif ($data['status'] == "Sedang Diantar") {

                                $badge = "primary";

                            } elseif ($data['status'] == "Selesai") {

                                $badge = "success";

                            } else {

                                $badge = "danger";

                            }

                            ?>

                            <span class="badge bg-<?= $badge; ?>">

                                <?= htmlspecialchars($data['status']); ?>

                            </span>

                        </td>

                    </tr>

                </table>

                <hr>

                <form method="POST">

                    <div class="mb-4">

                        <label class="form-label">

                            Status Baru

                        </label>

                        <select name="status" class="form-select">

                            <?php if ($data['status'] == "Selesai") { ?>

                                <option value="Selesai" selected>
                                    Selesai
                                </option>

                            <?php } elseif ($data['status'] == "Pesanan Masuk") { ?>

                                <option value="Pesanan Masuk" selected>
                                    Pesanan Masuk
                                </option>

                                <option value="Diproses">
                                    Diproses
                                </option>

                            <?php } elseif ($data['status'] == "Diproses") { ?>

                                <option value="Diproses" selected>
                                    Diproses
                                </option>

                                <option value="Sedang Diantar">
                                    Sedang Diantar
                                </option>

                            <?php } elseif ($data['status'] == "Sedang Diantar") { ?>

                                <option value="Sedang Diantar" selected>
                                    Sedang Diantar
                                </option>

                                <option value="Selesai">
                                    Selesai
                                </option>

                            <?php } elseif ($data['status'] == "Menunggu Pembatalan") { ?>

                                <option value="Dibatalkan">
                                    Setujui Pembatalan
                                </option>

                                <option value="Diproses">
                                    Tolak Pembatalan
                                </option>

                            <?php } else { ?>

                                <option value="<?= htmlspecialchars($data['status']); ?>" selected>
                                    <?= htmlspecialchars($data['status']); ?>
                                </option>

                            <?php } ?>

                        </select>

                    </div>

                    <button class="btn btn-bloom" name="update">

                        <i class="bi bi-check-circle me-2"></i>

                        Simpan Perubahan

                    </button>

                    <a href="transaksi.php" class="btn btn-outline-secondary ms-2">

                        Batal

                    </a>

                </form>

            </div>

        </main>

    </div>

</body>

</html>