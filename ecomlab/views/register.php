<?php
require_once "../core/core.php";
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Register Customer</title>
	<link rel="stylesheet" href="../css/style.css">
</head>
<body class="auth-body">
	<div class="auth-page auth-page--wide">
		<h1>Customer Registration</h1>

		<?php if (isset($_SESSION['error'])): ?>
			<div class="auth-error"><?php echo htmlspecialchars($_SESSION['error']); ?></div>
			<?php unset($_SESSION['error']); ?>
		<?php endif; ?>

		<form id="registerForm" class="auth-form auth-form--grid" action="../actions/register_action.php" method="POST" novalidate>
			<div class="form-group">
				<label for="customer_name">Full Name</label>
				<input type="text" name="customer_name" id="customer_name" placeholder="Enter your full name">
				<div class="field-error" data-error-for="customer_name"></div>
			</div>
			<div class="form-group">
				<label for="customer_email">Email</label>
				<input type="email" name="customer_email" id="customer_email" placeholder="Enter your email">
				<div class="field-error" data-error-for="customer_email"></div>
			</div>
			<div class="form-group">
				<label for="customer_pass">Password</label>
				<input type="password" name="customer_pass" id="customer_pass" placeholder="Create a password">
				<div class="field-error" data-error-for="customer_pass"></div>
			</div>
			<div class="form-group">
				<label for="customer_confirm_pass">Confirm Password</label>
				<input type="password" name="customer_confirm_pass" id="customer_confirm_pass" placeholder="Confirm your password">
				<div class="field-error" data-error-for="customer_confirm_pass"></div>
			</div>
			<div class="form-group">
				<label for="customer_country">Country</label>
				<select name="customer_country" id="customer_country">
					<option value="">Select country</option>
					<option value="Nigeria">Nigeria</option>
					<option value="Ghana">Ghana</option>
					<option value="Kenya">Kenya</option>
					<option value="South Africa">South Africa</option>
					<option value="United States">United States</option>
					<option value="United Kingdom">United Kingdom</option>
				</select>
				<div class="field-error" data-error-for="customer_country"></div>
			</div>
			<div class="form-group">
				<label for="customer_city">City</label>
				<input type="text" name="customer_city" id="customer_city" placeholder="City">
				<div class="field-error" data-error-for="customer_city"></div>
			</div>
			<div class="form-group">
				<label for="customer_contact">Contact Number</label>
				<input type="text" name="customer_contact" id="customer_contact" placeholder="Contact number">
				<div class="field-error" data-error-for="customer_contact"></div>
			</div>
			<div class="form-group form-group--full">
				<button type="submit" id="registerButton">Register</button>
			</div>
		</form>

		<p class="auth-link-text">
			Already have an account? <a href="login.php">Login here</a>
		</p>
	</div>

	<script src="../js/validate.js"></script>
</body>
</html>
