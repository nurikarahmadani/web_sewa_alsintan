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
}
$id_admin = $_SESSION['login_admin'];
if ($id_admin == 1) {
    $select_sql = "SELECT * FROM admin WHERE status != 'submitted'";
    $result  = mysqli_query($conn, $select_sql);
    $karyawan = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $karyawan[] = $row;
    }
} else {
    echo "<script>
        alert('Hanya Untuk Admin Bos !!!');
        document.location.href = 'alsintan.php';
    </script>";
}
if (isset($_POST['button-acc'])) {
    $id_karyawan = $_POST['button-acc'];
    $update_sql = "UPDATE admin SET status = 'submitted' WHERE id_admin = $id_karyawan";
    $result = mysqli_query($conn, $update_sql);
    if ($result) {
        echo "<script>
            alert('Berhasil ACC Data');
            document.location.href = 'submit-karyawan.php';
        </script>";
    } else {
        echo "<script>
            alert('Data tidak berhasil di-ACC');
            document.location.href = 'editadmin.php';
        </script>";
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
    <title>Submit Karyawan</title>
</head>

<body>
    <div class="container">
        <div class="card-table">
            <h2> Data Admin </h2><br>
            <table border="1">
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Password</th>
                    <th>No HP</th>
                    <th>Email</th>
                    <th>Action</th>
                </tr>
                <?php $n = 1; ?>
                <?php foreach ($karyawan as $kar) : ?>
                    <tr>
                        <td><?= $n; ?></td>
                        <td><?= $kar["nama_admin"]; ?></td>
                        <td><?= $kar["pass_admin"]; ?></td>
                        <td><?= $kar["noHP_admin"]; ?></td>
                        <td><?= $kar["email_admin"]; ?></td>
                        <td>
                            <!-- <a href="editadmin.php?id_admin=<?= $kar["id_admin"]; ?>"><button>EDIT</button></a> -->
                            <form action="" method="POST"><button class="btn" name="button-acc" value="<?= $kar['id_admin'] ?>">ACC</button></form>

                        </td>
                    </tr>
                    <?php $n++;  ?>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
    <script type="text/javascript" src="../lainnya/helper.js"></script>
</body>

</html>