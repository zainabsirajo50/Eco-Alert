<?php
session_start();

include "path.php";
require ROOT_PATH . "/app/database/connection.php";

$user_type = isset($_SESSION['user_type']) ? $_SESSION['user_type'] : 'community_member'; // Default to 'community_member' if not set

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "You must be logged in to change your password.";
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Fetch the current password from the database
    $query = "SELECT password FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (!$user) {
        echo "User not found.";
        exit();
    }

    // Validate current password
    if ($user['password'] !== $current_password) {
        echo "<script>alert('Current password is incorrect.');</script>";
    } elseif ($new_password !== $confirm_password) {
        echo "<script>alert('New password and confirm password do not match.');</script>";
    } else {
        // Update the password in the database
        $update_query = "UPDATE users SET password = ? WHERE id = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("si", $new_password, $user_id);

        if ($stmt->execute()) {
            echo "<script>alert('Password changed successfully!');</script>";
            header("Location: " . BASE_URL . "/settings.php"); // Redirect to settings page
            exit();
        } else {
            echo "<script>alert('Failed to update password.');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="src/css/LoginForm.css">
    <title>Change Password</title>
</head>
<body>

<header>
    <div class="header-container">
    <div class="header-buttons">
                <button onclick="window.location.href='<?php echo $user_type === 'govt_worker' ? BASE_URL . '/govt-homepage.php' : BASE_URL . '/user-homepage.php'; ?>'">
                    Home
                </button>
                    <?php if ($user_type !== 'govt_worker'): ?>
                        <button onclick="window.location.href='<?php echo BASE_URL; ?>/pageview/reports/index.php'">Create
                        Report</button>

                    <?php endif; ?>
                    <button onclick="window.location.href='<?php echo BASE_URL; ?>/pageview/events/index.php'">View
                        Events</button>
                </div>

                <div class="header-search">
                    <form method="GET" action="search_results.php">
                        <input type="text" name="search_query" placeholder="Search reports or events..." required>
                        <button type="submit">Search</button>
                    </form>
                </div>

        <!-- Profile Dropdown -->
        <div class="profile-dropdown">
            <button class="profile-button">
                <?php echo $_SESSION['user_name']; ?>
            </button>
            <div class="dropdown-menu">
                <a href="<?php echo BASE_URL; ?>/view_profile.php">View Profile</a>
                <a href="<?php echo BASE_URL; ?>/settings.php">Settings</a>
                <a href="<?php echo BASE_URL; ?>/logout.php">Logout</a>
            </div>
        </div>
    </div>
</header>

<div class="report-form">
    <h1>Change Password</h1>
    <form method="POST" action="">
        <label for="current_password">Current Password:</label>
        <input type="password" id="current_password" name="current_password" required>
        <br>

        <label for="new_password">New Password:</label>
        <input type="password" id="new_password" name="new_password" required>
        <br>

        <label for="confirm_password">Confirm New Password:</label>
        <input type="password" id="confirm_password" name="confirm_password" required>
        <br>

        <button type="submit" class="submit-button">Update Password</button>
    </form>
    <br>
    <a class="signup-link" href="<?php echo BASE_URL; ?>/settings.php">Back to Settings</a>
</div>

</body>
</html>