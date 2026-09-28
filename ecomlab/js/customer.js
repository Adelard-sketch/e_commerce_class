function registerCustomer() {
	
	var name = document.getElementById("customer_name").value.trim();
	var email = document.getElementById("customer_email").value.trim();
	var pass = document.getElementById("customer_pass").value.trim();
	var country = document.getElementById("customer_country").value.trim();
	var city = document.getElementById("customer_city").value.trim();
	var contact = document.getElementById("customer_contact").value.trim();

	
	var messageEl = document.getElementById("formMessage");

	// A simple regular expression to check for a basic "something@something.something" shape
	var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;


	if (!name || !email || !pass || !country || !city || !contact) {
		messageEl.textContent = "Please fill in all required fields.";
		return;
	}

	// Stop here if the email doesn't look valid
	if (!emailPattern.test(email)) {
		messageEl.textContent = "Please enter a valid email address.";
		return;
	}

	
	var formData = new FormData(document.getElementById("registerForm"));


	fetch("../actions/customer_register_action.php", {
		method: "POST",
		body: formData
	})
		.then(function (response) {
			// Parse the JSON response the action file sent back
			return response.json();
		})
		.then(function (data) {
			// Show whatever message the server sent (success or failure)
			messageEl.textContent = data.message;

			// If it worked, clear the form so it's ready for another entry
			if (data.success) {
				document.getElementById("registerForm").reset();
			}
		})
		.catch(function () {
			// Runs if the request itself failed (e.g. network/server error)
			messageEl.textContent = "Something went wrong. Please try again.";
		});
}

function loginCustomer() {
	var email = document.getElementById("login_email").value.trim();
	var pass = document.getElementById("login_pass").value.trim();
	var messageEl = document.getElementById("formMessage");
	var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

	if (!email || !pass) {
		messageEl.textContent = "Please enter your email and password.";
		return;
	}

	if (!emailPattern.test(email)) {
		messageEl.textContent = "Please enter a valid email address.";
		return;
	}

	var formData = new FormData(document.getElementById("loginForm"));

	fetch("../actions/customer_login_action.php", {
		method: "POST",
		body: formData
	})
		.then(function (response) {
			return response.json();
		})
		.then(function (data) {
			messageEl.textContent = data.message;

			if (data.success) {
				window.location.href = "../index.php";
			}
		})
		.catch(function () {
			messageEl.textContent = "Something went wrong. Please try again.";
		});
}

