<?php 
//Include Booking Class
require_once 'classes/Booking.php';
//DB Connection
require_once 'db1.php'; 

//Enable error reporting (
error_reporting(E_ALL);
ini_set('display_errors', 1);

//Escape helper for input sanitization
function escape($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

//Check if bookingID was posted
if (isset($_POST['bookingID'])) {
    try {
        //Sanitize the input
        $bookingID = escape($_POST['bookingID']); 

        //Prepare the SQL statement
        $sql = "DELETE FROM bookings WHERE bookingID = :bookingID";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':bookingID', $bookingID, PDO::PARAM_INT);
        $stmt->execute();

        //Redirect based on outcome
        if ($stmt->rowCount() > 0) {
            header("Location: manage_bookings.php?status=Booking+deleted+successfully");
        } else {
            header("Location: manage_bookings.php?status=No+booking+found+to+delete");
        }
        exit; //Ensure script stops after redirect
    } catch (PDOException $e) {
        //Redirect with error
        $error = urlencode("Database+error:+".$e->getMessage());
        header("Location: manage_bookings.php?status=$error");
        exit;
    }
} else {
    //redirect with an error
    header("Location: manage_bookings.php?status=Invalid+request");
    exit;
}
?>
