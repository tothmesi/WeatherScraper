<?php
if (!isset($_GET["city"]))
{
    $error = array("error" => "Missing argument: 'city'");
    echo json_encode($error);
    die();
}

$city = $_GET["city"];
$weatherUrl =getForecastUrl($city);

// cross-domain elérés miatt curl, letöltöm a weather-forecast.com-ról az adott város idõjárását html-ben
$stream = curl_init($weatherUrl);
curl_setopt($stream,CURLOPT_RETURNTRANSFER,1);
$htmlFile = curl_exec($stream);
curl_close($stream);

$summary = findSummary($htmlFile);
echo json_encode($summary);

// összerakja a weather-forecast URL-t a város nevével
function getForecastUrl($city) {
    return "www.weather-forecast.com/locations/".urlencode($city)."/forecasts/latest";
}

// regex-szel kikeresi a weather-forecast HTML-jébõl a napi idõjárás összefoglalót
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