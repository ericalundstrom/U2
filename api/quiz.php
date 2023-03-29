<?php 
ini_set("display_errors", 1);
require_once("function.php");
?>

<?php 

$filename = '../images/';
$dir = scandir($filename);
$dogs_json = "dogs.json";

$array_of_all_the_dogs = [];
foreach($dir as $dog){
    $dogsname = $dog;
    $replace_words = [ "_", ".jpg"];
    $newName = str_replace($replace_words, " ", $dogsname);
    $newdoggo = [
        "name" => trim($newName),
        "url" => $dog,
    ];
    $array_of_all_the_dogs[] = $newdoggo;
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

$imageofdog = $alternatives[array_rand($alternatives, 1)];

$dogname = [];
foreach($alternatives as $dog) {
    $dogname[] = [
        "correct" => check_answer( $imageofdog,$dog["name"]),
        "name" => $dog["name"],
    ];
}

$alt = [
    "image" => "images/" . $imageofdog["url"],
    "alternatives" => $dogname,
];

 function check_answer($imageofdog, $dog){
    if (str_contains($imageofdog["name"], $dog)) {
        return true;
    } else {
        return false;
    }
    };

sendJSON($alt);


?>