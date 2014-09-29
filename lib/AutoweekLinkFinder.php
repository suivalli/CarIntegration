<?php
// FOR TESTING PURPOSES, UNCOMMENT THE NEXT BLOCK!
/*
include 'CSVLib.php';

$details = [
    "make" => "BMW",
    "model" => "320",
    "year" => 1986,
    "transmission" => "manuaal",
];

getModels($details);
*/

function getModels($details)
{
    $friendlyModel = getFriendlyModelName($details["make"], $details["model"]);    
    if (strlen($friendlyModel) > 0) {        
        $pathToModel = realpath('./') . '/db/autoweek/Margid/' . $details["make"] . '/' .  $friendlyModel;  
    } else {
        $pathToModel = realpath('./') . '/db/autoweek/Margid/' . $details["make"] . '/' .  $details["model"];
    }
    $pathToMake = realpath('./') . '/db/autoweek/Margid/' . $details["make"];
    
    $selection = getSuitableModels($pathToModel, $details["year"]);
    
    $ids = [];
    foreach ($selection as $selected) {
        $suitableModels = getSuitableVariants(
            $pathToModel, $selected, $details["model"], $details["year"],
            $details["transmission"], $details["bodytype"], $pathToMake,
            $details["displacement"], $friendlyModel, $details["fuel"]
        );
        if (count($suitableModels) > 0) {
            foreach ($suitableModels as $suits) {
                $ids[] = $suits;
            }        
        }
    }
    
    return makeLinks($ids, $details["year"]);
    
}

function makeLinks($ids, $year)
{
    $links = [];
    foreach ($ids as $id) {
        $link = "http://www.autoweek.nl/carbase_data.php?id=" . $id . "&jaar=" 
                . $year . "&cache=no";
        $links[] = $link;
    }
    return $links;
}

function getSuitableModels($directory, $year)
{
    if ( ! is_dir($directory)) {
        exit('Invalid directory path');
    }

    $files = array();

    foreach (scandir($directory) as $file) {
        if ('.' === $file) continue;
        if ('..' === $file) continue;
        $start = explode('_', $file)[0];
        $end = explode('_', $file)[1];
        if ($year >= $start && $year <= $end) {
            $files[] = $file;            
        }
    }
    
    return $files;
}

function getSuitableVariants($path, $file, $model, $year, $transmission, 
        $bodytype, $makePath, $displacement, $friendlyModel, $fuel)
{
    
    $files = array();
    if ( ! is_dir($makePath)) {
        exit('Invalid directory path');
    }
    
    foreach (scandir($makePath) as $modelFile) {
        if ('.' === $modelFile) continue;
        if ('..' === $modelFile) continue;
        if ( !is_dir($makePath . "/" . $modelFile)) {
            $modelN = explode(".", explode('_', $modelFile)[1])[0];            
            if (strlen($friendlyModel) <= 0) {                
                if (strpos($modelN, $model) !== FALSE) {                   
                    $files[] = $modelFile;                    
                    break;
                }
            } else {
               if (strpos($modelN, $friendlyModel) !== FALSE) {
                    $files[] = $modelFile;                   
                    break;
               } 
            }
        }
        
    }
    
    
    $vars = CSVToArray($makePath . "/" . $files[0]);    
    $suitableIDs = [];
    foreach ($vars as $var) {
        if (strpos($var["mudeli_tyyp"], "sedan") !== FALSE && 
                strpos(strtolower($bodytype), "sedaan") !== FALSE) {
            $suitableIDs[] = $var["id"];
        } elseif (strpos($var["mudeli_tyyp"], "station") !== FALSE && 
                strpos(strtolower($bodytype), "universaal") !== FALSE) {
            $suitableIDs[] = $var["id"];
        } elseif (strpos($var["mudeli_tyyp"], "hatchback") !== FALSE && 
                strpos(strtolower($bodytype), "luukpära") !== FALSE) {
            $suitableIDs[] = $var["id"];
        } elseif (strpos($var["mudeli_tyyp"], "cabrio") !== FALSE && 
                strpos(strtolower($bodytype), "kabriolett") !== FALSE) {
            $suitableIDs[] = $var["id"];
        } elseif (strpos($var["mudeli_tyyp"], "coup") !== FALSE && 
                strpos(strtolower($bodytype), "kupee") !== FALSE) {
            $suitableIDs[] = $var["id"];
        } elseif (strpos($var["mudeli_tyyp"], "mpv") !== FALSE && 
                strpos(strtolower($bodytype), "mahtuniversaal") !== FALSE) {
            $suitableIDs[] = $var["id"];
        } elseif (strpos($var["mudeli_tyyp"], "pick") !== FALSE && 
                strpos(strtolower($bodytype), "pikap") !== FALSE) {
            $suitableIDs[] = $var["id"];
        }        
    }    
    $fileID = explode(".", explode("_", $file)[2])[0];
    $isSuitable = FALSE;
    foreach ($suitableIDs as $compID) {
        if ($compID == $fileID) {
            $isSuitable = TRUE;
            break;
        }
    }
    
    $variants = CSVToArray($path . "/" . $file);
    
    
    $ids = [];    
    if ($isSuitable) {
        foreach ($variants as $variant) {        
            $longModel = $variant["pikk_mudel"];
            $from = $variant["alates"];
            $to = $variant["kuni"];
            $trans = $variant["kaigukast"];
            $fuelType = $variant["kytus"];
            if (strpos($trans, "H") !== FALSE) {
                $trans = "manuaal";
            } elseif (strpos($trans, "A") !== FALSE) {
                $trans = "automaat";
            }
            if (strpos($fuelType, "D") !== FALSE) {
                $fuelType = "diisel";
            } else {
                $fuelType = "bensiin";
            }
            if (strpos($longModel, $model) !== FALSE) {                
                if ($year <= $to && $year >= $from) {                    
                    if (strcmp($fuelType, $fuel) == 0) {
                        if (strcmp($trans, $transmission) == 0) {                            
                            if (strpos($longModel, ".") !== FALSE) {                                
                                if (strpos($longModel, $displacement) !== FALSE) {
                                    $ids[] = $variant["id"];
                                }
                            } else {                               
                               $ids[] = $variant["id"]; 
                            }
                        }
                    }
                }
            }
        }
        return $ids;
    } else {
        return array();
    }
      
}

function getFriendlyModelName($make, $model)
{
    if (strcmp($make, "BMW") == 0) {
        if ($model[0] == "1") {
            return "1-serie";
        } elseif ($model[0] == "2") {
            return "2-serie";
        } elseif ($model[0] == "3") {
            return "3-serie";
        } elseif ($model[0] == "4") {
            return "4-serie";
        } elseif ($model[0] == "5") {
            return "5-serie";
        } elseif ($model[0] == "6") {
            return "6-serie";
        } elseif ($model[0] == "7") {
            return "7-serie";
        } elseif ($model[0] == "8") {
            return "8-serie";
        }
    } elseif (strcmp($make, "Lada") == 0) {
        if ($model[0] == "2") {
            return "2100-serie";
        }
    } elseif (strcmp($make, "Lexus") == 0) {
        if ($model[0] == "I") {
            return "IS";
        } elseif ($model[0] == "C") {
            return "CT";
        } elseif ($model[0] == "G") {
            return "GS";
        } elseif ($model[0] == "L") {
            return "LS";
        } elseif ($model[0] == "R") {
            return "RX";
        } elseif ($model[0] == "S") {
            return "SC";
        }
    } elseif (strcmp($make, "Mercedes-Benz") == 0) {
        if ($model[0] == "1") {
            return "190-serie";
        } elseif ($model[0] == "2") {
            return "200-serie";
        } elseif ($model[0] == "A") {
            return "A-klasse";
        } elseif ($model[0] == "B") {
            return "B-klasse";
        } elseif ($model[0] == "C") {
            if ($model[1] == "L") {
                if ($model[2] == "A") {
                    return "CLA-klasse";
                } elseif ($model[2] == "C") {
                    return "CLC-klasse";
                } elseif ($model[2] == "K") {
                    return "CLK-klasse";
                } elseif ($model[2] == "S") {
                    return "CLS-klasse";
                } else {
                    return "CL-klasse";
                }
            } elseif ($model[1] == "E") {
                return "E-klasse";
            } elseif ($model[1] == " ") {
                return "C-klasse";
            } else {
                return FALSE;
            }
        } elseif ($model[0] == "E") {
            return "E-klasse";
        } elseif ($model[0] == "M") {
            return "R-klasse";
        } elseif ($model[0] == "R") {
            return "R-klasse";
        } elseif ($model[0] = "G") {
            if ($model[1] == "L") {
                if ($model[2] == "A") {
                    return "GLA-klasse";
                } elseif ($model[2] == "K") {
                    return "GLK-klasse";
                } else {
                    return "GL-klasse";
                }
            } else {
                return "G-klasse";
            }
        } elseif ($model[0] == "S") {
            if ($model[1] == "L") {
                if ($model[2] == "K") {
                    return "SLK-klasse";
                } elseif ($model[2] == " ") {
                    return "SL-klasse";
                } else {
                    return FALSE;
                }
            } elseif ($model[1] == 0) {
                return "S-klasse";
            } else {
                return FALSE;
            }
        }
        
    } else {
        return FALSE;
    }
}
