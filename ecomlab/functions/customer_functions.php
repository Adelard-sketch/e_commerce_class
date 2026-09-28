<?php

// This is a "function" file - a small helper that a view (page) can
// include when it needs data but doesn't need the full action/JSON flow
// that a form submission uses. It's used directly by PHP running on the
// server (not called from JavaScript), so it can just return PHP data
// instead of echoing JSON.
require_once "../controller/CustomerController.php";

// Get all customers via the controller.
// A view file (like view/customers.php) calls this function to get the
// data it needs to display, without having to know anything about the
// controller or model underneath.
function getAllCustomersList()
{
    // Create the controller (this also connects to the database, since
    // CustomerController creates a Customer, which extends Database).
    $controller = new CustomerController();

    return $controller->selectAll();
}

function getCustomerByEmail($email)
{
    $controller = new CustomerController();

    return $controller->findByEmail($email);
}

function getCustomerById($id)
{
    $controller = new CustomerController();

    return $controller->getById($id);
}

