<?php
session_start();
include '../config/koneksi.php';

if(!isset($_SESSION['email'])){
    header("Location:../auth/login.php");
    exit();
}

$id_user = $_SESSION['id_user'];

$sql = "SELECT keranjang.*,
        produk.nama_produk,
        produk.gambar,
        produk.harga_small,
        produk.harga_medium,
        produk.harga_large
        FROM keranjang
        JOIN produk
        ON keranjang.id_produk = produk.id_produk
        WHERE keranjang.id_user='$id_user'
        ORDER BY keranjang.id_keranjang DESC";

$query = mysqli_query($koneksi,$sql);

$total_item = mysqli_num_rows($query);
?>

<!DOCTYPE html>
<html>
<head>
<title>Keranjang Saya</title>
<style>

    body{
        font-family:Arial;
        margin:30px;
    }

    .card{

        border:1px solid #ddd;
        border-radius:10px;
        padding:20px;
        margin-bottom:20px;

    }

    .flex{

        display:flex;
        gap:20px;

    }

    img{

        width:150px;
        height:150px;
        object-fit:cover;
        border-radius:10px;

    }

    textarea{

        width:100%;

    }

    input[type=text]{

        width:100%;

    }

    .btn{

        padding:8px 14px;
        text-decoration:none;
        border:1px solid #ccc;
        border-radius:6px;
        display:inline-block;
        margin-right:5px;
        background:#f7f7f7;
        color:black;
        cursor:pointer;

    }

</style>

</head>

<body>

    <h2>🛒 Keranjang Saya</h2>

    <a href="kategori.php">← Belanja Lagi</a>

    <hr>

    <?php
    if($total_item>0){

    $total_semua = 0;
    while($data=mysqli_fetch_assoc($query)){

        if($data['ukuran']=="Small"){

            $harga=$data['harga_small'];

        }elseif($data['ukuran']=="Medium"){

            $harga=$data['harga_medium'];

        }else{

            $harga=$data['harga_large'];

        }

        $subtotal=$harga*$data['jumlah'];

        if($data['boneka']) $subtotal+=25000;
        if($data['balon']) $subtotal+=15000;
        if($data['kartu_ucapan']) $subtotal+=5000;

        $total_semua += $subtotal;
    ?>

    <div class="card">
        <div class="flex">

            <div>
                <?php if(!empty($data['gambar'])){ ?>
                <img src="../images/<?= $data['gambar']; ?>">
                <?php } ?>
            </div>

            <div style="flex:1;">
                <form id="form<?= $data['id_keranjang']; ?>"
                    action="update_keranjang.php"
                    method="POST">

                    <input
                    type="hidden"
                    name="id_keranjang"
                    value="<?= $data['id_keranjang']; ?>">

                    <h3><?= $data['nama_produk']; ?></h3>

                    <p>
                        <b>Ukuran :</b>
                        <?= $data['ukuran']; ?>
                    </p>

                    <p>
                        <b>Jumlah :</b>
                        <a class="btn"
                        href="kurang_jumlah.php?id=<?= $data['id_keranjang']; ?>">

                            ➖

                        </a>

                        <b style="padding:0 15px;">

                            <?= $data['jumlah']; ?>

                        </b>

                        <a class="btn"
                        href="tambah_jumlah.php?id=<?= $data['id_keranjang']; ?>">

                            ➕

                        </a>
                    </p>

                    <hr>

                    <label>
                        <input
                        type="checkbox"
                        name="boneka"

                        <?= $data['boneka'] ? "checked" : ""; ?>>

                        Boneka (+Rp25.000)
                    </label>

                    <br><br>

                    <label>
                        <input
                        type="checkbox"
                        name="balon"

                        <?= $data['balon'] ? "checked" : ""; ?>>

                        Balon (+Rp15.000)
                    </label>

                    <br><br>

                    <label>
                        <input
                        type="checkbox"
                        name="kartu_ucapan"

                        <?= $data['kartu_ucapan'] ? "checked" : ""; ?>>

                        Kartu Ucapan (+Rp5.000)
                    </label>

                    <br><br>

                    <label>Warna Buket</label>

                    <br>

                    <input
                    type="text"
                    name="warna_buket"
                    value="<?= htmlspecialchars($data['warna_buket'] ?? ''); ?>">

                    <br><br>

                    <label>Isi Surat</label>

                    <br>

                    <textarea
                    name="isi_surat"
                    rows="4"><?= htmlspecialchars($data['isi_surat'] ?? ''); ?></textarea>

                    <br><br>

                    <label>Catatan</label>

                    <br>

                    <textarea
                    name="catatan"
                    rows="3"><?= htmlspecialchars($data['catatan'] ?? ''); ?></textarea>

                    <br><br>

                    <h3>
                        Subtotal :
                        Rp <?= number_format($subtotal,0,',','.'); ?>
                    </h3>

                        <button
                        type="submit"
                        class="btn"
                        name="simpan">
                            💾 Simpan Perubahan
                        </button>

                        &nbsp;

                        <a class="btn"
                        href="hapus_keranjang.php?id=<?= $data['id_keranjang']; ?>"
                        onclick="return confirm('Hapus produk ini dari keranjang?')">
                            🗑 Hapus
                        </a>

                        &nbsp;

                        <br><br>

                        <button
                            type="button"
                            class="btn"
                            onclick="checkout(<?= $data['id_keranjang']; ?>)">
                            ⚡ Checkout
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <?php
        }
        ?>

        <hr>
        <h2>
        Total Keranjang :
        Rp <?= number_format($total_semua,0,',','.'); ?>
        </h2>

        <?php

        }else{

        ?>

        <h3>🛒 Keranjang Masih Kosong</h3>
        <p>Belum ada produk di keranjang.</p>

        <a class="btn" href="kategori.php">Mulai Belanja</a>

        <?php } ?>

        <script>
            function checkout(id){

                var form = document.getElementById("form"+id);

                form.action = "update_keranjang.php?checkout=1";
                form.submit();
            }
        </script>
</body>
</html>

