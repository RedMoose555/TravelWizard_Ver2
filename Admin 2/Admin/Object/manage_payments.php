<?php
require_once 'classes/Payment.php';
require_once 'db1.php';

//  Handle form submission for updating a payment 
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_payment'])) {
    $bookingID = $_POST['bookingID'];
    $amountPaid = $_POST['amountPaid'];
    $amountPending = $_POST['amountPending'];
    $payment_status = $_POST['payment_status'];
    $notes = $_POST['notes'] ?? '';

    $payment = new Payment($conn);

    if ($payment->updatePayment($bookingID, $amountPaid, $amountPending, $payment_status, $notes)) {
        echo "<script>alert('✅ Payment updated successfully!'); window.location.href='manage_payments.php';</script>";
    } else {
        echo "<script>alert('❌ Error updating payment.'); window.location.href='manage_payments.php';</script>";
    }
}

// ===== Handle search and filters =====
$searchBookingID = $_GET['search'] ?? '';
$filter = $_GET['filter'] ?? '';
$editPayment = null;

try {
    $baseQuery = "SELECT * FROM payments";
    $params = [];

    if (!empty($searchBookingID)) {
        $baseQuery .= " WHERE bookingID = ?";
        $params[] = $searchBookingID;
    } elseif ($filter === 'full') {
        $baseQuery .= " WHERE amountPending <= 0";
    } elseif ($filter === 'installments') {
        $baseQuery .= " WHERE amountPending > 0";
    }

    $stmt = $conn->prepare($baseQuery);
    $stmt->execute($params);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (isset($_GET['edit'])) {
        $editID = intval($_GET['edit']);
        $editStmt = $conn->prepare("SELECT * FROM payments WHERE bookingID = ?");
        $editStmt->execute([$editID]);
        $editPayment = $editStmt->fetch(PDO::FETCH_ASSOC);
    }
} catch (PDOException $e) {
    die("❌ Error fetching payments: " . $e->getMessage());
}
?>

<!--  HTML Layout Starts Here  -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Payments</title>
    <link rel="stylesheet" href="css/Admin.css">
    <style>
        .badge { padding: 5px 10px; border-radius: 6px; font-weight: bold; }
        .bg-success { background-color: #28a745; color: #fff; }
        .bg-warning { background-color: #ffc107; color: #000; }
    </style>
</head>
<body>

<div class="container mt-5">
    <h2 class="mb-4">Manage Payments</h2>
    <a href="dashboard.php" style="text-decoration: none; color: #007BFF;">&larr; Return to Dashboard</a><br><br>

    <!--  Search and Filter Form  -->
    <form method="GET" class="mb-4">
        <input type="text" name="search" placeholder="Search by Booking ID" value="<?= htmlspecialchars($searchBookingID) ?>">
        <button type="submit">Search</button>
        <a href="manage_payments.php" class="btn">Clear</a>
    </form>

    <div class="mb-4">
        <a href="manage_payments.php" class="btn">Show All</a>
        <a href="manage_payments.php?filter=full" class="btn">Paid in Full</a>
        <a href="manage_payments.php?filter=installments" class="btn">Installments</a>
    </div>

    <!--  Edit Payment Form  -->
    <?php if ($editPayment): ?>
        <h4>Edit Payment</h4>
        <form method="POST" class="mb-4">
            <input type="hidden" name="bookingID" value="<?= htmlspecialchars($editPayment['bookingID']) ?>">

            <input type="number" step="0.01" name="amountPaid" value="<?= htmlspecialchars($editPayment['amountPaid']) ?>" placeholder="Amount Paid" required><br><br>

            <input type="number" step="0.01" name="amountPending" value="<?= htmlspecialchars($editPayment['amountPending']) ?>" placeholder="Amount Pending" required><br><br>

            <select name="payment_status" required>
                <option value="pending" <?= $editPayment['payment_status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                <option value="completed" <?= $editPayment['payment_status'] === 'completed' ? 'selected' : '' ?>>Completed</option>
            </select><br><br>

            <textarea name="notes" placeholder="Enter notes (optional)" rows="3" style="width:100%;"><?= htmlspecialchars($editPayment['notes'] ?? '') ?></textarea><br><br>

            <button type="submit" name="update_payment">Update Payment</button>
        </form>
    <?php endif; ?>

    <!--  Payments Table  -->
    <table border="1" cellpadding="8" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Booking ID</th>
                <th>Amount Paid</th>
                <th>Amount Pending</th>
                <th>Status</th>
                <th>Transaction Date</th>
                <th>Completion</th>
                <th>Notes</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php if (!empty($result)): ?>
            <?php foreach ($result as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row['bookingID']) ?></td>
                    <td><?= htmlspecialchars($row['amountPaid']) ?></td>
                    <td><?= htmlspecialchars($row['amountPending']) ?></td>
                    <td><?= htmlspecialchars($row['payment_status']) ?></td>
                    <td><?= htmlspecialchars($row['transactionDate']) ?></td>
                    <td>
                        <?php if (floatval($row['amountPending']) <= 0): ?>
                            <span class="badge bg-success">Paid in Full</span>
                        <?php else: ?>
                            <span class="badge bg-warning">Installments</span>
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($row['notes'] ?? '') ?></td>
                    <td><a href="manage_payments.php?edit=<?= $row['bookingID'] ?>" class="btn btn-sm">Edit</a></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="8">No payments found.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>
