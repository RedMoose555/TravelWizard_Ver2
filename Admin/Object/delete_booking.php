<?php 
require_once 'db1.php'; // Include the database connection

// Check if the request is made using POST method
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get the booking ID from the submitted form
    $bookingID = $_POST['bookingID'] ?? null;

    // If booking ID is provided
    if ($bookingID) {
        // Prepare a SQL DELETE query to remove the booking by ID
        $stmt = $conn->prepare("DELETE FROM bookings WHERE bookingID = ?");

        // Execute the query with the booking ID as parameter
        if ($stmt->execute([$bookingID])) {
            // If successful, redirect back to the bookings page with a success status
            header("Location: manage_bookings.php?status=deleted");
        } else {
            // If execution fails, redirect with an error status
            header("Location: manage_bookings.php?status=error");
        }
    } else {
        // If booking ID is not set, redirect with a missing ID status
        header("Location: manage_bookings.php?status=missing_id");
    }

    // Stop further script execution after redirect
    exit;
}
?>





