<?php
session_start();
require_once 'db1.php';
require_once 'classes/Notification.php';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $message = trim($_POST['message']);
    $userID = intval($_POST['user_id']);
    $sentAt = date("Y-m-d H:i:s");

    $notification = new Notification($conn);

    if ($notification->createNotification($name, $userID, $message, $sentAt)) {
        header("Location: manage_notifications.php?success=1");
        exit;
    } else {
        echo "<script>alert('❌ Error creating notification.');</script>";
    }
}
?>

<!-- HTML Form to Create Notification -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Notification</title>
    <link rel="stylesheet" href="css/Admin.css">
</head>
<body>

<h2>Create Notification</h2>

<form method="POST" action="create_notification.php">
    <input type="text" name="name" placeholder="Notification Title" required>
    <textarea name="message" placeholder="Notification Message" required></textarea>
    <input type="number" name="user_id" placeholder="Admin User ID" required>
    <button type="submit">Send Notification</button>
</form>

<a href="manage_notifications.php">← Back to Manage Notifications</a>

</body>
</html>



