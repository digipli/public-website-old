<?php
// include('./dbconnect.php');

require("PHPMailer/src/PHPMailer.php");
require("PHPMailer/src/SMTP.php");
require("PHPMailer/src/Exception.php");

$mail = new PHPMailer\PHPMailer\PHPMailer(true);

$name = trim($_POST["name"]);
$email = trim($_POST["email"]);
$message = trim($_POST["message"]);
$business = trim($_POST["business"]);
$siteName = trim($_POST["siteName"]);

$message = str_replace("\\r\\n", ' ', $message);

if (!$name || !$email || !$message) {
  $response = [
    'message' => 'All fields are required!',
    'status' => "error",
  ];
  header('Content-Type: application/json');
  echo json_encode($response);
  exit;
}

$body = file_get_contents('mail-template/contact-us-email.html');
$body = str_replace('%name%', $name, $body);
$body = str_replace('%email%', $email, $body);
$body = str_replace('%business%', $business, $body);
$body = str_replace('%message%', $message, $body);
$body = str_replace('%from%', $siteName, $body);


// host
$host = 'tls://smtp.gmail.com:587';
$port = 587;

// authentication

$firstRecipientUsername = "jmwerden@gmail.com";
$secondRecipientUsername = "team@digipli.com";
// $thirdRecipientUsername = "jeff.horvath@digipli.com";
// $secondRecipientUsername = "jeff.horvath@digipli.com";
// $thirdRecipientUsername = "jeffrey.ruiz@digipli.com";
$authUsername = "dev.jeremy.ahc@gmail.com";
$authPassword = "jeremy5000";
$senderName = "jeremy";


// recipient

// email
$subject = $siteName;

try {
  // $mail->SMTPDebug = 0;
  $mail->isSMTP();
  $mail->Host       = $host;
  $mail->SMTPAuth   = true;
  $mail->Username   = $authUsername;
  $mail->Password   = $authPassword;
  $mail->SMTPSecure = "tls";

  $mail->setFrom($authUsername, $senderName);

  $mail->addAddress($firstRecipientUsername);
  $mail->addAddress($secondRecipientUsername);
  // $mail->addAddress($thirdRecipientUsername);

  // Content
  $mail->isHTML(true);
  $mail->Subject = $subject;
  $mail->Body    = $body;
  $mail->send();


  $response = [
    'message' => 'Message has been sent',
    'status' => "success",
  ];
  header('Content-Type: application/json');
  echo json_encode($response);
  exit;
} catch (Exception $e) {
  echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
  $response = [
    'message' => 'Email could not be sent.',
    'status' => "error",
  ];
  header('Content-Type: application/json');
  echo json_encode($response);
}
