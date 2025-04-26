<?php 
// Include the database connection
require_once 'db1.php';

// Include the Admin class
require_once 'classes/Admin.php';

// Create an instance of the Admin class
$adminObj = new Admin($conn);

// Initialize the status message
$statusMessage = "";

// Check if the form has been submitted via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Sanitize and trim form inputs
    $adminname = trim($_POST['adminname']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Validate that none of the fields are empty
    if (!empty($adminname) && !empty($email) && !empty($password)) {

        // Attempt to create the admin and corresponding user
        if ($adminObj->createAdminAndUser($adminname, $email, $password)) {

            // Redirect to the admin management page with a success status
            header("Location: manage_admins.php?status=Admin+created+successfully");
            exit;
        } else {
            // Set error message if creation fails
            $statusMessage = "❌ Failed to create admin.";
        }
    } else {
        // Set message if form validation fails
        $statusMessage = "❌ All fields are required.";
    }
}
?>

<!-- HTML PAGE -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Admin</title>
    <link rel="stylesheet" href="css/Admin.css">
</head>
<body style="background-color: #e0f7fa; padding: 20px;">

<h2>Create New Admin</h2>
<a href="manage_admins.php">&larr; Back to Admin List</a>

<!-- Display status message if set -->
<?php if (!empty($statusMessage)): ?>
    <p><?php echo htmlspecialchars($statusMessage); ?></p>
<?php endif; ?>

<!-- Admin Creation Form -->
<form method="POST" action="">
    <label for="adminname">Admin Name:</label><br>
    <input type="text" name="adminname" id="adminname" required><br><br>

    <label for="email">Admin Email:</label><br>
    <input type="email" name="email" id="email" required><br><br>

    <label for="password">Password:</label><br>
    <input type="password" name="password" id="password" required><br><br>

    <button type="submit" style="padding: 10px 20px; background-color: #0288d1; color: white; border: none;">Create Admin</button>
</form>

</body>
</html>

