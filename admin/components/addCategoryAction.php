<?php
session_start();
require_once '../../db/db.php';

// Helper function for remove.bg
function remove_bg($input_path, $output_path)
{
	$api_key = 'nZb4CdbNnszt1WR3aA8zZidv';
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'https://api.remove.bg/v1.0/removebg');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, [
		'image_file' => new CURLFile($input_path),
		'size' => 'auto'
	]);
	curl_setopt($ch, CURLOPT_HTTPHEADER, [
		'X-Api-Key: ' . $api_key
	]);
	$result = curl_exec($ch);
	$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	curl_close($ch);
	if ($http_code == 200 && $result) {
		file_put_contents($output_path, $result);
		return true;
	}
	return false;
}

// Add Category
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_category'])) {
	$name = $_POST['category_name'] ?? '';
	$photo = '';
	if (!empty($_FILES['category_photo']['name'])) {
		$target_dir = "../../images/categories/";
		if (!is_dir($target_dir))
			mkdir($target_dir, 0777, true);
		$tmp_path = $_FILES["category_photo"]["tmp_name"];
		$filename = basename($_FILES["category_photo"]["name"]);
		$final_path = $target_dir . $filename;
		move_uploaded_file($tmp_path, $final_path);
		$output_path = $target_dir . "bg_" . $filename;
		if (remove_bg($final_path, $output_path)) {
			$photo = "images/categories/bg_" . $filename;
			unlink($final_path);
		} else {
			$photo = "images/categories/" . $filename;
		}
	}
	$stmt = $conn->prepare("INSERT INTO category (name, photo) VALUES (?, ?)");
	$stmt->bind_param("ss", $name, $photo);
	$stmt->execute();
	$stmt->close();
	header('Location: addCategory.php');
	exit;
}

// Add Subcategory
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_subcategory'])) {
	$name = $_POST['subcategory_name'] ?? '';
	$category_id = $_POST['parent_category'] ?? 0;
	$photo = '';
	if (!empty($_FILES['subcategory_photo']['name'])) {
		$target_dir = "../../images/subcategories/";
		if (!is_dir($target_dir))
			mkdir($target_dir, 0777, true);
		$tmp_path = $_FILES["subcategory_photo"]["tmp_name"];
		$filename = basename($_FILES["subcategory_photo"]["name"]);
		$final_path = $target_dir . $filename;
		move_uploaded_file($tmp_path, $final_path);
		$output_path = $target_dir . "bg_" . $filename;
		if (remove_bg($final_path, $output_path)) {
			$photo = "images/subcategories/bg_" . $filename;
			unlink($final_path);
		} else {
			$photo = "images/subcategories/" . $filename;
		}
	}
	$stmt = $conn->prepare("INSERT INTO subcategory (category_id, name, photo) VALUES (?, ?, ?)");
	$stmt->bind_param("iss", $category_id, $name, $photo);
	$stmt->execute();
	$stmt->close();
	header('Location: addCategory.php');
	exit;
}
?>