<?php
session_start();
include "../config/koneksi.php";

if(!isset($_SESSION['email'])){
    header("Location:../auth/login.php");
    exit();
}

$id_user = $_SESSION['id_user'];
$id_transaksi = $_GET['id'];

// cek transaksi milik user
$sql = "SELECT *
        FROM transaksi
        WHERE id_transaksi='$id_transaksi'
        AND id_user='$id_user'";

$query = mysqli_query($koneksi,$sql);
$data = mysqli_fetch_assoc($query);

if(!$data){

    echo "
    <script>
    alert('Pesanan tidak ditemukan!');
    window.location='pesanan_saya.php';
    </script>
    ";

    exit();

}

// hanya bisa dibatalkan jika masih Pesanan Masuk
if($data['status'] != "Pesanan Masuk"){

    echo "
    <script>
    alert('Pesanan tidak dapat dibatalkan.');
    window.location='pesanan_saya.php';
    </script>
    ";

    exit();

}

// ubah status
mysqli_query($koneksi,"
UPDATE transaksi
SET status='Menunggu Pembatalan'
WHERE id_transaksi='$id_transaksi'
");

echo "
<script>
alert('Permintaan pembatalan berhasil dikirim.');
window.location='pesanan_saya.php';
</script>
";
?>