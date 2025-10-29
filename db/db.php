<!-- <?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ghm";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}
?> -->

<?php
$servername = "sql311.infinityfree.com";
$username = "if0_39900989";
$password = "7UJK40LxjPz";
$dbname = "if0_39900989_ghm";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}

?>