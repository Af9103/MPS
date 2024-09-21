<?php
session_start();

// Periksa apakah pengguna sudah login, jika belum, redirect ke halaman login
if (!isset($_SESSION["npk"]) || !isset($_SESSION["dept"]) || !isset($_SESSION['otp_verified']) || $_SESSION['otp_verified'] !== true) {
    echo "Anda harus login terlebih dahulu";
    header("Location: ../forbidden.php");
    exit; // Pastikan untuk menghentikan eksekusi skrip setelah melakukan redirect
}
include __DIR__ . '/koneksi.php';

$user_department = isset($_SESSION['dept']) ? $_SESSION['dept'] : '';

// Handle when cwoc is selected
if (isset($_POST['cwoc'])) {
    $cwoc = mysqli_real_escape_string($koneksi2, $_POST['cwoc']);
    $query = mysqli_query($koneksi2, "SELECT DISTINCT s.sect, s.`desc` FROM hrd_sect s JOIN ct_users k ON k.sect = s.sect WHERE k.dept = '$cwoc' AND approved = 1");

    if ($query) {
        if (mysqli_num_rows($query) > 0) {
            echo '<option value="" disabled selected hidden>Pilih Seksi</option>';
            while ($row = mysqli_fetch_assoc($query)) {
                echo "<option value='" . htmlspecialchars($row['sect'], ENT_QUOTES, 'UTF-8') . "'>" . htmlspecialchars($row['desc'], ENT_QUOTES, 'UTF-8') . "</option>";
            }
        } else {
            echo '<option value="" disabled selected hidden>Tidak ada seksi tersedia</option>';
        }
    } else {
        echo '<option value="" disabled selected hidden>Error fetching data</option>';
    }

    exit;
}
// Handle when sectAsal changes
if (isset($_POST['id_sect'])) {
    $id_sect = mysqli_real_escape_string($koneksi2, $_POST['id_sect']);

    $query = mysqli_query($koneksi2, "SELECT DISTINCT s.subsect, s.`desc`FROM hrd_subsect s JOIN ct_users k ON k.subsect = s.subsect WHERE k.sect = '$id_sect' AND approved = 1");

    if ($query) {
        if (mysqli_num_rows($query) > 0) {
            echo '<option value="" disabled selected hidden>Pilih Subseksi</option>';
            while ($row = mysqli_fetch_assoc($query)) {
                echo "<option value='" . htmlspecialchars($row['subsect'], ENT_QUOTES, 'UTF-8') . "'>" . htmlspecialchars($row['desc'], ENT_QUOTES, 'UTF-8') . "</option>";
            }
        } else {
            echo '<option value="" disabled selected hidden>No Sub Section available</option>';
        }
    } else {
        echo '<option value="" disabled selected hidden>Error fetching data</option>';
    }
    exit;
}
if (isset($_POST['id_subsect'])) {
    $id_subsect = mysqli_real_escape_string($koneksi2, $_POST['id_subsect']);
    $user_department = isset($_SESSION['dept']) ? $_SESSION['dept'] : '';

    if ($_SESSION['role'] == 'Foreman' || $_SESSION['role'] == 'Foreman HRD') {
        $query = mysqli_query($koneksi2, "SELECT DISTINCT npk, full_name FROM ct_users WHERE dept = '$user_department' AND subsect = '$id_subsect' AND golongan <=2 AND approved = 1");
    } elseif ($_SESSION['role'] == 'Supervisor' || $_SESSION['role'] == 'Supervisor HRD') {
        $query = mysqli_query($koneksi2, "SELECT DISTINCT npk, full_name FROM ct_users WHERE dept = '$user_department' AND subsect = '$id_subsect' AND golongan <=3 AND approved = 1");
    } elseif ($_SESSION['role'] == 'Kepala Departemen' || $_SESSION['role'] == 'Kepala Departemen HRD') {
        $query = mysqli_query($koneksi2, "SELECT DISTINCT npk, full_name FROM ct_users WHERE dept = '$user_department' AND subsect = '$id_subsect' AND golongan <= 4 AND acting = 2 AND approved = 1");
    } else {
        $query = mysqli_query($koneksi2, "SELECT DISTINCT npk, full_name FROM ct_users WHERE dept = '$user_department' AND subsect = '$id_subsect' AND approved = 1");
    }

    //perubahan
    if ($query && mysqli_num_rows($query) > 0) {
        $npks = array();
        while ($row = mysqli_fetch_assoc($query)) {
            $npks[$row['npk']] = $row['full_name'];
        }

        if (!empty($npks)) {
            $npkList = implode("','", array_keys($npks));

            // Query to find npks in the mutasi table
            $statusQuery = "SELECT emno, status, hapus FROM mutasi WHERE emno IN ('$npkList')";
            $statusResult = mysqli_query($koneksi3, $statusQuery);

            if ($statusResult) {
                $npkStatus = array();
                while ($row = mysqli_fetch_assoc($statusResult)) {
                    $npkStatus[$row['emno']] = $row;
                }
                // Display the options
                echo '<option value="" disabled hidden>Pilih Karyawan</option>';
                foreach ($npks as $npk => $full_name) {

                    $status = $npkStatus[$npk]['status'];
                    $hapus = $npkStatus[$npk]['hapus'];

                    if ($status == 10 || $hapus == 1 || $status == 100 || !isset($npkStatus[$npk])) {
                        echo "<option value='" . htmlspecialchars($npk, ENT_QUOTES, 'UTF-8') . "'>" . htmlspecialchars($npk, ENT_QUOTES, 'UTF-8') . " - " . htmlspecialchars($full_name, ENT_QUOTES, 'UTF-8') . "</option>";
                    }
                }
            } else {
                echo '<option value="" disabled selected hidden>Error checking status</option>';
            }
        } else {
            echo '<option value="" disabled selected hidden>Tidak ada karyawan yang terssedia</option>';
        }
    }
    exit; // Exit to prevent further output
}
?>