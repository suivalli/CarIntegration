<?php

include 'simple_html_dom.php';
include 'csv_lib.php';


function makeModelCSV()
{
    
    $makeArray = CSVToArray(realpath('../') . "/db/aw-Margid.csv");
    
    foreach ($makeArray as $row) {
        $name = $row["Mark"];
        $id = $row["id"];
        
        $baseURL = "http://www.autoweek.nl/async/get_cbm_modelseries.php?"
                . "bouwjaar=0&merk=" . $id . "&w=568";
        $html = file_get_html($baseURL);
        
        $models = $html->find("a[class=cbmlinkblock]");
        
        $list = array(
            array('id', 'Mudel')
        );
        foreach ($models as $model) {
            $link = $model->href;
            $scramble = explode("(", $link);
            $scrambleMore = explode(",", $scramble[1]);
            $modelID = $scrambleMore[0];
            $scrambleName = explode("'", $scrambleMore[1]);            
            $modelName = $scrambleName[1];
            $modelNameRplc1 = str_replace("&eacute;", "e", $modelName);
            $modelNameRplc2 = str_replace("&ograve;", "o", $modelNameRplc1);
            $modelArray = array($modelID, $modelNameRplc2);
            array_push($list, $modelArray);
        }
        
        $fp = fopen(
            realpath('../') . "/db/autoweek/" . $id . "_" . 
            $name . ".csv", 'w'
        );
        
        foreach ($list as $fields) {
        fputcsv($fp, $fields, ';');
        }

        fclose($fp);
        
        
    }
}

function makeModelDataCSV()
{
    $directory = realpath('../') . "/db/autoweek/Margid";

    if ( ! is_dir($directory)) {
        exit('Invalid diretory path');
    }

    $files = array();

    foreach (scandir($directory) as $file) {
        if ('.' === $file) continue;
        if ('..' === $file) continue;

        $files[] = $file;
    }
    
    foreach ($files as $file) {
        
        $makeCombo = explode(".", $file)[0];
        $make = explode("_", $makeCombo)[1];
        $makeID = explode("_", $makeCombo)[0];
        
        //Make a folder for the make
        if (!file_exists($directory . "/" . $make)) {
            mkdir($directory . "/" . $make, 0777, true);
        }
        // Make array from csv file
        $modelArray = CSVToArray($directory . "/" . $file);
        
        //Iterate through names and id's to get info about model
        foreach ($modelArray as $row) {
            $name = $row["Mudel"];
            $id = $row["id"];
            
            
            //Get info about different model data
            $baseURL = "http://www.autoweek.nl/async/get_cbm_modelvarianten.php?"
                . "bouwjaar=0&merk=" . $makeID . 
                    "&modelserie=" . $id . "&w=568";
            $html = file_get_html($baseURL);
            
            $modelData = $html->find("a[class=cbmlinkblock]");
            
            $list = array(
            array('id', 'pikk_mudel', 'mudeli_tyyp', 'alates', 'kuni', 'id_2',
                'mudel', 'id_3')
            );
            
            $i = 1;
            foreach ($modelData as $data) {
                if (($i % 3) == 0) {
                    $link = $data->href;
                    //Taking info out of scrambled data
                    $scramble = explode("(", $link);
                    $scrambledData = explode(",", $scramble[1]);
                    
                    $id1 = $scrambledData[0];
                    $modelNameExtended = explode("'", $scrambledData[1])[1];
                    $modelExtRplc1 = str_replace(
                        "&eacute;", "e", $modelNameExtended
                    );
                    $modelExtRplc2 = str_replace("&ograve;", "o", $modelExtRplc1);
                    $modelType = trim($scrambledData[2]);
                    $modelTypeRplc = str_replace("&eacute;", "e", $modelType);
                    $modelYears = trim($scrambledData[3], " '");
                    $yearStart = explode("-", $modelYears)[0];
                    $yearEnd = explode("-", $modelYears)[1];
                    $id2 = trim($scrambledData[4], " '");
                    $modelName = trim($scrambledData[5], " '");
                    $modelNameRplc1 = str_replace("&eacute;", "e", $modelName);
                    $modelNameRplc2 = str_replace("&ograve;", "o", $modelNameRplc1);
                    $id3 = trim($scrambledData[6], " ')");
                    
                    //echo everything                    
                    $dataArray = array($id1, $modelExtRplc2, $modelTypeRplc,
                        $yearStart, $yearEnd, $id2, $modelNameRplc2, $id3);
                    array_push($list, $dataArray);
                }
                $i++;
            }
            
            $fp = fopen(
                realpath('../') . "/db/autoweek/Margid/" . $make
                . "/" . $id . "_" . $name . ".csv", 'w'
            );
        
            foreach ($list as $fields) {
                fputcsv($fp, $fields, ';');
            }

            fclose($fp);
            
            
        }
    }
}

function makeDataVariantsCSV()
{
    $directory = realpath('../') . "/db/autoweek/Margid";    
    
    //Get the directories in "Margid" directory
    $results = scandir($directory);
    $directories = [];
    
    foreach ($results as $result) {
        if ($result === '.' or $result === '..') continue;

        if (is_dir($directory . '/' . $result)) {
            $directories[] = $result;
        }
    }
    
    //Iterate through the found directories
    
    foreach ($directories as $make) {
        //Get files from directory
        $path = $directory . "/" . $make;

        if ( ! is_dir($path)) {
            exit('Invalid diretory path');
        }

        $files = array();

        foreach (scandir($path) as $file) {
            if ('.' === $file) continue;
            if ('..' === $file) continue;
            
            if ( ! is_dir($file)) {
                $files[] = $file;
            }
        }
        
        //Iterate through found files in make directory
        foreach ($files as $file) {
            $modelCombo = explode(".", $file)[0];           
            $model = explode("_", $modelCombo)[1];            
            $id = explode("_", $modelCombo)[0]; 
        
            //Make a folder for the make
            if (!file_exists($path . "/" . $model)) {
                mkdir($path . "/" . $model, 0777, true);
            }
            // Make array from csv file
            $dataArray = CSVToArray($path . "/" . $file);

            //Iterate through all the lines of a file
            foreach ($dataArray as $line) {
                $id = $line["id"];
                $from = $line["alates"];
                $to = $line["kuni"];

                //Get info about different model data
                $baseURL = "http://www.autoweek.nl/async/get_cbm_uitvoeringen.php?"
                        . "bouwjaar=0&modelvariant=" . $id;
                $html = file_get_html($baseURL);

                $modelData = $html->find("a[class=cbmlinkblock]");

                $list = array(
                array('id', 'pikk_mudel', 'kytus', 'kaigukast', 'alates', 'kuni')
                );

                $i = 1;
                foreach ($modelData as $data) {
                    if (($i % 3) == 0) {
                        $link = $data->href;
                        //Taking info out of scrambled data
                        $scramble = explode("(", $link);
                        $scrambledData = explode(",", $scramble[1]);

                        $id1 = $scrambledData[0];
                        $modelNameExtended = explode("'", $scrambledData[1])[1];
                        $modelExtRplc1 = str_replace("&eacute;", "e", $modelNameExtended);
                        $modelExtRplc2 = str_replace("&ograve;", "o", $modelExtRplc1);
                        $fuelType = trim($scrambledData[2], " '");
                        $transmission = trim($scrambledData[3], " '");
                        $modelYears = trim($scrambledData[4], " ')");
                        $yearStart = explode("-", $modelYears)[0];
                        $yearEnd = explode("-", $modelYears)[1];                    
                                                
                        $dataArray = array($id1, $modelExtRplc2, $fuelType,
                            $transmission, $yearStart, $yearEnd);
                        array_push($list, $dataArray);
                    }
                    $i++;
                }
                $fp = fopen(
                    $path . "/" . $model . "/" . $from . "_" . $to . "_" 
                    . $id . ".csv", 'w'
                );

                foreach ($list as $fields) {
                    fputcsv($fp, $fields, ';');
                }

                fclose($fp);
            }
        }
        
    }
}
