<?php
include __DIR__ . '/koneksi.php'; // Ensure the connection file is correctly included

$batchMutasi = $_GET['batchMutasi'];

// Escape the input to prevent SQL injection
$batchMutasi = mysqli_real_escape_string($koneksi3, $batchMutasi);

$response = array('data' => array());

// Fetch latest remark for each 'by'
$queryRemark = "SELECT remark, `by`, `date` 
    FROM remarks 
    WHERE batchMutasi = '$batchMutasi' 
    ORDER BY `date` DESC";
$resultRemark = mysqli_query($koneksi3, $queryRemark);

$remarks = array();
if ($resultRemark) {
    while ($row = mysqli_fetch_assoc($resultRemark)) {
        $remarks[$row['by']] = $row; // Keep latest remark for each 'by'
    }
}

// Fetch latest feedback for each 'oleh'
$queryFeedback = "SELECT feedback, oleh, `date` 
    FROM feedbacks 
    WHERE batchMutasi = '$batchMutasi' 
    ORDER BY `date` DESC";
$resultFeedback = mysqli_query($koneksi3, $queryFeedback);

$feedbacks = array();
if ($resultFeedback) {
    while ($row = mysqli_fetch_assoc($resultFeedback)) {
        $feedbacks[$row['oleh']] = $row; // Keep latest feedback for each 'oleh'
    }
}

// Prepare array to hold all 'by' and 'oleh' for full_name lookup
$usersToLookup = array_merge(array_keys($remarks), array_keys($feedbacks));

// Remove duplicates (PHP 5.3 doesn't support shorthand, so we use the full syntax)
$usersToLookup = array_unique($usersToLookup);

// Query to get full_name for all 'by' and 'oleh'
$fullNames = array();
if (!empty($usersToLookup)) {
    $placeholders = implode("','", $usersToLookup);

    // Query full_name based on 'npk'
    $queryFullName = "SELECT npk, full_name FROM ct_users WHERE npk IN ('$placeholders')";
    $resultFullName = mysqli_query($koneksi2, $queryFullName);

    if ($resultFullName) {
        while ($row = mysqli_fetch_assoc($resultFullName)) {
            $fullNames[$row['npk']] = $row['full_name'];
        }
    }
}

// Combine remarks and feedbacks into a single array
foreach ($remarks as $by => $remark) {
    $name = isset($fullNames[$by]) ? $fullNames[$by] : $by; // Use full_name or fallback to 'by'

    if (isset($feedbacks[$by])) {
        // If there's a matching feedback for the same 'by'/'oleh', combine them
        $response['data'][] = array(
            'message' => "memberikan remark " . $remark['remark'] . " dan feedback " . $feedbacks[$by]['feedback'],
            'by' => $name, // Use the full name or fallback
            'date' => max($remark['date'], $feedbacks[$by]['date']) // Use the latest date
        );
        unset($feedbacks[$by]); // Remove matched feedback
    } else {
        // If there's no matching feedback
        $response['data'][] = array(
            'message' => "memberikan remark " . $remark['remark'],
            'by' => $name, // Use the full name or fallback
            'date' => $remark['date']
        );
    }
}

// Add remaining feedbacks without remarks
foreach ($feedbacks as $oleh => $feedback) {
    $name = isset($fullNames[$oleh]) ? $fullNames[$oleh] : $oleh; // Use full_name or fallback to 'oleh'

    $response['data'][] = array(
        'message' => "memberikan feedback " . $feedback['feedback'],
        'by' => $name, // Use the full name or fallback
        'date' => $feedback['date']
    );
}

echo json_encode($response);

mysqli_close($koneksi3); // Close the connection
?>