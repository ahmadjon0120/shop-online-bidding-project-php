<?php
session_start();
// Load the XML file
$xmlFile = '/home/students/accounts/s104096281/cos80021/www/data/auction.xml';
$xml = simplexml_load_file($xmlFile);

// Get the POST data
$data = json_decode(file_get_contents('php://input'), true);

$itemNumber = $data['itemNumber'];
$newBidPrice = floatval($data['newBidPrice']);
$customerID = $_SESSION['customerID'];

// $itemNumber = "652aca281445f";
// $newBidPrice = floatval(16);
// $customerID = $_SESSION['customerID'];


// Find the item in the XML
$item = $xml->xpath("/auction/item[item_number='{$itemNumber}']");

if (count($item) === 1) {
    $item = $item[0];
    $currentBidPrice = floatval($item->currentBidPrice);
    $status = (string)$item->status;

    if ($newBidPrice > $currentBidPrice && $status !== 'sold') {
        // Update the XML data
        $item->currentBidPrice = $newBidPrice;
        $item->customerID = $customerID;

        // Save the updated XML
        $xml->asXML($xmlFile);


        // Send an acknowledgment to the client
        echo 'Thank you! Your bid is recorded in ShopOnline.';
    } else {
        // Send a message that the bid is not valid
        echo 'Sorry, your bid is not valid.';
    }
} else {
    // Item not found, send an error response
    echo 'Error: Item not found.';
}


// Before the response is sent


?>
