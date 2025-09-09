<?php
session_start();
require_once '../db/db.php';

// Check if the admin is logged in, if not then redirect to login page
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
	header('Location: login.php');
	exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<title>Admin Dashboard</title>
	<link rel="stylesheet" href="../css/admin/dashboard.css">
	<style>
		body {
			font-family: 'Segoe UI', Arial, sans-serif;
			height: 100vh;
			margin: 0;
			display: flex;
			align-items: center;
			justify-content: center;
		}

		.dashboard-card {
			display: flex;
			flex-direction: column;
			align-items: center;
		}

		.dashboard-title {
			font-size: 2rem;
			font-weight: 600;
			margin-bottom: 32px;
			color: #333;
		}

		.btn {
			display: inline-block;
			padding: 14px 32px;
			margin: 12px 0;
			border-radius: 8px;
			font-size: 1.1rem;
			font-weight: 500;
			text-decoration: none;
			transition: background 0.2s, color 0.2s;
			border: none;
			outline: none;
			cursor: pointer;
		}

		.btn-category {
			background: linear-gradient(90deg, #43e97b 0%, #38f9d7 100%);
			color: #fff;
		}

		.btn-product {
			background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
			color: #fff;
		}

		.btn:hover {
			filter: brightness(0.95);
		}

		@media (max-width: 600px) {
			.dashboard-card {
				padding: 18px 8px 18px 8px;
				min-width: unset;
				width: 98vw;
			}

			.dashboard-title {
				font-size: 1.3rem;
				margin-bottom: 18px;
			}

			.btn {
				padding: 10px 10px;
				font-size: 1rem;
				width: 100%;
				box-sizing: border-box;
			}
		}
	</style>
</head>

<body>
	<div class="dashboard-card">
		<h1 class="dashboard-title">Admin Dashboard</h1>
		<a href="components/addCategory.php" class="btn btn-category">Category Management</a>
		<a href="components/addProduct.php" class="btn btn-product">Product Management</a>
	</div>
</body>

</html>