<?php  
// Include database connection
require_once 'db1.php';
//Include User class
require_once 'classes/User.php';

// Function to sanitize input
function escape($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

// Check if the required form fields are submitted
if (isset($_POST['userID'], $_POST['username'], $_POST['email'], $_POST['user_type'])) {
    try {
        // Sanitize and store submitted form data
        $user_data = array(
            "userID"    => escape($_POST['userID']),
            "username"  => escape($_POST['username']),
            "email"     => escape($_POST['email']),
            "user_type" => escape($_POST['user_type'])
        );

        // Validate the form data
        if (
            $user_data['userID'] <= 0 ||                                // Check if userID is valid
            empty($user_data['username']) ||                            // Check if username is not empty
            !filter_var($user_data['email'], FILTER_VALIDATE_EMAIL) ||  // Validate email format
            !in_array($user_data['user_type'], ['admin', 'customer'])   // Ensure user_type is either admin or customer
        ) {
            throw new Exception("Invalid input data");
        }

        // Create User object and update core user details
        $user = new User($conn, $user_data['userID']);
        $user->setUsername($user_data['username']);
        $user->setEmail($user_data['email']);
        $user->setUserType($user_data['user_type']);

        // Update corresponding admin or customer table 
        if ($user_data['user_type'] === 'admin') {
            // Check if user exists in the admins table
            $stmt = $conn->prepare("SELECT COUNT(*) FROM admins WHERE userID = :userID");
            $stmt->execute(["userID" => $user_data['userID']]);

            // update the admin name
            if ($stmt->fetchColumn()) {
                $update = $conn->prepare("UPDATE admins SET adminname = :username WHERE userID = :userID");
                $update->execute([
                    "username" => $user_data['username'],
                    "userID"   => $user_data['userID']
                ]);
            }
        } else {
            // Check if user exists in the customers table
            $stmt = $conn->prepare("SELECT COUNT(*) FROM customers WHERE userID = :userID");
            $stmt->execute(["userID" => $user_data['userID']]);

            //  update customer username and email
            if ($stmt->fetchColumn()) {
                $update = $conn->prepare("UPDATE customers SET username = :username, email = :email WHERE userID = :userID");
                $update->execute([
                    "username" => $user_data['username'],
                    "email"    => $user_data['email'],
                    "userID"   => $user_data['userID']
                ]);
            }
        }

        //Redirect to user management page with success message
        header("Location: manage_users.php?success=User+updated+successfully");
        exit();

    } catch (Exception $e) {
        // Log the error and redirect with an error message
        error_log("❌ Update error: " . $e->getMessage());
        header("Location: manage_users.php?error=" . urlencode($e->getMessage()));
        exit();
    }
} else {
    // Redirect
    header("Location: manage_users.php?error=Missing+required+fields");
    exit();
}
?>
