<?php
include '../connect.php';

$course_id = $_POST['course_id'];
$section = $_POST['section'];
$year_level = $_POST['year_level'];

$response = [];

// Check if course name already exists
$sqlCheck = "SELECT * FROM section_tbl WHERE course_id = :course_id AND section = :section AND year_level = :year_level";
$checkQuery = $conn->prepare($sqlCheck);
$checkQuery->bindParam(':course_id', $course_id);
$checkQuery->bindParam(':section', $section);
$checkQuery->bindParam(':year_level', $year_level);
$checkQuery->execute();
$existing = $checkQuery->fetch(PDO::FETCH_ASSOC);

if (!$existing) {
    if ($_POST['section_id'] === "0") {
        // Insert new course
        $sqlInsert = "INSERT INTO section_tbl (course_id, section, year_level) VALUES (:course_id, :section, :year_level)";
        $insertQuery = $conn->prepare($sqlInsert);
        $insertQuery->bindParam(':course_id', $course_id);
        $insertQuery->bindParam(':section', $section);
        $insertQuery->bindParam(':year_level', $year_level);

        if ($insertQuery->execute()) {
            $lastInsertId = $conn->lastInsertId();

            $sql = "SELECT * FROM course_tbl WHERE course_id = :course_id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':course_id', $course_id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $response['status'] = 'success';
            $response['course_name'] = $row['course_name'];
            $response['course_id'] = $course_id;
            $response['year_level'] = $year_level;
            $response['section'] = $section;
            $response['section_id'] = $lastInsertId;
        } else {
            throw new Exception("Insertion failed");
        }
    } else {
        $section_id = $_POST['section_id'];

        // Update existing course
        $sqlUpdate = "UPDATE section_tbl SET course_id = :course_id, section = :section, year_level = :year_level WHERE section_id = :section_id";
        $updateQuery = $conn->prepare($sqlUpdate);
        $updateQuery->bindParam(':course_id', $course_id);
        $updateQuery->bindParam(':section', $section);
        $updateQuery->bindParam(':year_level', $year_level);
        $updateQuery->bindParam(':section_id', $section_id);

        if ($updateQuery->execute()) {
            $sql = "SELECT * FROM course_tbl WHERE course_id = :course_id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':course_id', $course_id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $response['status'] = 'success';
            $response['course_name'] = $row['course_name'];
            $response['course_id'] = $course_id;
            $response['year_level'] = $year_level;
            $response['section'] = $section;
            $response['section_id'] = $section_id;
        } else {
            throw new Exception("Update failed");
        }
    }
} else {
    $response['status'] = 'error';
    $response['message'] = 'This section is already set!';
}

echo json_encode($response);
?>
