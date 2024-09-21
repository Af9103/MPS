<?php
include __DIR__ . '/koneksi.php';


if (isset($_GET['show_all'])) {
    // Query to retrieve all mutation data without any filter on date
    $queryMutasi = "SELECT *
                FROM mutasi
                WHERE hapus IS NULL
                AND status = '10'";
} else {
    $currentMonth = date('m');
    $currentYear = date('Y');

    // Filter data based on the selected month and year, or show data for the current month if no filter is applied
    $selectedMonth = isset($_GET['bulan']) ? $_GET['bulan'] : $currentMonth;
    $selectedYear = isset($_GET['tahun']) ? $_GET['tahun'] : $currentYear;

    // Adjust previous month and year based on the selected month
    if ($selectedMonth == 1) {
        // If the selected month is January, adjust to the previous year
        $previousMonth = 12;
        $previousYear = $selectedYear - 1;
    } else {
        // Otherwise, just decrement the month
        $previousMonth = $selectedMonth - 1;
        $previousYear = $selectedYear;
    }

    $previousMonthTimestamp = mktime(0, 0, 0, $previousMonth, 10, $previousYear);
    $previousMonthName = date('F', $previousMonthTimestamp);


    // Query to retrieve mutation data based on the selected month and year
    $queryMutasi = "SELECT *
                FROM mutasi
                WHERE hapus IS NULL
                AND status = '10' AND
                     MONTH(mutasi.tanggalMutasi) = '$selectedMonth' AND YEAR(mutasi.tanggalMutasi) = '$selectedYear'";
}

$resultMutasi = mysqli_query($koneksi3, $queryMutasi);
if (!$resultMutasi) {
    die("Query error: " . mysqli_error($koneksi3));
}

// Query to get the number of employees moving out (mutasi out) per department
$queryMutasiSect = "SELECT subquery.sect_asal AS sect_asal, COUNT(*) AS MutasiOut
                        FROM (
                            SELECT karyawan.emno AS karyawan_emno, histori.cwoc AS histori_cwoc, histori.tanggal,
                                   (SELECT sect_sebelumnya.id_sect 
                                    FROM histori AS histori_sebelumnya 
                                    INNER JOIN sect AS sect_sebelumnya ON histori_sebelumnya.sect = sect_sebelumnya.id_sect 
                                    WHERE histori_sebelumnya.emno = karyawan.emno AND histori_sebelumnya.tanggal < histori.tanggal 
                                    ORDER BY histori_sebelumnya.tanggal DESC LIMIT 1) AS sect_asal
                            FROM karyawan 
                            INNER JOIN histori ON karyawan.emno = histori.emno
                            WHERE karyawan.gol BETWEEN 0 AND 3 
                            AND MONTH(histori.tanggal) = '$selectedMonth' AND YEAR(histori.tanggal) = '$selectedYear') AS subquery
                        GROUP BY subquery.sect_asal";


// Execute the query to get the mutation data
$resultMutasiSect = $koneksi->query($queryMutasiSect);

// Initialize an associative array to store mutation data by section
$ExcelMutasiSectbysect = array();
while ($row = $resultMutasiSect->fetch_assoc()) {
    $sect_asal = $row['sect_asal'];
    $ExcelMutasiSectbysect[$sect_asal] = array('MutasiOut' => $row['MutasiOut']);
}


$queryMutasiSectBulanLalu = "SELECT subquery.sect_asal AS sect_asal, COUNT(*) AS MutasiOut
    FROM (
        SELECT k.emno AS karyawan_emno, h.cwoc AS histori_cwoc, h.tanggal,
               (SELECT ss.id_sect 
                FROM histori AS hs 
                INNER JOIN sect AS ss ON hs.sect = ss.id_sect 
                WHERE hs.emno = k.emno AND hs.tanggal < h.tanggal 
                ORDER BY hs.tanggal DESC LIMIT 1) AS sect_asal
        FROM karyawan AS k 
        INNER JOIN histori AS h ON k.emno = h.emno
        WHERE k.gol BETWEEN 0 AND 3 
        AND MONTH(h.tanggal) = MONTH(FROM_UNIXTIME($previousMonthTimestamp)) AND YEAR(h.tanggal) = YEAR(FROM_UNIXTIME($previousMonthTimestamp))) AS subquery
    GROUP BY subquery.sect_asal";

// Persiapkan dan eksekusi query
$resultMutasiSectBulanLalu = $koneksi->query($queryMutasiSectBulanLalu);

// Inisialisasi array asosiatif untuk menyimpan data mutasi berdasarkan departemen asal
$ExcelMutasiSectbysectBulanLalu = array();
while ($row = $resultMutasiSectBulanLalu->fetch_assoc()) {
    $sect_asal = $row['sect_asal'];
    $ExcelMutasiSectbysectBulanLalu[$sect_asal] = array('MutasiOut' => $row['MutasiOut']);
}

// Define the query to get the number of incoming mutations (Mutasi In) per department
$queryMutasiInSect = "SELECT subquery.sect_sekarang AS sect_sekarang,
                            COUNT(*) AS MutasiIn
                      FROM (SELECT karyawan.emno AS karyawan_emno, histori.cwoc AS histori_cwoc, sect.Id_sect AS sect_sekarang
                            FROM karyawan 
                            INNER JOIN histori ON karyawan.emno = histori.emno
                            INNER JOIN sect ON histori.sect = sect.Id_sect
                            WHERE karyawan.gol BETWEEN 0 AND 3 
                            AND MONTH(histori.tanggal) = '$selectedMonth' 
                            AND YEAR(histori.tanggal) = '$selectedYear'
                            GROUP BY karyawan.emno, histori.tanggal) AS subquery
                      GROUP BY subquery.sect_sekarang";

$resultMutasiInSect = $koneksi->query($queryMutasiInSect);


// Initialize an associative array to store incoming mutations by section
$ExcelMutasiInSectbysect = array();

while ($row = $resultMutasiInSect->fetch_assoc()) {
    $sect_sekarang = $row['sect_sekarang'];
    $ExcelMutasiInSectbysect[$sect_sekarang] = array(
        'MutasiIn' => $row['MutasiIn']
    );
}

$queryMutasiInSectBulanLalu = "SELECT subquery.sect_sekarang AS sect_sekarang,
                                    COUNT(*) AS MutasiIn
                            FROM (SELECT karyawan.emno AS karyawan_emno, histori.cwoc AS histori_cwoc, sect.Id_sect AS sect_sekarang
                                    FROM karyawan 
                                    INNER JOIN histori ON karyawan.emno = histori.emno
                                    INNER JOIN sect ON histori.sect = sect.Id_sect
                                    WHERE karyawan.gol BETWEEN 0 AND 3 
                                    AND MONTH(histori.tanggal) = MONTH(FROM_UNIXTIME($previousMonthTimestamp)) 
                                    AND YEAR(histori.tanggal) = YEAR(FROM_UNIXTIME($previousMonthTimestamp))
                                    GROUP BY karyawan.emno, histori.tanggal) AS subquery
                            GROUP BY subquery.sect_sekarang";


$resultMutasiInSectBulanLalu = $koneksi->query($queryMutasiInSectBulanLalu);


// Initialize an associative array to store incoming mutations by section
$ExcelMutasiInSectbysectBulanLalu = array();

while ($row = $resultMutasiInSectBulanLalu->fetch_assoc()) {
    $sect_sekarang = $row['sect_sekarang'];
    $ExcelMutasiInSectbysectBulanLalu[$sect_sekarang] = array(
        'MutasiIn' => $row['MutasiIn']
    );
}

$queryInOutSect = "SELECT
        SUM(CASE WHEN MONTH(joindate) = '$selectedMonth' AND YEAR(joindate) = '$selectedYear' AND gol BETWEEN 0 AND 3 THEN 1 ELSE 0 END) AS `IN`,
        SUM(CASE WHEN MONTH(resdate) = '$selectedMonth' AND YEAR(resdate) = '$selectedYear' AND gol BETWEEN 0 AND 3 THEN 1 ELSE 0 END) AS `OUT`,
        sect
    FROM karyawan
    GROUP BY sect
";

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


$queryInOutSectLalu = "SELECT
        SUM(CASE WHEN MONTH(joindate) = '" . date('m', $previousMonthTimestamp) . "' AND YEAR(joindate) = '$previousYear' AND gol BETWEEN 0 AND 3 THEN 1 ELSE 0 END) AS `IN`,
        SUM(CASE WHEN MONTH(resdate) = '" . date('m', $previousMonthTimestamp) . "' AND YEAR(resdate) = '$previousYear' AND gol BETWEEN 0 AND 3 THEN 1 ELSE 0 END) AS `OUT`,
        sect
    FROM karyawan
    GROUP BY sect";


// Execute the query
$resultInOutSectLalu = $koneksi->query($queryInOutSectLalu);

// Initialize an associative array to store the IN and OUT data by section for the previous month
$ExcelInOutbysectLalu = array();
while ($row = $resultInOutSectLalu->fetch_assoc()) {
    $sect = $row['sect']; // Use the $sect variable if sect represents department
    // Store the 'IN' and 'OUT' values into array using appropriate keys
    $ExcelInOutbysectLalu[$sect] = array(
        'IN' => $row['IN'],
        'OUT' => $row['OUT']
    );
}



$queryIL = "SELECT karyawan.cwoc
                FROM karyawan
                INNER JOIN sect ON karyawan.sect = sect.Id_sect
                WHERE karyawan.cwoc IN ('PRODUCTION 1','PRODUCTION 2','PRODUCTION 3','PRODUCTION 4','PRODUCTION 5')
                GROUP BY karyawan.cwoc
                ORDER BY FIELD(karyawan.cwoc, 'PRODUCTION 1','PRODUCTION 2','PRODUCTION 3','PRODUCTION 4','PRODUCTION 5')";


$resultIL = mysqli_query($koneksi, $queryIL);


$queryMutOut2 = "SELECT 
    COALESCE(subquery.departemen_asal, subquery.cwoc) AS departemen_asal,
    COUNT(*) AS MutasiOut
FROM (SELECT karyawan.cwoc,karyawan.emno AS karyawan_emno, nama.*, histori.cwoc AS histori_cwoc, histori.tanggal, sect.desc AS sect_desc,
        (SELECT histori_sebelumnya.cwoc 
            FROM histori AS histori_sebelumnya 
            WHERE histori_sebelumnya.emno = karyawan.emno AND histori_sebelumnya.tanggal < histori.tanggal 
            ORDER BY histori_sebelumnya.tanggal DESC LIMIT 1) AS departemen_asal,
        (SELECT sect_sebelumnya.desc 
            FROM histori AS histori_sebelumnya 
            INNER JOIN sect AS sect_sebelumnya ON histori_sebelumnya.sect = sect_sebelumnya.id_sect 
            WHERE histori_sebelumnya.emno = karyawan.emno 
                AND histori_sebelumnya.tanggal < histori.tanggal 
            ORDER BY histori_sebelumnya.tanggal DESC 
            LIMIT 1) AS sect_asal
    FROM 
        karyawan 
        LEFT JOIN nama ON karyawan.emno = nama.emno 
        INNER JOIN histori ON karyawan.emno = histori.emno
        INNER JOIN sect ON histori.sect = sect.id_sect
    WHERE karyawan.gol BETWEEN 4 AND 6
        AND MONTH(histori.tanggal) = '$selectedMonth' 
        AND YEAR(histori.tanggal) = '$selectedYear') AS subquery
GROUP BY 
    COALESCE(subquery.departemen_asal, subquery.cwoc)";

// Eksekusi query
$resultMutOut2 = $koneksi->query($queryMutOut2);

// Memasukkan data jumlah mutasi keluar sesuai dengan departemen asal atau cwoc ke dalam array asosiatif
$ExcelMutOut2bycwoc = array();
while ($row = $resultMutOut2->fetch_assoc()) {
    $cwoc = $row['departemen_asal']; // Gunakan variabel $cwoc jika departemen_asal berisi cwoc
    // Menyimpan nilai 'jumlah_mutasi_out' ke dalam array dengan menggunakan kunci yang sesuai
    $ExcelMutOut2bycwoc[$cwoc] = array(
        'MutasiOut' => $row['MutasiOut']
    );
}

$queryMutOut2BulanLalu = "SELECT 
    COALESCE(subquery.departemen_asal, subquery.cwoc) AS departemen_asal,
    COUNT(*) AS MutasiOut
FROM (SELECT karyawan.cwoc,karyawan.emno AS karyawan_emno, nama.*, histori.cwoc AS histori_cwoc, histori.tanggal, sect.desc AS sect_desc,
        (SELECT histori_sebelumnya.cwoc 
            FROM histori AS histori_sebelumnya 
            WHERE histori_sebelumnya.emno = karyawan.emno AND histori_sebelumnya.tanggal < histori.tanggal 
            ORDER BY histori_sebelumnya.tanggal DESC LIMIT 1) AS departemen_asal,
        (SELECT sect_sebelumnya.desc 
            FROM histori AS histori_sebelumnya 
            INNER JOIN sect AS sect_sebelumnya ON histori_sebelumnya.sect = sect_sebelumnya.id_sect 
            WHERE histori_sebelumnya.emno = karyawan.emno 
                AND histori_sebelumnya.tanggal < histori.tanggal 
            ORDER BY histori_sebelumnya.tanggal DESC 
            LIMIT 1) AS sect_asal
    FROM 
        karyawan 
        LEFT JOIN nama ON karyawan.emno = nama.emno 
        INNER JOIN histori ON karyawan.emno = histori.emno
        INNER JOIN sect ON histori.sect = sect.id_sect
    WHERE karyawan.gol BETWEEN 4 AND 6
    AND MONTH(histori.tanggal) = MONTH(FROM_UNIXTIME($previousMonthTimestamp)) AND YEAR(histori.tanggal) = YEAR(FROM_UNIXTIME($previousMonthTimestamp))) AS subquery
GROUP BY 
    COALESCE(subquery.departemen_asal, subquery.cwoc)";

// Eksekusi query
$resultMutOut2BulanLalu = $koneksi->query($queryMutOut2BulanLalu);

// Memasukkan data jumlah mutasi keluar sesuai dengan departemen asal atau cwoc ke dalam array asosiatif
$ExcelMutOut2bycwocBulanLalu = array();
while ($row = $resultMutOut2BulanLalu->fetch_assoc()) {
    $cwoc = $row['departemen_asal']; // Gunakan variabel $cwoc jika departemen_asal berisi cwoc
    // Menyimpan nilai 'jumlah_mutasi_out' ke dalam array dengan menggunakan kunci yang sesuai
    $ExcelMutOut2bycwocBulanLalu[$cwoc] = array(
        'MutasiOut' => $row['MutasiOut']
    );
}

$queryMutIn2 = "SELECT 
subquery.histori_cwoc AS histori_cwoc,
COUNT(*) AS MutasiIn
FROM (SELECT karyawan.emno AS karyawan_emno,nama.*, histori.cwoc AS histori_cwoc, histori.tanggal, sect.desc AS sect_desc,
    (SELECT histori_sebelumnya.cwoc 
        FROM histori AS histori_sebelumnya 
        WHERE histori_sebelumnya.emno = karyawan.emno AND histori_sebelumnya.tanggal < histori.tanggal 
        ORDER BY histori_sebelumnya.tanggal DESC LIMIT 1) AS departemen_asal,
    (SELECT sect_sebelumnya.desc 
        FROM histori AS histori_sebelumnya 
        INNER JOIN sect AS sect_sebelumnya ON histori_sebelumnya.sect = sect_sebelumnya.id_sect 
        WHERE histori_sebelumnya.emno = karyawan.emno 
            AND histori_sebelumnya.tanggal < histori.tanggal 
        ORDER BY histori_sebelumnya.tanggal DESC LIMIT 1) AS sect_asal
FROM 
    karyawan 
    LEFT JOIN nama ON karyawan.emno = nama.emno 
    INNER JOIN histori ON karyawan.emno = histori.emno
    INNER JOIN sect ON histori.sect = sect.id_sect
WHERE karyawan.gol BETWEEN 4 AND 6
AND MONTH(histori.tanggal) = '$selectedMonth' 
AND YEAR(histori.tanggal) = '$selectedYear') AS subquery
GROUP BY 
subquery.histori_cwoc";

// Eksekusi query
$resultMutIn2 = $koneksi->query($queryMutIn2);

// Memasukkan data jumlah mutasi keluar sesuai dengan departemen asal atau cwoc ke dalam array asosiatif
$ExcelMutIn2bycwoc = array();
while ($row = $resultMutIn2->fetch_assoc()) {
    $cwoc = $row['histori_cwoc']; // Gunakan variabel $cwoc jika departemen_asal berisi cwoc
    // Menyimpan nilai 'jumlah_mutasi_In' ke dalam array dengan menggunakan kunci yang sesuai
    $ExcelMutIn2bycwoc[$cwoc] = array(
        'MutasiIn' => $row['MutasiIn']
    );
}

$queryMutIn2BulanLalu = "SELECT 
subquery.histori_cwoc AS histori_cwoc,
COUNT(*) AS MutasiIn
FROM (SELECT karyawan.emno AS karyawan_emno,nama.*, histori.cwoc AS histori_cwoc, histori.tanggal, sect.desc AS sect_desc,
    (SELECT histori_sebelumnya.cwoc 
        FROM histori AS histori_sebelumnya 
        WHERE histori_sebelumnya.emno = karyawan.emno AND histori_sebelumnya.tanggal < histori.tanggal 
        ORDER BY histori_sebelumnya.tanggal DESC LIMIT 1) AS departemen_asal,
    (SELECT sect_sebelumnya.desc 
        FROM histori AS histori_sebelumnya 
        INNER JOIN sect AS sect_sebelumnya ON histori_sebelumnya.sect = sect_sebelumnya.id_sect 
        WHERE histori_sebelumnya.emno = karyawan.emno 
            AND histori_sebelumnya.tanggal < histori.tanggal 
        ORDER BY histori_sebelumnya.tanggal DESC LIMIT 1) AS sect_asal
FROM 
    karyawan 
    LEFT JOIN nama ON karyawan.emno = nama.emno 
    INNER JOIN histori ON karyawan.emno = histori.emno
    INNER JOIN sect ON histori.sect = sect.id_sect
WHERE karyawan.gol BETWEEN 4 AND 6
AND MONTH(histori.tanggal) = MONTH(FROM_UNIXTIME($previousMonthTimestamp)) AND YEAR(histori.tanggal) = YEAR(FROM_UNIXTIME($previousMonthTimestamp))) AS subquery
GROUP BY 
subquery.histori_cwoc";

// Eksekusi query
$resultMutIn2BulanLalu = $koneksi->query($queryMutIn2BulanLalu);

// Memasukkan data jumlah mutasi keluar sesuai dengan departemen asal atau cwoc ke dalam array asosiatif
$ExcelMutIn2bycwocBulanLalu = array();
while ($row = $resultMutIn2BulanLalu->fetch_assoc()) {
    $cwoc = $row['histori_cwoc']; // Gunakan variabel $cwoc jika departemen_asal berisi cwoc
    // Menyimpan nilai 'jumlah_mutasi_In' ke dalam array dengan menggunakan kunci yang sesuai
    $ExcelMutIn2bycwocBulanLalu[$cwoc] = array(
        'MutasiIn' => $row['MutasiIn']
    );
}

$queryInOut2 = "SELECT
        SUM(CASE WHEN MONTH(joindate) = '$selectedMonth' AND YEAR(joindate) = '$selectedYear' THEN 1 ELSE 0 END) AS `IN`,
        SUM(CASE WHEN MONTH(resdate) = '$selectedMonth' AND YEAR(resdate) = '$selectedYear' THEN 1 ELSE 0 END) AS `OUT`,
cwoc
FROM karyawan
WHERE gol BETWEEN 4 AND 6
GROUP BY cwoc";

// Eksekusi query
$resultInOut2 = $koneksi->query($queryInOut2);

// Memasukkan data jumlah mutasi keluar sesuai dengan departemen asal atau cwoc ke dalam array asosiatif
$ExcelInOut2bycwoc = array();
while ($row = $resultInOut2->fetch_assoc()) {
    $cwoc = $row['cwoc']; // Gunakan variabel $cwoc jika departemen_asal berisi cwoc
    // Menyimpan nilai 'jumlah_mutasi_In' ke dalam array dengan menggunakan kunci yang sesuai
    $ExcelInOut2bycwoc[$cwoc] = array(
        'IN' => $row['IN'],
        'OUT' => $row['OUT']
    );
}

$queryInOut2BulanLalu = "SELECT
        SUM(CASE WHEN MONTH(joindate) = '" . date('m', $previousMonthTimestamp) . "' AND YEAR(joindate) = '$previousYear' THEN 1 ELSE 0 END) AS `IN`,
        SUM(CASE WHEN MONTH(resdate) = '" . date('m', $previousMonthTimestamp) . "' AND YEAR(resdate) = '$previousYear' THEN 1 ELSE 0 END) AS `OUT`,
cwoc
FROM karyawan
WHERE gol BETWEEN 4 AND 6
GROUP BY cwoc";

// Eksekusi query
$resultInOut2BulanLalu = $koneksi->query($queryInOut2BulanLalu);

// Memasukkan data jumlah mutasi keluar sesuai dengan departemen asal atau cwoc ke dalam array asosiatif
$ExcelInOut2bycwocBulanLalu = array();
while ($row = $resultInOut2BulanLalu->fetch_assoc()) {
    $cwoc = $row['cwoc']; // Gunakan variabel $cwoc jika departemen_asal berisi cwoc
    // Menyimpan nilai 'jumlah_mutasi_In' ke dalam array dengan menggunakan kunci yang sesuai
    $ExcelInOut2bycwocBulanLalu[$cwoc] = array(
        'IN' => $row['IN'],
        'OUT' => $row['OUT']
    );
}

$queryMutIn = "SELECT 
subquery.histori_cwoc AS histori_cwoc,
COUNT(*) AS MutasiIn
FROM (
SELECT 
    karyawan.emno AS karyawan_emno,
    nama.*, 
    histori.cwoc AS histori_cwoc, 
    histori.tanggal, 
    sect.desc AS sect_desc,
    (SELECT histori_sebelumnya.cwoc 
        FROM histori AS histori_sebelumnya 
        WHERE histori_sebelumnya.emno = karyawan.emno 
            AND histori_sebelumnya.tanggal < histori.tanggal 
        ORDER BY histori_sebelumnya.tanggal DESC 
        LIMIT 1) AS departemen_asal,
    (SELECT sect_sebelumnya.desc 
        FROM histori AS histori_sebelumnya 
        INNER JOIN sect AS sect_sebelumnya ON histori_sebelumnya.sect = sect_sebelumnya.id_sect 
        WHERE histori_sebelumnya.emno = karyawan.emno 
            AND histori_sebelumnya.tanggal < histori.tanggal 
        ORDER BY histori_sebelumnya.tanggal DESC 
        LIMIT 1) AS sect_asal
FROM 
    karyawan 
    LEFT JOIN nama ON karyawan.emno = nama.emno 
    INNER JOIN histori ON karyawan.emno = histori.emno
    INNER JOIN sect ON histori.sect = sect.id_sect
WHERE MONTH(histori.tanggal) = '$selectedMonth' 
AND YEAR(histori.tanggal) = '$selectedYear') AS subquery
GROUP BY 
subquery.histori_cwoc";

// Eksekusi query
$resultMutIn = $koneksi->query($queryMutIn);

// Memasukkan data jumlah mutasi keluar sesuai dengan departemen asal atau cwoc ke dalam array asosiatif
$ExcelMutInbycwoc = array();
while ($row = $resultMutIn->fetch_assoc()) {
    $cwoc = $row['histori_cwoc']; // Gunakan variabel $cwoc jika departemen_asal berisi cwoc
    // Menyimpan nilai 'MutasiIn' ke dalam array dengan menggunakan kunci yang sesuai
    $ExcelMutInbycwoc[$cwoc] = array(
        'MutasiIn' => $row['MutasiIn']
    );
}

$queryMutInBulanLalu = "SELECT 
subquery.histori_cwoc AS histori_cwoc,
COUNT(*) AS MutasiIn
FROM (
SELECT 
    karyawan.emno AS karyawan_emno,
    nama.*, 
    histori.cwoc AS histori_cwoc, 
    histori.tanggal, 
    sect.desc AS sect_desc,
    (SELECT histori_sebelumnya.cwoc 
        FROM histori AS histori_sebelumnya 
        WHERE histori_sebelumnya.emno = karyawan.emno 
            AND histori_sebelumnya.tanggal < histori.tanggal 
        ORDER BY histori_sebelumnya.tanggal DESC 
        LIMIT 1) AS departemen_asal,
    (SELECT sect_sebelumnya.desc 
        FROM histori AS histori_sebelumnya 
        INNER JOIN sect AS sect_sebelumnya ON histori_sebelumnya.sect = sect_sebelumnya.id_sect 
        WHERE histori_sebelumnya.emno = karyawan.emno 
            AND histori_sebelumnya.tanggal < histori.tanggal 
        ORDER BY histori_sebelumnya.tanggal DESC 
        LIMIT 1) AS sect_asal
FROM 
    karyawan 
    LEFT JOIN nama ON karyawan.emno = nama.emno 
    INNER JOIN histori ON karyawan.emno = histori.emno
    INNER JOIN sect ON histori.sect = sect.id_sect
    WHERE MONTH(histori.tanggal) = MONTH(FROM_UNIXTIME($previousMonthTimestamp)) AND YEAR(histori.tanggal) = YEAR(FROM_UNIXTIME($previousMonthTimestamp))) AS subquery
GROUP BY 
subquery.histori_cwoc";

// Eksekusi query
$resultMutInBulanLalu = $koneksi->query($queryMutInBulanLalu);

// Memasukkan data jumlah mutasi keluar sesuai dengan departemen asal atau cwoc ke dalam array asosiatif
$ExcelMutIn2bycwocBulanLalu = array();
while ($row = $resultMutInBulanLalu->fetch_assoc()) {
    $cwoc = $row['histori_cwoc']; // Gunakan variabel $cwoc jika departemen_asal berisi cwoc
    // Menyimpan nilai 'MutasiIn' ke dalam array dengan menggunakan kunci yang sesuai
    $ExcelMutInbycwocBulanLalu[$cwoc] = array(
        'MutasiIn' => $row['MutasiIn']
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

$queryInOutBulanLalu = "SELECT
        SUM(CASE WHEN MONTH(joindate) = '" . date('m', $previousMonthTimestamp) . "' AND YEAR(joindate) = '$previousYear' THEN 1 ELSE 0 END) AS `IN`,
        SUM(CASE WHEN MONTH(resdate) = '" . date('m', $previousMonthTimestamp) . "' AND YEAR(resdate) = '$previousYear' THEN 1 ELSE 0 END) AS `OUT`,
cwoc
FROM karyawan
GROUP BY cwoc";

// Eksekusi query
$resultInOutBulanLalu = $koneksi->query($queryInOutBulanLalu);

// Memasukkan data jumlah mutasi keluar sesuai dengan departemen asal atau cwoc ke dalam array asosiatif
$ExcelInOutbycwocBulanLalu = array();
while ($row = $resultInOutBulanLalu->fetch_assoc()) {
    $cwoc = $row['cwoc']; // Gunakan variabel $cwoc jika departemen_asal berisi cwoc
    // Menyimpan nilai 'jumlah_mutasi_In' ke dalam array dengan menggunakan kunci yang sesuai
    $ExcelInOutbycwocBulanLalu[$cwoc] = array(
        'IN' => $row['IN'],
        'OUT' => $row['OUT']
    );
}

$queryMutOut = "SELECT 
    COALESCE(subquery.departemen_asal, subquery.cwoc) AS departemen_asal,
    COUNT(*) AS MutasiOut
FROM (
    SELECT 
        karyawan.cwoc,
        karyawan.emno AS karyawan_emno,
        nama.*, 
        histori.cwoc AS histori_cwoc, 
        histori.tanggal, 
        sect.desc AS sect_desc,
        (SELECT histori_sebelumnya.cwoc 
            FROM histori AS histori_sebelumnya 
            WHERE histori_sebelumnya.emno = karyawan.emno 
                AND histori_sebelumnya.tanggal < histori.tanggal 
            ORDER BY histori_sebelumnya.tanggal DESC 
            LIMIT 1) AS departemen_asal,
        (SELECT sect_sebelumnya.desc 
            FROM histori AS histori_sebelumnya 
            INNER JOIN sect AS sect_sebelumnya ON histori_sebelumnya.sect = sect_sebelumnya.id_sect 
            WHERE histori_sebelumnya.emno = karyawan.emno 
                AND histori_sebelumnya.tanggal < histori.tanggal 
            ORDER BY histori_sebelumnya.tanggal DESC 
            LIMIT 1) AS sect_asal
    FROM 
        karyawan 
        LEFT JOIN nama ON karyawan.emno = nama.emno 
        INNER JOIN histori ON karyawan.emno = histori.emno
        INNER JOIN sect ON histori.sect = sect.id_sect
    WHERE MONTH(histori.tanggal) = '$selectedMonth' 
AND YEAR(histori.tanggal) = '$selectedYear') AS subquery
GROUP BY 
    COALESCE(subquery.departemen_asal, subquery.cwoc)";

// Eksekusi query
$resultMutOut = $koneksi->query($queryMutOut);

// Memasukkan data jumlah mutasi keluar sesuai dengan departemen asal atau cwoc ke dalam array asosiatif
$ExcelMutOutbycwoc = array();
while ($row = $resultMutOut->fetch_assoc()) {
    $cwoc = $row['departemen_asal']; // Gunakan variabel $cwoc jika departemen_asal berisi cwoc
    // Menyimpan nilai 'jumlah_mutasi_out' ke dalam array dengan menggunakan kunci yang sesuai
    $ExcelMutOutbycwoc[$cwoc] = array(
        'MutasiOut' => $row['MutasiOut']
    );
}

$queryMutOutBulanLalu = "SELECT 
    COALESCE(subquery.departemen_asal, subquery.cwoc) AS departemen_asal,
    COUNT(*) AS MutasiOut
FROM (
    SELECT 
        karyawan.cwoc,
        karyawan.emno AS karyawan_emno,
        nama.*, 
        histori.cwoc AS histori_cwoc, 
        histori.tanggal, 
        sect.desc AS sect_desc,
        (SELECT histori_sebelumnya.cwoc 
            FROM histori AS histori_sebelumnya 
            WHERE histori_sebelumnya.emno = karyawan.emno 
                AND histori_sebelumnya.tanggal < histori.tanggal 
            ORDER BY histori_sebelumnya.tanggal DESC 
            LIMIT 1) AS departemen_asal,
        (SELECT sect_sebelumnya.desc 
            FROM histori AS histori_sebelumnya 
            INNER JOIN sect AS sect_sebelumnya ON histori_sebelumnya.sect = sect_sebelumnya.id_sect 
            WHERE histori_sebelumnya.emno = karyawan.emno 
                AND histori_sebelumnya.tanggal < histori.tanggal 
            ORDER BY histori_sebelumnya.tanggal DESC 
            LIMIT 1) AS sect_asal
    FROM 
        karyawan 
        LEFT JOIN nama ON karyawan.emno = nama.emno 
        INNER JOIN histori ON karyawan.emno = histori.emno
        INNER JOIN sect ON histori.sect = sect.id_sect
        WHERE MONTH(histori.tanggal) = MONTH(FROM_UNIXTIME($previousMonthTimestamp)) AND YEAR(histori.tanggal) = YEAR(FROM_UNIXTIME($previousMonthTimestamp))) AS subquery
GROUP BY 
    COALESCE(subquery.departemen_asal, subquery.cwoc)";

// Eksekusi query
$resultMutOutBulanLalu = $koneksi->query($queryMutOutBulanLalu);

// Memasukkan data jumlah mutasi keluar sesuai dengan departemen asal atau cwoc ke dalam array asosiatif
$ExcelMutOutbycwocBulanLalu = array();
while ($row = $resultMutOutBulanLalu->fetch_assoc()) {
    $cwoc = $row['departemen_asal']; // Gunakan variabel $cwoc jika departemen_asal berisi cwoc
    // Menyimpan nilai 'jumlah_mutasi_out' ke dalam array dengan menggunakan kunci yang sesuai
    $ExcelMutOutbycwocBulanLalu[$cwoc] = array(
        'MutasiOut' => $row['MutasiOut']
    );
}
?>