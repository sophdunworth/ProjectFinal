<?php
// Include database connection 
require_once 'db1.php';
//Include the Payment Class
require_once 'classes/Payment.php';

// debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Get search and filter values 
$searchBookingID = $_GET['search'] ?? '';
$filter = $_GET['filter'] ?? '';
$editPayment = null; 

try {
    // Default SQL query to get all payments
    $query = "SELECT * FROM payments";
    $params = [];

    // Filter by booking ID if a search value is entered
    if (!empty($searchBookingID)) {
        $query .= " WHERE bookingID = :searchBookingID";
        $params[':searchBookingID'] = $searchBookingID;
    }
    // Filter for payments fully paid
    elseif ($filter === 'full') {
        $query .= " WHERE amountPending <= 0";
    }
    // Filter for payments still with pending amounts
    elseif ($filter === 'installments') {
        $query .= " WHERE amountPending > 0";
    }

    // Prepare and run the query
    $stmt = $conn->prepare($query);
    $stmt->execute($params);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC); 

    // Check what booking is being edited
    if (isset($_GET['edit'])) {
        $editID = intval($_GET['edit']);
        $editStmt = $conn->prepare("SELECT * FROM payments WHERE bookingID = :editID");
        $editStmt->execute([':editID' => $editID]);
        $editPayment = $editStmt->fetch(PDO::FETCH_ASSOC); 
    }
} catch (PDOException $e) {
    // Display error 
    die("❌ Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Payments</title>
    <link rel="stylesheet" href="css/Admin.css">
</head>
<body>

<h2>Manage Payments</h2>
<a href="dashboard.php" class="back-link"> Return to Dashboard</a><br><br>

<!-- Search by booking ID -->
<form method="GET" style="max-width: 400px;">
    <div class="input-group mb-3">
        <input type="text" name="search" placeholder="Search by Booking ID"
               value="<?= htmlspecialchars($searchBookingID) ?>" class="form-control">
        <button type="submit" class="Search">Search</button>
        <a href="manage_payments.php" class="Clear">Clear</a>
    </div>
</form>

<!-- Filter Buttons -->
<div style="margin-top: 10px;">
    <a href="manage_payments.php" class="Search">Show All</a>
    <a href="manage_payments.php?filter=full" class="badge bg-success">Paid in Full</a>
    <a href="manage_payments.php?filter=installments" class="badge bg-warning">Installments</a>
</div>

<!-- Edit form  -->
<?php if ($editPayment): ?>
    <hr>
    <h3>Edit Payment</h3>
    <form method="POST" action="update_payment.php" style="margin-bottom: 30px;">
        <!-- Hidden field to store booking ID -->
        <input type="hidden" name="bookingID" value="<?= htmlspecialchars($editPayment['bookingID']) ?>">

        <!-- Fields to update payment data -->
        <input type="number" step="0.01" name="amountPaid"
               value="<?= htmlspecialchars($editPayment['amountPaid']) ?>" placeholder="Amount Paid" required><br><br>

        <input type="number" step="0.01" name="amountPending"
               value="<?= htmlspecialchars($editPayment['amountPending']) ?>" placeholder="Amount Pending" required><br><br>

        <select name="payment_status" required>
            <option value="pending" <?= $editPayment['payment_status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
            <option value="completed" <?= $editPayment['payment_status'] === 'completed' ? 'selected' : '' ?>>Completed</option>
        </select><br><br>

        <!-- Notes field -->
        <textarea name="notes" placeholder="Enter notes (optional)" rows="3"
                  style="width:100%;"><?= htmlspecialchars($editPayment['notes'] ?? '') ?></textarea><br><br>

        <button type="submit" name="update_payment" class="Update">Update Payment</button>
    </form>
<?php endif; ?>

<!-- Display list of all payments in a table -->
<table>
    <thead>
    <tr>
        <th>Booking ID</th>
        <th>Amount Paid</th>
        <th>Amount Pending</th>
        <th>Status</th>
        <th>Transaction Date</th>
        <th>Completion</th>
        <th>Notes</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    <?php if (!empty($result)): ?>
        <?php foreach ($result as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['bookingID']) ?></td>
                <td><?= htmlspecialchars($row['amountPaid']) ?></td>
                <td><?= htmlspecialchars($row['amountPending']) ?></td>
                <td><?= htmlspecialchars($row['payment_status']) ?></td>
                <td><?= htmlspecialchars($row['transactionDate']) ?></td>
                <td>
                    <!-- Show badge depending on payment type -->
                    <?php if (floatval($row['amountPending']) <= 0): ?>
                        <span class="badge bg-success">Paid in Full</span>
                    <?php else: ?>
                        <span class="badge bg-warning">Installments</span>
                    <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($row['notes'] ?? '') ?></td>
                <!-- Edit link -->
                <td><a href="manage_payments.php?edit=<?= $row['bookingID'] ?>" class="Edit">Edit</a></td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr><td colspan="8">No payments found.</td></tr>
    <?php endif; ?>
    </tbody>
</table>

</body>
</html>

