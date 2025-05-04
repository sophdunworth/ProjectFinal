<?php
// Database connection
require_once 'db1.php'; 
//Include the Customer Class
require_once 'classes/Customer.php';

//Define the escape helper function
function escape($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

//Check if the request method is POST and if the userID is set
if (isset($_POST['userID'])) {
    try {
        //Sanitize the user ID
        $userID = escape($_POST['userID']);

        //Start transaction
        $conn->beginTransaction();

        //Delete from customers table
        $stmt1 = $conn->prepare("DELETE FROM customers WHERE userID = :userID");
        $stmt1->bindParam(':userID', $userID, PDO::PARAM_INT);
        $stmt1->execute();

        //Delete from users table
        $stmt2 = $conn->prepare("DELETE FROM users WHERE userID = :userID");
        $stmt2->bindParam(':userID', $userID, PDO::PARAM_INT);
        $stmt2->execute();

        //Commit if successful
        $conn->commit();
        
        //Redirect when deleted
        header("Location: manage_customers.php?success=deleted");
        exit();

    } catch (PDOException $e) {
        //If theres an error redirect
        $conn->rollBack();
        header("Location: manage_customers.php?error=delete_failed");
        exit();
    }
} else {
    header("Location: manage_customers.php?error=invalid_request");
    exit();
}
?>


