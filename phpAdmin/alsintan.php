<!--  -->
<?php
session_start();
if (!isset($_SESSION['login_admin'])) {
    echo "<script>
        alert('Harus Login dulu !!!');
        document.location.href = 'login.php';
    </script>";
    exit;
} else {
    require '../connect.php';
    require '../lainnya/drawerNavigasiAdmin.html';

    $alsintan = [];
    if (isset($_POST['btn-cari'])) {
        $nama_alsintan = $_POST['cari'];
        $cari_sql = "SELECT * FROM alsintan WHERE nama_alsintan LIKE '$nama_alsintan'";
        $result = mysqli_query($conn, $cari_sql);
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $alsintan[] = $row;
            }
        } else {
            echo mysqli_error($conn);
        }
    } else {
        $read_sql = "SELECT * from alsintan";
        $result = mysqli_query($conn, $read_sql);
        while ($row = mysqli_fetch_assoc($result)) {
            $alsintan[] = $row;
        }
    }
}
?>
<!--  -->
<!--  -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../lainnya/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css"/>
    <title>ALSINTAN</title>
</head>w

<body>
    <div class="container">
        <br><br>
        <div class="card-tambah">
            <button onclick="window.location.href = 'tambahals.php'"> +Tambah </button><br>
            <div class="card-cari">
                <form action="" method="POST">
                    <input name="cari" type="text">
                    <button name="btn-cari"><i class="fa fa-search"></i></button>
                </form>
            </div>
        </div>
        <?php foreach ($alsintan as $als) : ?>
            <div class="card-kelola">
                <img src="../tempImage/<?= $als['Foto'] ?>" alt="Gambar"><br>
                <div class="detail">
                    <h2><?= $als["nama_alsintan"]; ?></h2>
                    <p>Harga Sewa : Rp.<?= $als["harga_sewa"]; ?> / hari</p>
                    <p>Jumlah Unit : <?= $als['jumlah_unit'] ?></p><br>
                    <p><?= $als['spesifikasi'] ?></p>
                    <div class="card-btn">
                        <button onclick="window.location.href='hapusals.php?id_alsintan=<?= $als['id_alsintan']; ?>';"><i style="font-size: 25px;" class="fa fa-trash-o"></i></button>
                        <button onclick="window.location.href='editals.php?id_alsintan=<?= $als['id_alsintan']; ?>';"><i style="font-size: 25px;" class="fa fa-edit"></i></button>
                    </div>

                </div>
            </div>
        <?php endforeach ?>
    </div><br><br>
    <script type="text/javascript" src="../lainnya/helper.js"></script>
</body>

</html>