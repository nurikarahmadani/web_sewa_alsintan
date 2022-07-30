<?php
require "connect.php";
session_start();
// if (isset($_SESSION['login_admin'])) {
//     header('location: phpAdmin/alsintan.php');
// }
// if (isset($_SESSION['login_penyewa'])) {
//     header('location: phpPenyewa/alsintan.php');
// }
date_default_timezone_set('Asia/Makassar'); //sesuaikan timezone ke wita
$select_sql = "SELECT tr.id_transaksi, tr.id_penyewa, tr.tanggal_sewa, tr.waktu_tranksaksi, tr.tanggal_pengembalian, tr.total, tr.bukti_pembayaran, tr.status_pembayaran, tr.status_pengembalian, tr.status, tr.sisa_waktu_pembayaran,
py.id_alsintan, py.jumlah_alsintan
 FROM transaksi tr JOIN penyewaan py ON tr.id_transaksi = py.id_transaksi";
$result = mysqli_query($conn, $select_sql);
$tranksaksi = [];
$selisihJam = [];
if($result){
    while ($row = mysqli_fetch_assoc($result)) {
        $tranksaksi[] = $row;
    }
    
}else{
    echo mysqli_error($conn);
}
$waktu_tranksaksi = [];
$waktu_skrg = date_create(date("Y-m-d h:i:s"));
for ($i = 0; $i < count($tranksaksi); $i++) {
    //--- mengembalikan stok alsintan ---//
    $id = $tranksaksi[$i]['id_transaksi'];
    if ($tranksaksi[$i]['status'] !== 'selesai' && $tranksaksi[$i]['status_pembayaran'] == 'Lunas' && $tranksaksi[$i]['status_pengembalian'] == 'Sudah Kembali') {
        $id_alsintan = $tranksaksi[$i]['id_alsintan'];
        $select_alsintan = "SELECT * FROM alsintan WHERE id_alsintan = '$id_alsintan'";
        $result = mysqli_query($conn, $select_alsintan);
        $als = mysqli_fetch_assoc($result);
        $stok = $tranksaksi[$i]['jumlah_alsintan'] + $als['jumlah_unit'];
        $update_stok = "UPDATE alsintan SET jumlah_unit = '$stok' WHERE id_alsintan = $id_alsintan";
        $result = mysqli_query($conn, $update_stok);
        if ($result) {
            $update_status = "UPDATE transaksi SET status = 'selesai' WHERE id_transaksi = $id";
            $result = mysqli_query($conn, $update_status);
            if ($result) {
                // echo "Berhasil Update status";
            } else {
                // echo "Gagal Update Status";
            }
        } else {
            // echo "GAGAL mengembalikan stok";
        }
    }
}
for ($i = 0; $i < count($tranksaksi); $i++) {
    $waktu_tranksaksi[$i] = date_create($tranksaksi[$i]['waktu_tranksaksi']);
    $diff = date_diff($waktu_skrg, $waktu_tranksaksi[$i]); //menghitung selisih waktu sekarang dengan waktu tranksaksi
    $selisihJam[$i] = $diff->h; //diff->h artinya ambil selisih jam
    $id = $tranksaksi[$i]['id_transaksi'];
    $update_sql = "UPDATE transaksi SET sisa_waktu_pembayaran = '$selisihJam[$i]' WHERE id_transaksi = '$id'";
    $result = mysqli_query($conn, $update_sql);
    if ($result == true && $tranksaksi[$i]['status'] !== 'selesai' && $tranksaksi[$i]['status_pembayaran'] === 'Belum Lunas' && $tranksaksi[$i]['sisa_waktu_pembayaran'] >= 24) {
        //--- mengembalikan stok alsintan ---//
        $id_alsintan = $tranksaksi[$i]['id_alsintan'];
        $select_alsintan = "SELECT * FROM alsintan WHERE id_alsintan = '$id_alsintan'";
        $result = mysqli_query($conn, $select_alsintan);
        $als = mysqli_fetch_assoc($result);
        $stok = $tranksaksi[$i]['jumlah_alsintan'] + $als['jumlah_unit'];
        $update_stok = "UPDATE alsintan SET jumlah_unit = '$stok' WHERE id_alsintan = $id_alsintan";
        $result = mysqli_query($conn, $update_stok);
        if ($result) {
            echo "Berhasil Mengembalikan Stok 2";
            //--- menghapus tranksaksi jika belum dibayar dalam waktu 24 jam setelah tranksaksi diinputkan user ---//
            $delete_sql = "DELETE FROM transaksi WHERE id_transaksi = '$id'";
            $result = mysqli_query($conn, $delete_sql);
            if ($result) {
                $update_status = "UPDATE transaksi SET status = 'selesai' WHERE id_transaksi = $id";
                $result = mysqli_query($conn, $update_status);
                if ($result) {
                    echo "<script> alert('Terdapat tranksaksi yang dihapus karna terlambat membayar'); document.location.href = '../index.php'; </script>";
                } else {
                    echo "Gagal Update Status";
                }
            } else {
                echo "tranksaksi tidak terhapus";
            }
        } else {
            echo "GAGAL mengembalikan stok";
        }
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
    <link rel="stylesheet" href="lainnya/style-index-login-regis.css">
    <!-- <link rel="stylesheet" href="lainnya/style-topbar.css"> -->
    <title>Halaman Awal</title>
</head>

<body>
    <div class="topbar">
        <div class="web-logo">
            <a href="index.php">SEAL.COM</a>
            <img src="lainnya/tractor.png" alt="logo" class="logo">
        </div>
        <nav>
            <ul>
                <li><a class="one" href="#">Home</a></li>
                <li><a class="two" href="#">About</a></li>
                <li><a class="three" href="#">Login</a>
                    <ul>
                        <li class="dropdown"><a href="phpPenyewa/login.php">Penyewa</a></li>
                        <li class="dropdown"><a href="phpAdmin/login.php">Admin</a></li>
                    </ul>
                </li>
                <li><a href="#">Registrasi</a>
                    <ul>
                        <li class="dropdown"><a href="phpPenyewa/register.php">Penyewa</a></li>
                        <li class="dropdown"><a href="phpAdmin/regis.php">Admin</a></li>
                    </ul>
                </li>
            </ul>
        </nav>

    </div>
    <div class="card-index">
        <br><br>
        <h1>TINGKATKAN PRODUKTIFITAS PERTANIAN ANDA</h1>
        <p>SEWA AGRICULTURAL MACHINE</p>
        <p>LEBIH MUDAH DENGAN SEAL.COM</p><br><br>
        <button onclick="window.location.href = 'phpPenyewa/register.php'">Register Now</button>
    </div>


</body>

</html>