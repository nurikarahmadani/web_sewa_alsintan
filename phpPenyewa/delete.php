<?php
session_start();
require("../connect.php");
if (!isset($_SESSION['login_penyewa'])) {
  echo "<script>
      alert('Harus Login dulu !!!');
      document.location.href = 'login.php';
  </script>";
  exit;
}
$id_penyewa = $_SESSION['login_penyewa'];

$result = mysqli_query($conn, "DELETE FROM penyewa WHERE id_penyewa='$id_penyewa'");

if ($result) {
  session_unset();
  session_destroy();
  echo "<script> alert('data berhasil dihapus'); document.location.href = '../index.php'; </script>";
} else {
  echo "<script> alert('data gagal dihapus'); document.location.href = 'penyewa.php';</script>";
}
