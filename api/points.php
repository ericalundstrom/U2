<?php

ini_set("dispaly_errors", 1);

require_once "function.php";


$method = $_SERVER["REQUEST_METHOD"];
$filename = "users.json";

if (!file_exists($filename)) {
    $message = ["message" => "$filename does not exist. Can't load points."];
    sendJSON($message, 404);
}

$requestJSON = file_get_contents("php://input");
$requestDATA = json_decode($requestJSON, true);

$json = file_get_contents($filename);
$databaseOfUsers = json_decode($json, true);

$method = $_SERVER["REQUEST_METHOD"];

$allowedMethods = ["GET", "POST"];

if (!in_array($method, $allowedMethods)) {
    $message = ["message" => "Invalid HTTP method"];
    sendJson($message, 405);
}


if ($method == "POST") {
        
    $username = $requestDATA["username"];
    $password = $requestDATA["password"];

    for($i = 0; $i < count($databaseOfUsers); $i++){

        if ($databaseOfUsers[$i]["username"] == $username) {
            $databaseOfUsers[$i]["points"] = $databaseOfUsers[$i]["points"] + $requestDATA["points"];
            file_put_contents($filename, json_encode($databaseOfUsers, JSON_PRETTY_PRINT));
            sendJSON(["points" => $databaseOfUsers[$i]["points"]]);
        }
    }
}
    

if ($method == "GET") {
    
    function compare($a, $b) {
        if ($a["points"] == $b["points"]) {
            return 0;
        }
        return ($a["points"] > $b["points"]) ? -1 : 1;
    }
    
    usort($databaseOfUsers, "compare");
 
    $firstFive = array_slice($databaseOfUsers, 0, 5);
    
    $usernameAndPoints = [];
    foreach($firstFive as $user){
        $oneUser = [
            "username" => $user["username"],
            "points" => $user["points"],
        ];
        $usernameAndPoints[] = $oneUser;
    };
    
    sendJSON($usernameAndPoints);
}

?>