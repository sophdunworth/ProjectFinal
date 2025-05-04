<?php 
// Include the User class
require_once 'classes/User.php';

// debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if database connection is valid
if (!isset($conn) || !($conn instanceof PDO)) {
    die("Database connection error.");
}

// Get the search query from the URL 
$searchQuery = !empty($_GET['search']) ? htmlspecialchars($_GET['search']) : '';

try {
    // Prepare base SQL query to fetch all users
    $sql = "SELECT * FROM users";
    $params = [];

    // filter users by email
    if (!empty($searchQuery)) {
        $sql .= " WHERE email LIKE :searchQuery";
        $params[':searchQuery'] = "%" . $searchQuery . "%";
    }

    // Execute the SQL query
    $statement = $conn->prepare($sql);
    $statement->execute($params);

    // Fetch all matching users from the database
    $users = $statement->fetchAll(PDO::FETCH_ASSOC);

    // Set success message depending on result
    if ($users) {
        $successMessage = 'Users fetched successfully!';
    } else {
        $successMessage = 'No users found matching the search criteria.';
    }
} catch (PDOException $error) {
    // Display error if query fails
    echo "Error: " . $error->getMessage();
}
?>

<!--  HTML SECTION  -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Users</title>
    
    <link rel="stylesheet" href="css/Admin.css">
</head>
<body>

<h2>Manage Users</h2>

<!-- Link back to dashboard -->
<a href="dashboard.php" class="back-link"> Return to Dashboard</a>

<!-- Display feedback messages  -->
<?php if (isset($_GET['success']) && $_GET['success'] === 'deleted'): ?>
    <p class="message" style="color: green;">✅ User deleted successfully.</p>
<?php elseif (isset($_GET['success']) && $_GET['success'] === 'updated'): ?>
    <p class="message" style="color: green;">✅ User updated successfully.</p>
<?php elseif (isset($_GET['error'])): ?>
    <p class="message" style="color: red;">❌ An error occurred.</p>
<?php endif; ?>

<!-- Search bar for filtering users by email -->
<form method="GET" action="" class="mb-4" style="margin-top: 20px; max-width: 400px;">
    <div class="input-group mb-3">
        <input type="text" class="form-control" name="search" placeholder="Search by email..." value="<?= htmlspecialchars($searchQuery); ?>">
        <button type="submit" class="Search">Search</button>
        <a href="manage_users.php" class="Clear">Clear</a>
    </div>
</form>

<!-- Table displaying users -->
<table>
    <thead>
        <tr>
            <th>User ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>User Type</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
    <?php if (!empty($users)): ?>
        <?php foreach ($users as $u): ?>
            <tr>
                <td><?= htmlspecialchars($u['userID']); ?></td>
                <td><?= htmlspecialchars($u['username']); ?></td>
                <td><?= htmlspecialchars($u['email']); ?></td>
                <td><?= htmlspecialchars($u['user_type']); ?></td>
                <td>
                    <!-- Form to update user -->
                    <form method="POST" action="update_user.php" style="display:inline-block;">
                        <input type="hidden" name="userID" value="<?= $u['userID']; ?>">
                        <input type="text" name="username" value="<?= htmlspecialchars($u['username']); ?>" required>
                        <input type="email" name="email" value="<?= htmlspecialchars($u['email']); ?>" required>
                        <select name="user_type" required>
                            <option value="customer" <?= $u['user_type'] === 'customer' ? 'selected' : '' ?>>Customer</option>
                            <option value="admin" <?= $u['user_type'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                        </select>
                        <button type="submit" name="update_user" class="Update">Update</button>
                    </form>

                    <!-- Form to delete user -->
                    <form method="POST" action="delete_user.php" style="display:inline-block;" onsubmit="return confirm('Are you sure you want to delete this user?');">
                        <input type="hidden" name="userID" value="<?= $u['userID']; ?>">
                        <button type="submit" class="Delete">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <!-- Message if no users found -->
        <tr><td colspan="5">No users found.</td></tr>
    <?php endif; ?>
    </tbody>
</table>

</body>
</html>
