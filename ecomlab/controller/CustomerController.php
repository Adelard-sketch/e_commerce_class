<?php

require_once __DIR__ . '/../classes/CustomerClass.php';

class CustomerController
{
    private $customer;

    public function __construct()
    {
        $this->customer = new CustomerClass();
    }

    public function register($data)
    {
        $name = trim(strip_tags($data['name'] ?? ''));
        $email = trim(strtolower(strip_tags($data['email'] ?? '')));
        $country = trim(strip_tags($data['country'] ?? ''));
        $city = trim(strip_tags($data['city'] ?? ''));
        $contact = trim(strip_tags($data['contact'] ?? ''));

        if ($this->customer->emailExists($email)) {
            return ["success" => false, "error" => "Email already registered"];
        }

        $result = $this->customer->addCustomer(
            $name,
            $email,
            $data['pass'] ?? '',
            $country,
            $city,
            $contact
        );

        if ($result) {
            return ["success" => true];
        }

        return ["success" => false, "error" => "Unable to register customer"];
    }

    public function login($email, $pass)
    {
        $email = trim(strip_tags(strtolower($email ?? '')));
        $user = $this->customer->login($email, $pass);

        if ($user === false) {
            return ["success" => false, "error" => "Invalid email or password"];
        }

        return ["success" => true, "customer" => $user];
    }

    public function insert($name, $email, $pass, $country, $city, $contact, $image, $role)
    {
        return $this->customer->insertCustomer($name, $email, $pass, $country, $city, $contact, $image, $role);
    }

    public function selectAll()
    {
        return $this->customer->getAllCustomers();
    }

    public function findByEmail($email)
    {
        return $this->customer->findByEmail($email);
    }

    public function getById($id)
    {
        return $this->customer->getCustomerById($id);
    }
}
