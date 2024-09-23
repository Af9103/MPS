<?php

include __DIR__ . '/koneksi.php';

session_start();

// Periksa apakah pengguna sudah login, jika belum, redirect ke halaman login
if (!isset($_SESSION["npk"]) || !isset($_SESSION["dept"]) || !isset($_SESSION['otp_verified']) || $_SESSION['otp_verified'] !== true) {
    echo "Anda harus login terlebih dahulu";
    header("Location: ../forbidden.php");
    exit; // Pastikan untuk menghentikan eksekusi skrip setelah melakukan redirect
}

if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] == 'Foreman HRD') {
        $queryMutasi = "SELECT *
                      FROM mutasi
                      WHERE hapus IS NULL AND (mutasi.status = '9' OR (mutasi.status = '2' AND mutasi.cwocAsal = '{$_SESSION['dept']}')) AND batchMutasi IS NOT NULL
                        GROUP BY batchMutasi
                      ORDER BY mutasi.status ASC";
    } elseif ($_SESSION['role'] == 'Supervisor HRD') {
        $queryMutasi = "SELECT *
                      FROM mutasi
                      WHERE hapus IS NULL AND (mutasi.status = '9' OR (mutasi.status = '3' AND mutasi.cwocAsal = '{$_SESSION['dept']}')) AND batchMutasi IS NOT NULL
                        GROUP BY batchMutasi
                      ORDER BY mutasi.status ASC";
    } elseif ($_SESSION['role'] == 'Kepala Departemen HRD') {
        $queryMutasi = "SELECT *
                      FROM mutasi
                      WHERE hapus IS NULL AND (mutasi.cwocAsal = 'HRD IR' AND mutasi.status = '4'  
                      OR mutasi.cwocBaru = 'HRD IR' AND mutasi.status = '5') AND batchMutasi IS NOT NULL
                        GROUP BY batchMutasi
                      ORDER BY mutasi.status ASC";
    } elseif ($_SESSION['role'] == 'Foreman') {
        $queryMutasi = "SELECT *
                      FROM mutasi
                      WHERE hapus IS NULL AND mutasi.cwocAsal = '{$_SESSION['dept']}' AND mutasi.status = '2' AND batchMutasi IS NOT NULL
                        GROUP BY batchMutasi
                      ORDER BY mutasi.status ASC";
    } elseif ($_SESSION['role'] == 'Supervisor') {
        $queryMutasi = "SELECT *
                      FROM mutasi
                      WHERE hapus IS NULL AND cwocAsal = '{$_SESSION['dept']}' AND status = '3' AND batchMutasi IS NOT NULL
                        GROUP BY batchMutasi
                      ORDER BY status ASC";
    } elseif ($_SESSION['role'] == 'Kepala Departemen') {
        $queryMutasi = "SELECT *
                        FROM mutasi
                        WHERE hapus IS NULL AND (cwocAsal = '{$_SESSION['dept']}' AND status = '4' 
                      OR cwocBaru = '{$_SESSION['dept']}' AND status = '5') AND batchMutasi IS NOT NULL
                        GROUP BY batchMutasi
                      ORDER BY status ASC";
    } elseif ($_SESSION['role'] == 'Kepala Divisi' && $_SESSION['npk'] == '01033') {
        $queryMutasi = "SELECT *
                        FROM mutasi
                        WHERE hapus IS NULL
                        AND (
                            (cwocAsal IN ('QA', 'PDE 2W', 'PDE 4W', 'CQE 2W', 'CQE 4W') AND status = '6')
                            OR 
                            (cwocBaru IN ('QA', 'PDE 2W', 'PDE 4W', 'CQE 2W', 'CQE 4W') AND status = '7')
                        ) AND batchMutasi IS NOT NULL
                        GROUP BY batchMutasi
                        ORDER BY status ASC";
    } elseif ($_SESSION['role'] == 'Kepala Divisi' && $_SESSION['npk'] == '01561') {
        $queryMutasi = "SELECT *
                        FROM mutasi
                        WHERE hapus IS NULL
                        AND (
                            (cwocAsal IN ('HRD IR', 'GA', 'MIS') AND status = '6')
                            OR 
                            (cwocBaru IN ('HRD IR', 'GA', 'MIS') AND status = '7')
                        ) AND batchMutasi IS NOT NULL
                        GROUP BY batchMutasi
                        ORDER BY status ASC";
    } elseif ($_SESSION['role'] == 'Kepala Divisi' && $_SESSION['npk'] == '01961') {
        $queryMutasi = "SELECT *
                        FROM mutasi
                        WHERE hapus IS NULL
                        AND (
                            (cwocAsal IN ('PCE', 'PE 2W', 'PE 4W') AND status = '6')
                            OR 
                            (cwocBaru IN ('PCE', 'PE 2W', 'PE 4W') AND status = '7')
                        ) AND batchMutasi IS NOT NULL
                        GROUP BY batchMutasi
                        ORDER BY status ASC";
    } elseif ($_SESSION['role'] == 'Kepala Divisi' && $_SESSION['npk'] == '01166') {
        $queryMutasi = "SELECT *
                          FROM mutasi
                          WHERE hapus IS NULL
                          AND (
                              (cwocAsal IN ('MARKETING', 'PROCUREMENT', 'VENDOR DEVELOPMENT', 'GENERAL PURCHASE') AND status = '6')
                              OR 
                              (cwocBaru IN ('MARKETING', 'PROCUREMENT', 'VENDOR DEVELOPMENT', 'GENERAL PURCHASE') AND status = '7')
                          ) AND batchMutasi IS NOT NULL
                        GROUP BY batchMutasi
                          ORDER BY status ASC";
    } elseif ($_SESSION['role'] == 'Kepala Divisi' && $_SESSION['npk'] == '01266') {
        $queryMutasi = "SELECT *
                          FROM mutasi
                          WHERE hapus IS NULL
                          AND (
                              (cwocAsal IN ('WAREHOUSE', 'PRODUCTION SYSTEM', 'PPC') AND status = '6')
                              OR 
                              (cwocBaru IN ('WAREHOUSE', 'PRODUCTION SYSTEM', 'PPC') AND status = '7')
                          ) AND batchMutasi IS NOT NULL
                        GROUP BY batchMutasi
                          ORDER BY status ASC";
    } elseif ($_SESSION['role'] == 'Kepala Divisi' && $_SESSION['npk'] == '01577') {
        $queryMutasi = "SELECT *
                            FROM mutasi
                            WHERE hapus IS NULL
                            AND (
                                (cwocAsal IN ('PRODUCTION 1', 'PRODUCTION 2', 'PRODUCTION 3', 'PRODUCTION 4', 'PRODUCTION 5') AND status = '6')
                                OR 
                                (cwocBaru IN ('PRODUCTION 1', 'PRODUCTION 2', 'PRODUCTION 3', 'PRODUCTION 4', 'PRODUCTION 5') AND status = '7')
                            ) AND batchMutasi IS NOT NULL
                        GROUP BY batchMutasi
                            ORDER BY status ASC";
    } elseif ($_SESSION['role'] == 'Direktur Plant') {
        $queryMutasi = "SELECT *
                        FROM mutasi
                        WHERE hapus IS NULL AND status = '8' AND cwocAsal IN ('PDE 2W', 'PDE 4W', 'QA', 'CQE 2W', 'CQE 4W', 'PCE', 'PE 2W', 'PE 4W', 'PRODUCTION 1', 'PRODUCTION 2', 'PRODUCTION 3', 'PRODUCTION 4', 'PRODUCTION 5') AND batchMutasi IS NOT NULL
                        GROUP BY batchMutasi
                        ORDER BY status ASC";
    } elseif ($_SESSION['role'] == 'Direktur Non Plant') {
        $queryMutasi = "SELECT *
                        FROM mutasi
                        WHERE hapus IS NULL AND status = '8' AND cwocAsal IN ('HRD IR', 'MIS', 'GA', 'MARKETING', 'PROCUREMENT', 'VENDOR DEVELOPMENT', 'GENERAL PURCHASE') AND batchMutasi IS NOT NULL
                        GROUP BY batchMutasi
                        ORDER BY status ASC";

    }
}

$resultMutasi = mysqli_query($koneksi3, $queryMutasi);
// Periksa apakah kueri berhasil dieksekusi
if (!$resultMutasi) {
    die("Query error: " . mysqli_error($koneksi3));
}

$batchCounts = [];

// Fetch the records for counting
$batchCountQuery = "SELECT batchMutasi, COUNT(*) as total FROM mutasi 
    WHERE hapus IS NULL AND status != 100 
    GROUP BY batchMutasi";

$batchCountResult = mysqli_query($koneksi3, $batchCountQuery);

// Populate the batchCounts array
while ($batchRow = mysqli_fetch_assoc($batchCountResult)) {
    $batchCounts[$batchRow['batchMutasi']] = $batchRow['total'];
}


$response = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['IdMutasi']) && !empty($_POST['batchMutasi'])) {
    $IdMutasi = $_POST['IdMutasi'];
    $batchMutasi = $_POST['batchMutasi'];

    // Sanitize input
    $batchMutasi = $koneksi3->real_escape_string($batchMutasi);
    $IdMutasi = array_map('intval', $IdMutasi);
    $IdMutasiList = implode(',', $IdMutasi);
    $name = isset($_SESSION['npk']) ? $_SESSION['npk'] : '';
    $namareject = isset($_SESSION['name']) ? $_SESSION['name'] : '';
    date_default_timezone_set('Asia/Jakarta');
    $tgl_apv_hrd = date("Y-m-d H:i:s");
    $tgl_fm = date("Y-m-d H:i:s");
    $tgl_spv = date("Y-m-d H:i:s");
    $tgl_kadept1 = date("Y-m-d H:i:s");
    $tgl_kadept2 = date("Y-m-d H:i:s");
    $tgl_kadiv1 = date("Y-m-d H:i:s");
    $tgl_kadiv2 = date("Y-m-d H:i:s");
    $tgl_direktur = date("Y-m-d H:i:s");
    $tgl_reject = date("Y-m-d H:i:s");


    $queryGetStatus = "SELECT status, emno, cwocAsal, cwocBaru, sectAsal, sectBaru, tanggalMutasi, nama, batchMutasi FROM mutasi WHERE IdMutasi IN ($IdMutasiList)";
    $resultGetStatus = mysqli_query($koneksi3, $queryGetStatus);
    if (!$resultGetStatus) {
        die("Query error: " . mysqli_error($koneksi3));
    }

    $employeeDetails = array();
    $emnoArray = array();
    $counter = 1; // Initialize a counter to add numbering
    while ($row = mysqli_fetch_assoc($resultGetStatus)) {
        $status = $row['status'];
        $emno = $row['emno'];
        $cwocAsal = $row['cwocAsal'];
        $batchMutasi1 = $row['batchMutasi'];
        $cwocBaru = $row['cwocBaru'];
        $sectAsal = $row['sectAsal'];
        $sectBaru = $row['sectBaru'];
        $tanggalMutasi = $row['tanggalMutasi'];
        $nama = $row['nama'];

        // Fetch sectAsalDesc from the hrd_sect table
        $querySectAsal = "SELECT `desc` AS sectAsalDesc FROM hrd_sect WHERE sect = '$sectAsal'";
        $resultSectAsal = mysqli_query($koneksi2, $querySectAsal);
        if ($resultSectAsal && mysqli_num_rows($resultSectAsal) > 0) {
            $sectAsalData = mysqli_fetch_assoc($resultSectAsal);
            $sectAsalDesc = $sectAsalData['sectAsalDesc'];
        } else {
            $sectAsalDesc = 'Unknown Section'; // Default value if not found
        }

        // Fetch sectBaruDesc from the hrd_sect table
        $querySectBaru = "SELECT `desc` AS sectBaruDesc FROM hrd_sect WHERE sect = '$sectBaru'";
        $resultSectBaru = mysqli_query($koneksi2, $querySectBaru);
        if ($resultSectBaru && mysqli_num_rows($resultSectBaru) > 0) {
            $sectBaruData = mysqli_fetch_assoc($resultSectBaru);
            $sectBaruDesc = $sectBaruData['sectBaruDesc'];
        } else {
            $sectBaruDesc = 'Unknown Section'; // Default value if not found
        }

        $employeeDetails[] = "$counter. $emno - $nama";
        $counter++; // Increment counter for the next employee
        $emnoArray[] = $emno;
    }

    // Define the groups
    $kelompok1 = ['QA', 'PDE 2W', 'PDE 4W', 'CQE 2W', 'CQE 4W'];
    $kelompok2 = ['HRD IR', 'GA', 'MIS'];
    $kelompok3 = ['PCE', 'PE 2W', 'PE 4W'];
    $kelompok4 = ['MARKETING', 'PROCUREMENT', 'VENDOR DEVELOPMENT', 'GENERAL PURCHASE'];
    $kelompok5 = ['WAREHOUSE', 'PRODUCTION SYSTEM', 'PPC'];
    $kelompok6 = ['PRODUCTION 1', 'PRODUCTION 2', 'PRODUCTION 3', 'PRODUCTION 4', 'PRODUCTION 5'];

    if (in_array($cwocAsal, $kelompok1)) {
        $divisi = "Quality Assurance";
    } elseif (in_array($cwocAsal, $kelompok2)) {
        $divisi = "HRGA & MIS";
    } elseif (in_array($cwocAsal, $kelompok3)) {
        $divisi = "Engineering";
    } elseif (in_array($cwocAsal, $kelompok4)) {
        $divisi = "Marketing & Procurement";
    } elseif (in_array($cwocAsal, $kelompok5)) {
        $divisi = "Production Control";
    } elseif (in_array($cwocAsal, $kelompok6)) {
        $divisi = "Production";
    } else {
        $divisi = "Non Divisi"; // Fallback if none matches
    }

    if (in_array($cwocBaru, $kelompok1)) {
        $divisiBaru = "Quality Assurance";
    } elseif (in_array($cwocBaru, $kelompok2)) {
        $divisiBaru = "HRGA & MIS";
    } elseif (in_array($cwocBaru, $kelompok3)) {
        $divisiBaru = "Engineering";
    } elseif (in_array($cwocBaru, $kelompok4)) {
        $divisiBaru = "Marketing & Procurement";
    } elseif (in_array($cwocBaru, $kelompok5)) {
        $divisiBaru = "Production Control";
    } elseif (in_array($cwocBaru, $kelompok6)) {
        $divisiBaru = "Production";
    } else {
        $divisiBaru = "Non Divisi"; // Fallback if none matches
    }

    // Function to check if two CWOCs belong to the same group
    function sameGroup($cwoc1, $cwoc2, $groups)
    {
        foreach ($groups as $group) {
            if (in_array($cwoc1, $group) && in_array($cwoc2, $group)) {
                return true;
            }
        }
        return false;
    }

    $grup1 = ['QA', 'PDE 2W', 'PDE 4W', 'CQE 2W', 'CQE 4W'];
    $grup2 = ['HRD IR', 'MIS', 'GA'];
    $grup3 = ['MARKETING', 'PROCUREMENT', 'VENDOR DEVELOPMENT', 'GENERAL PURCHASE'];
    $grup4 = ['PRODUCTION SYSTEM', 'PPC'];
    $grup5 = ['PRODUCTION 1', 'PRODUCTION 2', 'PRODUCTION 3', 'PRODUCTION 4', 'PRODUCTION 5'];

    $npk = '';

    // Check if either $cwocAsal or $cwocBaru is in any of the groups
    if (in_array($cwocAsal, $grup1)) {
        $npk = '01033';
    } elseif (in_array($cwocAsal, $grup2)) {
        $npk = '01561';
    } elseif (in_array($cwocAsal, $grup3)) {
        $npk = '01166';
    } elseif (in_array($cwocAsal, $grup4)) {
        $npk = '01266';
    } elseif (in_array($cwocAsal, $grup5)) {
        $npk = '01577';
    }

    if (in_array($cwocBaru, $grup1)) {
        $npk2 = '01033';
    } elseif (in_array($cwocBaru, $grup2)) {
        $npk2 = '01561';
    } elseif (in_array($cwocBaru, $grup3)) {
        $npk2 = '01166';
    } elseif (in_array($cwocBaru, $grup4)) {
        $npk2 = '01266';
    } elseif (in_array($cwocBaru, $grup5)) {
        $npk2 = '01577';
    }


    $plantGroups = ['PDE 2W', 'PDE 4W', 'QA', 'CQE 2W', 'CQE 4W', 'PCE', 'PE 2W', 'PE 4W', 'WAREHOUSE', 'PRODUCTION SYSTEM', 'PPC', 'PRODUCTION 1', 'PRODUCTION 2', 'PRODUCTION 3', 'PRODUCTION 4', 'PRODUCTION 5'];
    $nonPlantGroups = ['HRD IR', 'MIS', 'GA', 'MARKETING', 'PROCUREMENT', 'VENDOR DEVELOPMENT', 'GENERAL PURCHASE'];

    // Initialize department variable
    $deptTarget = '';

    // Determine the target department based on cwocAsal
    if (in_array($cwocAsal, $plantGroups)) {
        $deptTarget = 'BOD PLANT';
    } elseif (in_array($cwocAsal, $nonPlantGroups)) {
        $deptTarget = 'BOD Non Plant';
    }

    // Define all groups in an array
    $allGroups = [$kelompok1, $kelompok2, $kelompok3, $kelompok4, $kelompok5, $kelompok6];
    $specialGroups = ['PDCA CPC', 'EHS', 'PLANNING BUDGETING', 'FINANCE ACCOUNTING'];

    // Tentukan status baru dan field terkait berdasarkan peran dan status saat ini
    $newStatus = $status;
    $updateField = '';
    $message = '';
    $flags = '';

    if (isset($_SESSION['role'])) {
        if ($_SESSION['role'] == 'Foreman' || $_SESSION['role'] == 'Foreman HRD') {
            if ($status == 2) {
                $newStatus = 3;
                $updateField = "FM = '$name', tgl_fm = '$tgl_fm'";
                $message = "Pemberitahuan Mutasi Karyawan!!\n\nBatch Mutasi [$batchMutasi1] telah di-approve oleh Foreman $cwocAsal. Status saat ini menunggu persetujuan Supervisor $cwocAsal.\nKaryawan disetujui:\n" . implode("\n", $employeeDetails) . "\n\nMohon proses persetujuan.";
                $flags = "queue";

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

                if ($result_phone && mysqli_num_rows($result_phone) > 0) {
                    while ($phone_row = mysqli_fetch_assoc($result_phone)) {
                        $phone_number = $phone_row['no_hp'];
                        $queryInsertNotif = "INSERT INTO notif (phone_number, message, flags) 
                                             VALUES ('$phone_number', '$message', '$flags')";
                        mysqli_query($koneksi3, $queryInsertNotif);
                    }
                }
            } elseif ($status == 9) {
                $newStatus = 10;
                $updateField = "HRD = '$name', tgl_apv_hrd = '$tgl_apv_hrd'";
                $flags = "queue";
                $messages = [];

                if (!empty($emnoArray)) {
                    $emnoList = "'" . implode("','", $emnoArray) . "'";
                    $query_phone = "SELECT no_hp, npk 
                                    FROM hp 
                                    WHERE npk IN ($emnoList)";
                    $result_phone = mysqli_query($koneksi4, $query_phone);

                    if ($result_phone && mysqli_num_rows($result_phone) > 0) {
                        while ($phone_row = mysqli_fetch_assoc($result_phone)) {
                            $npk = $phone_row['npk'];
                            $phone_number = $phone_row['no_hp'];

                            $encryption_key = 'your_secret_key_32_bytes'; // 32 bytes key for AES-256
                            $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length("aes-256-cbc")); // Generating a random IV

                            include __DIR__ . '/utils.php';

                            $encrypted_npk = encrypt($npk, $encryption_key, $iv);
                            $encrypted_batchMutasi = encrypt($batchMutasi1, $encryption_key, $iv);

                            // Bangun URL
                            $url = "http://localhost/MPSMUTASI/verifikasi.php?batchMutasi=" . urlencode($encrypted_batchMutasi) . "&emno=" . urlencode($encrypted_npk);


                            // Prepare the message
                            $messages[$phone_number] = "NPK $npk telah dimutasikan dari $cwocAsal ke $cwocBaru seksi $sectBaruDesc. Silahkan konfirmasi melalui link di bawah ini\n\n$url";
                        }

                    }
                }

                foreach ($messages as $phone_number => $message) {
                    $queryInsertNotif = "INSERT INTO notif (phone_number, message, flags) 
                                         VALUES ('$phone_number', '$message', '$flags')";
                    mysqli_query($koneksi3, $queryInsertNotif);
                }
            }
        } elseif ($_SESSION['role'] == 'Supervisor' || $_SESSION['role'] == 'Supervisor HRD') {
            if ($status == 3) {
                $newStatus = 4;
                $updateField = "SPV = '$name', tgl_spv = '$tgl_spv'";
                $message = "Pemberitahuan Mutasi Karyawan!!\n\nKami informasikan bahwa Batch Mutasi [$batchMutasi1] telah di-approve oleh Supervisor $cwocAsal. Status saat ini adalah menunggu persetujuan Kepala Departemen $cwocAsal.\nBerikut daftar karyawan yang disetujui:\n" . implode("\n", $employeeDetails) . "\n\nMohon untuk memproses persetujuan sesuai prosedur yang berlaku.\nTerima kasih atas perhatian dan kerjasamanya.";
                $flags = "queue";
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

                if ($result_phone && mysqli_num_rows($result_phone) > 0) {
                    while ($phone_row = mysqli_fetch_assoc($result_phone)) {
                        $phone_number = $phone_row['no_hp'];
                        $queryInsertNotif = "INSERT INTO notif (phone_number, message, flags) 
                                             VALUES ('$phone_number', '$message', '$flags')";
                        mysqli_query($koneksi3, $queryInsertNotif);
                    }
                }
            } elseif ($status == 9) {  // Combine HRD role condition here
                $newStatus = 10;
                $updateField = "HRD = '$name', tgl_apv_hrd = '$tgl_apv_hrd'";
                $flags = "queue";
                $messages = [];

                if (!empty($emnoArray)) {
                    $emnoList = "'" . implode("','", $emnoArray) . "'";
                    $query_phone = "SELECT no_hp, npk 
                                    FROM hp 
                                    WHERE npk IN ($emnoList)";
                    $result_phone = mysqli_query($koneksi2, $query_phone);

                    if ($result_phone && mysqli_num_rows($result_phone) > 0) {
                        while ($phone_row = mysqli_fetch_assoc($result_phone)) {
                            $npk = $phone_row['npk'];
                            $phone_number = $phone_row['no_hp'];

                            // Define your encryption key and IV
                            $encryption_key = 'your_secret_key_32_bytes'; // 32 bytes key for AES-256
                            $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length("aes-256-cbc")); // Generating a random IV

                            include __DIR__ . '/utils.php';

                            $encrypted_npk = encrypt($npk, $encryption_key, $iv);
                            $encrypted_batchMutasi = encrypt($batchMutasi1, $encryption_key, $iv);

                            // Bangun URL
                            $url = "http://localhost/MPSMUTASI/verifikasi.php?batchMutasi=" . urlencode($encrypted_batchMutasi) . "&emno=" . urlencode($encrypted_npk);


                            // Prepare the message
                            $messages[$phone_number] = "NPK $npk telah dimutasikan dari $cwocAsal ke $cwocBaru seksi $sectBaruDesc. Silahkan konfirmasi melalui link di bawah ini\n\n$url";
                        }

                    }
                }

                foreach ($messages as $phone_number => $message) {
                    $queryInsertNotif = "INSERT INTO notif (phone_number, message, flags) 
                                         VALUES ('$phone_number', '$message', '$flags')";
                    mysqli_query($koneksi3, $queryInsertNotif);
                }
            }
        } elseif ($_SESSION['role'] == 'Kepala Departemen' || $_SESSION['role'] == 'Kepala Departemen HRD') {
            if ($status == 4) {
                if ($cwocAsal == $cwocBaru) {
                    $newStatus = 6;
                    $updateField = "Kadept1 = '$name', Kadept2 = '$name', tgl_kadept1 = '$tgl_kadept1', tgl_kadept2 = '$tgl_kadept2'";
                    $message = "Pemberitahuan Mutasi Karyawan!!\n\nKami informasikan bahwa Batch Mutasi [$batchMutasi1] telah di-approve oleh Kepala Departemen $cwocAsal. Status saat ini adalah menunggu persetujuan Kepala Divisi $divisi.\nBerikut daftar karyawan yang disetujui:\n" . implode("\n", $employeeDetails) . "\n\nMohon untuk memproses persetujuan sesuai prosedur yang berlaku.\nTerima kasih atas perhatian dan kerjasamanya.";
                    $flags = "queue";
                    // Fetch phone number for newStatus 3
                    if ($npk) {
                        // Fetch phone number for newStatus 3
                        $query_phone = "SELECT no_hp
                                        FROM hp
                                        WHERE npk = '$npk'";
                        $result_phone = mysqli_query($koneksi4, $query_phone);

                        if ($result_phone && mysqli_num_rows($result_phone) > 0) {
                            while ($phone_row = mysqli_fetch_assoc($result_phone)) {
                                $phone_number = $phone_row['no_hp'];
                                $queryInsertNotif = "INSERT INTO notif (phone_number, message, flags) 
                                             VALUES ('$phone_number', '$message', '$flags')";
                                mysqli_query($koneksi3, $queryInsertNotif);
                            }
                        }
                    }
                } else {
                    $newStatus = 5;
                    $updateField = "Kadept1 = '$name', tgl_kadept1 = '$tgl_kadept1'";
                    $message = "Pemberitahuan Mutasi Karyawan!!\n\nKami informasikan bahwa Batch Mutasi [$batchMutasi1] telah di-approve oleh Kepala Departemen $cwocAsal. Status saat ini adalah menunggu persetujuan Kepala Departemen $cwocBaru.\nBerikut daftar karyawan yang disetujui:\n" . implode("\n", $employeeDetails) . "\n\nMohon untuk memproses persetujuan sesuai prosedur yang berlaku.\nTerima kasih atas perhatian dan kerjasamanya.";
                    $flags = "queue";

                    $query_npk = "SELECT npk FROM ct_users WHERE dept = '$cwocBaru' AND npk IN (SELECT npk FROM hrd_so WHERE tipe = 1)";
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

                    if ($result_phone && mysqli_num_rows($result_phone) > 0) {
                        while ($phone_row = mysqli_fetch_assoc($result_phone)) {
                            $phone_number = $phone_row['no_hp'];
                            $queryInsertNotif = "INSERT INTO notif (phone_number, message, flags) 
                                             VALUES ('$phone_number', '$message', '$flags')";
                            mysqli_query($koneksi3, $queryInsertNotif);
                        }
                    }
                }
            } elseif ($status == 5) {
                if (in_array($cwocAsal, $specialGroups) && in_array($cwocBaru, $specialGroups)) {
                    $newStatus = 8;
                    $updateField = "Kadept2 = '$name', tgl_kadept2 = '$tgl_kadept2'";
                    $message = "Pemberitahuan Mutasi Karyawan!!\n\nKami informasikan bahwa Batch Mutasi [$batchMutasi1] telah di-approve oleh Kepala Departmen $cwocBaru. Status saat ini adalah menunggu persetujuan Direktur.\nBerikut daftar karyawan yang disetujui:\n" . implode("\n", $employeeDetails) . "\n\nMohon untuk memproses persetujuan sesuai prosedur yang berlaku.\nTerima kasih atas perhatian dan kerjasamanya.";
                    $flags = "queue";
                    $query_npk = "SELECT npk FROM ct_users WHERE dept = '$deptTarget'";
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

                    if ($result_phone && mysqli_num_rows($result_phone) > 0) {
                        while ($phone_row = mysqli_fetch_assoc($result_phone)) {
                            $phone_number = $phone_row['no_hp'];
                            $queryInsertNotif = "INSERT INTO notif (phone_number, message, flags) 
                                             VALUES ('$phone_number', '$message', '$flags')";
                            mysqli_query($koneksi3, $queryInsertNotif);
                        }
                    }
                } elseif (in_array($cwocAsal, $specialGroups)) {
                    $newStatus = 7;
                    $updateField = "Kadept2 = '$name', tgl_kadept2 = '$tgl_kadept2'";
                    $message = "Pemberitahuan Mutasi Karyawan!!\n\nKami informasikan bahwa Batch Mutasi [$batchMutasi1] telah di-approve oleh Kepala Departemen $cwocBaru. Status saat ini adalah menunggu persetujuan Kepala Divisi $divisiBaru.\nBerikut daftar karyawan yang disetujui:\n" . implode("\n", $employeeDetails) . "\n\nMohon untuk memproses persetujuan sesuai prosedur yang berlaku.\nTerima kasih atas perhatian dan kerjasamanya.";
                    $flags = "queue";
                    // Fetch phone number for newStatus 3
                    if ($npk) {
                        // Fetch phone number for newStatus 3
                        $query_phone = "SELECT no_hp
                                        FROM hp
                                        WHERE npk = '$npk2';";
                        $result_phone = mysqli_query($koneksi4, $query_phone);

                        if ($result_phone && mysqli_num_rows($result_phone) > 0) {
                            while ($phone_row = mysqli_fetch_assoc($result_phone)) {
                                $phone_number = $phone_row['no_hp'];
                                $queryInsertNotif = "INSERT INTO notif (phone_number, message, flags) 
                                             VALUES ('$phone_number', '$message', '$flags')";
                                mysqli_query($koneksi3, $queryInsertNotif);
                            }
                        }
                    }
                } else {
                    $newStatus = 6;
                    $updateField = "Kadept2 = '$name', tgl_kadept2 = '$tgl_kadept2'";
                    $message = "Pemberitahuan Mutasi Karyawan!!\n\nKami informasikan bahwa Batch Mutasi [$batchMutasi1] telah di-approve oleh Kepala Departemen $cwocBaru. Status saat ini adalah menunggu persetujuan Kepala Divisi $divisi.\nBerikut daftar karyawan yang disetujui:\n" . implode("\n", $employeeDetails) . "\n\nMohon untuk memproses persetujuan sesuai prosedur yang berlaku.\nTerima kasih atas perhatian dan kerjasamanya.";
                    $flags = "queue";
                    // Fetch phone number for newStatus 3
                    if ($npk) {
                        // Fetch phone number for newStatus 3
                        $query_phone = "SELECT no_hp
                                        FROM hp
                                        WHERE npk = '$npk';";
                        $result_phone = mysqli_query($koneksi4, $query_phone);

                        if ($result_phone && mysqli_num_rows($result_phone) > 0) {
                            while ($phone_row = mysqli_fetch_assoc($result_phone)) {
                                $phone_number = $phone_row['no_hp'];
                                $queryInsertNotif = "INSERT INTO notif (phone_number, message, flags) 
                                             VALUES ('$phone_number', '$message', '$flags')";
                                mysqli_query($koneksi3, $queryInsertNotif);
                            }
                        }
                    }
                }
            }
        } elseif ($_SESSION['role'] == 'Kepala Divisi') {
            if ($status == 6) {
                if ($cwocAsal == $cwocBaru) {
                    $newStatus = 9;
                    $updateField = "Kadiv1 = '$name', Kadiv2 = '$name', tgl_kadiv1 = '$tgl_kadiv1', tgl_kadiv2 = '$tgl_kadiv2'";
                    $message = "Pemberitahuan Mutasi Karyawan!!\n\nKami informasikan bahwa Batch Mutasi [$batchMutasi1] telah di-approve oleh Kepala Divisi $divisi. Status saat ini adalah menunggu persetujuan HRD.\nBerikut daftar karyawan yang disetujui:\n" . implode("\n", $employeeDetails) . "\n\nMohon untuk memproses persetujuan sesuai prosedur yang berlaku.\nTerima kasih atas perhatian dan kerjasamanya.";
                    $flags = "queue";
                    $query_npk = "SELECT npk FROM ct_users WHERE ct_users.dept = 'HRD IR' AND NOT EXISTS (SELECT 1 FROM hrd_so WHERE hrd_so.npk = ct_users.npk)";
                    $result_npk = mysqli_query($koneksi2, $query_npk);

                    // Ambil hasil query npk
                    $npk_list = [];
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

                    if ($result_phone && mysqli_num_rows($result_phone) > 0) {
                        while ($phone_row = mysqli_fetch_assoc($result_phone)) {
                            $phone_number = $phone_row['no_hp'];
                            $queryInsertNotif = "INSERT INTO notif (phone_number, message, flags) 
                         VALUES ('$phone_number', '$message', '$flags')";
                            mysqli_query($koneksi3, $queryInsertNotif);
                        }
                    }
                } elseif (sameGroup($cwocAsal, $cwocBaru, $allGroups)) {
                    $newStatus = 8;
                    $updateField = "Kadiv1 = '$name', Kadiv2 = '$name', tgl_kadiv1 = '$tgl_kadiv1', tgl_kadiv2 = '$tgl_kadiv2'";
                    $message = "Pemberitahuan Mutasi Karyawan!!\n\nKami informasikan bahwa Batch Mutasi [$batchMutasi1] telah di-approve oleh Kepala Divisi $divisi. Status saat ini adalah menunggu persetujuan Direktur.\nBerikut daftar karyawan yang disetujui:\n" . implode("\n", $employeeDetails) . "\n\nMohon untuk memproses persetujuan sesuai prosedur yang berlaku.\nTerima kasih atas perhatian dan kerjasamanya.";
                    $flags = "queue";
                    // Fetch phone number for newStatus 3
                    $query_phone = "SELECT isd.no_hp
                                    FROM isd
                                    JOIN ct_users ON ct_users.npk = isd.npk
                                    WHERE ct_users.dept = '$deptTarget'";
                    $result_phone = mysqli_query($koneksi2, $query_phone);

                    if ($result_phone && mysqli_num_rows($result_phone) > 0) {
                        while ($phone_row = mysqli_fetch_assoc($result_phone)) {
                            $phone_number = $phone_row['no_hp'];
                            $queryInsertNotif = "INSERT INTO notif (phone_number, message, flags) 
                                             VALUES ('$phone_number', '$message', '$flags')";
                            mysqli_query($koneksi3, $queryInsertNotif);
                        }
                    }
                } else {
                    $newStatus = 7;
                    $updateField = "Kadiv1 = '$name', tgl_kadiv1 = '$tgl_kadiv1'";
                    $message = "Pemberitahuan Mutasi Karyawan!!\n\nKami informasikan bahwa Batch Mutasi [$batchMutasi1] telah di-approve oleh Kepala Divisi $divisi. Status saat ini adalah menunggu persetujuan Kepala Divisi $divisiBaru.\nBerikut daftar karyawan yang disetujui:\n" . implode("\n", $employeeDetails) . "\n\nMohon untuk memproses persetujuan sesuai prosedur yang berlaku.\nTerima kasih atas perhatian dan kerjasamanya.";
                    $flags = "queue";
                    // Fetch phone number for newStatus 3
                    if ($npk) {
                        // Fetch phone number for newStatus 3
                        $query_phone = "SELECT no_hp
                                        FROM hp
                                        WHERE npk = '$npk2'";
                        $result_phone = mysqli_query($koneksi4, $query_phone);

                        if ($result_phone && mysqli_num_rows($result_phone) > 0) {
                            while ($phone_row = mysqli_fetch_assoc($result_phone)) {
                                $phone_number = $phone_row['no_hp'];
                                $queryInsertNotif = "INSERT INTO notif (phone_number, message, flags) 
                                             VALUES ('$phone_number', '$message', '$flags')";
                                mysqli_query($koneksi3, $queryInsertNotif);
                            }
                        }
                    }
                }
            } elseif ($status == 7) {
                $newStatus = 8;
                $updateField = "Kadiv2 = '$name', tgl_kadiv2 = '$tgl_kadiv2'";
                $message = "Pemberitahuan Mutasi Karyawan!!\n\nKami informasikan bahwa Batch Mutasi [$batchMutasi1] telah di-approve oleh Kepala Divisi $divisiBaru. Status saat ini adalah menunggu persetujuan Direktur.\nBerikut daftar karyawan yang disetujui:\n" . implode("\n", $employeeDetails) . "\n\nMohon untuk memproses persetujuan sesuai prosedur yang berlaku.\nTerima kasih atas perhatian dan kerjasamanya.";
                $flags = "queue";
                $query_npk = "SELECT npk FROM ct_users WHERE dept = '$deptTarget'";
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

                if ($result_phone && mysqli_num_rows($result_phone) > 0) {
                    while ($phone_row = mysqli_fetch_assoc($result_phone)) {
                        $phone_number = $phone_row['no_hp'];
                        $queryInsertNotif = "INSERT INTO notif (phone_number, message, flags) 
                                             VALUES ('$phone_number', '$message', '$flags')";
                        mysqli_query($koneksi3, $queryInsertNotif);
                    }
                }
            }
        } elseif ($_SESSION['role'] == 'Direktur Plant' || $_SESSION['role'] == 'Direktur Non Plant') {
            if ($status == 8) {
                $newStatus = 9;
                $updateField = "Direktur = '$name', tgl_direktur = '$tgl_direktur'";
                $message = "Pemberitahuan Mutasi Karyawan!!\n\nKami informasikan bahwa Batch Mutasi [$batchMutasi1] telah di-approve oleh Direktur. Status saat ini adalah menunggu HRD.\nBerikut daftar karyawan yang disetujui:\n" . implode("\n", $employeeDetails) . "\n\nMohon untuk memproses persetujuan sesuai prosedur yang berlaku.\nTerima kasih atas perhatian dan kerjasamanya.";
                $flags = "queue";
                $query_npk = "SELECT npk FROM ct_users WHERE ct_users.dept = 'HRD IR' AND NOT EXISTS (SELECT 1 FROM hrd_so WHERE hrd_so.npk = ct_users.npk)";
                $result_npk = mysqli_query($koneksi2, $query_npk);

                // Ambil hasil query npk
                $npk_list = [];
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

                if ($result_phone && mysqli_num_rows($result_phone) > 0) {
                    while ($phone_row = mysqli_fetch_assoc($result_phone)) {
                        $phone_number = $phone_row['no_hp'];
                        $queryInsertNotif = "INSERT INTO notif (phone_number, message, flags) 
                                             VALUES ('$phone_number', '$message', '$flags')";
                        mysqli_query($koneksi3, $queryInsertNotif);
                    }
                }
            }
        }

        if ($updateField) {
            // Update the status of selected records
            $queryUpdateStatus = "UPDATE mutasi SET status = '$newStatus', $updateField WHERE IdMutasi IN ($IdMutasiList)";
            $resultUpdateStatus = mysqli_query($koneksi3, $queryUpdateStatus);

            if ($resultUpdateStatus) {
                $response['success'] = true;

                // Update unselected records
                $queryUpdateUnselected = "UPDATE mutasi SET status = 100, reject = '$namareject', tgl_reject = '$tgl_reject' WHERE IdMutasi NOT IN ($IdMutasiList) AND batchMutasi = '$batchMutasi1'";
                $resultUpdateUnselected = mysqli_query($koneksi3, $queryUpdateUnselected);

                if (!$resultUpdateUnselected) {
                    $response['error'] = "Error updating unselected records: " . mysqli_error($koneksi3);
                    $response['success'] = false;
                }

            } else {
                $response['success'] = false;
                $response['error'] = "Update query error: " . mysqli_error($koneksi3);
            }
        } else {
            $response['success'] = false;
            $response['error'] = "Invalid input data.";
        }

        echo json_encode($response);
        exit;
    }
}
?>