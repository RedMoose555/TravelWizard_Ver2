<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Notifications</title>
    <link rel="stylesheet" href="css/Admin.css"> 
    <style>
        /* Styling for the page layout and components */
        body {
            background-color: #e6f7fa;
            font-family: Arial, sans-serif;
            padding: 40px;
        }

        h2 {
            text-align: center;
            background-color: #0288d1;
            color: white;
            padding: 20px;
            border-radius: 6px;
        }

        .search-container {
            text-align: center;
            margin-bottom: 30px;
        }

        input[type="text"] {
            padding: 10px;
            font-size: 16px;
            width: 320px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        .notification {
            background: white;
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 20px;
            margin: 20px auto;
            width: 80%;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }

        .notification-title {
            font-weight: bold;
            font-size: 18px;
            cursor: pointer;
            color: #0288d1;
        }

        .sent-at-inline {
            color: #666;
            font-size: 14px;
            margin-left: 10px;
        }

        .notification-message {
            display: none;
            margin-top: 12px;
            white-space: pre-wrap;
        }

        .sent-at {
            color: gray;
            font-size: 13px;
            margin-top: 10px;
        }

        .back-link {
            text-align: center;
            margin-top: 30px;
        }

        .back-link a {
            color: #0288d1;
            font-weight: bold;
            text-decoration: none;
        }

        .back-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<h2>Previous Notifications</h2>

<!-- Search box to filter notifications by month -->
<div class="search-container">
    <input type="text" id="searchInput" placeholder="Search by month (e.g. March or 03)">
</div>

<!-- Notification list -->
<div id="notificationList">
    <?php if (!empty($notifications)): ?>
        <?php foreach ($notifications as $n): ?>
            <?php
                // Convert the sent_at datetime into different formats for display and filtering
                $sentAt = strtotime($n['sent_at']);
                $monthName = strtolower(date('F', $sentAt));   
                $monthNumber = date('m', $sentAt);              
                $sentFormatted = date("Y-m-d H:i", $sentAt);    
            ?>
            <!-- Each notification is wrapped with a data attribute for filtering -->
            <div class="notification" data-month="<?= $monthName ?> <?= $monthNumber ?>">
                <div class="notification-title" onclick="toggleMessage(this)">
                    <?= htmlspecialchars($n['name']) ?>
                    <span class="sent-at-inline">(<?= $sentFormatted ?>)</span>
                </div>
                <div class="notification-message">
                    <?= nl2br(htmlspecialchars($n['message'])) ?>
                    <div class="sent-at">Sent at: <?= htmlspecialchars($n['sent_at']) ?></div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p style="text-align:center;">No notifications found.</p>
    <?php endif; ?>
</div>

<!-- Back to main notifications management page -->
<div class="back-link">
    <a href="manage_notifications.php">← Back to Notifications</a>
</div>

<!-- JavaScript for toggling messages and filtering -->
<script>
// Toggle the visibility of a notification's message
function toggleMessage(titleElement) {
    const messageDiv = titleElement.nextElementSibling;
    messageDiv.style.display = (messageDiv.style.display === "block") ? "none" : "block";
}

// Filter notifications based on search input 
document.getElementById("searchInput").addEventListener("input", function () {
    const filter = this.value.toLowerCase();
    const notifications = document.querySelectorAll(".notification");

    notifications.forEach(function (notif) {
        const data = notif.getAttribute("data-month");
        notif.style.display = data.includes(filter) ? "block" : "none";
    });
});
</script>

</body>
</html>

