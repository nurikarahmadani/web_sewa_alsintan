<?php
require '../connect.php';
require '../lainnya/drawerNavigasiAdmin.html';
session_start();
if (!isset($_SESSION['login_admin'])) {
    echo "<script>
        alert('Harus Login dulu !!!');
        document.location.href = 'login.php';
    </script>";
    exit;
}
if (isset($_POST["submit"])) {
    $nama_alsintan = htmlspecialchars($_POST["nama_alsintan"]);
    $harga_sewa = htmlspecialchars($_POST["harga_sewa"]);
    $jumlah_unit = htmlspecialchars($_POST["jumlah_unit"]);
    $foto = $_FILES["foto"];
    $spesifikasi = htmlspecialchars($_POST["spesifikasi"]);
    $img_name = $_FILES['foto']['name'];
    $img_size = $_FILES['foto']['size'];
    $tmp_name = $_FILES['foto']['tmp_name'];
    $error = $_FILES['foto']['error'];
    $hasil_tambah;
    $query_cek = "SELECT * FROM alsintan WHERE nama_alsintan = '$nama_alsintan'";
    $result_cek = mysqli_query($conn, $query_cek);
    if (mysqli_fetch_assoc($result_cek)) {
        $hasil_tambah = "alsintan exist";
    } else {
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
                    $create_sql = "INSERT INTO alsintan VALUES ('','$nama_alsintan','$harga_sewa','$jumlah_unit','$new_img_name','$spesifikasi')";
                    $result = mysqli_query($conn, $create_sql);
                    if ($result) {
                        $hasil_tambah = 'berhasil';
                    } else {
                        $hasil_tambah = 'gagal';
                    }
                } else {
                    $hasil_tambah = 'jenis file tidak valid';
                }
            }
        } else {
            $hasil_tambah = 'unknown error occured';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../lainnya/style.css">

    <title>Tambah</title>
</head>

<body>
    <div class="alert" id="alert">
        <span class="close-alert">&times;</span>
        <strong>Data Berhasil Disimpan</strong>
    </div>
    <div class="alert" id="alert-gagal">
        <span class="close-alert">&times;</span>
        <strong>Data Gagal Disimpan</strong>
    </div>
    <div class="alert" id="alert-alsintan-exist">
        <span class="close-alert">&times;</span>
        <strong>Data Alsintan Sudah Ada</strong>
    </div>
    <div class="card-form">
        <h2>Tambah Data</h2><br>
        <FORM action="" method="POST" enctype="multipart/form-data">
            <table>
                <tr>
                    <td>Nama Alsintan</td>
                    <td><input type="text" name="nama_alsintan" id="nama_alsintan" required></td>
                </tr>
                <tr>
                    <td>Harga Sewa</td>
                    <td><input type="number" name="harga_sewa" id="harga_sewa" required></td>
                </tr>
                <tr>
                    <td>Jumlah Unit</td>
                    <td><input type="number" name="jumlah_unit" id="jumlah_unit" required></td>
                </tr>
                <tr>
                    <td>Foto</td>
                    <td><input class="inputFile" type="file" name="foto" id="foto" required></td>
                </tr>
                <tr>
                    <td>Spesifikasi</td>
                    <td><textarea name="spesifikasi" id="" cols="40" rows="10" required></textarea></td>
                </tr>
            </table>
            <button class="btn" type="submit" name="submit">TAMBAH</button>

        </FORM>
        <?php if (isset($_GET['error'])) : ?>
            <p><?php echo $_GET['error']; ?></p>
        <?php endif ?>
    </div><br><br>
    <!------------ UNTUK NOTIFNYA ----------------->
    <script>
        var hasil_tambah = "<?php echo "$hasil_tambah" ?>"
        if (hasil_tambah == 'berhasil') {
            const al = document.getElementById("alert");
            al.style.backgroundColor = 'rgba(175, 250, 180, 0.1)';
            setTimeout(function() {
                al.style.display = 'block';
            }, 500)
            setTimeout(function() {
                window.location.href = "alsintan.php"
            }, 3000)

        } else if (hasil_tambah = 'alsintan exist') {
            const al = document.getElementById("alert-alsintan-exist");
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