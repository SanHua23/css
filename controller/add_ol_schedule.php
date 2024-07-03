<?php
// Include your database connection file
include "../connect.php"; // Adjust path as necessary



// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $subject_id = $_POST['subject_id'];
    if (isset($_POST['faculty_id']) && isset($_POST['schedule_id'])) {
        $faculty_id = $_POST['faculty_id'];
        $schedule_id = $_POST['schedule_id'];       

        // Check if the schedule already exists and get its details
        $sql_check = "SELECT * FROM ol_schedule_tbl WHERE ol_schedule_id = :ol_schedule_id";
        $stmt_check = $conn->prepare($sql_check);
        $stmt_check->bindParam(':ol_schedule_id', $schedule_id);

        if ($stmt_check->execute()) {
            $result = $stmt_check->fetch(PDO::FETCH_ASSOC);
            $schedule_start_datetime = $result['start_datetime'];
            $schedule_end_datetime = $result['end_datetime'];

            // Check if the faculty has any existing schedules
            $sql_faculty = "SELECT * FROM ol_schedule_tbl WHERE faculty_id = :faculty_id";
            $stmt_faculty = $conn->prepare($sql_faculty);
            $stmt_faculty->bindParam(':faculty_id', $faculty_id);

            if ($stmt_faculty->execute()) {
                $unique_times = array();

                $fetch_faculty = $stmt_faculty->fetchAll(PDO::FETCH_ASSOC);

                if ($fetch_faculty) {
                    foreach ($fetch_faculty as $fetch_faculty_row) {
                        $faculty_start_datetime = $fetch_faculty_row['start_datetime'];
                        $faculty_end_datetime = $fetch_faculty_row['end_datetime'];

                        if (!in_array($schedule_start_datetime, $unique_times)) {
                            $unique_times[] = $schedule_start_datetime;
                        }
                        if (!in_array($schedule_end_datetime, $unique_times)) {
                            $unique_times[] = $schedule_end_datetime;
                        }

                        // Check for overlapping schedules
                        if (
                            ($schedule_start_datetime >= $faculty_start_datetime && $schedule_start_datetime < $faculty_end_datetime) ||
                            ($schedule_end_datetime > $faculty_start_datetime && $schedule_end_datetime <= $faculty_end_datetime) ||
                            ($schedule_start_datetime <= $faculty_start_datetime && $schedule_end_datetime >= $faculty_end_datetime)
                        ) {
                            $response = [
                                'status' => 'exists',
                                'messages' => 'The schedule overlaps with an existing schedule.'
                            ];
                            echo json_encode($response);
                            exit;
                        }
                    }
                }
            }
        }


        // Prepare SQL statement for updating schedule with new faculty_id
        $sql_update = "UPDATE ol_schedule_tbl SET faculty_id = :faculty_id WHERE ol_schedule_id = :schedule_id";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bindParam(':faculty_id', $faculty_id);
        $stmt_update->bindParam(':schedule_id', $schedule_id);

        // Execute update query
        if ($stmt_update->execute()) {
            // Fetch updated data to send back as JSON response
            $sql_select = "SELECT sch.*, CONCAT(f.first_name, ' ', f.last_name) AS teacher_name
                           FROM ol_schedule_tbl sch
                           INNER JOIN faculty_tbl f ON f.faculty_id = sch.faculty_id
                           WHERE sch.ol_schedule_id = :schedule_id";
            $stmt_select = $conn->prepare($sql_select);
            $stmt_select->bindParam(':schedule_id', $schedule_id);
            $stmt_select->execute();
            $result = $stmt_select->fetch(PDO::FETCH_ASSOC);

            // Assuming getTeachers is a function that fetches teacher information
            $teacher_info = getTeachers($conn, $subject_id);

            // Prepare response data
            $response = [
                'status' => 'success',
                'schedule_id' => $schedule_id,
                'teacher_name' => $result['teacher_name'],
                'faculty_id' => $faculty_id,
                'teachers' => $teacher_info['teachers'],
                'faculty_ids' => $teacher_info['faculty_ids'],
                'subject_id' => $subject_id
            ];

            // Send JSON response back to JavaScript
            echo json_encode($response);
        } else {
            // Handle update failure
            echo json_encode(['status' => 'error', 'message' => 'Failed to update schedule']);
        }
    } else {
        // Retrieve POST data (ensure you sanitize and validate these inputs in your actual application)
        $course_id = $_POST['course_id'];
        $section_id = $_POST['section_id'];
        $room_id = $_POST['room_id'];
        $start_datetime = $_POST['start_datetime'];
        $end_datetime = $_POST['end_datetime'];
        $semester = $_POST['semester'];

        if ($semester === '1') {
            $sem = "1st semester"; 
        }else if ($semester === '2') {
            $sem = "2nd semester"; 
        }


        // Check if the schedule already exists or overlaps with existing schedules
        $sql_check = "SELECT COUNT(*) AS count FROM ol_schedule_tbl 
                      WHERE (course_id = :course_id AND subject_id = :subject_id AND section_id = :section_id 
                         AND start_datetime = :start_datetime AND end_datetime = :end_datetime AND semester = :semester) 
                         OR (start_datetime = :start_datetime AND end_datetime = :end_datetime AND room_id = :room_id AND semester = :semester) 
                         OR (
                             semester = :semester AND (
                                 (:start_datetime BETWEEN start_datetime AND end_datetime)
                                 OR (:end_datetime BETWEEN start_datetime AND end_datetime)
                                 OR (start_datetime BETWEEN :start_datetime AND :end_datetime)
                                 OR (end_datetime BETWEEN :start_datetime AND :end_datetime)
                             )
                         )";
        $stmt_check = $conn->prepare($sql_check);
		$stmt_check->bindParam(':course_id', $course_id);
		$stmt_check->bindParam(':subject_id', $subject_id); // Ensure $subject_id is set appropriately
		$stmt_check->bindParam(':section_id', $section_id);
		$stmt_check->bindParam(':start_datetime', $start_datetime);
		$stmt_check->bindParam(':end_datetime', $end_datetime);
        $stmt_check->bindParam(':room_id', $room_id);
        $stmt_check->bindParam(':semester', $semester);
		$stmt_check->execute();
		$result_check = $stmt_check->fetch(PDO::FETCH_ASSOC);

        if ($result_check['count'] > 0) {
            // Schedule already exists

            $response = [
                'status' => 'error',
                'message' => 'This schedule already exists.'
            ];
            echo json_encode($response); // Send JSON response back to JavaScript
            exit; // Stop further execution
        }

        // Prepare SQL statement for insertion
        $sql_insert = "INSERT INTO ol_schedule_tbl (course_id, subject_id, section_id, start_datetime, end_datetime, room_id, semester) VALUES (:course_id, :subject_id, :section_id, :start_datetime, :end_datetime, :room_id, :semester)";
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bindParam(':course_id', $course_id);
        $stmt_insert->bindParam(':subject_id', $subject_id);
        $stmt_insert->bindParam(':section_id', $section_id);
        $stmt_insert->bindParam(':start_datetime', $start_datetime);
        $stmt_insert->bindParam(':end_datetime', $end_datetime);
        $stmt_insert->bindParam(':room_id', $room_id);
        $stmt_insert->bindParam(':semester', $semester);
        // Execute the statement
        if ($stmt_insert->execute()) {
            // Retrieve inserted data for response
            $ol_schedule_id = $conn->lastInsertId(); // Get the last inserted ID
            $teacher_info = getTeachers($conn, $subject_id);

            $formated_start = date("F d, Y h:i a", strtotime($start_datetime));
            $formated_end = date("F d, Y h:i a", strtotime($end_datetime));
            $concat_time = $formated_start."<br>".$formated_end;
            $response = [
                'status' => 'success',
                'old_schedule_id' => $ol_schedule_id,
                'semester' => $sem,
                'room' => roomNumber($conn, $room_id),
                'time_date' => $concat_time, // fetch (start_time - end_time)
                'subject_id' => $subject_id, // fetch subject_id
                'subject_title' => getSubjectTitle($conn, $subject_id), // fetch subject title
                'year_section' => getYearSection($conn, $section_id), // fetch fetch year and section
                'course_name' => getCourseName($conn, $course_id), // fetch coursename
                'teachers' => $teacher_info['teachers'], // fetch full name
                'faculty_ids' => $teacher_info['faculty_ids'], // fetch teacher/faculty id
                'row_count' => getRowCount($conn) // to display realtime table row
            ];
            echo json_encode($response); // Send JSON response back to JavaScript
        } else {
            // Insertion failed
            $error = $stmt_insert->errorInfo()[2];
            $response = [
                'status' => 'error',
                'message' => 'Error inserting schedule: ' . $error
            ];
            echo json_encode($response); // Send JSON response back to JavaScript
        }
    }

}

// Function to fetch subject title based on subject_id
function getSubjectTitle($conn, $subject_id) {
    $sql = "SELECT subject_title FROM subject_tbl WHERE subject_id = :subject_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':subject_id', $subject_id);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['subject_title'];
}


// Function to fetch room
function roomNumber($conn, $room_id) {
    $sql = "SELECT room_number FROM room_tbl WHERE room_id = :room_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':room_id', $room_id);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['room_number'];
}

// Function to fetch year and section based on section_id
function getYearSection($conn, $section_id) {
    $sql = "SELECT CONCAT(year_level, '-', section) AS year_section FROM section_tbl WHERE section_id = :section_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':section_id', $section_id);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['year_section'];
}

// Function to fetch course name based on course_id
function getCourseName($conn, $course_id) {
    $sql = "SELECT course_name FROM course_tbl WHERE course_id = :course_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':course_id', $course_id);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['course_name'];
}

// Function to fetch current row count for display
function getRowCount($conn) {
    $sql = "SELECT COUNT(*) AS row_count FROM schedule_tbl";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['row_count'];
}

function getTeachers($conn, $subject_id) {
    $sql = "SELECT *, f.faculty_id AS fids FROM faculty_tbl f INNER JOIN preferred_subject_tbl ps ON ps.faculty_id = f.faculty_id WHERE ps.subject_id = :subject_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":subject_id", $subject_id);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $teachers = [];
    $faculty_ids = [];
    
    foreach ($results as $result){
        $fullname = $result['first_name'] . " " . $result['last_name'];
        $teachers[] = $fullname;
        $faculty_ids[] = $result['fids'];
    }
    
    return ['teachers' => $teachers, 'faculty_ids' => $faculty_ids];
}

?>


