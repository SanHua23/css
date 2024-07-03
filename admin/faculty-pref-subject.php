<?php
include "../connect.php"; // Adjust path as necessary

// if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['course_id'])) {
//     $course_id = $_GET['course_id'];

//     // Prepare SQL statement to fetch subjects for the given course_id
//     $sql_subjects = "SELECT subject_id, subject_title FROM subject_tbl WHERE course_id = :course_id";
//     $stmt_subjects = $conn->prepare($sql_subjects);
//     $stmt_subjects->bindParam(':course_id', $course_id, PDO::PARAM_INT);
//     $stmt_subjects->execute();
//     $subjects = $stmt_subjects->fetchAll(PDO::FETCH_ASSOC);
    
//     // Return subjects as JSON
//     header('Content-Type: application/json');
//     echo json_encode(array('subjects' => $subjects));
// }else
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['faculty_id'])) {

    $faculty_id = $_GET['faculty_id'];

    // Prepare SQL statement to fetch subjects for the given course_id
    $sql_subjects = "SELECT * FROM preferred_subject_tbl ps 
                     INNER JOIN subject_tbl s ON s.subject_id = ps.subject_id WHERE faculty_id = :faculty_id";
    $stmt_subjects = $conn->prepare($sql_subjects);
    $stmt_subjects->bindParam(':faculty_id', $faculty_id, PDO::PARAM_INT);
    $stmt_subjects->execute();
    $subjects = $stmt_subjects->fetchAll(PDO::FETCH_ASSOC);

    // Return subjects as JSON
    header('Content-Type: application/json');
    echo json_encode(array('faculty_id' => $faculty_id, 'subjects' => $subjects));

}elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['faculty_id'])) {

     $faculty_id = $_POST['faculty_id'];

    // Prepare SQL statement to fetch subjects for the given faculty_id
    $sql_faculty = "SELECT * FROM faculty_tbl WHERE faculty_id = :faculty_id";
    $stmt_faculty = $conn->prepare($sql_faculty);
    $stmt_faculty->bindParam(':faculty_id', $faculty_id, PDO::PARAM_INT);

    if ($stmt_faculty->execute()) {
        $teacher = $stmt_faculty->fetch(PDO::FETCH_ASSOC);
        if ($teacher) {
            $course_id = $teacher['course_id'];
            $sql_subjects = "SELECT * FROM subject_tbl WHERE course_id = :course_id";
            $stmt_subjects = $conn->prepare($sql_subjects);
            $stmt_subjects->bindParam(':course_id', $course_id, PDO::PARAM_INT);

            if ($stmt_subjects->execute()) {
                $subjects = $stmt_subjects->fetchAll(PDO::FETCH_ASSOC);

                // Return subjects as JSON
                header('Content-Type: application/json');
                echo json_encode(array('faculty_id' => $faculty_id, 'subjects' => $subjects));
                exit;
            }
        }
    }

    // In case of error, return an empty subjects array
    header('Content-Type: application/json');
    echo json_encode(array('faculty_id' => $faculty_id, 'subjects' => []));

} else {
    // Handle invalid requests or errors
    http_response_code(400); // Bad Request
    echo json_encode(array('error' => 'Invalid request or no course_id provided'));
}
?>
