<?php
include '../connect.php';

$id = $_POST['id'];
$tablename = $_POST['tablename'];
$id_type = $_POST['id_type'];

$response = [];

$deleteSQL = "DELETE FROM $tablename WHERE $id_type = :id";
$deleteStmt = $conn->prepare($deleteSQL);
$deleteStmt->bindParam(':id', $id, PDO::PARAM_INT);
if ($deleteStmt->execute()) {
    $response['status'] = 'success';
} else {
    $response['status'] = 'error';
}
echo json_encode($response);
?>
