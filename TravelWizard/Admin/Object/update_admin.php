<?php  
//Include the Admin class
require_once 'classes/Admin.php';
//Include the database connection
require_once 'db1.php';
//debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Escape helper
function escape($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

// Process form submission
if (isset($_POST['userID'], $_POST['adminname'])) {
    try {
        $adminData = array(
            "userID" => escape($_POST['userID']),
            "adminname" => escape($_POST['adminname']),
            "password" => !empty($_POST['password']) ? escape($_POST['password']) : null
        );

        $adminObj = new Admin($conn);

        // Begin a transaction to ensure both tables are updated together
        $conn->beginTransaction();

        // Update admin name in admins table
        $sqlAdmins = "UPDATE admins SET adminname = :adminname WHERE userID = :userID";
        $stmtAdmins = $conn->prepare($sqlAdmins);
        $stmtAdmins->execute([
            ':adminname' => $adminData['adminname'],
            ':userID' => $adminData['userID']
        ]);

        // Update username in users table
        $sqlUsers = "UPDATE users SET username = :username WHERE userID = :userID";
        $stmtUsers = $conn->prepare($sqlUsers);
        $stmtUsers->execute([
            ':username' => $adminData['adminname'],
            ':userID' => $adminData['userID']
        ]);

        // If a password was provided, update it in users table as well
        if (!empty($adminData['password'])) {
            // Hash the password 
            $hashedPassword = password_hash($adminData['password'], PASSWORD_DEFAULT);

            $stmtPassword = $conn->prepare("UPDATE users SET password = :password WHERE userID = :userID");
            $stmtPassword->execute([
                ':password' => $hashedPassword,
                ':userID' => $adminData['userID']
            ]);
        }

        // Commit all changes
        $conn->commit();

        // Redirect to admin management
        header("Location: manage_admins.php?status=Admin+updated+successfully");
        exit;

    } catch (PDOException $error) {
        $conn->rollBack();
        $encodedError = urlencode("Error: " . $error->getMessage());
        //redirect with error
        header("Location: manage_admins.php?status=$encodedError");
        exit;
    }
} else {
    // redirect with error
    header("Location: manage_admins.php?status=Invalid+form+data");
    exit;
}
?>
