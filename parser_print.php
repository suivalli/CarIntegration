<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML+RDFa 1.1//EN" "http://www.w3.org/MarkUp/DTD/xhtml-rdfa-2.dtd">
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html version="XHTML+RDFa 1.1" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" >
    <head>        
        <link href="css/print_style.css" rel="stylesheet" type="text/css" />
        <script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
        <script>
        $( document ).ready(function() {
            console.log( "document loaded" );
        });

        $( window ).load(function() {
            window.print();
        });
        </script>
        <meta charset="UTF-8">
        <title>Auto andmed</title>
    </head>
    <body>
        <div id="bg_body">
        <?php
        error_reporting(E_ALL);
        
        include_once 'lib/MakeModelFinder.php';            
        include_once 'lib/simple_html_dom.php';
        include_once 'lib/ValueStripper.php';
        include_once 'lib/Auto24.php';
        include_once 'lib/Spritmonitor.php';            
        include_once 'lib/AdParserAuto24.php';
        include_once 'lib/AdParserMotors24.php';
        include_once 'lib/MNTQueries.php';
        include_once 'lib/AutoweekLinkFinder.php';
        include_once 'lib/ReviewLinkFinder.php';
        include_once 'lib/CSVLib.php';
        $link = htmlspecialchars($_POST["link"]);

        // Making the DOM parser object out of given link
        $html = file_get_html($link);

        $vinSubm = htmlspecialchars($_POST["vin"]);

        $regNrSubm = htmlspecialchars($_POST["regnr"]);

        $isAuto24 = true;

        //Is it auto24 link or a motors24 link, send it to preferred parser

        if (strpos($link, "auto24") !== FALSE) {
            $details = getStatsAuto($html);
        } elseif (strpos($link, "motors24") !==FALSE) {
            $details = getStatsMotors($html);
            $isAuto24 = false;
        }

        if (strlen($vinSubm) >= 8) {
           $details["vin_code"] = $vinSubm;
        }

        if (strlen($regNrSubm) >= 5) {
            $details["reg_nr"] = $regNrSubm;
        }     
        ?>
        <div class="content">
            <h3><?php echo $details["make"] . " " . $details["model"];?> </h3>                      
                <div id="main_info">
                    <h2>Üldine info</h2>
                    <table class="main_info">
                    <?php if (isset($details["type"])) {?>
                    <tr>
                        <td><b>Liik:</b></td>
                        <td><?php echo $details["type"] ?></td>
                    </tr>
                    <?php } ?>                
                    <?php if (isset($details["year"])) {?>
                    <tr>
                        <td><b>Aasta:</b></td>
                        <td><?php echo $details["year"] ?></td>
                    </tr>
                    <?php } ?>
                    <?php if (isset($details["engine_power"])) {?>
                    <tr>
                        <td><b>Mootor ja võimsus:</b></td>
                        <td><?php echo $details["engine_power"] ?></td>
                    </tr>
                    <?php } ?>
                    <?php if (isset($details["fuel"])) {?>
                    <tr>
                        <td><b>Kütus:</b></td>
                        <td><?php echo $details["fuel"] ?></td>
                    </tr>
                    <?php } ?>
                    <?php if (isset($details["mileage"])) {?>
                    <tr>
                        <td><b>Läbisõit:</b></td>
                        <td><?php echo $details["mileage"] ?></td>
                    </tr>
                    <?php } ?>
                    <?php if (isset($details["layout"])) {?>
                    <tr>
                        <td><b>Vedav sild:</b></td>
                        <td><?php echo $details["layout"] ?></td>
                    </tr>
                    <?php } ?>
                    <?php if (isset($details["transmission"])) {?>
                    <tr>
                        <td><b>Käigukast:</b></td>
                        <td><?php echo $details["transmission"] ?></td>
                    </tr>
                    <?php } ?>
                    <?php if (isset($details["color"])) { ?>
                    <tr>
                        <td><b>Värvus:</b></td>
                        <td><?php echo $details["color"] ?></td>
                    </tr>
                    <?php } ?>
                    <?php
                    if (isset($details["vin_code"]) && strlen($details["vin_code"]) > 0) { ?>
                    <tr>
                        <td><b>VIN kood:</b></td>
                        <td><?php echo $details["vin_code"] ?></td>
                    </tr>
                    <?php
                    }
                    ?>
                    <?php
                    if (isset($details["reg_nr"]) && strlen($details["reg_nr"]) > 0) { ?>
                    <tr>
                        <td><b>Reg. number: </b></td>
                        <td><?php echo $details["reg_nr"] ?></td>
                    </tr>
                    <?php
                    }
                    ?>
                    <?php
                    if (isset($details["price"]) && strlen($details["price"][0]) > 0) { ?>
                    <tr>
                        <td><b>Hind:</b></td>
                        <td><?php 
                            if ($isAuto24) {
                                echo $details["price"][0];
                            } else {
                                echo $details["price"];
                            }
                            ?></td>
                    </tr>
                    <?php
                    }
                    ?>
                    <?php
                    if (isset($details["discount_price"][0]) && strlen($details["discount_price"][0]) > 0) { ?>
                    <tr style="color: #cd0a0a">
                        <td><b>Soodushind:</b></td>
                        <td><?php echo $details["discount_price"][0]?></td>
                    </tr>
                    <?php
                    }
                    ?>                
                    </table>
                </div>
            
                <br/>
                
                <div id="tabs-2">
                    <h2>Maanteeameti infopäring</h2>
                    <?php
                    if (isset($details["vin_code"]) && isset($details["reg_nr"]) && 
                            strlen($details["vin_code"]) > 0 && strlen($details["reg_nr"]) > 0
                            ) {
                        $infoForm = getPublicQuery($details["reg_nr"], $details["vin_code"]);
                        foreach ($infoForm as $fieldset) {
                            print($fieldset);
                        }
                    } else {
                    ?>
                    <p>Kahjuks pole kuulutuse lisaja sisestanud vajatud andmeid päringu tegemiseks. Vabandame!</p>
                    <?php
                    }
                    ?>
                </div>
                
                <br/>
                
                <div id="tabs-3">                    
                    <?php 
                    $reviewLinks = findReviewLinks($details); 
                    if (count($reviewLinks) >0) {?>
                    <h2>Autode ülevaated</h2>
                    <?php }
                    if (count($reviewLinks) >0) {                    
                        foreach ($reviewLinks as $link) {
                            echo '<li><a href="' . $link . '" target="_blank">' . $link . '</a></li>';
                        }
                    }?>
                </div>
                
                <br/>
                
                <div id="tabs-4">
                    <h2>Reaalne kütusekulu</h2>
                    <?php
                    $spritData = getSpritData($details);
                    if ($spritData !== NULL) {
                    ?>
                    <table class="spritdata" style="text-align: center;">
                        <tr>
                            <th>Sõidukeid</th>
                            <th>Kütuse liik</th>
                            <th>Minimaalne kütusekulu</th>
                            <th>Keskmine kütusekulu</th>
                            <th>Maksimaalne kütusekulu</th>
                        </tr>
                        <tr>
                            <td><?php echo $spritData[2]?></td>
                            <td><?php echo $spritData[3]?></td>
                            <td><?php echo $spritData[5]?></td>
                            <td><?php echo $spritData[7]?></td>
                            <td><?php echo $spritData[9]?></td>
                        </tr>
                    </table>
                    
                    <a href="<?php echo $spritData[-1]?>">Vaata järgi!</a>
                    <?php } else { ?>
                    <p>Kahjuks sellise sõiduki andmeid ei leitud.</p>
                    <?php } ?>
                </div>
                
                <br/>
                
                <div id="tabs-6">
                    <h2>Piirangute päring</h2>
                    <?php                    
                    if (isset($details["reg_nr"]) && strlen($details["reg_nr"]) >0) {
                        $piirangForm = getLimitations($details["reg_nr"]);
                        
                        for ($i=0; $i<count($piirangForm); $i++) {
                            if ($i >= 5) {
                                print $piirangForm[$i];
                                print "<br>";
                            }
                        }                            
                    } else {
                    ?>
                    <p>Kahjuks ei ole kuulutuse lisaja sisestanud auto registriandmeid.</p>
                    <?php }?>                
                </div>
                
                <br/>
                
                <div id="tabs-7">
                    <h2>Tehnilised andmed</h2>
                    <?php $autoweekLinks = getModels($details);
                    if (count($autoweekLinks > 0)) {
                    ?>
                    <h3>Selle auto kohta on leitud järgmised lingid:</h3>
                    <ul>
                        <?php foreach ($autoweekLinks as $link) {
                            echo '<li><a href="' . $link . '" target="_blank">' . $link . '</a></li>';
                        }
                        ?>
                    </ul>
                    <?php } else { ?>
                    <h3>Selle auto kohta andmeid ei leitud.</h3>
                    <?php } ?>
                </div>
                <div id="footer">
                <p>Rakenduse tööks kasutatakse portaalidest <a href="http://www.mnt.ee/">mnt.ee</a>, 
                    <a href="http://www.lkf.ee/index.php?lang=et">lkf.ee</a>,
                    <a href="http://auto.motors24.ee/Home.mvc#tv">motors24.ee</a>, 
                    <a href="http://www.autoleht.ee/">autoleht.ee</a>, 
                    <a href="http://www.whatcar.ee/">whatcar.ee</a>, 
                    <a href="http://www.autoweek.nl/">autoweek.nl</a> ja 
                    <a href="http://www.spritmonitor.de/">spritmonitor.de</a> 
                    pärit andmeid.</p>   
                </div>
            </div>            
        </div>        
        </div>
        
    </body>
</html>
