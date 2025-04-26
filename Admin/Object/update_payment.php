<?php
require_once 'db1.php';        
require_once 'classes/Payment.php'; // Include Payment class

error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $bookingID = $_POST['bookingID'] ?? null;
    $amountPaid = $_POST['amountPaid'] ?? null;
    $amountPending = $_POST['amountPending'] ?? null;
    $paymentStatus = $_POST['payment_status'] ?? null;

    if (!$bookingID || !$amountPaid || !$amountPending || !$paymentStatus) {
        echo "❌ All fields are required.";
        exit;
    }

    try {
        $payment = new Payment($conn); // Provide PDO connection

        if ($payment->updatePayment($bookingID, $amountPaid, $amountPending, $paymentStatus, '')) {
            echo "✅ Payment updated successfully!";
        } else {
            echo "❌ Failed to update payment.";
        }
    } catch (Exception $e) {
        echo "❌ Error: " . $e->getMessage();
    }
}
?>

<!-- === Payment Update Form === -->
<form action="update_payment.php" method="POST">
    <input type="number" name="bookingID" placeholder="Booking ID" required><br><br>
    
    <input type="number" step="0.01" name="amountPaid" placeholder="Amount Paid" required><br><br>
    
    <input type="number" step="0.01" name="amountPending" placeholder="Amount Pending" required><br><br>

    <select name="payment_status" required>
        <option value="">-- Select Status --</option>
        <option value="pending">Pending</option>
        <option value="completed">Completed</option>
    </select><br><br>

    <button type="submit">Update Payment</button>
</form>


