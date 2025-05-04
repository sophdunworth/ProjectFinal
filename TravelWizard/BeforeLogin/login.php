<?php 
ob_start(); // Start output buffering

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'db.php'; // Database connection

$error = ""; // Initialize error variable

// Check if required POST fields are set
if (isset($_POST['email'], $_POST['password'])) {
    // Sanitize and normalize input
    $email = htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8');
    $password = htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8');

    try {
        // Prepare and execute SQL query
        $stmt = $pdo->prepare("SELECT userID, password FROM Users WHERE email = :email");
        $stmt->execute([':email' => $email]);

        // Fetch user data
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verify the password 
        if ($user && password_verify($password, $user['password'])) {
            // Set session data
            $_SESSION['userid'] = $user['userID'];
            $_SESSION['email'] = $email;

            //Redirect
            $redirectPage = $_SESSION['redirect_after_login'] ?? '../AfterLogin/index.php';
            unset($_SESSION['redirect_after_login']);

            header("Location: " . $redirectPage);
            exit();
        } else {
            $error = "Invalid email or password.";
        }
    } catch (PDOException $e) {
        $error = "Database error: " . htmlspecialchars($e->getMessage());
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Travel Wizard</title>
    <link rel="stylesheet" href="css/Login.css"> 
</head>
<body>
<?php include 'templates/header1.php'; ?> 

<div class="login-container">
    <h2>User Login</h2>

    <!-- Display error message if login fails -->
    <?php if (isset($error)): ?>
        <p style="color:red;"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <!-- Login form -->
    <form action="login.php" method="POST">
        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" required><br><br>

        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" required><br><br>

        <input type="submit" value="Login">
    </form>

    <p>
        Don't have an account?
        <a href="register.php"><button type="button">Create an Account</button></a>
    </p>
</div>
<?php include 'templates/footer.php'; ?> 

</body>
</html>

