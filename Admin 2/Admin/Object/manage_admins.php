<?php  
// Include the database connection
require_once 'db1.php';

// Include the Admin class which handles admin related operations
require_once 'classes/Admin.php';

// Create an instance of the Admin class
$adminObj = new Admin($conn);

// Initialize variables for search and admin data
$searchQuery = "";
$admins = [];

// If a search term is provided via GET, filter the results
if (isset($_GET['search']) && !empty($_GET['search'])) {
    // Sanitize the search input
    $searchQuery = trim($_GET['search']);

    // Retrieve all admins
    $allAdmins = $adminObj->getAllAdmins();

    // Filter results that match the search term 
    foreach ($allAdmins as $admin) {
        if (stripos($admin['adminName'], $searchQuery) !== false) {
            $admins[] = $admin;
        }
    }
} else {
    // If no search term is provided, retrieve all admins
    $admins = $adminObj->getAllAdmins();
}
?>

<!-- HTML Page Begins -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Admins</title>
    <link rel="stylesheet" href="css/Admin.css">
</head>
<body>

<h2>Manage Admins</h2>

<!-- Link back to dashboard -->
<a href="dashboard.php">&larr; Back to Dashboard</a>
<br><br>

<!-- Link to create new admin -->
<a href="create_admins.php"><button>➕ Create Admin</button></a>

<!-- Search form to filter admins by name -->
<form method="GET" action="">
    <input type="text" name="search" placeholder="Search by name..." value="<?php echo htmlspecialchars($searchQuery); ?>">
    <button type="submit">Search</button>
</form>

<!-- Display success or error status if set -->
<?php if (isset($_GET['status'])): ?>
    <p><?php echo htmlspecialchars($_GET['status']); ?></p>
<?php endif; ?>

<!-- Admins Table -->
<table>
    <thead>
        <tr>
            <th>User ID</th>
            <th>Admin Name</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <!-- If there are admins to display -->
        <?php if (!empty($admins)): ?>
            <?php foreach ($admins as $admin): ?>
                <tr>
                    <td><?php echo htmlspecialchars($admin['userID']); ?></td>
                    <td><?php echo htmlspecialchars($admin['adminName']); ?></td>
                    <td>
                        <!-- Form to update admin -->
                        <form method="POST" action="update_admin.php" style="display:inline-block;">
                            <input type="hidden" name="userID" value="<?php echo $admin['userID']; ?>">
                            <input type="text" name="adminname" value="<?php echo htmlspecialchars($admin['adminName']); ?>" required>
                            <input type="password" name="password" placeholder="New Password (optional)">
                            <button type="submit">Update</button>
                        </form>

                        <!-- Form to delete admin -->
                        <form method="POST" action="delete_admins.php" style="display:inline-block;" onsubmit="return confirm('Delete this admin?');">
                            <input type="hidden" name="userID" value="<?php echo $admin['userID']; ?>">
                            <button type="submit" class="delete-btn">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <!-- If no admins found, show message -->
            <tr><td colspan="3">No admins found.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

</body>
</html>

