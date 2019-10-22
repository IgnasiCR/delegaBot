<?php

include_once 'conexion.php';

$website = "https://api.telegram.org/bot".token;

$update = file_get_contents('php://input');
$update = json_decode($update, TRUE);

$chatId = $update["message"]["chat"]["id"];
$messageId = $update["message"]["message_id"];
$userId = $update["message"]['from']['id'];

$chatType = $update["message"]["chat"]["type"];
$chatTitle = $update["message"]["chat"]["title"];
$chatUsername = $update["message"]["chat"]["username"];

$firstname = $update["message"]['from']['username'];

if ($firstname=="") {
    $firstname = $update["message"]['from']['first_name'];
}else{
    $firstname = "@".$firstname;
}

if ($chatUsername=="") {
    $chatUsername = $update["message"]['chat']['first_name'];
}else{
    $chatUsername = "@".$chatUsername;
}

$message = $update["message"]["text"];

 ?>
