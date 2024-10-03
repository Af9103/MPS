<?php
include __DIR__ . '/koneksi.php'; // Ensure the connection file is correctly included

$batchMutasi = $_GET['batchMutasi'];

// Escape the input to prevent SQL injection
$batchMutasi = mysqli_real_escape_string($koneksi3, $batchMutasi);

$response = ['data' => []];

// Fetch latest remark for each 'by'
$queryRemark = "SELECT remark, `by`, `date` 
    FROM remarks 
    WHERE batchMutasi = '$batchMutasi' 
    ORDER BY `date` DESC";
$resultRemark = mysqli_query($koneksi3, $queryRemark);

$remarks = [];
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

$feedbacks = [];
if ($resultFeedback) {
    while ($row = mysqli_fetch_assoc($resultFeedback)) {
        $feedbacks[$row['oleh']] = $row; // Keep latest feedback for each 'oleh'
    }
}

// Combine remarks and feedbacks into a single array
foreach ($remarks as $by => $remark) {
    if (isset($feedbacks[$by])) {
        // If there's a matching feedback for the same 'by'/'oleh', combine them
        $response['data'][] = [
            'message' => "memberikan remark " . $remark['remark'] . " dan feedback " . $feedbacks[$by]['feedback'],
            'by' => $by,
            'date' => max($remark['date'], $feedbacks[$by]['date']) // Use the latest date
        ];
        unset($feedbacks[$by]); // Remove matched feedback
    } else {
        // If there's no matching feedback
        $response['data'][] = [
            'message' => "memberikan remark " . $remark['remark'],
            'by' => $by,
            'date' => $remark['date']
        ];
    }
}

// Add remaining feedbacks without remarks
foreach ($feedbacks as $oleh => $feedback) {
    $response['data'][] = [
        'message' => "memberikan feedback " . $feedback['feedback'],
        'by' => $oleh,
        'date' => $feedback['date']
    ];
}

echo json_encode($response);

mysqli_close($koneksi3); // Close the connection
?>