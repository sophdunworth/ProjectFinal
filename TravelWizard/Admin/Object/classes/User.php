<?php
//DB connection
require_once(__DIR__ . '/../db1.php'); 

// User class represents a system user and provides methods to manage their account data

class User {
    // Database connection
    protected $conn;
    // User's unique ID
    private $userID;  
    // Username
    private $username;
     // User's email
    private $email;
    // User's hashed password
    private $password; 
    // Type of user: admin or customer
    private $user_type;       

    //Constructor: Accepts a PDO connection and an optional user ID. If a user ID is provided, it loads that user's details automatically.
     
    public function __construct($conn, $userID = null) {
        $this->conn = $conn;
        if ($userID) {
            $this->userID = $userID;
            $this->loadUser($userID); 
        }
    }

    //Loads a user's information from the database and sets class properties.
     
    private function loadUser($userID) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE userID = ?");
        $stmt->execute([$userID]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $this->userID = $user['userID'];
            $this->username = $user['username'];
            $this->email = $user['email'];
            $this->password = $user['password'];
            $this->user_type = $user['user_type'];
        }
    }

    //Getter methods

    public function getUserID() {
        return $this->userID;
    }

    public function getUsername() {
        return $this->username;
    }

    public function getEmail() {
        return $this->email;
    }

    //Setter methods 

    // method to sets a new username and updates the database
     
    public function setUsername($username) {
        $this->username = $username;
        $stmt = $this->conn->prepare("UPDATE users SET username = ? WHERE userID = ?");
        $stmt->execute([$this->username, $this->userID]);
    }

    // method to set a new email address and updates the database
    
    public function setEmail($email) {
        $this->email = $email;
        $stmt = $this->conn->prepare("UPDATE users SET email = ? WHERE userID = ?");
        $stmt->execute([$this->email, $this->userID]);
    }

    // method to sets a new password and updates the database.
     
    public function setPassword($password) {
        $this->password = password_hash($password, PASSWORD_DEFAULT); // Hash the password
        $stmt = $this->conn->prepare("UPDATE users SET password = ? WHERE userID = ?");
        $stmt->execute([$this->password, $this->userID]);
    }

    // method to set the user type and updates the database.
    public function setUserType($type) {
        $this->user_type = $type;
        $stmt = $this->conn->prepare("UPDATE users SET user_type = ? WHERE userID = ?");
        $stmt->execute([$this->user_type, $this->userID]);
    }

    // Returns all users from the database.
     
    public function getAllUsers() {
        $sql = "SELECT userID, username, email, user_type FROM users";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Searches for users whose email contains the given text.
     
    public function searchUsersByEmail($email) {
        $sql = "SELECT userID, username, email, user_type FROM users WHERE email LIKE ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['%' . $email . '%']);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>


