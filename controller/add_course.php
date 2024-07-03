<?php
include '../connect.php';

$course_name = $_POST['course_name'];
$course_description = $_POST['course_description'];

$response = [];

// Check if course name already exists
$sqlCheck = "SELECT course_name FROM course_tbl WHERE course_name = :course_name";
$checkQuery = $conn->prepare($sqlCheck);
$checkQuery->bindParam(':course_name', $course_name);
$checkQuery->execute();
$existing = $checkQuery->fetch(PDO::FETCH_ASSOC);

if (!$existing) {
    if ($_POST['course_id'] === "0") {
        // Insert new course
        $sqlInsert = "INSERT INTO course_tbl (course_name, course_description) VALUES (:course_name, :course_description)";
        $insertQuery = $conn->prepare($sqlInsert);
        $insertQuery->bindParam(':course_name', $course_name);
        $insertQuery->bindParam(':course_description', $course_description);

        if ($insertQuery->execute()) {
            $response['status'] = 'success';
            $response['course_id'] = $conn->lastInsertId();
            $response['course_name'] = $course_name;
            $response['course_description'] = $course_description;
        } else {
            throw new Exception("Insertion failed");
        }
    } else {
         $course_id = $_POST['course_id'];

        // Update existing course
        $sqlUpdate = "UPDATE course_tbl SET course_name = :course_name, course_description = :course_description WHERE course_id = :course_id";
        $updateQuery = $conn->prepare($sqlUpdate);
        $updateQuery->bindParam(':course_name', $course_name);
        $updateQuery->bindParam(':course_description', $course_description);
        $updateQuery->bindParam(':course_id', $course_id);

        if ($updateQuery->execute()) {
            $response['status'] = 'success';
            $response['course_id'] = $course_id;
            $response['course_name'] = $course_name;
            $response['course_description'] = $course_description;
        } else {
            throw new Exception("Update failed");
        }
    }
} else {
    $response['status'] = 'error';
    $response['message'] = 'This course is already added!';
}

echo json_encode($response);
?>
