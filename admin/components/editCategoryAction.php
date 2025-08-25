<?php
session_start();
require_once '../../db/db.php';

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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_category_id'])) {
	$id = intval($_POST['edit_category_id']);
	$name = $_POST['edit_category_name'] ?? '';
	$photo = '';
	if (!empty($_FILES['edit_category_photo']['name'])) {
		$target_dir = "../../images/categories/";
		if (!is_dir($target_dir))
			mkdir($target_dir, 0777, true);
		$tmp_path = $_FILES["edit_category_photo"]["tmp_name"];
		$filename = basename($_FILES["edit_category_photo"]["name"]);
		$final_path = $target_dir . $filename;
		move_uploaded_file($tmp_path, $final_path);
		$output_path = $target_dir . "bg_" . $filename;
		if (remove_bg($final_path, $output_path)) {
			$photo = "../images/categories/bg_" . $filename;
			unlink($final_path);
		} else {
			$photo = "../images/categories/" . $filename;
		}
		$stmt = $conn->prepare("UPDATE category SET name=?, photo=? WHERE id=?");
		$stmt->bind_param("ssi", $name, $photo, $id);
	} else {
		$stmt = $conn->prepare("UPDATE category SET name=? WHERE id=?");
		$stmt->bind_param("si", $name, $id);
	}
	$stmt->execute();
	$stmt->close();
	echo "ok";
}
?>