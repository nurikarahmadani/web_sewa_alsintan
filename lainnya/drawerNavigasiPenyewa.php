<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="index.css">
    <link rel="stylesheet" href="style-topbar.css">
    <title>Document</title>
</head>

<body>
    <div class="topbar">
        <span onclick="openNav()" class="mobile-nav-open-icon">&#9776</span>
        <div class="web-logo">
            <a href="../index.php">SEAL.COM</a>
            <!-- <img src="../lainnya/tractor.png" alt="logo" class="logo" /> -->
        </div>
        <nav>
            <ul>
                <li>
                    <a class="logout" href="../phpPenyewa/logout.php">Logout</a>
                </li>
            </ul>
        </nav>
    </div>
    <div class="backdrop-container" id="backdrop"></div>
    <div id="mySidenav" class="sidenav-container">
        <span class="drawer-close-button">
            <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
        </span>
        <a href="../phpPenyewa/editpenyewa.php?" onclick="closeNav()" id="home-link">Kelola Data Diri</a>
        <a href="../phpPenyewa/alsintan.php" onclick="closeNav()" id="about-link">Lihat Produk</a>
        <a href="../phpPenyewa/keranjang.php" onclick="closeNav()" id="about-link">Keranjang</a>
        <a href="../phpPenyewa/pembayaran.php" onclick="closeNav()" id="about-link">Pembayaran</a>
        <a href="../phpPenyewa/denda.php" onclick="closeNav()" id="works-link">Denda Keterlambatan</a>
    </div>
    <script type="text/javascript" src="helper.js"></script>
</body>

</html>