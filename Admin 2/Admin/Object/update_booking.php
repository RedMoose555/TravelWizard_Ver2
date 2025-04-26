<?php 
require_once 'classes/Booking.php'; // Include the Booking class

// Check if form is submitted via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get booking ID and new date from the form
    $bookingID = $_POST['bookingID'];
    $newDate = $_POST['newDate'];

    // Create a Booking object 
    $booking = new Booking($conn, $bookingID);

    // Prepare the SQL query to update the booking date
    $stmt = $conn->prepare("UPDATE bookings SET dateBooked = ? WHERE bookingID = ?");
    

    // Execute the query and check if it was successful
    if ($stmt->execute()) {
        echo "Booking updated successfully!";
    } else {
        // Show error if update fails
        echo "Error: " . $conn->error;
    }
}
?>

<!-- === HTML Form to Update Booking Date === -->
<form action="update_booking.php" method="POST">
    <!-- Input for booking ID -->
    <input type="number" name="bookingID" placeholder="Booking ID" required><br>

    <!-- Input for new booking date -->
    <input type="date" name="newDate" required><br>

    <!-- Submit button -->
    <button type="submit">Update</button>
</form>

