<?php
session_start();
require_once '../../db/db.php';

// Helper for remove.bg
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

// Add Product
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product'])) {
	$name = $_POST['product_name'] ?? '';
	$category_id = $_POST['category_id'] ?? null;
	$subcategory_id = $_POST['subcategory_id'] ?? null;
	$photo = '';
	if (!empty($_FILES['product_photo']['name'])) {
		$target_dir = "../../images/products/";
		if (!is_dir($target_dir))
			mkdir($target_dir, 0777, true);
		$tmp_path = $_FILES["product_photo"]["tmp_name"];
		$filename = basename($_FILES["product_photo"]["name"]);
		$final_path = $target_dir . $filename;
		move_uploaded_file($tmp_path, $final_path);
		$output_path = $target_dir . "bg_" . $filename;
		if (remove_bg($final_path, $output_path)) {
			$photo = "images/products/bg_" . $filename;
			unlink($final_path);
		} else {
			$photo = "images/products/" . $filename;
		}
	}
	// Insert product (you need a product table)
	$stmt = $conn->prepare("INSERT INTO product (name, photo, category_id, subcategory_id) VALUES (?, ?, ?, ?)");
	if ($subcategory_id) {
		$stmt->bind_param("ssii", $name, $photo, $category_id, $subcategory_id);
	} else {
		$null = null;
		$stmt->bind_param("ssii", $name, $photo, $category_id, $null);
	}
	$stmt->execute();
	$stmt->close();
	header('Location: addProduct.php');
	exit;
}
?>