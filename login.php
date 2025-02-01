<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Load customer data from the XML file using __DIR__
    $xmlFile = __DIR__ . "/data/customers.xml";
    $xml = simplexml_load_file($xmlFile);

    if ($xml === false) {
        die("Error: Cannot load XML file.");
    }

    $found = false;
    $customerID = null;
    $first_name = null;

    foreach ($xml->customer as $customer) {
        if ($customer->email == $email && $customer->password == $password) {

            $found = true;
            $customerID = (string)$customer->customerID;
            $first_name = (string)$customer->first_name;
            break;
        }
    }

    if ($found) {
        // Set session or cookie to remember customer information for future sessions
        $_SESSION['customerID'] = $customerID;

        echo "Login successful!";
    } else {
        echo "Login failed. Invalid email address or password.";
    }
} else {
    echo "Invalid request!";
}
