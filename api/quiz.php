<?php 
ini_set("display_errors", 1);
require_once("function.php");

$method = $_SERVER["REQUEST_METHOD"];

if ($method == "GET") {

    $filename = '../images/';
    $originalArrayOfDogs = scandir($filename);
    $dogs_json = "dogs.json";

    if (count($originalArrayOfDogs) == 0) {
        $message = ["message" => "No images available"];
        sendJSON($message, 404);
    }
    
    $array_of_all_the_dogs = [];
    foreach($originalArrayOfDogs as $dog){
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
            "correct" => check_answer($imageOfdog,$dog["name"]),
            "name" => $dog["name"],
        ];
    }
    
    $alternativesAndImage = [
        "image" => "images/" . $imageOfdog["url"],
        "alternatives" => $fourDogs,
    ];
    
    sendJSON($alternativesAndImage);
    
}    

$message = ["message" => "Wrong kind of Request Method."];
sendJSON($message, 405);


function check_answer($imageOfdog, $dog){
   if (str_contains($imageOfdog["name"], $dog)) {
       return true;
   } else {
       return false;
   }
   };

?>