<?php 
// DB connection
require_once 'db1.php';    
//Include User class
require_once 'classes/User.php';     

// Escape helper function
function escape($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

// Check if userID was posted
if (isset($_POST['userID'])) {
    try {
        $userID = escape($_POST['userID']);

        // Start transaction to ensure all deletions happen together on all the DB tables
        $conn->beginTransaction();

        //Delete from admin and customer tables first
        $conn->prepare("DELETE FROM admins WHERE userID = :userID")->execute([':userID' => $userID]);
        $conn->prepare("DELETE FROM customers WHERE userID = :userID")->execute([':userID' => $userID]);

        //Delete from users table
        $conn->prepare("DELETE FROM users WHERE userID = :userID")->execute([':userID' => $userID]);

        //Commit if all successful
        $conn->commit();

        // Redirect with success
        header("Location: manage_users.php?success=User+deleted+successfully");
        exit;

    } catch (PDOException $e) {
        // Redirect and display an erro
        $conn->rollBack();

        error_log("Delete error: " . $e->getMessage());
        header("Location: manage_users.php?error=Deletion+failed");
        exit;
    }
} else {
    
    header("Location: manage_users.php?error=Invalid+request");
    exit;
}
?>
