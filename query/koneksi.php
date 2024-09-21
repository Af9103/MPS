<?php
$server = "127.0.0.1";
$user = "root";
$password = "";
$database3 = "mutasi";
$database2 = "lembur";
$database = "cobamps";

$koneksi3 = mysqli_connect($server, $user, $password, $database3);

$koneksi2 = mysqli_connect($server, $user, $password, $database2);

$koneksi = mysqli_connect($server, $user, $password, $database);

if (!$koneksi3) {
     echo "Koneksi database gagal : " . mysqli_connect_error();
}
if (!$koneksi2) {
     echo "Koneksi database2 gagal : " . mysqli_connect_error();
}
if (!$koneksi) {
     echo "Koneksi database2 gagal : " . mysqli_connect_error();
}
?>