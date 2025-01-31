<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Define an array to store error messages
    $errors = array();

    // Validate and sanitize the input data
    $first_name = filter_var($_POST["first_name"], FILTER_SANITIZE_STRING);
    $surname = filter_var($_POST["surname"], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
    $password = $_POST["password"];
    $retype_password = $_POST["retype_password"];

    // Validate email address format using a simplified regex
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email address format.";
    }

    // Check if the passwords match
    if ($password !== $retype_password) {
        $errors[] = "Passwords do not match.";
    }

    // Check if the email address is unique (you would typically check your database)

    // If there are no errors, proceed with registration
    if (empty($errors)) {
        // Generate a customer ID (you can use a suitable algorithm)
        $customerID = uniqid();

        // Check if the XML file exists
        if (!file_exists("/home/students/accounts/s104096281/cos80021/www/data/customers.xml")) {
            // If the file doesn't exist, create it with the initial structure
            $xml = new SimpleXMLElement("<customers></customers>");
        } else {
            // If the file exists, load it
            $xml = simplexml_load_file("/home/students/accounts/s104096281/cos80021/www/data/customers.xml");
        }

         // Check if the email is already used
         foreach ($xml->customer as $customer) {
            if ($customer->email == $email) {
                echo "Email address already in use. Please choose another.";
                exit; // Exit the script
            }
        }

        // Create a new customer node
        $customer = $xml->addChild("customer");
        $customer->addChild("customerID", $customerID);
        $customer->addChild("first_name", $first_name);
        $customer->addChild("surname", $surname);
        $customer->addChild("email", $email);
        $customer->addChild("password", $password); 

        // Save the XML file
        $xml->asXML("/home/students/accounts/s104096281/cos80021/www/data/customers.xml");


        // Send a welcome email
        $to = $email;
        $subject = "Welcome to ShopOnline!";
        $message = "Dear $first_name, welcome to use ShopOnline! Your customer id is $customerID and the password is $password.";
        $headers = "From: registration@shoponline.com.au";

        // Send the email
        if (mail($to, $subject, $message, $headers)) {
            echo "Registration successful! A welcome email has been sent.";
        } else {
            echo "Registration successful, but there was an issue sending the welcome email.";
        }

    } else {
        // Display error messages
        foreach ($errors as $error) {
            echo $error . "<br>";
        }
    }
} else {
    echo "Invalid request!";
}
?>
