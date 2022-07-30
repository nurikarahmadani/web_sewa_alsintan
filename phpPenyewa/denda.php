<?php
session_start();
require '../connect.php';
require '../lainnya/drawerNavigasiPenyewa.php';
$id_penyewa = $_SESSION['login_penyewa'];
$select_sql =
    "SELECT tr.id_transaksi, tr.tanggal_sewa, tr.waktu_tranksaksi, kt.lama_keterlambatan, kt.jumlah_denda, kt.bukti_pembayaran, kt.status_pembayaran
     FROM transaksi tr JOIN keterlambatan kt ON tr.id_transaksi = kt.id_transaksi
     WHERE tr.id_penyewa = $id_penyewa
";
$result = mysqli_query($conn, $select_sql);
$keterlambatan = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $keterlambatan[] = $row;
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
                $em = "Size gambar terlalu besar !";
                header('location: tambahals.php?error = $em');
            } else { //ex kepanjangannya extension atau jenis file
                $img_ex = pathinfo($img_name, PATHINFO_EXTENSION); //untuk mengetahui jenis file yg diupload
                $img_ex_lc = strtolower($img_ex);
                $allowed_ex = array("jpg", "jpeg", "png"); //simpan extensi file yang dibolehkan
                if (in_array($img_ex_lc, $allowed_ex)) {
                    $new_img_name = uniqid("IMG-", true) . '.' . $img_ex_lc;
                    $img_upload_path = '../tempImage/' . $new_img_name;
                    move_uploaded_file($tmp_name, $img_upload_path);
                    //upload gambar ke database
                    $create_sql = "UPDATE keterlambatan SET bukti_pembayaran = '$new_img_name' WHERE id_transaksi = $id";
                    $result = mysqli_query($conn, $create_sql);

                    if ($result) {
                        echo "<script>
                            alert('Bukti Pembayaran Berhasil Terkirim!');
                        </script>";
                    } else {
                        echo "<script>
                            alert('Gagal Mengirim Bukti Pembayaran!');
                        </script>";
                    }
                } else {
                    $em = "Hanya bisa upload gambar tipe jpg, jpeg, dan png !";
                    header('location: keterlambatan.php?error = $em');
                }
            }
        } else {
            $em = "Unknown error occured";
            header('location: keterlambatan.php?error = $em');
        }
    }
} else {
    echo mysqli_error($conn);
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
    <title>Document</title>
</head>

<body>
    <div class="container">
        <div class="card-table">
            <h2>Denda Keterlambatan</h2><br>
            <table border="1">
                <tr>
                    <th>Tanggal Penyewaan</th>
                    <th>Tanggal Tranksaksi</th>
                    <th>Lama Keterlambatan</th>
                    <th>Total Denda</th>
                    <th>Bukti Pembayaran</th>
                    <th>Kirim</th>
                </tr>
                <?php for ($i=0; $i<count($keterlambatan); $i++) :  ?>
                    <tr>
                        <td><?= $keterlambatan[$i]['tanggal_sewa'] ?></td>
                        <td><?= $keterlambatan[$i]['waktu_tranksaksi'] ?></td>
                        <td><?= $keterlambatan[$i]['lama_keterlambatan'] ?> Hari</td>
                        <td>Rp. <?= $keterlambatan[$i]['jumlah_denda'] ?></td>
                        <?php if ($keterlambatan[$i]['bukti_pembayaran'] == !null || $keterlambatan[$i]['status_pembayaran'] == 'Lunas') { ?>
                            <td>Sudah Mengirim Bukti Pembayaran</td>
                            <td> - </td>
                        <?php } else { ?>
                            <form action="" method="POST" enctype="multipart/form-data">
                                <td><input type="file" name="foto" required>Bukti Pembayaran</td>
                                <td><button class="btn" type="submit" name="submit" value="<?= $keterlambatan[$i]['id_transaksi'] ?>">Kirim</button></td>
                            </form>
                        <?php } ?>
                    </tr>
                <?php endfor; ?>
            </table>

        </div>
    </div>
    <script type="text/javascript" src="../lainnya/helper.js"></script>
</body>

</html>