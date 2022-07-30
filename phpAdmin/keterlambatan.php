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
    $read_sql = "SELECT tr.id_transaksi, py.nama_penyewa, kt.lama_keterlambatan, kt.jumlah_denda, kt.bukti_pembayaran, kt.status_pembayaran 
                 from transaksi tr JOIN keterlambatan kt ON tr.id_transaksi = kt.id_transaksi
                 JOIN penyewa py ON tr.id_penyewa = py.id_penyewa";
    $result = mysqli_query($conn, $read_sql);
    $keterlambatan = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            if ($row['status_pembayaran'] === 'Lunas') {
                continue; 
            } else {
                $keterlambatan[] = $row;
            }
        }
        if (isset($_POST['buttonApprovedPembayaran'])) {
            $id_tranksaksi = $_POST['buttonApprovedPembayaran'];
            var_dump($id_tranksaksi);
            $update_sql = "UPDATE keterlambatan SET status_pembayaran = 'Lunas' WHERE id_transaksi = $id_tranksaksi";
            $result = mysqli_query($conn, $update_sql);
            if ($result) {
                echo "<script>
                alert('Berhasil Update status Pembayaran Denda!!!');
                document.location.href = 'keterlambatan.php';
            </script>";
                header("refresh: 2");
            } else {
                echo mysqli_error($conn);
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
    <title>Penyewaan Alsintan</title>
</head>

<body>

    <?php for ($i = 0; $i < count($keterlambatan); $i++) : ?>
        <div class="card-kelola">
            <img src="../tempImage/<?= $keterlambatan[$i]['bukti_pembayaran'] ?>" alt="Belum Upload Bukti Pembayaran"><br>
            <div class="detail">
                <table>
                    <tr>
                        <td>Nama Penyewa</td>
                        <td>
                            <p>: <?= $keterlambatan[$i]['nama_penyewa'] ?></p>
                        </td>
                    </tr>
                    <tr>
                        <td>Lama Keterlambatan</td>
                        <td>
                            <p>: <?= $keterlambatan[$i]['lama_keterlambatan'] ?></p>
                        </td>
                    </tr>
                    <tr>
                        <td>Total denda</td>
                        <td>
                            <p>: Rp. <?= $keterlambatan[$i]['jumlah_denda'] ?></p>
                        </td>
                    </tr>
                </table>
                <div class="card-btn">
                    <form action="" method="POST">
                        <?php if ($keterlambatan[$i]['status_pembayaran'] !== 'Lunas') : ?>
                            <button class="btn-acc" name="buttonApprovedPembayaran" value="<?= $keterlambatan[$i]['id_transaksi'] ?>">Approved Pembayaran</button>
                        <?php endif ?>
                    </form>
                </div>
            </div>
        </div>
    <?php endfor ?>
    <br>
    <script type="text/javascript" src="../lainnya/helper.js"></script>
</body>

</html>