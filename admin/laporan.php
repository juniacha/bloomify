<?php
session_start();
include '../config/koneksi.php';

if(!isset($_SESSION['email'])){
    header("Location:../auth/login.php");
    exit();
}

$bulan = isset($_GET['bulan']) ? $_GET['bulan'] : date('m');
$tahun = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');
$status = isset($_GET['status']) ? $_GET['status'] : "";

$where = "WHERE MONTH(tanggal)='$bulan'
          AND YEAR(tanggal)='$tahun'";

if($status!=""){
    $where .= " AND status='$status'";
}

$sql = "SELECT transaksi.*, produk.nama_produk
        FROM transaksi
        JOIN produk
        ON transaksi.id_produk=produk.id_produk
        $where
        ORDER BY tanggal DESC";

$query = mysqli_query($koneksi,$sql);

$total_pesanan = mysqli_fetch_assoc(
mysqli_query($koneksi,"
SELECT COUNT(*) total
FROM transaksi
$where")
);

$total_pendapatan = mysqli_fetch_assoc(
mysqli_query($koneksi,"
SELECT IFNULL(SUM(total_harga),0) total
FROM transaksi
$where
AND status='Selesai'")
);

$pesanan_masuk = mysqli_fetch_assoc(
mysqli_query($koneksi,"
SELECT COUNT(*) total
FROM transaksi
$where
AND status='Pesanan Masuk'")
);

$diproses = mysqli_fetch_assoc(
mysqli_query($koneksi,"
SELECT COUNT(*) total
FROM transaksi
$where
AND status='Diproses'")
);

$selesai = mysqli_fetch_assoc(
mysqli_query($koneksi,"
SELECT COUNT(*) total
FROM transaksi
$where
AND status='Selesai'")
);

$dibatalkan = mysqli_fetch_assoc(
mysqli_query($koneksi,"
SELECT COUNT(*) total
FROM transaksi
$where
AND status='Dibatalkan'")
);

$menunggu = mysqli_fetch_assoc(
mysqli_query($koneksi,"
SELECT COUNT(*) total
FROM transaksi
$where
AND status='Menunggu Pembatalan'")
);

?>

<!DOCTYPE html>
<html>
<head>
<title>Laporan Penjualan</title>
</head>
<body>

    <h2>Laporan Penjualan</h2>

    <a href="dashboard.php">Kembali</a>
    |
    <a href="export_excel.php?bulan=<?= $bulan; ?>&tahun=<?= $tahun; ?>&status=<?= $status; ?>">Export Excel</a>

    <hr>

    <form method="GET">
        
        Bulan
        <select name="bulan">
            <?php
            for($i=1;$i<=12;$i++){
            ?>

            <option
                value="<?= sprintf("%02d",$i); ?>"
                <?= ($bulan==sprintf("%02d",$i))?"selected":"";?>>
                <?= sprintf("%02d",$i); ?>
            </option>

            <?php } ?>

        </select>

            Tahun
            <select name="tahun">
            <?php
            for($i=2024;$i<=2035;$i++) {
            ?>
            <option
                value="<?= $i;?>"
                <?= ($tahun==$i)?"selected":"";?>>

                <?= $i;?>

            </option>

            <?php } ?>

        </select>
            Status
            <select name="status">
            <option value="" <?= $status=="" ? "selected" : ""; ?>>Semua</option>

            <option value="Pesanan Masuk"
            <?= $status=="Pesanan Masuk" ? "selected" : ""; ?>>
            Pesanan Masuk
            </option>

            <option value="Diproses"
            <?= $status=="Diproses" ? "selected" : ""; ?>>
            Diproses
            </option>

            <option value="Selesai"
            <?= $status=="Selesai" ? "selected" : ""; ?>>
            Selesai
            </option>

            <option value="Menunggu Pembatalan"
            <?= $status=="Menunggu Pembatalan" ? "selected" : ""; ?>>
            Menunggu Pembatalan
            </option>

            <option value="Dibatalkan"
            <?= $status=="Dibatalkan" ? "selected" : ""; ?>>
            Dibatalkan
            </option>
        </select>

        <input type="submit" value="Filter">

    </form>

    <hr>

    <h3>Ringkasan Laporan</h3>

    <table border="1" cellpadding="10">
        <tr>
            <td>Total Pesanan</td>
            <td><b><?= $total_pesanan['total']; ?></b></td>
        </tr>
        <tr>
            <td>Pesanan Masuk</td>
            <td><b><?= $pesanan_masuk['total']; ?></b></td>
        </tr>
        <tr>
            <td>Diproses</td>
            <td><b><?= $diproses['total']; ?></b></td>
        </tr>
        <tr>
            <td>Menunggu Pembatalan</td>
            <td><b><?= $menunggu['total']; ?></b></td>
        </tr>
        <tr>
            <td>Dibatalkan</td>
            <td><b><?= $dibatalkan['total']; ?></b></td>
        </tr>
        <tr>
            <td>Selesai</td>
            <td><b><?= $selesai['total']; ?></b></td>
        </tr>
        <tr>
            <td>Total Pendapatan</td>
            <td>
                <b>
                    Rp <?= number_format($total_pendapatan['total'],0,',','.'); ?>
                </b>
            </td>
        </tr>
    </table>

    <br>

    <h3>Data Transaksi</h3>

    <table border="1" cellpadding="10">

        <tr>
            <th>ID</th>
            <th>Tanggal</th>
            <th>Pemesan</th>
            <th>No HP</th>
            <th>Produk</th>
            <th>Ukuran</th>
            <th>Jumlah</th>
            <th>Total</th>
            <th>Sumber</th>
            <th>Status</th>
        </tr>

        <?php
        if(mysqli_num_rows($query)>0){
        while($data=mysqli_fetch_assoc($query)){
        ?>

        <tr>
            <td><?= $data['id_transaksi']; ?></td>
            <td><?= date('d-m-Y',strtotime($data['tanggal'])); ?></td>
            <td><?= $data['nama_pemesan']; ?></td>
            <td><?= $data['no_hp']; ?></td>
            <td><?= $data['nama_produk']; ?></td>
            <td><?= $data['ukuran']; ?></td>
            <td><?= $data['jumlah']; ?></td>
            <td>
                Rp <?= number_format($data['total_harga'],0,',','.'); ?>
            </td>
            <td><?= $data['sumber']; ?></td>
            <td><?= $data['status']; ?></td>
        </tr>
        <?php } ?>

        <?php
        }else{
        ?>
        <tr>
            <td colspan="10" align="center">
                Data tidak ditemukan
            </td>
        </tr>
        <?php } ?>
    </table>
</body>
</html>