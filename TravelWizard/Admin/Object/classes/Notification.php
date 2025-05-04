<?php
database connection
require_once 'db1.php';

// Notification class to manage sending and retrieving notifications for users.
 
class Notification {
    // Property to store the PDO connection
    private $conn; 

    // Constructor: accepts a PDO database connection
     
    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    // Create a new notification and send it to all users.
    public function createNotification($name, $adminID, $message, $sentAt) {
        try {
            // Fetch all user IDs from the users table
            $result = $this->conn->query("SELECT userID FROM users");
            $users = $result->fetchAll(PDO::FETCH_ASSOC);

            // Prepare SQL insert statement for inserting one notification per user
            $stmt = $this->conn->prepare("INSERT INTO notifications (user_id, name, message, sent_at) VALUES (?, ?, ?, ?)");

            // Loop through each user and send them the notification
            foreach ($users as $row) {
                $userID = $row['userID'];
                $stmt->execute([$userID, $name, $message, $sentAt]);
            }

            return true; // Return true on success
        } catch (PDOException $e) {
            // Log the error to help with debugging
            error_log("Error creating notifications: " . $e->getMessage());
            return false; // Return false on failure
        }
    }

    // Retrieve all notifications, ordered by most recent first.
     
    public function getAllNotifications() {
        // Fetch all notifications from the database
        $stmt = $this->conn->query("SELECT * FROM notifications ORDER BY sent_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Retrieve all notifications for a specific user.
     
    public function getNotificationsByUser($userID) {
        // Prepare and execute query for the specific user
        $stmt = $this->conn->prepare("SELECT * FROM notifications WHERE user_id = ? ORDER BY sent_at DESC");
        $stmt->execute([$userID]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Delete a notification by its ID.
    
    public function deleteNotification($id) {
        // Prepare and execute delete statement
        $stmt = $this->conn->prepare("DELETE FROM notifications WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
?>



