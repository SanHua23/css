<?php
include '../connect.php';

$response = [
    'status' => 'error', 
    'inserted' => [],
    'failed' => []
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $days = $_POST['days'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $total_time = $_POST['total_time'];

    try {
        $conn->beginTransaction();
        
        foreach ($days as $day) {
            // Check if time already exists or overlaps for the current day
            $sqlCheck = "SELECT * FROM time_tbl 
                         WHERE days = :days 
                         AND ((:start_time BETWEEN start_time AND end_time) 
                         OR (:end_time BETWEEN start_time AND end_time))";
            $checkQuery = $conn->prepare($sqlCheck);
            $checkQuery->bindParam(':days', $day);
            $checkQuery->bindParam(':start_time', $start_time);
            $checkQuery->bindParam(':end_time', $end_time);
            $checkQuery->execute();
            $existing = $checkQuery->fetch(PDO::FETCH_ASSOC);

            if (!$existing) {
                // Insert new time for the current day
                $sqlInsert = "INSERT INTO time_tbl (days, start_time, end_time, total_time) VALUES (:day, :start_time, :end_time, :total_time)";
                $insertQuery = $conn->prepare($sqlInsert);
                $insertQuery->bindParam(':day', $day);
                $insertQuery->bindParam(':start_time', $start_time);
                $insertQuery->bindParam(':end_time', $end_time);
                $insertQuery->bindParam(':total_time', $total_time);
                $insertQuery->execute();

                $time_id = $conn->lastInsertId();

                $response['inserted'][] = [
                    'time_id' => $time_id,
                    'day' => $day,
                    'start_time' => date("h:i A", strtotime($start_time)),
                    'end_time' => date("h:i A", strtotime($end_time))
                ];
            } else {
                $response['failed'][] = $day;
            }
        }

        $conn->commit();

        if (!empty($response['inserted'])) {
            $response['status'] = 'success';
        } else {
            $response['status'] = 'error';
            $response['message'] = 'No time slots were inserted due to overlaps.';
        }
    } catch (Exception $e) {
        $conn->rollBack();
        $response['message'] = $e->getMessage();
    }
} else {
    $response['message'] = 'Invalid request method.';
}

echo json_encode($response);
?>
