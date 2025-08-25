<?php
$servername = "localhost";
$username = "root"; // Change if your MySQL user is different
$password = "";     // Change if your MySQL password is different
$dbname = "ghm";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}
?>