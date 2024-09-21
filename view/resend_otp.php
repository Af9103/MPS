<?php
require_once(__DIR__ . '/../query/koneksi.php');

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $npk = $_POST['npk'];
    $otp_code = sprintf('%06d', mt_rand(0, 999999)); // Generate a 6-digit OTP

    // Update OTP in the database
    $sql_update = "UPDATE otp SET otp = '$otp_code', send = '2', `use` = '2' WHERE npk = '$npk'";
    if (mysqli_query($koneksi3, $sql_update)) {
        $_SESSION["otp_code"] = $otp_code;
        $otpSentTime = time(); // Waktu pengiriman OTP saat ini dalam detik
        $_SESSION['otp_sent_time'] = $otpSentTime; // Simpan waktu pengiriman OTP di session
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error updating OTP.']);
    }
}
?>