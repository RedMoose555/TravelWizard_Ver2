<?php  
// Booking class to manage bookings
require_once 'classes/Booking.php'; 
// Database connection
require_once 'db1.php';             

// ===== Update booking status when form is submitted =====
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_booking'])) {
    $bookingID = $_POST['bookingID'];    
    $status = $_POST['status'];          

    $booking = new Booking($conn);       

    // Update status in the database and show a popup alert based on success
    if ($booking->updateBookingStatus($bookingID, $status)) {
        echo "<script>alert('Booking status updated successfully!'); window.location.href='manage_bookings.php';</script>";
    } else {
        echo "<script>alert('Error updating booking status.'); window.location.href='manage_bookings.php';</script>";
    }
}

// ===== Handle search filter by Booking ID  =====
$searchBookingID = $_GET['search'] ?? ''; // Get the search value or default to empty

if (!empty($searchBookingID)) {
    // Search by specific booking ID
    $query = "SELECT * FROM bookings WHERE bookingID = ?";
    $stmt = $conn->prepare($query);
    $stmt->execute([$searchBookingID]);
} else {
    // Otherwise, retrieve all bookings
    $query = "SELECT * FROM bookings";
    $stmt = $conn->prepare($query);
    $stmt->execute();
}

$result = $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch all rows

// ===== If an edit is requested, retrieve the booking to populate the form =====
$editBooking = null;
if (isset($_GET['edit'])) {
    $editID = intval($_GET['edit']);
    $editStmt = $conn->prepare("SELECT * FROM bookings WHERE bookingID = ?");
    $editStmt->execute([$editID]);
    $editBooking = $editStmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Bookings</title>
    <link rel="stylesheet" href="css/Admin.css"> <!-- External stylesheet -->
</head>
<body>

<div class="container mt-5">
    <h2 class="mb-4">Manage Bookings</h2>
    <a href="dashboard.php" style="text-decoration: none; color: #007BFF; font-size: 16px;">&larr; Return to Dashboard</a>
    <br><br>

    <!-- ===== Search bar for Booking ID ===== -->
    <form action="manage_bookings.php" method="GET" class="mb-4">
        <div class="input-group mb-3" style="max-width: 400px;">
            <input type="text" class="form-control" name="search" placeholder="Search by Booking ID" value="<?php echo htmlspecialchars($searchBookingID); ?>">
            <button class="btn btn-primary" type="submit">Search</button>
            <a href="manage_bookings.php" class="btn btn-secondary">Clear</a>
        </div>
    </form>

    <!-- ===== If an edit is requested, show the edit form ===== -->
    <?php if ($editBooking) { ?>
        <h4>Edit Booking Status</h4>
        <form action="manage_bookings.php" method="POST" class="mb-4">
            <input type="hidden" name="bookingID" value="<?php echo htmlspecialchars($editBooking['bookingID']); ?>">

            <!-- Dropdown to select new status -->
            <select name="status" required>
                <option value="pending" <?php if ($editBooking['status'] == 'pending') echo 'selected'; ?>>Pending</option>
                <option value="completed" <?php if ($editBooking['status'] == 'completed') echo 'selected'; ?>>Completed</option>
            </select><br><br>

            <button type="submit" name="update_booking" class="btn btn-success">Update Status</button>
        </form>
    <?php } ?>

    <!-- ===== Display bookings in a table ===== -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Booking ID</th>
                <th>Customer Name</th>
                <th>Package ID</th>
                <th>Date Booked</th>
                <th>Status</th>
                <th>Departure Flight</th>
                <th>Return Flight</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($result as $row) { ?>
                <tr id="row-<?php echo $row['bookingID']; ?>">
                    <td><?php echo htmlspecialchars($row['bookingID']); ?></td>
                    <td>
                        <?php
                        // Lookup customer username from user_id
                        if (isset($row['user_id'])) {
                            $userID = intval($row['user_id']);
                            $userStmt = $conn->prepare("SELECT username FROM users WHERE userID = ?");
                            $userStmt->execute([$userID]);
                            $userRow = $userStmt->fetch(PDO::FETCH_ASSOC);
                            echo $userRow ? htmlspecialchars($userRow['username']) : 'Unknown User';
                        } else {
                            echo 'Unknown User';
                        }
                        ?>
                    </td>
                    <td><?php echo htmlspecialchars($row['package_id'] ?? ''); ?></td>
                    <td><?php echo htmlspecialchars($row['dateBooked'] ?? ''); ?></td>
                    <td><?php echo htmlspecialchars($row['status'] ?? ''); ?></td>
                    <td><?php echo htmlspecialchars($row['departureFlight'] ?? ''); ?></td>
                    <td><?php echo htmlspecialchars($row['returnFlight'] ?? ''); ?></td>
                    <td>
                        <!-- Edit button to trigger form population -->
                        <a href="manage_bookings.php?edit=<?php echo $row['bookingID']; ?>" class="btn btn-primary btn-sm">Edit</a>

                        <!-- Delete button  -->
                        <form method="POST" action="delete_booking.php" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this booking?');">
                            <input type="hidden" name="bookingID" value="<?php echo $row['bookingID']; ?>">
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

</body>
</html>
