<?php
// Load the XML file
$xmlFile = __DIR__ . '/data/auction.xml';
$xml = simplexml_load_file($xmlFile);

// Get the item number to delete from the query string
$itemNumberToDelete = $_GET['itemNumber'];

// Find and remove the item with the specified item number
$items = $xml->xpath("/auction/item[item_number='{$itemNumberToDelete}']");
if (count($items) === 1) {
    unset($items[0][0]);
    // Save the updated XML
    $xml->asXML($xmlFile);
    echo 'Item with item number ' . $itemNumberToDelete . ' has been deleted.';
} else {
    echo 'Item not found.';
}
?>
