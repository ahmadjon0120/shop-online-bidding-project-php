<?php
session_start();

// Load the XML file
$xmlFile = __DIR__ . '/data/auction.xml';
$xml = simplexml_load_file($xmlFile);

// Get the POST data
$data = json_decode(file_get_contents('php://input'), true);

$itemNumber = $data['itemNumber'];
$customerID = $_SESSION['customerID'];

// Find the item in the XML
$item = $xml->xpath("/auction/item[item_number='{$itemNumber}']");

if (count($item) === 1) {
    $item = $item[0];
    $buyItNowPrice = floatval($item->buyItNowPrice);

    // Update the XML data
    $item->currentBidPrice = $buyItNowPrice;
    $item->customerID = $customerID;
    $item->status = 'sold';

    // Save the updated XML
    $xml->asXML($xmlFile);

    // Send an acknowledgment to the client
    echo 'Thank you for purchasing this item.';
} else {
    // Item not found, send an error response
    echo 'Error: Item not found.';
}
?>
