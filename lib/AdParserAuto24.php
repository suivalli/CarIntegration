<?php
function getStatsAuto($html)
{
    
    $modelName = $html->find('.commonSubtitle', 0);
            
    $details = [];
    
    $modelNamePlain =  $modelName->plaintext; 
    $makeModel = getModelData($modelNamePlain);            
    $details["make"] = $makeModel[0];
    $details["model"] = $makeModel[1];
    $details["type"] = trim(stripValue($html->find('.field-liik')));
    $details["year"] = trim(stripValue($html->find('.field-month_and_year')));
    $details["bodytype"] = trim(stripValue($html->find('.field-keretyyp')));
    if (strlen($details["year"]) > 4) {
        $justYear = explode("/", $details["year"]);
        $details["year"] = $justYear[1];                        
    }
    $details["engine_power"] = trim(stripValue($html->find('.field-mootorvoimsus')));
    $powerScrap = explode("(", $details["engine_power"]);
    $details["power"] = filter_var($powerScrap[1], FILTER_SANITIZE_NUMBER_INT);
    $details["displacement"] = explode(" ", $details["engine_power"])[0];
    $details["fuel"] = trim(stripValue($html->find('.field-kytus')));
    $details["mileage"] = trim(stripValue($html->find('.field-labisoit')));
    $details["layout"] = trim(stripValue($html->find('.field-vedavsild')));
    $details["transmission"] = trim(stripValue($html->find('.field-kaigukast_kaikudega')));
    $details["color"] = trim(stripValue($html->find('.field-varvus')));
    $priceWKM = trim(stripValue($html->find('.field-hind')));    
    if (strpos($priceWKM,"sisaldab") !== 0) {
        $details["price"] = explode("sisaldab", $priceWKM);
    } else {
        $details["price"] = explode("KM", $priceWKM);
    }
    $discountPriceWKM = trim(stripValue($html->find('.field-soodushind')));
    if (strpos($discountPriceWKM,"sisaldab") !== 0) {
        $details["discount_price"] = explode("sisaldab", $discountPriceWKM);
    } else {
        $details["discount_price"] = explode("KM", $discountPriceWKM);
    }    
    $details["reg_nr"] = stripValue($html->find('.field-reg_nr'));            
    $details["vin_code"] = stripValue($html->find('.field-tehasetahis'));
    
    if (strlen(trim($details["reg_nr"])) > 0) {
                $regNrURL =  $html->find('.color-link', 0);
                $regNrStrip1 = explode('key="', $regNrURL);
                $regNrStrip2 = explode('">', $regNrStrip1[1]);
                $details["reg_nr"] = getNumber("uv_regnr", $regNrStrip2[0]);
    }
    if (strlen(trim($details["vin_code"])) > 0) {
        if (strlen(trim($details["reg_nr"])) > 0) {
            $vinNrURL = $html->find('.color-link', 1);
        } else {
            $vinNrURL = $html->find('.color-link', 0);
        }
        $vinNrStrip1 = explode('key="', $vinNrURL);
        $vinNrStrip2 = explode('">', $vinNrStrip1[1]);
        $details["vin_code"] = getNumber("uv_vin", $vinNrStrip2[0]);
    }  
    
    if (isset($details["bodytype"]) && isset($details["power"])
             && isset($details["displacement"])  && isset($details["layout"])
             && isset($details["transmission"])  && isset($details["mileage"])
             && isset($details["fuel"]) && isset($details["reg_nr"])
             && isset($details["vin_code"])) {
        $details["essentials"] = TRUE;
    }
    
    return $details;
}

function getPicsAuto($nonHTTP)
{
    if (strpos($nonHTTP[1], "used") !==FALSE) {
                $carID = explode("/", $nonHTTP[1]);
                $htmlPic = "http://www.auto24.ee/kasutatud/auto_pildid.php?id=" . $carID[2];
    } else {
                $carID= explode('?id=', $nonHTTP[1]);
                $htmlPic = "http://www.auto24.ee/kasutatud/auto_pildid.php?id=" . $carID[1];
    }
            
            $imgHTML = file_get_html($htmlPic);            
            
            
            $thumbs = $imgHTML->find(".thumbnails");
            foreach ($thumbs as $value) {
                $thumbsArray = $value->find("img");
            }                       
            
            $otherPics = array();
            
            foreach ($thumbsArray as $value) {
                $rip1 = explode('data-ref="', $value);
                $rip2 = explode('" ', $rip1[1]);
                array_push($otherPics, $rip2[0]);
            }
            
            return $otherPics;
}
