<?php
require '../lainnya/drawerNavigasiPenyewa.php';
require '../connect.php';
session_start();
if (!isset($_SESSION['login_penyewa'])) {
    echo "<script>
        alert('Harus Login dulu !!!');
        document.location.href = 'login.php';
    </script>";
    exit;
} else {
    $read_sql = "SELECT * from alsintan";
    $result = mysqli_query($conn, $read_sql);
    $alsintan = [];
    $hasil_keranjang;
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $alsintan[] = $row;
        }
        if (isset($_POST['btn-keranjang'])) {
            if (in_array($_POST['btn-keranjang'], $_SESSION['keranjang']) == FALSE) {
                array_push($_SESSION['keranjang'], $_POST['btn-keranjang']);
                $hasil_keranjang = "berhasil";
            } else {
                $hasil_keranjang = "gagal";
            }
        }
    } else {
        echo mysqli_error($conn);
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
    <title>ALSINTAN</title>
</head>

<body>
    <div class="alert" id="alert">
        <span class="close-alert">&times;</span>
        <strong>Berhasil menambahkan ke keranjang</strong>
    </div>
    <div class="alert" id="alert-gagal">
        <span class="close-alert">&times;</span>
        <strong>Barang sudah ada di keranjang !!</strong>
    </div>
    <div class="card-menu">
        <?php foreach ($alsintan as $als) : ?>
            <div class="card-als">
                <img src="../tempImage/<?= $als['Foto'] ?>" alt="Gambar"><br>
                <div class="ket">
                    <p><?= $als["nama_alsintan"]; ?></p>
                    <p>Rp.<?= $als["harga_sewa"]; ?> / hari</p><br>
                    <p class="spek" style="text-decoration: underline; cursor: pointer;">Spesifikasi</p><br>
                    <div class="tooltip">
                        <p><?=$als['spesifikasi']?></p>
                    </div>
                </div>
                <form action="alsintan.php" method="POST">
                    <button class="btn" name="btn-keranjang" value="<?= $als['id_alsintan'] ?>">+Keranjang</button>
                </form>
                <br>
            </div>
        <?php endforeach ?>
    </div>
    <!------------ UNTUK NOTIFNYA ----------------->
    <script>
        var hasil_keranjang = "<?php echo "$hasil_keranjang" ?>"
        if (hasil_keranjang == 'berhasil') {
            const al = document.getElementById("alert");
            al.style.backgroundColor = 'rgba(175, 250, 180, 0.1)';
            setTimeout(function() {
                al.style.display = 'block';
            }, 500)
        } else {
            const al = document.getElementById("alert-gagal");
            setTimeout(function() {
                al.style.display = 'block';
            }, 500)
        }
        var close = document.getElementsByClassName("close-alert");
        var i;
        for (i = 0; i < close.length; i++) {
            close[i].onclick = function() {
                var div = this.parentElement;
                div.style.opacity = "0";
                setTimeout(function() {
                    div.style.display = "none";
                }, 600);
            }
        }
    </script>
    <script type="text/javascript" src="../lainnya/helper.js"></script>
</body>

</html>