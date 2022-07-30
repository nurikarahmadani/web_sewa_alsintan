<?php
session_start();
require '../connect.php';
if (isset($_SESSION['login_penyewa'])) {
    header('location: alsintan.php');
}
if (isset($_POST['login'])) {
    $hasil_login;
    $email = $_POST['Email'];
    $password = $_POST['password'];
    $cek_email = "SELECT * FROM penyewa WHERE email_penyewa = '$email'";
    $result = mysqli_query($conn, $cek_email);

    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        if (password_verify($password, $row['pass_penyewa'])) {
            $hasil_login = 'berhasil';
            $_SESSION['login_penyewa'] = $row['id_penyewa'];
            $_SESSION['keranjang'] = [];
        } else {
            $hasil_login = 'gagal';
        }
    } else {
        $hasil_login = 'gagal';
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
    <title>Login</title>
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
                        <li><a href="../phpPenyewa/login.php">Penyewa</a></li>
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
    <div class="card-form">
        <h1>Login Penyewa</h1><br><br>
        <div class="alert" id="alert">
            <span class="closebtn">&times;</span>
            <strong>Login Gagal</strong> Data Login Salah !!!
        </div>
        <form action="" method="POST">
            <div class="txt_field">
                <input type="text" name="Email" required>
                <label for="username">Email</label>
            </div>
            <div class="passContainer">
                <div class="txt_field">
                    <input type="password" name="password" id="password" required>
                    <label for="password">Password</label>
                </div>
                <div class="togglePass">
                    <i class="far fa-eye" id="togglePassword" style="margin-right: 0px; cursor: pointer;" onclick="togglePass()"></i>
                </div>
            </div><br>
            <button class="loginButton" type="submit" name="login">Login</button>
        </form>
    </div>

    <script>
        var hasil_login = "<?php echo "$hasil_login" ?>"
        if (hasil_login == 'berhasil') {
            window.location.href = "alsintan.php";
        } else {
            const al = document.getElementById("alert");
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