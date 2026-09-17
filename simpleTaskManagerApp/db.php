<?php
$host = getenv('TASKS_DB_HOST') ?: "localhost";
$db_user = getenv('TASKS_DB_USER') ?: "your_mysql_username";
$db_pass = getenv('TASKS_DB_PASS') ?: "your_mysql_password";
$db_name = getenv('TASKS_DB_NAME') ?: "your_database_name";

mysqli_report(MYSQLI_REPORT_OFF);
$conn = new mysqli($host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
