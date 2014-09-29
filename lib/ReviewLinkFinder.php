<?php

function findReviewLinks($details)
{
    
    $links = [];
    
    $autolehtDB = realpath('./') . '/db/autoleht_db.csv';
    $whatcarUsedDB = realpath('./') . '/db/whatcar_used_db.csv';
    $whatcarNewDB = realpath('./') . '/db/whatcar_new_db.csv';
    
    $friendlyModel = getReviewFriendlyName($details["make"], $details["model"]);
    
    $autolehtArray = CSVToArray($autolehtDB);
    
        
    foreach ($autolehtArray as $row) {         
        if (strpos($row["mark"], $details["make"]) !== FALSE &&
                strpos($row["mudel"], $friendlyModel) !== FALSE) {
            if (!is_numeric($row["lopp"])) {
                $row["lopp"] = date("Y");
            }
            if ($details["year"] >= $row["algus"] && $details["year"] <= $row["lopp"]) {
                $links[] = "http://www.autoleht.ee" . $row["link"];
            }
        }
    }
    
    $whatcarUsedArray = CSVToArray($whatcarUsedDB);
    
    foreach ($whatcarUsedArray as $row) {
        if (strpos($row["mark"], $details["make"]) !== FALSE &&
                strpos($row["mudel"], $friendlyModel) !== FALSE) {
            if (!is_numeric($row["kuni"])) {
                $row["lopp"] = date("Y");
            }
            if ($details["year"] >= $row["alates"] && $details["year"] <= $row["kuni"]) {
                $links[] = "http://www.whatcar.ee" . $row["link"];
            }
        }
    }
    
    $whatCarNewArray = CSVToArray($whatcarNewDB);
    
    foreach ($whatCarNewArray as $row) {
        if (strpos($row["mark"], $details["make"]) !== FALSE &&
                strpos($row["mudel"], $friendlyModel) !== FALSE) {            
           
            $links[] = "http://www.whatcar.ee" . $row["link"];
        }
    }
    
   return $links; 
}

function getReviewFriendlyName($make, $model)
{
    if (strpos($make, "BMW") !== FALSE) {
        if (is_numeric($model)) {
            return $model[0];
        }
    } elseif (strpos($make, "Mercedes") !== FALSE) {
        return (trim(explode(" ", $model)[0]));
    } else {
        return $model;
    }
}