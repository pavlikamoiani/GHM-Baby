<?php
session_start();
require_once '../../db/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
	$id = intval($_POST['id']);
	// Get photo paths
	$res = $conn->query("SELECT photo FROM product WHERE id = $id");
	if ($row = $res->fetch_assoc()) {
		$photos = json_decode($row['photo'], true);
		if (is_array($photos)) {
			foreach ($photos as $p) {
				$fullPath = '../../' . $p;
				if (file_exists($fullPath)) {
					unlink($fullPath);
				}
			}
		}
	}
	// Delete product
	$conn->query("DELETE FROM product WHERE id = $id");
	echo "ok";
}
?>