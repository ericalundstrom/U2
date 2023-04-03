<?php 
ini_set("display_errors", 1);
require_once("function.php");

$method = $_SERVER["REQUEST_METHOD"];

// $contentType = $_SERVER["CONTENT_TYPE"];

// if ($contentType == "application/json") {
//     $message = ["message" => "Wrong kind of Request Method."];
//     sendJSON($message, 404);
// }


if ($method == "GET") {

    
    $filename = '../images/';
    $direction = scandir($filename);
    $dogs_json = "dogs.json";

    if (!file_exists($dogs_json)) {
        $message = ["message" => "$dogs_json does not exist."];
        sendJSON($message, 404);
    }
    
    $array_of_all_the_dogs = [];
    foreach($direction as $dog){
        $dogsName = $dog;
        $replaceWords = [ "_", ".jpg"];
        $newName = str_replace($replaceWords, " ", $dogsName);
        $newDoggo = [
            "name" => trim($newName),
            "url" => $dog,
        ];
        $array_of_all_the_dogs[] = $newDoggo;
    }
    $data = json_encode($array_of_all_the_dogs, JSON_PRETTY_PRINT);
    file_put_contents($dogs_json, $data);
    
    array_splice($array_of_all_the_dogs, 0, 2);
    
    
    $alternatives = [];
    $i = 0;
    while (count($alternatives) < 4) {
            
        $random = array_rand($array_of_all_the_dogs, 1);
        $new_dog = [
            "name" => $array_of_all_the_dogs[$random]["name"],
            "url" => $array_of_all_the_dogs[$random]["url"],
        ];
        if (!in_array($new_dog, $alternatives)) {
            $alternatives[] = $new_dog;
        }
            $i++;
    }
    
    $imageOfdog = $alternatives[array_rand($alternatives, 1)];
    
    $fourDogs = [];
    foreach($alternatives as $dog) {
        $fourDogs[] = [
            "correct" => check_answer( $imageOfdog,$dog["name"]),
            "name" => $dog["name"],
        ];
    }
    
    $alternativesAndImage = [
        "image" => "images/" . $imageOfdog["url"],
        "alternatives" => $fourDogs,
    ];
    
    sendJSON($alternativesAndImage);
    
}    




function check_answer($imageOfdog, $dog){
   if (str_contains($imageOfdog["name"], $dog)) {
       return true;
   } else {
       return false;
   }
   };


$message = ["message" => "Wrong kind of Request Method."];
sendJSON($message, 400);
?>