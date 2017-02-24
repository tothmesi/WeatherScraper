<?php
if (!isset($_GET["city"]))
{
    $error = array("error" => "Missing argument: 'city'");
    echo json_encode($error);
    die();
}

$city = $_GET["city"];
$weatherUrl =getForecastUrl($city);

// cross-domain el�r�s miatt curl, let�lt�m a weather-forecast.com-r�l az adott v�ros id�j�r�s�t html-ben
$stream = curl_init($weatherUrl);
curl_setopt($stream,CURLOPT_RETURNTRANSFER,1);
$htmlFile = curl_exec($stream);
curl_close($stream);

$summary = findSummary($htmlFile);
echo json_encode($summary);

// �sszerakja a weather-forecast URL-t a v�ros nev�vel
function getForecastUrl($city) {
    return "www.weather-forecast.com/locations/".urlencode($city)."/forecasts/latest";
}

// regex-szel kikeresi a weather-forecast HTML-j�b�l a napi id�j�r�s �sszefoglal�t
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