<?php
include "../connect.php"; // Adjust path as necessary

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['course_id'])) {
    $course_id = $_GET['course_id'];


    // Prepare SQL statement to fetch sections for the given course_id
    $sql_sections = "SELECT section_id, year_level, section FROM section_tbl WHERE course_id = :course_id";
    $stmt_sections = $conn->prepare($sql_sections);
    $stmt_sections->bindParam(':course_id', $course_id, PDO::PARAM_INT);
    $stmt_sections->execute();
    $sections = $stmt_sections->fetchAll(PDO::FETCH_ASSOC);

     // Prepare SQL statement to fetch subjects for the given course_id
    $sql_subjects = "SELECT subject_id, subject_title FROM subject_tbl WHERE course_id = :course_id";
    $stmt_subjects = $conn->prepare($sql_subjects);
    $stmt_subjects->bindParam(':course_id', $course_id, PDO::PARAM_INT);
    $stmt_subjects->execute();
    $subjects = $stmt_subjects->fetchAll(PDO::FETCH_ASSOC);
    
    // Return subjects and sections as JSON
    header('Content-Type: application/json');
    echo json_encode(array(
        'subjects' => $subjects,
        'sections' => $sections
    ));
} else {
    // Handle invalid requests or errors
    http_response_code(400); // Bad Request
    echo json_encode(array('error' => 'Invalid request or no course_id provided'));
}
?>
