<?php
include __DIR__ . '/koneksi.php';

$currentMonth = date('m');
$currentYear = date('Y');

$selectedMonth = isset($_GET['bulan']) ? $_GET['bulan'] : $currentMonth;
$selectedYear = isset($_GET['tahun']) ? $_GET['tahun'] : $currentYear;

// Mendapatkan tanggal terakhir dari bulan yang dipilih
$endDate = date('Y-m-d', strtotime("last day of $selectedYear-$selectedMonth"));

// Mengurangi satu hari dari tanggal terakhir bulan yang dipilih
$endDate = date('Y-m-d', strtotime($endDate . ' -1 day'));


$selectedMonth = isset($_GET['bulan']) ? $_GET['bulan'] : $currentMonth;
$selectedYear = isset($_GET['tahun']) ? $_GET['tahun'] : $currentYear;

// Prepare the condition for default date and null resdate
$defaultDateCondition = "(resdate IS NULL)";

$queryStatusOut = "SELECT 
                    SUM(CASE WHEN sexe = 'L' AND gol = '0' AND reason = 'Habis Kontrak' THEN 1 ELSE 0 END) AS Gol0_Kontrak_Habis_Pria,
                    SUM(CASE WHEN sexe = 'P' AND gol = '0' AND reason = 'Habis Kontrak' THEN 1 ELSE 0 END) AS Gol0_Kontrak_Habis_Perempuan,
                    SUM(CASE WHEN sexe = 'L' AND gol = '0' AND reason = 'PHK' THEN 1 ELSE 0 END) AS Gol0_Dikeluarkan_Pria,
                    SUM(CASE WHEN sexe = 'P' AND gol = '0' AND reason = 'PHK' THEN 1 ELSE 0 END) AS Gol0_Dikeluarkan_Perempuan,
                    SUM(CASE WHEN sexe = 'L' AND gol = '0' AND reason = 'Resign' THEN 1 ELSE 0 END) AS Gol0_Kehendak_Sendiri_Pria,
                    SUM(CASE WHEN sexe = 'P' AND gol = '0' AND reason = 'Resign' THEN 1 ELSE 0 END) AS Gol0_Kehendak_Sendiri_Perempuan,
                    SUM(CASE WHEN sexe = 'L' AND gol = '0' AND reason = 'Pensiun' THEN 1 ELSE 0 END) AS Gol0_Pensiun_Pria,
                    SUM(CASE WHEN sexe = 'P' AND gol = '0' AND reason = 'Pensiun' THEN 1 ELSE 0 END) AS Gol0_Pensiun_Perempuan,
                    SUM(CASE WHEN sexe = 'L' AND gol = '0' AND reason = 'Meninggal' THEN 1 ELSE 0 END) AS Gol0_Meninggal_Pria,
                    SUM(CASE WHEN sexe = 'P' AND gol = '0' AND reason = 'Meninggal' THEN 1 ELSE 0 END) AS Gol0_Meninggal_Perempuan,
                    SUM(CASE WHEN sexe = 'L' AND gol = '0' AND reason = 'Mutasi' THEN 1 ELSE 0 END) AS Gol0_Mutasi_Pria,
                    SUM(CASE WHEN sexe = 'P' AND gol = '0' AND reason = 'Mutasi' THEN 1 ELSE 0 END) AS Gol0_Mutasi_Perempuan,

                    SUM(CASE WHEN sexe = 'L' AND gol = '1' AND reason = 'Habis Kontrak' THEN 1 ELSE 0 END) AS Gol1_Kontrak_Habis_Pria,
                    SUM(CASE WHEN sexe = 'P' AND gol = '1' AND reason = 'Habis Kontrak' THEN 1 ELSE 0 END) AS Gol1_Kontrak_Habis_Perempuan,
                    SUM(CASE WHEN sexe = 'L' AND gol = '1' AND reason = 'PHK' THEN 1 ELSE 0 END) AS Gol1_Dikeluarkan_Pria,
                    SUM(CASE WHEN sexe = 'p' AND gol = '1' AND reason = 'PHK' THEN 1 ELSE 0 END) AS Gol1_Dikeluarkan_Perempuan,
                    SUM(CASE WHEN sexe = 'L' AND gol = '1' AND reason = 'Resign' THEN 1 ELSE 0 END) AS Gol1_Kehendak_Sendiri_Pria,
                    SUM(CASE WHEN sexe = 'P' AND gol = '1' AND reason = 'Resign' THEN 1 ELSE 0 END) AS Gol1_Kehendak_Sendiri_Perempuan,
                    SUM(CASE WHEN sexe = 'L' AND gol = '1' AND reason = 'Pensiun' THEN 1 ELSE 0 END) AS Gol1_Pensiun_Pria,
                    SUM(CASE WHEN sexe = 'P' AND gol = '1' AND reason = 'Pensiun' THEN 1 ELSE 0 END) AS Gol1_Pensiun_Perempuan,
                    SUM(CASE WHEN sexe = 'L' AND gol = '1' AND reason = 'Meninggal' THEN 1 ELSE 0 END) AS Gol1_Meninggal_Pria,
                    SUM(CASE WHEN sexe = 'P' AND gol = '1' AND reason = 'Meninggal' THEN 1 ELSE 0 END) AS Gol1_Meninggal_Perempuan,

                    SUM(CASE WHEN sexe = 'L' AND gol = '2' AND reason = 'Habis Kontrak' THEN 1 ELSE 0 END) AS Gol2_Kontrak_Habis_Pria,
                    SUM(CASE WHEN sexe = 'P' AND gol = '2' AND reason = 'Habis Kontrak' THEN 1 ELSE 0 END) AS Gol2_Kontrak_Habis_Perempuan,
                    SUM(CASE WHEN sexe = 'L' AND gol = '2' AND reason = 'PHK' THEN 1 ELSE 0 END) AS Gol2_Dikeluarkan_Pria,
                    SUM(CASE WHEN sexe = 'p' AND gol = '2' AND reason = 'PHK' THEN 1 ELSE 0 END) AS Gol2_Dikeluarkan_Perempuan,
                    SUM(CASE WHEN sexe = 'L' AND gol = '2' AND reason = 'Resign' THEN 1 ELSE 0 END) AS Gol2_Kehendak_Sendiri_Pria,
                    SUM(CASE WHEN sexe = 'P' AND gol = '2' AND reason = 'Resign' THEN 1 ELSE 0 END) AS Gol2_Kehendak_Sendiri_Perempuan,
                    SUM(CASE WHEN sexe = 'L' AND gol = '2' AND reason = 'Pensiun' THEN 1 ELSE 0 END) AS Gol2_Pensiun_Pria,
                    SUM(CASE WHEN sexe = 'P' AND gol = '2' AND reason = 'Pensiun' THEN 1 ELSE 0 END) AS Gol2_Pensiun_Perempuan,
                    SUM(CASE WHEN sexe = 'L' AND gol = '2' AND reason = 'Meninggal' THEN 1 ELSE 0 END) AS Gol2_Meninggal_Pria,
                    SUM(CASE WHEN sexe = 'P' AND gol = '2' AND reason = 'Meninggal' THEN 1 ELSE 0 END) AS Gol2_Meninggal_Perempuan,

                    SUM(CASE WHEN sexe = 'L' AND gol = '3' AND reason = 'Habis Kontrak' THEN 1 ELSE 0 END) AS Gol3_Kontrak_Habis_Pria,
                    SUM(CASE WHEN sexe = 'P' AND gol = '3' AND reason = 'Habis Kontrak' THEN 1 ELSE 0 END) AS Gol3_Kontrak_Habis_Perempuan,
                    SUM(CASE WHEN sexe = 'L' AND gol = '3' AND reason = 'PHK' THEN 1 ELSE 0 END) AS Gol3_Dikeluarkan_Pria,
                    SUM(CASE WHEN sexe = 'p' AND gol = '3' AND reason = 'PHK' THEN 1 ELSE 0 END) AS Gol3_Dikeluarkan_Perempuan,
                    SUM(CASE WHEN sexe = 'L' AND gol = '3' AND reason = 'Resign' THEN 1 ELSE 0 END) AS Gol3_Kehendak_Sendiri_Pria,
                    SUM(CASE WHEN sexe = 'P' AND gol = '3' AND reason = 'Resign' THEN 1 ELSE 0 END) AS Gol3_Kehendak_Sendiri_Perempuan,
                    SUM(CASE WHEN sexe = 'L' AND gol = '3' AND reason = 'Pensiun' THEN 1 ELSE 0 END) AS Gol3_Pensiun_Pria,
                    SUM(CASE WHEN sexe = 'P' AND gol = '3' AND reason = 'Pensiun' THEN 1 ELSE 0 END) AS Gol3_Pensiun_Perempuan,
                    SUM(CASE WHEN sexe = 'L' AND gol = '3' AND reason = 'Meninggal' THEN 1 ELSE 0 END) AS Gol3_Meninggal_Pria,
                    SUM(CASE WHEN sexe = 'P' AND gol = '3' AND reason = 'Meninggal' THEN 1 ELSE 0 END) AS Gol3_Meninggal_Perempuan,

                    SUM(CASE WHEN sexe = 'L' AND gol = '4' AND reason = 'Habis Kontrak' THEN 1 ELSE 0 END) AS Gol4_Kontrak_Habis_Pria,
                    SUM(CASE WHEN sexe = 'P' AND gol = '4' AND reason = 'Habis Kontrak' THEN 1 ELSE 0 END) AS Gol4_Kontrak_Habis_Perempuan,
                    SUM(CASE WHEN sexe = 'L' AND gol = '4' AND reason = 'PHK' THEN 1 ELSE 0 END) AS Gol4_Dikeluarkan_Pria,
                    SUM(CASE WHEN sexe = 'p' AND gol = '4' AND reason = 'PHK' THEN 1 ELSE 0 END) AS Gol4_Dikeluarkan_Perempuan,
                    SUM(CASE WHEN sexe = 'L' AND gol = '4' AND reason = 'Resign' THEN 1 ELSE 0 END) AS Gol4_Kehendak_Sendiri_Pria,
                    SUM(CASE WHEN sexe = 'P' AND gol = '4' AND reason = 'Resign' THEN 1 ELSE 0 END) AS Gol4_Kehendak_Sendiri_Perempuan,
                    SUM(CASE WHEN sexe = 'L' AND gol = '4' AND reason = 'Pensiun' THEN 1 ELSE 0 END) AS Gol4_Pensiun_Pria,
                    SUM(CASE WHEN sexe = 'P' AND gol = '4' AND reason = 'Pensiun' THEN 1 ELSE 0 END) AS Gol4_Pensiun_Perempuan,
                    SUM(CASE WHEN sexe = 'L' AND gol = '4' AND reason = 'Meninggal' THEN 1 ELSE 0 END) AS Gol4_Meninggal_Pria,
                    SUM(CASE WHEN sexe = 'P' AND gol = '4' AND reason = 'Meninggal' THEN 1 ELSE 0 END) AS Gol4_Meninggal_Perempuan,

                    SUM(CASE WHEN sexe = 'L' AND gol = '5' AND reason = 'Habis Kontrak' THEN 1 ELSE 0 END) AS Gol5_Kontrak_Habis_Pria,
                    SUM(CASE WHEN sexe = 'P' AND gol = '5' AND reason = 'Habis Kontrak' THEN 1 ELSE 0 END) AS Gol5_Kontrak_Habis_Perempuan,
                    SUM(CASE WHEN sexe = 'L' AND gol = '5' AND reason = 'PHK' THEN 1 ELSE 0 END) AS Gol5_Dikeluarkan_Pria,
                    SUM(CASE WHEN sexe = 'p' AND gol = '5' AND reason = 'PHK' THEN 1 ELSE 0 END) AS Gol5_Dikeluarkan_Perempuan,
                    SUM(CASE WHEN sexe = 'L' AND gol = '5' AND reason = 'Resign' THEN 1 ELSE 0 END) AS Gol5_Kehendak_Sendiri_Pria,
                    SUM(CASE WHEN sexe = 'P' AND gol = '5' AND reason = 'Resign' THEN 1 ELSE 0 END) AS Gol5_Kehendak_Sendiri_Perempuan,
                    SUM(CASE WHEN sexe = 'L' AND gol = '5' AND reason = 'Pensiun' THEN 1 ELSE 0 END) AS Gol5_Pensiun_Pria,
                    SUM(CASE WHEN sexe = 'P' AND gol = '5' AND reason = 'Pensiun' THEN 1 ELSE 0 END) AS Gol5_Pensiun_Perempuan,
                    SUM(CASE WHEN sexe = 'L' AND gol = '5' AND reason = 'Meninggal' THEN 1 ELSE 0 END) AS Gol5_Meninggal_Pria,
                    SUM(CASE WHEN sexe = 'P' AND gol = '5' AND reason = 'Meninggal' THEN 1 ELSE 0 END) AS Gol5_Meninggal_Perempuan,

                    SUM(CASE WHEN sexe = 'L' AND gol = '6' AND reason = 'Habis Kontrak' THEN 1 ELSE 0 END) AS Gol6_Kontrak_Habis_Pria,
                    SUM(CASE WHEN sexe = 'P' AND gol = '6' AND reason = 'Habis Kontrak' THEN 1 ELSE 0 END) AS Gol6_Kontrak_Habis_Perempuan,
                    SUM(CASE WHEN sexe = 'L' AND gol = '6' AND reason = 'PHK' THEN 1 ELSE 0 END) AS Gol6_Dikeluarkan_Pria,
                    SUM(CASE WHEN sexe = 'p' AND gol = '6' AND reason = 'PHK' THEN 1 ELSE 0 END) AS Gol6_Dikeluarkan_Perempuan,
                    SUM(CASE WHEN sexe = 'L' AND gol = '6' AND reason = 'Resign' THEN 1 ELSE 0 END) AS Gol6_Kehendak_Sendiri_Pria,
                    SUM(CASE WHEN sexe = 'P' AND gol = '6' AND reason = 'Resign' THEN 1 ELSE 0 END) AS Gol6_Kehendak_Sendiri_Perempuan,
                    SUM(CASE WHEN sexe = 'L' AND gol = '6' AND reason = 'Pensiun' THEN 1 ELSE 0 END) AS Gol6_Pensiun_Pria,
                    SUM(CASE WHEN sexe = 'P' AND gol = '6' AND reason = 'Pensiun' THEN 1 ELSE 0 END) AS Gol6_Pensiun_Perempuan,
                    SUM(CASE WHEN sexe = 'L' AND gol = '6' AND reason = 'Meninggal' THEN 1 ELSE 0 END) AS Gol6_Meninggal_Pria,
                    SUM(CASE WHEN sexe = 'P' AND gol = '6' AND reason = 'Meninggal' THEN 1 ELSE 0 END) AS Gol6_Meninggal_Perempuan,

                    SUM(CASE WHEN sexe = 'L' AND gol = '7' AND reason = 'Habis Kontrak' THEN 1 ELSE 0 END) AS Gol7_Kontrak_Habis_Pria,
                    SUM(CASE WHEN sexe = 'P' AND gol = '7' AND reason = 'Habis Kontrak' THEN 1 ELSE 0 END) AS Gol7_Kontrak_Habis_Perempuan,
                    SUM(CASE WHEN sexe = 'L' AND gol = '7' AND reason = 'PHK' THEN 1 ELSE 0 END) AS Gol7_Dikeluarkan_Pria,
                    SUM(CASE WHEN sexe = 'p' AND gol = '7' AND reason = 'PHK' THEN 1 ELSE 0 END) AS Gol7_Dikeluarkan_Perempuan,
                    SUM(CASE WHEN sexe = 'L' AND gol = '7' AND reason = 'Resign' THEN 1 ELSE 0 END) AS Gol7_Kehendak_Sendiri_Pria,
                    SUM(CASE WHEN sexe = 'P' AND gol = '7' AND reason = 'Resign' THEN 1 ELSE 0 END) AS Gol7_Kehendak_Sendiri_Perempuan,
                    SUM(CASE WHEN sexe = 'L' AND gol = '7' AND reason = 'Pensiun' THEN 1 ELSE 0 END) AS Gol7_Pensiun_Pria,
                    SUM(CASE WHEN sexe = 'P' AND gol = '7' AND reason = 'Pensiun' THEN 1 ELSE 0 END) AS Gol7_Pensiun_Perempuan,
                    SUM(CASE WHEN sexe = 'L' AND gol = '7' AND reason = 'Meninggal' THEN 1 ELSE 0 END) AS Gol7_Meninggal_Pria,
                    SUM(CASE WHEN sexe = 'P' AND gol = '7' AND reason = 'Meninggal' THEN 1 ELSE 0 END) AS Gol7_Meninggal_Perempuan
                FROM karyawan
WHERE 
    ($defaultDateCondition OR (MONTH(resdate) = MONTH('$endDate') AND YEAR(resdate) = YEAR('$endDate')))
    AND joindate <= '$endDate'
";

// // Execute query
// $resultStatusOut = mysqli_query($koneksi, $queryStatusOut);

// // Fetch the result as an associative array
// $row = mysqli_fetch_assoc($resultStatusOut);

// // Save query results to PHP variables
// $Gol0_Kontrak_Habis = $row['Gol0_Kontrak_Habis'];
// $Gol0_Dikeluarkan = $row['Gol0_Dikeluarkan'];
// $Gol0_Kehendak_Sendiri = $row['Gol0_Kehendak_Sendiri'];
// $Gol0_Pensiun = $row['Gol0_Pensiun'];
// $Gol0_Meninggal = $row['Gol0_Meninggal'];

// $Gol1_Kontrak_Habis = $row['Gol1_Kontrak_Habis'];
// $Gol1_Dikeluarkan = $row['Gol1_Dikeluarkan'];
// $Gol1_Kehendak_Sendiri = $row['Gol1_Kehendak_Sendiri'];
// $Gol1_Pensiun = $row['Gol1_Pensiun'];
// $Gol1_Meninggal = $row['Gol1_Meninggal'];

// $Gol2_Kontrak_Habis = $row['Gol2_Kontrak_Habis'];
// $Gol2_Dikeluarkan = $row['Gol2_Dikeluarkan'];
// $Gol2_Kehendak_Sendiri = $row['Gol2_Kehendak_Sendiri'];
// $Gol2_Pensiun = $row['Gol2_Pensiun'];
// $Gol2_Meninggal = $row['Gol2_Meninggal'];

// $Gol3_Kontrak_Habis = $row['Gol3_Kontrak_Habis'];
// $Gol3_Dikeluarkan = $row['Gol3_Dikeluarkan'];
// $Gol3_Kehendak_Sendiri = $row['Gol3_Kehendak_Sendiri'];
// $Gol3_Pensiun = $row['Gol3_Pensiun'];
// $Gol3_Meninggal = $row['Gol3_Meninggal'];

// $Gol4_Kontrak_Habis = $row['Gol4_Kontrak_Habis'];
// $Gol4_Dikeluarkan = $row['Gol4_Dikeluarkan'];
// $Gol4_Kehendak_Sendiri = $row['Gol4_Kehendak_Sendiri'];
// $Gol4_Pensiun = $row['Gol4_Pensiun'];
// $Gol4_Meninggal = $row['Gol4_Meninggal'];

// $Gol5_Kontrak_Habis = $row['Gol5_Kontrak_Habis'];
// $Gol5_Dikeluarkan = $row['Gol5_Dikeluarkan'];
// $Gol5_Kehendak_Sendiri = $row['Gol5_Kehendak_Sendiri'];
// $Gol5_Pensiun = $row['Gol5_Pensiun'];
// $Gol5_Meninggal = $row['Gol5_Meninggal'];

// $Gol6_Kontrak_Habis = $row['Gol6_Kontrak_Habis'];
// $Gol6_Dikeluarkan = $row['Gol6_Dikeluarkan'];
// $Gol6_Kehendak_Sendiri = $row['Gol6_Kehendak_Sendiri'];
// $Gol6_Pensiun = $row['Gol6_Pensiun'];
// $Gol6_Meninggal = $row['Gol6_Meninggal'];

// $Gol7_Kontrak_Habis = $row['Gol7_Kontrak_Habis'];
// $Gol7_Dikeluarkan = $row['Gol7_Dikeluarkan'];
// $Gol7_Kehendak_Sendiri = $row['Gol7_Kehendak_Sendiri'];
// $Gol7_Pensiun = $row['Gol7_Pensiun'];
// $Gol7_Meninggal = $row['Gol7_Meninggal'];


?>