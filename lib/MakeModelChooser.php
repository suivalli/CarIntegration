<?php

function getMakes()
{
    
    $pathToMake = realpath('./') . '/db/aw-Margid.csv';
    
    return CSVToArray($pathToMake);
    
}