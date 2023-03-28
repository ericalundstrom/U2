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

    // $pointsfile = "points.json";
    // $data_points = ["points" => 0];

    // if (!file_exists($pointsfile)) {
    //     file_put_contents($pointsfile, $data_points);
    // }else{
    //     $points_content = file_get_contents($pointsfile);
    //     $points = json_decode($points_content, true);
    // }

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