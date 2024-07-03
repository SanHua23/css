<?php
include '../connect.php';

$response = [];

if (isset($_POST['status']) AND isset($_POST['ol_schedule_id'])) {
    $ol_schedule_id = $_POST['ol_schedule_id'];
    $status = $_POST['status'];

    $sqlUpdate = "UPDATE ol_schedule_tbl SET remarks = :status WHERE ol_schedule_id = :ol_schedule_id";
    $updateQuery = $conn->prepare($sqlUpdate);
    $updateQuery->bindParam(':status', $status);
    $updateQuery->bindParam(':ol_schedule_id', $ol_schedule_id);
    $updateQuery->execute();
    $existing = $updateQuery->fetch(PDO::FETCH_ASSOC);

    if (!$existing) {
        $response['status'] = 'success';
    } else {
        $response['status'] = 'error';
    }
}

echo json_encode($response);
?>
