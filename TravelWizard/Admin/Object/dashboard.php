<?php
session_start();
//DB Connection
require_once 'db1.php';
include '../templates/header2.php'; 


// Check that the user is logged in
if (isset($_SESSION['userID'])) {
    $userID = $_SESSION['userID'];

    // Check if this user already exists in the admins table
    $stmt = $conn->prepare("SELECT COUNT(*) FROM admins WHERE userID = ?");
    $stmt->execute([$userID]);

    if ($stmt->fetchColumn() == 0) {
        // Get the username from users table
        $userStmt = $conn->prepare("SELECT username FROM users WHERE userID = ?");
        $userStmt->execute([$userID]);
        $user = $userStmt->fetch(PDO::FETCH_ASSOC);

        // Insert into admins table
        if ($user) {
            $insertAdmin = $conn->prepare("INSERT INTO admins (userID, adminname) VALUES (?, ?)");
            $insertAdmin->execute([$userID, $user['username']]);
        }
    }
}


?>

<link rel="stylesheet" href="css/Admin.css">

<h3>Manage Sections</h3>

<!-- Dashboard grid display admin only features -->

<div class="dashboard-grid">
    <div class="grid-item"><a href="manage_users.php">Manage Users</a></div>
    <div class="grid-item"><a href="manage_bookings.php">Manage Bookings</a></div>
    <div class="grid-item"><a href="manage_reviews.php">Manage Reviews</a></div>
    <div class="grid-item"><a href="manage_admins.php">Manage Admins</a></div>
    <div class="grid-item"><a href="manage_customers.php">Manage Customers</a></div>
    <div class="grid-item"><a href="manage_payments.php">Manage Payments</a></div>
    <div class="grid-item"><a href="manage_notifications.php">Manage Notifications</a></div>
    <div class="grid-item"><a href="manage_contactusrequest.php">Customer Queries</a></div>
</div>





