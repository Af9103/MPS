<?php
// Load PHPExcel library
require_once '../asset/PHPExcel/Classes/PHPExcel.php';
require_once('../query/koneksi.php');
require_once('../query/queryExcelMutasi.php');

$objPHPExcel = new PHPExcel();
$objPHPExcel->setActiveSheetIndex(0); // Set aktif ke sheet pertama ('Real Time Man Power')
$objPHPExcel->getDefaultStyle()->getFont()->setName('Calibri');
$objPHPExcel->getSheet(0)->setTitle('Daftar Mutasi'); // Mengatur judul lembar pertama


$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(5);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(48);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(17);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(25);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(25);
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(25);
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(21);

$objPHPExcel->getActiveSheet()->getStyle('B4')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle('B4')->getFill()->getStartColor()->setRGB('D9E1F2');

$objPHPExcel->getActiveSheet()->mergeCells('B4:J4');

$objPHPExcel->getActiveSheet()
            ->getStyle('B4')
            ->getAlignment()
            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

$objPHPExcel->getActiveSheet()
            ->getStyle('B:J')
            ->getAlignment()
            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

$objPHPExcel->getActiveSheet()
            ->getStyle('B4:J5')
            ->getFont()
            ->setBold(true);

$currentMonth = date('m');
$currentYear = date('Y');

// Filter data based on the selected month and year, or show data for the current month if no filter is applied
$selectedMonth = isset($_GET['bulan']) ? $_GET['bulan'] : $currentMonth;
$selectedYear = isset($_GET['tahun']) ? $_GET['tahun'] : $currentYear;

// Convert the selected month number to the month name
$selectedMonthName = date('F', mktime(0, 0, 0, $selectedMonth, 10));

$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('B4', "Daftar Mutasi: $selectedMonthName $selectedYear")
            ->setCellValue('B5', 'No')
            ->setCellValue('C5', 'NPK')
            ->setCellValue('D5', 'Nama')
            ->setCellValue('E5', 'Gender')
            ->setCellValue('F5', 'Status')
            ->setCellValue('G5', 'Posisi')
            ->setCellValue('H5', 'Departemen Sebelumnya')
            ->setCellValue('I5', 'Departemen Saat Ini')
            ->setCellValue('J5', 'Tanggal Mutasi');

$styleArray = array(
    'borders' => array(
        'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
        ),
    ),
);

// Terapkan gaya tepi di dalam sel (tipis)
$objPHPExcel->getActiveSheet()->getStyle('B5:J5')->applyFromArray($styleArray);

// Definisikan gaya tepi (border) tipis
$styleArray = [
    'borders' => [
        'allborders' => [
            'style' => PHPExcel_Style_Border::BORDER_THIN
        ]
    ]
];

$sheet = $objPHPExcel->getActiveSheet();
$sheet->freezePane('A6');



$no = 1; // Start number
$start_row = 6; // Start row number for data

// Terapkan border ke baris 6 terlebih dahulu
$objPHPExcel->getActiveSheet()->getStyle('B6:J6')->applyFromArray($styleArray);

while ($user_data = mysqli_fetch_array($resultMutasi)) {
    $emno = isset($user_data['emno']) ? $user_data['emno'] : null;
    $nama = isset($user_data['nama_karyawan']) ? $user_data['nama_karyawan'] : null;
    $sexe = isset($user_data['sexe']) ? ($user_data['sexe'] == 'P' ? 'Perempuan' : ($user_data['sexe'] == 'L' ? 'Laki-laki' : null)) : null;
    $status = isset($user_data['emno']) ? (
        strpos($user_data['emno'], 'K') === 0 ? 'Kontrak' :
        (preg_match('/^0/', $user_data['emno']) || preg_match('/^[0-9]+$/', $user_data['emno']) ? 'Permanen' :
        (strpos($user_data['emno'], 'P') === 0 ? 'Trainee' : 'Unknown'))) : null;
// Logika untuk posisi berdasarkan gol
    $posisi = isset($user_data['gol']) ? (
        $user_data['gol'] >= 0 && $user_data['gol'] <= 2 ? 'Operator' :
        ($user_data['gol'] == 3 ? 'Foreman' :
        ($user_data['gol'] == 4 ? 'Supervisor' :
        ($user_data['gol'] == 5 ? 'Manager' :
        ($user_data['gol'] == 6 ? 'BOD' : 'Unknown'))))
    ) : null;
    $cwoc= isset($user_data['cwoc']) ? $user_data['cwoc'] : null;
    $deptsebelumnya=isset($user_data['departemen_asal']) ? $user_data['departemen_asal'] : null;
    $deptNow=isset($user_data['histori_cwoc']) ? $user_data['histori_cwoc'] : null;
    $tanggal = isset($user_data['tanggal']) ? date('d/M/y', strtotime($user_data['tanggal'])) : null;

    // Set cell values
    $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('B' . ($no + $start_row - 1), $no) // Set the row number in column B
                ->setCellValue('C' . ($no + $start_row - 1), $emno)
                ->setCellValue('D' . ($no + $start_row - 1), $nama)
                ->setCellValue('E' . ($no + $start_row - 1), $sexe)
                ->setCellValue('F' . ($no + $start_row - 1), $status)
                ->setCellValue('G' . ($no + $start_row - 1), $posisi)
                ->setCellValue('H' . ($no + $start_row - 1), $deptsebelumnya)
                ->setCellValue('I' . ($no + $start_row - 1), $deptNow)
                ->setCellValue('J' . ($no + $start_row - 1), $tanggal);

    // Terapkan gaya tepi (border) ke baris saat ini
    $currentRow = $no + $start_row - 1;
    $objPHPExcel->getActiveSheet()->getStyle('B' . $currentRow . ':J' . $currentRow)->applyFromArray($styleArray);

    $no++; // Increment number
}

$objPHPExcel->createSheet(); // Membuat sheet baru
$objPHPExcel->setActiveSheetIndex(1); // Set aktif ke sheet baru

// Mengatur judul lembar baru
$objPHPExcel->getDefaultStyle()->getFont()->setName('Century Gothic');
$objPHPExcel->getSheet(1)->setTitle('Man Power Status'); // Mengatur judul lembar pertama
$objPHPExcel->getDefaultStyle()->getFont()->setSize(9);
$sheet = $objPHPExcel->getActiveSheet();
$sheet->getDefaultColumnDimension()->setWidth(7); // Set the default width to 15 units
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(4);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(35);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(10);

$sheet = $objPHPExcel->getActiveSheet();
$sheet->freezePane('D5');


// Daftar sel yang akan digabungkan
$mergeCells = [
    'B1:C2',
    'D1:H2',
    'B3:B4',
    'C3:C4',
    'D3:H3',
    'I3:M3'
];

$mergeCells = array_unique($mergeCells);

// Menggabungkan sel sesuai dengan range yang telah ditentukan
foreach ($mergeCells as $mergeRange) {
    $objPHPExcel->getActiveSheet()->mergeCells($mergeRange);
}

// Add border to cells A1:D4 in the first sheet
$styleArray = array(
    'borders' => array(
        'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
        ),
    ),
);
$styleArrayOutside = array(
    'borders' => array(
        'outline' => array(
            'style' => PHPExcel_Style_Border::BORDER_DOUBLE,
        ),
    ),
);

// Terapkan gaya tepi di dalam sel (tipis)
$objPHPExcel->getActiveSheet()->getStyle('B3:M95')->applyFromArray($styleArray);

// Terapkan gaya tepi di luar sel (tebal)
$objPHPExcel->getActiveSheet()->getStyle('B3:M95')->applyFromArray($styleArrayOutside);
$objPHPExcel->getActiveSheet()->getStyle('B3:C95')->applyFromArray($styleArrayOutside);
$objPHPExcel->getActiveSheet()->getStyle('D3:M3')->applyFromArray($styleArrayOutside);
$objPHPExcel->getActiveSheet()->getStyle('B13:M13')->applyFromArray($styleArrayOutside);
$objPHPExcel->getActiveSheet()->getStyle('B20:M20')->applyFromArray($styleArrayOutside);
$objPHPExcel->getActiveSheet()->getStyle('B26:M26')->applyFromArray($styleArrayOutside);
$objPHPExcel->getActiveSheet()->getStyle('B37:M37')->applyFromArray($styleArrayOutside);
$objPHPExcel->getActiveSheet()->getStyle('B59:M59')->applyFromArray($styleArrayOutside);
$objPHPExcel->getActiveSheet()->getStyle('B60:M60')->applyFromArray($styleArrayOutside);
$objPHPExcel->getActiveSheet()->getStyle('B81:M81')->applyFromArray($styleArrayOutside);
$objPHPExcel->getActiveSheet()->getStyle('B94:M94')->applyFromArray($styleArrayOutside);
$objPHPExcel->getActiveSheet()->getStyle('B95:M95')->applyFromArray($styleArrayOutside);
$objPHPExcel->getActiveSheet()->getStyle('B3:M4')->applyFromArray($styleArrayOutside);

$objPHPExcel->getActiveSheet()
            ->getStyle('B3:M4')
            ->getAlignment()
            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

// Mengatur horizontal dan vertical alignment untuk 'B4'
$objPHPExcel->getActiveSheet()
            ->getStyle('D5:M95')
            ->getAlignment()
            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

$objPHPExcel->getActiveSheet()
            ->getStyle('B5:B95')
            ->getAlignment()
            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
            ->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

// Dapatkan sheet aktif
$sheet = $objPHPExcel->getActiveSheet();

// Daftar sel yang akan diatur gaya dan ukuran fontnya
$cells = ['B1', 'B5:C6','H7:H12','B13:M13','C14','H15:H19', 'M15:M19','C20:M20','C21','H22:H25', 'M22:M25','C26:M27','H28:H36','M28:M36','C37:C38','H39:H58','M39:M58','C59:M60','H62:H80','M62:M80','C81:C82','H83:H93','M83:M93','C94:M95','D37:M37','H5:H95', 'M5:M95','C81:M81'];

// Iterasi melalui setiap sel dan atur gaya dan ukuran font
foreach ($cells as $cell) {
    $style = $sheet->getStyle($cell);
    $font = $style->getFont();
    $font->setBold(true);
}

$cellsToAlignRight = ['C13', 'C20', 'C26', 'C37', 'C59', 'C60', 'C81', 'C94','C95'];

// Loop through each cell and set the alignment to right
foreach ($cellsToAlignRight as $cell) {
    $objPHPExcel->getActiveSheet()->getStyle($cell)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
}

$objPHPExcel->getActiveSheet()->getStyle('H95')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle('H95')->getFill()->getStartColor()->setRGB('FFFF00'); 

$objPHPExcel->getActiveSheet()->getStyle('M95')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle('M95')->getFill()->getStartColor()->setRGB('FFFF00'); 

$objPHPExcel->getActiveSheet()
            ->getStyle('B1')
            ->getAlignment()
            ->setWrapText(true);

            $objPHPExcel->getActiveSheet()
            ->getStyle('D1')
            ->getAlignment()
            ->setWrapText(true);

function setSumFormulas($startRow, $endRow, $formulaColumn, $sumRangeStartColumn, $sumRangeEndColumn, $objPHPExcel) {
    for ($row = $startRow; $row <= $endRow; $row++) {
        $cell = $formulaColumn . $row;
        $sumRange = $sumRangeStartColumn . $row . ':' . $sumRangeEndColumn . $row;
        $objPHPExcel->getActiveSheet()->setCellValue($cell, "=SUM($sumRange)");
    }
}
            setSumFormulas(7, 12, 'H', 'D', 'G', $objPHPExcel);
            setSumFormulas(15, 19, 'H', 'D', 'G', $objPHPExcel);
            setSumFormulas(22, 25, 'H', 'D', 'G', $objPHPExcel);
            setSumFormulas(28, 36, 'H', 'D', 'G', $objPHPExcel);
            setSumFormulas(39, 58, 'H', 'D', 'G', $objPHPExcel);
            setSumFormulas(62, 80, 'H', 'D', 'G', $objPHPExcel);
            setSumFormulas(83, 95, 'H', 'D', 'G', $objPHPExcel);

            setSumFormulas(7, 12, 'M', 'I', 'L', $objPHPExcel);
            setSumFormulas(15, 19, 'M', 'I', 'L', $objPHPExcel);
            setSumFormulas(22, 25, 'M', 'I', 'L', $objPHPExcel);
            setSumFormulas(28, 36, 'M', 'I', 'L', $objPHPExcel);
            setSumFormulas(39, 58, 'M', 'I', 'L', $objPHPExcel);
            setSumFormulas(62, 80, 'M', 'I', 'L', $objPHPExcel);
            setSumFormulas(83, 93, 'M', 'I', 'L', $objPHPExcel);

function setSumFormula($objPHPExcel, $startColumn, $endColumn, $targetRow, $sumRangeStartRow, $sumRangeEndRow) {
    for ($col = $startColumn; $col <= $endColumn; $col++) {
        $cell = $col . $targetRow;
        $sumRange = $col . $sumRangeStartRow . ':' . $col . $sumRangeEndRow;
        $objPHPExcel->getActiveSheet()->setCellValue($cell, "=SUM($sumRange)");
    }
}
            setSumFormula($objPHPExcel, 'D', 'H', 13, 7, 12);
            setSumFormula($objPHPExcel, 'D', 'H', 20, 15, 19);
            setSumFormula($objPHPExcel, 'D', 'H', 26, 22, 25);
            setSumFormula($objPHPExcel, 'D', 'H', 37, 28, 36);
            setSumFormula($objPHPExcel, 'D', 'H', 59, 39, 58);
            setSumFormula($objPHPExcel, 'D', 'H', 81, 62, 80);
            setSumFormula($objPHPExcel, 'D', 'H', 94, 83, 93);

            setSumFormula($objPHPExcel, 'I', 'M', 13, 7, 12);
            setSumFormula($objPHPExcel, 'I', 'M', 20, 15, 19);
            setSumFormula($objPHPExcel, 'I', 'M', 26, 22, 25);
            setSumFormula($objPHPExcel, 'I', 'M', 37, 28, 36);
            setSumFormula($objPHPExcel, 'I', 'M', 59, 39, 58);
            setSumFormula($objPHPExcel, 'I', 'M', 81, 62, 80);
            setSumFormula($objPHPExcel, 'I', 'M', 94, 83, 93);


// Calculate the previous month
$previousMonthTimestamp = mktime(0, 0, 0, $selectedMonth - 1, 10);
$previousMonthName = date('F', $previousMonthTimestamp);
$objPHPExcel->setActiveSheetIndex(1)
            ->setCellValue('B1', "HUMAN RESOURCES DEV.\nPT KAYABA INDONESIA")
            ->setCellValue('D1', "MONTH : $selectedMonthName                                                  Year      :  $selectedYear")
            ->setCellValue('B3', "NO")
            ->setCellValue('C3', "DEPARTEMEN/SECTION")
            ->setCellValue('D3', $previousMonthName)
            ->setCellValue('I3', $selectedMonthName)
            ->setCellValue('D4', 'IN')
            ->setCellValue('E4', 'OUT')
            ->setCellValue('F4', 'Mutasi In')
            ->setCellValue('G4', 'Mutasi Out')
            ->setCellValue('H4', 'Total')
            ->setCellValue('I4', 'IN')
            ->setCellValue('J4', 'OUT')
            ->setCellValue('K4', 'Mutasi In')
            ->setCellValue('L4', 'Mutasi Out')
            ->setCellValue('M4', 'Total')
            ->setCellValue('C5', 'DIRECT LABOUR')
            ->setCellValue('C6', 'PRODUCTION 1')
            ->setCellValue('C13', 'SUB TOTAL PRODUCTION 1')
            ->setCellValue('C14', 'PRODUCTION 2')
            ->setCellValue('C20', 'SUB TOTAL PRODUCTION 2')
            ->setCellValue('C21', 'PRODUCTION 3')
            ->setCellValue('C26', 'SUB TOTAL PRODUCTION 3')
            ->setCellValue('C27', 'PRODUCTION 4')
            ->setCellValue('C37', 'SUB TOTAL PRODUCTION 4')
            ->setCellValue('C38', 'PRODUCTION 5')
            ->setCellValue('C59', 'SUB TOTAL PRODUCTION 5')
            ->setCellValue('C60', 'TOTAL DL')
            ->setCellValue('C61', 'INDIRECT LABOUR')
            ->setCellValue('C81', 'TOTAL IL')
            ->setCellValue('C82', 'GENERAL ADMINISTRATION')
            ->setCellValue('C94', 'TOTAL GA')
            ->setCellValue('C95', 'GRAND TOTAL')
            ->setCellValue('D60', '=D59+D37+D26+D20+D13')
            ->setCellValue('I60', '=I59+I37+I26+I20+I13')
            ->setCellValue('E60', '=E59+E37+E26+E20+E13')
            ->setCellValue('J60', '=J59+J37+J26+J20+J13')
            ->setCellValue('F60', '=F59+F37+F26+F20+F13')
            ->setCellValue('K60', '=K59+K37+K26+K20+K13')
            ->setCellValue('G60', '=G59+G37+G26+G20+G13')
            ->setCellValue('L60', '=L59+L37+L26+L20+L13')
            ->setCellValue('H60', '=H59+H37+H26+H20+H13')
            ->setCellValue('M60', '=M59+M37+M26+M20+M13')
            ->setCellValue('D95', '=D94+D81+D60')
            ->setCellValue('I95', '=I94+I81+I60')
            ->setCellValue('E95', '=E94+E81+E60')
            ->setCellValue('J95', '=J94+J81+J60')
            ->setCellValue('F95', '=F94+F81+F60')
            ->setCellValue('K95', '=K94+K81+K60')
            ->setCellValue('G95', '=G94+G81+G60')
            ->setCellValue('L95', '=L94+L81+L60')
            ->setCellValue('M95', '=SUM(I95:L95)');


function fetchProductionData($koneksi, $cwoc, $start_row, $objPHPExcel, $selectedMonth, $selectedYear, $ExcelMutasiSectbysect, $ExcelMutasiInSectbysect, $ExcelInOutbysect, $ExcelMutasiSectbysectBulanLalu, $ExcelMutasiInSectbysectBulanLalu, $ExcelInOutbysectLalu) {
    $query = "SELECT sect.Id_sect, sect.`desc`, karyawan.sect, karyawan.cwoc
        FROM karyawan
        INNER JOIN sect ON karyawan.sect = sect.Id_sect
        WHERE karyawan.cwoc = '$cwoc'
        GROUP BY sect.Id_sect, sect.`desc`, karyawan.sect, karyawan.cwoc
    ";

    $result = $koneksi->query($query);
    if (!$result) {
        die("Query gagal: " . $koneksi->error);
    }

    $no = 1;

    while ($user_data = $result->fetch_assoc()) {
        $sect = isset($user_data['sect']) ? $user_data['sect'] : null;
        $desc = isset($user_data['desc']) ? $user_data['desc'] : null;
        $MutasiOut = isset($ExcelMutasiSectbysect[$sect]['MutasiOut']) ? $ExcelMutasiSectbysect[$sect]['MutasiOut'] : '';
        $MutasiOutLalu = isset($ExcelMutasiSectbysectBulanLalu[$sect]['MutasiOut']) ? $ExcelMutasiSectbysectBulanLalu[$sect]['MutasiOut'] : '';
        $MutasiIn = isset($ExcelMutasiInSectbysect[$sect]['MutasiIn']) ? $ExcelMutasiInSectbysect[$sect]['MutasiIn'] : '';
        $MutasiInLalu = isset($ExcelMutasiInSectbysectBulanLalu[$sect]['MutasiIn']) ? $ExcelMutasiInSectbysectBulanLalu[$sect]['MutasiIn'] : '';
        $IN = isset($ExcelInOutbysect[$sect]['IN']) ? ($ExcelInOutbysect[$sect]['IN'] != 0 ? $ExcelInOutbysect[$sect]['IN'] : null) : null;
        $OUT = isset($ExcelInOutbysect[$sect]['OUT']) ? $ExcelInOutbysect[$sect]['OUT'] : '';
        $INLalu = isset($ExcelInOutbysectLalu[$sect]['IN']) ? ($ExcelInOutbysectLalu[$sect]['IN'] != 0 ? $ExcelInOutbysectLalu[$sect]['IN'] : null) : null;
        $OUTLalu = isset($ExcelInOutbysectLalu[$sect]['OUT']) ? $ExcelInOutbysectLalu[$sect]['OUT'] : '';

        if ($MutasiOut !== '') {
            $MutasiOut = '-' . $MutasiOut;
        }

        if ($MutasiOutLalu !== '') {
            $MutasiOutLalu = '-' . $MutasiOutLalu;
        }

        if ($OUT === '0') {
            $OUT = '';
        } else if ($OUT !== '') {
            $OUT = '-' . $OUT;
        }

        if ($OUTLalu === '0') {
            $OUTLalu = '';
        } else if ($OUTLalu !== '') {
            $OUTLalu = '-' . $OUTLalu;
        }

        $objPHPExcel->setActiveSheetIndex(1)
                    ->setCellValue('B' . ($no + $start_row - 1), $no)
                    ->setCellValue('C' . ($no + $start_row - 1), $desc)
                    ->setCellValue('D' . ($no + $start_row - 1), $INLalu)
                    ->setCellValue('E' . ($no + $start_row - 1), $OUTLalu)
                    ->setCellValue('F' . ($no + $start_row - 1), $MutasiInLalu)
                    ->setCellValue('G' . ($no + $start_row - 1), $MutasiOutLalu)
                    ->setCellValue('I' . ($no + $start_row - 1), $IN)
                    ->setCellValue('J' . ($no + $start_row - 1), $OUT)
                    ->setCellValue('K' . ($no + $start_row - 1), $MutasiIn)
                    ->setCellValue('L' . ($no + $start_row - 1), $MutasiOut);

        $no++;
    }
}

// Panggil fungsi fetchProductionData untuk setiap grup produksi
fetchProductionData($koneksi, 'PRODUCTION 1', 7, $objPHPExcel, $selectedMonth, $selectedYear, $ExcelMutasiSectbysect, $ExcelMutasiInSectbysect, $ExcelInOutbysect, $ExcelMutasiSectbysectBulanLalu, $ExcelMutasiInSectbysectBulanLalu, $ExcelInOutbysectLalu);
fetchProductionData($koneksi, 'PRODUCTION 2', 15, $objPHPExcel, $selectedMonth, $selectedYear, $ExcelMutasiSectbysect, $ExcelMutasiInSectbysect, $ExcelInOutbysect, $ExcelMutasiSectbysectBulanLalu, $ExcelMutasiInSectbysectBulanLalu, $ExcelInOutbysectLalu);
fetchProductionData($koneksi, 'PRODUCTION 3', 22, $objPHPExcel, $selectedMonth, $selectedYear, $ExcelMutasiSectbysect, $ExcelMutasiInSectbysect, $ExcelInOutbysect, $ExcelMutasiSectbysectBulanLalu, $ExcelMutasiInSectbysectBulanLalu, $ExcelInOutbysectLalu);
fetchProductionData($koneksi, 'PRODUCTION 4', 28, $objPHPExcel, $selectedMonth, $selectedYear, $ExcelMutasiSectbysect, $ExcelMutasiInSectbysect, $ExcelInOutbysect, $ExcelMutasiSectbysectBulanLalu, $ExcelMutasiInSectbysectBulanLalu, $ExcelInOutbysectLalu);
fetchProductionData($koneksi, 'PRODUCTION 5', 39, $objPHPExcel, $selectedMonth, $selectedYear, $ExcelMutasiSectbysect, $ExcelMutasiInSectbysect, $ExcelInOutbysect, $ExcelMutasiSectbysectBulanLalu, $ExcelMutasiInSectbysectBulanLalu, $ExcelInOutbysectLalu);


$resultIL = mysqli_query($koneksi, $queryIL);

$no = 1; // Start number
$start_row = 62; // Start row number

// Looping untuk mengisi nilai sel dalam objek Excel
while ($user_data = mysqli_fetch_array($resultIL)) {
    $sect = isset($user_data['sect']) ? $user_data['sect'] : null; // Inisialisasi $sect
    $cwoc = isset($user_data['cwoc']) ? $user_data['cwoc'] : null;
    $MutasiIn = isset($ExcelMutIn2bycwoc[$cwoc]['MutasiIn']) ? $ExcelMutIn2bycwoc[$cwoc]['MutasiIn'] : '';
    $MutasiInBulanLalu = isset($ExcelMutIn2bycwocBulanLalu[$cwoc]['MutasiIn']) ? $ExcelMutIn2bycwocBulanLalu[$cwoc]['MutasiIn'] : '';
    $MutasiOut = isset($ExcelMutOut2bycwoc[$cwoc]['MutasiOut']) ? $ExcelMutOut2bycwoc[$cwoc]['MutasiOut'] : null;
    $MutasiOutBulanLalu = isset($ExcelMutOut2bycwocBulanLalu[$cwoc]['MutasiOut']) ? $ExcelMutOut2bycwocBulanLalu[$cwoc]['MutasiOut'] : null;
    $IN = isset($ExcelInOut2bycwoc[$cwoc]['IN']) ? ($ExcelInOut2bycwoc[$cwoc]['IN'] != 0 ? $ExcelInOut2bycwoc[$cwoc]['IN'] : null) : null;
    $INBulanLalu = isset($ExcelInOut2bycwocBulanLalu[$cwoc]['IN']) ? ($ExcelInOut2bycwocBulanLalu[$cwoc]['IN'] != 0 ? $ExcelInOut2bycwocBulanLalu[$cwoc]['IN'] : null) : null;
    $OUT = isset($ExcelInOut2bycwoc[$cwoc]['OUT']) ? ($ExcelInOut2bycwoc[$cwoc]['OUT'] != 0 ? $ExcelInOut2bycwoc[$cwoc]['OUT'] : null) : null;
    $OUTBulanLalu = isset($ExcelInOut2bycwocBulanLalu[$cwoc]['OUT']) ? ($ExcelInOut2bycwocBulanLalu[$cwoc]['OUT'] != 0 ? $ExcelInOut2bycwocBulanLalu[$cwoc]['OUT'] : null) : null;

    if ($MutasiOut !== null) {
        $MutasiOut = '-' . $MutasiOut;
    } else {
        $MutasiOut = '';
    }

    if ($MutasiOutBulanLalu !== null) {
        $MutasiOutBulanLalu = '-' . $MutasiOutBulanLalu;
    } else {
        $MutasiOutBulanLalu = '';
    }

    if ($OUT !== null) {
        $OUT = '-' . $OUT;
    } else {
        $OUT = '';
    }

    if ($OUTBulanLalu !== null) {
        $OUTBulanLalu = '-' . $OUTBulanLalu;
    } else {
        $OUTBulanLalu = '';
    }

    // Set cell values
    $objPHPExcel->setActiveSheetIndex(1)
                 ->setCellValue('B' . ($no + $start_row - 1), $no) 
                 ->setCellValue('C' . ($no + $start_row - 1), $cwoc)
                 ->setCellValue('D' . ($no + $start_row - 1), $INBulanLalu)
                 ->setCellValue('E' . ($no + $start_row - 1), $OUTBulanLalu)
                 ->setCellValue('F' . ($no + $start_row - 1), $MutasiInBulanLalu)
                 ->setCellValue('G' . ($no + $start_row - 1), $MutasiOutBulanLalu)
                 ->setCellValue('I' . ($no + $start_row - 1), $IN)
                 ->setCellValue('J' . ($no + $start_row - 1), $OUT)
                 ->setCellValue('K' . ($no + $start_row - 1), $MutasiIn)
                 ->setCellValue('L' . ($no + $start_row - 1), $MutasiOut); 

 $no++; // Increment number
}

$queryIL2 = "SELECT karyawan.cwoc
                FROM karyawan
                INNER JOIN sect ON karyawan.sect = sect.Id_sect
                WHERE karyawan.cwoc IN ('PRODUCTION SYSTEM','PPC','PCE','PE 2W','PE 4W','PDE 2W','PDE 4W','QA','CQE 2W','CQE 4W','GENERAL PURCHASE','PROCUREMENT','VENDOR DEVELOPMENT','WAREHOUSE')
                GROUP BY karyawan.cwoc
                ORDER BY FIELD(karyawan.cwoc, 'PRODUCTION SYSTEM','PPC','PCE','PE 2W','PE 4W','PDE 2W','PDE 4W','QA','CQE 2W','CQE 4W','GENERAL PURCHASE','PROCUREMENT','VENDOR DEVELOPMENT','WAREHOUSE')";


$resultIL2 = mysqli_query($koneksi, $queryIL2);

$no = 6; // Start number
$start_row = 62; // Start row number

// Looping untuk mengisi nilai sel dalam objek Excel
while ($user_data = mysqli_fetch_array($resultIL2)) {
    $cwoc = isset($user_data['cwoc']) ? $user_data['cwoc'] : null;
    $IN = isset($ExcelInOutbycwoc[$cwoc]['IN']) ? ($ExcelInOutbycwoc[$cwoc]['IN'] != 0 ? $ExcelInOutbycwoc[$cwoc]['IN'] : null) : null;
    $INBulanLalu = isset($ExcelInOutbycwocBulanLalu[$cwoc]['IN']) ? ($ExcelInOutbycwocBulanLalu[$cwoc]['IN'] != 0 ? $ExcelInOutbycwocBulanLalu[$cwoc]['IN'] : null) : null;
    $OUT = isset($ExcelInOutbycwoc[$cwoc]['OUT']) ? ($ExcelInOutbycwoc[$cwoc]['OUT'] != 0 ? $ExcelInOutbycwoc[$cwoc]['OUT'] : null) : null;
    $OUTBulanLalu = isset($ExcelInOutbycwocBulanLalu[$cwoc]['OUT']) ? ($ExcelInOutbycwocBulanLalu[$cwoc]['OUT'] != 0 ? $ExcelInOutbycwocBulanLalu[$cwoc]['OUT'] : null) : null;
    $MutasiIn = isset($ExcelMutInbycwoc[$cwoc]['MutasiIn']) ? $ExcelMutInbycwoc[$cwoc]['MutasiIn'] : '';
    $MutasiInBulanLalu = isset($ExcelMutInbycwocBulanLalu[$cwoc]['MutasiIn']) ? $ExcelMutInbycwocBulanLalu[$cwoc]['MutasiIn'] : '';
    $MutasiOut = isset($ExcelMutOutbycwoc[$cwoc]['MutasiOut']) ? $ExcelMutOutbycwoc[$cwoc]['MutasiOut'] : null;
    $MutasiOutBulanLalu = isset($ExcelMutOutbycwocBulanLalu[$cwoc]['MutasiOut']) ? $ExcelMutOutbycwocBulanLalu[$cwoc]['MutasiOut'] : null;

    if ($OUT !== null) {
        $OUT = '-' . $OUT;
    } else {
        $OUT = '';
    }

    if ($OUTBulanLalu !== null) {
        $OUTBulanLalu = '-' . $OUTBulanLalu;
    } else {
        $OUTBulanLalu = '';
    }

    if ($MutasiOut !== null) {
        $MutasiOut = '-' . $MutasiOut;
    } else {
        $MutasiOut = '';
    }

    if ($MutasiOutBulanLalu !== null) {
        $MutasiOutBulanLalu = '-' . $MutasiOutBulanLalu;
    } else {
        $MutasiOutBulanLalu = '';
    }

    // Set cell values
    $objPHPExcel->setActiveSheetIndex(1)
                 ->setCellValue('B' . ($no + $start_row - 1), $no)
                 ->setCellValue('C' . ($no + $start_row - 1), $cwoc)
                 ->setCellValue('D' . ($no + $start_row - 1), $INBulanLalu)
                 ->setCellValue('E' . ($no + $start_row - 1), $OUTBulanLalu)
                 ->setCellValue('F' . ($no + $start_row - 1), $MutasiInBulanLalu)
                 ->setCellValue('G' . ($no + $start_row - 1), $MutasiOutBulanLalu)
                 ->setCellValue('I' . ($no + $start_row - 1), $IN)
                 ->setCellValue('J' . ($no + $start_row - 1), $OUT)
                 ->setCellValue('K' . ($no + $start_row - 1), $MutasiIn)
                 ->setCellValue('L' . ($no + $start_row - 1), $MutasiOut);

 $no++; // Increment number
}

$queryGA = "SELECT karyawan.cwoc
FROM karyawan
INNER JOIN sect ON karyawan.sect = sect.Id_sect
WHERE karyawan.cwoc IN ('FINANCE ACCOUNTING', 'PLANNING BUDGETING', 'HRD IR', 'GA', 'EHS','COMITTEE QCC - SS', 'PDCA CPC', 'MARKETING', 'MIS', 'BOD INDONESIA', 'BOD TA EXP')
GROUP BY karyawan.cwoc
ORDER BY FIELD(karyawan.cwoc, 
'FINANCE ACCOUNTING', 'PLANNING BUDGETING', 'HRD IR', 'GA', 'EHS',
    'COMITTEE QCC - SS', 'PDCA CPC', 'MARKETING', 'MIS', 'BOD INDONESIA', 'BOD TA EXP');
";


$resultGA = mysqli_query($koneksi, $queryGA);

$no = 1; // Inisialisasi variabel no
$start_row = 83; // Inisialisasi variabel start_row dengan nilai yang sesuai

while ($user_data = mysqli_fetch_array($resultGA)) {
    $cwoc = isset($user_data['cwoc']) ? $user_data['cwoc'] : null;
    $IN = isset($ExcelInOutbycwoc[$cwoc]['IN']) ? ($ExcelInOutbycwoc[$cwoc]['IN'] != 0 ? $ExcelInOutbycwoc[$cwoc]['IN'] : null) : null;
    $INBulanLalu = isset($ExcelInOutbycwocBulanLalu[$cwoc]['IN']) ? ($ExcelInOutbycwocBulanLalu[$cwoc]['IN'] != 0 ? $ExcelInOutbycwocBulanLalu[$cwoc]['IN'] : null) : null;
    $OUT = isset($ExcelInOutbycwoc[$cwoc]['OUT']) ? ($ExcelInOutbycwoc[$cwoc]['OUT'] != 0 ? $ExcelInOutbycwoc[$cwoc]['OUT'] : null) : null;
    $OUTBulanLalu = isset($ExcelInOutbycwocBulanLalu[$cwoc]['OUT']) ? ($ExcelInOutbycwocBulanLalu[$cwoc]['OUT'] != 0 ? $ExcelInOutbycwocBulanLalu[$cwoc]['OUT'] : null) : null;
    $MutasiIn = isset($ExcelMutInbycwoc[$cwoc]['MutasiIn']) ? $ExcelMutInbycwoc[$cwoc]['MutasiIn'] : '';
    $MutasiInBulanLalu = isset($ExcelMutInbycwocBulanLalu[$cwoc]['MutasiIn']) ? $ExcelMutInbycwocBulanLalu[$cwoc]['MutasiIn'] : '';
    $MutasiOut = isset($ExcelMutOutbycwoc[$cwoc]['MutasiOut']) ? $ExcelMutOutbycwoc[$cwoc]['MutasiOut'] : null;
    $MutasiOutBulanLalu = isset($ExcelMutOutbycwocBulanLalu[$cwoc]['MutasiOut']) ? $ExcelMutOutbycwocBulanLalu[$cwoc]['MutasiOut'] : null;

    if ($OUT !== null) {
        $OUT = '-' . $OUT;
    } else {
        $OUT = '';
    }

    if ($OUTBulanLalu !== null) {
        $OUTBulanLalu = '-' . $OUTBulanLalu;
    } else {
        $OUTBulanLalu = '';
    }

    if ($MutasiOut !== null) {
        $MutasiOut = '-' . $MutasiOut;
    } else {
        $MutasiOut = '';
    }

    if ($MutasiOutBulanLalu !== null) {
        $MutasiOutBulanLalu = '-' . $MutasiOutBulanLalu;
    } else {
        $MutasiOutBulanLalu = '';
    }

    // Mengganti nilai cwoc dengan menggunakan str_replace
    $display_text = str_replace(
        ['BOD TA EXP', 'HRD IR', 'FINANCE ACCOUNTING', 'PLANNING BUDGETING', 'PDCA CPC'],
        ['BOD & TA EXP', 'HRD & IR', 'FINANCE & ACCOUNTING', 'PLANNING & BUDGETING', 'PDCA & CPC'],
        $cwoc
    );

    // Set cell values
    $objPHPExcel->setActiveSheetIndex(1)
                 ->setCellValue('B' . ($no + $start_row - 1), $no)
                 ->setCellValue('C' . ($no + $start_row - 1), $cwoc)
                 ->setCellValue('D' . ($no + $start_row - 1), $INBulanLalu)
                 ->setCellValue('E' . ($no + $start_row - 1), $OUTBulanLalu)
                 ->setCellValue('F' . ($no + $start_row - 1), $MutasiInBulanLalu)
                 ->setCellValue('G' . ($no + $start_row - 1), $MutasiOutBulanLalu)
                 ->setCellValue('I' . ($no + $start_row - 1), $IN)
                 ->setCellValue('J' . ($no + $start_row - 1), $OUT)
                 ->setCellValue('K' . ($no + $start_row - 1), $MutasiIn)
                 ->setCellValue('L' . ($no + $start_row - 1), $MutasiOut);

    $no++; // Increment number

}

$nama_file = "Daftar Mutasi_$selectedMonthName $selectedYear.xls";

$objPHPExcel->setActiveSheetIndex(0);

// Set header dengan nama file yang telah dibuat
header("Content-Disposition: attachment;filename=\"$nama_file\"");
header('Content-Type: application/vnd.ms-excel'); // Mengatur tipe konten untuk format xls
header('Cache-Control: max-age=0');

// Menyimpan file dalam format Excel 97-2003 (.xls) dan mengirimkannya ke output
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
?>