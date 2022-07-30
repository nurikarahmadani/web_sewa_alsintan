<?php
session_start();
if (!isset($_SESSION['login_penyewa'])) {
    echo "<script>
        alert('Harus Login dulu !!!');
        document.location.href = 'login.php';
    </script>";
    exit;
}
require '../connect.php';
require '../lainnya/drawerNavigasiPenyewa.php';

if (isset($_POST['kirim'])) {
    $id_alsintan = $_POST["id_alsintan"];
    date_default_timezone_set('Asia/Makassar'); //sesuaikan timezone ke wita
    $id_penyewa = $_SESSION['login_penyewa'];
    $jumlah_unit = $_POST["jumlah_alsintan"];
    $tanggal = $_POST["tanggal_sewa"];
    $waktuTranksaksi = date("Y-m-d h:i:s");
    $durasi = $_POST["jangka_wkt_sewa"];
    $tglKembali = date('Y-m-d', strtotime('+' . $durasi . ' days', strtotime($tanggal))); //mencari tanggal pengembalian
    // var_dump($_POST['jumlah_']);
    $select_sql = "SELECT * FROM alsintan WHERE id_alsintan = $id_alsintan";
    $result = mysqli_query($conn, $select_sql);
    $alsintan = mysqli_fetch_assoc($result);
    if ($alsintan['jumlah_unit'] >= $jumlah_unit) { //cek apakah stok mencukupi
        $sisa = $alsintan['jumlah_unit'] - $jumlah_unit;
        $total = $alsintan['harga_sewa'] * $jumlah_unit;
        $update_sql = "UPDATE alsintan SET jumlah_unit = '$sisa' WHERE id_alsintan = $id_alsintan";
        $result = mysqli_query($conn, $update_sql);
        if ($result) {
            $create_sql = "INSERT INTO transaksi(id_penyewa, tanggal_sewa, waktu_tranksaksi, jangka_waktu_sewa, tanggal_pengembalian, total, status_pembayaran, status_pengembalian) VALUES ('$id_penyewa','$tanggal', '$waktuTranksaksi','$durasi', '$tglKembali', '$total', 'Belum Lunas', 'Belum Kembali')";
            $result = mysqli_query($conn, $create_sql);
            if ($result) {
                // echo "Berhasil ditambahkan";
                echo "<script>
                    alert('Data berhasil ditambahkan!');
                    document.location.href = 'alsintan.php';
                </script>";
            } else {
                echo "<script>
                    alert('Data gagal ditambahkan!');
                    document.location.href = 'tranksaksi.php';
                </script>";
            }
        } else {
            // echo "Gagal Update jumlah alsintan";
            echo "<script>
                alert('Gagal Update Jumlah Alsintan !!!');
                document.location.href = 'tranksaksi.php';
            </script>";
        }
    } else {
        // echo "Jumlah unit tidak mencukupi";
        echo "<script>
            alert('Jumlah Unit Tidak Mencukupi !!!');
            document.location.href = 'alsintan.php';
        </script>";
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
    <title>Form Tranksaksi</title>
</head>

<body>

    <div class="card-form">
        <h2>Form Penyewaan</h2><br>
        <form action="tranksaksi.php" method="POST">
        <input type="hidden" name="id_alsintan" value="<?= $_GET["btn-sewa"]; ?>">
            <table>
                <tr>
                    <td>Jumlah Unit</td>
                    <td><input type="text" name="jumlah_alsintan"></td>
                </tr>
                <tr>
                    <td>Tanggal Sewa</td>
                    <td><input type="date" name="tanggal_sewa"></td>
                </tr>
                <tr>
                    <td>Jangka Waktu Sewa</td>
                    <td><input type="number" name="jangka_wkt_sewa"></td>
                </tr>
            </table>
            <td> <button class="btn" name="kirim">Kirim</button></td>
        </form>
        <!-- <form action="" method="POST">
            <button class="btn" name="kirim">Kirim</button>
        </form> -->

    </div>

    <script type="text/javascript" src="../lainnya/helper.js"></script>
</body>

</html>