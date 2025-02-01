<?php
// Load the XML file using a relative path
$xmlFile = 'auction.xml';
$xml = simplexml_load_file($xmlFile);

if ($xml === false) {
    // Handle the error if the XML file cannot be loaded
    echo 'Failed to load XML file.';
    exit;
}

// Get the item number to delete from the query string
$itemNumberToDelete = $_GET['itemNumber'];

// Find and remove the item with the specified item number
$items = $xml->xpath("/auction/item[item_number='{$itemNumberToDelete}']");
if (count($items) === 1) {
    $dom = dom_import_simplexml($items[0])->parentNode;
    $dom->removeChild($dom->firstChild);

    // Save the updated XML
    $xml->asXML($xmlFile);
    echo 'Item with item number ' . $itemNumberToDelete . ' has been deleted.';
} else {
    echo 'Item not found.';
}
?>
