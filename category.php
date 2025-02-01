<?php
// Load the XML file using a relative path
$xml = simplexml_load_file('auction.xml');

if ($xml === false) {
    // Handle the error if the XML file cannot be loaded
    echo json_encode(array('error' => 'Failed to load XML file'));
    exit;
}

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
