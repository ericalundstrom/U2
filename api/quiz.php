<?php

ini_set("display_errors", 1);

require_once "function.php";


$filename = '../images';
$dir = scandir($filename);


$alternativesOfDogs = [];

//Chek for the right answere and add to newdog
for ($i=0; $i < 4 ; $i++) { 
    $randomDog = rand(2, count($dir));
    $newDog = [
        "name" => $dir[$randomDog],
    ];
    $alternativesOfDogs[] = $newDog;
}


$randomDogFromArray = array_rand($alternativesOfDogs);
$rightanswere = "images/" . $alternativesOfDogs[$randomDogFromArray]["name"];

//Fix the name in the alternatives
$alt = [
    "image" => $rightanswere,
    "alternatives" => $alternativesOfDogs,
];


$dogs = json_encode($alt);
sendJSON($alt);



?>