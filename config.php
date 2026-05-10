<?php
session_start();

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "bus_booking";

$conn = new mysqli($host, $user, $pass, $dbname,3307);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function h($value) {
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}
?>