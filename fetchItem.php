<?php
// Load the XML data from auction.xml using a relative path
$xmlFile = 'auction.xml';
$xml = simplexml_load_file($xmlFile);

if ($xml === false) {
    // Handle the error if the XML file cannot be loaded
    echo 'Failed to load XML file.';
    exit;
}

// Return the XML content as a response
header('Content-Type: text/xml');
echo $xml->asXML();
?>
