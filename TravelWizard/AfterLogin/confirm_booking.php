<?php
session_start();
require_once '../BeforeLogin/auth.php';
require 'db2.php';

// Custom escape function to sanitize input
function escape($value) {
    return htmlspecialchars(($value), ENT_QUOTES, 'UTF-8');
}

// Shortcut function to get and sanitize POST data, return 'N/A' if not set
$get = fn($key) => isset($_POST[$key]) ? htmlspecialchars($_POST[$key], ENT_QUOTES, 'UTF-8') : 'N/A';

// Check if the form was submitted
if (isset($_POST['final_confirm'])) {
    try {
        // Gather booking data from the form and session
        $booking_data = array(
            "user_id" => $_SESSION['userid'],
            "package_id" => escape($_POST['package_id']),
            "departureFlight" => escape($_POST['departureFlight']),
            "returnFlight" => escape($_POST['returnFlight']),
            "status" => 'pending'
        );
        // Basic validation to ensure all required fields are present
        if (!$booking_data["user_id"] || !$booking_data["package_id"] || !$booking_data["departureFlight"] || !$booking_data["returnFlight"]) {
            echo "Booking details missing.";
            // Stop script if data is missing
            exit();
        }
        
        // Begin transaction to handle booking and payment together
        $pdo->beginTransaction();

        // Prepare SQL to insert booking info into the bookings table
        $booking_sql = sprintf(
            "INSERT INTO %s (%s) VALUES (%s)",
            "bookings",
            implode(", ", array_keys($booking_data)),
            ":" . implode(", :", array_keys($booking_data))
        );
        $stmt = $pdo->prepare($booking_sql);
        $stmt->execute($booking_data);
        
        // Get the ID of the newly inserted booking
        $bookingID = $pdo->lastInsertId();

        // Get the price of the selected package from the database
        $package_stmt = $pdo->prepare("SELECT price FROM packages WHERE packageid = :package_id");
        $package_stmt->execute(["package_id" => $booking_data["package_id"]]);
        $package = $package_stmt->fetch();

        // If no package was found, throw an error
        if (!$package) {
            throw new Exception("Package not found.");
        }

        $price = (float)$package['price'];
        $payment_type = $_POST['payment_type'] ?? 'full';
        // Prepare payment data based on selected payment type
        $payment_data = array(
            "bookingID" => $bookingID,
            "amountPaid" => $payment_type === 'full' ? $price : 0.00,
            "amountPending" => $payment_type === 'full' ? 0.00 : $price,
            "payment_status" => 'pending'
        );
        
        // Insert payment info into the payments table
        $payment_sql = sprintf(
            "INSERT INTO %s (%s) VALUES (%s)",
            "payments",
            implode(", ", array_keys($payment_data)),
            ":" . implode(", :", array_keys($payment_data))
        );
        $pay_stmt = $pdo->prepare($payment_sql);
        $pay_stmt->execute($payment_data);

        // Commit the transaction if both inserts succeeded
        $pdo->commit();
        // Redirect user to a success page
        header("Location: booking_success.php");
        exit();
    } catch (Exception $e) {
        // Roll back transaction if anything fails
        $pdo->rollBack();
        echo "Booking failed: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Confirm Your Booking</title>
    <link rel="stylesheet" href="css/confirm.css">
</head>
<body>

<main>
    <h1>Confirm Your Booking</h1>

    <!-- Trip Details -->
    <section>
        <h2>Trip Details</h2>
        <p><strong>Departure Date:</strong> <?= $get('departureFlight') ?></p>
        <p><strong>Return Date:</strong> <?= $get('returnFlight') ?></p>
    </section>

    <!-- Personal Information -->
    <section>
        <h2>Personal Details</h2>
        <p><strong>First Name:</strong> <?= $get('first_name') ?></p>
        <p><strong>Surname:</strong> <?= $get('surname') ?></p>
        <p><strong>Date of Birth:</strong> <?= $get('dob') ?></p>
        <p><strong>Address:</strong>
            <?= $get('house_number') ?>,
            <?= $get('road_name') ?>,
            <?= $get('town') ?>,
            <?= $get('county') ?>,
            <?= $get('country') ?>,
            <?= $get('eircode') ?>
        </p>
    </section>

    <!-- Payment Information -->
    <section>
        <h2>Payment Details</h2>
        <p><strong>Payment Type:</strong> <?= $get('payment_type') ?></p>
        <p><strong>Card Type:</strong> <?= $get('card_type') ?></p>
        <p><strong>Cardholder Name:</strong> <?= $get('cardholder_name') ?></p>
        <p><strong>Card Number:</strong> **** **** **** <?= substr($get('card_number'), -4) ?></p>
    </section>

    <!-- Passport Information -->
    <section>
        <h2>Passport Information</h2>
        <p><strong>Passport Name:</strong> <?= $get('passport_first_name') ?> <?= $get('passport_second_name') ?></p>
        <p><strong>Passport Number:</strong> <?= $get('passport_number') ?></p>
        <p><strong>Expiry Date:</strong> <?= $get('passport_expiry') ?></p>
        <p><strong>Country of Issue:</strong> <?= $get('passport_country') ?></p>
    </section>

    <!-- Health and Safety -->
    <section>
        <h2>Health and Safety</h2>
        <p><strong>Emergency Contact:</strong> <?= $get('emergency_name') ?> (<?= $get('emergency_phone') ?>)</p>
        <p><strong>Emergency Address:</strong> <?= $get('emergency_address') ?></p>
        <p><strong>Allergies:</strong> <?= $get('allergies') ?: 'None' ?></p>
    </section>

    <!-- Confirm + Edit Buttons -->
    <form method="POST">
        <?php foreach ($_POST as $key => $value): ?>
            <input type="hidden" name="<?= htmlspecialchars($key) ?>" value="<?= htmlspecialchars($value) ?>">
        <?php endforeach; ?>

        <button type="submit" name="final_confirm">Confirm Booking</button>
        <button type="button" onclick="window.history.back();">Edit</button>
    </form>
</main>

</body>
</html>

