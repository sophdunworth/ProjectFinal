<?php 
//database connection
require_once 'db1.php';                    

// Include the Notification class 
require_once 'classes/Notification.php';   

// Create a new instance of the Notification class
$notificationObj = new Notification($conn);

// Fetch all notifications from the database
$notifications = $notificationObj->getAllNotifications();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Notifications</title>
    <link rel="stylesheet" href="css/Admin.css"> 
</head>
<body>

<h2>Previous Notifications</h2>

<!-- A search bar to filter notifications by month  -->
<div class="search-container" style="max-width: 400px; display: flex; gap: 10px;">
    <input type="text" id="searchInput" placeholder="Search by month (e.g. March or 03)" class="form-control">
    <!-- Clear button to reset search input -->
    <button type="button" class="Clear" onclick="document.getElementById('searchInput').value = '';">Clear</button>
</div>

<!-- Link to go back to the main notification management page -->
<a href="manage_notifications.php" class="back-link">← Back to Notifications</a>

<!-- List of all notifications -->
<div id="notificationList">
    <?php if (!empty($notifications)): ?>
        <?php foreach ($notifications as $n): ?>
            <?php
                // Convert the datetime string to a timestamp for formatting
                $sentAt = strtotime($n['sent_at']);

                // Extract the month in both name and number formats
                $monthName = strtolower(date('F', $sentAt));   
                $monthNumber = date('m', $sentAt);            

                // Format the full datetime nicely for display
                $sentFormatted = date("Y-m-d H:i", $sentAt);    
            ?>
            <!-- Each notification has a data attribute to allow filtering -->
            <div class="notification" data-month="<?= $monthName ?> <?= $monthNumber ?>">
                <!-- Notification title that toggles the message display -->
                <div class="notification-title" onclick="toggleMessage(this)">
                    <?= htmlspecialchars($n['name']) ?>
                    <span class="sent-at-inline">(<?= $sentFormatted ?>)</span>
                </div>

                <!-- Hidden notification message that appears when clicked -->
                <div class="notification-message">
                    <?= nl2br(htmlspecialchars($n['message'])) ?>
                    <div class="sent-at">Sent at: <?= htmlspecialchars($n['sent_at']) ?></div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <!-- Message if no notifications are found -->
        <p style="text-align:center;">No notifications found.</p>
    <?php endif; ?>
</div>
