<?php

include __DIR__ . '/query/koneksi.php';
include __DIR__ . '/query/utils.php';

$encryption_key = 'your_secret_key_32_bytes'; // Ensure this is the same key used for encryption
$iv = 'your_iv_16_bytes'; // Ensure this is the same IV used for encryption

// Retrieve and decrypt values from URL
$encrypted_batchMutasi = $_POST['batchMutasi']; // Ensure this is properly sanitized
$encrypted_emno = $_POST['emno']; // Ensure this is properly sanitized

// Decrypt the values
$batchMutasi = decrypt($encrypted_batchMutasi, $encryption_key);
$emno = decrypt($encrypted_emno, $encryption_key);

$batchMutasi = mysqli_real_escape_string($koneksi3, $batchMutasi);
$emno = mysqli_real_escape_string($koneksi3, $emno);
mysqli_query($koneksi3, "SET time_zone = 'Asia/Jakarta'");


// Prepare and execute SQL query
$sql = "UPDATE mutasi SET cek = 1, tgl_cek = NOW() WHERE batchMutasi = '$batchMutasi' AND emno = '$emno'";
if (mysqli_query($koneksi3, $sql)) {
    echo "Sukses";
} else {
    echo "Error updating record: " . mysqli_error($koneksi3);
}