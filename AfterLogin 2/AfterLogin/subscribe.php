<?php
require_once 'db.php';

// Check if the form was submitted using POST method
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Trim whitespace from the submitted email
    $email = trim($_POST['email']);

    // Validate the email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // If invalid, show alert and go back
        echo "<script>alert('Invalid email address.'); window.history.back();</script>";
        exit; // Stop script execution
    }

    // Check if the email is already subscribed
    $check = $pdo->prepare("SELECT * FROM subscribers WHERE email = ?");
    $check->execute([$email]);
    $result = $check->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        // If already subscribed, show message and return
        echo "<script>alert('You are already subscribed!'); window.history.back();</script>";
    } else {
        // Insert new email into subscribers table
        $stmt = $pdo->prepare("INSERT INTO subscribers (email) VALUES (?)");
        $stmt->execute([$email]);

        // If the insertion was successful
        if ($stmt->rowCount() > 0) {
            // Show thank-you message and redirect to homepage
            echo "<script>alert('Thank you for subscribing!'); window.location.href='index.php';</script>";
        } else {
            // If something went wrong, show error
            echo "<script>alert('Error subscribing. Please try again later.'); window.history.back();</script>";
        }
    }
}
?>


