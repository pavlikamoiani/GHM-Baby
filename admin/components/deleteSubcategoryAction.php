<?php
session_start();
require_once '../../db/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
	$id = intval($_POST['id']);
	$stmt = $conn->prepare("SELECT photo FROM subcategory WHERE id=?");
	$stmt->bind_param("i", $id);
	$stmt->execute();
	$stmt->bind_result($photo);
	$stmt->fetch();
	$stmt->close();

	if ($photo) {
		$img_path = '../../' . $photo;
		if (file_exists($img_path)) {
			unlink($img_path);
		}
	}

	$stmt = $conn->prepare("DELETE FROM subcategory WHERE id=?");
	$stmt->bind_param("i", $id);
	$stmt->execute();
	$stmt->close();
	echo "ok";
}
?>