<?php
// Include the database connection file
require_once 'db1.php';  

// Define the ContactUsRequest class to handle operations related to contact form submissions
class ContactUsRequest {
    private $conn;

    // Constructor 
    public function __construct($conn) {
        $this->conn = $conn;
    }

    //Fetch all contact requests from the database, ordered by newest first
    public function getAllRequests() {
        $query = "SELECT * FROM contactusrequests ORDER BY created_at DESC";
        $stmt = $this->conn->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //  Mark a specific request as answered
    public function markAsAnswered($id, $adminID) {
        $query = "UPDATE contactusrequests SET answered = 1, admin_id = ?, status = 'closed' WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$adminID, $id]);
    }

    // Get a specific contact request by its ID
    public function getRequestByID($id) {
        $stmt = $this->conn->prepare("SELECT * FROM contactusrequests WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    //Submit a response to a contact reques
    //mark as answered
    //mark as closed
    public function submitResponse($id, $adminID, $response) {
        $stmt = $this->conn->prepare("
            UPDATE contactusrequests 
            SET answered = 1, admin_id = ?, response = ?, status = 'closed' 
            WHERE id = ?
        ");
        return $stmt->execute([$adminID, $response, $id]);
    }

    // Fetch contact requests filtered by whether they are answered or not 
    public function getRequestsByAnswered(bool $answered) {
        $sql = "SELECT * FROM contactusrequests WHERE answered = :answered ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':answered' => $answered ? 1 : 0]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>


