<?php
// Load the XML file
$xml = simplexml_load_file('/home/students/accounts/s104096281/cos80021/www/data/auction.xml');

$categories = array();

foreach ($xml->item as $item) {
    $category = (string)$item->category;
    if (!in_array($category, $categories)) {
        $categories[] = $category;
    }
}

// Send the categories as a JSON response
header('Content-Type: application/json');
echo json_encode($categories);
?>
