<?php
require '../connect.php';
session_start();
if (!isset($_SESSION['login_admin'])) {
    echo "<script>
        alert('Harus Login dulu !!!');
        document.location.href = 'login.php';
    </script>";
    exit;
} else {
    $id_alsintan = $_GET["id_alsintan"];
    // Hapus file gambar dari direktori
    $select_sql = "SELECT * FROM alsintan WHERE id_alsintan = $id_alsintan";
    $result = mysqli_query($conn, $select_sql);
    $alsintan;
    while ($row = mysqli_fetch_assoc($result)) {
        $alsintan = $row;
    }
    if ($result) {
        $img_pointer = "../tempImage/" . $alsintan['Foto']; //buat direktori img yang akan dihapus
        if (!unlink($img_pointer)) { //gunakan fungsi unlink untuk delete file img
            echo ("$img_pointer cannot be deleted due to an error");
        } else {
            $delete_sql = "DELETE FROM alsintan WHERE id_alsintan = $id_alsintan";
            $result = mysqli_query($conn, $delete_sql);
            if ($result) {
                echo "<script>
                alert('Data berhasil dihapus!');
                document.location.href = 'alsintan.php';
            </script>";
            } else {
                echo "<script>
                alert('Data tidak berhasil dihapus!');
                document.location.href = 'alsintan.php';
            </script>";
            }
        }
    } else {
        echo "<script>
            alert('Data tidak Ditemukan!');
            document.location.href = 'alsintan.php';
        </script>";
    }
}
