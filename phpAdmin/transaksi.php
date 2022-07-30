<?php
require '../connect.php';
require '../lainnya/drawerNavigasiAdmin.html';
date_default_timezone_set('Asia/Makassar');
session_start();
if (!isset($_SESSION['login_admin'])) {
    echo "<script>
        alert('Harus Login dulu !!!');
        document.location.href = 'login.php';
    </script>";
    exit;
} else {
    $read_sql = "SELECT py.nama_penyewa, tr.id_transaksi, tr.total, tr.tanggal_sewa,
             tr.tanggal_pengembalian, tr.bukti_pembayaran, tr.status_pembayaran, tr.status_pengembalian
             FROM transaksi tr JOIN penyewa py ON tr.id_penyewa = py.id_penyewa ";
    $result = mysqli_query($conn, $read_sql);
    $transaksi = [];
    $alsintan = [];
    $hasil_tr;
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            if ($row['status_pembayaran'] === 'Lunas' && $row['status_pengembalian'] === 'Sudah Kembali') {
                continue; //agar hanya menampilkan tranksaksi yang belum lunas dan belum dikembalikan alsintannya
            } else {
                $transaksi[] = $row;
            }
        }
        for ($i = 0; $i < count($transaksi); $i++) {
            $idtr = $transaksi[$i]['id_transaksi'];
            $als_sql = "SELECT pn.jumlah_alsintan, al.nama_alsintan
                        FROM penyewaan pn JOIN alsintan al ON pn.id_alsintan = al.id_alsintan
                        WHERE id_transaksi = '$idtr'";
            $resultals = mysqli_query($conn, $als_sql);
            if ($resultals) {
                while ($als = mysqli_fetch_assoc($resultals)) {
                    $alsintan[$i][] = $als;
                }
                if (isset($_POST['buttonApprovedPembayaran'])) {
                    $id_tranksaksi = $_POST['buttonApprovedPembayaran'];
                    $update_sql = "UPDATE transaksi SET status_pembayaran = 'Lunas' WHERE id_transaksi = $id_tranksaksi";
                    $result = mysqli_query($conn, $update_sql);
                    if ($result) {
                        $hasil_tr = "berhasil";
                    } else {
                        $hasil_tr = "gagal";
                    }
                }
                // var_dump($alsintan);
                if (isset($_POST['buttonApprovedPengembalian'])) {
                    $id_tranksaksi = $_POST['buttonApprovedPengembalian'];
                    $update_sql = "UPDATE transaksi SET status_pengembalian = 'Sudah Kembali' WHERE id_transaksi = $id_tranksaksi";
                    $result = mysqli_query($conn, $update_sql);
                    if ($result) {
                        $hasil_tr = "berhasil";
                        $select_sql = "SELECT * FROM transaksi WHERE id_transaksi = $id_tranksaksi";
                        $result = mysqli_query($conn, $select_sql);
                        $tr;
                        if ($result) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                $tr = $row;
                            }
                            $img_pointer = "../tempImage/" . $tr['bukti_pembayaran'];
                            if (file_exists($img_pointer)) {
                                if (unlink($img_pointer)) {
                                    $tanggal_skrg = date_create(date("Y-m-d h:i:s"));
                                    $tanggal_pengembalian = date_create($tr['tanggal_pengembalian']);
                                    $diff = date_diff($tanggal_skrg, $tanggal_pengembalian);
                                    $selisih_hari = $diff->d;
                                    $max_date = max($tanggal_skrg, $tanggal_pengembalian);
                                    if ($max_date !== $tanggal_pengembalian && $selisih_hari > 0) {
                                        $denda = 150000;
                                        $total_denda = $selisih_hari * $denda;
                                        $insert_sql = "INSERT INTO keterlambatan(id_transaksi, lama_keterlambatan, jumlah_denda) VALUES ('$id_tranksaksi','$selisih_hari', '$total_denda')";
                                        $result = mysqli_query($conn, $insert_sql);
                                        if ($result) {
                                            echo "berhasil update pengembalian";
                                        } else {
                                            $hasil_tr = "gagal";
                                        }
                                    }
                                } else {
                                    echo "gagal Hapus foto";
                                }
                            }
                        } else {
                            echo mysqli_error($conn);
                        }
                    } else {
                        $hasil_tr = "gagal";
                    }
                }
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
    <div class="alert" id="alert">
        <span class="close-alert">&times;</span>
        <strong>Berhasil Approved</strong>
    </div>
    <div class="alert" id="alert-gagal">
        <span class="close-alert">&times;</span>
        <strong>Gagal Approved</strong>
    </div><br><br>
    <?php for ($i = 0; $i < count($transaksi); $i++) : ?>
        <div class="card-kelola">
            <img src="../tempImage/<?= $transaksi[$i]['bukti_pembayaran'] ?>" alt="Belum Upload Bukti Pembayaran"><br>
            <div class="detail">
                <table>
                    <tr>
                        <td>Penyewa</td>
                        <td>
                            <p>: <?= $transaksi[$i]['nama_penyewa'] ?></p>
                        </td>
                    </tr>
                    <tr>
                        <td>Total Bayar</td>
                        <td>
                            <p>: <?= $transaksi[$i]['total'] ?></p>
                        </td>
                    </tr>
                    <tr>
                        <td>Tanggal Sewa</td>
                        <td>
                            <p>: <?= $transaksi[$i]['tanggal_sewa'] ?> sd <?= $transaksi[$i]['tanggal_pengembalian'] ?></p>
                        </td>
                    </tr>
                    <tr>
                        <td>Alsintan</td>
                        <td><?php for ($j = 0; $j < count($alsintan[$i]); $j++) : ?>
                                <p> - <?= $alsintan[$i][$j]['nama_alsintan'] ?> ( <?= $alsintan[$i][$j]['jumlah_alsintan'] ?> )</p>
                            <?php endfor ?>
                        </td>
                    </tr>
                </table><br><br>
                <div class="card-btn-tr">
                    <form action="" method="POST">
                        <?php if ($transaksi[$i]['status_pembayaran'] == 'Belum Lunas') : ?>
                            <button class="btn-acc" name="buttonApprovedPembayaran" value="<?= $transaksi[$i]['id_transaksi'] ?>">Approved Pembayaran</button>
                        <?php endif ?>
                        <?php if ($transaksi[$i]['status_pengembalian'] == 'Belum Kembali') : ?>
                            <button class="btn-acc" name="buttonApprovedPengembalian" value="<?= $transaksi[$i]['id_transaksi'] ?>">Approved Pengembalian</button>
                        <?php endif ?>
                    </form>
                </div>
            </div>
        </div>
    <?php endfor ?>
    <br>
    <!------------ UNTUK NOTIFNYA ----------------->
    <script>
        var hasil_tr = "<?php echo "$hasil_tr" ?>"
        if (hasil_tr == 'berhasil') {
            const al = document.getElementById("alert");
            al.style.backgroundColor = 'rgba(175, 250, 180, 0.1)';
            setTimeout(function() {
                al.style.display = 'block';
            }, 800)
            setTimeout(function() {
                window.location.href = "transaksi.php";
            }, 3000)

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
</body>

</html>