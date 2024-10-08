<?php
session_start();
include __DIR__ . '/koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cwocAsal = mysqli_real_escape_string($koneksi3, $_POST['cwocAsal']);
    $sectAsal = mysqli_real_escape_string($koneksi3, $_POST['sectAsal']);
    $subsectAsal = mysqli_real_escape_string($koneksi3, $_POST['subsectAsal']);
    $Req = isset($_SESSION['npk']) ? $_SESSION['npk'] : '';

    $emnoArray = $_POST['emno'];
    $emnoList = array_map(function ($item) use ($koneksi3) {
        return mysqli_real_escape_string($koneksi3, $item);
    }, $emnoArray);
    $emno = implode(',', $emnoList);

    $tanggalMutasi = date('Y-m-d', strtotime($_POST['tanggalMutasi']));
    $cwocBaru = mysqli_real_escape_string($koneksi3, $_POST['cwocBaru']);
    $sectBaru = !empty($_POST['sectBaru']) ? mysqli_real_escape_string($koneksi3, $_POST['sectBaru']) : $sectAsal;
    $subsectBaru = !empty($_POST['subsectBaru']) ? mysqli_real_escape_string($koneksi3, $_POST['subsectBaru']) : $subsectAsal;
    date_default_timezone_set('Asia/Jakarta');
    $tanggalBuat = date("Y-m-d H:i:s");

    $shortCWOCAsal = str_replace(
        ['PRODUCTION 1', 'PRODUCTION 2', 'PRODUCTION 3', 'PRODUCTION 4', 'PRODUCTION 5', 'PRODUCTION SYSTEM', 'VENDOR DEVELOPMENT', 'GENERAL PURCHASE', 'MARKETING', 'WAREHOUSE'],
        ['Prod 1', 'Prod 2', 'Prod 3', 'Prod 4', 'Prod 5', 'Prod System', 'VDD', 'GP', 'MKT', 'WH'],
        $cwocAsal
    );
    $shortCWOCBaru = str_replace(
        ['PRODUCTION 1', 'PRODUCTION 2', 'PRODUCTION 3', 'PRODUCTION 4', 'PRODUCTION 5', 'PRODUCTION SYSTEM', 'VENDOR DEVELOPMENT', 'GENERAL PURCHASE'],
        ['Prod 1', 'Prod 2', 'Prod 3', 'Prod 4', 'Prod 5', 'Prod System', 'VDD', 'GP', 'MKT', 'WH'],
        $cwocBaru
    );

    // Buat batchMutasi dengan istilah singkat
    $batchMutasi = date("dmyHis");

    // Fetch sectAsalDesc and sectBaruDesc from the hrd_sect table
    $querySectAsal = "SELECT `desc` AS sectAsalDesc FROM hrd_sect WHERE sect = '$sectAsal'";
    $resultSectAsal = mysqli_query($koneksi2, $querySectAsal);
    $sectAsalDesc = $resultSectAsal ? mysqli_fetch_assoc($resultSectAsal)['sectAsalDesc'] : 'N/A';

    $querySectBaru = "SELECT `desc` AS sectBaruDesc FROM hrd_sect WHERE sect = '$sectBaru'";
    $resultSectBaru = mysqli_query($koneksi2, $querySectBaru);
    $sectBaruDesc = $resultSectBaru ? mysqli_fetch_assoc($resultSectBaru)['sectBaruDesc'] : 'N/A';

    $highestGolongan = 0; // Initialize highestGolongan
    $listKaryawan = array(); // List of employees for the notification

    foreach ($emnoArray as $emno) {
        $emno = mysqli_real_escape_string($koneksi2, $emno);

        // Query to fetch full_name, golongan, and acting based on emno
        $query_golongan = "SELECT full_name, golongan, acting FROM ct_users WHERE npk = '$emno'";
        $result_golongan = mysqli_query($koneksi2, $query_golongan);

        if (mysqli_num_rows($result_golongan) > 0) {
            $row = mysqli_fetch_assoc($result_golongan);
            $fullName = $row['full_name'];
            $golongan = $row['golongan'];
            $acting = $row['acting'];

            if ($golongan > $highestGolongan) {
                $highestGolongan = $golongan; // Update highestGolongan if current golongan is higher
            }

            $listKaryawan[] = "$emno - $fullName"; // Add to the employee list
            $listKaryawanStr = '';
            foreach ($listKaryawan as $index => $karyawan) {
                $listKaryawanStr .= ($index + 1) . ". $karyawan\n"; // Adding numbering
            }

            // Determine status and query to fetch phone numbers
            switch (true) {
                case ($golongan >= 0 && $golongan <= 2):
                    $status = 2;
                    $message = "Pemberitahuan mutasi!\n\nKami informasikan bahwa Batch Mutasi [$batchMutasi] sedang dalam proses pemindahan karyawan dari Departemen $cwocAsal section $sectAsalDesc ke departemen $cwocBaru section $sectBaruDesc, status saat ini menunggu approval Foreman $cwocAsal.\nBerikut daftar karyawan yang dimutasi:\n$listKaryawanStr\n\nMohon untuk segera memproses dan memberikan persetujuan sesuai prosedur yang berlaku.\nTerima kasih atas perhatian dan kerjasamanya.";

                    // Query pertama untuk mengambil data `npk` dari lembur.ct_users yang sesuai
                    $query_npk = "SELECT npk FROM ct_users WHERE golongan = 3 AND acting = 2 AND dept = '$cwocAsal'";
                    $result_npk = mysqli_query($koneksi2, $query_npk);

                    // Ambil hasil query npk
                    $npk_list = array();
                    if ($result_npk) {
                        while ($row = mysqli_fetch_assoc($result_npk)) {
                            $npk_list[] = "'" . $row['npk'] . "'";
                        }
                    }

                    if (!empty($npk_list)) {
                        // Query kedua untuk mengambil nomor HP dari isd.hp berdasarkan hasil query sebelumnya
                        $npk_list_str = implode(',', $npk_list);
                        $query_phone = "SELECT no_hp FROM hp WHERE npk IN ($npk_list_str)";
                        $result_phone = mysqli_query($koneksi4, $query_phone);
                    } else {
                        $query_phone = ""; // Kosongkan jika tidak ada npk yang sesuai
                    }
                    break;

                case ($golongan == 3):
                    $status = 3;
                    $message = "Pemberitahuan mutasi!\n\nKami informasikan bahwa Batch Mutasi [$batchMutasi] sedang dalam proses pemindahan karyawan dari Departemen $cwocAsal section $sectAsalDesc ke departemen $cwocBaru section $sectBaruDesc, status saat ini menunggu approval Supervisor $cwocAsal.\nBerikut daftar karyawan yang dimutasi:\n$listKaryawanStr\n\nMohon untuk segera memproses dan memberikan persetujuan sesuai prosedur yang berlaku.\nTerima kasih atas perhatian dan kerjasamanya.";

                    // Query pertama untuk mengambil data `npk` dari lembur.ct_users yang sesuai
                    $query_npk = "SELECT npk FROM ct_users WHERE golongan = 4 AND acting = 2 AND dept = '$cwocAsal'";
                    $result_npk = mysqli_query($koneksi2, $query_npk);

                    // Ambil hasil query npk
                    $npk_list = array();
                    if ($result_npk) {
                        while ($row = mysqli_fetch_assoc($result_npk)) {
                            $npk_list[] = "'" . $row['npk'] . "'";
                        }
                    }

                    if (!empty($npk_list)) {
                        // Query kedua untuk mengambil nomor HP dari isd.hp berdasarkan hasil query sebelumnya
                        $npk_list_str = implode(',', $npk_list);
                        $query_phone = "SELECT no_hp FROM isd.hp WHERE npk IN ($npk_list_str)";
                        $result_phone = mysqli_query($koneksi4, $query_phone);
                    } else {
                        $query_phone = ""; // Kosongkan jika tidak ada npk yang sesuai
                    }
                    break;

                case ($golongan == 4 && $acting == 2):
                    $status = 4;
                    $message = "Pemberitahuan mutasi!\n\nKami informasikan bahwa Batch Mutasi [$batchMutasi] sedang dalam proses pemindahan karyawan dari Departemen $cwocAsal section $sectAsalDesc ke departemen $cwocBaru section $sectBaruDesc, status saat ini menunggu approval Kepala Departemen $cwocAsal.\nBerikut daftar karyawan yang dimutasi:\n$listKaryawanStr\n\nMohon untuk segera memproses dan memberikan persetujuan sesuai prosedur yang berlaku.\nTerima kasih atas perhatian dan kerjasamanya.";

                    // Query pertama untuk mengambil data `npk` dari lembur.ct_users yang sesuai
                    $query_npk = "SELECT npk FROM ct_users WHERE dept = '$cwocAsal' AND npk IN (SELECT npk FROM hrd_so WHERE tipe = 1)";
                    $result_npk = mysqli_query($koneksi2, $query_npk);

                    // Ambil hasil query npk
                    $npk_list = array();
                    if ($result_npk) {
                        while ($row = mysqli_fetch_assoc($result_npk)) {
                            $npk_list[] = "'" . $row['npk'] . "'";
                        }
                    }

                    if (!empty($npk_list)) {
                        // Query kedua untuk mengambil nomor HP dari isd.hp berdasarkan hasil query sebelumnya
                        $npk_list_str = implode(',', $npk_list);
                        $query_phone = "SELECT no_hp FROM hp WHERE npk IN ($npk_list_str)";
                        $result_phone = mysqli_query($koneksi4, $query_phone);
                    } else {
                        $query_phone = ""; // Kosongkan jika tidak ada npk yang sesuai
                    }
                    break;

                default:
                    $status = 1;
                    continue 2; // Skip ke iterasi berikutnya
            }

            $query_insert_mutasi = "INSERT INTO mutasi (cwocAsal, sectAsal, subsectAsal, emno, tanggalMutasi, cwocBaru, sectBaru, subsectBaru, status, tanggalBuat, batchMutasi, Req) 
            VALUES ('$cwocAsal', '$sectAsal', '$subsectAsal', '$emno', '$tanggalMutasi', '$cwocBaru', '$sectBaru', '$subsectBaru', '$status', '$tanggalBuat', '$batchMutasi', '$Req')";
            mysqli_query($koneksi3, $query_insert_mutasi);

            $query_insert_batch = "INSERT INTO batch (batchMutasi, npk) VALUES ('$batchMutasi', '$emno')";
            mysqli_query($koneksi3, $query_insert_batch);
        } else {
            echo "Golongan karyawan tidak ditemukan untuk NPK $emno.";
        }
    }

    // Fetch phone numbers and send notification once
    $result_phone = mysqli_query($koneksi4, $query_phone);
    $phone_numbers = array();

    if ($result_phone) {
        while ($phone_row = mysqli_fetch_assoc($result_phone)) {
            $phone_numbers[] = $phone_row['no_hp'];
        }
    }

    // Insert into notif table for each valid phone number
    foreach ($phone_numbers as $phone_number) {
        $query_insert_notif = "INSERT INTO notif (phone_number, message, flags) VALUES ('$phone_number', '$message', 'queue')";
        mysqli_query($koneksi3, $query_insert_notif);
    }

    // Determine the final status based on the highest golongan encountered
    if ($highestGolongan >= 0 && $highestGolongan <= 2) {
        $finalStatus = 2;
    } elseif ($highestGolongan == 3) {
        $finalStatus = 3;
    } elseif ($highestGolongan == 4) {
        $finalStatus = 4;
    }

    // Update the mutasi records to reflect the final status
    $query_update_status = "UPDATE mutasi SET status = '$finalStatus' WHERE batchMutasi = '$batchMutasi'";
    mysqli_query($koneksi3, $query_update_status);
}
?>