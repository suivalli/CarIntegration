<?php

function getNumber($key, $value)
{
// QUESTION
$q    = $key;

// URL TO RETRIEVE RESPONSE
$k = $value;

// JSON URL TO BE REQUESTED - API ENDPOINT
$jsonURL = 'http://www.auto24.ee/services/data_json.php?q=' . $q . '&k=' . $k;

// GET THE VALUE FROM RESPONSE
$jsonString = 'q';

// INITIALIZE CURL
$ch = curl_init($jsonURL);

// CONFIG CURL OPTIONS
$options = array(
    CURLOPT_RETURNTRANSFER  => true,
    CURLOPT_HTTPHEADER      => array('Content-type: application/json') ,
    CURLOPT_POSTFIELDS      => $jsonString
);

// SETTING CURL AOPTIONS
curl_setopt_array($ch, $options);

// GET THE RESULTS
$result =  curl_exec($ch); // Getting jSON result string

$decoded = json_decode($result, true);
return $decoded["q"]["response"]["value"];
}
