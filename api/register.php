<?php

ini_set("display_errors", 1);

require_once "function.php";

$method = $_SERVER["REQUEST_METHOD"];

$filename = "users.json";
$users = [];

if (!file_exists($filename)) {
    file_put_contents($filename, $users);
}else{
    $user_json = file_get_contents($filename);
    $users = json_decode($user_json, true);
};

$requestJSON = file_get_contents("php://input");
$requestDATA = json_decode($requestJSON, true);

if ($method == "POST") {

    $username = $requestDATA["username"];
    $password = $requestDATA["password"];

    foreach ($users as $user) {
        if ($user["username"] == $username) {
            $message = ["message" => "Conflict; (The username is already taken)"];
            sendJSON($message, 409);
        }
    }

    if ($password == "" or $username == "") {
        $message = ["message" => "Please type an username and a password"];
        sendJSON($message, 400);
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

$message = ["message" => "Wrong kind of Request Method."];
sendJSON($message, 405);

?>