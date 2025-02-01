<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Load customer data from the XML file using a relative path
    $xmlFile = 'customers.xml';
    $xml = simplexml_load_file($xmlFile);

    if ($xml === false) {
        // Handle the error if the XML file cannot be loaded
        echo 'Failed to load XML file.';
        exit;
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
        $_SESSION['first_name'] = $first_name; // Optionally store the first name in the session

        echo "Login successful!";
    } else {
        echo "Login failed. Invalid email address or password.";
    }
} else {
    echo "Invalid request!";
}
?>
