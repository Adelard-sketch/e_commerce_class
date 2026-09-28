<?php
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controller/CustomerController.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['error'] = 'Invalid request method.';
    redirect_to('/ecomlab/views/login.php');
}

$email = trim(strip_tags(strtolower($_POST['customer_email'] ?? '')));
$pass = $_POST['customer_pass'] ?? '';

if ($email === '' || $pass === '') {
    $_SESSION['error'] = 'Email and password are required.';
    redirect_to('/ecomlab/views/login.php');
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] = 'Please enter a valid email address.';
    redirect_to('/ecomlab/views/login.php');
}

$controller = new CustomerController();
$result = $controller->login($email, $pass);

if (!$result['success']) {
    $_SESSION['error'] = $result['error'] ?? 'Invalid email or password.';
    redirect_to('/ecomlab/views/login.php');
}

$user = $result['customer'];
$_SESSION['customer_id'] = $user['customer_id'];
$_SESSION['customer_name'] = $user['customer_name'];
$_SESSION['customer_email'] = $user['customer_email'];
$_SESSION['user_role'] = $user['user_role'];

redirect_to('/ecomlab/index.php');
