<?php
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controller/CustomerController.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['error'] = 'Invalid request method.';
    redirect_to('/ecomlab/views/register.php');
}

$rawName = trim(strip_tags($_POST['customer_name'] ?? ''));
$email = trim(strtolower(strip_tags($_POST['customer_email'] ?? '')));
$pass = $_POST['customer_pass'] ?? '';
$confirmPass = $_POST['customer_confirm_pass'] ?? '';
$country = trim(strip_tags($_POST['customer_country'] ?? ''));
$city = trim(strip_tags($_POST['customer_city'] ?? ''));
$contact = trim(strip_tags($_POST['customer_contact'] ?? ''));

if ($rawName === '' || $email === '' || $pass === '' || $confirmPass === '' || $country === '' || $city === '' || $contact === '') {
    $_SESSION['error'] = 'Please fill in all required fields.';
    redirect_to('/ecomlab/views/register.php');
}

if ($pass !== $confirmPass) {
    $_SESSION['error'] = 'Passwords do not match.';
    redirect_to('/ecomlab/views/register.php');
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] = 'Please enter a valid email address.';
    redirect_to('/ecomlab/views/register.php');
}

if (strlen($email) > 50 || strlen($rawName) > 100 || strlen($country) > 30 || strlen($city) > 30 || strlen($contact) > 15) {
    $_SESSION['error'] = 'One or more fields are too long.';
    redirect_to('/ecomlab/views/register.php');
}

if (!preg_match('/^(?=.*\d).{8,}$/', $pass)) {
    $_SESSION['error'] = 'Password must be at least 8 characters long and contain at least one number.';
    redirect_to('/ecomlab/views/register.php');
}

if (!preg_match('/^[0-9+\-\s]{7,15}$/', $contact)) {
    $_SESSION['error'] = 'Please enter a valid phone number.';
    redirect_to('/ecomlab/views/register.php');
}

$controller = new CustomerController();
$result = $controller->register([
    'name' => $rawName,
    'email' => $email,
    'pass' => $pass,
    'country' => $country,
    'city' => $city,
    'contact' => $contact,
]);

if (!$result['success']) {
    $_SESSION['error'] = $result['error'] ?? 'Registration failed.';
    redirect_to('/ecomlab/views/register.php');
}

$customer = $controller->findByEmail($email);
$_SESSION['customer_id'] = $customer['customer_id'] ?? null;
$_SESSION['customer_name'] = $rawName;
$_SESSION['customer_email'] = $email;
$_SESSION['user_role'] = 2;

redirect_to('/ecomlab/views/account/my_account.php');
