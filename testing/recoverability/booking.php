<?php

session_start();
require_once '../BeforeLogin/auth.php';
require_once 'db2.php';

function getValue($name) {
  return htmlspecialchars($_POST[$name] ?? '', ENT_QUOTES);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Booking Page</title>
  <link rel="stylesheet" href="css/Booking.css">
  <style>
    .error-message {
      color: red;
      font-size: 0.9em;
    }
  </style>
</head>
<body>

<main>
<h1>Booking Form</h1>

<?php
$package_id = $_POST['package_id'] ?? '';
$departureFlight = $_POST['departureFlight'] ?? '';
$returnFlight = $_POST['returnFlight'] ?? '';
?>

<form action="confirm_booking.php" method="POST" id="bookingForm">
  <input type="hidden" name="package_id" value="<?= htmlspecialchars($package_id) ?>">
  <input type="hidden" name="departureFlight" value="<?= htmlspecialchars($departureFlight) ?>">
  <input type="hidden" name="returnFlight" value="<?= htmlspecialchars($returnFlight) ?>">

  <section>
    <h2>Personal Details</h2>
    <label>First Name:
      <input type="text" name="first_name" id="first_name" value="<?= getValue('first_name') ?>" required>
      <span class="error-message" id="first_name_error"></span>
    </label><br>

    <label>Surname:
      <input type="text" name="surname" id="surname" value="<?= getValue('surname') ?>" required>
      <span class="error-message" id="surname_error"></span>
    </label><br>

    <label>Date of Birth (DD/MM/YYYY):
      <input type="text" name="dob" id="dob" value="<?= getValue('dob') ?>" required>
      <span class="error-message" id="dob_error"></span>
    </label><br>

    <label>House Number:
      <input type="text" name="house_number" value="<?= getValue('house_number') ?>" required>
    </label><br>

    <label>Road Name:
      <input type="text" name="road_name" value="<?= getValue('road_name') ?>" required>
    </label><br>

    <label>Town:
      <input type="text" name="town" value="<?= getValue('town') ?>" required>
    </label><br>

    <label>County:
      <input type="text" name="county" value="<?= getValue('county') ?>" required>
    </label><br>

    <label>Country:
      <input type="text" name="country" value="<?= getValue('country') ?>" required>
    </label><br>

    <label>EIRCODE:
      <input type="text" name="eircode" value="<?= getValue('eircode') ?>" required>
    </label><br>
  </section>

  <section>
    <h2>Payment Details</h2>
    <label><input type="radio" name="payment_type" value="full" required> Full Payment</label>
    <label><input type="radio" name="payment_type" value="installments"> Installments (3 months)</label><br>

    <label><input type="radio" name="card_type" value="credit" required> Credit Card</label>
    <label><input type="radio" name="card_type" value="debit"> Debit Card</label><br>

    <label>Cardholder Name:
      <input type="text" name="cardholder_name" id="cardholder_name" value="<?= getValue('cardholder_name') ?>" required>
      <span class="error-message" id="cardholder_name_error"></span>
    </label><br>

    <label>Card Number (16 digits):
      <input type="text" name="card_number" id="card_number" value="<?= getValue('card_number') ?>" required>
      <span class="error-message" id="card_number_error"></span>
    </label><br>

    <label>Expiry Date (MM/YY):
      <input type="text" name="expiry_date" id="expiry_date" value="<?= getValue('expiry_date') ?>" required>
      <span class="error-message" id="expiry_date_error"></span>
    </label><br>

    <label>CVC (3 digits):
      <input type="text" name="cvc" id="cvc" value="<?= getValue('cvc') ?>" required>
      <span class="error-message" id="cvc_error"></span>
    </label><br>
  </section>

  <section>
    <h2>Passport Information</h2>
    <label>First Name (Passport):
      <input type="text" name="passport_first_name" id="passport_first_name" value="<?= getValue('passport_first_name') ?>" required>
      <span class="error-message" id="passport_first_name_error"></span>
    </label><br>

    <label>Second Name (Passport):
      <input type="text" name="passport_second_name" id="passport_second_name" value="<?= getValue('passport_second_name') ?>" required>
      <span class="error-message" id="passport_second_name_error"></span>
    </label><br>

    <label>Passport Number:
      <input type="text" name="passport_number" id="passport_number" value="<?= getValue('passport_number') ?>" required>
      <span class="error-message" id="passport_number_error"></span>
    </label><br>

    <label>Expiry Date (DD/MM/YYYY):
      <input type="text" name="passport_expiry" id="passport_expiry" value="<?= getValue('passport_expiry') ?>" required>
      <span class="error-message" id="passport_expiry_error"></span>
    </label><br>

    <label>Country of Issue:
      <select name="passport_country" required>
        <option value="">Select Country</option>
        <?php
        $countries = ["United Kingdom", "United States", "Canada", "Australia", "Ireland",
                      "France", "Germany", "Italy", "Spain", "Netherlands", "Sweden",
                      "Switzerland", "Japan", "China", "India", "South Africa"];
        foreach ($countries as $country) {
          $selected = (getValue('passport_country') == $country) ? 'selected' : '';
          echo "<option value='$country' $selected>$country</option>";
        }
        ?>
      </select>
    </label><br>
  </section>

  <section>
    <h2>Health and Safety</h2>
    <label>Emergency Contact Name:
      <input type="text" name="emergency_name" id="emergency_name" value="<?= getValue('emergency_name') ?>" required>
      <span class="error-message" id="emergency_name_error"></span>
    </label><br>

    <label>Emergency Phone:
      <input type="text" name="emergency_phone" id="emergency_phone" value="<?= getValue('emergency_phone') ?>" required>
      <span class="error-message" id="emergency_phone_error"></span>
    </label><br>

    <label>Emergency Address:
      <input type="text" name="emergency_address" value="<?= getValue('emergency_address') ?>" required>
    </label><br>

    <label><input type="checkbox" id="allergies_check"> Allergies</label>
    <input type="text" id="allergies_input" name="allergies" value="<?= getValue('allergies') ?>" style="display:none;" placeholder="Specify allergies">
  </section>

  <button type="submit">Proceed to Confirmation</button>
  <button type="button" onclick="history.back();">Cancel</button>

</form>

</main>

<script>
function showError(id, message) {
  document.getElementById(id).textContent = message;
}

function clearError(id) {
  document.getElementById(id).textContent = "";
}

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

document.getElementById('allergies_check').addEventListener('change', function() {
  document.getElementById('allergies_input').style.display = this.checked ? 'block' : 'none';
});
</script>

</body>
</html>
