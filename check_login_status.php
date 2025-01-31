<?php
session_start();

if (isset($_SESSION['customerID'])) {
    // The user is logged in
    echo "logged_in";
} else {
    // The user is not logged in
    echo "not_logged_in";
}
?>
