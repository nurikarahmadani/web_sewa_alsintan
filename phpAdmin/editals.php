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
} else {
    $id_alsintan = $_GET["id_alsintan"];
    $select_sql = "SELECT * FROM alsintan WHERE id_alsintan = $id_alsintan";
    $result = mysqli_query($conn, $select_sql);
    $alsintan;
    $hasil;
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $alsintan = $row;
        }
        if (isset($_POST["submit"])) {
            $img_pointer = "../tempImage/" . $alsintan['Foto'];
            if (file_exists($img_pointer)) {
                if (unlink($img_pointer)) {
                    $nama_alsintan = htmlspecialchars($_POST["nama_alsintan"]);
                    $harga_sewa = htmlspecialchars($_POST["harga_sewa"]);
                    $jumlah_unit = htmlspecialchars($_POST["jumlah_unit"]);
                    $foto = $_FILES["foto"];
                    $spesifikasi = htmlspecialchars($_POST["spesifikasi"]);
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
                                $img_upload_path = '../tempImage/' . $new_img_name; //pindahkan file yg diupload ke direktori uploads
                                move_uploaded_file($tmp_name, $img_upload_path);
                                $update_sql = "UPDATE alsintan SET nama_alsintan='$nama_alsintan', harga_sewa= '$harga_sewa', jumlah_unit='$jumlah_unit', Foto = '$new_img_name', spesifikasi = '$spesifikasi' WHERE id_alsintan = '$id_alsintan'";
                                $result = mysqli_query($conn, $update_sql);
                                if ($result) {
                                    $hasil = "berhasil";
                                } else {
                                    $hasil = "gagal";
                                }
                            } else {
                                $hasil = "gagal";
                            }
                        }
                    } else {
                        $hasil = "gagal";
                    }
                } else {
                    $hasil = "gagal";
                }
            }
        }
    } else {
        echo mysqli_error($conn);
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
    <title>Edit</title>
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
    <div class="card-form">
        <h2>Edit Alsintan</h2>
        <FORM action="" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id_alsintan" value="<?= $alsintan["id_alsintan"]; ?>">
            <table>
                <tr>
                    <td>Nama Alsintan</td>
                    <td><input type="text" name="nama_alsintan" id="nama_alsintan" value="<?= $alsintan["nama_alsintan"]; ?>" required></td>
                </tr>
                <tr>
                    <td>Harga Sewa</td>
                    <td><input type="number" name="harga_sewa" id="harga_sewa" value="<?= $alsintan["harga_sewa"]; ?>" required></td>
                </tr>
                <tr>
                    <td>Jumlah Unit</td>
                    <td><input type="number" name="jumlah_unit" id="jumlah_unit" value="<?= $alsintan["jumlah_unit"]; ?>" required></td>
                </tr>
                <tr>
                    <td>Foto Alsintan</td>
                    <td><input type="file" name="foto" id="foto" class="inputFile" value="<?= $alsintan["Foto"]; ?>" required></td>
                </tr>
                <tr>
                    <td>Spesifikasi</td>
                    <td><textarea name="spesifikasi" id="" cols="40" rows="10" placeholder="<?= $alsintan["spesifikasi"] ?>" required></textarea></td>
                </tr>
            </table>
            <button class="btn" type="submit" name="submit">UPDATE</button>
        </FORM>
    </div><br><br>
    <!------------ UNTUK NOTIFNYA ----------------->
    <script>
        var hasil = "<?php echo "$hasil" ?>"
        if (hasil == 'berhasil') {
            const al = document.getElementById("alert");
            al.style.backgroundColor = 'rgba(175, 250, 180, 0.1)';
            setTimeout(function() {
                al.style.display = 'block';
            }, 500)
            setTimeout(function() {
                window.location.href = "alsintan.php"
            }, 3000)
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