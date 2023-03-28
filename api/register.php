<?php

ini_set("display_errors", 1);

require_once "function.php";


$method = $_SERVER["REQUEST_METHOD"];

if ($method == "OPTIONS"){
    header("Access-Control-Allow-Origin: ");
    header("Access-Control-Allow-Methods:");
    header("Access-Control-Allow-Headers: ");
    exit();

} else {
    header("Access-Control-Allow-Origin:");
}


$filename = "users.json";
$users = [];
$user_json = file_get_contents($filename);
$users = json_decode($user_json, true);

$requestJSON = file_get_contents("php://input");
$requestDATA = json_decode($requestJSON, true);

if ($method == "POST") {
    
    if (!file_exists($filename)) {
        $json = file_put_contents($filename, $users);
    }else{
        $json = file_get_contents($filename);
        $data = json_decode($json, true);
    }

    $username = $requestDATA["username"];
    $password = $requestDATA["password"];

    foreach ($users as $user) {
        if ($user["username"] == $username) {
            $error = ["error" => "Conflict; (The username is already taken)"];
            sendJSON($error, 409);
        }
    }

    if ($password == "" or $username == "") {
        $error = ["error" => "wrong username or password"];
        sendJSON($error, 400);
    }

    $newUser = [
        "username" => $username,
        "password" => $password,
        "points" => 0,
    ];
    
    $users[] = $newUser;
    $json = json_encode($users, JSON_PRETTY_PRINT);
    file_put_contents($filename, $json);

    sendJSON($newUser);

}

?>