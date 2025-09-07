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
	$price = $_POST['product_price'] ?? null;
	$size = $_POST['product_size'] ?? null;
	$material = $_POST['product_material'] ?? null;
	$color = $_POST['product_color'] ?? null; // new
	$photoPaths = [];
	$photo = '';

	// Ensure images/products directory exists
	$target_dir = '../../images/products/';
	if (!is_dir($target_dir)) {
		mkdir($target_dir, 0777, true);
	}

	if (!empty($_FILES['product_photo']['name'][0])) {
		foreach ($_FILES['product_photo']['name'] as $key => $photo_name) {
			if ($_FILES['product_photo']['error'][$key] === UPLOAD_ERR_OK) {
				$tmp_name = $_FILES['product_photo']['tmp_name'][$key];
				$filename = uniqid() . '_' . basename($photo_name);
				$targetPath = $target_dir . $filename;
				move_uploaded_file($tmp_name, $targetPath);
				$output_path = $target_dir . "bg_" . $filename;
				if (remove_bg($targetPath, $output_path)) {
					$photoPaths[] = "images/products/bg_" . $filename;
					unlink($targetPath);
				} else {
					$photoPaths[] = "images/products/" . $filename;
				}
			}
		}
		$photo = json_encode($photoPaths);
	}

	$stmt = $conn->prepare("INSERT INTO product (name, photo, price, size, material, color, category_id, subcategory_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
	// If subcategory_id is empty, set to null
	if ($subcategory_id) {
		$stmt->bind_param("ssdsssis", $name, $photo, $price, $size, $material, $color, $category_id, $subcategory_id);
	} else {
		$null = null;
		$stmt->bind_param("ssdsssis", $name, $photo, $price, $size, $material, $color, $category_id, $null);
	}
	$stmt->execute();
	$stmt->close();
	header('Location: addProduct.php');
	exit;
}
?>