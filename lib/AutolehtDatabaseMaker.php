<?php

include 'simple_html_dom.php';
include 'CSVLib.php';

function makeAutolehtCSV($startingArray)
{
    
    $list = array(
            array('mark', 'mudel', 'algus', 'lopp','link')
        );
    
    
    foreach ($startingArray as $startingPoint) {
        $baseURL = "http://www.autoleht.ee/testid/" . $startingPoint;
        
        $html = file_get_html($baseURL);        
        $tableRows = $html->find('tr'); 
        
        foreach ($tableRows as $row) {          
           
           $make = "0";
           $model = $row->find('.first', 0)->plaintext;
           $href = $row->find('a', 0)->href;
           $year = $row->find('.year', 0)->plaintext;
           $start = trim(explode("-", $year)[0]);
           $end = trim(explode("-", $year)[1]);
           
           if (isset($model) && isset($href) && isset($year)) {
               $modelArray = array($make, $model, $start, $end, $href);
                    array_push($list, $modelArray);
           }
           
        }
    }
    
    var_dump($list);
    
    $fp = fopen(realpath('../') . "/db/autoleht_db.csv", 'w');
        
    foreach ($list as $fields) {
    fputcsv($fp, $fields, ';');
    }

    fclose($fp);
    
    echo "Töö on tehtud!";
        
}

$startingArray = [0,54,98,135,165,201,247,290,327,371,406,448,497,531,573,620,
    670,730,767,804,846,897,948];

makeAutolehtCSV($startingArray);

