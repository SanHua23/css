<?php                
try {
    // Include database connection file
    require '../connect.php'; 

    // SQL query to fetch schedule details
    $display_query = "SELECT *, ols.faculty_id AS ols_f FROM ol_schedule_tbl ols
                      INNER JOIN course_tbl c ON c.course_id = ols.course_id
                      INNER JOIN subject_tbl s ON s.subject_id = ols.subject_id
                      LEFT JOIN faculty_tbl f ON f.faculty_id = ols.faculty_id
                      INNER JOIN room_tbl r ON r.room_id = ols.room_id
                      INNER JOIN section_tbl sec ON sec.section_id = ols.section_id";   

    // Prepare the SQL statement
    $stmt = $conn->prepare($display_query);
    // Execute the statement
    $stmt->execute();

    // Fetch all rows as associative arrays
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($rows) {
        // Transform rows into desired format
        $data_arr = array_map(function($row) {
            // Check if a faculty member is assigned
            $faculty = isset($row['ols_f']) ? $row['first_name'] . " " . $row['last_name'] : "No Teacher Available";
            $remarks = isset($row['remarks']) ? $row['remarks'] : "To Be Announced";
            return [
                'event_id' => $row['ol_schedule_id'],
                'course_name' => $row['course_name'],
                'faculty_name' => $faculty,
                'remarks' => $remarks,
                'room' => $row['room_number'],
                'ys' => $row['year_level'] . "-" . $row['section'],
                'subject_title' => $row['subject_title'],
                'start_datetime' => $row['start_datetime'], // Keep original format
                'end_datetime' => $row['end_datetime'],     // Keep original format
                'color' => '#ddad35'
            ];
        }, $rows);

        // Prepare response data
        $data = [
            'status' => true,
            'msg' => 'Data retrieved successfully!',
            'data' => $data_arr
        ];
    } else {
        // Handle case where no data is found
        throw new Exception('No data found.');
    }
} catch (Exception $e) {
    // Handle exceptions and prepare error response
    $data = [
        'status' => false,
        'msg' => 'Error: ' . $e->getMessage()
    ];
}

// Return response as JSON
echo json_encode($data);
?>
