
<?php 
ini_set("display_errors", 1);

require_once "function.php";

$userDatabase = "users.json";

$users = json_decode(file_get_contents($userDatabase), true);

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $requestDATA = json_decode(file_get_contents("php://input"), true);

    $username = $requestDATA["username"];
    $password = $requestDATA["password"];

    foreach($users as $user){
        if ($user["username"] == $username && $user["password"] == $password ) {

            $points = $user["points"];
            $loggedInUser = [
                "username" => $username,
                "password" => $password,
                "points" => $points
            ];

            $users[] = $loggedInUser;
            $user_json = json_encode($users, JSON_PRETTY_PRINT);
            sendJSON($loggedInUser);

        }
    }
    $message = ["message" => "Not found!"];
    sendJSON($message, 404);
    
}
$message = ["message" => "Wrong kind of Request Method."];
sendJSON($message, 404);
    

?>