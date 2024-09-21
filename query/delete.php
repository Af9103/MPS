<?php
include __DIR__ . '/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['IdMutasi']) && !empty($_POST['batchMutasi'])) {
    $IdMutasi = $_POST['IdMutasi'];
    $batchMutasi = $_POST['batchMutasi'];

    // Sanitize input
    $batchMutasi = $koneksi3->real_escape_string($batchMutasi);
    $IdMutasi = array_map('intval', $IdMutasi);
    $IdMutasiList = implode(',', $IdMutasi);

    // Prepare and execute query
    $query = "DELETE FROM mutasi WHERE IdMutasi IN ($IdMutasiList)";
    if ($koneksi3->query($query)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $koneksi3->error]);
    }
}
?>