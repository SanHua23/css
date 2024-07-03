<?php
include '../connect.php';

$room_id = $_POST['room_id'];
$room_number = $_POST['room_number'];
$room_description = $_POST['room_description'];

$response = [];

    if ($_POST['room_id'] === "0") {

        // Check if course name already exists
        $sqlCheck = "SELECT * FROM room_tbl WHERE room_number = :room_number";
        $checkQuery = $conn->prepare($sqlCheck);
        $checkQuery->bindParam(':room_number', $room_number);
        $checkQuery->execute();
        $existing = $checkQuery->fetch(PDO::FETCH_ASSOC);

        if ($existing) {
            $response['status'] = 'error';
            $response['message'] = 'This room number already exists!';
        }else{
            // Insert new course
            $sqlInsert = "INSERT INTO room_tbl (room_number, room_description) VALUES (:room_number, :room_description)";
            $insertQuery = $conn->prepare($sqlInsert);
            $insertQuery->bindParam(':room_number', $room_number);
            $insertQuery->bindParam(':room_description', $room_description);

            if ($insertQuery->execute()) {
                $lastInsertId = $conn->lastInsertId();

                $response['status'] = 'insert';
                $response['room_id'] = $lastInsertId;
                $response['room_number'] = $room_number;
                $response['room_description'] = $room_description;
            }
        }
    } else {

        // Update existing course
        $sqlUpdate = "UPDATE room_tbl SET room_number = :room_number, room_description = :room_description WHERE room_id = :room_id";
        $updateQuery = $conn->prepare($sqlUpdate);
        $updateQuery->bindParam(':room_number', $room_number);
        $updateQuery->bindParam(':room_description', $room_description);
        $updateQuery->bindParam(':room_id', $room_id);

        if ($updateQuery->execute()) {

            $response['status'] = 'update';
            $response['room_id'] = $room_id;
            $response['room_number'] = $room_number;
            $response['room_description'] = $room_description;
        }
    }

echo json_encode($response);
?>
