<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML+RDFa 1.1//EN" "http://www.w3.org/MarkUp/DTD/xhtml-rdfa-2.dtd">
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html version="XHTML+RDFa 1.1" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" >
    <head>
        <link href="css/jquery.bxslider.css" rel="stylesheet" type="text/css"/>
        <link href="css/style.css" rel="stylesheet" type="text/css" />
        <link href="css/jquery-ui-1.10.3.custom.min.css" rel="stylesheet" type="text/css" />
        <script src="http://code.jquery.com/jquery-latest.min.js"></script>
        <script src="js/jquery.bxslider.min.js"></script>
        <script src="js/jquery-ui-1.10.3.custom.min.js"></script>
        <script src="js/jquery.bcat.bgswitcher.min.js"></script>
        <script>
        $(document).ready(function(){
            $('.bxslider').bxSlider({
                adaptiveHeight: true,
                auto: true
            });
        });
        $(function() {
            $( "#tabs" ).tabs();
        });
        </script>
        <script type="text/javascript">
        // array with image paths        
        
        var srcBgArray = [
          "img/1.jpg",
          "img/2.jpg",
          "img/3.jpg"
        ];
        
        $(document).ready(function() {
          $('#bg_body').bcatBGSwitcher({
            urls: srcBgArray,
            alt: 'Background'
          });
        });
        </script>
        <script>
        $(function() {
        $( document ).tooltip();
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

        //Checks what form is used
        $isAutomatic = htmlspecialchars($_POST["automatic"]);

        if ($isAutomatic == 1) {            
            $link = filter_input(INPUT_POST, 'link_name', FILTER_VALIDATE_URL);

            $vinSubm = htmlspecialchars($_POST["vin"]);

            $regNrSubm = htmlspecialchars($_POST["regnr"]);

            // Making the DOM parser object out of given link
            $html = file_get_html($link);

            // FOR TESTING
            //$html = file_get_html("http://www.auto24.ee/kasutatud/auto.php?id=1680514");

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

            /* Getting bigger pictures */
            if ($isAuto24) {
                $nonHTTP = explode("//", $link);            
                $otherPics = getPicsAuto($nonHTTP);
            } else {
                $otherPics = getPicsMotors($html);
            }
        }

        if ($isAutomatic == 0) {
            $details = [];
            $details["vin_code"] = "";
            $details["reg_nr"] = "";
            $details["make"] = explode("_", htmlspecialchars($_POST["make"]))[1];
            $details["model"] = explode("_", htmlspecialchars($_POST["model"]))[1];
            $details["bodytype"] = htmlspecialchars($_POST["bodytype"]);
            $details["transmission"] = htmlspecialchars($_POST["transmission"]);
            $details["year"] = htmlspecialchars($_POST["year"]);
            $details["power"] = htmlspecialchars($_POST["power"]);
            $details["displacement"] = htmlspecialchars($_POST["displacement"]);
            $details["fuel"] = htmlspecialchars($_POST["fuel"]);
            $details["type"] = "sõiduauto";
            $details["engine_power"] = $details["displacement"] . "l " . $details["power"] . " kw";

        }
                   
        ?>
            
        <div class="content">
            <div id="compare">
                <a href="index.php" target="_blank">Võrdle..</a>
            </div>
            <h3 class="centered"><?php echo $details["make"] . " " . $details["model"];?> </h3>
            <?php if ( $isAutomatic == 1) { ?>
            <div id="print">
                <form action="parser_print.php" method="post">
                    <input type="hidden" name="link" value="<?php echo $link;?>">
                    <input type="hidden" name="vin" value="<?php echo $vinSubm;?>">
                    <input type="hidden" name="regnr" value="<?php echo $regNrSubm;?>">
                    <input type="submit" value="" class="printButton">
                </form>
            </div>
            <?php } ?>
            <?php if ($isAutomatic == 1) { ?>
            <div id="slider">
                <ul class="bxslider">
                    <?php
                    foreach ($otherPics as $image) {
                        echo '<li><img src="' . $image . '"></li>';                      
                    }
                    ?>
                </ul>
            </div>
            <?php } ?>
            <div id="tabs" class="mainTabs">
                <ul>
                    <li><a href="#tabs-1" title="Kuulutusest saadud info vastava auto kohta">
                            <div class="icon yldine">Üldine info</div></a></li>
                    <?php if ( $isAutomatic == 1) { ?>
                    <li><a href="#tabs-2" title="Siin on näha andmed, mis on kantud auto registreerimistunnistusse, info ülevaatuste ja muude maanteeametis tehtud toimingute kohta">
                            <div class="icon maanteeinfo">Maanteeinfo päring</div></a></li>
                    <?php } ?>
                    <li><a href="#tabs-3" title="Antud auto ülevaated lehtedelt whatcar.ee ja autoleht.ee">
                            <div class="icon ylevaated">Auto ülevaated</div></a></li>
                    <li><a href="#tabs-4" title="Portaali spritmonitor.de kasutajate andmete põhjal valminud kokkuvõte auto kütusekulust reaalsete andmetega">
                            <div class="icon kytusekulu">Reaalne kütusekulu</div></a></li><li>
                    <?php if ($isAutomatic == 1) { ?>            
                    <li><a href="#tabs-5" title="Info auto liikluskahjudest Eesti Vabariigis">
                            <div class="icon liikluskahjud">Liikluskahjude info</div></a></li>
                    <li><a href="#tabs-6" title="Maanteeameti info auto kasutus- ja käsutuspiirangute kohta">
                            <div class="icon piirangud">Piirangute info</div></a></li>
                    <?php } ?>
                    <li><a href="#tabs-7" title="Auto tehnilised andmed portaali autoweek.nl baasil">
                            <div class="icon autoweek">Tehnilised andmed</div></a></li>                    
                </ul>
                <div id="tabs-1">
                <table class="main_info">
                <?php if (isset($details["type"])) {?>
                <tr>
                    <td><b>Liik:</b></td>
                    <td><?php echo $details["type"] ?></td>
                </tr>
                <?php } else { ?>
                <tr>
                    <td><b>Liik:</b></td>
                    <td><?php echo $details["type"] ?></td>
                </tr>
                <?php }?>
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
                <?php if ($isAutomatic == 1) { ?>
                <div id="tabs-2">
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
                    <p>Selle päringu nägemiseks on vajalik avelehel sisestada nii auto VIN kood, kui ka registreerimisnumber.</p>
                    <?php
                    }
                    ?>
                </div>
                <?php } ?>                
                <div id="tabs-3">
                    <?php 
                    $reviewLinks = findReviewLinks($details);                    
                    if (count($reviewLinks) >0) {                    
                        foreach ($reviewLinks as $link) {
                                echo '<li><a href="' . $link . '" target="_blank">' . $link . '</a></li>';
                            }
                    }?>
                </div>
                <div id="tabs-4">
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
                    <p>Kahjuks selle sõiduki kohtaandmeid ei leitud.</p>
                    <?php } ?>
                </div>
                <?php if ($isAutomatic == 1) { ?>
                <div id="tabs-5">
                    <?php if (isset($details["vin_code"])) { ?>
                        <p>Tehasetähis, mis tuleb veel manuaalselt sisse panna: <b><?php echo $details["vin_code"];?></b></p>
                    <?php } ?>
                    <iframe id="vin_code_frame" class="spacer10" height="400" 
                            width="100%" style="" 
                            src="https://vs.lkf.ee/pls/xlk/SYSADM.LK_INFOKESKUS_PKT.avarii?plang=EST&potsi=Otsi&ptehnr=<?php echo $details["vin_code"];?>">
                    </iframe>
                </div>
           
                <div id="tabs-6">
                    
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
                    <p>Auto piirangute nägemiseks on vaja auto registreerimisnumbrit.</p>
                    <?php }?>
                
                </div>
                <?php } ?>
                <div id="tabs-7">
                    <?php $autoweekLinks = getModels($details);                    
                    if (count($autoweekLinks) > 0) {
                    ?>
                    <h3> Selle auto kohta on leitud järgmised lingid: </h3>
                    <ul>
                        <?php foreach ($autoweekLinks as $link) {
                            echo '<li><a href="' . $link . '" target="_blank">' . $link . '</a></li>';
                        }
                        ?>
                    </ul>
                    <?php } else { ?>
                    <h3>Kahjuks selle sõiduki kohta andmeid ei leitud.</h3>
                    <?php } ?>
                </div>
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
        
        
    </body>
</html>
