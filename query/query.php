<?php
include __DIR__ . '/koneksi.php';

$currentMonth = date('m');
$currentYear = date('Y');

// Mendapatkan bulan dan tahun yang dipilih dari query string
$selectedMonth = isset($_GET['bulan']) ? (int) $_GET['bulan'] : (int) $currentMonth;
$selectedYear = isset($_GET['tahun']) ? (int) $_GET['tahun'] : (int) $currentYear;

// Mendapatkan tanggal terakhir dari bulan yang dipilih
$endDate = date('Y-m-d', strtotime("last day of $selectedYear-$selectedMonth +1 day"));

// Menghitung bulan dan tahun sebelumnya
if ($selectedMonth == 1) {
    $previousMonth = 12; // Januari sebelumnya adalah Desember
    $previousYear = $selectedYear - 1;
} else {
    $previousMonth = $selectedMonth - 1; // Bulan sebelumnya
    $previousYear = $selectedYear;
}

// Mendapatkan tanggal terakhir dari bulan sebelumnya
$endDate2 = date('Y-m-d', strtotime("last day of $previousYear-$previousMonth +1 day"));

// Debug output (jika diperlukan)
// echo "End Date: $endDate\n";
// echo "End Date Previous: $endDate2\n";


// Prepare the condition for default date and null resdate
$defaultDateCondition = "(resdate IS NULL AND birthday <= CURDATE())";
$defaultDateCondition2 = "(resdate IS NULL AND joindate <= CURDATE())";

////////////////////////
$queryUmur = "SELECT
    SUM(CASE WHEN sexe = 'L' AND TIMESTAMPDIFF(YEAR, birthday, '$endDate') < 18 THEN 1 ELSE 0 END) AS '<18 Pria',
    SUM(CASE WHEN sexe = 'P' AND TIMESTAMPDIFF(YEAR, birthday, '$endDate') < 18 THEN 1 ELSE 0 END) AS '<18 Perempuan',
    SUM(CASE WHEN sexe = 'L' AND TIMESTAMPDIFF(YEAR, birthday, '$endDate') BETWEEN 18 AND 25 THEN 1 ELSE 0 END) AS '18-25 Pria',
    SUM(CASE WHEN sexe = 'P' AND TIMESTAMPDIFF(YEAR, birthday, '$endDate') BETWEEN 18 AND 25 THEN 1 ELSE 0 END) AS '18-25 Perempuan',
    SUM(CASE WHEN sexe = 'L' AND TIMESTAMPDIFF(YEAR, birthday, '$endDate') BETWEEN 26 AND 35 THEN 1 ELSE 0 END) AS '26-35 Pria',
    SUM(CASE WHEN sexe = 'P' AND TIMESTAMPDIFF(YEAR, birthday, '$endDate') BETWEEN 26 AND 35 THEN 1 ELSE 0 END) AS '26-35 Perempuan',
    SUM(CASE WHEN sexe = 'L' AND TIMESTAMPDIFF(YEAR, birthday, '$endDate') BETWEEN 36 AND 45 THEN 1 ELSE 0 END) AS '36-45 Pria',
    SUM(CASE WHEN sexe = 'P' AND TIMESTAMPDIFF(YEAR, birthday, '$endDate') BETWEEN 36 AND 45 THEN 1 ELSE 0 END) AS '36-45 Perempuan',
    SUM(CASE WHEN sexe = 'L' AND TIMESTAMPDIFF(YEAR, birthday, '$endDate') BETWEEN 46 AND 55 THEN 1 ELSE 0 END) AS '46-55 Pria',
    SUM(CASE WHEN sexe = 'P' AND TIMESTAMPDIFF(YEAR, birthday, '$endDate') BETWEEN 46 AND 55 THEN 1 ELSE 0 END) AS '46-55 Perempuan',
    SUM(CASE WHEN sexe = 'L' AND TIMESTAMPDIFF(YEAR, birthday, '$endDate') > 55 THEN 1 ELSE 0 END) AS '>55 Pria',
    SUM(CASE WHEN sexe = 'P' AND TIMESTAMPDIFF(YEAR, birthday, '$endDate') > 55 THEN 1 ELSE 0 END) AS '>55 Perempuan'
FROM
    karyawan
WHERE
($defaultDateCondition OR (resdate > '$endDate'))
    AND joindate <= '$endDate'";

// Execute query
$resultUmur = mysqli_query($koneksi, $queryUmur);

// Check if the query was successful
if (!$resultUmur) {
    die("Query gagal: " . mysqli_error($koneksi));
}

// Fetch the result as an associative array
$row = mysqli_fetch_assoc($resultUmur);

// Save query results to PHP variables
$jumlah_pria_kurang_dari_18 = $row['<18 Pria'];
$jumlah_perempuan_kurang_dari_18 = $row['<18 Perempuan'];
$jumlah_pria_18_25 = $row['18-25 Pria'];
$jumlah_perempuan_18_25 = $row['18-25 Perempuan'];
$jumlah_pria_26_35 = $row['26-35 Pria'];
$jumlah_perempuan_26_35 = $row['26-35 Perempuan'];
$jumlah_pria_36_45 = $row['36-45 Pria'];
$jumlah_perempuan_36_45 = $row['36-45 Perempuan'];
$jumlah_pria_46_55 = $row['46-55 Pria'];
$jumlah_perempuan_46_55 = $row['46-55 Perempuan'];
$jumlah_pria_lebih_dari_55 = $row['>55 Pria'];
$jumlah_perempuan_lebih_dari_55 = $row['>55 Perempuan'];

//Jenis Kelamin
$queryJK = "SELECT
            SUM(CASE WHEN sexe = 'L' THEN 1 ELSE 0 END) AS 'Pria',
            SUM(CASE WHEN sexe = 'P' THEN 1 ELSE 0 END) AS 'Perempuan'
          FROM
         karyawan
         WHERE
($defaultDateCondition OR (resdate > '$endDate'))
    AND joindate <= '$endDate'";
$resultJK = mysqli_query($koneksi, $queryJK);

// Memeriksa apakah query berhasil dieksekusi
if (!$resultJK) {
    die("Query gagal: " . mysqli_error($koneksi));
}

// Simpan hasil query ke dalam variabel $result
// Misalnya:
$row = mysqli_fetch_assoc($resultJK);

// Simpan hasil query ke dalam variabel PHP
$pria = $row['Pria'];
$perempuan = $row['Perempuan'];


//Masa Kerja
$queryMK = "SELECT
            SUM(CASE WHEN sexe = 'L' AND TIMESTAMPDIFF(YEAR, joindate, '$endDate') BETWEEN 0 AND 5 THEN 1 ELSE 0 END) AS '0-5 Pria',
            SUM(CASE WHEN sexe = 'P' AND TIMESTAMPDIFF(YEAR, joindate, '$endDate') BETWEEN 0 AND 5 THEN 1 ELSE 0 END) AS '0-5 Perempuan',
            SUM(CASE WHEN sexe = 'L' AND TIMESTAMPDIFF(YEAR, joindate, '$endDate') BETWEEN 6 AND 10 THEN 1 ELSE 0 END) AS '6-10 Pria',
            SUM(CASE WHEN sexe = 'P' AND TIMESTAMPDIFF(YEAR, joindate, '$endDate') BETWEEN 6 AND 10 THEN 1 ELSE 0 END) AS '6-10 Perempuan',
            SUM(CASE WHEN sexe = 'L' AND TIMESTAMPDIFF(YEAR, joindate, '$endDate') BETWEEN 11 AND 15 THEN 1 ELSE 0 END) AS '11-15 Pria',
            SUM(CASE WHEN sexe = 'P' AND TIMESTAMPDIFF(YEAR, joindate, '$endDate') BETWEEN 11 AND 15 THEN 1 ELSE 0 END) AS '11-15 Perempuan',
            SUM(CASE WHEN sexe = 'L' AND TIMESTAMPDIFF(YEAR, joindate, '$endDate') BETWEEN 16 AND 20 THEN 1 ELSE 0 END) AS '16-20 Pria',
            SUM(CASE WHEN sexe = 'P' AND TIMESTAMPDIFF(YEAR, joindate, '$endDate') BETWEEN 16 AND 20 THEN 1 ELSE 0 END) AS '16-20 Perempuan',
            SUM(CASE WHEN sexe = 'L' AND TIMESTAMPDIFF(YEAR, joindate, '$endDate') BETWEEN 21 AND 25 THEN 1 ELSE 0 END) AS '21-25 Pria',
            SUM(CASE WHEN sexe = 'P' AND TIMESTAMPDIFF(YEAR, joindate, '$endDate') BETWEEN 21 AND 25 THEN 1 ELSE 0 END) AS '21-25 Perempuan',
            SUM(CASE WHEN sexe = 'L' AND TIMESTAMPDIFF(YEAR, joindate, '$endDate') BETWEEN 26 AND 30 THEN 1 ELSE 0 END) AS '26-30 Pria',
            SUM(CASE WHEN sexe = 'P' AND TIMESTAMPDIFF(YEAR, joindate, '$endDate') BETWEEN 26 AND 30 THEN 1 ELSE 0 END) AS '26-30 Perempuan',
            SUM(CASE WHEN sexe = 'L' AND TIMESTAMPDIFF(YEAR, joindate, '$endDate') > 30 THEN 1 ELSE 0 END) AS '>30 Pria',
            SUM(CASE WHEN sexe = 'P' AND TIMESTAMPDIFF(YEAR, joindate, '$endDate') > 30 THEN 1 ELSE 0 END) AS '>30 Perempuan'
            FROM
                karyawan
            WHERE
            ($defaultDateCondition2 OR (resdate > '$endDate'))
                AND joindate <= '$endDate'";

$resultMK = mysqli_query($koneksi, $queryMK);

// Memeriksa apakah query berhasil dieksekusi
if (!$resultMK) {
    die("Query gagal: " . mysqli_error($koneksi));
}

// Simpan hasil query ke dalam variabel $result
// Misalnya:
$row = mysqli_fetch_assoc($resultMK);

// Simpan hasil query ke dalam variabel PHP
$jumlah_pria_kurang_dari_5 = $row['0-5 Pria'];
$jumlah_perempuan_kurang_dari_5 = $row['0-5 Perempuan'];
$jumlah_pria_6_10 = $row['6-10 Pria'];
$jumlah_perempuan_6_10 = $row['6-10 Perempuan'];
$jumlah_pria_11_15 = $row['11-15 Pria'];
$jumlah_perempuan_11_15 = $row['11-15 Perempuan'];
$jumlah_pria_16_20 = $row['16-20 Pria'];
$jumlah_perempuan_16_20 = $row['16-20 Perempuan'];
$jumlah_pria_21_25 = $row['21-25 Pria'];
$jumlah_perempuan_21_25 = $row['21-25 Perempuan'];
$jumlah_pria_26_30 = $row['26-30 Pria'];
$jumlah_perempuan_26_30 = $row['26-30 Perempuan'];
$jumlah_pria_lebih_dari_30 = $row['>30 Pria'];
$jumlah_perempuan_lebih_dari_30 = $row['>30 Perempuan'];

//Pendidikan
$queryPend = "SELECT
            SUM(CASE WHEN pendidikan = 'SD' AND sexe='L' THEN 1 ELSE 0 END) AS 'Pria SD',
            SUM(CASE WHEN pendidikan = 'SD' AND sexe='P' THEN 1 ELSE 0 END) AS 'Perempuan SD',
            SUM(CASE WHEN pendidikan IN ('SMP', 'SLTP') AND sexe='L' THEN 1 ELSE 0 END) AS 'Pria SLTP',
            SUM(CASE WHEN pendidikan IN ('SMP', 'SLTP') AND sexe='P' THEN 1 ELSE 0 END) AS 'Perempuan SLTP',
            SUM(CASE WHEN pendidikan IN ('SLTA', 'SMA', 'SMK', 'SMU', 'SPG', 'SMEA') AND sexe='L' THEN 1 ELSE 0 END) AS 'Pria SMA',
            SUM(CASE WHEN pendidikan IN ('SLTA', 'SMA', 'SMK', 'SMU', 'SPG', 'SMEA') AND sexe='P' THEN 1 ELSE 0 END) AS 'Perempuan SMA',
            SUM(CASE WHEN pendidikan IN ('D1', 'D2', 'D3', 'D4') AND sexe='L' THEN 1 ELSE 0 END) AS 'Pria Diploma',
            SUM(CASE WHEN pendidikan IN ('D1', 'D2', 'D3', 'D4') AND sexe='P' THEN 1 ELSE 0 END) AS 'Perempuan Diploma',
            SUM(CASE WHEN pendidikan = 'S1' AND sexe='L' THEN 1 ELSE 0 END) AS 'Pria S1',
            SUM(CASE WHEN pendidikan = 'S1' AND sexe='P' THEN 1 ELSE 0 END) AS 'Perempuan S1',
            SUM(CASE WHEN pendidikan = 'S2' AND sexe='L' THEN 1 ELSE 0 END) AS 'Pria S2',
            SUM(CASE WHEN pendidikan = 'S2' AND sexe='P' THEN 1 ELSE 0 END) AS 'Perempuan S2',
            SUM(CASE WHEN pendidikan = 'S3' AND sexe='L' THEN 1 ELSE 0 END) AS 'Pria S3',
            SUM(CASE WHEN pendidikan = 'S3' AND sexe='P' THEN 1 ELSE 0 END) AS 'Perempuan S3'
          FROM
            karyawan
            WHERE
            ($defaultDateCondition OR (resdate > '$endDate'))
                AND joindate <= '$endDate'";


$resultPend = mysqli_query($koneksi, $queryPend);

// Memeriksa apakah query berhasil dieksekusi
if (!$resultPend) {
    die("Query gagal: " . mysqli_error($koneksi));
}

// Simpan hasil query ke dalam variabel $result
// Misalnya:
$row = mysqli_fetch_assoc($resultPend);

// Simpan hasil query ke dalam variabel PHP
$jumlah_pria_SD = $row['Pria SD'];
$jumlah_perempuan_SD = $row['Perempuan SD'];
$jumlah_pria_SLTP = $row['Pria SLTP'];
$jumlah_perempuan_SLTP = $row['Perempuan SLTP'];
$jumlah_pria_SMA = $row['Pria SMA'];
$jumlah_perempuan_SMA = $row['Perempuan SMA'];
$jumlah_pria_Diploma = $row['Pria Diploma'];
$jumlah_perempuan_Diploma = $row['Perempuan Diploma'];
$jumlah_pria_S1 = $row['Pria S1'];
$jumlah_perempuan_S1 = $row['Perempuan S1'];
$jumlah_pria_S2 = $row['Pria S2'];
$jumlah_perempuan_S2 = $row['Perempuan S2'];
$jumlah_pria_S3 = $row['Pria S3'];
$jumlah_perempuan_S3 = $row['Perempuan S3'];

$queryRMP = "SELECT DISTINCT cwoc FROM karyawan ORDER BY
    FIELD(cwoc, 'PRODUCTION 1', 'PRODUCTION 2', 'PRODUCTION 3', 'PRODUCTION 4', 'PRODUCTION 5', 'PRODUCTION SYSTEM', 'PDE 2W', 'PDE 4W', 'PCE', 'PE 2W', 'PE 4W', 'PPC', 'QA', 'CQE 2W', 'CQE 4W', 'PROCUREMENT',
          'VENDOR DEVELOPMENT', 'GENERAL PURCHASE', 'WAREHOUSE', 'MARKETING', 'MIS', 'PDCA CPC', 'FINANCE ACCOUNTING', 'PLANNING BUDGETING', 'HRD IR', 'GA', 'EHS', 'COMITTEE QCC - SS', 'BOD')";

$resultRMP = mysqli_query($koneksi, $queryRMP);

// Periksa apakah kueri berhasil dieksekusi
if (!$queryRMP) {
    die("Query error: " . mysqli_error($koneksi));
}

$queryRMP2 = "SELECT DISTINCT cwoc FROM karyawan ORDER BY
    FIELD(cwoc, 'PRODUCTION 1', 'PRODUCTION 2', 'PRODUCTION 3', 'PRODUCTION 4', 'PRODUCTION 5', 'PRODUCTION SYSTEM', 'PDE 2W', 'PDE 4W', 'PCE', 'PE 2W', 'PE 4W', 'PPC', 'QA', 'CQE 2W', 'CQE 4W', 'PROCUREMENT',
          'VENDOR DEVELOPMENT', 'GENERAL PURCHASE', 'WAREHOUSE', 'MARKETING', 'MIS', 'PDCA CPC', 'FINANCE ACCOUNTING', 'PLANNING BUDGETING', 'HRD IR', 'GA', 'EHS', 'COMITTEE QCC - SS', 'BOD')";

$resultRMP2 = mysqli_query($koneksi, $queryRMP);

// Periksa apakah kueri berhasil dieksekusi
if (!$queryRMP2) {
    die("Query error: " . mysqli_error($koneksi));
}

$queryDashboard = "SELECT 
    karyawan.cwoc,
    SUM(CASE WHEN karyawan.emno LIKE 'K%' THEN 1 ELSE 0 END) AS Kontrak,
    SUM(CASE WHEN karyawan.gol = 1 AND (karyawan.emno LIKE '0%' OR karyawan.emno REGEXP '^[0-9]+$' OR karyawan.emno LIKE 'EXP%') THEN 1 ELSE 0 END) AS Tetap1,
    SUM(CASE WHEN karyawan.gol = 2 AND (karyawan.emno LIKE '0%' OR karyawan.emno REGEXP '^[0-9]+$' OR karyawan.emno LIKE 'EXP%') THEN 1 ELSE 0 END) AS Tetap2,
    SUM(CASE WHEN karyawan.gol = 2 AND (karyawan.emno LIKE 'P%') THEN 1 ELSE 0 END) AS Trainee2,
    SUM(CASE WHEN karyawan.gol = 3 AND (karyawan.emno LIKE '0%' OR karyawan.emno REGEXP '^[0-9]+$' OR karyawan.emno LIKE 'EXP%') THEN 1 ELSE 0 END) AS Tetap3,
    SUM(CASE WHEN karyawan.gol = 3 AND (karyawan.emno LIKE 'P%') THEN 1 ELSE 0 END) AS Trainee3,
    SUM(CASE WHEN karyawan.gol = 4 AND (karyawan.emno LIKE '0%' OR karyawan.emno REGEXP '^[0-9]+$' OR karyawan.emno LIKE 'EXP%') THEN 1 ELSE 0 END) AS Tetap4,
    SUM(CASE WHEN karyawan.gol = 4 AND (karyawan.emno LIKE 'P%') THEN 1 ELSE 0 END) AS Trainee4,
    SUM(CASE WHEN karyawan.gol = 5 AND (karyawan.emno LIKE '0%' OR karyawan.emno REGEXP '^[0-9]+$' OR karyawan.emno LIKE 'EXP%') THEN 1 ELSE 0 END) AS Tetap5,
    SUM(CASE WHEN karyawan.gol IN (6, 7) AND (karyawan.emno LIKE '0%' OR karyawan.emno REGEXP '^[0-9]+$' OR karyawan.emno LIKE 'EXP%') THEN 1 ELSE 0 END) AS Tetap6_7,
    SUM(CASE WHEN karyawan.emno LIKE '0%' OR karyawan.emno REGEXP '^[0-9]+$' OR karyawan.emno LIKE 'EXP%' THEN 1 ELSE 0 END) AS Tetap  
    FROM 
    karyawan
WHERE
($defaultDateCondition OR (resdate > '$endDate'))
    AND joindate <= '$endDate'
    GROUP BY 
    karyawan.cwoc";

$resultDashboard = $koneksi->query($queryDashboard);

// Memasukkan data cwoc dan jumlah Dashboard ke dalam array asosiatif
$DashboardByCwoc = array();
while ($row = $resultDashboard->fetch_assoc()) {
    $cwoc = $row['cwoc'];
    // Memasukkan nilai 'Kontrak', 'Tetap1', dan 'Tetap2' ke dalam array dengan menggunakan key yang berbeda
    $DashboardByCwoc[$cwoc] = array(
        'Kontrak' => $row['Kontrak'],
        'Tetap1' => $row['Tetap1'],
        'Tetap2' => $row['Tetap2'],
        'Trainee2' => $row['Trainee2'],
        'Tetap3' => $row['Tetap3'],
        'Trainee3' => $row['Trainee3'],
        'Tetap4' => $row['Tetap4'],
        'Trainee4' => $row['Trainee4'],
        'Tetap5' => $row['Tetap5'],
        'Tetap6_7' => $row['Tetap6_7'],
        'Tetap' => $row['Tetap']
    );
}


$queryDashboard1 = "SELECT 
    karyawan.cwoc,
    SUM(CASE WHEN karyawan.emno LIKE 'K%' THEN 1 ELSE 0 END) AS Kontrak,
    SUM(CASE WHEN karyawan.gol = 1 AND (karyawan.emno LIKE '0%' OR karyawan.emno REGEXP '^[0-9]+$' OR karyawan.emno LIKE 'EXP%') THEN 1 ELSE 0 END) AS Tetap1,
    SUM(CASE WHEN karyawan.gol = 2 AND (karyawan.emno LIKE '0%' OR karyawan.emno REGEXP '^[0-9]+$' OR karyawan.emno LIKE 'EXP%') THEN 1 ELSE 0 END) AS Tetap2,
    SUM(CASE WHEN karyawan.gol = 2 AND (karyawan.emno LIKE 'P%') THEN 1 ELSE 0 END) AS Trainee2,
    SUM(CASE WHEN karyawan.gol = 3 AND (karyawan.emno LIKE '0%' OR karyawan.emno REGEXP '^[0-9]+$' OR karyawan.emno LIKE 'EXP%') THEN 1 ELSE 0 END) AS Tetap3,
    SUM(CASE WHEN karyawan.gol = 3 AND (karyawan.emno LIKE 'P%') THEN 1 ELSE 0 END) AS Trainee3,
    SUM(CASE WHEN karyawan.gol = 4 AND (karyawan.emno LIKE '0%' OR karyawan.emno REGEXP '^[0-9]+$' OR karyawan.emno LIKE 'EXP%') THEN 1 ELSE 0 END) AS Tetap4,
    SUM(CASE WHEN karyawan.gol = 4 AND (karyawan.emno LIKE 'P%') THEN 1 ELSE 0 END) AS Trainee4,
    SUM(CASE WHEN karyawan.gol = 5 AND (karyawan.emno LIKE '0%' OR karyawan.emno REGEXP '^[0-9]+$' OR karyawan.emno LIKE 'EXP%') THEN 1 ELSE 0 END) AS Tetap5,
    SUM(CASE WHEN karyawan.gol IN (6, 7) AND (karyawan.emno LIKE '0%' OR karyawan.emno REGEXP '^[0-9]+$' OR karyawan.emno LIKE 'EXP%') THEN 1 ELSE 0 END) AS Tetap6_7,
    SUM(CASE WHEN karyawan.emno LIKE '0%' OR karyawan.emno REGEXP '^[0-9]+$' OR karyawan.emno LIKE 'EXP%' THEN 1 ELSE 0 END) AS Tetap  
    FROM 
    karyawan
WHERE
($defaultDateCondition OR (resdate > '$endDate'))
    AND joindate <= '$endDate'";

$resultDashboard1 = mysqli_query($koneksi, $queryDashboard1);

// Memeriksa apakah query berhasil dieksekusi
if (!$resultDashboard1) {
    die("Query gagal: " . mysqli_error($koneksi));
}

// Simpan hasil query ke dalam variabel $result
// Misalnya:
$row = mysqli_fetch_assoc($resultDashboard1);

// Simpan hasil query ke dalam variabel PHP
$Kontrak = $row['Kontrak'];
$Tetap1 = $row['Tetap1'];
$Tetap2 = $row['Tetap2'];
$Trainee2 = $row['Trainee2'];
$Tetap3 = $row['Tetap3'];
$Trainee3 = $row['Trainee3'];
$Tetap4 = $row['Tetap4'];
$Trainee4 = $row['Trainee4'];
$Tetap5 = $row['Tetap5'];
$Tetap6_7 = $row['Tetap6_7'];
$Tetap = $row['Tetap'];

//SECTION
$queryTotalSect = "SELECT sect.Id_sect, sect.desc, COUNT(*) AS total_karyawan 
FROM karyawan 
INNER JOIN sect ON karyawan.sect = sect.Id_sect 
WHERE karyawan.resdate is NULL
GROUP BY sect.Id_sect, sect.desc";

$resultTotalSect = $koneksi->query($queryTotalSect);

// Memasukkan data cwoc dan jumlah Dashboard ke dalam array asosiatif
$DashboardBySect = array();
while ($row = $resultTotalSect->fetch_assoc()) {
    $sect = $row['Id_sect']; // Perbaikan nama variabel
// Memasukkan nilai 'Kontrak', 'Tetap1', dan 'Tetap2' ke dalam array dengan menggunakan key yang berbeda
    $DashboardBySect[$sect] = array(
        'total_karyawan' => $row['total_karyawan']
    );
}

//SUBSECTION
$queryTotalSubSect = "SELECT subsect.id_subsect, subsect.desc, COUNT(*) AS total_karyawan 
FROM karyawan 
INNER JOIN subsect ON karyawan.subsect = subsect.id_subsect 
WHERE karyawan.resdate is NULL
GROUP BY subsect.id_subsect, subsect.desc";

$resultTotalSubSect = $koneksi->query($queryTotalSubSect);

// Memasukkan data cwoc dan jumlah Dashboard ke dalam array asosiatif
$DashboardBySubSect = array();
while ($row = $resultTotalSubSect->fetch_assoc()) {
    $subsect = $row['id_subsect']; // Perbaikan nama variabel
// Memasukkan nilai 'Kontrak', 'Tetap1', dan 'Tetap2' ke dalam array dengan menggunakan key yang berbeda
    $DashboardBySubSect[$subsect] = array(
        'total_karyawan' => $row['total_karyawan']
    );
}


//QUERY MODAL
$queryRMPModal = "SELECT DISTINCT cwoc FROM karyawan ORDER BY
          FIELD(cwoc, 'PRODUCTION 1', 'PRODUCTION 2', 'PRODUCTION 3', 'PRODUCTION 4', 'PRODUCTION 5', 'PRODUCTION SYSTEM', 'PDE 2W', 'PDE 4W', 'PCE', 'PE 2W', 'PE 4W', 'PPC', 'QA', 'CQE 2W', 'CQE 4W', 'PROCUREMENT',
                'VENDOR DEVELOPMENT', 'GENERAL PURCHASE', 'WAREHOUSE', 'MARKETING', 'MIS', 'PDCA CPC', 'FINANCE ACCOUNTING', 'PLANNING BUDGETING', 'HRD IR', 'GA', 'EHS', 'COMITTEE QCC - SS', 'BOD')";

$RMPModalResult = mysqli_query($koneksi, $queryRMPModal);

//MODAL UMUR
$queryModalUmur = "SELECT 
                    karyawan.cwoc,
                    SUM(CASE WHEN sexe = 'L' AND TIMESTAMPDIFF(YEAR, birthday, '$endDate') < 18 THEN 1 ELSE 0 END) AS '<18 Pria',
                    SUM(CASE WHEN sexe = 'P' AND TIMESTAMPDIFF(YEAR, birthday, '$endDate') < 18 THEN 1 ELSE 0 END) AS '<18 Perempuan',
                    SUM(CASE WHEN sexe = 'L' AND TIMESTAMPDIFF(YEAR, birthday, '$endDate') BETWEEN 18 AND 25 THEN 1 ELSE 0 END) AS '18-25 Pria',
                    SUM(CASE WHEN sexe = 'P' AND TIMESTAMPDIFF(YEAR, birthday, '$endDate') BETWEEN 18 AND 25 THEN 1 ELSE 0 END) AS '18-25 Perempuan',
                    SUM(CASE WHEN sexe = 'L' AND TIMESTAMPDIFF(YEAR, birthday, '$endDate') BETWEEN 26 AND 30 THEN 1 ELSE 0 END) AS '26-30 Pria',
                    SUM(CASE WHEN sexe = 'P' AND TIMESTAMPDIFF(YEAR, birthday, '$endDate') BETWEEN 26 AND 30 THEN 1 ELSE 0 END) AS '26-30 Perempuan',
                    SUM(CASE WHEN sexe = 'L' AND TIMESTAMPDIFF(YEAR, birthday, '$endDate') BETWEEN 31 AND 35 THEN 1 ELSE 0 END) AS '31-35 Pria',
                    SUM(CASE WHEN sexe = 'P' AND TIMESTAMPDIFF(YEAR, birthday, '$endDate') BETWEEN 31 AND 35 THEN 1 ELSE 0 END) AS '31-35 Perempuan',
                    SUM(CASE WHEN sexe = 'L' AND TIMESTAMPDIFF(YEAR, birthday, '$endDate') BETWEEN 36 AND 40 THEN 1 ELSE 0 END) AS '36-40 Pria',
                    SUM(CASE WHEN sexe = 'P' AND TIMESTAMPDIFF(YEAR, birthday, '$endDate') BETWEEN 36 AND 40 THEN 1 ELSE 0 END) AS '36-40 Perempuan',
                    SUM(CASE WHEN sexe = 'L' AND TIMESTAMPDIFF(YEAR, birthday, '$endDate') BETWEEN 41 AND 45 THEN 1 ELSE 0 END) AS '41-45 Pria',
                    SUM(CASE WHEN sexe = 'P' AND TIMESTAMPDIFF(YEAR, birthday, '$endDate') BETWEEN 41 AND 45 THEN 1 ELSE 0 END) AS '41-45 Perempuan',
                    SUM(CASE WHEN sexe = 'L' AND TIMESTAMPDIFF(YEAR, birthday, '$endDate') BETWEEN 46 AND 50 THEN 1 ELSE 0 END) AS '46-50 Pria',
                    SUM(CASE WHEN sexe = 'P' AND TIMESTAMPDIFF(YEAR, birthday, '$endDate') BETWEEN 46 AND 50 THEN 1 ELSE 0 END) AS '46-50 Perempuan',
                    SUM(CASE WHEN sexe = 'L' AND TIMESTAMPDIFF(YEAR, birthday, '$endDate') BETWEEN 51 AND 55 THEN 1 ELSE 0 END) AS '51-55 Pria',
                    SUM(CASE WHEN sexe = 'P' AND TIMESTAMPDIFF(YEAR, birthday, '$endDate') BETWEEN 51 AND 55 THEN 1 ELSE 0 END) AS '51-55 Perempuan',
                    SUM(CASE WHEN sexe = 'L' AND TIMESTAMPDIFF(YEAR, birthday, '$endDate') > 55 THEN 1 ELSE 0 END) AS '>55 Pria',
                    SUM(CASE WHEN sexe = 'P' AND TIMESTAMPDIFF(YEAR, birthday, '$endDate') > 55 THEN 1 ELSE 0 END) AS '>55 Perempuan'
                    FROM karyawan
                    WHERE
                    ($defaultDateCondition OR (resdate > '$endDate'))
                        AND joindate <= '$endDate'
                    GROUP BY karyawan.cwoc";


$resultModalUmur = mysqli_query($koneksi, $queryModalUmur);

// Memasukkan data cwoc dan jumlah Dashboard ke dalam array asosiatif
$ModalUmurByCwoc = array();
while ($row = mysqli_fetch_assoc($resultModalUmur)) {
    $cwoc = $row['cwoc'];
    // Memasukkan nilai umur ke dalam array dengan menggunakan key yang berbeda
    $ModalUmurByCwoc[$cwoc] = array(
        '<18 Pria' => $row['<18 Pria'],
        '<18 Perempuan' => $row['<18 Perempuan'],
        '18-25 Pria' => $row['18-25 Pria'],
        '18-25 Perempuan' => $row['18-25 Perempuan'],
        '26-30 Pria' => $row['26-30 Pria'],
        '26-30 Perempuan' => $row['26-30 Perempuan'],
        '31-35 Pria' => $row['31-35 Pria'],
        '31-35 Perempuan' => $row['31-35 Perempuan'],
        '36-40 Pria' => $row['36-40 Pria'],
        '36-40 Perempuan' => $row['36-40 Perempuan'],
        '41-45 Pria' => $row['41-45 Pria'],
        '41-45 Perempuan' => $row['41-45 Perempuan'],
        '46-50 Pria' => $row['46-50 Pria'],
        '46-50 Perempuan' => $row['46-50 Perempuan'],
        '51-55 Pria' => $row['51-55 Pria'],
        '51-55 Perempuan' => $row['51-55 Perempuan'],
        '>55 Pria' => $row['>55 Pria'],
        '>55 Perempuan' => $row['>55 Perempuan']
    );
}


//MODAL MK
$queryModalMK = "SELECT 
                karyawan.cwoc,
                SUM(CASE WHEN sexe = 'L' AND TIMESTAMPDIFF(YEAR, joindate, CURDATE()) BETWEEN 0 AND 5 THEN 1 ELSE 0 END) AS '0-5 Pria Modal',
                SUM(CASE WHEN sexe = 'P' AND TIMESTAMPDIFF(YEAR, joindate, CURDATE()) BETWEEN 0 AND 5 THEN 1 ELSE 0 END) AS '0-5 Perempuan Modal',
                SUM(CASE WHEN sexe = 'L' AND TIMESTAMPDIFF(YEAR, joindate, CURDATE()) BETWEEN 6 AND 10 THEN 1 ELSE 0 END) AS '6-10 Pria Modal',
                SUM(CASE WHEN sexe = 'P' AND TIMESTAMPDIFF(YEAR, joindate, CURDATE()) BETWEEN 6 AND 10 THEN 1 ELSE 0 END) AS '6-10 Perempuan Modal',
                SUM(CASE WHEN sexe = 'L' AND TIMESTAMPDIFF(YEAR, joindate, CURDATE()) BETWEEN 11 AND 15 THEN 1 ELSE 0 END) AS '11-15 Pria Modal',
                SUM(CASE WHEN sexe = 'P' AND TIMESTAMPDIFF(YEAR, joindate, CURDATE()) BETWEEN 11 AND 15 THEN 1 ELSE 0 END) AS '11-15 Perempuan Modal',
                SUM(CASE WHEN sexe = 'L' AND TIMESTAMPDIFF(YEAR, joindate, CURDATE()) BETWEEN 16 AND 20 THEN 1 ELSE 0 END) AS '16-20 Pria Modal',
                SUM(CASE WHEN sexe = 'P' AND TIMESTAMPDIFF(YEAR, joindate, CURDATE()) BETWEEN 16 AND 20 THEN 1 ELSE 0 END) AS '16-20 Perempuan Modal',
                SUM(CASE WHEN sexe = 'L' AND TIMESTAMPDIFF(YEAR, joindate, CURDATE()) BETWEEN 21 AND 25 THEN 1 ELSE 0 END) AS '21-25 Pria Modal',
                SUM(CASE WHEN sexe = 'P' AND TIMESTAMPDIFF(YEAR, joindate, CURDATE()) BETWEEN 21 AND 25 THEN 1 ELSE 0 END) AS '21-25 Perempuan Modal',
                SUM(CASE WHEN sexe = 'L' AND TIMESTAMPDIFF(YEAR, joindate, CURDATE()) BETWEEN 26 AND 30 THEN 1 ELSE 0 END) AS '26-30 Pria Modal',
                SUM(CASE WHEN sexe = 'P' AND TIMESTAMPDIFF(YEAR, joindate, CURDATE()) BETWEEN 26 AND 30 THEN 1 ELSE 0 END) AS '26-30 Perempuan Modal',
                SUM(CASE WHEN sexe = 'L' AND TIMESTAMPDIFF(YEAR, joindate, CURDATE()) > 30 THEN 1 ELSE 0 END) AS '>30 Pria Modal',
                SUM(CASE WHEN sexe = 'P' AND TIMESTAMPDIFF(YEAR, joindate, CURDATE()) > 30 THEN 1 ELSE 0 END) AS '>30 Perempuan Modal'
                FROM 
                karyawan 
                WHERE
                ($defaultDateCondition OR (resdate > '$endDate'))
                    AND joindate <= '$endDate'
                    GROUP BY karyawan.cwoc";

$resultModalMK = mysqli_query($koneksi, $queryModalMK);

// Memasukkan data cwoc dan jumlah Dashboard ke dalam array asosiatif
$ModalMKByCwoc = array();
while ($row = mysqli_fetch_assoc($resultModalMK)) {
    $cwoc = $row['cwoc'];
    // Memasukkan nilai MK ke dalam array dengan menggunakan key yang berbeda
    $ModalMKByCwoc[$cwoc] = array(
        '0-5 Pria Modal' => $row['0-5 Pria Modal'],
        '0-5 Perempuan Modal' => $row['0-5 Perempuan Modal'],
        '6-10 Pria Modal' => $row['6-10 Pria Modal'],
        '6-10 Perempuan Modal' => $row['6-10 Perempuan Modal'],
        '11-15 Pria Modal' => $row['11-15 Pria Modal'],
        '11-15 Perempuan Modal' => $row['11-15 Perempuan Modal'],
        '16-20 Pria Modal' => $row['16-20 Pria Modal'],
        '16-20 Perempuan Modal' => $row['16-20 Perempuan Modal'],
        '21-25 Pria Modal' => $row['21-25 Pria Modal'],
        '21-25 Perempuan Modal' => $row['21-25 Perempuan Modal'],
        '26-30 Pria Modal' => $row['26-30 Pria Modal'],
        '26-30 Perempuan Modal' => $row['26-30 Perempuan Modal'],
        '>30 Pria Modal' => $row['>30 Pria Modal'],
        '>30 Perempuan Modal' => $row['>30 Perempuan Modal']
    );
}

//MODAL PENDIDIKAN
$queryModalPendidikan = "SELECT 
                        karyawan.cwoc,
                        SUM(CASE WHEN pendidikan IN ('SD', 'SLTP', 'SMP') AND sexe = 'L' THEN 1 ELSE 0 END) AS 'Pria SD, SLTP Modal',
                        SUM(CASE WHEN pendidikan IN ('SD', 'SLTP', 'SMP') AND sexe = 'P' THEN 1 ELSE 0 END) AS 'Perempuan SD, SLTP Modal',
                        SUM(CASE WHEN pendidikan IN ('SLTA', 'SMA', 'SMK', 'SMU', 'SPG', 'SMEA') AND sexe = 'L' THEN 1 ELSE 0 END) AS 'Pria SMA Modal',
                        SUM(CASE WHEN pendidikan IN ('SLTA', 'SMA', 'SMK', 'SMU', 'SPG', 'SMEA') AND sexe = 'P' THEN 1 ELSE 0 END) AS 'Perempuan SMA Modal',
                        SUM(CASE WHEN pendidikan IN ('D3', 'D4') AND sexe = 'L' THEN 1 ELSE 0 END) AS 'Pria Diploma Modal',
                        SUM(CASE WHEN pendidikan IN ('D3', 'D4') AND sexe = 'P' THEN 1 ELSE 0 END) AS 'Perempuan Diploma Modal',
                        SUM(CASE WHEN pendidikan = 'S1' AND sexe = 'L' THEN 1 ELSE 0 END) AS 'Pria S1 Modal',
                        SUM(CASE WHEN pendidikan = 'S1' AND sexe = 'P' THEN 1 ELSE 0 END) AS 'Perempuan S1 Modal',
                        SUM(CASE WHEN pendidikan IN ('S2', 'S3') AND sexe = 'L' THEN 1 ELSE 0 END) AS 'Pria S2, S3 Modal',
                        SUM(CASE WHEN pendidikan IN ('S2', 'S3') AND sexe = 'P' THEN 1 ELSE 0 END) AS 'Perempuan S2, S3 Modal',
                        SUM(CASE WHEN pendidikan IN ('SD') AND (karyawan.emno LIKE '0%' OR karyawan.emno REGEXP '^[0-9]+$' OR karyawan.emno LIKE 'P%' OR karyawan.emno LIKE 'EXP%') THEN 1 ELSE 0 END) AS 'SD',
                        SUM(CASE WHEN pendidikan IN ('SLTP', 'SMP') AND (karyawan.emno LIKE '0%' OR karyawan.emno REGEXP '^[0-9]+$' OR karyawan.emno LIKE 'P%' OR karyawan.emno LIKE 'EXP%') THEN 1 ELSE 0 END) AS 'SLTP',
                        SUM(CASE WHEN pendidikan IN ('SLTA', 'SMA', 'SMK', 'SMU', 'SPG', 'SMEA') AND (karyawan.emno LIKE '0%' OR karyawan.emno REGEXP '^[0-9]+$' OR karyawan.emno LIKE 'P%' OR karyawan.emno LIKE 'EXP%' ) THEN 1 ELSE 0 END) AS 'SLTA',
                        SUM(CASE WHEN pendidikan IN ('D1', 'D2') AND (karyawan.emno LIKE '0%' OR karyawan.emno REGEXP '^[0-9]+$' OR karyawan.emno LIKE 'P%' OR karyawan.emno LIKE 'EXP%') THEN 1 ELSE 0 END) AS 'D1_D2',
                        SUM(CASE WHEN pendidikan IN ('D3') AND (karyawan.emno LIKE '0%' OR karyawan.emno REGEXP '^[0-9]+$' OR karyawan.emno LIKE 'P%' OR karyawan.emno LIKE 'EXP%') THEN 1 ELSE 0 END) AS 'D3',
                        SUM(CASE WHEN pendidikan IN ('S1') AND (karyawan.emno LIKE '0%' OR karyawan.emno REGEXP '^[0-9]+$' OR karyawan.emno LIKE 'P%' OR karyawan.emno LIKE 'EXP%') THEN 1 ELSE 0 END) AS 'S1',
                        SUM(CASE WHEN pendidikan IN ('S2', 'S3') AND (karyawan.emno LIKE '0%' OR karyawan.emno REGEXP '^[0-9]+$' OR karyawan.emno LIKE 'P%' OR karyawan.emno LIKE 'EXP%') THEN 1 ELSE 0 END) AS 'S2_S3'
                        FROM karyawan 
                            WHERE
                            ($defaultDateCondition OR (resdate > '$endDate'))
                                AND joindate <= '$endDate'
                        GROUP BY karyawan.cwoc";

$resultModalPendidikan = mysqli_query($koneksi, $queryModalPendidikan);

// Memasukkan data cwoc dan jumlah Dashboard ke dalam array asosiatif
$ModalPendidikanByCwoc = array();
while ($row = mysqli_fetch_assoc($resultModalPendidikan)) {
    $cwoc = $row['cwoc'];
    // Memasukkan nilai Pendidikan ke dalam array dengan menggunakan key yang berbeda
    $ModalPendidikanByCwoc[$cwoc] = array(
        'Pria SD, SLTP Modal' => $row['Pria SD, SLTP Modal'],
        'Perempuan SD, SLTP Modal' => $row['Perempuan SD, SLTP Modal'],
        'Pria SMA Modal' => $row['Pria SMA Modal'],
        'Perempuan SMA Modal' => $row['Perempuan SMA Modal'],
        'Pria Diploma Modal' => $row['Pria Diploma Modal'],
        'Perempuan Diploma Modal' => $row['Perempuan Diploma Modal'],
        'Pria S1 Modal' => $row['Pria S1 Modal'],
        'Perempuan S1 Modal' => $row['Perempuan S1 Modal'],
        'Pria S2, S3 Modal' => $row['Pria S2, S3 Modal'],
        'Perempuan S2, S3 Modal' => $row['Perempuan S2, S3 Modal'],
        'SD' => $row['SD'],
        'SLTP' => $row['SLTP'],
        'SLTA' => $row['SLTA'],
        'D1_D2' => $row['D1_D2'],
        'D3' => $row['D3'],
        'S1' => $row['S1'],
        'S2_S3' => $row['S2_S3']
    );
}

//MODAL JK
$queryModalJK = "SELECT 
                karyawan.cwoc,
                SUM(CASE WHEN sexe = 'L' THEN 1 ELSE 0 END) AS 'Pria Modal',
                SUM(CASE WHEN sexe = 'P' THEN 1 ELSE 0 END) AS 'Perempuan Modal'
                FROM karyawan 
                WHERE
                ($defaultDateCondition OR (resdate > '$endDate'))
                    AND joindate <= '$endDate'
                GROUP BY karyawan.cwoc";

$resultModalJK = mysqli_query($koneksi, $queryModalJK);

// Memasukkan data cwoc dan jumlah Dashboard ke dalam array asosiatif
$ModalJKByCwoc = array();
while ($row = mysqli_fetch_assoc($resultModalJK)) {
    $cwoc = $row['cwoc'];
    // Memasukkan nilai JK ke dalam array dengan menggunakan key yang berbeda
    $ModalJKByCwoc[$cwoc] = array(
        'Pria Modal' => $row['Pria Modal'],
        'Perempuan Modal' => $row['Perempuan Modal']
    );
}

$queryDL = "SELECT 
    karyawan.sect,
    SUM(CASE WHEN karyawan.gol IN (1, 2, 3) AND karyawan.emno LIKE 'K%' AND TIMESTAMPDIFF(YEAR, joindate, '$endDate') >= 1 THEN 1 ELSE 0 END) AS C2,
    SUM(CASE WHEN karyawan.gol IN (1, 2, 3) AND karyawan.emno LIKE 'K%' AND TIMESTAMPDIFF(YEAR, joindate, '$endDate') < 1 AND joindate < '$endDate' THEN 1 ELSE 0 END) AS C1,
    SUM(CASE WHEN karyawan.gol = 3 AND (karyawan.emno LIKE '0%' OR karyawan.emno REGEXP '^[0-9]+$' OR karyawan.emno LIKE 'EXP%') THEN 1 ELSE 0 END) AS III,
    SUM(CASE WHEN karyawan.gol IN (1, 2) AND (karyawan.emno LIKE '0%' OR karyawan.emno REGEXP '^[0-9]+$' OR karyawan.emno LIKE 'EXP%') THEN 1 ELSE 0 END) AS I_II,
    SUM(CASE WHEN karyawan.gol IN (1, 2, 3) AND karyawan.emno LIKE 'P%' THEN 1 ELSE 0 END) AS T
    FROM 
    karyawan
    WHERE
($defaultDateCondition OR (resdate > '$endDate'))
    AND joindate <= '$endDate'
    GROUP BY 
    karyawan.sect";

$resultDL = $koneksi->query($queryDL);

// Memasukkan data cwoc dan jumlah Dashboard ke dalam array asosiatif
$Excelbysect = array();
while ($row = $resultDL->fetch_assoc()) {
    $sect = $row['sect'];
    // Memasukkan nilai 'Kontrak', 'Tetap1', dan 'Tetap2' ke dalam array dengan menggunakan key yang berbeda
    $Excelbysect[$sect] = array(
        'C2' => $row['C2'],
        'C1' => $row['C1'],
        'III' => $row['III'],
        'I_II' => $row['I_II'],
        'T' => $row['T']
    );
}

$queryDLBulanLalu = "SELECT 
    karyawan.sect,
    SUM(CASE WHEN karyawan.gol IN (1, 2, 3) AND karyawan.emno LIKE 'K%' AND TIMESTAMPDIFF(YEAR, joindate, '$endDate2') >= 1 THEN 1 ELSE 0 END) AS C2,
    SUM(CASE WHEN karyawan.gol IN (1, 2, 3) AND karyawan.emno LIKE 'K%' AND TIMESTAMPDIFF(YEAR, joindate, '$endDate2') < 1 AND joindate < '$endDate2' THEN 1 ELSE 0 END) AS C1,
    SUM(CASE WHEN karyawan.gol = 3 AND (karyawan.emno LIKE '0%' OR karyawan.emno REGEXP '^[0-9]+$' OR karyawan.emno LIKE 'EXP%') THEN 1 ELSE 0 END) AS III,
    SUM(CASE WHEN karyawan.gol IN (1, 2) AND (karyawan.emno LIKE '0%' OR karyawan.emno REGEXP '^[0-9]+$' OR karyawan.emno LIKE 'EXP%') THEN 1 ELSE 0 END) AS I_II,
    SUM(CASE WHEN karyawan.gol IN (1, 2, 3) AND karyawan.emno LIKE 'P%' THEN 1 ELSE 0 END) AS T
    FROM 
    karyawan
    WHERE
($defaultDateCondition OR (resdate > '$endDate2'))
    AND joindate <= '$endDate2'
    GROUP BY 
    karyawan.sect";

$resultDLBulanLalu = $koneksi->query($queryDLBulanLalu);

// Memasukkan data cwoc dan jumlah Dashboard ke dalam array asosiatif
$ExcelbysectBulanLalu = array();
while ($row = $resultDLBulanLalu->fetch_assoc()) {
    $sect = $row['sect'];
    // Memasukkan nilai 'Kontrak', 'Tetap1', dan 'Tetap2' ke dalam array dengan menggunakan key yang berbeda
    $ExcelbysectBulanLalu[$sect] = array(
        'C2' => $row['C2'],
        'C1' => $row['C1'],
        'III' => $row['III'],
        'I_II' => $row['I_II'],
        'T' => $row['T']
    );
}

$queryIDL = "SELECT 
    karyawan.cwoc,
    SUM(CASE WHEN karyawan.gol IN (4, 5, 6) AND karyawan.emno LIKE 'K%' AND TIMESTAMPDIFF(YEAR, joindate, '$endDate') >= 1 THEN 1 ELSE 0 END) AS C2,
    SUM(CASE WHEN karyawan.gol IN (4, 5, 6) AND karyawan.emno LIKE 'K%' AND TIMESTAMPDIFF(YEAR, joindate, '$endDate') < 1 AND joindate < '$endDate' THEN 1 ELSE 0 END) AS C1,
    SUM(CASE WHEN karyawan.gol IN (4, 5, 6) AND (karyawan.emno LIKE '0%' OR karyawan.emno REGEXP '^[0-9]+$' OR karyawan.emno LIKE 'EXP%') THEN 1 ELSE 0 END) AS IV_VI,
    SUM(CASE WHEN karyawan.gol IN (4, 5, 6) AND (karyawan.emno LIKE '0%' OR karyawan.emno REGEXP '^[0-9]+$' OR karyawan.emno LIKE 'EXP%') THEN 1 ELSE 0 END) AS III_VI,
    SUM(CASE WHEN karyawan.gol IN (1, 2) AND (karyawan.emno LIKE '0%' OR karyawan.emno REGEXP '^[0-9]+$' OR karyawan.emno LIKE 'EXP%') THEN 1 ELSE 0 END) AS I_II,
    SUM(CASE WHEN karyawan.gol IN (4, 5, 6) AND karyawan.emno LIKE 'P%' THEN 1 ELSE 0 END) AS T
    FROM 
    karyawan
        WHERE
        ($defaultDateCondition OR (resdate > '$endDate'))
            AND joindate <= '$endDate'
    GROUP BY 
    karyawan.cwoc";

$resultIDL = $koneksi->query($queryIDL);

// Memasukkan data cwoc dan jumlah Dashboard ke dalam array asosiatif
$ExceIDLbycwoc = array();
while ($row = $resultIDL->fetch_assoc()) {
    $cwoc = $row['cwoc'];
    // Memasukkan nilai 'C2', 'C1', 'IV_VI', 'III_VI', 'I_II', dan 'T' ke dalam array dengan menggunakan key yang sesuai
    $ExcelDLbycwoc[$cwoc] = array(
        'C2' => $row['C2'],
        'C1' => $row['C1'],
        'IV_VI' => $row['IV_VI'],
        'III_VI' => $row['III_VI'],
        'I_II' => $row['I_II'],
        'T' => $row['T']
    );
}

$queryIDLLalu = "SELECT 
    karyawan.cwoc,
    SUM(CASE WHEN karyawan.gol IN (4, 5, 6, 7) AND karyawan.emno LIKE 'K%' AND TIMESTAMPDIFF(YEAR, joindate, '$endDate2') >= 1 THEN 1 ELSE 0 END) AS C2,
    SUM(CASE WHEN karyawan.gol IN (4, 5, 6, 7) AND karyawan.emno LIKE 'K%' AND TIMESTAMPDIFF(YEAR, joindate, '$endDate2') < 1 AND joindate < '$endDate2' THEN 1 ELSE 0 END) AS C1,
    SUM(CASE WHEN karyawan.gol IN (4, 5, 6, 7) AND (karyawan.emno LIKE '0%' OR karyawan.emno REGEXP '^[0-9]+$' OR karyawan.emno LIKE 'EXP%') THEN 1 ELSE 0 END) AS IV_VI,
    SUM(CASE WHEN karyawan.gol IN (4, 5, 6, 7) AND (karyawan.emno LIKE '0%' OR karyawan.emno REGEXP '^[0-9]+$' OR karyawan.emno LIKE 'EXP%') THEN 1 ELSE 0 END) AS III_VI,
    SUM(CASE WHEN karyawan.gol IN (1, 2) AND (karyawan.emno LIKE '0%' OR karyawan.emno REGEXP '^[0-9]+$' OR karyawan.emno LIKE 'EXP%') THEN 1 ELSE 0 END) AS I_II,
    SUM(CASE WHEN karyawan.gol IN (4, 5, 6, 7) AND karyawan.emno LIKE 'P%' THEN 1 ELSE 0 END) AS T
    FROM 
    karyawan
WHERE
($defaultDateCondition OR (resdate > '$endDate2'))
    AND joindate <= '$endDate2'
    GROUP BY 
    karyawan.cwoc";

$resultIDLLalu = $koneksi->query($queryIDLLalu);

// Memasukkan data cwoc dan jumlah Dashboard ke dalam array asosiatif
$ExceIDLbycwocLalu = array();
while ($row = $resultIDLLalu->fetch_assoc()) {
    $cwoc = $row['cwoc'];
    // Memasukkan nilai 'C2', 'C1', 'IV_VI', 'III_VI', 'I_II', dan 'T' ke dalam array dengan menggunakan key yang sesuai
    $ExcelDLbycwocLalu[$cwoc] = array(
        'C2' => $row['C2'],
        'C1' => $row['C1'],
        'IV_VI' => $row['IV_VI'],
        'III_VI' => $row['III_VI'],
        'I_II' => $row['I_II'],
        'T' => $row['T']
    );
}

$queryIDL2 = "SELECT 
    karyawan.cwoc,
    SUM(CASE WHEN karyawan.gol IN (1, 2, 3, 4, 5, 6, 7) AND karyawan.emno LIKE 'K%' AND TIMESTAMPDIFF(YEAR, joindate, '$endDate') >= 1 THEN 1 ELSE 0 END) AS C2,
    SUM(CASE WHEN karyawan.gol IN (1, 2, 3, 4, 5, 6, 7) AND karyawan.emno LIKE 'K%' AND TIMESTAMPDIFF(YEAR, joindate, '$endDate') < 1 AND joindate < '$endDate' THEN 1 ELSE 0 END) AS C1,
    SUM(CASE WHEN karyawan.gol IN (3, 4, 5, 6, 7) AND (karyawan.emno LIKE '0%' OR karyawan.emno REGEXP '^[0-9]+$' OR karyawan.emno LIKE 'EXP%') THEN 1 ELSE 0 END) AS III_VI,
    SUM(CASE WHEN karyawan.gol IN (1, 2) AND (karyawan.emno LIKE '0%' OR karyawan.emno REGEXP '^[0-9]+$' OR karyawan.emno LIKE 'EXP%') THEN 1 ELSE 0 END) AS I_II,
    SUM(CASE WHEN karyawan.emno LIKE 'P%' THEN 1 ELSE 0 END) AS T
    FROM 
    karyawan
WHERE
($defaultDateCondition OR (resdate > '$endDate'))
    AND joindate <= '$endDate'
    GROUP BY 
    karyawan.cwoc";

$resultIDL2 = $koneksi->query($queryIDL2);

// Memasukkan data cwoc dan jumlah Dashboard ke dalam array asosiatif
$ExcelIDL2bycwoc = array();
while ($row = $resultIDL2->fetch_assoc()) {
    $cwoc = $row['cwoc'];
    // Memasukkan nilai 'C2', 'C1', 'IV_VI', 'III_VI', 'I_II', dan 'T' ke dalam array dengan menggunakan key yang sesuai
    $ExcelIDL2bycwoc[$cwoc] = array(
        'C2' => $row['C2'],
        'C1' => $row['C1'],
        'III_VI' => $row['III_VI'],
        'I_II' => $row['I_II'],
        'T' => $row['T']
    );
}

$queryIDL2Lalu = "SELECT 
    karyawan.cwoc,
    SUM(CASE WHEN karyawan.gol IN (1, 2, 3, 4, 5, 6, 7) AND karyawan.emno LIKE 'K%' AND TIMESTAMPDIFF(YEAR, joindate, '$endDate2') >= 1 THEN 1 ELSE 0 END) AS C2,
    SUM(CASE WHEN karyawan.gol IN (1, 2, 3, 4, 5, 6, 7) AND karyawan.emno LIKE 'K%' AND TIMESTAMPDIFF(YEAR, joindate, '$endDate2') < 1 AND joindate < '$endDate2' THEN 1 ELSE 0 END) AS C1,
    SUM(CASE WHEN karyawan.gol IN (3, 4, 5, 6, 7) AND (karyawan.emno LIKE '0%' OR karyawan.emno REGEXP '^[0-9]+$' OR karyawan.emno LIKE 'EXP%') THEN 1 ELSE 0 END) AS III_VI,
    SUM(CASE WHEN karyawan.gol IN (1, 2) AND (karyawan.emno LIKE '0%' OR karyawan.emno REGEXP '^[0-9]+$' OR karyawan.emno LIKE 'EXP%') THEN 1 ELSE 0 END) AS I_II,
    SUM(CASE WHEN karyawan.emno LIKE 'P%' THEN 1 ELSE 0 END) AS T
    FROM 
    karyawan
WHERE
($defaultDateCondition OR (resdate > '$endDate2'))
    AND joindate <= '$endDate2'
    GROUP BY 
    karyawan.cwoc";

$resultIDL2Lalu = $koneksi->query($queryIDL2Lalu);

// Memasukkan data cwoc dan jumlah Dashboard ke dalam array asosiatif
$ExcelIDL2bycwocLalu = array();
while ($row = $resultIDL2Lalu->fetch_assoc()) {
    $cwoc = $row['cwoc'];
    // Memasukkan nilai 'C2', 'C1', 'IV_VI', 'III_VI', 'I_II', dan 'T' ke dalam array dengan menggunakan key yang sesuai
    $ExcelIDL2bycwocLalu[$cwoc] = array(
        'C2' => $row['C2'],
        'C1' => $row['C1'],
        'III_VI' => $row['III_VI'],
        'I_II' => $row['I_II'],
        'T' => $row['T']
    );
}


$queryMutOut = "SELECT cwocAsal, 
        COUNT(*) AS jumlah_mutasi_out 
    FROM mutasi 
    WHERE 
        MONTH(tanggalMutasi) = '$selectedMonth'
        AND YEAR(tanggalMutasi) = '$selectedYear'
        AND status = 10 
    GROUP BY cwocAsal";

// Execute the query
$resultMutOut = $koneksi3->query($queryMutOut);

// Check if the query execution was successful
if (!$resultMutOut) {
    die("Query failed: " . $koneksi3->error);
}

// Initialize an associative array to store the data
$ExcelMutOutbycwoc = array();

// Fetch and store the results
while ($row = $resultMutOut->fetch_assoc()) {
    $cwoc = $row['cwocAsal']; // Access the cwocAsal field

    // Store the count of 'jumlah_mutasi_out' in the array using 'cwoc' as the key
    $ExcelMutOutbycwoc[$cwoc] = array(
        'jumlah_mutasi_out' => $row['jumlah_mutasi_out']
    );
}


$queryMutIn = "SELECT cwocBaru, 
        COUNT(*) AS jumlah_mutasi_in 
    FROM mutasi 
    WHERE 
        MONTH(tanggalMutasi) = '$selectedMonth'
        AND YEAR(tanggalMutasi) = '$selectedYear'
        AND status = 10 
    GROUP BY cwocBaru";


// Eksekusi query
$resultMutIn = $koneksi3->query($queryMutIn);

// Memasukkan data jumlah mutasi keluar sesuai dengan departemen asal atau cwoc ke dalam array asosiatif
$ExcelMutInbycwoc = array();
while ($row = $resultMutIn->fetch_assoc()) {
    $cwoc = $row['cwocBaru']; // Gunakan variabel $cwoc jika departemen_asal berisi cwoc
    // Menyimpan nilai 'jumlah_mutasi_In' ke dalam array dengan menggunakan kunci yang sesuai
    $ExcelMutInbycwoc[$cwoc] = array(
        'jumlah_mutasi_in' => $row['jumlah_mutasi_in']
    );
}

$queryInOut = "SELECT
        SUM(CASE WHEN MONTH(joindate) = '$selectedMonth' AND YEAR(joindate) = '$selectedYear' THEN 1 ELSE 0 END) AS `IN`,
        SUM(CASE WHEN MONTH(resdate) = '$selectedMonth' AND YEAR(resdate) = '$selectedYear' THEN 1 ELSE 0 END) AS `OUT`,
cwoc
FROM karyawan
GROUP BY cwoc;";

// Eksekusi query
$resultInOut = $koneksi->query($queryInOut);

// Memasukkan data jumlah mutasi keluar sesuai dengan departemen asal atau cwoc ke dalam array asosiatif
$ExcelInOutbycwoc = array();
while ($row = $resultInOut->fetch_assoc()) {
    $cwoc = $row['cwoc']; // Gunakan variabel $cwoc jika departemen_asal berisi cwoc
    // Menyimpan nilai 'jumlah_mutasi_In' ke dalam array dengan menggunakan kunci yang sesuai
    $ExcelInOutbycwoc[$cwoc] = array(
        'IN' => $row['IN'],
        'OUT' => $row['OUT']
    );
}

$queryMutasiSect = "SELECT mutasi.sectAsal, COUNT(*) as MutasiOut FROM 
        $database3.mutasi
    JOIN 
        $database2.ct_users ON mutasi.emno = ct_users.npk
    WHERE 
        MONTH(mutasi.tanggalMutasi) = '$selectedMonth'
        AND YEAR(mutasi.tanggalMutasi) = '$selectedYear'
        AND mutasi.status = 10
        AND ct_users.golongan IN (0, 1, 2, 3)  -- Only fetch data for gol 0 to 3
    GROUP BY mutasi.sectAsal";

// Execute the query
$resultMutasiSect = $koneksi3->query($queryMutasiSect);

// Check for errors
if (!$resultMutasiSect) {
    die("Query failed: " . $koneksi3->error);
}

// Prepare an associative array to store the mutation data
$ExcelMutasiSectbysect = array();
while ($row = $resultMutasiSect->fetch_assoc()) {
    $sect_asal = $row['sectAsal'];
    $MutasiOut = $row['MutasiOut'];

    // Store the count of 'MutasiOut' in the associative array
    $ExcelMutasiSectbysect[$sect_asal] = array(
        'MutasiOut' => $MutasiOut
    );
}




$ExcelMutasiInSectbysect = array();

// Your query to get data for mutasi in sections
$queryMutasiInSect = "SELECT mutasi.sectBaru, COUNT(*) as MutasiIn FROM 
        $database3.mutasi
    JOIN 
        $database2.ct_users ON mutasi.emno = ct_users.npk
    WHERE 
        MONTH(mutasi.tanggalMutasi) = '$selectedMonth'
        AND YEAR(mutasi.tanggalMutasi) = '$selectedYear'
        AND mutasi.status = 10
        AND ct_users.golongan IN (0, 1, 2, 3)  -- Only fetch data for gol 0 to 3
    GROUP BY mutasi.sectBaru";

$resultMutasiInSect = $koneksi3->query($queryMutasiInSect);

while ($row = $resultMutasiInSect->fetch_assoc()) {
    $sectBaru = $row['sectBaru']; // Mengambil nilai 'sectBaru' dari hasil query
    // Menyimpan nilai 'MutasiIn' ke dalam array dengan menggunakan kunci yang sesuai
    $ExcelMutasiInSectbysect[$sectBaru] = array(
        'MutasiIn' => $row['MutasiIn'] // Memasukkan jumlah mutasi masuk ke dalam array dengan kunci 'sect_sekarang'
    );
}


$queryInOutSect = "SELECT
        SUM(CASE WHEN MONTH(joindate) = '$selectedMonth' AND YEAR(joindate) = '$selectedYear' AND gol BETWEEN 0 AND 3 THEN 1 ELSE 0 END) AS `IN`,
        SUM(CASE WHEN MONTH(resdate) = '$selectedMonth' AND YEAR(resdate) = '$selectedYear' AND gol BETWEEN 0 AND 3 THEN 1 ELSE 0 END) AS `OUT`,
                sect
                FROM karyawan
                GROUP BY sect;";

// Eksekusi query
$resultInOutSect = $koneksi->query($queryInOutSect);

// Memasukkan data jumlah mutasi keluar sesuai dengan departemen asal atau cwoc ke dalam array asosiatif
$ExcelInOutbysect = array();
while ($row = $resultInOutSect->fetch_assoc()) {
    $sect = $row['sect']; // Gunakan variabel $sect jika departemen_asal berisi sect
    // Menyimpan nilai 'jumlah_mutasi_In' ke dalam array dengan menggunakan kunci yang sesuai
    $ExcelInOutbysect[$sect] = array(
        'IN' => $row['IN'],
        'OUT' => $row['OUT']
    );
}

$queryMutOut2 = "SELECT mutasi.cwocAsal, COUNT(*) as jumlah_mutasi_out FROM 
        $database3.mutasi
    JOIN 
        $database2.ct_users ON mutasi.emno = ct_users.npk
    WHERE 
        MONTH(mutasi.tanggalMutasi) = '$selectedMonth'
        AND YEAR(mutasi.tanggalMutasi) = '$selectedYear'
        AND mutasi.status = 10
        AND ct_users.golongan IN (4, 5, 6)  -- Only fetch data for gol 0 to 3
    GROUP BY mutasi.cwocAsal";

$resultMutOut2 = $koneksi->query($queryMutOut2);

$ExcelMutOut2bycwoc = array();
while ($row = $resultMutOut2->fetch_assoc()) {
    $cwoc = $row['cwocAsal'];
    $ExcelMutOut2bycwoc[$cwoc] = array(
        'jumlah_mutasi_out' => $row['jumlah_mutasi_out']
    );
}

$queryMutIn2 = "SELECT mutasi.cwocAsal, COUNT(*) as jumlah_mutasi_out FROM 
        $database3.mutasi
    JOIN 
        $database2.ct_users ON mutasi.emno = ct_users.npk
    WHERE 
        MONTH(mutasi.tanggalMutasi) = '$selectedMonth'
        AND YEAR(mutasi.tanggalMutasi) = '$selectedYear'
        AND mutasi.status = 10
        AND ct_users.golongan IN (4, 5, 6)  -- Only fetch data for gol 0 to 3
    GROUP BY mutasi.cwocAsal";

$resultMutIn2 = $koneksi->query($queryMutIn2);

$ExcelMutIn2bycwoc = array();
while ($row = $resultMutIn2->fetch_assoc()) {
    $cwoc = $row['histori_cwoc'];
    $ExcelMutIn2bycwoc[$cwoc] = array(
        'jumlah_mutasi_in' => $row['jumlah_mutasi_in']
    );
}

$queryInOut2 = "SELECT
SUM(CASE WHEN MONTH(joindate) = '$selectedMonth' AND YEAR(joindate) = '$selectedYear' THEN 1 ELSE 0 END) AS `IN`,
SUM(CASE WHEN MONTH(resdate) = '$selectedMonth' AND YEAR(resdate) = '$selectedYear' THEN 1 ELSE 0 END) AS `OUT`,
cwoc
FROM karyawan
WHERE gol BETWEEN 4 AND 6
GROUP BY cwoc;";

// Eksekusi query
$resultInOut2 = $koneksi->query($queryInOut2);

$ExcelInOut2bycwoc = array();
while ($row = $resultInOut2->fetch_assoc()) {
    $cwoc = $row['cwoc'];
    $ExcelInOut2bycwoc[$cwoc] = array(
        'IN' => $row['IN'],
        'OUT' => $row['OUT']
    );
}

$queryBODIndo = "SELECT 
    karyawan.cwoc,
    SUM(CASE WHEN karyawan.gol IN (3, 4, 5, 6, 7) AND karyawan.emno LIKE 'K%' AND TIMESTAMPDIFF(YEAR, joindate, '$endDate') >= 1 THEN 1 ELSE 0 END) AS C2,
    SUM(CASE WHEN karyawan.gol IN (3, 4, 5, 6, 7) AND karyawan.emno LIKE 'K%' AND TIMESTAMPDIFF(YEAR, joindate, '$endDate') < 1 AND joindate < '$endDate' THEN 1 ELSE 0 END) AS C1,
    SUM(CASE WHEN karyawan.gol IN (3, 4, 5, 6, 7) AND (karyawan.emno LIKE '0%' OR karyawan.emno REGEXP '^[0-9]+$') THEN 1 ELSE 0 END) AS III_VI,
    SUM(CASE WHEN karyawan.gol IN (1, 2) AND (karyawan.emno LIKE '0%' OR karyawan.emno REGEXP '^[0-9]+$') THEN 1 ELSE 0 END) AS I_II,
    SUM(CASE WHEN karyawan.emno LIKE 'P%' THEN 1 ELSE 0 END) AS T
    FROM 
    karyawan
WHERE
($defaultDateCondition OR (resdate > '$endDate'))
    AND joindate <= '$endDate' AND cwoc = 'BOD'";

$resultBODIndo = mysqli_query($koneksi, $queryBODIndo);

if (!$resultBODIndo) {
    die("Query gagal: " . mysqli_error($koneksi));
}

$row = mysqli_fetch_assoc($resultBODIndo);

$C2 = $row['C2'];
$C1 = $row['C1'];
$III_VI = $row['III_VI'];
$I_II = $row['I_II'];
$T = $row['T'];

$queryBODIndoLalu = "SELECT 
    karyawan.cwoc,
    SUM(CASE WHEN karyawan.gol IN (3, 4, 5, 6, 7) AND karyawan.emno LIKE 'K%' AND TIMESTAMPDIFF(YEAR, joindate, '$endDate2') >= 1 THEN 1 ELSE 0 END) AS C2Lalu,
    SUM(CASE WHEN karyawan.gol IN (3, 4, 5, 6, 7) AND karyawan.emno LIKE 'K%' AND TIMESTAMPDIFF(YEAR, joindate, '$endDate2') < 1 AND joindate < '$endDate2' THEN 1 ELSE 0 END) AS C1Lalu,
    SUM(CASE WHEN karyawan.gol IN (3, 4, 5, 6, 7) AND (karyawan.emno LIKE '0%' OR karyawan.emno REGEXP '^[0-9]+$') THEN 1 ELSE 0 END) AS III_VILalu,
    SUM(CASE WHEN karyawan.gol IN (1, 2) AND (karyawan.emno LIKE '0%' OR karyawan.emno REGEXP '^[0-9]+$') THEN 1 ELSE 0 END) AS I_IILalu,
    SUM(CASE WHEN karyawan.emno LIKE 'P%' THEN 1 ELSE 0 END) AS TLalu
    FROM 
    karyawan
WHERE
($defaultDateCondition OR (resdate > '$endDate2'))
    AND joindate <= '$endDate2' AND cwoc = 'BOD'";

$resultBODIndoLalu = mysqli_query($koneksi, $queryBODIndoLalu);

if (!$resultBODIndoLalu) {
    die("Query gagal: " . mysqli_error($koneksi));
}

$row = mysqli_fetch_assoc($resultBODIndoLalu);

$C2Lalu = $row['C2Lalu'];
$C1Lalu = $row['C1Lalu'];
$III_VILalu = $row['III_VILalu'];
$I_IILalu = $row['I_IILalu'];
$TLalu = $row['TLalu'];

$queryBODEXP = "SELECT 
    karyawan.cwoc,
    SUM(CASE WHEN karyawan.gol IN (3, 4, 5, 6, 7) AND (karyawan.emno LIKE 'EXP%') THEN 1 ELSE 0 END) AS III_VI,
    SUM(CASE WHEN karyawan.gol IN (1, 2) AND (karyawan.emno LIKE 'EXP%') THEN 1 ELSE 0 END) AS I_II
    FROM 
    karyawan
WHERE
($defaultDateCondition OR (resdate > '$endDate'))
    AND joindate <= '$endDate' AND cwoc = 'BOD'";

$resultBODEXP = mysqli_query($koneksi, $queryBODEXP);

if (!$resultBODEXP) {
    die("Query gagal: " . mysqli_error($koneksi));
}

$row = mysqli_fetch_assoc($resultBODEXP);

$III_VI = $row['III_VI'];
$I_II = $row['I_II'];

$queryBODEXPLalu = "SELECT 
    karyawan.cwoc,
    SUM(CASE WHEN karyawan.gol IN (3, 4, 5, 6, 7) AND (karyawan.emno LIKE 'EXP%') THEN 1 ELSE 0 END) AS III_VILalu,
    SUM(CASE WHEN karyawan.gol IN (1, 2) AND (karyawan.emno LIKE 'EXP%') THEN 1 ELSE 0 END) AS I_IILalu
    FROM 
    karyawan
WHERE
($defaultDateCondition OR (resdate > '$endDate2'))
    AND joindate <= '$endDate2' AND cwoc = 'BOD'";

$resultBODEXPLalu = mysqli_query($koneksi, $queryBODEXPLalu);

if (!$resultBODEXPLalu) {
    die("Query gagal: " . mysqli_error($koneksi));
}

$row = mysqli_fetch_assoc($resultBODEXPLalu);

$III_VILalu = $row['III_VILalu'];
$I_IILalu = $row['I_IILalu'];

$queryInOutBODIndo = "SELECT
    SUM(CASE WHEN MONTH(joindate) = '$selectedMonth' AND YEAR(joindate) = '$selectedYear' THEN 1 ELSE 0 END) AS `IN`,
    SUM(CASE WHEN MONTH(joindate) = '$selectedMonth' AND YEAR(joindate) = '$selectedYear' THEN 1 ELSE 0 END) AS `OUT`,
    cwoc
    FROM karyawan
    WHERE cwoc = 'BOD' AND emno NOT LIKE 'EXP%'
    GROUP BY cwoc;";


$queryMutInBODIndo = "SELECT cwocBaru, 
        COUNT(*) AS jumlah_mutasi_in 
    FROM mutasi 
    WHERE 
        MONTH(tanggalMutasi) = '$selectedMonth'
        AND YEAR(tanggalMutasi) = '$selectedYear'
        AND status = 10 AND emno NOT LIKE 'EXP%' AND cwocBaru = 'BOD'
    GROUP BY cwocBaru";


$queryMutOutBODIndo = "SELECT cwocAsal, 
        COUNT(*) AS jumlah_mutasi_out 
    FROM mutasi 
    WHERE 
        MONTH(tanggalMutasi) = '$selectedMonth'
        AND YEAR(tanggalMutasi) = '$selectedYear'
        AND status = 10 AND emno NOT LIKE 'EXP%' AND cwocAsal = 'BOD'
    GROUP BY cwocAsal";


////////////
$queryInOutBODEXP = "SELECT
    SUM(CASE WHEN MONTH(joindate) = '$selectedMonth' AND YEAR(joindate) = '$selectedYear' THEN 1 ELSE 0 END) AS `IN`,
    SUM(CASE WHEN MONTH(resdate) = '$selectedMonth' AND YEAR(resdate) = '$selectedYear' THEN 1 ELSE 0 END) AS `OUT`,
    cwoc
    FROM karyawan
    WHERE cwoc = 'BOD' AND emno LIKE 'EXP%'
    GROUP BY cwoc";

$queryMutInBODEXP = "SELECT cwocBaru, 
        COUNT(*) AS jumlah_mutasi_in 
    FROM mutasi 
    WHERE 
        MONTH(tanggalMutasi) = '$selectedMonth'
        AND YEAR(tanggalMutasi) = '$selectedYear'
        AND status = 10 AND emno LIKE 'EXP%' AND cwocBaru = 'BOD'
    GROUP BY cwocBaru";



$queryMutOutBODEXP = "SELECT cwocAsal, 
        COUNT(*) AS jumlah_mutasi_out 
    FROM mutasi 
    WHERE 
        MONTH(tanggalMutasi) = '$selectedMonth'
        AND YEAR(tanggalMutasi) = '$selectedYear'
        AND status = 10 AND emno LIKE 'EXP%' AND cwocAsal = 'BOD'
    GROUP BY cwocAsal";



$queryUmur2 = "SELECT
    SUM(CASE WHEN sexe = 'L' AND TIMESTAMPDIFF(YEAR, birthday, '$endDate') < 18 THEN 1 ELSE 0 END) AS '<18 Pria',
    SUM(CASE WHEN sexe = 'P' AND TIMESTAMPDIFF(YEAR, birthday, '$endDate') < 18 THEN 1 ELSE 0 END) AS '<18 Perempuan',
    SUM(CASE WHEN sexe = 'L' AND TIMESTAMPDIFF(YEAR, birthday, '$endDate') < 26 THEN 1 ELSE 0 END) AS '<26 Pria',
    SUM(CASE WHEN sexe = 'P' AND TIMESTAMPDIFF(YEAR, birthday, '$endDate') < 26 THEN 1 ELSE 0 END) AS '<26 Perempuan',
    SUM(CASE WHEN sexe = 'L' AND TIMESTAMPDIFF(YEAR, birthday, '$endDate') BETWEEN 18 AND 25 THEN 1 ELSE 0 END) AS '18-25 Pria',
    SUM(CASE WHEN sexe = 'P' AND TIMESTAMPDIFF(YEAR, birthday, '$endDate') BETWEEN 18 AND 25 THEN 1 ELSE 0 END) AS '18-25 Perempuan',
    SUM(CASE WHEN sexe = 'L' AND TIMESTAMPDIFF(YEAR, birthday, '$endDate') BETWEEN 26 AND 30 THEN 1 ELSE 0 END) AS '26-30 Pria',
    SUM(CASE WHEN sexe = 'P' AND TIMESTAMPDIFF(YEAR, birthday, '$endDate') BETWEEN 26 AND 30 THEN 1 ELSE 0 END) AS '26-30 Perempuan',
    SUM(CASE WHEN sexe = 'L' AND TIMESTAMPDIFF(YEAR, birthday, '$endDate') BETWEEN 31 AND 35 THEN 1 ELSE 0 END) AS '31-35 Pria',
    SUM(CASE WHEN sexe = 'P' AND TIMESTAMPDIFF(YEAR, birthday, '$endDate') BETWEEN 31 AND 35 THEN 1 ELSE 0 END) AS '31-35 Perempuan',
    SUM(CASE WHEN sexe = 'L' AND TIMESTAMPDIFF(YEAR, birthday, '$endDate') BETWEEN 36 AND 40 THEN 1 ELSE 0 END) AS '36-40 Pria',
    SUM(CASE WHEN sexe = 'P' AND TIMESTAMPDIFF(YEAR, birthday, '$endDate') BETWEEN 36 AND 40 THEN 1 ELSE 0 END) AS '36-40 Perempuan',
    SUM(CASE WHEN sexe = 'L' AND TIMESTAMPDIFF(YEAR, birthday, '$endDate') BETWEEN 41 AND 45 THEN 1 ELSE 0 END) AS '41-45 Pria',
    SUM(CASE WHEN sexe = 'P' AND TIMESTAMPDIFF(YEAR, birthday, '$endDate') BETWEEN 41 AND 45 THEN 1 ELSE 0 END) AS '41-45 Perempuan',
    SUM(CASE WHEN sexe = 'L' AND TIMESTAMPDIFF(YEAR, birthday, '$endDate') BETWEEN 46 AND 50 THEN 1 ELSE 0 END) AS '46-50 Pria',
    SUM(CASE WHEN sexe = 'P' AND TIMESTAMPDIFF(YEAR, birthday, '$endDate') BETWEEN 46 AND 50 THEN 1 ELSE 0 END) AS '46-50 Perempuan',
    SUM(CASE WHEN sexe = 'L' AND TIMESTAMPDIFF(YEAR, birthday, '$endDate') BETWEEN 51 AND 55 THEN 1 ELSE 0 END) AS '51-55 Pria',
    SUM(CASE WHEN sexe = 'P' AND TIMESTAMPDIFF(YEAR, birthday, '$endDate') BETWEEN 51 AND 55 THEN 1 ELSE 0 END) AS '51-55 Perempuan',
    SUM(CASE WHEN sexe = 'L' AND TIMESTAMPDIFF(YEAR, birthday, '$endDate') > 55 THEN 1 ELSE 0 END) AS '>55 Pria',
    SUM(CASE WHEN sexe = 'P' AND TIMESTAMPDIFF(YEAR, birthday, '$endDate') > 55 THEN 1 ELSE 0 END) AS '>55 Perempuan'
FROM
    karyawan
WHERE
($defaultDateCondition OR (resdate > '$endDate'))
    AND joindate <= '$endDate'";

// Execute query
$resultUmur2 = mysqli_query($koneksi, $queryUmur2);

// Check if the query was successful
if (!$resultUmur2) {
    die("Query gagal: " . mysqli_error($koneksi));
}

// Fetch the result as an associative array
$row = mysqli_fetch_assoc($resultUmur2);

// Save query results to PHP variables
$pria18 = $row['<18 Pria'];
$perempuan18 = $row['<18 Perempuan'];
$pria26 = $row['<26 Pria'];
$perempuan26 = $row['<26 Perempuan'];
$pria18_25 = $row['18-25 Pria'];
$perempuan18_25 = $row['18-25 Perempuan'];
$pria26_30 = $row['26-30 Pria'];
$perempuan26_30 = $row['26-30 Perempuan'];
$pria31_35 = $row['31-35 Pria'];
$perempuan31_35 = $row['31-35 Perempuan'];
$pria36_40 = $row['36-40 Pria'];
$perempuan36_40 = $row['36-40 Perempuan'];
$pria41_45 = $row['41-45 Pria'];
$perempuan41_45 = $row['41-45 Perempuan'];
$pria46_50 = $row['46-50 Pria'];
$perempuan46_50 = $row['46-50 Perempuan'];
$pria51_55 = $row['51-55 Pria'];
$perempuan51_55 = $row['51-55 Perempuan'];
$pria55 = $row['>55 Pria'];
$perempuan55 = $row['>55 Perempuan'];


$querygolStatus = "SELECT 
    SUM(CASE WHEN karyawan.gol = 0 AND (karyawan.emno LIKE '0%' OR (karyawan.emno REGEXP '^[0-9]+$') OR karyawan.emno LIKE 'P%') AND karyawan.sexe = 'L' THEN 1 ELSE 0 END) AS Tetap0L,
    SUM(CASE WHEN karyawan.gol = 0 AND (karyawan.emno LIKE '0%' OR (karyawan.emno REGEXP '^[0-9]+$') OR karyawan.emno LIKE 'P%') AND karyawan.sexe = 'P' THEN 1 ELSE 0 END) AS Tetap0P,
    SUM(CASE WHEN karyawan.gol = 1 AND (karyawan.emno LIKE '0%' OR (karyawan.emno REGEXP '^[0-9]+$') OR karyawan.emno LIKE 'P%') AND karyawan.sexe = 'L' THEN 1 ELSE 0 END) AS Tetap1L,
    SUM(CASE WHEN karyawan.gol = 1 AND (karyawan.emno LIKE '0%' OR (karyawan.emno REGEXP '^[0-9]+$') OR karyawan.emno LIKE 'P%') AND karyawan.sexe = 'P' THEN 1 ELSE 0 END) AS Tetap1P,
    SUM(CASE WHEN karyawan.gol = 2 AND (karyawan.emno LIKE '0%' OR (karyawan.emno REGEXP '^[0-9]+$') OR karyawan.emno LIKE 'P%') AND karyawan.sexe = 'L' THEN 1 ELSE 0 END) AS Tetap2L,
    SUM(CASE WHEN karyawan.gol = 2 AND (karyawan.emno LIKE '0%' OR (karyawan.emno REGEXP '^[0-9]+$') OR karyawan.emno LIKE 'P%') AND karyawan.sexe = 'P' THEN 1 ELSE 0 END) AS Tetap2P,
    SUM(CASE WHEN karyawan.gol = 3 AND (karyawan.emno LIKE '0%' OR (karyawan.emno REGEXP '^[0-9]+$') OR karyawan.emno LIKE 'P%') AND karyawan.sexe = 'L' THEN 1 ELSE 0 END) AS Tetap3L,
    SUM(CASE WHEN karyawan.gol = 3 AND (karyawan.emno LIKE '0%' OR (karyawan.emno REGEXP '^[0-9]+$') OR karyawan.emno LIKE 'P%') AND karyawan.sexe = 'P' THEN 1 ELSE 0 END) AS Tetap3P,
    SUM(CASE WHEN karyawan.gol = 4 AND (karyawan.emno LIKE '0%' OR (karyawan.emno REGEXP '^[0-9]+$') OR karyawan.emno LIKE 'P%') AND karyawan.sexe = 'L' THEN 1 ELSE 0 END) AS Tetap4L,
    SUM(CASE WHEN karyawan.gol = 4 AND (karyawan.emno LIKE '0%' OR (karyawan.emno REGEXP '^[0-9]+$') OR karyawan.emno LIKE 'P%') AND karyawan.sexe = 'P' THEN 1 ELSE 0 END) AS Tetap4P,
    SUM(CASE WHEN karyawan.gol = 5 AND (karyawan.emno LIKE '0%' OR (karyawan.emno REGEXP '^[0-9]+$') OR karyawan.emno LIKE 'P%') AND karyawan.sexe = 'L' THEN 1 ELSE 0 END) AS Tetap5L,
    SUM(CASE WHEN karyawan.gol = 5 AND (karyawan.emno LIKE '0%' OR (karyawan.emno REGEXP '^[0-9]+$') OR karyawan.emno LIKE 'P%') AND karyawan.sexe = 'P' THEN 1 ELSE 0 END) AS Tetap5P,
    SUM(CASE WHEN karyawan.gol = 6 AND (karyawan.emno LIKE '0%' OR (karyawan.emno REGEXP '^[0-9]+$') OR karyawan.emno LIKE 'P%') AND karyawan.sexe = 'L' THEN 1 ELSE 0 END) AS Tetap6L,
    SUM(CASE WHEN karyawan.gol = 6 AND (karyawan.emno LIKE '0%' OR (karyawan.emno REGEXP '^[0-9]+$') OR karyawan.emno LIKE 'P%') AND karyawan.sexe = 'P' THEN 1 ELSE 0 END) AS Tetap6P,
    SUM(CASE WHEN karyawan.gol = 7 AND (karyawan.emno LIKE '0%' OR (karyawan.emno REGEXP '^[0-9]+$') OR karyawan.emno LIKE 'P%') AND karyawan.sexe = 'L' THEN 1 ELSE 0 END) AS Tetap7L,
    SUM(CASE WHEN karyawan.gol = 7 AND (karyawan.emno LIKE '0%' OR (karyawan.emno REGEXP '^[0-9]+$') OR karyawan.emno LIKE 'P%') AND karyawan.sexe = 'P' THEN 1 ELSE 0 END) AS Tetap7P,

    SUM(CASE WHEN karyawan.gol = 0 AND (karyawan.emno LIKE 'K%') AND karyawan.sexe = 'L' THEN 1 ELSE 0 END) AS Kontrak0L,
    SUM(CASE WHEN karyawan.gol = 0 AND (karyawan.emno LIKE 'K%') AND karyawan.sexe = 'P' THEN 1 ELSE 0 END) AS Kontrak0P,
    SUM(CASE WHEN karyawan.gol = 1 AND (karyawan.emno LIKE 'K%') AND karyawan.sexe = 'L' THEN 1 ELSE 0 END) AS Kontrak1L,
    SUM(CASE WHEN karyawan.gol = 1 AND (karyawan.emno LIKE 'K%') AND karyawan.sexe = 'P' THEN 1 ELSE 0 END) AS Kontrak1P,
    SUM(CASE WHEN karyawan.gol = 2 AND (karyawan.emno LIKE 'K%') AND karyawan.sexe = 'L' THEN 1 ELSE 0 END) AS Kontrak2L,
    SUM(CASE WHEN karyawan.gol = 2 AND (karyawan.emno LIKE 'K%') AND karyawan.sexe = 'P' THEN 1 ELSE 0 END) AS Kontrak2P,
    SUM(CASE WHEN karyawan.gol = 3 AND (karyawan.emno LIKE 'K%') AND karyawan.sexe = 'L' THEN 1 ELSE 0 END) AS Kontrak3L,
    SUM(CASE WHEN karyawan.gol = 3 AND (karyawan.emno LIKE 'K%') AND karyawan.sexe = 'P' THEN 1 ELSE 0 END) AS Kontrak3P,
    SUM(CASE WHEN karyawan.gol = 4 AND (karyawan.emno LIKE 'K%') AND karyawan.sexe = 'L' THEN 1 ELSE 0 END) AS Kontrak4L,
    SUM(CASE WHEN karyawan.gol = 4 AND (karyawan.emno LIKE 'K%') AND karyawan.sexe = 'P' THEN 1 ELSE 0 END) AS Kontrak4P,
    SUM(CASE WHEN karyawan.gol = 5 AND (karyawan.emno LIKE 'K%') AND karyawan.sexe = 'L' THEN 1 ELSE 0 END) AS Kontrak5L,
    SUM(CASE WHEN karyawan.gol = 5 AND (karyawan.emno LIKE 'K%') AND karyawan.sexe = 'P' THEN 1 ELSE 0 END) AS Kontrak5P,
    SUM(CASE WHEN karyawan.gol = 6 AND (karyawan.emno LIKE 'K%') AND karyawan.sexe = 'L' THEN 1 ELSE 0 END) AS Kontrak6L,
    SUM(CASE WHEN karyawan.gol = 6 AND (karyawan.emno LIKE 'K%') AND karyawan.sexe = 'P' THEN 1 ELSE 0 END) AS Kontrak6P,
    SUM(CASE WHEN karyawan.gol = 7 AND (karyawan.emno LIKE 'K%') AND karyawan.sexe = 'L' THEN 1 ELSE 0 END) AS Kontrak7L,
    SUM(CASE WHEN karyawan.gol = 7 AND (karyawan.emno LIKE 'K%') AND karyawan.sexe = 'P' THEN 1 ELSE 0 END) AS Kontrak7P,

    SUM(CASE WHEN karyawan.gol = 0 AND (karyawan.emno LIKE 'EXP%') AND karyawan.sexe = 'L' THEN 1 ELSE 0 END) AS Asing0L,
    SUM(CASE WHEN karyawan.gol = 0 AND (karyawan.emno LIKE 'EXP%') AND karyawan.sexe = 'P' THEN 1 ELSE 0 END) AS Asing0P,
    SUM(CASE WHEN karyawan.gol = 1 AND (karyawan.emno LIKE 'EXP%') AND karyawan.sexe = 'L' THEN 1 ELSE 0 END) AS Asing1L,
    SUM(CASE WHEN karyawan.gol = 1 AND (karyawan.emno LIKE 'EXP%') AND karyawan.sexe = 'P' THEN 1 ELSE 0 END) AS Asing1P,
    SUM(CASE WHEN karyawan.gol = 2 AND (karyawan.emno LIKE 'EXP%') AND karyawan.sexe = 'L' THEN 1 ELSE 0 END) AS Asing2L,
    SUM(CASE WHEN karyawan.gol = 2 AND (karyawan.emno LIKE 'EXP%') AND karyawan.sexe = 'P' THEN 1 ELSE 0 END) AS Asing2P,
    SUM(CASE WHEN karyawan.gol = 3 AND (karyawan.emno LIKE 'EXP%') AND karyawan.sexe = 'L' THEN 1 ELSE 0 END) AS Asing3L,
    SUM(CASE WHEN karyawan.gol = 3 AND (karyawan.emno LIKE 'EXP%') AND karyawan.sexe = 'P' THEN 1 ELSE 0 END) AS Asing3P,
    SUM(CASE WHEN karyawan.gol = 4 AND (karyawan.emno LIKE 'EXP%') AND karyawan.sexe = 'L' THEN 1 ELSE 0 END) AS Asing4L,
    SUM(CASE WHEN karyawan.gol = 4 AND (karyawan.emno LIKE 'EXP%') AND karyawan.sexe = 'P' THEN 1 ELSE 0 END) AS Asing4P,
    SUM(CASE WHEN karyawan.gol = 5 AND (karyawan.emno LIKE 'EXP%') AND karyawan.sexe = 'L' THEN 1 ELSE 0 END) AS Asing5L,
    SUM(CASE WHEN karyawan.gol = 5 AND (karyawan.emno LIKE 'EXP%') AND karyawan.sexe = 'P' THEN 1 ELSE 0 END) AS Asing5P,
    SUM(CASE WHEN karyawan.gol = 6 AND (karyawan.emno LIKE 'EXP%') AND karyawan.sexe = 'L' THEN 1 ELSE 0 END) AS Asing6L,
    SUM(CASE WHEN karyawan.gol = 6 AND (karyawan.emno LIKE 'EXP%') AND karyawan.sexe = 'P' THEN 1 ELSE 0 END) AS Asing6P,
    SUM(CASE WHEN karyawan.gol = 7 AND (karyawan.emno LIKE 'EXP%') AND karyawan.sexe = 'L' THEN 1 ELSE 0 END) AS Asing7L,
    SUM(CASE WHEN karyawan.gol = 7 AND (karyawan.emno LIKE 'EXP%') AND karyawan.sexe = 'P' THEN 1 ELSE 0 END) AS Asing7P
    FROM 
    karyawan
WHERE
($defaultDateCondition OR (resdate > '$endDate'))
    AND joindate <= '$endDate'";

// Execute query
$resultgolStatus = mysqli_query($koneksi, $querygolStatus);

// Check if the query was successful
if (!$resultgolStatus) {
    die("Query gagal: " . mysqli_error($koneksi));
}

// Fetch the result as an associative array
$row = mysqli_fetch_assoc($resultgolStatus);

// Save query results to PHP variables
$Tetap0L = $row['Tetap0L'];
$Tetap0P = $row['Tetap0P'];
$Tetap1L = $row['Tetap1L'];
$Tetap1P = $row['Tetap1P'];
$Tetap2L = $row['Tetap2L'];
$Tetap2P = $row['Tetap2P'];
$Tetap3L = $row['Tetap3L'];
$Tetap3P = $row['Tetap3P'];
$Tetap4L = $row['Tetap4L'];
$Tetap4P = $row['Tetap4P'];
$Tetap5L = $row['Tetap5L'];
$Tetap5P = $row['Tetap5P'];
$Tetap6L = $row['Tetap6L'];
$Tetap6P = $row['Tetap6P'];
$Tetap7L = $row['Tetap7L'];
$Tetap7P = $row['Tetap7P'];
$Kontrak0L = $row['Kontrak0L'];
$Kontrak0P = $row['Kontrak0P'];
$Kontrak1L = $row['Kontrak1L'];
$Kontrak1P = $row['Kontrak1P'];
$Kontrak2L = $row['Kontrak2L'];
$Kontrak2P = $row['Kontrak2P'];
$Kontrak3L = $row['Kontrak3L'];
$Kontrak3P = $row['Kontrak3P'];
$Kontrak4L = $row['Kontrak4L'];
$Kontrak4P = $row['Kontrak4P'];
$Kontrak5L = $row['Kontrak5L'];
$Kontrak5P = $row['Kontrak5P'];
$Kontrak6L = $row['Kontrak6L'];
$Kontrak6P = $row['Kontrak6P'];
$Kontrak7L = $row['Kontrak7L'];
$Kontrak7P = $row['Kontrak7P'];
$Asing0L = $row['Asing0L'];
$Asing0P = $row['Asing0P'];
$Asing1L = $row['Asing1L'];
$Asing1P = $row['Asing1P'];
$Asing2L = $row['Asing2L'];
$Asing2P = $row['Asing2P'];
$Asing3L = $row['Asing3L'];
$Asing3P = $row['Asing3P'];
$Asing4L = $row['Asing4L'];
$Asing4P = $row['Asing4P'];
$Asing5L = $row['Asing5L'];
$Asing5P = $row['Asing5P'];
$Asing6L = $row['Asing6L'];
$Asing6P = $row['Asing6P'];
$Asing7L = $row['Asing7L'];
$Asing7P = $row['Asing7P'];

$queryJabatan = "SELECT 
    SUM(CASE WHEN karyawan.gol >= 5 THEN 1 ELSE 0 END) AS Manager,
    SUM(CASE WHEN karyawan.gol IN ('1', '2', '3', '4') THEN 1 ELSE 0 END) AS NonManager
    FROM 
    karyawan
WHERE
($defaultDateCondition OR (resdate > '$endDate'))
    AND joindate <= '$endDate'";

$resultJabatan = mysqli_query($koneksi, $queryJabatan);

// Memeriksa apakah query berhasil dieksekusi
if (!$resultJabatan) {
    die("Query gagal: " . mysqli_error($koneksi));
}

// Simpan hasil query ke dalam variabel $result
// Misalnya:
$row = mysqli_fetch_assoc($resultJabatan);

// Simpan hasil query ke dalam variabel PHP
$Manager = $row['Manager'];
$NonManager = $row['NonManager'];

$queryDLIDLSGA = "SELECT 
    SUM(CASE WHEN sexe = 'L' AND gol BETWEEN 0 AND 3 AND cwoc IN ('PRODUCTION 1', 'PRODUCTION 2', 'PRODUCTION 3', 'PRODUCTION 4', 'PRODUCTION 5') THEN 1 ELSE 0 END) AS DLPria,
    SUM(CASE WHEN sexe = 'P' AND gol BETWEEN 0 AND 3 AND cwoc IN ('PRODUCTION 1', 'PRODUCTION 2', 'PRODUCTION 3', 'PRODUCTION 4', 'PRODUCTION 5') THEN 1 ELSE 0 END) AS DLPerempuan,
    SUM(CASE WHEN sexe = 'L' AND gol BETWEEN 4 AND 7 AND cwoc IN ('PRODUCTION 1', 'PRODUCTION 2', 'PRODUCTION 3', 'PRODUCTION 4', 'PRODUCTION 5') THEN 1 ELSE 0 END) AS IDL1Pria,
    SUM(CASE WHEN sexe = 'P' AND gol BETWEEN 4 AND 7 AND cwoc IN ('PRODUCTION 1', 'PRODUCTION 2', 'PRODUCTION 3', 'PRODUCTION 4', 'PRODUCTION 5') THEN 1 ELSE 0 END) AS IDL1Perempuan,
    SUM(CASE WHEN sexe = 'L' AND cwoc IN ('PRODUCTION SYSTEM' , 'PPC', 'PCE', 'PE 2W', 'PE 4W', 'PDE 2W', 'PDE 4W', 'QA', 'CQE 2W', 'CQE 4W', 'GENERAL PURCHASE', 'PROCUREMENT', 'VENDOR DEVELOPMENT', 'WAREHOUSE') THEN 1 ELSE 0 END) AS IDL2Pria,
    SUM(CASE WHEN sexe = 'P' AND cwoc IN ('PRODUCTION SYSTEM' , 'PPC', 'PCE', 'PE 2W', 'PE 4W', 'PDE 2W', 'PDE 4W', 'QA', 'CQE 2W', 'CQE 4W', 'GENERAL PURCHASE', 'PROCUREMENT', 'VENDOR DEVELOPMENT', 'WAREHOUSE') THEN 1 ELSE 0 END) AS IDL2Perempuan,
    SUM(CASE WHEN sexe = 'L' AND cwoc IN ('FINANCE ACCOUNTING' , 'PLANNING BUDGETING', 'HRD IR', 'GA', 'EHS', 'COMITTEE QCC - SS', 'PDCA CPC', 'MARKETING', 'MIS', 'BOD') THEN 1 ELSE 0 END) AS SGAPria,
    SUM(CASE WHEN sexe = 'P' AND cwoc IN ('FINANCE ACCOUNTING' , 'PLANNING BUDGETING', 'HRD IR', 'GA', 'EHS', 'COMITTEE QCC - SS', 'PDCA CPC', 'MARKETING', 'MIS', 'BOD') THEN 1 ELSE 0 END) AS SGAPerempuan,

    SUM(CASE WHEN (emno LIKE '0%' OR emno REGEXP '^[0-9]+$' OR emno LIKE 'EXP%' OR emno LIKE 'P%') AND sexe = 'L' AND gol BETWEEN 0 AND 3 AND cwoc IN ('PRODUCTION 1', 'PRODUCTION 2', 'PRODUCTION 3', 'PRODUCTION 4', 'PRODUCTION 5') THEN 1 ELSE 0 END) AS DLPriaTetap,
    SUM(CASE WHEN (emno LIKE '0%' OR emno REGEXP '^[0-9]+$' OR emno LIKE 'EXP%' OR emno LIKE 'P%') AND sexe = 'P' AND gol BETWEEN 0 AND 3 AND cwoc IN ('PRODUCTION 1', 'PRODUCTION 2', 'PRODUCTION 3', 'PRODUCTION 4', 'PRODUCTION 5') THEN 1 ELSE 0 END) AS DLPerempuanTetap,

    SUM(CASE WHEN (emno LIKE 'K%') AND sexe = 'L' AND gol BETWEEN 0 AND 3 AND cwoc IN ('PRODUCTION 1', 'PRODUCTION 2', 'PRODUCTION 3', 'PRODUCTION 4', 'PRODUCTION 5') THEN 1 ELSE 0 END) AS DLPriaKontrak,
    SUM(CASE WHEN (emno LIKE '0%') AND sexe = 'P' AND gol BETWEEN 0 AND 3 AND cwoc IN ('PRODUCTION 1', 'PRODUCTION 2', 'PRODUCTION 3', 'PRODUCTION 4', 'PRODUCTION 5') THEN 1 ELSE 0 END) AS DLPerempuanKontrak,

    SUM(CASE WHEN (emno LIKE '0%' OR emno REGEXP '^[0-9]+$' OR emno LIKE 'EXP%' OR emno LIKE 'P%') AND sexe = 'L' AND gol BETWEEN 4 AND 7 AND cwoc IN ('PRODUCTION 1', 'PRODUCTION 2', 'PRODUCTION 3', 'PRODUCTION 4', 'PRODUCTION 5') THEN 1 ELSE 0 END) AS IDL1PriaTetap,
    SUM(CASE WHEN (emno LIKE '0%' OR emno REGEXP '^[0-9]+$' OR emno LIKE 'EXP%' OR emno LIKE 'P%') AND sexe = 'P' AND gol BETWEEN 4 AND 7 AND cwoc IN ('PRODUCTION 1', 'PRODUCTION 2', 'PRODUCTION 3', 'PRODUCTION 4', 'PRODUCTION 5') THEN 1 ELSE 0 END) AS IDL1PerempuanTetap,

    SUM(CASE WHEN (emno LIKE 'K%') AND sexe = 'L' AND gol BETWEEN 4 AND 7 AND cwoc IN ('PRODUCTION 1', 'PRODUCTION 2', 'PRODUCTION 3', 'PRODUCTION 4', 'PRODUCTION 5') THEN 1 ELSE 0 END) AS IDL1PriaKontrak,
    SUM(CASE WHEN (emno LIKE '0%') AND sexe = 'P' AND gol BETWEEN 4 AND 7 AND cwoc IN ('PRODUCTION 1', 'PRODUCTION 2', 'PRODUCTION 3', 'PRODUCTION 4', 'PRODUCTION 5') THEN 1 ELSE 0 END) AS IDL1PerempuanKontrak,

    SUM(CASE WHEN (emno LIKE '0%' OR emno REGEXP '^[0-9]+$' OR emno LIKE 'EXP%' OR emno LIKE 'P%') AND sexe = 'L' AND cwoc IN ('PRODUCTION SYSTEM' , 'PPC', 'PCE', 'PE 2W', 'PE 4W', 'PDE 2W', 'PDE 4W', 'QA', 'CQE 2W', 'CQE 4W', 'GENERAL PURCHASE', 'PROCUREMENT', 'VENDOR DEVELOPMENT', 'WAREHOUSE') THEN 1 ELSE 0 END) AS IDL2PriaTetap,
    SUM(CASE WHEN (emno LIKE '0%' OR emno REGEXP '^[0-9]+$' OR emno LIKE 'EXP%' OR emno LIKE 'P%') AND sexe = 'P' AND cwoc IN ('PRODUCTION SYSTEM' , 'PPC', 'PCE', 'PE 2W', 'PE 4W', 'PDE 2W', 'PDE 4W', 'QA', 'CQE 2W', 'CQE 4W', 'GENERAL PURCHASE', 'PROCUREMENT', 'VENDOR DEVELOPMENT', 'WAREHOUSE') THEN 1 ELSE 0 END) AS IDL2PerempuanTetap,

    SUM(CASE WHEN (emno LIKE 'K%') AND sexe = 'L' AND cwoc IN ('PRODUCTION SYSTEM' , 'PPC', 'PCE', 'PE 2W', 'PE 4W', 'PDE 2W', 'PDE 4W', 'QA', 'CQE 2W', 'CQE 4W', 'GENERAL PURCHASE', 'PROCUREMENT', 'VENDOR DEVELOPMENT', 'WAREHOUSE') THEN 1 ELSE 0 END) AS IDL2PriaKontrak,
    SUM(CASE WHEN (emno LIKE 'K%') AND sexe = 'P' AND cwoc IN ('PRODUCTION SYSTEM' , 'PPC', 'PCE', 'PE 2W', 'PE 4W', 'PDE 2W', 'PDE 4W', 'QA', 'CQE 2W', 'CQE 4W', 'GENERAL PURCHASE', 'PROCUREMENT', 'VENDOR DEVELOPMENT', 'WAREHOUSE') THEN 1 ELSE 0 END) AS IDL2PerempuanKontrak,

    SUM(CASE WHEN (emno LIKE '0%' OR emno REGEXP '^[0-9]+$' OR emno LIKE 'EXP%' OR emno LIKE 'P%') AND sexe = 'L' AND cwoc IN ('FINANCE ACCOUNTING' , 'PLANNING BUDGETING', 'HRD IR', 'GA', 'EHS', 'COMITTEE QCC - SS', 'PDCA CPC', 'MARKETING', 'MIS', 'BOD') THEN 1 ELSE 0 END) AS SGAPriaTetap,
    SUM(CASE WHEN (emno LIKE '0%' OR emno REGEXP '^[0-9]+$' OR emno LIKE 'EXP%' OR emno LIKE 'P%') AND sexe = 'P' AND cwoc IN ('FINANCE ACCOUNTING' , 'PLANNING BUDGETING', 'HRD IR', 'GA', 'EHS', 'COMITTEE QCC - SS', 'PDCA CPC', 'MARKETING', 'MIS', 'BOD') THEN 1 ELSE 0 END) AS SGAPerempuanTetap,

    SUM(CASE WHEN (emno LIKE 'K%') AND sexe = 'L' AND cwoc IN ('FINANCE ACCOUNTING' , 'PLANNING BUDGETING', 'HRD IR', 'GA', 'EHS', 'COMITTEE QCC - SS', 'PDCA CPC', 'MARKETING', 'MIS', 'BOD') THEN 1 ELSE 0 END) AS SGAPriaKontrak,
    SUM(CASE WHEN (emno LIKE 'K%') AND sexe = 'P' AND cwoc IN ('FINANCE ACCOUNTING' , 'PLANNING BUDGETING', 'HRD IR', 'GA', 'EHS', 'COMITTEE QCC - SS', 'PDCA CPC', 'MARKETING', 'MIS', 'BOD') THEN 1 ELSE 0 END) AS SGAPerempuanKontrak
    
    
    
    FROM 
    karyawan
WHERE
($defaultDateCondition OR (resdate > '$endDate'))
    AND joindate <= '$endDate'";

$resultDLIDLSGA = mysqli_query($koneksi, $queryDLIDLSGA);

// Memeriksa apakah query berhasil dieksekusi
if (!$resultDLIDLSGA) {
    die("Query gagal: " . mysqli_error($koneksi));
}

// Simpan hasil query ke dalam variabel $result
// Misalnya:
$row = mysqli_fetch_assoc($resultDLIDLSGA);

// Simpan hasil query ke dalam variabel PHP
$DLPria = $row['DLPria'];
$DLPerempuan = $row['DLPerempuan'];
$IDL1Pria = $row['IDL1Pria'];
$IDL1Perempuan = $row['IDL1Perempuan'];
$IDL2Pria = $row['IDL2Pria'];
$IDL2Perempuan = $row['IDL2Perempuan'];
$SGAPria = $row['SGAPria'];
$SGAPerempuan = $row['SGAPerempuan'];

$DLPriaTetap = $row['DLPriaTetap'];
$DLPriaKontrak = $row['DLPriaKontrak'];
$DLPerempuanTetap = $row['DLPerempuanTetap'];
$DLPerempuanKontrak = $row['DLPerempuanKontrak'];
$IDL1PriaTetap = $row['IDL1PriaTetap'];
$IDL1PriaKontrak = $row['IDL1PriaKontrak'];
$IDL1PerempuanTetap = $row['IDL1PerempuanTetap'];
$IDL1PerempuanKontrak = $row['IDL1PerempuanKontrak'];
$IDL2PriaTetap = $row['IDL2PriaTetap'];
$IDL2PriaKontrak = $row['IDL2PriaKontrak'];
$IDL2PerempuanTetap = $row['IDL2PerempuanTetap'];
$IDL2PerempuanKontrak = $row['IDL2PerempuanKontrak'];
$SGAPriaTetap = $row['SGAPriaTetap'];
$SGAPriaKontrak = $row['SGAPriaKontrak'];
$SGAPerempuanTetap = $row['SGAPerempuanTetap'];
$SGAPerempuanKontrak = $row['SGAPerempuanKontrak'];


?>