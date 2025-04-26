<?php 
// Include the database connection
require_once 'db1.php';

// Include the Admin class
require_once 'classes/Admin.php';

// Check if the request is a POST request and required fields are set
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['userID'], $_POST['adminname'])) {

    // Create an instance of the Admin class
    $adminObj = new Admin($conn);

    // Get the user ID from the POST data
    $userID = $_POST['userID'];

    // Get the admin name and trim extra spaces
    $adminname = trim($_POST['adminname']);

    // Check if a password was provided 
    $password = isset($_POST['password']) ? trim($_POST['password']) : null;

    // Call the update method 
    if ($adminObj->updateAdmin($userID, $adminname)) {

        // If a password was provided, update it
        if (!empty($password)) {
            $adminObj->setPassword($password);
        }

        // Redirect with a success message
        header("Location: manage_admins.php?status=Admin+updated+successfully");
    } else {
        // Redirect with an error message
        header("Location: manage_admins.php?status=Error+updating+admin");
    }

    // Terminate the script after the redirect
    exit;
}
?>



