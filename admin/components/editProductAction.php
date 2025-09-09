<?php
session_start();
require_once '../../db/db.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_product_id'])) {
	$id = intval($_POST['edit_product_id']);
	$name = $_POST['edit_product_name'] ?? '';
	$price = $_POST['edit_product_price'] ?? 0;
	$size = $_POST['edit_product_size'] ?? '';
	$material = $_POST['edit_product_material'] ?? '';
	$category_id = intval($_POST['edit_category_id'] ?? 0);
	$subcategory_id = intval($_POST['edit_subcategory_id'] ?? 0);
	$color = $_POST['edit_product_color'] ?? null; // new
	$photo = '';

	if (!empty($_FILES['edit_product_photo']['name'][0])) {
		$target_dir = "../../images/products/";
		$photoPaths = [];

		if (!is_dir($target_dir))
			mkdir($target_dir, 0777, true);

		foreach ($_FILES['edit_product_photo']['name'] as $key => $photo_name) {
			if ($_FILES['edit_product_photo']['error'][$key] === UPLOAD_ERR_OK) {
				$tmp_name = $_FILES['edit_product_photo']['tmp_name'][$key];
				$filename = uniqid() . '_' . basename($photo_name);
				$targetPath = $target_dir . $filename;
				move_uploaded_file($tmp_name, $targetPath);
				$photoPaths[] = "images/products/" . $filename;
			}
		}

		$photo = json_encode($photoPaths);
		$stmt = $conn->prepare("UPDATE product SET name=?, price=?, size=?, material=?, category_id=?, subcategory_id=?, color=?, photo=? WHERE id=?");
		$stmt->bind_param("sdssiissi", $name, $price, $size, $material, $category_id, $subcategory_id, $color, $photo, $id);
	} else {
		$stmt = $conn->prepare("UPDATE product SET name=?, price=?, size=?, material=?, category_id=?, subcategory_id=?, color=? WHERE id=?");
		$stmt->bind_param("sdssiisi", $name, $price, $size, $material, $category_id, $subcategory_id, $color, $id);
	}
	$stmt->execute();
	$stmt->close();
	echo "ok";
}
?>