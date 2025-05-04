<?php 
//start session
session_start();
//DB connection
require_once 'db1.php'; 
//ContactUsRequest Class
require_once 'classes/ContactUsRequest.php';

//Instantiate with PDO connection to call methods
$contactHandler = new ContactUsRequest($conn); 

// Check if a valid request ID is passed
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid request ID.");
}

$requestID = intval($_GET['id']);

// Fetch the contact request
$request = $contactHandler->getRequestByID($requestID);

if (!$request) {
    die("Contact request not found.");
}
//https://www.w3schools.com/Php/php_superglobals_server.asp
// Handle form submission when the admin submits a response
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Get the logged in admin user ID from the session
    $adminID = $_SESSION['userID']; 

    // Sanitize the response input
    $response = trim($_POST['response']);

    // Check if response is empty
    if (empty($response)) {
        $error = "Response cannot be empty.";
    } else {
        // Attempt to save the response in the database
        if ($contactHandler->submitResponse($requestID, $adminID, $response)) {
            // show alert and redirect to manage requests page
            echo "<script>alert('✅ Response submitted successfully!'); window.location.href='manage_contactusrequest.php';</script>";
            exit;
        } else {
            // set error message
            $error = "❌ Failed to submit the response.";
        }
    }
}

?>

<!DOCTYPE html> 
<html lang="en">
<head>
    
    <meta charset="UTF-8">
    <title>Answer Contact Request</title>
    <link rel="stylesheet" href="css/Admin.css">
    
</head>
<body>


<h2>Respond to Contact Request</h2>

<div class="container">
    <!-- Display the contact request's details -->
    <p><strong>Email:</strong> <?= htmlspecialchars($request['email']) ?></p>
    <p><strong>Subject:</strong> <?= htmlspecialchars($request['subject']) ?></p>
    <p><strong>Message:</strong> <?= nl2br(htmlspecialchars($request['message'])) ?></p>

    <!-- Show an error message if one exists  -->
    <?php if (!empty($error)): ?>
        <p class="error"><?= $error ?></p>
    <?php endif; ?>

    <!-- Response form for the admin to reply to the message -->
    <form method="POST">
        <!-- Textarea for entering the response message -->
        <label for="response">Your Response:</label><br>
        <textarea name="response" required></textarea><br>

        <!-- Submit button -->
        <button type="submit" class="Answer">Submit Response</button>
    </form>

    <!-- Link to go back to the manage requests page -->
    <div class="back-link">
        <a href="manage_contactusrequest.php" class="back-link">← Back to Manage Requests</a>
    </div>
</div>

</body>
</html>

