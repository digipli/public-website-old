<?php
$servername = "localhost";
$username = "root";
$password = "digipli@123";
$dbname = "digipli";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  $response = [
    'message' => 'Something went wrong!',
    'status' => "error",
  ];
  header('Content-Type: application/json');
  http_response_code(400);
  echo json_encode($response);
  exit;
}

function insertData($table, $field, $data)
{
  global $conn;

  $field_values = implode(',', $field);
  $data_values = implode("','", $data);

  $sql = "INSERT INTO $table (" . $field_values . ")
    VALUES ('" . $data_values . "') ";

  if (!$conn->query($sql)) {
    $response = [
      'message' => 'Something went wrong!',
      'status' => "error",
    ];
    header('Content-Type: application/json');
    http_response_code(400);
    echo json_encode($response);
    exit;
  }
}

// query for creating table
// CREATE TABLE `digipli`.`digipli_mails` ( `id` INT(11) NOT NULL AUTO_INCREMENT , `name` VARCHAR(255) NULL DEFAULT NULL , `email` VARCHAR(255) NULL DEFAULT NULL , `company` VARCHAR(255) NULL DEFAULT NULL , `country` VARCHAR(255) NULL DEFAULT NULL , `createdAt` DATETIME NULL DEFAULT CURRENT_TIMESTAMP, `updatedAt` DATETIME NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`)) ENGINE = InnoDB;
