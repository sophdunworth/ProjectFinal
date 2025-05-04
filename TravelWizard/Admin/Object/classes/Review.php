<?php 
//database connection
require_once 'db1.php'; 

// Review class is used to represent and manage customer reviews 
 
class Review {
    // Database connection
    private $conn;
    // Review ID 
    private $reviewID;
    // Email of the customer who left the review
    private $email;
    // Star rating value
    private $rating; 
    // Service being reviewed
    private $service; 
    // Written review content
    private $reviewText;  

    //Constructor: Accepts database connection and optionally a review ID
     
     
    public function __construct($conn, $reviewID = null) {
        $this->conn = $conn;
        if ($reviewID) {
            $this->loadReview($reviewID); 
        }
    }

    // Loads a specific review from the database by its ID
    
    private function loadReview($reviewID) {
        $stmt = $this->conn->prepare("SELECT * FROM reviews WHERE reviewID = ?");
        $stmt->execute([$reviewID]);
        $review = $stmt->fetch(PDO::FETCH_ASSOC);

        // If a review is found, assign its data to the class properties
        if ($review) {
            $this->reviewID = $review['reviewID'];
            $this->email = $review['email'];
            $this->rating = $review['rating'];
            $this->service = $review['service'];
            $this->reviewText = $review['reviewText'];
        }
    }

    //Getter methods

    public function getReviewID() { return $this->reviewID; }

    public function getEmail() { return $this->email; }

    public function getRating() { return $this->rating; }

    public function getService() { return $this->service; }

    public function getReviewText() { return $this->reviewText; }

    // Retrieves all reviews from the database. If a search query is provided, filters reviews by service or email.
     
    public function getAllReviews($searchQuery = '') {
        // Base SQL to get all reviews
        $sql = "SELECT * FROM reviews";

        // If search input is provided, filter by service or email
        if (!empty($searchQuery)) {
            $sql .= " WHERE service LIKE ? OR email LIKE ?";
        }

        $stmt = $this->conn->prepare($sql);

        
        if (!empty($searchQuery)) {
            $searchTerm = "%" . $searchQuery . "%";
            $stmt->execute([$searchTerm, $searchTerm]);
        } else {
            $stmt->execute(); 
        }

        // Return the matching review records
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>

