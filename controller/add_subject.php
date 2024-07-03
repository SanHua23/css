<?php
include '../connect.php';

$course_id = $_POST['course_id'];
$subject_title = $_POST['subject_title'];
$subject_code = $_POST['subject_code'];
$section_id = $_POST['section_id'];
$year_level = $_POST['year_level'];

$response = [];

// Check if subject already exists
$sqlCheck = "SELECT * FROM subject_tbl WHERE course_id = :course_id AND subject_title = :subject_title AND subject_code = :subject_code AND year_level = :year_level";
$checkQuery = $conn->prepare($sqlCheck);
$checkQuery->bindParam(':course_id', $course_id);
$checkQuery->bindParam(':subject_title', $subject_title);
$checkQuery->bindParam(':subject_code', $subject_code);
$checkQuery->bindParam(':year_level', $year_level);
$checkQuery->execute();
$existing = $checkQuery->fetch(PDO::FETCH_ASSOC);

if (!$existing) {
    if ($section_id === "0") {
        // Insert new subject
        $sqlInsert = "INSERT INTO subject_tbl (course_id, subject_title, subject_code, year_level) VALUES (:course_id, :subject_title, :subject_code, :year_level)";
        $insertQuery = $conn->prepare($sqlInsert);
        $insertQuery->bindParam(':course_id', $course_id);
        $insertQuery->bindParam(':subject_title', $subject_title);
        $insertQuery->bindParam(':subject_code', $subject_code);
        $insertQuery->bindParam(':year_level', $year_level);

        if ($insertQuery->execute()) {
            $sql = "SELECT * FROM course_tbl WHERE course_id = :course_id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':course_id', $course_id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $response['status'] = 'success';
            $response['operation'] = 'insert';
            $response['year_level'] = $year_level;
            $response['subject_id'] = $conn->lastInsertId();
            $response['course_name'] = $row['course_name'];
            $response['course_id'] = $course_id;
            $response['subject_title'] = $subject_title;
            $response['subject_code'] = $subject_code;
        } else {
            throw new Exception("Insertion failed");
        }
    } else {
        // Update existing subject
        $sqlUpdate = "UPDATE subject_tbl SET course_id = :course_id, subject_title = :subject_title, subject_code = :subject_code WHERE subject_id = :subject_id, year_level = :year_level";
        $updateQuery = $conn->prepare($sqlUpdate);
        $updateQuery->bindParam(':course_id', $course_id);
        $updateQuery->bindParam(':subject_title', $subject_title);
        $updateQuery->bindParam(':subject_code', $subject_code);
        $updateQuery->bindParam(':subject_id', $section_id);
        $updateQuery->bindParam(':year_level', $year_level);

        if ($updateQuery->execute()) {
            $sql = "SELECT * FROM course_tbl WHERE course_id = :course_id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':course_id', $course_id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $response['status'] = 'success';
            $response['operation'] = 'update';
            $response['subject_id'] = $section_id;
            $response['year_level'] = $year_level;
            $response['course_name'] = $row['course_name'];
            $response['course_id'] = $course_id;
            $response['subject_title'] = $subject_title;
            $response['subject_code'] = $subject_code;
        } else {
            throw new Exception("Update failed");
        }
    }
} else {
    $response['status'] = 'error';
    $response['message'] = 'This subject is already set!';
}

echo json_encode($response);
?>
