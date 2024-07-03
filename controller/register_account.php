<?php
include '../connect.php'; // Adjust the path as necessary
use PHPMailer\PHPMailer\PHPMailer;

require_once "../phpmailer/PHPMailer.php";
require_once "../phpmailer/SMTP.php";
require_once "../phpmailer/Exception.php";

// Retrieve POST data
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$email = $_POST['email'];
$user_role = $_POST['user_role'];
$password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Hash the password securely
$verificationCode = rand(100000, 999999);

$word_role = ($user_role === '2') ? "Faculty" : (($user_role === '3') ? "Student" : "Unknown");

// Check if email already exists
$sqlCheck = "SELECT * FROM user_tbl WHERE email = :email";
$checkQuery = $conn->prepare($sqlCheck);
$checkQuery->bindParam(':email', $email);
$checkQuery->execute();
$existing = $checkQuery->fetch(PDO::FETCH_ASSOC);

$response = [];

if ($existing) {
    // Email already exists
    $response['status'] = 'exists';
} else {
    // Email user
    $mail = new PHPMailer();

    $mail->isSMTP();
    $mail->Host = "smtp.gmail.com";
    $mail->SMTPAuth = true;
    $mail->Username = "kkit8588@gmail.com";
    $mail->Password = 'aiorrgpinpteusih';
    $mail->Port = 587;
    $mail->SMTPSecure = "tls";

    $mail->isHTML(true);
    $mail->setFrom('kkit8588@gmail.com', 'PUP');
    $mail->addAddress($email);
    $mail->Subject = "PUP Account Verification Code";
    $mail->Body = "
        <div style='font-size: 15px;'>Hello " . htmlspecialchars($first_name) ." ". htmlspecialchars($last_name) . ",</div>
        <div style='font-size: 15px;'>Thank you for registering as a " . htmlspecialchars($word_role) . ".</div>
        <div style='font-size: 15px;'>This is your verification code: <b>" . $verificationCode . "</b></div>
        <div style='font-size: 15px;'><a href='http://localhost:8080/css/verification.php'>Please verify your account</a>.</div>
        <div style='font-size: 15px;'>Thank you for registering with PUP!</div>";

    if ($mail->send()) {

        // Insert new user
        $sqlInsert = "INSERT INTO user_tbl (first_name, last_name, email, password, user_role, verification) VALUES (:first_name, :last_name, :email, :password, :user_role, :verificationCode)";
        $insertQuery = $conn->prepare($sqlInsert);
        $insertQuery->bindParam(':first_name', $first_name);
        $insertQuery->bindParam(':last_name', $last_name);
        $insertQuery->bindParam(':email', $email);
        $insertQuery->bindParam(':password', $password);
        $insertQuery->bindParam(':user_role', $user_role); 
        $insertQuery->bindParam(':verificationCode', $verificationCode); 

        if ($insertQuery->execute()) {
            $response['status'] = 'success';

        } else {
            // Registration failed
            $response['status'] = 'error';
        }
    }
}

echo json_encode($response);
?>
