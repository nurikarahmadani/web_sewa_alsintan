<?php
    $server = "localhost";
    $username = "root";
    $password = "";
    $dbname = "projek_akhir";

    $conn = mysqli_connect($server,$username,$password,$dbname);

    if (!$conn) {
        die("Gagal terkoneksi ke database".mysqli_connect_error());
    }
?>