<?php
require_once 'db1.php';
require_once 'classes/User.php';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['update_user'])) {
    $userID = intval($_POST['userID'] ?? 0);
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $userType = trim($_POST['user_type'] ?? '');

    if (
        $userID <= 0 ||
        empty($username) ||
        !filter_var($email, FILTER_VALIDATE_EMAIL) ||
        !in_array($userType, ['admin', 'customer'])
    ) {
        header("Location: manage_users.php?error=invalid_input");
        exit();
    }

    try {
        // Update main users table
        $user = new User($conn, $userID);
        $user->setUsername($username);
        $user->setEmail($email);
        $user->setUserType($userType);

        // Update role-specific table
        if ($userType === 'admin') {
            // Update adminName only — email isn't stored in admins table
            $check = $conn->prepare("SELECT COUNT(*) FROM admins WHERE userID = ?");
            $check->execute([$userID]);
            if ($check->fetchColumn()) {
                $stmt = $conn->prepare("UPDATE admins SET adminName = ? WHERE userID = ?");
                $stmt->execute([$username, $userID]);
            }
        } else {
            // Update customers table 
            $check = $conn->prepare("SELECT COUNT(*) FROM customers WHERE userID = ?");
            $check->execute([$userID]);
            if ($check->fetchColumn()) {
                $stmt = $conn->prepare("UPDATE customers SET username = ?, email = ? WHERE userID = ?");
                $stmt->execute([$username, $email, $userID]);
            }
        }

        header("Location: manage_users.php?success=updated");
        exit();
    } catch (Exception $e) {
        error_log("❌ Update failed: " . $e->getMessage());
        header("Location: manage_users.php?error=update_failed");
        exit();
    }
}
?>
