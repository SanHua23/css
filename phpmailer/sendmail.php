<?php
use PHPMailer\PHPMailer\PHPMailer;

require_once "PHPMailer.php";
require_once "SMTP.php";
require_once "Exception.php";

if (isset($_POST['email'])) {
$sendto = $_POST['email'];

$mail = new PHPMailer();

$mail->isSMTP();
$mail->Host = "smtp.gmail.com";
$mail->SMTPAuth = true;
$mail->Username = "kkit8588@gmail.com";
$mail->Password = 'aiorrgpinpteusih';
$mail->Port = 587;
$mail->SMTPSecure = "tls";

$mail->isHTML(true);
$mail->setFrom('kkit8588@gmail.com', 'BMCP');     
$mail->addAddress($sendto);
$mail->Subject = "Barangay Management Change Password";
$mail->Body    = '
      <div style="text-align:center;font-size:24px;">
        Good day!<br><br>
        <div style="background-color:#D1E2C4;border-radius:20px;width:300px;margin:auto;color:#fff;font-size:24px;padding:20px 10px;">
          <a href="http://localhost/Barangay-Management/changepassword.php?email='.$sendto.'">Click here to change your password</a>
        </div>
      </div>';
}
if ($mail->send()) {
  echo 'success';
}


?>