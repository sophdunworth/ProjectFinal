<?php
// database connection
require_once 'db1.php';

// Include the Booking class for linking payments to bookings
require_once 'Booking.php'; 

//Payment class for managing payment-related operations in the system.
 
class Payment {
    // Database connection 
    private $conn;  
    // ID of the payment
    private $paymentID; 
    //Booking object
    private $booking; 
    // Amount that has been paid
    private $amountPaid; 
    // Remaining amount to be paid
    private $amountPending; 
    // Date the payment was made
    private $transactionDate; 
     // Status of the payment 
    private $paymentStatus;     

    //Constructor: Initializes the class with a DB connection.
     
    public function __construct($conn, $paymentID = null) {
        $this->conn = $conn;
        if ($paymentID) {
            $this->loadPayment($paymentID); 
        }
    }

    //Loads payment details from the database based on payment ID.
     
    private function loadPayment($paymentID) {
        $stmt = $this->conn->prepare("SELECT * FROM payments WHERE paymentID = ?");
        $stmt->execute([$paymentID]);
        $payment = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($payment) {
            $this->paymentID = $payment['paymentID'];
            $this->booking = new Booking($this->conn, $payment['bookingID']);
            $this->amountPaid = $payment['amountPaid'];
            $this->amountPending = $payment['amountPending'];
            $this->transactionDate = $payment['transactionDate'];
            $this->paymentStatus = $payment['payment_status'];
        }
    }

    // Returns all payment records from the database.
     
    public function getAllPayments() {
        $stmt = $this->conn->prepare("SELECT * FROM payments");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //Getter methods

    public function getPaymentID() { return $this->paymentID; }

    public function getBooking() { return $this->booking; }

    public function getAmountPaid() { return $this->amountPaid; }

    public function getAmountPending() { return $this->amountPending; }

    public function getTransactionDate() { return $this->transactionDate; }

    public function getPaymentStatus() { return $this->paymentStatus; }

    // method to set a new payment status and updates it in the database.
     
    public function setPaymentStatus($paymentStatus) {
        $this->paymentStatus = $paymentStatus;
        $stmt = $this->conn->prepare("UPDATE payments SET payment_status = ? WHERE paymentID = ?");
        $stmt->execute([$paymentStatus, $this->paymentID]);
    }

    // Updates the payment record in the database using the booking ID. Also allows storing optional notes.
     
    public function updatePayment($bookingID, $amountPaid, $amountPending, $paymentStatus, $notes) {
        $stmt = $this->conn->prepare("
            UPDATE payments 
            SET amountPaid = ?, amountPending = ?, payment_status = ?, notes = ?
            WHERE bookingID = ?
        ");
        return $stmt->execute([$amountPaid, $amountPending, $paymentStatus, $notes, $bookingID]);
    }
}
?>
