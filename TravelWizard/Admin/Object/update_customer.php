<?php 
// Include the database connection
require_once 'db1.php'; 

// Define the escape helper function
function escape($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

// Check if form was submitted
if (isset($_POST['submit'])) {
    try {
        // Sanitize and store user input
        $update_data = array(
            "userID"   => escape($_POST['userID']),
            "username" => escape($_POST['username'] ?? ''),
            "email"    => escape($_POST['email'] ?? '')
        );

        // Validate required fields
        if ($update_data['userID'] <= 0 || empty($update_data['username']) || empty($update_data['email'])) {
            throw new Exception("Invalid input");
        }

        // Validate email format
        if (!filter_var($update_data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email format");
        }

        // Begin transaction
        $conn->beginTransaction();

        // Update users table
        $stmtUsers = $conn->prepare("UPDATE users SET username = :username, email = :email WHERE userID = :userID");
        $stmtUsers->execute($update_data);

        // Update customers table
        $stmtCustomers = $conn->prepare("UPDATE customers SET username = :username, email = :email WHERE userID = :userID");
        $stmtCustomers->execute($update_data);

        // Commit the transaction
        $conn->commit();

        // redircted with success message
        header("Location: manage_customers.php?success=updated");
        exit();

    } catch (Exception $e) {
        if ($conn->inTransaction()) {
            $conn->rollBack();
        }
        // redirect with error message
        header("Location: manage_customers.php?error=" . urlencode($e->getMessage()));
        exit();
    }
}
?>
