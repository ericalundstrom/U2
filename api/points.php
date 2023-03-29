<?php

ini_set("dispaly_errors", 1);

require_once "function.php";


$method = $_SERVER["REQUEST_METHOD"];
$filename = "users.json";

$requestJSON = file_get_contents("php://input");
$requestDATA = json_decode($requestJSON, true);

$json = file_get_contents($filename);
$databaseOfUsers = json_decode($json, true);

    

if ($method == "POST") {
        
    $username = $requestDATA["username"];
    $password = $requestDATA["password"];

    for($i = 0; $i < count($databaseOfUsers); $i){

        if ($databaseOfUsers[$i]["username"] == $username) {
            $databaseOfUsers[$i]["points"] = $databaseOfUsers[$i]["points"] + $requestDATA["points"];
            $newpoints = 
            file_put_contents($filename, json_encode($databaseOfUsers, JSON_PRETTY_PRINT));
            sendJSON(["points" => $databaseOfUsers[$i]["points"]]);
        }
    }
}
$error = ["error" => "Can't load points"];
sendJSON($error, 400);

?>