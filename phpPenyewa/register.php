<?php

require '../connect.php';

if (isset($_POST['daftar'])) {
    $nama = $_POST['Nama'];
    $email = $_POST['Email'];
    $password = $_POST['password'];
    $cpassword = $_POST['Cpassword'];
    $no_hp = $_POST['hp'];
    $hasil_regis;
    if ($password === $cpassword) {
        $password = password_hash($password, PASSWORD_DEFAULT);
        $select_email = "SELECT email_penyewa FROM penyewa WHERE email_penyewa = '$email'";
        $cek_user = mysqli_query($conn, $select_email);

        if (mysqli_fetch_assoc($cek_user)) {
            $hasil_regis = 'email sudah ada';
        } else {
            date_default_timezone_get();
            $date = date('Y-m-d H:i:s');
            $insert_sql = "INSERT INTO penyewa(nama_penyewa, pass_penyewa, noHP_penyewa, email_penyewa, tanggal_daftar) VALUES ('$nama', '$password', '$no_hp', '$email', '$date')";
            mysqli_query($conn, $insert_sql);
            if (mysqli_affected_rows($conn) > 0) {
                $hasil_regis = 'berhasil';
            } else {
                die(mysqli_error($conn));
                echo "<script>
                    alert('Data gagal didaftarkan!');
                    document.location.href = 'register.php';
                </script>";
            }
        }
    } else {
        $hasil_regis = 'konfir password salah';
    }
}


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <link rel="stylesheet" href="../lainnya/style-topbar.css"> -->
    <link rel="stylesheet" href="../lainnya/style-index-login-regis.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css">
    <script>
        function togglePass() {
            var x = document.getElementById("password");
            if (x.type === "password") {
                x.type = "text";
            } else {
                x.type = "password";
            }
        }
    </script>
    <title>Register</title>
</head>

<body>
    <div class="topbar">
        <div class="web-logo">
            <a href="../index.php">SEAL.COM</a>
            <img src="../lainnya/tractor.png" alt="logo" class="logo">
        </div>
        <nav>
            <ul>
                <li><a class="one" href="../index.php">Home</a></li>
                <li><a class="two" href="#">About</a></li>
                <li><a class="three" href="#">Login</a>
                    <ul>
                        <li><a href="login.php">Penyewa</a></li>
                        <li><a href="../phpAdmin/login.php">Admin</a></li>
                    </ul>
                </li>
                <li><a href="#">Registrasi</a>
                    <ul>
                        <li><a href="register.php">Penyewa</a></li>
                        <li><a href="../phpAdmin/regis.php">Admin</a></li>
                    </ul>
                </li>
            </ul>
        </nav>
    </div>
    <div class="card-regis">
        <h1>Registrasi</h1><br><br>
        <div class="alert" id="alert-berhasil">
            <span class="closebtn">&times;</span>
            <strong>Registrasi Berhasil</strong>
        </div>
        <div class="alert" id="alert-email-exist">
            <span class="closebtn">&times;</span>
            <strong>Email Sudah Didaftarkan</strong>
        </div>
        <div class="alert" id="alert-pass-salah">
            <span class="closebtn">&times;</span>
            <strong>Konfirmasi Password Tidak Sesuai</strong>
        </div>
        <form action="" method="POST">
            <div class="txt_field">
                <input type="text" name="Nama" required>
                <label for="Nama">Nama</label>
            </div>
            <div class="txt_field">
                <input type="text" name="Email" required>
                <label for="Email">Email</label>
            </div>
            <div class="passContainer">
                <div class="txt_field">
                    <input type="password" name="password" id="password" required>
                    <label for="password">Password</label>
                </div>
                <div class="togglePass">
                    <i class="far fa-eye" id="togglePassword" style="margin-right: 0px; cursor: pointer;" onclick="togglePass()"></i>
                </div>
            </div>
            <div class="passContainer">
                <div class="txt_field">
                    <input type="password" name="Cpassword" id="password" required>
                    <label for="Cpassword">Konfirmasi Password</label>
                </div>
                <div class="togglePass">
                    <i class="far fa-eye" id="togglePassword" style="margin-right: 0px; cursor: pointer;" onclick="togglePass()"></i>
                </div>
            </div>
            <div class="txt_field">
                <input type="text" name="hp" required>
                <label for="hp">Nomor Telepon</label>
            </div>
            <br>
            <button class="loginButton" type="submit" name="daftar">Daftar</button>
        </form>
    </div><br><br>
    <script>
        var hasil_regis = "<?php echo "$hasil_regis" ?>"
        if (hasil_regis == 'berhasil') {
            const al = document.getElementById("alert-berhasil");
            al.style.width = '420px';
            al.style.backgroundColor = '#6ade82';
            al.style.display = 'block';
        } else if (hasil_regis == 'email sudah ada') {
            const al = document.getElementById("alert-email-exist");
            al.style.width = '420px';
            al.style.display = 'block';
        } else if (hasil_regis == 'konfir password salah') {
            const al = document.getElementById("alert-pass-salah");
            al.style.width = '420px';
            al.style.display = 'block';
        }
        var close = document.getElementsByClassName("closebtn");
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