<?php

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

function redirect_to($path)
{
    header('Location: ' . $path);
    exit;
}

function is_logged_in()
{
    return isset($_SESSION['customer_id']) && !empty($_SESSION['customer_id']);
}

function is_admin()
{
    return isset($_SESSION['user_role']) && (int) $_SESSION['user_role'] === 1;
}

function isLoggedIn()
{
    return is_logged_in();
}

function getLoggedInCustomerId()
{
    return $_SESSION['customer_id'] ?? null;
}

function getLoggedInCustomerRole()
{
    return $_SESSION['user_role'] ?? null;
}

function logoutCustomer()
{
    $_SESSION = [];

    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params["path"],
            $params["domain"],
            $params["secure"],
            $params["httponly"]
        );
    }

    session_destroy();
    session_start();
}

function require_login()
{
    if (!is_logged_in()) {
        redirect_to('/ecomlab/views/login.php');
    }
}

function require_admin()
{
    if (!is_admin()) {
        $_SESSION['error'] = 'Access denied.';
        redirect_to('/ecomlab/index.php');
    }
}

function requireLogin()
{
    require_login();
}

