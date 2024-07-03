<?php
// Include your database connection file
include "../connect.php"; // Adjust path as necessary

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $subject_id = $_POST['subject_id'];
    if (isset($_POST['faculty_id']) && isset($_POST['schedule_id'])) {
        $faculty_id = $_POST['faculty_id'];
        $schedule_id = $_POST['schedule_id'];
        
        // Check if schedule with the same subject_id and faculty_id already exists
        $sql_check5 = "SELECT COUNT(*) AS count FROM schedule_tbl WHERE subject_id = :subject_id AND faculty_id = :faculty_id";
        $stmt_check5 = $conn->prepare($sql_check5);
        $stmt_check5->bindParam(':subject_id', $subject_id);
        $stmt_check5->bindParam(':faculty_id', $faculty_id);
        $stmt_check5->execute();
        $result_check5 = $stmt_check5->fetch(PDO::FETCH_ASSOC);

        if ($result_check5['count'] > 0) {
            $response = [
                'status' => 'exists',
                'message' => 'This teacher is already assigned to this subject.'
            ];
            echo json_encode($response); // Send JSON response back to JavaScript
            exit; // Exit after sending response
        }

        // Select time_id using schedule_id
        $sql_sched = "SELECT * FROM schedule_tbl WHERE schedule_id = :schedule_id";
        $stmt_sched = $conn->prepare($sql_sched);
        $stmt_sched->bindParam(':schedule_id', $schedule_id);

        if ($stmt_sched->execute()) {
            $fetch_schedule = $stmt_sched->fetch(PDO::FETCH_ASSOC);
            $sched_time_id = $fetch_schedule['time_id'];

            // Query time_tbl to fetch start_time, end_time, and days for the given time_id
            $sql_check2 = "SELECT * FROM time_tbl WHERE time_id = :time_id";
            $stmt_check2 = $conn->prepare($sql_check2);
            $stmt_check2->bindParam(':time_id', $sched_time_id);
            if ($stmt_check2->execute()) {
                $sched_time_tbl = $stmt_check2->fetch(PDO::FETCH_ASSOC);
                $start_time1 = $sched_time_tbl['start_time'];
                $end_time1 = $sched_time_tbl['end_time'];
                $days1 = $sched_time_tbl['days'];

                // Select schedules where the faculty_id matches to check for overlapping schedules
                $sql_faculty = "SELECT * FROM schedule_tbl WHERE faculty_id = :faculty_id";
                $stmt_faculty = $conn->prepare($sql_faculty);
                $stmt_faculty->bindParam(':faculty_id', $faculty_id);

                if ($stmt_faculty->execute()) {
                    $fetch_faculty = $stmt_faculty->fetchAll(PDO::FETCH_ASSOC);
                    if ($fetch_faculty) {
                        $unique_times = [];

                        foreach ($fetch_faculty as $fetch_faculty_row) {
                            $faculty_time_id = $fetch_faculty_row['time_id'];

                            // Query time_tbl to fetch start_time and days for the faculty_time_id
                            $sql_check3 = "SELECT * FROM time_tbl WHERE time_id = :time_id";
                            $stmt_check3 = $conn->prepare($sql_check3);
                            $stmt_check3->bindParam(':time_id', $faculty_time_id);
                            if ($stmt_check3->execute()) {
                                $faculty_time_tbl = $stmt_check3->fetch(PDO::FETCH_ASSOC);
                                $start_time2 = $faculty_time_tbl['start_time'];
                                $days2 = $faculty_time_tbl['days'];

                                // Check for overlapping times and days
                                if ($start_time2 === $start_time1 && $days2 === $days1) {
                                    $response = [
                                        'status' => 'exists',
                                        'message' => 'This teacher is already assigned to this subject on the same day.'
                                    ];
                                    echo json_encode($response); // Send JSON response back to JavaScript
                                    exit;
                                }
                            }
                        }
                    }
                }
            }
        } else {
            $response = [
                'status' => 'error',
                'message' => 'Failed to check schedule'
            ];
            echo json_encode($response); // Send JSON response back to JavaScript
            exit;
        }

        // Prepare SQL statement for updating schedule with new faculty_id
        $sql_update = "UPDATE schedule_tbl SET faculty_id = :faculty_id WHERE schedule_id = :schedule_id";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bindParam(':faculty_id', $faculty_id);
        $stmt_update->bindParam(':schedule_id', $schedule_id);

        // Execute update query
        if ($stmt_update->execute()) {
            // Fetch updated data to send back as JSON response
            $sql_select = "SELECT sch.*, CONCAT(f.first_name, ' ', f.last_name) AS teacher_name
                           FROM schedule_tbl sch
                           INNER JOIN faculty_tbl f ON f.faculty_id = sch.faculty_id
                           WHERE sch.schedule_id = :schedule_id";
            $stmt_select = $conn->prepare($sql_select);
            $stmt_select->bindParam(':schedule_id', $schedule_id);
            $stmt_select->execute();
            $t_result = $stmt_select->fetch(PDO::FETCH_ASSOC);

            // Fetch teacher information
            $teacher_info = getTeachers($conn, $subject_id);

            // Prepare response data
            $response = [
                'status' => 'success',
                'schedule_id' => $schedule_id,
                'teacher_name' => $t_result['teacher_name'],
                'faculty_id' => $faculty_id,
                'teachers' => $teacher_info['teachers'],
                'faculty_ids' => $teacher_info['faculty_ids'],
                'subject_id' => $subject_id
            ];

            // Send JSON response back to JavaScript
            echo json_encode($response);
        } else {
            // Handle update failure
            $response = [
                'status' => 'error',
                'message' => 'Failed to update schedule'
            ];
            echo json_encode($response);
        }
    } else {
          // Retrieve POST data (ensure you sanitize and validate these inputs in your actual application)
        $course_id = $_POST['course_id'];
        $section_id = $_POST['section_id'];
        $time_id = $_POST['time_id'];
        $semester = $_POST['semester'];

        if ($semester === '1') {
            $sem = "1st semester"; 
        }else if ($semester === '2') {
            $sem = "2nd semester"; 
        }
        $time_range = getTimeRange($conn, $time_id);
        $start_time = $time_range['start_time'];
        $end_time = $time_range['end_time'];
        $days = $time_range['days'];
        $time_ranges =  $start_time." - ".$end_time;

        $fetch_section = getYearSection($conn, $section_id);
        $year_section = $fetch_section['year_section'];
        $year_level = $fetch_section['year_level'];
        $section = $fetch_section['section'];
        
        $fetch_course = getCourseName($conn, $course_id);
        $course_name = $fetch_course['course_name'];

        $fetch_subject = getSubjectTitle($conn, $subject_id);
        $subject_title = $fetch_subject['subject_title'];

        $sql_check2 = "SELECT COUNT(*) AS count FROM schedule_tbl  sched
                      INNER JOIN time_tbl t ON t.time_id = sched.time_id
                      INNER JOIN subject_tbl s ON s.subject_id = sched.subject_id
                      INNER JOIN section_tbl sec ON sec.section_id = sched.section_id
                      WHERE t.days = :days AND subject_title = :subject_title AND sec.year_level = :year_level AND sec.section = :section ";
        $stmt_check2 = $conn->prepare($sql_check2);
        $stmt_check2->bindParam(':days', $days);
        $stmt_check2->bindParam(':subject_title', $subject_title);
        $stmt_check2->bindParam(':year_level', $year_level);
        $stmt_check2->bindParam(':section', $section);
        $stmt_check2->execute();
        $result_check2 = $stmt_check2->fetch(PDO::FETCH_ASSOC);

        if ($result_check2['count'] > 0) {
            $response = [
                'status' => 'error',
                'message' => 'This subject already exists.'
            ];
            echo json_encode($response);
            exit;
        }

        $sql_check = "SELECT COUNT(*) AS count FROM schedule_tbl  sched
                      INNER JOIN time_tbl t ON t.time_id = sched.time_id
                      INNER JOIN section_tbl sec ON sec.section_id = sched.section_id
                      WHERE start_time = :start_time AND end_time = :end_time AND year_level = :year_level AND section = :section AND days = :days";
        $stmt_check = $conn->prepare($sql_check);
        $stmt_check->bindParam(':start_time', $start_time);
        $stmt_check->bindParam(':end_time', $end_time);
        $stmt_check->bindParam(':year_level', $year_level);
        $stmt_check->bindParam(':section', $section);
        $stmt_check->bindParam(':days', $days);
        $stmt_check->execute();
        $result_check = $stmt_check->fetch(PDO::FETCH_ASSOC);

        if ($result_check['count'] > 0) {
            $response = [
                'status' => 'error',
                'message' => 'This schedule already exists.'
            ];
            echo json_encode($response);
            exit;
        }

        // Prepare SQL statement for inserting new schedule
        $sql_insert = "INSERT INTO schedule_tbl (course_id, section_id, subject_id, time_id, semester) 
                       VALUES (:course_id, :section_id, :subject_id, :time_id, :semester)";
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bindParam(':course_id', $course_id);
        $stmt_insert->bindParam(':section_id', $section_id);
        $stmt_insert->bindParam(':subject_id', $subject_id);
        $stmt_insert->bindParam(':time_id', $time_id);
        $stmt_insert->bindParam(':semester', $semester);

        // Execute insert query
        if ($stmt_insert->execute()) {
            $lastInsertId = $conn->lastInsertId();
            $teacher_info = getTeachers($conn, $subject_id);
            //Prepare response data
            $response = [
                'status' => 'success',
                'semester' => $sem,
                'schedule_id' => $lastInsertId,
                'course_id' => $course_id,
                'section_id' => $section_id,
                'subject_id' => $subject_id,
                'time' => $time_ranges,
                'day' => getDayName($conn, $time_id),
                'year_section' => $year_section,
                'subject_title' => $subject_title,
                'teachers' => $teacher_info['teachers'],
                'course_name' => $course_name,
                'row_count' => getRowCount($conn),
                'faculty_ids' => $teacher_info['faculty_ids']
            ];

            // Send JSON response back to JavaScript
            echo json_encode($response);
        } else {
            // Handle insert failure
            echo json_encode(['status' => 'error', 'message' => 'Failed to add schedule']);
        }
    }
}

// Function to fetch start and end time based on time_id
function getTimeRange($conn, $time_id) {
    $sql = "SELECT *, DATE_FORMAT(start_time, '%h:%i %p') AS start_time, DATE_FORMAT(end_time, '%h:%i %p') AS end_time 
            FROM time_tbl WHERE time_id = :time_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':time_id', $time_id);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result;
}


// Function to fetch day name based on time_id
function getDayName($conn, $time_id) {
    $sql = "SELECT CASE 
                WHEN days = 'M' THEN 'Monday'
                WHEN days = 'T' THEN 'Tuesday'
                WHEN days = 'W' THEN 'Wednesday'
                WHEN days = 'TH' THEN 'Thursday'
                WHEN days = 'F' THEN 'Friday'
                WHEN days = 'S' THEN 'Saturday'
                ELSE ''
            END AS day_name
            FROM time_tbl WHERE time_id = :time_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':time_id', $time_id);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['day_name'];
}

// Function to fetch subject title based on subject_id
function getSubjectTitle($conn, $subject_id) {
    $sql = "SELECT *, subject_title FROM subject_tbl WHERE subject_id = :subject_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':subject_id', $subject_id);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result;
}

// Function to fetch year and section based on section_id
function getYearSection($conn, $section_id) {
    $sql = "SELECT *, CONCAT(year_level, '-', section) AS year_section FROM section_tbl WHERE section_id = :section_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':section_id', $section_id);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result;
}

// Function to fetch course name based on course_id
function getCourseName($conn, $course_id) {
    $sql = "SELECT *, course_name FROM course_tbl WHERE course_id = :course_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':course_id', $course_id);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result;
}

// Function to fetch current row count for display
function getRowCount($conn) {
    $sql = "SELECT *, COUNT(*) AS row_count FROM schedule_tbl";
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
