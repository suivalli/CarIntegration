<?php

$VEHICLE_TYPE = 1;

function getInfo($make, $model, $fueltype, $year, $power, $trans)
{
    $makeArray = CSVToArray(realpath('./') . "/db/Margid.csv");
    

    $makeURL = NULL;
    foreach ($makeArray as $row) {
        $name = $row["Nimi"];
        if (strcmp(strtolower($name), strtolower($make)) == 0) {
            $makeURL = $row["URL"];        
        }
    }
    
    $modelArray = CSVToArray(realpath('./') . "/db/" . $makeURL . ".csv");
    

    $modelURL = NULL;
    foreach ($modelArray as $row) {
        $name = $row["Nimi"];        
        if (strcmp(strtolower($name), strtolower($model)) == 0) {
            $modelURL = $row["URL"];
        }
    }
    
    if ($makeURL == NULL || $modelURL == NULL) {
        return NULL;
    }

    
    $spritBaseURL = "http://www.spritmonitor.de/de/uebersicht/";

    $fuelURL = "fueltype=" . $fueltype . "&";
    $vehicleTypeURL = "vehicletype=1&";
    $yearURL = "constyear_s=" . $year . "&constyear_e=" . $year . "&";
    $powerURL = "power_s=" . $power . "&power_e=" . $power . "&";
    $transmissionURL = "gearing=" . $trans;
    $spritFullURL = $spritBaseURL . $makeURL . "/" . $modelURL . ".html?" 
            . $fuelURL . $vehicleTypeURL . $yearURL . 
            $powerURL . $transmissionURL;

    return $spritFullURL;
}

function getSpritData($details)
{
 
    //Get transmission
    $transmissionScrap = explode("(", trim($details["transmission"]));
    $transmissionName = trim($transmissionScrap[0]);
    $tsansmission = 0;
    if (strcmp($transmissionName, "manuaal") == 0) {
        $tsansmission = 1;
    } elseif (strcmp($transmissionName, "automaat") == 0 ||
            strcmp($transmissionName, "poolautomaat") == 0) {
        $tsansmission = 2;
    } else {
        $tsansmission = 1;
    }

    //get fuel type
    $fuelType = 0; 
    $details["fuel"] = trim($details["fuel"]);
    if (strpos($details["fuel"], "diisel") !== FALSE) {
        $fuelType = 1;
    } elseif (strpos($details["fuel"], "bensiin") !== FALSE) {
        $fuelType = 2;
    } elseif (strpos($details["fuel"], "gaas") !== FALSE) {
        if (strpos($details["fuel"], "LPG") !== FALSE) {
            $fuelType = 3;
        } else {
            $fuelType = 4;
        }
    } elseif (strpos($details["fuel"], "elekter") !== FALSE) {
        $fuelType = 5;
    } else {
        $fuelType = 2;
    }    
    $spritLink = getInfo(
        $details["make"], $details["model"], $fuelType,
        $details["year"], $details["power"], $tsansmission
    );
    if ($spritLink == NULL) {
        return NULL;
    }
    
    
    
    $spritHTML = file_get_html($spritLink);
    
    $spritCache1 = $spritHTML->find(".searchsummary", 0);
    
    
    if ($spritCache1 !== NULL) {
        $arr = preg_split("/ /", $spritCache1->children(2)->plaintext);
    } else {
        return NULL;
    }
    
            
    
    if (strcmp(trim($arr[3]), "Diesel") == 0) {
        $arr[3] = "Diisel";
    } elseif (strcmp(trim($arr[3]), "Benzin") == 0) {
        $arr[3] = "Bensiin";
    } elseif (strpos($arr[3], "LPG") !== FALSE) {
        $arr[3] = "LPG";
    } elseif (strpos($arr[3], "CNG" !== FALSE)) {
        $arr[3] = "CNG";
    } elseif (strpos($arr[3], "Elektri" !== FALSE)) {
        $arr[3] = "Elekter";
    }
    
    $arr[-1] = $spritLink;
    return $arr;
}
