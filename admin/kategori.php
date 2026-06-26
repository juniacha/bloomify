<?php
session_start();

if(!isset($_SESSION['email'])){
    header("Location:../auth/login.php");
    exit;
}

include'../config/koneksi.php';

if(isset($_POST['tambah'])){
    $nama_kategori = $_POST['nama_kategori'];

    $sql = "INSERT INTO Kategori(nama_kategori)
            VALUES('$nama_kategori')";

    $query = mysqli_query($koneksi, $sql);

    if($query){
        header("Location: kategori.php");
        exit;  
    }else{
        echo "Data gagal ditambahkan";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Kelola Kategori</title>
</head>
<body>
    <h2>Kelola kategori</h2>
    <a href="dashboard.php">Kembali ke Dashboard</a>

    <hr>

    <form method="POST">
        <input type="text" name="nama_kategori" placeholder="Masukkan kategori">

        <button type="submit" name="tambah">Tambah</button>
    </form>

    
    <h3>Daftar Kategori</h3>

    <?php
    $sql="SELECT * FROM kategori";
    $query=mysqli_query($koneksi, $sql);
    ?>

    <table border="1" cellpadding="10">
        <tr>
            <th>ID</th>
            <th>Nama Kategori</th>
            <th>Aksi</th>
        </tr>

        <?php while($data = mysqli_fetch_assoc($query)){ ?>
    <tr>
        <td><?php echo $data['id_kategori']; ?></td>
        <td><?php echo $data['nama_kategori']; ?></td>

        <td>
            <a href="edit_kategori.php?id=<?php echo $data['id_kategori'];?>">
                Edit
            </a>

            |

            <a href="hapus_kategori.php?id=<?php echo $data['id_kategori']; ?>">
                Hapus
            </a>
        </td>
    </tr>

    <?php } ?>
    </table>
</body>
</html>