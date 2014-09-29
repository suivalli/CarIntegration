<?php

function getStatsMotors ($html)
{
    
    $info = $html->find('#leftContent', 0);
    
    $details = [];
    
    $makeModelName = $info->find('h1', 0);
    $makeModel = getModelData($makeModelName->plaintext);
    $details["make"] = $makeModel[0];
    $details["model"] = $makeModel[1];
    $price = $info->find('#price', 0)->plaintext;
    $details["price"] = trim($price);
    
    // layout, transmission
    // color
    
    // Get data needed for finding model information
    
    $allData = $info->find('text');
    $allDataText = [];
    foreach ($allData as $row) {
        $allDataText[] = $row->plaintext;
    }
    $details["text_data"] = $allDataText;
    
    $yearEnd = explode("<", explode("'", $makeModelName)[1])[0];
    if ($yearEnd < 20) {
        $details["year"] = "20" . $yearEnd;
    } else {
        $details["year"] = "19" . $yearEnd;
    }
    
    //Get bodytype if possible
    foreach ($allDataText as $row) {
        if (strpos($row, "sedaan") !== FALSE) {
            $details["bodytype"] = "sedaan";
            break;
        } elseif (strpos($row, "universaal") !== FALSE) {
            $details["bodytype"] = "universaal";
            break;
        } elseif (strpos($row, "luukpära") !== FALSE) {
            $details["bodytype"] = "luukpära";
            break;
        } elseif (strpos($row, "kupee") !== FALSE) {
            $details["bodytype"] = "kupee";
            break;
        } elseif (strpos($row, "kabriolett") !== FALSE) {
            $details["bodytype"] = "kabriolett";
            break;
        } elseif (strpos($row, "limusiin") !== FALSE) {
            $details["bodytype"] = "limusiin";
            break;
        } elseif (strpos($row, "pickup") !== FALSE) {
            $details["bodytype"] = "pickup";
            break;
        } elseif (strpos($row, "mahtuniversaal") !== FALSE) {
            $details["bodytype"] = "mahtuniversaal";
            break;
        } elseif (strpos($row, "maastur") !== FALSE) {
            $details["bodytype"] = "universaal";
            break;
        }
    }
    
    $enginePower = "";
    //Get power if possible
    foreach ($allDataText as $row) {
        if (strpos($row, "kW")) {
            $enginePower = $row;
            $details["power"] = filter_var($row, FILTER_SANITIZE_NUMBER_INT);
            break;
        }
    }
    
    //Find displacement if possible
    $dispArray = preg_grep("/[0-9]\.[0-9]l/", $allDataText);
    foreach ($dispArray as $row) {
     $details["displacement"] = explode("l", $row)[0];
     break;
    }
    
    $details["engine_power"] = $details["displacement"] . " " . $enginePower;
    $details["type"] = "Sõiduato";
    
    //Find fuel type if possible
    foreach ($allDataText as $row) {
        if (strpos($row, "bensiin")) {
            $details["fuel"] = "bensiin";
            break;
        } elseif (strpos($row, "diisel")) {
            $details["fuel"] = "diisel";
            break;
        } elseif (strpos($row, "hübriid")) {
            $details["fuel"] = "hübriid";
            break;
        }
    }
    
    //Find mileage if possible
    for ($i = 0; $i<count($allDataText); $i++) {
      if (strpos($allDataText[$i], "Läbisõidunäidik")) {
          $details["mileage"] = $allDataText[$i+1];
          break;
      }  
    }
    
    //Find layout if possible
    foreach ($allDataText as $row) {
        if (strpos($row, "tagavedu")) {
            $details["layout"] = "tagavedu";
            break;
        } elseif (strpos($row, "esivedu")) {
            $details["layout"] = "esivedu";
            break;
        } elseif (strpos($row, "nelikvedu")) {
            $details["layout"] = "nelikvedu";
            break;
        }
    }
    
    //Find transmission if possible
    foreach ($allDataText as $row) {
        if (strpos($row, "manuaal")) {
            $details["transmission"] = "manuaal";
            break;
        } elseif (strpos($row, "automaat")) {
            $details["transmission"] = "automaat";
            break;
        } elseif (strpos($row, "tiptronic")) {
            $details["transmission"] = "automaat";
            break;
        } elseif (strpos($row, "steptronic")) {
            $details["transmission"] = "automaat";
            break;
        } elseif (strpos($row, "sequential")) {
            $details["transmission"] = "automaat";
            break;
        } elseif (strpos($row, "poolautomaat")) {
            $details["transmission"] = "automaat";
            break;
        }        
    }
    
    if (isset($details["bodytype"]) && isset($details["power"])
             && isset($details["displacement"])  && isset($details["layout"])
             && isset($details["transmission"])  && isset($details["mileage"])
             && isset($details["fuel"])) {
        $details["essentials"] = TRUE;
    }
    
    
    
    return $details;
    
}

function getPicsMotors($html)
{
    $pics = $html->find('.highslide');   
    $picsURLs = [];
    foreach ($pics as $row) {
        $picsURLs[] = $row->href;
    }
    $goodPicsURLs = [];
    
    foreach ($picsURLs as $row) {
        if (strlen($row) > 10) {
            $goodPicsURLs[] = $row;
        }
    }
    for ($i = 0; $i < count($goodPicsURLs); $i++) {
        if ($i > 0) {
            $goodPicsURLs[$i] = "http://auto.motors24.ee/" . $goodPicsURLs[$i];
        }
    }    
    
    return $goodPicsURLs;
}