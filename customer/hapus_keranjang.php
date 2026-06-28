<?php
session_start();
include '../config/koneksi.php';

if(!isset($_SESSION['email'])){
    header("Location:../auth/login.php");
    exit();
}

$id = $_GET['id'];
$id_user = $_SESSION['id_user'];

mysqli_query($koneksi,"
DELETE FROM keranjang
WHERE id_keranjang='$id'
AND id_user='$id_user'
");

echo "
<script>
alert('Produk berhasil dihapus dari keranjang');
window.location='keranjang.php';
</script>
";
?>