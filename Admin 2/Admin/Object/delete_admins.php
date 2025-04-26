<?php 
// Include the database connection
require_once 'db1.php';

// Include the Admin class 
require_once 'classes/Admin.php';

// Check if the request method is POST and the userID is provided
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['userID'])) {

    // Create a new instance of the Admin class with the database connection
    $adminObj = new Admin($conn);

    // Get the userID from the submitted form data
    $userID = $_POST['userID'];

    // Attempt to delete the admin with the given userID
    if ($adminObj->deleteAdmin($userID)) {
        // If successful, redirect back to the admin management page with a success message
        header("Location: manage_admins.php?status=Admin+deleted+successfully");
    } else {
        // If deletion failed, redirect with an error message
        header("Location: manage_admins.php?status=Error+deleting+admin");
    }

    // Stop further script execution after redirect
    exit;
}
?>





