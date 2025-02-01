<?php
// Load the XML file using a relative path
$xmlFile = 'auction.xml';
$xml = simplexml_load_file($xmlFile);

if ($xml === false) {
    // Handle the error if the XML file cannot be loaded
    echo 'Failed to load XML file.';
    exit;
}

$items = $xml->xpath("//item[status='in_progress']");

foreach ($items as $item) {
    date_default_timezone_set("Australia/Melbourne");
    $currentTimestamp = strtotime("now");
    $startTimestamp = strtotime($item->startDate . ' ' . $item->startTime);
    $duration = intval($item->duration);

    $timeLeft = $startTimestamp + $duration - $currentTimestamp;

    if ($timeLeft <= 0) {
        $currentBidPrice = floatval($item->currentBidPrice);
        $reservePrice = floatval($item->reservePrice);

        if ($currentBidPrice >= $reservePrice) {
            $item->status = 'sold';
        } else {
            $item->status = 'failed';
        }
    }
}

// Save the updated XML
$xml->asXML($xmlFile);

echo 'Auction items processed successfully.';
?>
