<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML+RDFa 1.1//EN" "http://www.w3.org/MarkUp/DTD/xhtml-rdfa-2.dtd">

<html version="XHTML+RDFa 1.1" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" >
    <head>
        <link href="css/style.css" rel="stylesheet" type="text/css">
        <script src="http://code.jquery.com/jquery-latest.min.js"
        type="text/javascript"></script>        
        <script type="text/javascript">
        // Using jQuery.

        $(function() {
            $('form').each(function() {
                $(this).find('input').keypress(function(e) {
                    // Enter pressed?
                    if(e.which === 10 || e.which === 13) {
                        this.form.submit();
                    }
                });

                //$(this).find('input[type=submit]').hide();
            });
        });
        
        $(document).ready(function () {
            
        $('#select-make').on('change', function () {
            $('#select-model').empty()
            .append('<option selected="selected" value="-1">Vali mudel</option>');
            
            //get selected value from category drop down
            var category = $(this).val();

            //select subcategory drop down
            var selectSubCat = $('#select-model');

            if ( category !== -1 ) {

                // ask server for sub-categories
                $.getJSON( "lib/GetModel.php?category="+category)
                .done(function( result) {    
                    // append each sub-category to second drop down   
                    $.each(result, function(k, v) {
                        selectSubCat.append($("<option />").val(k).text(v));
                    });
                    // enable sub-category drop down
                    selectSubCat.prop('disabled', false);                
                });

            } else {                
                // disable sub-category drop down
                selectSubCat.prop('disabled', 'disabled');
            }
        });    

        });
        </script>
        <meta charset="UTF-8">
        <title>Auto</title>
    </head>
    <body>
        <?php 
        
        error_reporting(E_ALL);
        include_once 'lib/CSVLib.php';
        include_once 'lib/MakeModelChooser.php';
        ?>
                
        <div id="submitcontent">
        <div id="manualsubmit" class="centered">
            <form name="manual" action="parser.php" method="post">
                <h3>Auto mark</h3>
                <div class="field">
                    <select class="input-select" id="select-make" name="make">
                        <option class="input-option" value="-1">Vali mark..</option>
                        <?php
                        $makes = getMakes();                        
                        foreach ($makes as $row) { ?>
                        <option class="input-option" value="<?php echo $row["id"] . "_" . $row["Mark"]?>"><?php echo $row["Mark"]?></option>
                        <?php } ?> 
                        
                    </select>
                </div>
                <h3>Auto mudel</h3>
                <div class="field">
                    <select class="input-select" id="select-model" name="model">
                        <option value="-1">Vali mudel..</option>
                    </select>
                </div>
                <h3>Keretüüp</h3>
                <div class="field">
                    <select class="input-select" id="select-bodytype" name="bodytype">
                        <option value="-1">Vali auto keretüüp</option>
                        <option value="sedaan">Sedaan</option>
                        <option value="mahtuniversaal">Mahtuniversaal</option>
                        <option value="universaal">Universaal</option>
                        <option value="luukpära">Luukpära</option>
                        <option value="kupee">Kupee</option>
                        <option value="kabriolett">Kabriolett</option>
                        <option value="pikap">Pikap</option>
                    </select>
                </div>
                <h3>Kütus</h3>
                <div class="field">
                    <select class="input-select" id="select-fuel" name="fuel">
                        <option value="-1">Vali kütuse liik..</option>
                        <option value="bensiin">Bensiin</option>
                        <option value="diisel">Diisel</option>
                        <option value="h&uuml;briid">Hübriid</option>
                        <option value="elekter">Elekter</option>
                    </select>
                </div>
                <h3>Käigukast</h3>
                <div class="field">
                    <select class="input-select" id="select-trans" name="transmission">
                        <option value="-1">Vali käigukasti tüüp..</option>
                        <option value="manuaal">Manuaal</option>
                        <option value="automaat">Automaat</option>
                    </select>
                </div>                
                <h3>Aasta</h3>
                <div class="field">
                    <select class="input-select" id="select-year" name="year">
                        <option value="-1">Vali auto valmistusaasta..</option>
                        <?php for ($x=2014; $x>=1970; $x--) { ?>
                        <option value="<?php echo $x?>"><?php echo $x?></option>    
                        <?php } ?>
                    </select>
                </div>
                <h3>Võimsus kilovattides</h3>
                <div class="field">
                    <input type="number" class="manual" name="power" placeholder="Võimsus">kw</input>
                </div>
                <h3>Litraaž</h3>
                <div class="field">
                    <input type="text" class="manual" name="displacement" placeholder="Näiteks 1.4">L</input>
                </div>
                <input type="hidden" value="0" name="automatic" />                
                <input type="submit" value="Edasi" />                    
            </form>
        </div>
        <div id="autosubmit" class="centered">
            <form name="link" action="parser.php" method="post">
            <h2>Sisestage auto kuulutuse link</h2>
            <input name="link_name" class="linksubmission" type="text" placeholder="Kuulutuse link" />
            <h3>VIN kood</h3>
            <input name="vin" class="linksubmission" type ="text" placeholder="VIN kood"/>
            <h3>Registreerimisnumber</h3>
            <input name="regnr" class="linksubmission" type="text" placeholder="Registreerimisnumber" />
            <input type="hidden" value="1" name="automatic">
            <input type="submit" value="Edasi"/>
            </form>
        </div>
        </div>        
    </body>
</html>
