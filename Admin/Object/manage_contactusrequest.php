<?php
// Database connection
require_once 'db1.php';                  
// Contact request class
require_once 'classes/ContactUsRequest.php'; 
$contactHandler = new ContactUsRequest($conn);
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Instantiate the class and fetch all requests
$contactRequest = new ContactUsRequest($conn);
$requests = $contactRequest->getAllRequests();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Contact Requests</title>
    <link rel="stylesheet" href="css/Admin.css">
</head>
<body>

<h2>Contact Us Requests</h2>

<!-- Navigation -->
<p><a href="dashboard.php">← Back to Dashboard</a></p>

<?php if (!empty($requests)): ?>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Email</th>
                <th>Subject</th>
                <th>Message</th>
                <th>Status</th>
                <th>Answered</th>
                <th>Created At</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($requests as $request): ?>
            <tr>
                <td><?= htmlspecialchars($request['id']) ?></td>
                <td><?= htmlspecialchars($request['email']) ?></td>
                <td><?= htmlspecialchars($request['subject']) ?></td>
                <td><?= nl2br(htmlspecialchars($request['message'])) ?></td>
                <td><?= htmlspecialchars($request['status']) ?></td>
                <td><?= $request['answered'] ? 'Yes' : 'No' ?></td>
                <td><?= htmlspecialchars($request['created_at']) ?></td>
                <td>
                    <?php if (!$request['answered']): ?>
                        <a href="answer_contactusrequest.php?id=<?= $request['id'] ?>">
                            <button type="button">Answer</button>
                        </a>
                    <?php else: ?>
                        ✅
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>No contact requests found.</p>
<?php endif; ?>

</body>
</html>
