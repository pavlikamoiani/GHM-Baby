<?php
session_start();
require_once '../db/db.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<title>Admin Dashboard</title>
	<link rel="stylesheet" href="../css/admin/dashboard.css">
</head>

<body>
	<a href="components/addCategory.php" class="btn"
		style="margin:20px 0;display:inline-block;padding:10px 20px;background:#007bff;color:#fff;border-radius:5px;text-decoration:none;">Category
		Management</a>
	<a href="components/addProduct.php" class="btn"
		style="margin:20px 0 0 0;display:inline-block;padding:10px 20px;background:#28a745;color:#fff;border-radius:5px;text-decoration:none;">Product
		Management</a>
</body>

</html>