<?php

include 'simple_html_dom.php';
include 'CSVLib.php';

function MakeWhatcarNewCSV()
{
    $list = array(
        array('mark', 'mudel', 'link')
    );
    
    $baseURL = "http://www.whatcar.ee/lehe-kaart/";
    
    $html = file_get_html($baseURL);
    
    $newCars = $html->find('#tblNewCars', 0);
    
    $models = $newCars->find('.functions_sitemap');
    
    foreach ($models as $row) {
        echo $row->href . " " . $row->title . "<br/>";
        $link = $row->href;
        $model = $row->plaintext;
        $make = "0";
        $modelArray = array($make, $model, $link);
        array_push($list, $modelArray);
    }
    
    $fp = fopen(realpath('../') . "/db/whatcar_new_db.csv", 'w');
        
    foreach ($list as $fields) {
        fputcsv($fp, $fields, ';');
    }

    fclose($fp);
    
    echo "Töö on tehtud!";
      
    
}

function MakeWhatcarUsedCSV()
{
    $list = array(
        array('mark', 'mudel', 'alates', 'kuni', 'link')
    );
    
    $baseURL = "http://www.whatcar.ee/lehe-kaart/";
    
    $html = file_get_html($baseURL);
    
    $newCars = $html->find('#tblNewCars', 1);
    
    $models = $newCars->find('.functions_sitemap');
    
    foreach ($models as $row) {        
        $link = $row->href;
        $modelYears = $row->plaintext;
        $model= trim(explode("(", $modelYears)[0]);
        $yearsFirst = explode("(", $modelYears)[1];        
        $start = trim(explode("-", $yearsFirst)[0]);
        $endFirst = explode("-", $yearsFirst)[1];
        $end = trim(explode(")", $endFirst)[0]);
        
        
        if ($start < 14) {
            $startLong = "20" . $start;
        } else {
            $startLong = "19" . $start;
        }
        
        if (!is_numeric($end)) {
            $endLong = "...";
        } elseif ($end < 14) {
            $endLong = "20" . $end;
        } else {
            $endLong = "19" . $end;
        }
        
        $make = "0";
        $modelArray = array($make, $model, $startLong, $endLong, $link);
        array_push($list, $modelArray);
    }
    
    $fp = fopen(realpath('../') . "/db/whatcar_used_db.csv", 'w');
        
    foreach ($list as $fields) {
        fputcsv($fp, $fields, ';');
    }

    fclose($fp);
    
    echo "Töö on tehtud!";
      
    
}

MakeWhatcarUsedCSV();
