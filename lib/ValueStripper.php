<?php

function stripValue($htmlLink)
{
    if (count($htmlLink) >0) {
        $htmlArr = explode(":", $htmlLink[0]);
        return strip_tags($htmlArr[1]);
    } else {
        return "";
    }
}
