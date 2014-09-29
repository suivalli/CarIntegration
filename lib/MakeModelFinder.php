<?php

function getModelData($data)
{
    $modelData = explode(" ", $data);
    $makeName = $modelData [0];
    $longMake = FALSE;
    if (strcmp($modelData[0], "Alfa") == 0 
            || strcmp($modelData[0], "Land") == 0
            || strcmp($modelData[0], "Aston") == 0) {
        $makeName = $modelData[0] . " " . $modelData[1];
        $longMake = TRUE;
    }
    $modelName = $modelData[1];
    if ($longMake) {
        $modelName = $modelData[2];
    }
    
    if (strcmp($makeName, "Alfa Romeo") == 0 && strcmp($modelName, "Alfa") == 0) {
        $modelName = "Alfa 6";
    }
    
    if (strcmp($makeName, "Audi") == 0 && (strcmp($modelName, "A4") == 0 ||
            (strcmp($modelName, "A6") == 0))) {
        if (strcmp($modelData[2], "Allroad") == 0) {
            $modelName = $modelName . " Allroad";
        }
    }
    
    if (strcmp($makeName, "Buick") == 0 && strcmp($modelName, "Park") == 0) {
        $modelName = "Park Avenue";
    }
    
    if (strcmp($makeName, "Chevrolet") == 0 && strcmp($modelName, "Trans") == 0) {
        $modelName = "Trans Sport";
    }
    
    if (strcmp($makeName, "Chrysler") == 0 ) {
        if (strcmp($modelName, "Grand") == 0) {
            $modelName = "Grand Voyager";
        } elseif (strcmp($modelName, "Le") == 0) {
            $modelName = "Le Baron";
        } elseif (strcmp($modelName, "New") == 0) {
            $modelName = "New Yorker";
        } elseif (strcmp($modelName, "PT") == 0) {
            $modelName = "PT Cruiser";
        }
    }
    
    if (strcmp($makeName, "Citroen") == 0 && (strcmp($modelName, "C3") == 0 ||
            (strcmp($modelName, "C4") == 0) ||
            (strcmp($modelName, "Xsara") == 0) )) {
        if (strcmp($modelData[2], "Picasso")) {
            $modelName = $modelName . " Picasso";
        }
    }
    
    if (strcmp($makeName, "Citroen") == 0 && (strcmp($modelName, "C4") == 0)) {
        if (strcmp($modelData[2], "Aircross") == 0) {
            $modelName = $modelName . "Aircross";
        }
    }
    
    if (strcmp($makeName, "Dacia") == 0 && (strcmp($modelName, "Logan") == 0)) {
        if (strcmp($modelData[2], "MCV") == 0) {
            $modelName = $modelName . "MCV";
        }
    }
    
    if (strcmp($makeName, "Daihatsu") == 0 && strcmp($modelName, "Gran") == 0) {
        $modelName = "Gran Move";
    }
    
    if (strcmp($makeName, "Daihatsu") == 0 && strcmp($modelName, "Young") == 0) {
        $modelName = "Young RV";
    }
    
    if (strcmp($makeName, "Daimler") == 0 && strcmp($modelName, "Double") == 0) {
        $modelName = "Double Six";
    }
    
    if (strcmp($makeName, "Daimler") == 0 && strcmp($modelName, "Super") == 0) {
        $modelName = "Super Eight";
    }
    
    if (strcmp($makeName, "Datsun") == 0 && strcmp($modelName, "280") == 0) {
        $modelName = "280 ZX";
    }
    
    if (strcmp($makeName, "Ferrari") == 0 && strcmp($modelName, "F512") == 0) {
        $modelName = "F512 M";
    }
    
    if (strcmp($makeName, "Ford") == 0 && (strcmp($modelName, "Focus") == 0)) {
        if (strcmp($modelData[2], "C-Max") == 0) {
            $modelName = $modelName ." " . "C-MAX";
        }
    }
    
    if (strcmp($makeName, "Jeep") == 0 && strcmp($modelName, "Grand") == 0) {
        $modelName = "Grand Cherokee";
    }
    
    if (strcmp($makeName, "Lancia") == 0 && strcmp($modelName, "Monte") == 0) {
        $modelName = "Monte Carlo";
    }
    
    if (strcmp($makeName, "Land Rover") == 0 && strcmp($modelName, "Range") == 0) {
        $modelName = "Range Rover";
        if (strcmp($modelData[4], "Sport") == 0) {
            $modelName = $modelName . " Sport";
        } elseif (strcmp($modelData[4], "Evoque") == 0) {
            $modelName = $modelName . " Evoque";
        }
    }
    
    if (strcmp($makeName, "Lexus") == 0 && strcmp($modelName, "LFA") != 0) {
        $modelName = $modelName . " " . $modelData[2];
    }
    
    if (strcmp($makeName, "Lincoln") == 0 && strcmp($modelName, "Mark") == 0) {
        $modelName = "Mark VIII";
    }
    
    if (strcmp($makeName, "Lincoln") == 0 && strcmp($modelName, "Town") == 0) {
        $modelName = "Town Car";
    }
    
    if (strcmp($makeName, "Maserati") == 0 && strcmp($modelName, "3200") == 0) {
        $modelName = "3200 GT";
    }
    
    if (strcmp($makeName, "Mazda") == 0 && strcmp($modelName, "Xedos") == 0) {
        $modelName = $modelName . " " . $modelData[2];
    }
    
    if (strcmp($makeName, "Mercedes-Benz") == 0) {
        if (strcmp($modelName, "A") == 0 || strcmp($modelName, "B") == 0 ) {
            $modelName =  $modelName . " " . $modelData[2];
        } elseif (strcmp($modelName, "C") == 0 || strcmp($modelName, "CE") == 0 ||
                strcmp($modelName, "CL") == 0 || strcmp($modelName, "CLA") == 0 ||
                strcmp($modelName, "CLC") == 0 || strcmp($modelName, "CLK") == 0 ||
                strcmp($modelName, "CLS") == 0 || strcmp($modelName, "E") == 0 ||
                strcmp($modelName, "G") == 0 || strcmp($modelName, "GL") == 0 ||
                strcmp($modelName, "GLK") == 0 || strcmp($modelName, "ML") == 0 ||
                strcmp($modelName, "R") == 0 || strcmp($modelName, "S") == 0 ||
                strcmp($modelName, "SL") == 0 || strcmp($modelName, "SLC") == 0 ||
                strcmp($modelName, "SLK") == 0 || strcmp($modelName, "V") == 0) {
            if (strlen($modelData[2]) == 2) {
                $modelName =  $modelName . " " . $modelData[2] . " AMG";
            } else {
                 $modelName =  $modelName . " " . $modelData[2];
            }
        }
        
    }
    
    if (strcmp($makeName, "Mercury") == 0 && strcmp($modelName, "Grand") == 0) {
        $modelName = "Grand Marquis";
    }
    
    if (strcmp($makeName, "Mitsubishi") == 0) {
        if (strcmp($modelName, "3000") == 0) {
            $modelName = "3000 GT";
        } elseif (strcmp($modelName, "Lancer") == 0 && strcmp($modelData[2], "Evolution") == 0) {
            $modelName = "Lancer Evolution";
        } elseif (strcmp($modelName, "Pajero") == 0&& strcmp($modelData[2], "Sport") == 0) {
            $modelName = "Pajero Sport";
        } elseif (strcmp($modelName, "Space") == 0) {
            $modelName = $modelName . " " . $modelData[2];
        }
    }
    
    if (strcmp($makeName, "Nissan") == 0 && strcmp($modelName, "100") == 0) {
        $modelName = "100 NX";
    }
    
    if (strcmp($makeName, "Nissan") == 0 && strcmp($modelName, "200SX") == 0) {
        $modelName = "200 SX";
    }
    
    if (strcmp($makeName, "Nissan") == 0 && strcmp($modelName, "300ZX") == 0) {
        $modelName = "300 ZX";
    }
    
    if (strcmp($makeName, "Nissan") == 0 && strcmp($modelName, "Almera") == 0) {
        if (strcmp($modelData[2], "Tino") == 0) {
            $modelName = $modelName . " " . $modelData[2];
        }
    }
    
    if (strcmp($makeName, "Pontiac") == 0 && strcmp($modelName, "Grand") == 0) {
        $modelName = "Grand Prix";
    }
    
    if (strcmp($makeName, "Pontiac") == 0 && strcmp($modelName, "Trans") == 0) {
        $modelName = "Trans Sport";
    }
    
    if (strcmp($makeName, "Porsche") == 0 && strcmp($modelName, "Carrera") == 0) {
        $modelName = "Carrera GT";
    }
    
    if (strcmp($makeName, "Renault") == 0 && strcmp($modelName, "Grand") == 0) {
        $modelName = $modelName . " " . $modelData[2];
    }
    
    if (strcmp($makeName, "Renault") == 0 && strcmp($modelName, "Vel") == 0) {
        $modelName = "Vel Satis";
    }
    
    if (strcmp($makeName, "Rolls-Royce") == 0 && strcmp($modelName, "Silver") == 0) {
        $modelName = $modelName . " " . $modelData[2];
    }
    
    if (strcmp($makeName, "Smart") == 0 ) {
        if (strcmp($modelName, "City") == 0) {
            $modelName = "city-coupe";
        } elseif (strcmp($modelName, "Crossblade") == 0) {
            $modelName = "crossblade";
        } elseif (strcmp($modelName, "Forfour") == 0) {
            $modelName = "forfour";
        } elseif (strcmp($modelName, "Fortwo") == 0) {
            $modelName = "fortwo";
        } elseif (strcmp($modelName, "Roadster") == 0) {
            $modelName = "roadster";
        }
    }
    
    if (strcmp($makeName, "Suzuki") == 0 && strcmp($modelName, "Grand") == 0) {
        $modelName = "Grand Vitara";
    }
    
    if (strcmp($makeName, "Suzuki") == 0 && strcmp($modelName, "Wagon") == 0) {
        $modelName = "Wagon R+";
    }
    
    if (strcmp($makeName, "Tesla") == 0 && strcmp($modelName, "Model") == 0) {
        $modelName = $modelName . " " . $modelData[2];
    }
    
    if (strcmp($makeName, "Toyota") == 0) {
        if (strcmp($modelName, "Avensis") == 0 || 
                strcmp($modelName, "Corolla") == 0 ||
                strcmp($modelName, "Yaris") == 0 ) {
            if (strcmp($modelData[2], "Verso") == 0) {
                $modelName = $modelName . " Verso";
            }
        }
        if (strcmp($modelName, "GT") == 0) {
            $modelName = $modelName . $modelData[2];
        }
        if (strcmp($modelName, "Land") == 0) {
            $modelName = "Land Cruiser";
            if (strcmp($modelData[3], "90") == 0) {
                $modelName = $modelName . " " . $modelData[3];
            }
            if (strcmp($modelData[3], "100") == 0) {
                $modelName = $modelName . " " . $modelData[3];
            }
            if (strcmp($modelData[3], "V8") == 0) {
                $modelName = $modelName . " " . $modelData[3];
            }
        }
        if (strcmp($modelName, "Urban") == 0 || strcmp($modelName, "Space") == 0) {
            $modelName = $modelName . " " . $modelData[2];
        }
    }
    
    
    if (strcmp($makeName, "Volkswagen") == 0) {
        if (strcmp($modelName, "Golf") == 0) {
            if (strcmp($modelData[2], "Plus") == 0) {
                $modelName = $modelName . " " . $modelData[2];
            }
        }
        if (strcmp($modelName, "New") == 0) {            
            $modelName = $modelName . " " . $modelData[2];
        }
    }
    
    $superArray = [$makeName, $modelName];
    return $superArray;
}
