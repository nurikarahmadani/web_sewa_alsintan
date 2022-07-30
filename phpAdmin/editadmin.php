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
    $id_admin = $_SESSION['login_admin'];
    $select_sql = "SELECT * FROM admin WHERE id_admin = $id_admin";
    $result = mysqli_query($conn, $select_sql);
    $hasil_edit;
    $admin;
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $admin = $row;
        }
        if (isset($_POST["submit"])) {
            $nama_admin = htmlspecialchars($_POST["nama_admin"]);
            $pass_admin = htmlspecialchars($_POST["pass_admin"]);
            $noHP_admin = htmlspecialchars($_POST["noHP_admin"]);
            $email_admin = htmlspecialchars($_POST["email_admin"]);
            $update_sql = "UPDATE admin SET nama_admin= '$nama_admin', pass_admin='$pass_admin', noHP_admin = '$noHP_admin',email_admin='$email_admin' WHERE id_admin = '$id_admin'";
            $result = mysqli_query($conn, $update_sql);
            if ($result) {
                $hasil_edit = "berhasil";
            } else {
                $hasil_edit = "gagal";
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
        <strong>Berhasil Edit Data</strong>
    </div>
    <div class="alert" id="alert-gagal">
        <span class="close-alert">&times;</span>
        <strong>Gagal Edit Data</strong>
    </div>
    <div class="card-form">
        <h2>Edit Data</h2>
        <FORM action="" method="POST">
            <input type="hidden" name="id_admin" value="<?= $admin["id_admin"]; ?>">
            <table>
                <tr>
                    <td>Nama</td>
                    <td><input type="text" name="nama_admin" id="nama_admin" value="<?= $admin["nama_admin"]; ?>" required></td>
                </tr>
                <tr>
                    <td>Password</td>
                    <td><input type="text" name="pass_admin" id="pass_admin" required></td>
                </tr>
                <tr>
                    <td>Nomor Telepon</td>
                    <td><input type="number" name="noHP_admin" id="noHP_admin" value="<?= $admin["noHP_admin"]; ?>" required></td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td><input type="text" name="email_admin" id="email_admin" value="<?= $admin["email_admin"]; ?>" required></td>
                </tr>
                <tr></tr>
            </table>
            <button class="btn" type="submit" name="submit">Update</button>
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