<?php
// database connection
require_once 'db1.php';
require_once 'Customer.php';

class Booking {
    //  Database connection
    private $conn;  
    //booking properties
    private $bookingID;    
    private $customerID;  
    private $packageID;    
    private $dateBooked;   
    private $status;  
    
    //  Constructor 
    
    public function __construct($conn, $bookingID = null) {
        $this->conn = $conn; 

        if ($bookingID) {
            $this->loadBooking($bookingID); 
        }
    }

    //  Load booking details from the database by ID
    private function loadBooking($bookingID) {
        $stmt = $this->conn->prepare("SELECT * FROM bookings WHERE bookingID = ?");
        $stmt->execute([$bookingID]);
        $booking = $stmt->fetch(PDO::FETCH_ASSOC);

        // If a valid booking is found store its properties in the object
        if ($booking) {
            $this->bookingID = $booking['bookingID'];
            $this->customerID = $booking['customerID'];
            $this->packageID = $booking['packageID'];
            $this->dateBooked = $booking['dateBooked'];
            $this->status = $booking['status'];
        }
    }

    //  Getter Methods 
    public function getBookingID()    { return $this->bookingID; }
    public function getCustomerID()   { return $this->customerID; }
    public function getPackageID()    { return $this->packageID; }
    public function getDateBooked()   { return $this->dateBooked; }
    public function getStatus()       { return $this->status; }

    // Instance Method to Update Booking Status
    
    public function setStatus($status) {
        $this->status = $status;
        $stmt = $this->conn->prepare("UPDATE bookings SET status = ? WHERE bookingID = ?");
        $stmt->execute([$status, $this->bookingID]);
    }

    //  Method to Update Status Using Booking ID 
   
    public function updateBookingStatus($bookingID, $status) {
        $stmt = $this->conn->prepare("UPDATE bookings SET status = ? WHERE bookingID = ?");
        return $stmt->execute([$status, $bookingID]);
    }
}
?>



