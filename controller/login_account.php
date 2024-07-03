<?php
session_start();
include '../connect.php'; // Adjust the path as necessary

$email = $_POST['email'];
$password = $_POST['password'];

// Check if the email exists
$sqlCheck = "SELECT * FROM user_tbl WHERE email = :email";
$checkQuery = $conn->prepare($sqlCheck);
$checkQuery->bindParam(':email', $email);
$checkQuery->execute();
$user = $checkQuery->fetch(PDO::FETCH_ASSOC);

$response = [];

if (!$user) {
    // Email not found
    $response['status'] = 'email';
} else {
    // Email found, now check the password
    if (password_verify($password, $user['password'])) {

        // Password is correct, login successful
        if ($user['verification'] === "verified") { 
            // verification is verified login success
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['first_name'] = $user['first_name'];
            $_SESSION['last_name'] = $user['last_name'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['user_role'] = $user['user_role'];

            $response['status'] = 'success';
            $response['user_role'] = $_SESSION['user_role'];
        }else{
            $response['status'] = 'verification';
        }
    } else {
        // Password is incorrect
        $response['status'] = 'password';
    }
}

echo json_encode($response);
?>
