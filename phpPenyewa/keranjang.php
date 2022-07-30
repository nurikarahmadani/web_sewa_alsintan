<?php
require "../connect.php";
require "../lainnya/drawerNavigasiPenyewa.php";
session_start();
if (!isset($_SESSION['login_penyewa'])) {
    echo "<script>
        alert('Harus Login dulu !!!');
        document.location.href = 'login.php';
    </script>";
    exit;
} else {
    $id_penyewa = $_SESSION['login_penyewa'];
    $jumlah = [];
    $subtotal = 0;
    $alsintan = [];
    $jumlah_alsintan = [];
    $total = 0;
    $hasil_tr;
    if (isset($_SESSION['keranjang'])) {
        $id_alsintan = $_SESSION['keranjang'];
        foreach ($id_alsintan as $id) {
            $select_sql = "SELECT * FROM alsintan WHERE id_alsintan = $id";
            $result = mysqli_query($conn, $select_sql);
            while($row_als = mysqli_fetch_assoc($result)){
                $alsintan[] = $row_als;
            }
        }
        if (isset($_POST['btn-hapus'])) {
            $id = $_POST['btn-hapus'];
            if (($key = array_search($_POST['btn-hapus'], $id_alsintan)) !== false) {
                unset($_SESSION['keranjang'][$key]);
                unset($id_alsintan[$key]);
                header('location:keranjang.php');
            }
        }
    }
    if (!empty($_POST["op"])) {
        for ($i = 0; $i < count($id_alsintan); $i++) {
            $jumlah[$i] = $_POST['jumlah'][$i];
            $subtotal = $subtotal + ($jumlah[$i] * $alsintan[$i]['harga_sewa']);
        }
        $total = $subtotal * $_POST['lama-sewa'];
        if (isset($_POST['btn-sewa'])) {
            date_default_timezone_set('Asia/Makassar'); //sesuaikan timezone ke wita
            $id_penyewa = $_SESSION['login_penyewa'];
            $tanggal = $_POST["tgl-sewa"];
            $waktuTranksaksi = date("Y-m-d h:i:s");
            $durasi = $_POST["lama-sewa"];
            $tglKembali = date('Y-m-d', strtotime('+' . $durasi . ' days', strtotime($tanggal))); //mencari tanggal pengembalian
            $create_sql = "INSERT INTO transaksi(id_penyewa, tanggal_sewa, waktu_tranksaksi, jangka_waktu_sewa, tanggal_pengembalian, total, status_pembayaran, status_pengembalian) VALUES ('$id_penyewa','$tanggal', '$waktuTranksaksi','$durasi', '$tglKembali', '$total', 'Belum Lunas', 'Belum Kembali')";
            $result = mysqli_query($conn, $create_sql);
            if ($result) {
                // define("current_id", mysqli_insert_id($conn));
                $current_id = mysqli_insert_id($conn); //untuk manegambil id terakhir yang diinsert ke db
                for ($i = 0; $i < count($id_alsintan); $i++) {
                    $insert_sql = "INSERT INTO penyewaan(id_transaksi, id_alsintan, jumlah_alsintan) VALUES('$current_id', '$id_alsintan[$i]','$jumlah[$i]')";
                    $result = mysqli_query($conn, $insert_sql);
                    if ($result) {
                        $select_sql = "SELECT * FROM alsintan WHERE id_alsintan = $id_alsintan[$i]";
                        $result = mysqli_query($conn, $select_sql);
                        $alsintandb = mysqli_fetch_assoc($result);
                        if ($alsintandb['jumlah_unit'] >= $jumlah[$i]) { //cek apakah stok mencukupi
                            $sisa = $alsintandb['jumlah_unit'] - $jumlah[$i];
                            $update_sql = "UPDATE alsintan SET jumlah_unit = '$sisa' WHERE id_alsintan = $id_alsintan[$i]";
                            $result = mysqli_query($conn, $update_sql);
                            if ($result) {
                                $_SESSION['keranjang'] = array();
                                $hasil_tr = "berhasil";
                            } else {
                                $hasil_tr = "gagal";
                            }
                        } else {
                            // echo mysqli_error($conn);
                            $hasil_tr = "stok kurang";
                        }
                    } else {
                        // echo mysqli_error($conn);
                        $hasil_tr = "gagal";
                    }
                }
            } else {
                $hasil_tr = "gagal";
            }
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
    <link rel="stylesheet" href="../lainnya/style.css">
    <link rel="stylesheet" href="../lainnya/font-awesome-4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../lainnya/jquery.nice-number.css">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <title>Keranjang</title>
</head>

<body>
    <div class="alert" id="alert">
        <span class="close-alert">&times;</span>
        <strong>Tranksaksi Berhasil</strong>
    </div>
    <div class="alert" id="alert-stok-kurang">
        <span class="close-alert">&times;</span>
        <strong>Stok Alsintan Kurang</strong>
    </div>
    <div class="alert" id="alert-gagal">
        <span class="close-alert">&times;</span>
        <strong>Tranksaksi Gagal</strong>
    </div>
    <div class="container-keranjang">
        <form class="keranjang" action="" method="POST">
            <div class="col">
                <h2>KERANJANG</h2><br>
                <?php foreach ($alsintan as $als) : ?>
                    <div class="card-keranjang">
                        <img src="../tempImage/<?= $als['Foto'] ?>" alt="Gambar"><br>
                        <div class="detail">
                            <h2><?= $als["nama_alsintan"]; ?></h2>
                            <p>Unit Yang Tersedia = <?= $als['jumlah_unit'] ?></p><br><br>
                            <p><b></b> Rp.<?= $als["harga_sewa"]; ?> / hari</p>
                        </div>
                        <div class="aksi">
                            <button class="btn-hapus" name="btn-hapus" value="<?= $als['id_alsintan'] ?>"><i class="fa fa-trash-o"></i></button>
                            <div class="garis"></div>
                            <input id="jumlah" name="jumlah[]" type=number min=1 max=100 value="1" />
                        </div>
                    </div>
                <?php endforeach ?><br>

            </div>
            <div class="trank">
                <table class="tb-keranjang">
                    <tr>
                        <td><label for="lama-sewa">Lama Sewa</label></td>
                        <td><input type="text" name="lama-sewa" value="1"></td>
                    </tr>
                    <tr>
                        <td><label for="tgl-sewa">Tanggal Sewa</label></td>
                        <td><input type="date" name="tgl-sewa"></td>
                    </tr>
                </table>
                <input type="hidden" name="op" value="sent" /><br>
                <button class="btn-keranjang" type="submit" name="btn-sewa" value="">Sewa</button>
            </div>

        </form>
        <?php
        ?>
    </div>
    <!------------ UNTUK NOTIFNYA ----------------->
    <script>
        var hasil_tr = "<?php echo "$hasil_tr" ?>"
        if (hasil_tr == 'berhasil') {
            const al = document.getElementById("alert");
            al.style.backgroundColor = 'rgba(175, 250, 180, 0.1)';
            setTimeout(function() {
                al.style.display = 'block';
            }, 800)
            setTimeout(function(){
                window.location.href = "pembayaran.php";
            }, 3000)

        } else if (hasil_tr = 'stok kurang') {
            const al = document.getElementById("alert-stok-kurang");
            setTimeout(function() {
                al.style.display = 'block';
            }, 600)
        } else {
            const al = document.getElementById("alert-gagal");
            setTimeout(function() {
                al.style.display = 'block';
            }, 600)
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="../lainnya/jquery.nice-number.js"></script>
    <script>
        $(function() {
            $('input[type="number"]').niceNumber();
        });
    </script>
</body>

</html>