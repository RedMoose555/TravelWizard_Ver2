<?php
// Include the database connection
require_once 'db1.php';

// Check if the request method is POST and if the userID is set
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['userID'])) {
    // Sanitize the user ID to ensure it's an integer
    $userID = intval($_POST['userID']);

    try {
        // Begin a database transaction to ensure both deletions are completed together
        $conn->beginTransaction();

        // Delete the corresponding entry from the customers table
        $conn->prepare("DELETE FROM customers WHERE userID = ?")->execute([$userID]);

        // Delete the user entry from the users table
        $conn->prepare("DELETE FROM users WHERE userID = ?")->execute([$userID]);

        // Commit the transaction since both deletions were successful
        $conn->commit();

        // Redirect back to the customer management page with a success message
        header("Location: manage_customers.php?success=deleted");
        exit();
    } catch (Exception $e) {
        // If there's any error, roll back the transaction to prevent partial deletion
        $conn->rollBack();

        // Redirect back with an error message
        header("Location: manage_customers.php?error=delete_failed");
        exit();
    }
} else {
    // If the request is invalid or userID is missing, redirect back with an error message
    header("Location: manage_customers.php?error=invalid_request");
    exit();
}


