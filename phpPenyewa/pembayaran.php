<?php
session_start();
require '../connect.php';
require '../lainnya/drawerNavigasiPenyewa.php';
date_default_timezone_set('Asia/Makassar'); //sesuaikan timezone ke wita
if (!isset($_SESSION['login_penyewa'])) {
    echo "<script>
        alert('Harus Login dulu !!!');
        document.location.href = 'login.php';
    </script>";
    exit;
} else {
    $id_penyewa = $_SESSION['login_penyewa'];
    $select_sql = "SELECT * FROM transaksi WHERE id_penyewa = $id_penyewa";
    $result = mysqli_query($conn, $select_sql);
    $tranksaksi = [];
    $alsintan = [];
    $hasil_tr;
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $tranksaksi[] = $row;
        }
        for ($i = 0; $i < count($tranksaksi); $i++) {
            $idtr = $tranksaksi[$i]['id_transaksi'];
            $als_sql = "SELECT pn.jumlah_alsintan, al.nama_alsintan
                        FROM penyewaan pn JOIN alsintan al ON pn.id_alsintan = al.id_alsintan
                        WHERE id_transaksi = '$idtr'";
            $resultals = mysqli_query($conn, $als_sql);
            if ($resultals) {
                while ($als = mysqli_fetch_assoc($resultals)) {
                    $alsintan[$i][] = $als;
                }
            } else {
                echo mysqli_error($conn);
            }
        }
        if (isset($_POST["submit"])) {
            $id = $_POST['submit'];
            $foto = $_FILES["foto"];
            $img_name = $_FILES['foto']['name'];
            $img_size = $_FILES['foto']['size'];
            $tmp_name = $_FILES['foto']['tmp_name'];
            $error = $_FILES['foto']['error'];
            if ($error === 0) {
                if ($img_size > 125000) { //jika lebih dari 1mb
                    $hasil_tr = "gagal-sizefile";
                } else { //ex kepanjangannya extension atau jenis file
                    $img_ex = pathinfo($img_name, PATHINFO_EXTENSION); //untuk mengetahui jenis file yg diupload
                    $img_ex_lc = strtolower($img_ex);
                    $allowed_ex = array("jpg", "jpeg", "png"); //simpan extensi file yang dibolehkan
                    if (in_array($img_ex_lc, $allowed_ex)) {
                        $new_img_name = uniqid("IMG-", true) . '.' . $img_ex_lc;
                        $img_upload_path = '../tempImage/' . $new_img_name;
                        move_uploaded_file($tmp_name, $img_upload_path);
                        $create_sql = "UPDATE transaksi SET bukti_pembayaran = '$new_img_name' WHERE id_transaksi = $id";
                        $result = mysqli_query($conn, $create_sql);
                        if ($result) {
                            $hasil_tr = "berhasil";
                        } else {
                            $hasil_tr = "gagal";
                        }
                    } else {
                        $hasil_tr = "gagal-tipefile";
                    }
                }
            } else {
                $hasil_tr = "gagal";
            }
        }
    } else {
        echo mysqli_error($conn);
    }
}

?>

<!--  -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../lainnya/style.css">
    <link rel="stylesheet" href="../lainnya/font-awesome-4.7.0/css/font-awesome.min.css">
    <title>Pembayaran</title>
</head>

<body>
    <div class="alert" id="alert">
        <span class="close-alert">&times;</span>
        <strong>Berhasil Mengirim Bukti Pembayaran</strong>
    </div>
    <div class="alert" id="alert-gagal">
        <span class="close-alert">&times;</span>
        <strong>Gagal Mengirim Bukti Pembayaran</strong>
    </div>
    <div class="alert" id="alert-gagal-tipe">
        <span class="close-alert">&times;</span>
        <strong>Harap Kirim Bukti Pembayaran bertipe file jpg, jpeg, dan png</strong>
    </div>
    <div class="alert" id="alert-gagal-size">
        <span class="close-alert">&times;</span>
        <strong>Harap Kirim Bukti Dengan Size Kurang dari 1mb</strong>
    </div>
    <div class="container">
        <div class="card-table">
            <h2>Pembayaran</h2><br>
            <table border="1">
                <tr>
                    <th>Alsintan</th>
                    <th>Tanggal Sewa</th>
                    <th>Lama Sewa</th>
                    <th>Tanggal Pengembalian</th>
                    <th>Sisa Waktu Pembayaran</th>
                    <th>Total</th>
                    <th>Pembayaran</th>
                    <th>Pengembalian</th>
                    <th></th>
                </tr>
                <?php for ($i = 0; $i < count($tranksaksi); $i++) : ?>
                    <tr>
                        <td>
                            <?php for ($j = 0; $j < count($alsintan[$i]); $j++) : ?>
                                <?= $alsintan[$i][$j]['nama_alsintan'] ?>
                                <?= $alsintan[$i][$j]['jumlah_alsintan'] ?> unit,
                            <?php endfor ?>
                        </td>
                        <td><?= $tranksaksi[$i]['tanggal_sewa'] ?></td>
                        <td><?= $tranksaksi[$i]['jangka_waktu_sewa'] ?></td>
                        <td><?= $tranksaksi[$i]['tanggal_pengembalian'] ?></td>
                        <td><?= 24 - $tranksaksi[$i]['sisa_waktu_pembayaran'] ?> Jam</td>
                        <td><?= $tranksaksi[$i]['total'] ?></td>
                        <?php if (($tranksaksi[$i]['bukti_pembayaran'] == !null || $tranksaksi[$i]['status_pembayaran'] == 'Lunas' ) && $tranksaksi[$i]['status_pengembalian'] == 'Belum Kembali') { ?>
                            <td><i class="fa fa-check"></i></td>
                            <td></td>
                            <td></td>
                        <?php } else if ($tranksaksi[$i]['status_pembayaran'] == 'Lunas' && $tranksaksi[$i]['status_pengembalian'] == 'Sudah Kembali') { ?>
                            <td><i class="fa fa-check"></i></td>
                            <td><i class="fa fa-check"></i></td>
                            <form action="struk.php" method="POST">
                                <td><button name="btn-struk" type="submit" value="<?= $tranksaksi[$i]['id_transaksi'] ?>">Struk</button></td>
                            </form>
                        <?php } else { ?>
                            <form action="" method="POST" enctype="multipart/form-data">
                                <td><input type="file" name="foto" required></td>
                                <td></td>
                                <td><button class="btn" type="submit" name="submit" value="<?= $tranksaksi[$i]['id_transaksi'] ?>">Kirim</button></td>
                            </form>
                        <?php } ?>

                    </tr>
                <?php endfor ?>
            </table>
        </div>
    </div>
    <br><br>
    <!------------ UNTUK NOTIFNYA ----------------->
    <script>
        var hasil_tr = "<?php echo "$hasil_tr" ?>"
        if (hasil_tr == 'berhasil') {
            const al = document.getElementById("alert");
            al.style.backgroundColor = 'rgba(175, 250, 180, 0.1)';
            setTimeout(function() {
                al.style.display = 'block';
            }, 500)
            setTimeout(function() {
                window.location.href = "pembayaran.php"
            }, 3000)
        } else if (hasil_tr = 'gagal-sizefile') {
            const al = document.getElementById("alert-gagal-size");
            setTimeout(function() {
                al.style.display = 'block';
            }, 500)
        } else if (hasil_tr = 'gagal-tipefile') {
            const al = document.getElementById("alert-gagal-tipe");
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