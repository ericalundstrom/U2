<?php

ini_set("dispaly_errors", 1);

require_once "function.php";


$filename = "users.json";
$users = [];
$user_json = file_get_contents($filename);
$users = json_decode($user_json, true);


$requestJSON = file_get_contents("php://input");
$requestDATA = json_decode($requestJSON, true);


$method = $_SERVER["REQUEST_METHOD"];

if ($method == "POST") {

    $username = $requestDATA["username"];
    $password = $requestDATA["password"];

    foreach($users as $user){

        if ($user["username"] == $username && $user["password"] == $password) {

            $points = $user["points"];

            $loggedInUser = [
                "username" => $username,
                "password" => $password,
                "points" => $points,
            ];

            $users[] = $loggedInUser;
            $json = json_encode($users, JSON_PRETTY_PRINT);
            sendJSON($loggedInUser);
        }
       
    }
  
}


?>