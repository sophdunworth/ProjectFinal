<?php
// Include the database connection
require_once 'db1.php';
// Include the ContactUsRequest class
require_once 'classes/ContactUsRequest.php';

//  debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Create an instance of the contact handler
$contactHandler = new ContactUsRequest($conn);

// Get the filter value from the URL 
$filter = $_GET['filter'] ?? '';
$requests = [];

// Fetch contact requests based on the filter
// Get only answered
if ($filter === 'answered') {
    $requests = $contactHandler->getRequestsByAnswered(true); 
    // Get only unanswered
} elseif ($filter === 'unanswered') {
    $requests = $contactHandler->getRequestsByAnswered(false); 
} else {
    // Get all
    $requests = $contactHandler->getAllRequests(); 
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Contact Requests</title>
    <link rel="stylesheet" href="css/Admin.css">
    <style>
        /* Styling for filter buttons */
        .badge {
            display: inline-block;
            padding: 6px 12px;
            margin-right: 5px;
            text-decoration: none;
            border-radius: 4px;
            font-size: 14px;
        }
        .bg-secondary { background-color: #ccc; color: #000; }
        .bg-success   { background-color: #28a745; color: #fff; }
        .bg-warning   { background-color: #ffc107; color: #000; }

        /* Styling for the Answer button */
        .Answer {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 4px;
        }
    </style>
</head>
<body>

<!-- Page heading -->
<h2>Contact Us Requests</h2>
<a href="dashboard.php" class="back-link">← Back to Dashboard</a>

<!-- Filter Buttons -->
<div style="margin: 10px 0;">
    <a href="manage_contactusrequest.php" class="badge bg-secondary">Show All</a>
    <a href="manage_contactusrequest.php?filter=unanswered" class="badge bg-warning">Unanswered</a>
    <a href="manage_contactusrequest.php?filter=answered" class="badge bg-success">Answered</a>
</div>

<!-- Manage contact us table -->
<?php if (!empty($requests)): ?>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Email</th>
                <th>Subject</th>
                <th>Message</th>
                <th>Status</th>
                <th>Answered</th>
                <th>Created At</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($requests as $request): ?>
            <tr>
                <!-- Display request details safely using htmlspecialchars -->
                <td><?= htmlspecialchars($request['id']) ?></td>
                <td><?= htmlspecialchars($request['email']) ?></td>
                <td><?= htmlspecialchars($request['subject']) ?></td>
                <td><?= nl2br(htmlspecialchars($request['message'])) ?></td>
                <td><?= htmlspecialchars($request['status']) ?></td>
                <td><?= $request['answered'] ? 'Yes' : 'No' ?></td>
                <td><?= htmlspecialchars($request['created_at']) ?></td>
                <td>
                    <!-- Show answered or not -->
                    <?php if (!$request['answered']): ?>
                        <a href="answer_contactusrequest.php?id=<?= $request['id'] ?>">
                            <button type="button" class="Answer">Answer</button>
                        </a>
                    <?php else: ?>
                        ✅
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <!-- Message if no results -->
    <p>No contact requests found.</p>
<?php endif; ?>

</body>
</html>


