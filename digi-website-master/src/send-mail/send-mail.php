<?php
include('dbconnect.php');

require("PHPMailer/src/PHPMailer.php");
require("PHPMailer/src/SMTP.php");
require("PHPMailer/src/Exception.php");

$mail = new PHPMailer\PHPMailer\PHPMailer(true);

$name = $conn->real_escape_string(trim($_POST["name"]));
$email = $conn->real_escape_string(trim($_POST["email"]));
$company = $conn->real_escape_string(trim($_POST["company"]));
$country = $conn->real_escape_string(trim($_POST["country"]));

$table = 'digipli_mails';
$field = ['name', 'email', 'company', 'country'];
$data = [$name, $email, $company, $country];

if (!$name || !$email || !$company || !$country) {
  $response = [
    'message' => 'All fields are required!',
    'status' => "error",
  ];
  header('Content-Type: application/json');
  echo json_encode($response);
  exit;
}

$body = file_get_contents('mail-template/email.html');
$body = str_replace('%name%', $name, $body);
$body = str_replace('%email%', $email, $body);
$body = str_replace('%company%', $company, $body);
$body = str_replace('%country%', $country, $body);

// host
$host = 'tls://smtp.gmail.com:587';
$port = 587;

// authentication


$firstRecipientUsername = "jmwerden@gmail.com";
$secondRecipientUsername = "team@digipli.com";
// $thirdRecipientUsername = "jeff.horvath@digipli.com";

$authUsername = "dev.jeremy.ahc@gmail.com";
$authPassword = "jeremy5000";
$senderName = "jeremy";

$senderName = "Jeremy";
$subject = 'DigiPli';

try {
  // $mail->SMTPDebug = 0;
  $mail->isSMTP();
  $mail->Host       = $host;
  $mail->SMTPAuth   = true;
  $mail->Username   = $authUsername;
  $mail->Password   = $authPassword;
  $mail->SMTPSecure = "tls";

  $mail->setFrom($authUsername, $senderName);
  $mail->addAddress($firstRecipientUsername, $firstRecipientName);
  $mail->addAddress($secondRecipientUsername);
  // $mail->addAddress($thirdRecipientUsername);


  // Content
  $mail->isHTML(true);
  $mail->Subject = $subject;
  $mail->Body    = $body;
  $mail->send();

  insertData($table, $field, $data);

  $response = [
    'message' => 'Thank you, Downloading file...',
    'status' => "success",
  ];
  header('Content-Type: application/json');
  echo json_encode($response);
  exit;
} catch (Exception $e) {
  $response = [
    'message' => 'Oops something went wrong!',
    'status' => "error",
  ];
  header('Content-Type: application/json');
  http_response_code(400);
  echo json_encode($response);
  exit;
}
