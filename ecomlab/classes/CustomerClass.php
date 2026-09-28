<?php

require_once "../core/db_class.php";

class CustomerClass extends Database
{
    public function emailExists($email)
    {
        $sql = "SELECT customer_email FROM customer WHERE customer_email = ? LIMIT 1";
        $row = $this->fetchOne($sql, [$email]);

        return ($row !== false && !empty($row['customer_email']));
    }

    public function addCustomer($name, $email, $pass, $country, $city, $contact)
    {
        $hashedPass = password_hash($pass, PASSWORD_BCRYPT);
        $sql = "
            INSERT INTO customer (
                customer_name,
                customer_email,
                customer_pass,
                customer_country,
                customer_city,
                customer_contact,
                user_role
            ) VALUES (?, ?, ?, ?, ?, ?, 2)
        ";

        return $this->execute($sql, [$name, $email, $hashedPass, $country, $city, $contact]);
    }

    public function insertCustomer($name, $email, $pass, $country, $city, $contact, $image, $role)
    {
        $hashedPass = password_hash($pass, PASSWORD_BCRYPT);
        $sql = "
            INSERT INTO customer (
                customer_name,
                customer_email,
                customer_pass,
                customer_country,
                customer_city,
                customer_contact,
                customer_image,
                user_role
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ";

        return $this->execute($sql, [$name, $email, $hashedPass, $country, $city, $contact, $image, $role]);
    }

    public function getAllCustomers()
    {
        $sql = "
            SELECT
                customer_id,
                customer_name,
                customer_email,
                customer_country,
                customer_city,
                customer_contact,
                customer_image,
                user_role
            FROM customer
            ORDER BY customer_id DESC
        ";

        return $this->fetchAll($sql);
    }

    public function findByEmail($email)
    {
        $sql = "
            SELECT
                customer_id,
                customer_name,
                customer_email,
                customer_pass,
                customer_country,
                customer_city,
                customer_contact,
                customer_image,
                user_role
            FROM customer
            WHERE customer_email = ?
            LIMIT 1
        ";

        return $this->fetchOne($sql, [$email]);
    }

    public function getCustomerByEmail($email)
    {
        $sql = "SELECT * FROM customer WHERE customer_email = ? LIMIT 1";
        $row = $this->fetchOne($sql, [$email]);

        return ($row !== false) ? $row : false;
    }

    public function login($email, $pass)
    {
        $row = $this->getCustomerByEmail($email);

        if ($row === false) {
            return false;
        }

        if (!password_verify($pass, $row['customer_pass'])) {
            return false;
        }

        return $row;
    }

    public function getCustomerById($id)
    {
        $sql = "
            SELECT
                customer_id,
                customer_name,
                customer_email,
                customer_country,
                customer_city,
                customer_contact,
                customer_image,
                user_role
            FROM customer
            WHERE customer_id = ?
            LIMIT 1
        ";

        return $this->fetchOne($sql, [(int) $id]);
    }
}

class Customer extends CustomerClass
{
}

