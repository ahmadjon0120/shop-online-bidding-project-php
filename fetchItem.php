<?php
// Load the XML data from auction.xml
$xml = simplexml_load_file('/home/students/accounts/s104096281/cos80021/www/data/auction.xml');

// Return the XML content as a response
header('Content-Type: text/xml');
echo $xml->asXML();
?>
