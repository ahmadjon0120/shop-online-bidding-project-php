<?php
function convertSecondsToDHMS($seconds) {
    $days = floor($seconds / (60 * 60 * 24));
    $seconds %= (60 * 60 * 24);
    
    $hours = floor($seconds / (60 * 60));
    $seconds %= (60 * 60);
    
    $minutes = floor($seconds / 60);
    $seconds %= 60;

    // Format the result
    $result = "";
    if ($days > 0) $result .= $days . 'D ';
    if ($hours > 0) $result .= $hours . 'H ';
    if ($minutes > 0) $result .= $minutes . 'M ';
    if ($seconds > 0) $result .= $seconds . 'S ';

    return trim($result);
}

// Load the XML file
$xmlFile = '/home/students/accounts/s104096281/cos80021/www/data/auction.xml';
$xml = simplexml_load_file($xmlFile);

$soldItems = $xml->xpath("//item[status='sold']");
$failedItems = $xml->xpath("//item[status='failed']");

$revenue = 0;

echo '<table class="table text-center">';
echo '<thead class="thead-dark"><tr><th>Item Number</th><th>Start Date</th><th>Start Time</th><th>Status</th><th>Seller ID</th><th>Customer ID</th><th>Name</th><th>Category</th><th>Reserve Price</th><th>Buy It Now Price</th><th>Start Price</th><th>Current Bid Price</th><th>Duration</th><th>Revenue</th></tr></thead>';
echo '<tbody>';

$itemsToDelete = array();

foreach ($soldItems as $item) {
    $revenue += floatval($item->currentBidPrice) * 0.03;
    echo '<tr>';
    echo '<td>' . $item->item_number . '</td>';
    echo '<td>' . $item->startDate . '</td>';
    echo '<td>' . $item->startTime . '</td>';
    echo '<td>' . $item->status . '</td>';
    echo '<td>' . $item->sellerID . '</td>';
    echo '<td>' . $item->customerID . '</td>';
    echo '<td>' . $item->name . '</td>';
    echo '<td>' . $item->category . '</td>';
   
    echo '<td>' . $item->reservePrice . '</td>';
    echo '<td>' . $item->buyItNowPrice . '</td>';
    echo '<td>' . $item->startPrice . '</td>';
    echo '<td>' . $item->currentBidPrice . '</td>';
    echo '<td>' . convertSecondsToDHMS($item->duration) . '</td>';
    echo '<td>$' . number_format(floatval($item->currentBidPrice) * 0.03, 2) . '</td>';
    echo '</tr>';

       // Check if the status is not "in_progress"
    if ($item->status != 'in_progress') {
        $itemsToDelete[] = (string)$item->item_number;
    }
}

foreach ($failedItems as $item) {
    $revenue += floatval($item->reservePrice) * 0.01;
    echo '<tr>';
    echo '<td>' . $item->item_number . '</td>';
    echo '<td>' . $item->startDate . '</td>';
    echo '<td>' . $item->startTime . '</td>';
    echo '<td>' . $item->status . '</td>';
    echo '<td>' . $item->sellerID . '</td>';
    echo '<td>' . $item->customerID . '</td>';
    echo '<td>' . $item->name . '</td>';
    echo '<td>' . $item->category . '</td>';
    echo '<td>' . $item->reservePrice . '</td>';
    echo '<td>' . $item->buyItNowPrice . '</td>';
    echo '<td>' . $item->startPrice . '</td>';
    echo '<td>' . $item->currentBidPrice . '</td>';
    echo '<td>' . convertSecondsToDHMS($item->duration) . '</td>';
    echo '<td>$' . number_format(floatval($item->currentBidPrice) * 0.03, 2) . '</td>';
    echo '</tr>';

    if ($item->status != 'in_progress') {
        $itemsToDelete[] = (string)$item->item_number;
    }
}


echo '<tr class="alert alert-success"><td colspan=15>Total number of sold items: ' . count($soldItems) . '</td></tr>';
echo '<tr class="alert alert-warning"><td colspan=15>Total number of failed items: ' . count($failedItems) . '</td></tr>';
echo '<tr class="alert alert-danger"><td colspan=15>Total revenue: $' . number_format($revenue, 2).'</td></tr>';


echo '</tbody>';
echo '</table>';



// Delete items from the XML file
$dom = new DOMDocument();
$dom->load($xmlFile);

$root = $dom->documentElement;

foreach ($itemsToDelete as $itemNumber) {
    $items = $dom->getElementsByTagName('item');
    foreach ($items as $item) {
        if ($item->getElementsByTagName('item_number')->item(0)->textContent == $itemNumber) {
            $root->removeChild($item);
            break;
        }
    }
}


$dom->save($xmlFile);

// Output a message to indicate the items have been deleted
// echo 'Items with status other than "in_progress" have been deleted from the XML file.';
?>


