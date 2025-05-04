<?php   
// Include the Booking class 
require_once 'classes/Booking.php'; 

//  database connection
require_once 'db1.php';             

// Function to sanitize input 
function escape($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

// Handle booking status update  
if (isset($_POST['update_booking'])) {
    try {
        // Sanitize form data
        $bookingData = array(
            "bookingID" => escape($_POST['bookingID']),
            "status" => escape($_POST['status'])
        );

        // Create Booking object
        $booking = new Booking($conn);

        // Update the booking status using the object method
        if ($booking->updateBookingStatus($bookingData['bookingID'], $bookingData['status'])) {
            echo "<script>alert('Booking status updated successfully!'); window.location.href='manage_bookings.php';</script>";
        } else {
            echo "<script>alert('Error updating booking status.'); window.location.href='manage_bookings.php';</script>";
        }
    } catch (PDOException $e) {
        // Handle any DB errors
        echo "<script>alert('Database error: " . htmlspecialchars($e->getMessage()) . "'); window.location.href='manage_bookings.php';</script>";
    }
}

//  Search for bookings by booking ID 
try {
    $searchBookingID = isset($_GET['search']) ? escape($_GET['search']) : '';

    //  fetch matching booking in search
    if (!empty($searchBookingID)) {
        $sql = "SELECT * FROM bookings WHERE bookingID = :bookingID";
        $stmt = $conn->prepare($sql);
        $stmt->execute(['bookingID' => $searchBookingID]);
    } else {
        $sql = "SELECT * FROM bookings";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
    }

    // Store all results
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "<script>alert('Database retrieval error: " . htmlspecialchars($e->getMessage()) . "');</script>";
}

//  Load booking data for edit
$editBooking = null;
if (isset($_GET['edit'])) {
    try {
        $editID = intval($_GET['edit']);
        $editStmt = $conn->prepare("SELECT * FROM bookings WHERE bookingID = :bookingID");
        $editStmt->execute(['bookingID' => $editID]);
        $editBooking = $editStmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "<script>alert('Error fetching booking: " . htmlspecialchars($e->getMessage()) . "');</script>";
    }
}
?>

<!--  HTML STARTS HERE  -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Bookings</title>
    <link rel="stylesheet" href="css/Admin.css"> 
</head>
<body>

    <!-- Page title and navigation -->
    <h2 class="mb-4">Manage Bookings</h2>
    <a href="dashboard.php" class="back-link"> Return to Dashboard</a>
    <br><br>

    <!--  Search Form -->
    <form action="manage_bookings.php" method="GET" class="mb-4">
        <div class="input-group mb-3" style="max-width: 400px;">
            <input type="text" class="form-control" name="search" placeholder="Search by Booking ID" value="<?php echo htmlspecialchars($searchBookingID); ?>">
            <button class="Search" type="submit">Search</button>
            <a href="manage_bookings.php" class="Clear">Clear</a>
        </div>
    </form>

    <!--  Edit Booking Form  -->
    <?php if ($editBooking) { ?>
        <h4>Edit Booking Status</h4>
        <form action="manage_bookings.php" method="POST" class="mb-4">
            <!-- Hidden field to hold booking ID -->
            <input type="hidden" name="bookingID" value="<?php echo htmlspecialchars($editBooking['bookingID']); ?>">

            <!-- Dropdown to choose status -->
            <select name="status" required>
                <option value="pending" <?php if ($editBooking['status'] == 'pending') echo 'selected'; ?>>Pending</option>
                <option value="completed" <?php if ($editBooking['status'] == 'completed') echo 'selected'; ?>>Completed</option>
            </select><br><br>

            <!-- Submit button to save changes -->
            <button type="submit" name="update_booking" class="Update">Update Status</button>
        </form>
    <?php } ?>

    <!--  Booking Table -->
    <table>
        <thead>
            <tr>
                <th>Booking ID</th>
                <th>Customer Name</th>
                <th>Package ID</th>
                <th>Date Booked</th>
                <th>Status</th>
                <th>Departure Flight</th>
                <th>Return Flight</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($result as $row) { ?>
                <tr id="row-<?php echo $row['bookingID']; ?>">
                    <td><?php echo htmlspecialchars($row['bookingID']); ?></td>
                    <td>
                        <?php
                        // Look up the customer name using the user ID
                        if (isset($row['user_id'])) {
                            $userID = intval($row['user_id']);
                            $userStmt = $conn->prepare("SELECT username FROM users WHERE userID = ?");
                            $userStmt->execute([$userID]);
                            $userRow = $userStmt->fetch(PDO::FETCH_ASSOC);
                            echo $userRow ? htmlspecialchars($userRow['username']) : 'Unknown User';
                        } else {
                            echo 'Unknown User';
                        }
                        ?>
                    </td>
                    <td><?php echo htmlspecialchars($row['package_id'] ?? ''); ?></td>
                    <td><?php echo htmlspecialchars($row['dateBooked'] ?? ''); ?></td>
                    <td><?php echo htmlspecialchars($row['status'] ?? ''); ?></td>
                    <td><?php echo htmlspecialchars($row['departureFlight'] ?? ''); ?></td>
                    <td><?php echo htmlspecialchars($row['returnFlight'] ?? ''); ?></td>
                    <td>
                        <!-- Button to edit this booking -->
                        <a href="manage_bookings.php?edit=<?php echo $row['bookingID']; ?>" class="Edit">Edit</a>

                        <!-- Form to delete this booking -->
                        <form method="POST" action="delete_booking.php" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this booking?');">
                            <input type="hidden" name="bookingID" value="<?php echo $row['bookingID']; ?>">
                            <button type="submit" class="Delete">Delete</button>
                        </form>
                    </td>
                </tr>
        <?php } ?>
        </tbody>
    </table>

</body>
</html>

