<?php
include '../connect.php';


$response = [];

if (isset($_POST['status'])) {
    $status = implode('', $_POST['status']);

    $sqlCheck = "SELECT * FROM user_tbl WHERE verification = :status";
    $checkQuery = $conn->prepare($sqlCheck);
    $checkQuery->bindParam(':status', $status);
    $checkQuery->execute();
    $existing = $checkQuery->fetch(PDO::FETCH_ASSOC);

    if ($existing) {

        $user_id = $existing['user_id'];
        $first_name = $existing['first_name'];
        $last_name = $existing['last_name'];
        $user_role = $existing['user_role'];

        $sqlUpdate = "UPDATE user_tbl SET verification = 'verified' WHERE verification = :status";
        $updateQuery = $conn->prepare($sqlUpdate);
        $updateQuery->bindParam(':status', $status);
        $updateQuery->execute();

        if ($updateQuery->rowCount() > 0) {
            if ($user_role === "2") {

                $sqlInsert2 = "INSERT INTO faculty_tbl (first_name, last_name, user_id) VALUES (:first_name, :last_name, :user_id)";
                $insertQuery2 = $conn->prepare($sqlInsert2);
                $insertQuery2->bindParam(':first_name', $first_name);
                $insertQuery2->bindParam(':last_name', $last_name);
                $insertQuery2->bindParam(':user_id', $user_id);
                $insertQuery2->execute();
            }

            $response['status'] = 'success';
        } else {
            $response['status'] = 'error';
        }

    }

}

echo json_encode($response);
?>
