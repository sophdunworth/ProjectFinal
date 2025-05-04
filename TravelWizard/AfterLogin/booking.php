<?php
// Start session if not already started
session_start();

// Protect the page to allow access only to logged-in users
require_once '../BeforeLogin/auth.php'; 
require_once 'db2.php'; 

?>

<!DOCTYPE html>
<html lang="en">
    <head>
          <meta charset="UTF-8">
          <meta name="viewport" content="width=device-width, initial-scale=1.0">
          <title>Booking Page</title>
      <link rel="stylesheet" href="css/Booking.css">
    </head>
<body>

<main>

<h1>Booking Form</h1>

<?php
    // Capture trip details sent from the trip page 
    $package_id = $_POST['package_id'] ?? ''; 
    $departureFlight = $_POST['departureFlight'] ?? ''; 
    $returnFlight = $_POST['returnFlight'] ?? ''; 
?>

<form action="confirm_booking.php" method="POST">

  <!-- Hidden inputs to carry trip details to the next page -->
  <input type="hidden" name="package_id" value="<?= htmlspecialchars($package_id) ?>">
  <input type="hidden" name="departureFlight" value="<?= htmlspecialchars($departureFlight) ?>">
  <input type="hidden" name="returnFlight" value="<?= htmlspecialchars($returnFlight) ?>">

 
  <section>
    <h2>Personal Details</h2>
    <?php
    // Array for personal details fields
        $fields = [
              "First Name" => "first_name",
              "Surname" => "surname",
              "Date of Birth (DD/MM/YYYY)" => "dob",
              "House Number" => "house_number",
              "Road Name" => "road_name",
              "Town" => "town",
              "County" => "county",
              "Country" => "country",
              "EIRCODE" => "eircode"
        ];
    // Loop through fields and generate form labels and inputs
    foreach ($fields as $label => $name) {
        echo "<label>$label: <input type='text' name='$name' required></label><br>";
    }
    ?>
  </section>

  <!-- Payment Details Section -->
  <section>
    <h2>Payment Details</h2>
    <!-- Payment type selection: Full payment or installments -->
        <label><input type="radio" name="payment_type" value="full" required> Full Payment</label>
        <label><input type="radio" name="payment_type" value="installments"> Installments (3 months)</label><br>

    <!-- Card type selection: Credit card or debit card -->
    <label><input type="radio" name="card_type" value="credit" required> Credit Card</label>
    <label><input type="radio" name="card_type" value="debit"> Debit Card</label><br>

    <?php
    // Array for payment details fields
        $payment_fields = [
          "Cardholder Name" => "cardholder_name",
          "Card Number (16 digits)" => "card_number",
          "Expiry Date (MM/YY)" => "expiry_date",
          "CVC (3 digits)" => "cvc"
        ];
    // Loop through fields and generate form labels and inputs
    foreach ($payment_fields as $label => $name) {
        echo "<label>$label: <input type='text' name='$name' required></label><br>";
    }
    ?>
  </section>

  <!-- Passport Information Section -->
  <section>
    <h2>Passport Information</h2>
    <?php
    // Array for passport details fields
        $passport_fields = [
              "First Name (Passport)" => "passport_first_name",
              "Second Name (Passport)" => "passport_second_name",
              "Passport Number" => "passport_number",
              "Expiry Date (DD/MM/YYYY)" => "passport_expiry"
        ];
    // Loop through fields and generate form labels and inputs
    foreach ($passport_fields as $label => $name) {
        echo "<label>$label: <input type='text' name='$name' required></label><br>";
    }
    ?>
    <!-- Country of issue dropdown -->
        <label>Country of Issue:</label>
            <select name="passport_country" required>
                  <option value="">Select Country</option>
                  <?php
                  // List of countries to choose from
                  $countries = [
                    "United Kingdom", "United States", "Canada", "Australia", "Ireland",
                    "France", "Germany", "Italy", "Spain", "Netherlands", "Sweden",
                    "Switzerland", "Japan", "China", "India", "South Africa"
                  ];
                  // Loop through countries and create <option> elements
                  foreach ($countries as $country) {
                      echo "<option value='$country'>$country</option>";
              }
          ?>
    </select>
  </section>

  <!-- Health and Safety Section -->
      <section>
            <h2>Health and Safety</h2>
                <!-- Emergency contact fields -->
                <label>Emergency Contact Name: <input type="text" name="emergency_name" required></label><br>
                <label>Emergency Phone: <input type="text" name="emergency_phone" required></label><br>
                <label>Emergency Address: <input type="text" name="emergency_address" required></label><br>
                <!-- Allergies checkbox to show additional input if checked -->
                <label><input type="checkbox" id="allergies_check"> Allergies</label>
            <input type="text" id="allergies_input" name="allergies" style="display:none;" placeholder="Specify allergies">
          </section>

      <!-- Submit and Cancel Buttons -->
      <button type="submit">Proceed to Confirmation</button>
      <button type="button" onclick="history.back();">Cancel</button>

</form>

</main>

<!-- Client-side JavaScript validation -->
<script>
// Function to show error messages
function showError(id, message) {
  document.getElementById(id).textContent = message;
}

// Function to clear error messages
function clearError(id) {
  document.getElementById(id).textContent = "";
}

// Function to validate text fields based on pattern
function validateTextField(inputId, errorId, pattern, message) {
  const input = document.getElementById(inputId);
  input.addEventListener("input", function () {
    if (!pattern.test(this.value)) {
      showError(errorId, message);
    } else {
      clearError(errorId);
    }
  });
}

// Validation rules for different input fields
validateTextField("first_name", "first_name_error", /^[^\d]+$/, "Invalid format: no numbers allowed!");
validateTextField("surname", "surname_error", /^[^\d]+$/, "Invalid format: no numbers allowed!");
validateTextField("dob", "dob_error", /^\d{2}\/\d{2}\/\d{4}$/, "Invalid date format: use DD/MM/YYYY");
validateTextField("cardholder_name", "cardholder_name_error", /^[^\d]+$/, "Invalid format: no numbers allowed!");
validateTextField("card_number", "card_number_error", /^\d{16}$/, "Card number must be exactly 16 digits");
validateTextField("expiry_date", "expiry_date_error", /^\d{2}\/\d{2}$/, "Invalid expiry format: use MM/YY");
validateTextField("cvc", "cvc_error", /^\d{3}$/, "CVC must be exactly 3 digits");
validateTextField("passport_first_name", "passport_first_name_error", /^[^\d]+$/, "Invalid format: no numbers allowed!");
validateTextField("passport_second_name", "passport_second_name_error", /^[^\d]+$/, "Invalid format: no numbers allowed!");
validateTextField("passport_number", "passport_number_error", /^\d+$/, "Invalid format: only digits allowed!");
validateTextField("passport_expiry", "passport_expiry_error", /^\d{2}\/\d{2}\/\d{4}$/, "Invalid date format: use DD/MM/YYYY");
validateTextField("emergency_name", "emergency_name_error", /^[^\d]+$/, "Invalid format: no numbers allowed!");
validateTextField("emergency_phone", "emergency_phone_error", /^\d+$/, "Invalid format: only numbers allowed!");

// Toggle allergies input visibility based on checkbox
document.getElementById('allergies_check').addEventListener('change', function() {
  document.getElementById('allergies_input').style.display = this.checked ? 'block' : 'none';
});
</script>

</body>
</html>


