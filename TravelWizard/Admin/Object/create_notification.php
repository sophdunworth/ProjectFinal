<?php
// Start session
session_start(); 
// DB connection
require_once 'db1.php';
// Include the Notification class
require_once 'classes/Notification.php'; 


// Escape function to sanitize user input
function escape($data) {
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

// Check if the form was submitted
if (isset($_POST['submit'])) {
    try {
        // Sanitize form input values
        $name = escape($_POST['name']);              
        $message = escape($_POST['message']);         
        $adminID = intval($_POST['user_id']);         
        $sentAt = date("Y-m-d H:i:s");                

        // Create a Notification object using the database connection
        $notificationObj = new Notification($conn);

        // Attempt to create and send the notification
        $success = $notificationObj->createNotification($name, $adminID, $message, $sentAt);

        // If successful, redirect to the management page with a success flag
        if ($success) {
            header("Location: manage_notifications.php?success=1");
            exit;
        } else {
            // If failure, show an alert
            echo "<script>alert('❌ Failed to send notification to users.');</script>";
        }
    } catch (PDOException $e) {
        // Catch and display database-related errors
        echo "<script>alert('❌ Database error: " . $e->getMessage() . "');</script>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Notification</title>
    <link rel="stylesheet" href="css/Admin.css"> 
</head>
<body>

<h2>Create Notification</h2>

<!-- Link to go back to the notification management page -->
<a href="manage_notifications.php" class="back-link">← Back to Manage Notifications</a>

<!-- Notification creation form -->
<form method="POST" action="create_notification.php">
    <input type="text" name="name" placeholder="Notification Title" required>
    <textarea name="message" placeholder="Notification Message" required></textarea>
    <input type="number" name="user_id" placeholder="Admin User ID" required>
    
    <!-- Submit button to send the notification -->
    <button type="submit" name="submit" class="Answer">Send Notification</button>
</form>

</body>
</html>





