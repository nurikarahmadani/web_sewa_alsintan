<?php
session_start();
unset($_SESSION['login_penyewa']);
var_dump($_SESSION['login_penyewa']);
header('location: ../index.php');
?>