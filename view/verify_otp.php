<?php
session_start();
require_once(__DIR__ . '/../query/koneksi.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $otp1 = $_POST['otp1'];
    $otp2 = $_POST['otp2'];
    $otp3 = $_POST['otp3'];
    $otp4 = $_POST['otp4'];
    $otp5 = $_POST['otp5'];
    $otp6 = $_POST['otp6'];

    $entered_otp = $otp1 . $otp2 . $otp3 . $otp4 . $otp5 . $otp6;
    $npk = $_SESSION['npk'];

    // Check OTP against the database
    $sql = "SELECT otp FROM otp WHERE npk = '$npk'";
    $result = mysqli_query($koneksi3, $sql);

    if ($row = mysqli_fetch_assoc($result)) {
        $stored_otp = $row['otp'];
        if ($entered_otp == $stored_otp) {
            // OTP is correct, set session flag and return success
            $_SESSION['otp_verified'] = true;
            echo json_encode(['status' => 'success', 'redirect_url' => $_SESSION["redirect_url"]]);
        } else {
            // OTP is incorrect, return error
            echo json_encode(['status' => 'error', 'message' => 'Invalid OTP. Please try again.']);
        }
    } else {
        // No OTP found in the database
        echo json_encode(['status' => 'error', 'message' => 'Invalid OTP. Please try again.']);
    }

    mysqli_close($koneksi3);
    exit;
}
?>