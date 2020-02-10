<?php

require_once('./data.php');

header("Content-type: application/json");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

  if (!isset($_POST['message']) || !isset($_POST['username'])) {
    http_response_code(400);
    echo json_encode(["error" => "Must post a message and username"]);
    exit();
  }

  $message = $_POST["message"];
  $username = $_POST["username"];
  saveNewSqueak($message, $username);

  http_response_code(201);
  echo json_encode(["data" => ["message" => $message, "username" => $username]]);

} else if ($_SERVER['REQUEST_METHOD'] == 'GET') {

  // New Features Go Here
  $username = '';
  $order = '';

  if(isset($_GET['username'])){
    $username = $_GET['username'];
  }

  if(isset($_GET['order'])){
    $order = $_GET['order'];
  }

  if(isset($_GET['username']) && isset($_GET['order'])){
    $username = $_GET['username'];
    $order = $_GET['order'];
  }
  
  $squeaks = getAllSqueaks($username, $order);
  echo json_encode(["data" => $squeaks]);
}
