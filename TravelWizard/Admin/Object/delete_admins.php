<?php  
//Include the admin class
require_once 'classes/Admin.php';
//DB connection
require_once 'db1.php'; 

//function to sanitize input values
function escape($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

//Check if a user ID was submitted
if (isset($_POST['userID'])) {
    try {
        $userID = escape($_POST['userID']);

        // Start a transaction for safe delete
        $conn->beginTransaction();

        // Delete from admins table
        $stmt1 = $conn->prepare("DELETE FROM admins WHERE userID = :userID");
        $stmt1->bindParam(':userID', $userID, PDO::PARAM_INT);
        $stmt1->execute();

        // Delete from users table
        $stmt2 = $conn->prepare("DELETE FROM users WHERE userID = :userID");
        $stmt2->bindParam(':userID', $userID, PDO::PARAM_INT);
        $stmt2->execute();

        //commit the changes
        $conn->commit();

        //redirect back to manage_admins.php
        if ($stmt1->rowCount() > 0 && $stmt2->rowCount() > 0) {
            header("Location: manage_admins.php?status=Admin+and+user+deleted+successfully");
        } else {
            header("Location: manage_admins.php?status=Delete+ran+but+rows+not+affected");
        }
        exit;
    } catch (PDOException $e) {
         // Undo if something fails
        $conn->rollBack();
        $error = urlencode("Database+error:+".$e->getMessage());
        header("Location: manage_admins.php?status=$error");
        exit;
    }
} else {
    //redirect with an error
    header("Location: manage_admins.php?status=Invalid+request");
    exit;
}
?>





