<?php
// Include the database connection
require_once 'db1.php';

// Check if the form was submitted via POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve and sanitize form data
    $userID = isset($_POST['userID']) ? intval($_POST['userID']) : 0;
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');

    // Validate inputs: userID must be positive, and fields must not be empty
    if ($userID <= 0 || empty($username) || empty($email)) {
        // Redirect back with an error if validation fails
        header("Location: manage_customers.php?error=invalid_input");
        exit();
    }

    // Validate email format using PHP's built-in filter
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: manage_customers.php?error=invalid_email");
        exit();
    }

    try {
        // Begin a transaction to update both tables safely
        $conn->beginTransaction();

        // Update the 'users' table with the new username and email
        $conn->prepare("UPDATE users SET username = ?, email = ? WHERE userID = ?")
             ->execute([$username, $email, $userID]);

        // Update the 'customers' table, if applicable
        $conn->prepare("UPDATE customers SET username = ?, email = ? WHERE userID = ?")
             ->execute([$username, $email, $userID]);

        // Commit the transaction since both updates succeeded
        $conn->commit();

        // Redirect back with a success message
        header("Location: manage_customers.php?success=updated");
        exit();
    } catch (Exception $e) {
        // Roll back any changes if an error occurs
        $conn->rollBack();

        // Redirect back with an error message
        header("Location: manage_customers.php?error=update_failed");
        exit();
    }
}
?>




