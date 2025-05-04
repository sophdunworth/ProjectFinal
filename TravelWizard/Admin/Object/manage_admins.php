<?php    
// Include Admin class 
require_once 'classes/Admin.php';
//database connection
require_once 'db1.php';

// Create an Admin object to access admin methods
$adminObj = new Admin($conn);

// Set default values
$searchQuery = "";
$admins = [];

// Check if the user searched for something
if (isset($_GET['search']) && !empty($_GET['search'])) {
    // Sanitize the search query
    $searchQuery = htmlspecialchars(trim($_GET['search']));
    
    // Get all admins and filter by name
    $allAdmins = $adminObj->getAllAdmins();

    foreach ($allAdmins as $admin) {
        // Check if the admin name contains the search text 
        if (stripos($admin['adminName'], $searchQuery) !== false) {
            $admins[] = $admin;
        }
    }
} else {
    // If no search, get all admins
    $admins = $adminObj->getAllAdmins();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Admins</title>
    <link rel="stylesheet" href="css/Admin.css">

    <!-- styling -->
    <style>
        .alert {
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid green;
            background-color: #e6ffe6;
            color: green;
        }
    </style>
</head>
<body>

<!-- Page Heading -->
<h2>Manage Admins</h2>

<!-- Navigation links -->
<a href="dashboard.php" class="back-link">Back to Dashboard</a>
<a href="create_admins.php"><button class="create">➕ Create Admin</button></a>

<!-- Search form -->
<form method="GET" action="" class="mb-4" style="max-width: 400px;">
    <div class="input-group mb-3">
        <!-- Search input -->
        <input type="text" name="search" class="form-control" placeholder="Search by name..." value="<?php echo htmlspecialchars($searchQuery); ?>">
        <!-- Submit search -->
        <button type="submit" class="Search">Search</button>
        <!-- Clear search -->
        <a href="manage_admins.php" class="Clear">Clear</a>
    </div>
</form>

<!-- Show feedback messages  -->
<?php if (isset($_GET['status'])): ?>
    <div class="alert"><?php echo htmlspecialchars($_GET['status']); ?></div>
<?php endif; ?>

<!-- Table of admins -->
<table>
    <thead>
        <tr>
            <th>User ID</th>
            <th>Admin Name</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <!-- Check if there are any admins -->
        <?php if (!empty($admins)): ?>
            <!-- Loop through and display each admin -->
            <?php foreach ($admins as $admin): ?>
                <tr>
                    <td><?php echo htmlspecialchars($admin['userID']); ?></td>
                    <td><?php echo htmlspecialchars($admin['adminName']); ?></td>
                    <td>
                        <!-- Form to update admin -->
                        <form method="POST" action="update_admin.php" style="display:inline-block;">
                            <input type="hidden" name="userID" value="<?php echo $admin['userID']; ?>">
                            <input type="text" name="adminname" value="<?php echo htmlspecialchars($admin['adminName']); ?>" required>
                            <input type="password" name="password" placeholder="New Password (optional)">
                            <button type="submit" name="submit" class="Update">Update</button>
                        </form>

                        <!-- Form to delete admin -->
                        <form method="POST" action="delete_admins.php" style="display:inline-block;" onsubmit="return confirm('Delete this admin?');">
                            <input type="hidden" name="userID" value="<?php echo $admin['userID']; ?>">
                            <button type="submit" class="Delete">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <!-- Message if no admins were found -->
            <tr><td colspan="3">No admins found.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

</body>
</html>

