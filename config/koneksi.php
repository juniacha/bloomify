<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "bloomify_db";

$koneksi = mysqli_connect($host, $user, $pass, $db);

if(!$koneksi) {
    die("Koneksi ke database gagal: ". mysqli_connect_error());
}
//else {echo "koneksi berhasil";}
?>