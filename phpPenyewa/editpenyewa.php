<?php
session_start();
require '../connect.php';
require '../lainnya/drawerNavigasiPenyewa.php';
if (!isset($_SESSION['login_penyewa'])) {
    echo "<script>
        alert('Harus Login dulu !!!');
        document.location.href = 'login.php';
    </script>";
    exit;
} else {
    $id_penyewa = $_SESSION['login_penyewa'];
    $select_sql = "SELECT * FROM penyewa WHERE id_penyewa = $id_penyewa";
    $result = mysqli_query($conn, $select_sql);
    $penyewa;
    $hasil_edit;
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $penyewa = $row;
        }
        if (isset($_POST["submit"])) {
            $nama_penyewa = htmlspecialchars($_POST["nama_penyewa"]);
            $pass_penyewa = htmlspecialchars($_POST["pass_penyewa"]);
            $noHP_penyewa = htmlspecialchars($_POST["noHP_penyewa"]);
            $email_penyewa = htmlspecialchars($_POST["email_penyewa"]);
            if ($pass_penyewa == $penyewa['pass_penyewa']) {
                $password = $pass_penyewa;
            } else {
                $password = password_hash($pass_penyewa, PASSWORD_DEFAULT);
            }
            $update_sql = "UPDATE penyewa SET nama_penyewa= '$nama_penyewa', pass_penyewa='$password', noHP_penyewa = '$noHP_penyewa',email_penyewa='$email_penyewa' WHERE id_penyewa = '$id_penyewa'";
            $result = mysqli_query($conn, $update_sql);

            if ($result) {
                $hasil_edit = "berhasil";
            } else {
                echo mysqli_error($conn);
                $hasil_edit = "gagal";
            }
        }
        if (isset($_POST['hapus'])) {
            header('location: delete.php');
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
        <strong>Berhasil Edit Data</strong>
    </div>
    <div class="alert" id="alert-gagal">
        <span class="close-alert">&times;</span>
        <strong>Gagal Edit Data</strong>
    </div>
    <div class="card-form">
        <h2>Edit Data</h2>
        <FORM action="" method="POST">
            <input type="hidden" name="id_penyewa" value="<?= $penyewa["id_penyewa"]; ?>">
            <table>
                <tr>
                    <td>Nama</td>
                    <td><input type="text" name="nama_penyewa" id="nama_penyewa" value="<?= $penyewa["nama_penyewa"]; ?>" required></td>
                </tr>
                <tr>
                    <td>Password</td>
                    <td><input type="text" name="pass_penyewa" id="pass_penyewa" required></td>
                </tr>
                <tr>
                    <td>Nomor Telepon</td>
                    <td><input type="number" name="noHP_penyewa" id="noHP_penyewa" value="<?= $penyewa["noHP_penyewa"]; ?>" required></td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td><input type="text" name="email_penyewa" id="email_penyewa" value="<?= $penyewa["email_penyewa"]; ?>" required></td>
                </tr>
                <tr></tr>
            </table>
            <div class="card-btn-penyewa">
                <button class="btn" type="submit" name="submit">Update</button>
                <button class="btn" type="submit" name="hapus">Hapus Akun</button>
            </div>

        </FORM>
    </div>
    <!------------ UNTUK NOTIFNYA ----------------->
    <script>
        var hasil_edit = "<?php echo "$hasil_edit" ?>"
        if (hasil_edit == 'berhasil') {
            const al = document.getElementById("alert");
            al.style.backgroundColor = 'rgba(175, 250, 180, 0.1)';
            setTimeout(function(){al.style.display = 'block';},600)
        } else {
            const al = document.getElementById("alert-gagal");
            setTimeout(function(){al.style.display = 'block';},600)
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