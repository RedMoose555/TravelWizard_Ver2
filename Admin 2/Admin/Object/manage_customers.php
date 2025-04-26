<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Customers</title>
    <link rel="stylesheet" href="css/Admin.css">
    <style>
        /* Basic styling for layout */
        body { font-family: Arial, sans-serif; padding: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { padding: 12px; border: 1px solid #ccc; }
        th { background-color: #0288d1; color: white; }
        button { padding: 6px 12px; background-color: #0288d1; color: white; border: none; cursor: pointer; }
        button:hover { background-color: #0277bd; }
        .delete-btn { background-color: #e53935; }
        .delete-btn:hover { background-color: #c62828; }

        /* Modal styling */
        .modal { display: none; position: fixed; top: 20%; left: 50%; transform: translate(-50%, -20%); background: #fff; padding: 20px; border: 2px solid #333; z-index: 9999; }
        .modal-content { width: 300px; }
        .close { float: right; cursor: pointer; font-weight: bold; color: red; }
    </style>
</head>
<body>

<h2>Manage Customers</h2>
<a href="dashboard.php" style="text-decoration: none; color: #007BFF;">&larr; Back to Dashboard</a>

<!-- Display feedback based on action outcome -->
<?php if (isset($_GET['success']) && $_GET['success'] === 'deleted'): ?>
    <p style="color: green;">✅ Customer deleted successfully!</p>
<?php elseif (isset($_GET['success']) && $_GET['success'] === 'updated'): ?>
    <p style="color: green;">✅ Customer updated successfully!</p>
<?php elseif (isset($_GET['error'])): ?>
    <p style="color: red;">❌ An error occurred.</p>
<?php endif; ?>

<!-- Search form -->
<form method="GET" action="" style="margin-top: 20px;">
    <input type="text" name="search" placeholder="Search by email..." value="<?= htmlspecialchars($searchQuery); ?>">
    <button type="submit">Search</button>
</form>

<!-- Customer table -->
<table>
    <thead>
        <tr>
            <th>User ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
    <?php if (!empty($customers)): ?>
        <?php foreach ($customers as $cust): ?>
            <tr>
                <td><?= htmlspecialchars($cust['userID']); ?></td>
                <td><?= htmlspecialchars($cust['username']); ?></td>
                <td><?= htmlspecialchars($cust['email']); ?></td>
                <td>
                    <!-- Open modal with pre-filled form -->
                    <button onclick="openModal('<?= $cust['userID']; ?>', '<?= htmlspecialchars($cust['username'], ENT_QUOTES); ?>', '<?= htmlspecialchars($cust['email'], ENT_QUOTES); ?>')">Edit</button>
                    
                    <!-- Delete form -->
                    <form method="POST" action="" style="display:inline;">
                        <input type="hidden" name="userID" value="<?= $cust['userID']; ?>">
                        <button type="submit" name="delete_user" class="delete-btn" onclick="return confirm('Are you sure?');">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr><td colspan="4">No customers found.</td></tr>
    <?php endif; ?>
    </tbody>
</table>
