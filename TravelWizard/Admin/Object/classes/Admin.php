<?php 
// Include the base User class 
require_once 'User.php';    
// Include database connection
require_once 'db1.php';     

// Admin class to manage admin-related data and actions.
 // Inherits from User class.
 
class Admin extends User {
    //admin properties
    private $adminName;
    private $email;
    private $userID;
    private $password;

    //Constructor: accepts database connection, optional user ID and admin name.
    
    public function __construct($conn, $userID = null, $adminName = null) {
        parent::__construct($conn, $userID); // Load user properties from User class
        $this->userID = $userID;
        if ($adminName) {
            $this->adminName = $adminName;
        }
    }

    // Create only an admin record 
    public function createAdmin($adminName, $email, $password) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare("INSERT INTO admins (adminName, email, password) VALUES (?, ?, ?)");
        return $stmt->execute([$adminName, $email, $hashedPassword]);
    }

    // Create both a user and an admin at the same time 
    public function createAdminAndUser($adminName, $email, $password) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        try {
            // Start a transaction to ensure data consistency
            $this->conn->beginTransaction();

            // Insert admin as a user first
            $stmtUser = $this->conn->prepare("INSERT INTO users (username, email, password, user_type) VALUES (?, ?, ?, 'admin')");
            $stmtUser->execute([$adminName, $email, $hashedPassword]);

            // Get the userID that was just created
            $userID = $this->conn->lastInsertId();

            //  insert into the admins table using that userID
            $stmtAdmin = $this->conn->prepare("INSERT INTO admins (userID, adminName, password) VALUES (?, ?, ?)");
            $stmtAdmin->execute([$userID, $adminName, $hashedPassword]);

            // Commit the transaction
            $this->conn->commit();
            return true;
        } catch (PDOException $e) {
            // fails display an error
            $this->conn->rollBack();
            echo "❌ DB ERROR: " . $e->getMessage();
            return false;
        }
    }

    // Update the admin's name in both admins and users tables.
     
    public function updateAdmin($adminID, $newName) {
        try {
            // Update admin name in admins table
            $stmtAdmin = $this->conn->prepare("UPDATE admins SET adminName = ? WHERE userID = ?");
            $stmtAdmin->execute([$newName, $adminID]);

            // Update username in users table
            $stmtUser = $this->conn->prepare("UPDATE users SET username = ? WHERE userID = ?");
            $stmtUser->execute([$newName, $adminID]);

            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    // Retrieve all admins from the database.
     
    public function getAllAdmins() {
        $stmt = $this->conn->prepare("SELECT * FROM admins");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Set the admin's name and update it in the database.
     
    public function setAdminName($adminName) {
        $this->adminName = $adminName;
        $userID = $this->getUserID();

        if (!$userID) {
            throw new Exception("❌ No valid userID found.");
        }

        // Update the admin name in the admins table
        $stmt = $this->conn->prepare("UPDATE admins SET adminName = ? WHERE userID = ?");
        if (!$stmt->execute([$adminName, $userID])) {
            throw new Exception("❌ Failed to update admin name.");
        }
    }

    // Update the password of an admin and in user table
    public function setPassword($password) {
        $this->password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare("UPDATE users SET password = ? WHERE userID = ?");
        if (!$stmt->execute([$this->password, $this->getUserID()])) {
            throw new Exception("❌ Failed to update password.");
        }
    }

    // Create an admin record using an existing user ID.
     
    public function createAdminWithID($userID, $adminName, $password, $email = null) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare("INSERT INTO admins (userID, adminName, email, password) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$userID, $adminName, $email, $hashedPassword]);
    }

    // Delete the admin from both the admins and users tables
    public function deleteAdmin($userID) {
        try {
            // Delete from admins table
            $stmtAdmin = $this->conn->prepare("DELETE FROM admins WHERE userID = ?");
            $stmtAdmin->execute([$userID]);

            // Delete from users table
            $stmtUser = $this->conn->prepare("DELETE FROM users WHERE userID = ?");
            $stmtUser->execute([$userID]);

            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    // Getters 

    public function getAdminName() {
        return $this->adminName;
    }

    
    public function getEmail() {
        return $this->email;
    }
}
?>




