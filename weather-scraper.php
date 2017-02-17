<?php
if (!isset($_GET["city"]))
{
    $error = array("error" => "Missing argument: 'city'");
    echo json_encode($error);
    die();
}

$city = str_replace(" ", "", $_GET["city"]);
$weatherUrl =getForecastUrl($city);

$stream = curl_init($weatherUrl);
curl_setopt($stream,CURLOPT_RETURNTRANSFER,1);
$htmlFile = curl_exec($stream);
curl_close($stream);

$summary = findSummary($htmlFile);

//if($summary)
    echo json_encode($summary);
//else 
//    echo $summary;

function getForecastUrl($city) {
    return "www.weather-forecast.com/locations/".urlencode($city)."/forecasts/latest";
}

function findSummary($html)
{
    $matches = array();
    $pattern = '/<span class="phrase">(.+)<\/span>/';
    preg_match($pattern, $html, $matches);

    return ($matches) ? $matches[1] : false;
}

function validateText($text) {
    return (trim($text) != "");
}

?>