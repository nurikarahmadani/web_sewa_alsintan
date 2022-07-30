<?php
require '../connect.php';
require '../lainnya/drawerNavigasiPenyewa.php';

$id = $_POST['btn-struk'];
$struk = [];
$alsintan = [];
$select_sql = "SELECT tr.id_transaksi,tr.waktu_tranksaksi, tr.total, tr.tanggal_sewa, tr.tanggal_pengembalian, py.nama_penyewa
               FROM transaksi tr JOIN penyewa py ON tr.id_penyewa = py.id_penyewa 
               WHERE id_transaksi = '$id'";
$result = mysqli_query($conn, $select_sql);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $struk[] = $row;
    }
    for ($i = 0; $i < count($struk); $i++) {
        $als_sql = "SELECT pn.jumlah_alsintan, al.nama_alsintan
                    FROM penyewaan pn JOIN alsintan al ON pn.id_alsintan = al.id_alsintan
                    WHERE id_transaksi = '$id'";
        $resultals = mysqli_query($conn, $als_sql);
        if ($resultals) {
            while ($als = mysqli_fetch_assoc($resultals)) {
                $alsintan[$i][] = $als;
            }
        } else {
            echo mysqli_error($conn);
        }
    }
} else {
    echo mysqli_error($conn);
}
// var_dump($alsintan);
// var_dump($struk);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../lainnya/style.css">
    <title>Struk Pembayaran</title>
</head>

<body>
    <div class="container">
        <div class="card-table">
            <h2>Struk Pembayaran</h2><br>
            <div class="struk">
                <table border="1">
                    <tr>
                        <td>Nomor Tranksaksi</td>
                        <td>
                            <p><?= $struk[0]['tanggal_sewa'] . "-" . $struk[0]['id_transaksi'] ?></p>
                        </td>
                    </tr>
                    <tr>
                        <td>Nama Penyewa</td>
                        <td>
                            <p><?= $struk[0]['nama_penyewa'] ?></p>
                        </td>
                    </tr>
                    <tr>
                        <td>Alsintan</td>
                        <td><?php for ($j = 0; $j < count($alsintan[0]); $j++) : ?>
                                <p> - <?= $alsintan[0][$j]['nama_alsintan'] ?> ( <?= $alsintan[0][$j]['jumlah_alsintan'] ?> unit )</p>
                            <?php endfor ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Jangka Waktu Sewa</td>
                        <td><?= $struk[0]['tanggal_sewa'] ?> - <?= $struk[0]['tanggal_pengembalian'] ?></td>
                    </tr>
                    <tr>
                        <td>Total</td>
                        <td>
                            <p>Rp. <?= $struk[0]['total']; ?></p>
                        </td>
                    </tr>

                </table><br><br>
            </div>
            <td> <button class="btn" name="kirim" onClick="kembali()">Kembali</button></td>
        </div><br><br><br>

    </div>
    <script type="text/javascript" src="../lainnya/helper.js"></script>
    <script>
        function kembali() {
            document.location.href = "pembayaran.php";
        }
    </script>
</body>

</html>