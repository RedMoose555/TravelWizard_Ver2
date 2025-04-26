<?php
// Include database connection
require_once 'db1.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['userID'])) {
    $userID = intval($_POST['userID']);

    try {
        // Begin transaction 
        $conn->beginTransaction();

        // Optionally delete from roles 
        $conn->prepare("DELETE FROM admins WHERE userID = ?")->execute([$userID]);
        $conn->prepare("DELETE FROM customers WHERE userID = ?")->execute([$userID]);

        // Then delete from users table
        $conn->prepare("DELETE FROM users WHERE userID = ?")->execute([$userID]);

        $conn->commit();

        // Redirect back with success message
        header("Location: manage_users.php?success=deleted");
        exit();
    } catch (Exception $e) {
        $conn->rollBack();
        error_log("Delete error: " . $e->getMessage());
        header("Location: manage_users.php?error=delete_failed");
        exit();
    }
} else {
    // If access is invalid
    header("Location: manage_users.php?error=invalid_request");
    exit();
}

