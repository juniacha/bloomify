<?php
session_start();
include '../config/koneksi.php';

if(!isset($_SESSION['email'])){
    header("Location:../auth/login.php");
    exit();
}

// ===============================
// AMBIL DATA PRODUK
// ===============================

if(isset($_POST['buat_pesanan'])){

    $id_produk = $_POST['id_produk'];
    $ukuran = $_POST['ukuran'];

}elseif(isset($_GET['id_produk']) && isset($_GET['ukuran'])){

    $id_produk = $_GET['id_produk'];
    $ukuran = $_GET['ukuran'];

}else{

    header("Location:index.php");
    exit();

}

$query = mysqli_query($koneksi,"
SELECT produk.*, kategori.nama_kategori
FROM produk
JOIN kategori
ON produk.id_kategori = kategori.id_kategori
WHERE produk.id_produk='$id_produk'
");

$data = mysqli_fetch_assoc($query);

if(!$data){
    header("Location:index.php");
    exit();
}

// ===============================
// HARGA BERDASARKAN UKURAN
// ===============================

if($ukuran=="Small"){

    $harga = $data['harga_small'];
    $stok  = $data['stok_small'];

}elseif($ukuran=="Medium"){

    $harga = $data['harga_medium'];
    $stok  = $data['stok_medium'];

}else{

    $harga = $data['harga_large'];
    $stok  = $data['stok_large'];

}

// ======================================
// BUAT PESANAN
// ======================================

if(isset($_POST['buat_pesanan'])){

    $nama_pemesan = mysqli_real_escape_string($koneksi,$_POST['nama_pemesan']);
    $no_hp = mysqli_real_escape_string($koneksi,$_POST['no_hp']);
    $jumlah = (int)$_POST['jumlah'];

    $metode_pengiriman = $_POST['metode_pengiriman'];
    $alamat = mysqli_real_escape_string($koneksi,$_POST['alamat']);

    $boneka = isset($_POST['boneka']) ? 1 : 0;
    $balon = isset($_POST['balon']) ? 1 : 0;
    $kartu_ucapan = isset($_POST['kartu_ucapan']) ? 1 : 0;

    $warna_buket = mysqli_real_escape_string($koneksi,$_POST['warna_buket']);
    $isi_surat = mysqli_real_escape_string($koneksi,$_POST['isi_surat']);
    $catatan = mysqli_real_escape_string($koneksi,$_POST['catatan']);

    if($jumlah > $stok){

        echo "<script>
        alert('Stok tidak mencukupi');
        history.back();
        </script>";
        exit();

    }

    $total = $harga * $jumlah;

    if($boneka){
        $total += 25000;
    }

    if($balon){
        $total += 15000;
    }

    if($kartu_ucapan){
        $total += 5000;
    }

    if($metode_pengiriman=="Delivery"){
        $total += 20000;
    }

    $status = "Pesanan Masuk";
    $sumber = "Online";

    $insert = mysqli_query($koneksi,"
    INSERT INTO transaksi
    (
        id_user,
        nama_pemesan,
        no_hp,
        alamat,
        metode_pengiriman,
        id_produk,
        jumlah,
        ukuran,
        boneka,
        balon,
        kartu_ucapan,
        warna_buket,
        isi_surat,
        catatan,
        total_harga,
        status,
        tanggal,
        sumber
    )
    VALUES
    (
        '".$_SESSION['id_user']."',
        '$nama_pemesan',
        '$no_hp',
        '$alamat',
        '$metode_pengiriman',
        '$id_produk',
        '$jumlah',
        '$ukuran',
        '$boneka',
        '$balon',
        '$kartu_ucapan',
        '$warna_buket',
        '$isi_surat',
        '$catatan',
        '$total',
        '$status',
        NOW(),
        '$sumber'
    )
    ");

    if($insert){

        $stokBaru = $stok - $jumlah;

        if($ukuran=="Small"){

            mysqli_query($koneksi,"
            UPDATE produk
            SET stok_small='$stokBaru'
            WHERE id_produk='$id_produk'
            ");

        }elseif($ukuran=="Medium"){

            mysqli_query($koneksi,"
            UPDATE produk
            SET stok_medium='$stokBaru'
            WHERE id_produk='$id_produk'
            ");

        }else{

            mysqli_query($koneksi,"
            UPDATE produk
            SET stok_large='$stokBaru'
            WHERE id_produk='$id_produk'
            ");

        }

        echo "<script>

        alert('Pesanan berhasil dibuat');

        window.location='pesanan_saya.php';

        </script>";

        exit();

    }else{

        echo "<script>

        alert('Pesanan gagal dibuat');

        </script>";

    }

}

?>

<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Checkout | Bloomify</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="../assets/css/style.css">

<link rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600;700&family=Poppins:wght@300;400;500;600&display=swap"
rel="stylesheet">

</head>

<body>

<nav class="navbar navbar-expand-lg sticky-top">

<div class="container">

<a class="navbar-brand" href="index.php">

<i class="bi bi-flower1 me-2"></i>

Bloomify

</a>

<div class="ms-auto">

<a href="keranjang.php" class="btn btn-outline-bloom me-2">

<i class="bi bi-bag"></i>

Keranjang

</a>

<a href="../auth/logout.php" class="btn btn-bloom">

Logout

</a>

</div>

</div>

</nav>

<div class="container checkout-page py-5">

<h2 class="form-title mb-4">

Checkout Pesanan

</h2>

<form method="POST">

<input
type="hidden"
name="id_produk"
value="<?= $id_produk; ?>">

<input
type="hidden"
name="ukuran"
value="<?= $ukuran; ?>">

<div class="row">

<div class="col-12">
    <div class="card border-0 shadow-sm rounded-4">

<div class="card-body p-4">

<h4 class="mb-4">

Produk Dipilih

</h4>

<div class="row align-items-center">

<div class="col-md-4 text-center">

<img
src="../assets/img/<?= $data['gambar']; ?>"
class="detail-image">

</div>

<div class="col-md-8">

<span class="badge new-badge mb-2">

<?= $data['nama_kategori']; ?>

</span>

<h3 class="mt-2">

<?= $data['nama_produk']; ?>

</h3>

<p class="text-muted mb-3">

Ukuran :
<strong><?= $ukuran; ?></strong>

</p>

<table class="table detail-table">

<tr>

<td>Harga</td>

<td>

<strong class="text-bloom">

Rp <?= number_format($harga,0,',','.'); ?>

</strong>

</td>

</tr>

<tr>

<td>Stok</td>

<td>

<?= $stok; ?> Bouquet

</td>

</tr>

</table>

</div>

</div>

</div>

</div>

</div>

<div class="col-12 mt-4">

    <div class="card border-0 shadow-sm rounded-4">

<div class="card-body p-4">

<h4 class="mb-4">

Informasi Pemesan

</h4>

<div class="mb-3">

<label class="form-label">

Nama Pemesan

</label>

<input
type="text"
name="nama_pemesan"
class="form-control"
value="<?= $_SESSION['nama']; ?>"
required>

</div>

<div class="mb-3">

<label class="form-label">

No. HP

</label>

<input
type="text"
name="no_hp"
class="form-control"
required>

</div>

<div class="mb-3">

<label class="form-label">

Jumlah

</label>

<input
type="number"
name="jumlah"
id="jumlah"
class="form-control"
value="1"
min="1"
max="<?= $stok; ?>"
required>

</div>

<div class="mb-3">

<label class="form-label">

Metode Pengiriman

</label>

<select
name="metode_pengiriman"
id="metode_pengiriman"
class="form-select"
required>

<option value="Delivery">Delivery (+Rp20.000)</option>

<option value="Ambil di Toko">Ambil di Toko</option>

</select>

</div>

<div
class="mb-3"
id="alamatBox">

<label class="form-label">

Alamat Lengkap

</label>

<textarea
name="alamat"
id="alamat"
class="form-control"
rows="4"
required></textarea>

</div>

<hr class="my-4">

<h4 class="mb-3">

Custom Bouquet

</h4>

<div class="form-check mb-3">

<input
class="form-check-input"
type="checkbox"
name="boneka"
id="boneka">

<label
class="form-check-label"
for="boneka">

Boneka (+Rp25.000)

</label>

</div>

<div class="form-check mb-3">

<input
class="form-check-input"
type="checkbox"
name="balon"
id="balon">

<label
class="form-check-label"
for="balon">

Balon (+Rp15.000)

</label>

</div>

<div class="form-check mb-4">

<input
class="form-check-input"
type="checkbox"
name="kartu_ucapan"
id="kartu_ucapan">

<label
class="form-check-label"
for="kartu_ucapan">

Kartu Ucapan (+Rp5.000)

</label>

</div>

<div class="mb-3">

<label class="form-label">

Warna Bouquet

</label>

<input
type="text"
name="warna_buket"
class="form-control"
placeholder="Contoh : Pink Pastel">

</div>

<div class="mb-3">

<label class="form-label">

Isi Surat

</label>

<textarea
name="isi_surat"
class="form-control"
rows="4"
placeholder="Tuliskan isi ucapan..."></textarea>

</div>

<div class="mb-3">

<label class="form-label">

Catatan Tambahan

</label>

<textarea
name="catatan"
class="form-control"
rows="3"
placeholder="Catatan untuk florist..."></textarea>

</div>
<hr class="my-4">

<h4 class="mb-3">

Ringkasan Pembayaran

</h4>

<div class="detail-box mb-4">

<div class="d-flex justify-content-between mb-2">

<span>Harga Bouquet</span>

<strong>

Rp <?= number_format($harga,0,',','.'); ?>

</strong>

</div>

<div class="d-flex justify-content-between">

<span>Total</span>

<h5 class="text-bloom mb-0" id="totalHarga">

Rp <?= number_format($harga,0,',','.'); ?>

</h5>

</div>

</div>

<button
type="submit"
name="buat_pesanan"
class="btn btn-bloom w-100 mb-2">

<i class="bi bi-credit-card me-2"></i>

Buat Pesanan

</button>

<a
href="detail_produk.php?id=<?= $id_produk; ?>"
class="btn btn-outline-bloom w-100">

Kembali

</a>

</div>

</div>

</div>

</div>

</form>

</div>

<script>

function toggleAlamat(){

    const metode = document.getElementById("metode_pengiriman");

    const alamatBox = document.getElementById("alamatBox");

    const alamat = document.getElementById("alamat");

    if(metode.value=="Ambil di Toko"){

        alamatBox.style.display="none";

        alamat.value="";

        alamat.disabled=true;

        alamat.removeAttribute("required");

    }else{

        alamatBox.style.display="block";

        alamat.disabled=false;

        alamat.setAttribute("required","required");

    }

}

toggleAlamat();

const harga = <?= $harga; ?>;

const jumlah = document.getElementById("jumlah");

const totalHarga = document.getElementById("totalHarga");

function hitungTotal(){

    let total = harga * parseInt(jumlah.value);

    if(document.getElementById("boneka").checked){

        total += 25000;

    }

    if(document.getElementById("balon").checked){

        total += 15000;

    }

    if(document.getElementById("kartu_ucapan").checked){

        total += 5000;

    }

    if(document.getElementById("metode_pengiriman").value=="Delivery"){

        total += 20000;

    }

    totalHarga.innerHTML =
    "Rp " + total.toLocaleString("id-ID");

}

jumlah.addEventListener("input", hitungTotal);

document.getElementById("boneka").addEventListener("change", hitungTotal);

document.getElementById("balon").addEventListener("change", hitungTotal);

document.getElementById("kartu_ucapan").addEventListener("change", hitungTotal);

document.getElementById("metode_pengiriman").addEventListener("change", function(){

    toggleAlamat();

    hitungTotal();

});

hitungTotal();
</script> 

</body>
</html>