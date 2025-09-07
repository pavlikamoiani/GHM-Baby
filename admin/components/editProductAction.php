<?php
session_start();
require_once '../../db/db.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_product_id'])) {
	$id = intval($_POST['edit_product_id']);
	$name = $_POST['edit_product_name'] ?? '';
	$category_id = intval($_POST['edit_category_id'] ?? 0);
	$subcategory_id = intval($_POST['edit_subcategory_id'] ?? 0);
	$color = $_POST['edit_product_color'] ?? null; // new
	$photo = '';

	if (!empty($_FILES['edit_product_photo']['name'])) {
		$target_dir = "../../images/products/";
		if (!is_dir($target_dir))
			mkdir($target_dir, 0777, true);
		$tmp_path = $_FILES["edit_product_photo"]["tmp_name"];
		$filename = basename($_FILES["edit_product_photo"]["name"]);
		$final_path = $target_dir . $filename;
		move_uploaded_file($tmp_path, $final_path);
		$output_path = $target_dir . "bg_" . $filename;
		// Если нужен remove_bg, раскомментируй:
		// if (remove_bg($final_path, $output_path)) {
		// 	$photo = "images/products/bg_" . $filename;
		// 	unlink($final_path);
		// } else {
		// 	$photo = "images/products/" . $filename;
		// }
		$photo = "images/products/" . $filename;
		$stmt = $conn->prepare("UPDATE product SET name=?, category_id=?, subcategory_id=?, color=?, photo=? WHERE id=?");
		$stmt->bind_param("siissi", $name, $category_id, $subcategory_id, $color, $photo, $id);
	} else {
		$stmt = $conn->prepare("UPDATE product SET name=?, category_id=?, subcategory_id=?, color=? WHERE id=?");
		$stmt->bind_param("siisi", $name, $category_id, $subcategory_id, $color, $id);
	}
	$stmt->execute();
	$stmt->close();
	echo "ok";
}
?>