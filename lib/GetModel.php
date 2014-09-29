<?php

include 'CSVLib.php';

$category = "";
if (isset($_GET['category'])) {
    $category = $_GET['category'];
}

$pathToModel = realpath('../') . '/db/autoweek/Margid/' . $category . ".csv";

$modelArray = CSVToArray($pathToModel);

$array = [];

foreach ($modelArray as $row) {
    $array[$row["id"] . "_" . $row["Mudel"]] = $row["Mudel"];
}

echo json_encode($array);
return  json_encode($array);