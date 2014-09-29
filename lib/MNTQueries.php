<?php

function getPublicQuery($regNumber, $vinCode)
{
    $infoURL = 'http://195.80.106.137:9050/saap';
    $infoData = array('regnr' => $regNumber, 'vin' => $vinCode, 'regtun' => '',
        'paring' => "Päring");

    // use key 'http' even if you send the request to https://...
    $infoOptions = array(
        'http' => array(
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($infoData),
        ),
    );
    $infoContext  = stream_context_create($infoOptions);
    $infoResult = file_get_contents($infoURL, false, $infoContext);

    $info = str_get_html($infoResult);
    return $info->find('fieldset');
}

function getLimitations($regNumber)
{
    $piirangURL = 'http://195.80.106.137:9050/soidukiPiirang';
    $piirangData = array('button' => "PÄRING", 'regmark' => $regNumber);

        // use key 'http' even if you send the request to https://...
        $piirangOptions = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($piirangData),

            ),
        );
        $piirangContext  = stream_context_create($piirangOptions);
        $piirangResult = file_get_contents($piirangURL, false, $piirangContext);

        $piirang = str_get_html($piirangResult);                            
        return $piirang->find('.vehicle', 0)->find('td');
}
