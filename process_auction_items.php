<?php
// Load the XML file
$xmlFile = '/home/students/accounts/s104096281/cos80021/www/data/auction.xml';
$xml = simplexml_load_file($xmlFile);

$items = $xml->xpath("//item[status='in_progress']");

foreach ($items as $item) {

    date_default_timezone_set("Asia/Karachi");
    $currentTimestamp = strtotime("now");
    $startTimestamp = strtotime($item->startDate . ' ' . $item->startTime);
    $duration = intval($item->duration);

    $timeLeft = $startTimestamp + $duration - $currentTimestamp;

    // echo $timeLeft;

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
