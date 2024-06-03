<?php
$servername = "localhost";
$username = "root";
$password = ""; //Your MySQL Password
$database = "HotelTransylvania"; //Your MySQL Database Name

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
