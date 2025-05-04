<?php 
// Include the database connection
require_once 'db1.php';
// Include the Customer class
require_once 'classes/Customer.php';

// Get the search input from the URL
$searchQuery = $_GET['search'] ?? ''; 

// Create a new instance of the Customer class
$customerObj = new Customer($conn);

// Fetch customers based on search input
$customers = !empty($searchQuery)
    ? $customerObj->searchCustomersByEmail($searchQuery)
    : $customerObj->getAllCustomers();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Customers</title>
    <!-- Link to external CSS file -->
    <link rel="stylesheet" href="css/Admin.css">
    <style>
        /* Styling for the Clear button */
        .Clear {
            display: inline-block;
            padding: 6px 12px;
            background-color: #ddd;
            color: #000;
            text-decoration: none;
            border-radius: 4px;
            margin-left: 5px;
            font-size: 14px;
        }
        .Clear:hover {
            background-color: #bbb;
        }
    </style>
</head>
<body>

<!-- Page heading -->
<h2>Manage Customers</h2>

<!-- Link to go back to the dashboard -->
<a href="dashboard.php" class="back-link">Back to Dashboard</a>
<!--https://www.w3schools.com/charsets/ref_emoji.asp -->
<!-- Display success or error messages -->
<?php if (isset($_GET['success']) && $_GET['success'] === 'deleted'): ?>
    <p style="color: green;">✅ Customer deleted successfully!</p>
<?php elseif (isset($_GET['success']) && $_GET['success'] === 'updated'): ?>
    <!--https://www.w3schools.com/charsets/ref_utf_dingbats.asp -->
    <p style="color: green;">✅ Customer updated successfully!</p>
<?php elseif (isset($_GET['error'])): ?>
    <p style="color: red;">❌ An error occurred.</p>
<?php endif; ?>

<!-- Search form -->
<form method="GET" action="" class="mb-4" style="max-width: 400px; margin-top: 20px;">
    <div class="input-group mb-3">
        <!-- Input box for search -->
        <input type="text" name="search" class="form-control" placeholder="Search by email..." value="<?= htmlspecialchars($searchQuery); ?>">
        <!-- Search button -->
        <button type="submit" class="Search">Search</button>
        <!-- Clear button to reset the search -->
        <a href="manage_customers.php" class="Clear">Clear</a>
    </div>
</form>

<!-- Customer list table -->
<table>
    <thead>
        <tr>
            <th>User ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
    <?php if (!empty($customers)): ?>
        <?php foreach ($customers as $cust): ?>
            <tr>
                <!-- Form to update customer info -->
                <form method="POST" action="update_customer.php">
                    <td>
                        <!-- Display and send user ID -->
                        <?= htmlspecialchars($cust['userID']); ?>
                        <input type="hidden" name="userID" value="<?= $cust['userID']; ?>">
                    </td>
                    <td>
                        <!-- Editable username field -->
                        <input type="text" name="username" value="<?= htmlspecialchars($cust['username']); ?>" required>
                    </td>
                    <td>
                        <!-- Editable email field -->
                        <input type="email" name="email" value="<?= htmlspecialchars($cust['email']); ?>" required>
                    </td>
                    <td>
                        <!-- Submit update button -->
                        <button type="submit" name="submit" class="Update">Update</button>
                </form>

                        <!-- Form to delete the customer -->
                        <form method="POST" action="delete_customer.php" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this customer?');">
                            <input type="hidden" name="userID" value="<?= $cust['userID']; ?>">
                            <button type="submit" name="submit" class="Delete">Delete</button>
                        </form>
                    </td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <!-- Message shown if no results -->
        <tr><td colspan="4">No customers found.</td></tr>
    <?php endif; ?>
    </tbody>
</table>

</body>
</html>

