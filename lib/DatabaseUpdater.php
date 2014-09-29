<?php

/**
 * This function has to be run manually for security purposes.
 */

include 'AutolehtDatabaseMaker.php';
include 'WhatcarDatabaseMaker.php';
include 'AutoweekDatabaseMaker.php';

function updateDatabases(){
    
    //Update Autoleht database
    $startingArray = [0,54,98,135,165,201,247,290,327,371,406,448,497,531,573,620,
    670,730,767,804,846,897,948];

    makeAutolehtCSV($startingArray);
    
    //Update Whatcar database
    
    MakeWhatcarUsedCSV();
    MakeWhatcarNewCSV();
    
    //Update Autoweek database
    
    makeModelCSV();
    makeModelDataCSV();
    makeDataVariantsCSV();
}


/*
 * Uncomment for the database update to run
 * updateDatabases();
 */