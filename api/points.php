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

    for($i = 0; $i < count($databaseOfUsers); $i++){

        if ($databaseOfUsers[$i]["username"] == $username) {
            $databaseOfUsers[$i]["points"] = $databaseOfUsers[$i]["points"] + $requestDATA["points"];
            file_put_contents($filename, json_encode($databaseOfUsers, JSON_PRETTY_PRINT));
            sendJSON(["points" => $databaseOfUsers[$i]["points"]]);
        }
    }
}
    

if ($method == "GET") {
    
    function cmp($a, $b) {
        if ($a["points"] == $b["points"]) {
            return 0;
        }
        return ($a["points"] > $b["points"]) ? -1 : 1;
    }
    
    usort($databaseOfUsers, "cmp");
 
    $firstFive = array_slice($databaseOfUsers, 0, 5);
    
    $usernamdeAndPoints = [];
    foreach($firstFive as $user){
        $oneUser = [
            "username" => $user["username"],
            "points" => $user["points"],
        ];
        $usernamdeAndPoints[] = $oneUser;
    };
    
    sendJSON($usernamdeAndPoints);
}

$message = ["message" => "Can't load points"];
sendJSON($message, 400);
?>