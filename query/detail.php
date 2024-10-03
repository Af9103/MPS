<?php
include __DIR__ . '/koneksi.php';

if (isset($_GET['batchMutasi'])) {
    $batchMutasi = $koneksi3->real_escape_string($_GET['batchMutasi']);

    // Query untuk mendapatkan cwocAsal dan cwocBaru berdasarkan batchMutasi
    $query = "SELECT cwocAsal, cwocBaru, sectAsal, sectBaru, FM, SPV, Kadept1 FROM mutasi WHERE batchMutasi = '$batchMutasi'";
    $result = $koneksi3->query($query);

    $data = [
        'emnos' => array(),
        'IdMutasi' => array(),
        'cwocAsal' => '',
        'cwocBaru' => '',
        'sectAsal' => '',
        'FM' => '',
        'SPV' => '',
        'Kadept1' => '',
        'sectBaru' => '',
        'sectAsalDesc' => '',
        'sectBaruDesc' => '',
        'status' => array(),
        'nama' => array(),
        'tanggalBuat' => array(),
        'tanggalMutasi' => array(),
        'reject' => array(),
        'batchMutasi' => array(),
        'error' => ''
    ];

    if ($result && $row = $result->fetch_assoc()) {
        $data['cwocAsal'] = $row['cwocAsal'];
        $data['cwocBaru'] = $row['cwocBaru'];
        $data['sectAsal'] = $row['sectAsal'];
        $data['sectBaru'] = $row['sectBaru'];
        $data['FM'] = $row['FM'];
        $data['SPV'] = $row['SPV'];
        $data['Kadept1'] = $row['Kadept1'];


        // Get sectAsal description
        $querySectAsal = "SELECT `desc` AS sectAsalDesc FROM hrd_sect WHERE sect = '" . $data['sectAsal'] . "'";
        $resultSectAsal = $koneksi2->query($querySectAsal);
        $sectAsalData = $resultSectAsal->fetch_assoc();
        $data['sectAsalDesc'] = isset($sectAsalData['sectAsalDesc']) ? $sectAsalData['sectAsalDesc'] : 'N/A';

        // Get sectBaru description
        $querySectBaru = "SELECT `desc` AS sectBaruDesc FROM hrd_sect WHERE sect = '" . $data['sectBaru'] . "'";
        $resultSectBaru = $koneksi2->query($querySectBaru);
        $sectBaruData = $resultSectBaru->fetch_assoc();
        $data['sectBaruDesc'] = isset($sectBaruData['sectBaruDesc']) ? $sectBaruData['sectBaruDesc'] : 'N/A';

        // Query untuk mendapatkan emno dan status berdasarkan batchMutasi
        $queryEmno = "SELECT IdMutasi, emno, status, nama, tanggalBuat, tanggalMutasi, reject, batchMutasi FROM mutasi WHERE batchMutasi = '$batchMutasi' AND status !='100'";
        $resultEmno = $koneksi3->query($queryEmno);

        if ($resultEmno) {
            while ($emnoRow = $resultEmno->fetch_assoc()) {
                $data['emnos'][] = $emnoRow['emno'];
                $data['status'][] = $emnoRow['status'];
                $data['nama'][] = $emnoRow['nama'];
                $data['tanggalBuat'][] = $emnoRow['tanggalBuat'];
                $data['tanggalMutasi'][] = $emnoRow['tanggalMutasi'];
                $data['IdMutasi'][] = $emnoRow['IdMutasi'];
                $data['reject'][] = $emnoRow['reject'];
                $data['batchMutasi'][] = $emnoRow['batchMutasi'];
            }
        } else {
            $data['error'] = 'Error fetching emno data.';
        }
    } else {
        $data['error'] = 'Error fetching main data.';
    }

    // Function to get the full name of an approver
    function getFullName($npk, $koneksi2)
    {
        // Check if the npk is empty; if so, return 'N/A'
        if (empty($npk)) {
            return 'N/A';
        }

        // Query to get the full name based on the npk
        $queryFullName = "SELECT full_name FROM ct_users WHERE npk = '$npk'";
        $resultFullName = mysqli_query($koneksi2, $queryFullName);
        $fullNameData = mysqli_fetch_assoc($resultFullName);

        // Return the full name if found; otherwise, return the npk
        return isset($fullNameData['full_name']) ? $fullNameData['full_name'] : $npk;
    }

    // Initialize the array for the second table data
    $data['approvals'] = array();

    // Query to get the approval details
    $queryApprovals = "SELECT cwocAsal, cwocBaru, status, Req, tanggalBuat, FM, tgl_fm, SPV, tgl_spv, Kadept1, tgl_kadept1, Kadept2, tgl_kadept2, Kadiv1, tgl_kadiv1, Kadiv2, tgl_kadiv2, Direktur, tgl_direktur, HRD, tgl_apv_hrd FROM mutasi WHERE batchMutasi = '$batchMutasi'";
    $resultApprovals = $koneksi3->query($queryApprovals);

    if ($resultApprovals && $rowApprovals = $resultApprovals->fetch_assoc()) {
        $FM_fullName = getFullName($rowApprovals['FM'], $koneksi2);
        $SPV_fullName = getFullName($rowApprovals['SPV'], $koneksi2);
        $Kadept1_fullName = getFullName($rowApprovals['Kadept1'], $koneksi2);
        $Kadept2_fullName = getFullName($rowApprovals['Kadept2'], $koneksi2);
        $Kadiv1_fullName = getFullName($rowApprovals['Kadiv1'], $koneksi2);
        $Kadiv2_fullName = getFullName($rowApprovals['Kadiv2'], $koneksi2);
        $Kadiv2_fullName = getFullName($rowApprovals['Kadiv2'], $koneksi2);
        $Direktur_fullName = getFullName($rowApprovals['Direktur'], $koneksi2);

        $kelompok1 = ['QA', 'PDE 2W', 'PDE 4W', 'CQE 2W', 'CQE 4W'];
        $kelompok2 = ['HRD IR', 'GA', 'MIS'];
        $kelompok3 = ['PCE', 'PE 2W', 'PE 4W'];
        $kelompok4 = ['MARKETING', 'PROCUREMENT', 'VENDOR DEVELOPMENT', 'GENERAL PURCHASE'];
        $kelompok5 = ['WAREHOUSE', 'PRODUCTION SYSTEM', 'PPC'];
        $kelompok6 = ['PRODUCTION 1', 'PRODUCTION 2', 'PRODUCTION 3', 'PRODUCTION 4', 'PRODUCTION 5'];

        // Determine division based on cwocAsal
        if (in_array($rowApprovals['cwocAsal'], $kelompok1)) {
            $divisi = "Quality Assurance";
        } elseif (in_array($rowApprovals['cwocAsal'], $kelompok2)) {
            $divisi = "HRGA & MIS";
        } elseif (in_array($rowApprovals['cwocAsal'], $kelompok3)) {
            $divisi = "Engineering";
        } elseif (in_array($rowApprovals['cwocAsal'], $kelompok4)) {
            $divisi = "Marketing & Procurement";
        } elseif (in_array($rowApprovals['cwocAsal'], $kelompok5)) {
            $divisi = "Production Control";
        } elseif (in_array($rowApprovals['cwocAsal'], $kelompok6)) {
            $divisi = "Production";
        } else {
            $divisi = "Non Divisi"; // Fallback if none matches
        }

        // Determine division based on cwocBaru
        if (in_array($rowApprovals['cwocBaru'], $kelompok1)) {
            $divisiBaru = "Quality Assurance";
        } elseif (in_array($rowApprovals['cwocBaru'], $kelompok2)) {
            $divisiBaru = "HRGA & MIS";
        } elseif (in_array($rowApprovals['cwocBaru'], $kelompok3)) {
            $divisiBaru = "Engineering";
        } elseif (in_array($rowApprovals['cwocBaru'], $kelompok4)) {
            $divisiBaru = "Marketing & Procurement";
        } elseif (in_array($rowApprovals['cwocBaru'], $kelompok5)) {
            $divisiBaru = "Production Control";
        } elseif (in_array($rowApprovals['cwocBaru'], $kelompok6)) {
            $divisiBaru = "Production";
        } else {
            $divisiBaru = "Non Divisi"; // Fallback if none matches
        }


        if ($rowApprovals['status'] == 2) {
            $data['approvals'][] = [
                'status2' => 'Diajukan',
                'approver' => $rowApprovals['Req'],
                'date' => $rowApprovals['tanggalBuat']
            ];

        } elseif ($rowApprovals['status'] == 3) {
            $data['approvals'][] = [
                'status2' => 'Diajukan',
                'approver' => $rowApprovals['Req'],
                'date' => $rowApprovals['tanggalBuat']
            ];
            if ($FM_fullName !== 'N/A' && !is_null($rowApprovals['tgl_fm'])) {
                $data['approvals'][] = [
                    'status2' => 'Disetujui FM',
                    'approver' => $FM_fullName,
                    'date' => $rowApprovals['tgl_fm']
                ];
            }

        } elseif ($rowApprovals['status'] == 4) {
            $data['approvals'][] = [
                'status2' => 'Diajukan',
                'approver' => $rowApprovals['Req'],
                'date' => $rowApprovals['tanggalBuat']
            ];

            if ($FM_fullName !== 'N/A' && !is_null($rowApprovals['tgl_fm'])) {
                $data['approvals'][] = [
                    'status2' => 'Disetujui FM',
                    'approver' => $FM_fullName,
                    'date' => $rowApprovals['tgl_fm']
                ];
            }
            if ($SPV_fullName !== 'N/A' && !is_null($rowApprovals['tgl_spv'])) {
                $data['approvals'][] = [
                    'status2' => 'Disetujui SPV',
                    'approver' => $SPV_fullName,
                    'date' => $rowApprovals['tgl_spv']
                ];
            }


        } elseif ($rowApprovals['status'] == 5) {
            // Selalu tampilkan data dengan status 'Diajukan'
            $data['approvals'][] = [
                'status2' => 'Diajukan',
                'approver' => $rowApprovals['Req'],
                'date' => $rowApprovals['tanggalBuat']
            ];

            if ($FM_fullName !== 'N/A' && !is_null($rowApprovals['tgl_fm'])) {
                $data['approvals'][] = [
                    'status2' => 'Disetujui FM',
                    'approver' => $FM_fullName,
                    'date' => $rowApprovals['tgl_fm']
                ];
            }

            if ($SPV_fullName !== 'N/A' && !is_null($rowApprovals['tgl_spv'])) {
                $data['approvals'][] = [
                    'status2' => 'Disetujui SPV',
                    'approver' => $SPV_fullName,
                    'date' => $rowApprovals['tgl_spv']
                ];
            }

            if ($Kadept1_fullName !== 'N/A' && !is_null($rowApprovals['tgl_kadept1'])) {
                $data['approvals'][] = [
                    'status2' => 'Disetujui Kepala Departemen ' . $rowApprovals['cwocAsal'],
                    'approver' => $Kadept1_fullName,
                    'date' => $rowApprovals['tgl_kadept1']
                ];
            }


        } elseif ($rowApprovals['status'] == 6) {
            $data['approvals'][] = [
                'status2' => 'Diajukan',
                'approver' => $rowApprovals['Req'],
                'date' => $rowApprovals['tanggalBuat']
            ];
            if ($FM_fullName !== 'N/A' && !is_null($rowApprovals['tgl_fm'])) {
                $data['approvals'][] = [
                    'status2' => 'Disetujui FM',
                    'approver' => $FM_fullName,
                    'date' => $rowApprovals['tgl_fm']
                ];
            }

            if ($SPV_fullName !== 'N/A' && !is_null($rowApprovals['tgl_spv'])) {
                $data['approvals'][] = [
                    'status2' => 'Disetujui SPV',
                    'approver' => $SPV_fullName,
                    'date' => $rowApprovals['tgl_spv']
                ];
            }
            if ($Kadept1_fullName !== 'N/A' && $Kadept2_fullName !== 'N/A' && !is_null($rowApprovals['tgl_kadept1']) && !is_null($rowApprovals['tgl_kadept2'])) {
                if ($Kadept1_fullName !== $Kadept2_fullName) {
                    // Jika nama Kadept1 dan Kadept2 berbeda, tampilkan keduanya
                    $data['approvals'][] = [
                        'status2' => 'Disetujui Kepala Departemen ' . $rowApprovals['cwocAsal'],
                        'approver' => $Kadept1_fullName,
                        'date' => $rowApprovals['tgl_kadept1']
                    ];
                    $data['approvals'][] = [
                        'status2' => 'Disetujui Kepala Departemen ' . $rowApprovals['cwocBaru'],
                        'approver' => $Kadept2_fullName,
                        'date' => $rowApprovals['tgl_kadept2']
                    ];
                } else {
                    // Jika nama Kadept1 dan Kadept2 sama, hanya tampilkan untuk cwocBaru
                    $data['approvals'][] = [
                        'status2' => 'Disetujui Kepala Departemen ' . $rowApprovals['cwocBaru'],
                        'approver' => $Kadept2_fullName,
                        'date' => $rowApprovals['tgl_kadept2']
                    ];
                }
            }


        } elseif ($rowApprovals['status'] == 7) {
            $data['approvals'][] = [
                'status2' => 'Diajukan',
                'approver' => $rowApprovals['Req'],
                'date' => $rowApprovals['tanggalBuat']
            ];
            if ($FM_fullName !== 'N/A' && !is_null($rowApprovals['tgl_fm'])) {
                $data['approvals'][] = [
                    'status2' => 'Disetujui FM',
                    'approver' => $FM_fullName,
                    'date' => $rowApprovals['tgl_fm']
                ];
            }

            if ($SPV_fullName !== 'N/A' && !is_null($rowApprovals['tgl_spv'])) {
                $data['approvals'][] = [
                    'status2' => 'Disetujui SPV',
                    'approver' => $SPV_fullName,
                    'date' => $rowApprovals['tgl_spv']
                ];
            }
            if ($Kadept1_fullName !== 'N/A' && $Kadept2_fullName !== 'N/A' && !is_null($rowApprovals['tgl_kadept1']) && !is_null($rowApprovals['tgl_kadept2'])) {
                if ($Kadept1_fullName !== $Kadept2_fullName) {
                    // Jika nama Kadept1 dan Kadept2 berbeda, tampilkan keduanya
                    $data['approvals'][] = [
                        'status2' => 'Disetujui Kepala Departemen ' . $rowApprovals['cwocAsal'],
                        'approver' => $Kadept1_fullName,
                        'date' => $rowApprovals['tgl_kadept1']
                    ];
                    $data['approvals'][] = [
                        'status2' => 'Disetujui Kepala Departemen ' . $rowApprovals['cwocBaru'],
                        'approver' => $Kadept2_fullName,
                        'date' => $rowApprovals['tgl_kadept2']
                    ];
                } else {
                    // Jika nama Kadept1 dan Kadept2 sama, hanya tampilkan untuk cwocBaru
                    $data['approvals'][] = [
                        'status2' => 'Disetujui Kepala Departemen ' . $rowApprovals['cwocBaru'],
                        'approver' => $Kadept2_fullName,
                        'date' => $rowApprovals['tgl_kadept2']
                    ];
                }
            }

            if ($Kadiv1_fullName !== 'N/A' && !is_null($rowApprovals['tgl_kadiv1'])) {
                $data['approvals'][] = [
                    'status2' => 'Disetujui Kepala Divisi ' . $divisi,
                    'approver' => $Kadiv1_fullName,
                    'date' => $rowApprovals['tgl_kadiv1']
                ];
            }

        } elseif ($rowApprovals['status'] == 8) {
            $data['approvals'][] = [
                'status2' => 'Diajukan',
                'approver' => $rowApprovals['Req'],
                'date' => $rowApprovals['tanggalBuat']
            ];
            if ($FM_fullName !== 'N/A' && !is_null($rowApprovals['tgl_fm'])) {
                $data['approvals'][] = [
                    'status2' => 'Disetujui FM',
                    'approver' => $FM_fullName,
                    'date' => $rowApprovals['tgl_fm']
                ];
            }

            if ($SPV_fullName !== 'N/A' && !is_null($rowApprovals['tgl_spv'])) {
                $data['approvals'][] = [
                    'status2' => 'Disetujui SPV',
                    'approver' => $SPV_fullName,
                    'date' => $rowApprovals['tgl_spv']
                ];
            }
            if ($Kadept1_fullName !== 'N/A' && $Kadept2_fullName !== 'N/A' && !is_null($rowApprovals['tgl_kadept1']) && !is_null($rowApprovals['tgl_kadept2'])) {
                if ($Kadept1_fullName !== $Kadept2_fullName) {
                    // Jika nama Kadept1 dan Kadept2 berbeda, tampilkan keduanya
                    $data['approvals'][] = [
                        'status2' => 'Disetujui Kepala Departemen ' . $rowApprovals['cwocAsal'],
                        'approver' => $Kadept1_fullName,
                        'date' => $rowApprovals['tgl_kadept1']
                    ];
                    $data['approvals'][] = [
                        'status2' => 'Disetujui Kepala Departemen ' . $rowApprovals['cwocBaru'],
                        'approver' => $Kadept2_fullName,
                        'date' => $rowApprovals['tgl_kadept2']
                    ];
                } else {
                    // Jika nama Kadept1 dan Kadept2 sama, hanya tampilkan untuk cwocBaru
                    $data['approvals'][] = [
                        'status2' => 'Disetujui Kepala Departemen ' . $rowApprovals['cwocBaru'],
                        'approver' => $Kadept2_fullName,
                        'date' => $rowApprovals['tgl_kadept2']
                    ];
                }
            }

            if ($Kadiv1_fullName !== 'N/A' && $Kadiv2_fullName !== 'N/A' && !is_null($rowApprovals['tgl_kadiv1']) && !is_null($rowApprovals['tgl_kadiv2'])) {
                if ($Kadiv1_fullName !== $Kadiv2_fullName) {
                    // Jika nama Kadiv1 dan Kadiv2 berbeda, tampilkan keduanya
                    $data['approvals'][] = [
                        'status2' => 'Disetujui Kepala Divisi ' . $divisi,
                        'approver' => $Kadiv1_fullName,
                        'date' => $rowApprovals['tgl_kadiv1']
                    ];
                    $data['approvals'][] = [
                        'status2' => 'Disetujui Kepala Divisi ' . $divisiBaru,
                        'approver' => $Kadiv2_fullName,
                        'date' => $rowApprovals['tgl_kadiv2']
                    ];
                } else {
                    // Jika nama Kadiv1 dan Kadiv2 sama, hanya tampilkan untuk divisiBaru
                    $data['approvals'][] = [
                        'status2' => 'Disetujui Kepala Divisi ' . $divisiBaru,
                        'approver' => $Kadiv2_fullName,
                        'date' => $rowApprovals['tgl_kadiv2']
                    ];
                }
            }

        } elseif ($rowApprovals['status'] == 9) {
            $data['approvals'][] = [
                'status2' => 'Diajukan',
                'approver' => $rowApprovals['Req'],
                'date' => $rowApprovals['tanggalBuat']
            ];
            if ($FM_fullName !== 'N/A' && !is_null($rowApprovals['tgl_fm'])) {
                $data['approvals'][] = [
                    'status2' => 'Disetujui FM',
                    'approver' => $FM_fullName,
                    'date' => $rowApprovals['tgl_fm']
                ];
            }

            if ($SPV_fullName !== 'N/A' && !is_null($rowApprovals['tgl_spv'])) {
                $data['approvals'][] = [
                    'status2' => 'Disetujui SPV',
                    'approver' => $SPV_fullName,
                    'date' => $rowApprovals['tgl_spv']
                ];
            }
            if ($Kadept1_fullName !== 'N/A' && $Kadept2_fullName !== 'N/A' && !is_null($rowApprovals['tgl_kadept1']) && !is_null($rowApprovals['tgl_kadept2'])) {
                if ($Kadept1_fullName !== $Kadept2_fullName) {
                    // Jika nama Kadept1 dan Kadept2 berbeda, tampilkan keduanya
                    $data['approvals'][] = [
                        'status2' => 'Disetujui Kepala Departemen ' . $rowApprovals['cwocAsal'],
                        'approver' => $Kadept1_fullName,
                        'date' => $rowApprovals['tgl_kadept1']
                    ];
                    $data['approvals'][] = [
                        'status2' => 'Disetujui Kepala Departemen ' . $rowApprovals['cwocBaru'],
                        'approver' => $Kadept2_fullName,
                        'date' => $rowApprovals['tgl_kadept2']
                    ];
                } else {
                    // Jika nama Kadept1 dan Kadept2 sama, hanya tampilkan untuk cwocBaru
                    $data['approvals'][] = [
                        'status2' => 'Disetujui Kepala Departemen ' . $rowApprovals['cwocBaru'],
                        'approver' => $Kadept2_fullName,
                        'date' => $rowApprovals['tgl_kadept2']
                    ];
                }
            }

            if ($Kadiv1_fullName !== 'N/A' && $Kadiv2_fullName !== 'N/A' && !is_null($rowApprovals['tgl_kadiv1']) && !is_null($rowApprovals['tgl_kadiv2'])) {
                if ($Kadiv1_fullName !== $Kadiv2_fullName) {
                    // Jika nama Kadiv1 dan Kadiv2 berbeda, tampilkan keduanya
                    $data['approvals'][] = [
                        'status2' => 'Disetujui Kepala Divisi ' . $divisi,
                        'approver' => $Kadiv1_fullName,
                        'date' => $rowApprovals['tgl_kadiv1']
                    ];
                    $data['approvals'][] = [
                        'status2' => 'Disetujui Kepala Divisi ' . $divisiBaru,
                        'approver' => $Kadiv2_fullName,
                        'date' => $rowApprovals['tgl_kadiv2']
                    ];
                } else {
                    // Jika nama Kadiv1 dan Kadiv2 sama, hanya tampilkan untuk divisiBaru
                    $data['approvals'][] = [
                        'status2' => 'Disetujui Kepala Divisi ' . $divisiBaru,
                        'approver' => $Kadiv2_fullName,
                        'date' => $rowApprovals['tgl_kadiv2']
                    ];
                }
            }
            if ($Direktur_fullName !== 'N/A' && !is_null($rowApprovals['tgl_direktur'])) {
                $data['approvals'][] = [
                    'status2' => 'Disetujui Direktur',
                    'approver' => $Direktur_fullName,
                    'date' => $rowApprovals['tgl_direktur']
                ];
            }
        }
    }

    $queryRemarks = "SELECT COUNT(*) as remarkCount FROM remarks WHERE batchMutasi = '$batchMutasi'";
    $resultRemarks = $koneksi3->query($queryRemarks);

    if ($resultRemarks) {
        $rowRemarks = $resultRemarks->fetch_assoc();
        $data['hasRemarks'] = $rowRemarks['remarkCount'] > 0;  // true if exists, false otherwise
    } else {
        $data['hasRemarks'] = false;  // In case of query failure
    }


    header('Content-Type: application/json');
    echo json_encode($data);

}
?>