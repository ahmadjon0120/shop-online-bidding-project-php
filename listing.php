<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and validate the input data
    $item_name = $_POST["item_name"];
    $category = $_POST["category"];


    if ($category === "Other") {
        // If "Other" is selected, use the new category input
        $newCategory = $_POST["new_category"];
        // Validate and use the $newCategory value
        $category = $newCategory;
        // Add code to validate and save the new category
    } else {
        // Use the selected category from the drop-down
        $category = $_POST["category"];
        // Add code to use the $category value
    }




    $description = $_POST["description"];
    $reserve_price = $_POST["reserve_price"];
    $buy_it_now_price = $_POST["buy_it_now_price"];
    $start_price = $_POST["start_price"];
    $duration = $_POST["duration"];

    // Perform input validation, e.g., ensure start price is less than or equal to reserve price

     // Perform input validation
     if ($start_price > $reserve_price) {
        // Start price should not be more than the reserve price
        echo "Start price cannot be greater than the reserve price.";
        exit; // Exit the script
    }
    if ($reserve_price >= $buy_it_now_price) {
        // Reserve price should be less than the buy-it-now price
        echo "Reserve price must be less than the buy-it-now price.";
        exit; // Exit the script
    }

    // Generate item number, startDate, and startTime
    $item_number = uniqid();  // You can use a more sophisticated method to generate unique item numbers
    date_default_timezone_set("Asia/Karachi");
    $startDate = date("Y-m-d");
    $startTime = date("H:i:s");

    // Create or load auction.xml and add the item information
    $xml = new DOMDocument();
    if (file_exists('/home/students/accounts/s104096281/cos80021/www/data/auction.xml')) {
        $xml->load('/home/students/accounts/s104096281/cos80021/www/data/auction.xml');
    } else {
        $auction = $xml->createElement('auction');
        $xml->appendChild($auction);
    }

    $item = $xml->createElement('item');

    // Separate elements for item_number, startDate, startTime, status
    $item->appendChild($xml->createElement('item_number', $item_number));
    $item->appendChild($xml->createElement('startDate', $startDate));
    $item->appendChild($xml->createElement('startTime', $startTime));
    $item->appendChild($xml->createElement('status', 'in_progress'));
    $item->appendChild($xml->createElement('sellerID', $_SESSION["customerID"]));
    $item->appendChild($xml->createElement('customerID', 'none')); //ID of customer who will bid on this item
    $item->appendChild($xml->createElement('name', $item_name));
    $item->appendChild($xml->createElement('category', $category));
    $item->appendChild($xml->createElement('description', $description));
    $item->appendChild($xml->createElement('reservePrice', $reserve_price));
    $item->appendChild($xml->createElement('buyItNowPrice', $buy_it_now_price));
    $item->appendChild($xml->createElement('startPrice', $start_price));
    $item->appendChild($xml->createElement('currentBidPrice', $start_price)); // Set current bid price (with the start price as initial value).

    $item->appendChild($xml->createElement('duration', $duration));

    $xml->documentElement->appendChild($item);

    // Save the XML document
    $xml->formatOutput = true;
    $xml->save('/home/students/accounts/s104096281/cos80021/www/data/auction.xml');

    // Return the generated item number and start date/time to the client
    echo "Thank you! Your item has been listed in ShopOnline. The item number is $item_number, and the bidding starts now: $startTime on $startDate";
}
?>
