<?php
// Include database connection 
require_once 'db1.php';
//Include the Payment class
require_once 'classes/Payment.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Helper function to sanitize input
function escape($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

// Check if the payment update form was submitted
if (isset($_POST['update_payment'])) {
    try {
        // Sanitize and store form input into an array
        $payment_data = array(
            "bookingID"      => escape($_POST['bookingID']),
            "amountPaid"     => escape($_POST['amountPaid']),
            "amountPending"  => escape($_POST['amountPending']),
            "payment_status" => escape($_POST['payment_status']),
            "notes"          => escape($_POST['notes'] ?? '') 
        );

        // SQL query to update the payment record based on booking ID
        $sql = "UPDATE payments SET 
                    amountPaid = :amountPaid,
                    amountPending = :amountPending,
                    payment_status = :payment_status,
                    notes = :notes
                WHERE bookingID = :bookingID";

        // Prepare and execute the update query
        $stmt = $conn->prepare($sql);
        $stmt->execute($payment_data);

        // Redirect to payment management page with success message
        header("Location: manage_payments.php?status=Payment+updated+successfully");
        exit();

    } catch (PDOException $e) {
        // redirect with an error message
        header("Location: manage_payments.php?error=Update+failed");
        exit();
    }
} else {
    // redirect with error
    header("Location: manage_payments.php?error=Invalid+form+submission");
    exit();
}
