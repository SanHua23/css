<?php
session_start();
include '../connect.php';

$response = [];

$sql = $_POST['request'];

if ($sql === "inserting") {
    $course_id = $_POST['course_id'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $description = $_POST['description'];
    $pstart_time = $_POST['pstart_time'];
    $pend_time = $_POST['pend_time'];

    $pstart_times = date("h:i a", strtotime($pstart_time));
    $pend_times = date("h:i a", strtotime($pend_time));
    $full_time = $pstart_times ."-". $pend_times;
    $response = [];


    if ($_POST['faculty_id'] === "0") {

       // Insert new faculty member
        $sqlInsert = "INSERT INTO faculty_tbl (first_name, last_name, course_id, description, pstart_time, pend_time) VALUES (:first_name, :last_name, :course_id, :description, :pstart_time, :pend_time)";
        $insertQuery = $conn->prepare($sqlInsert);
        $insertQuery->bindParam(':first_name', $first_name);
        $insertQuery->bindParam(':last_name', $last_name);
        $insertQuery->bindParam(':course_id', $course_id);
        $insertQuery->bindParam(':description', $description);
        $insertQuery->bindParam(':pstart_time', $pstart_time);
        $insertQuery->bindParam(':pend_time', $pend_time);

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
            $response['faculty_id'] = $lastInsertId;
            $response['full_name'] = $first_name . " " . $last_name;
            $response['description'] = $description;
            $response['full_time'] = $full_time;
        } else {
            throw new Exception("Insertion failed");
        }

    } else {

        $faculty_id = $_POST['faculty_id'];

        // Update existing course
        $sqlUpdate = "UPDATE faculty_tbl SET first_name = :first_name, last_name = :last_name, course_id = :course_id, description = :description, pstart_time = :pstart_time, pend_time = :pend_time WHERE faculty_id = :faculty_id";
        $updateQuery = $conn->prepare($sqlUpdate);
        $updateQuery->bindParam(':first_name', $first_name);
        $updateQuery->bindParam(':last_name', $last_name);
        $updateQuery->bindParam(':course_id', $course_id);
        $updateQuery->bindParam(':description', $description);
        $updateQuery->bindParam(':pstart_time', $pstart_time);
        $updateQuery->bindParam(':pend_time', $pend_time);
        $updateQuery->bindParam(':faculty_id', $faculty_id);
        

        if ($updateQuery->execute()) {

            if ($_SESSION['user_role'] === "2") {
                $description= "Update their details.";

                $sqlInsertNotif = "INSERT INTO notification_tbl (description, user_id) VALUES (:description, :user_id)";
                $insertNotifQuery = $conn->prepare($sqlInsertNotif);
                $insertNotifQuery->bindParam(':description', $description);
                $insertNotifQuery->bindParam(':user_id', $_SESSION['user_id']);
                $insertNotifQuery->execute();
            }

            $sql = "SELECT * FROM course_tbl WHERE course_id = :course_id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':course_id', $course_id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $response['status'] = 'success';
            $response['message'] = 'message1';
            $response['course_name'] = $row['course_name'];
            $response['course_id'] = $course_id;
            $response['faculty_id'] = $faculty_id;
            $response['full_time'] = $full_time;
            $response['full_name'] = $first_name." ".$last_name;
            $response['description'] = $description;

        } else {
            throw new Exception("Update failed");
        }
    }

}else if ($sql === "update") {
    $faculty_id = $_POST['faculty_id'];
    $subject_ids = $_POST['subject_ids'];


    // Insert preferred subjects
    foreach ($subject_ids as $subject_id) {
        // Check if the subject is already preferred by the faculty
        $sql = "SELECT * FROM preferred_subject_tbl WHERE faculty_id = :faculty_id AND subject_id = :subject_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':faculty_id', $faculty_id);
        $stmt->bindParam(':subject_id', $subject_id);
        $stmt->execute();

        if ($stmt->rowCount() == 0) {
            // If not preferred, insert into the table
            $sqlInsertSubject = "INSERT INTO preferred_subject_tbl (subject_id, faculty_id) VALUES (:subject_id, :faculty_id)";
            $insertSubjectQuery = $conn->prepare($sqlInsertSubject);
            $insertSubjectQuery->bindParam(':subject_id', $subject_id);
            $insertSubjectQuery->bindParam(':faculty_id', $faculty_id);

            if ($insertSubjectQuery->execute()) {
                $response['status'] = 'update';
                $newSubjectsAdded = true;
            }
        }else{
             $response['status'] = 'update';
             $response['message'] = 'message2';
        }
    }

    if ($newSubjectsAdded && $_SESSION['user_role'] === "2") {
        $description= "Update their preferred subject.";

        $sqlInsertNotif = "INSERT INTO notification_tbl (description, user_id) VALUES (:description, :user_id)";
        $insertNotifQuery = $conn->prepare($sqlInsertNotif);
        $insertNotifQuery->bindParam(':description', $description);
        $insertNotifQuery->bindParam(':user_id', $_SESSION['user_id']);

        if ($insertNotifQuery->execute()) {
            $response['message'] = 'message25';
        }
    }
}
echo json_encode($response);
?>