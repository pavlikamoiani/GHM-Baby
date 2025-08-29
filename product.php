<?php
require_once 'db/db.php';
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
	header('Location: index.php');
	exit;
}
$res = $conn->query("SELECT * FROM product WHERE id = $id");
$product = $res->fetch_assoc();
if (!$product) {
	echo "Product not found.";
	exit;
}
$photos = json_decode($product['photo'], true);
if (!is_array($photos))
	$photos = [];

// Get category info
$cat_res = $conn->query("SELECT id, name FROM category WHERE id = " . intval($product['category_id']));
$category = $cat_res->fetch_assoc();

// Get subcategory info if exists
$subcategory = null;
if (!empty($product['subcategory_id'])) {
	$subcat_res = $conn->query("SELECT id, name FROM subcategory WHERE id = " . intval($product['subcategory_id']));
	$subcategory = $subcat_res->fetch_assoc();
}

// Example: style options (simulate, ideally from DB)
$styles = [
	["img" => $photos[0] ?? 'images/baby-clothes.png', "name" => "White"],
	["img" => "images/style-pink.png", "name" => "Pink"],
	["img" => "images/style-gold.png", "name" => "Gold"],
	["img" => "images/style-mini.png", "name" => "Mini"],
	["img" => "images/style-green.png", "name" => "Grandpa"],
	["img" => "images/style-grandpa.png", "name" => "Grandpa"],
	["img" => "images/style-girl.png", "name" => "Girl"],
	["img" => "images/style-sister.png", "name" => "Sister"],
];
// Example: sizes from DB
$sizes = [];
if (!empty($product['size'])) {
	$sizes = array_map('trim', explode(',', $product['size']));
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<title><?= htmlspecialchars($product['name']) ?> - GHM Baby</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Mukta:300,400,700">
	<link rel="stylesheet" href="fonts/icomoon/style.css">
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/style.css">
	<style>
		@font-face {
			font-family: "BPG Rioni Arial";
			src: url("fonts/bpg_rioni_arial.otf") format("opentype");
		}

		body {
			font-family: "BPG Rioni Arial", sans-serif;
		}

		.breadcrumbs {
			font-size: 1rem;
			margin-bottom: 24px;
			color: #888;
		}

		.breadcrumbs a {
			color: #f79601;
			text-decoration: none;
		}

		.product-page-row {
			display: flex;
			gap: 40px;
		}

		.product-images {
			flex: 1;
			display: flex;
			gap: 20px;
		}

		.product-main-img {
			width: 350px;
			height: 550px;
			border-radius: 10px;
			background: #fafafa;
		}

		.product-zoom-img {
			width: 350px;
			height: 550px;
			border-radius: 10px;
			background: #fafafa;
		}

		.product-thumbs {
			display: flex;
			gap: 10px;
			margin-top: 18px;
		}

		.product-thumb {
			width: 48px;
			height: 48px;
			object-fit: cover;
			border-radius: 8px;
			cursor: pointer;
			border: 2px solid #eee;
		}

		.product-info {
			flex: 1.2;
			padding-left: 0;
		}

		.product-brand {
			font-size: 1.1rem;
			color: #f79601;
			font-weight: 500;
			margin-bottom: 4px;
		}

		.product-title {
			font-size: 1.6rem;
			font-weight: 700;
			margin-bottom: 6px;
			color: #000;
		}

		.product-review {
			font-size: 1rem;
			color: #f79601;
			margin-bottom: 12px;
			display: inline-block;
		}

		.product-meta {
			font-size: 0.95rem;
			color: #888;
			margin-bottom: 10px;
		}

		.product-style-label {
			font-weight: 600;
			margin-bottom: 6px;
		}

		.product-style-thumbs {
			display: flex;
			gap: 8px;
			margin-bottom: 18px;
		}

		.product-style-thumb {
			width: 48px;
			height: 48px;
			border-radius: 8px;
			border: 2px solid #eee;
			cursor: pointer;
			object-fit: cover;
		}

		.product-style-thumb.selected {
			border-color: #f79601;
		}

		.product-size-label {
			font-weight: 600;
			margin-bottom: 6px;
		}

		.product-sizes {
			display: flex;
			gap: 8px;
			flex-wrap: wrap;
			margin-bottom: 18px;
		}

		.product-size-btn {
			background: #fff;
			border: 2px solid #eee;
			border-radius: 8px;
			padding: 8px 18px;
			font-size: 1rem;
			cursor: pointer;
		}

		.product-size-btn.selected,
		.product-size-btn:hover {
			border-color: #f79601;
		}

		.size-guide-link {
			font-size: 0.98rem;
			color: #f79601;
			margin-left: 12px;
			text-decoration: underline;
			cursor: pointer;
		}

		.product-price-row {
			display: flex;
			align-items: center;
			gap: 12px;
			margin-bottom: 8px;
		}

		.product-price {
			font-size: 1.5rem;
			color: #f79601;
			font-weight: 700;
		}

		.product-old-price {
			font-size: 1.1rem;
			color: #aaa;
			text-decoration: line-through;
		}

		.product-hotdeal {
			font-size: 1rem;
			color: #f79601;
			font-weight: 600;
		}

		.add-to-cart-btn {
			background: #f79601;
			color: #fff;
			font-size: 1.2rem;
			padding: 14px 0;
			border-radius: 8px;
			border: none;
			width: 100%;
			font-weight: 600;
		}

		.add-to-cart-btn:hover {
			background: #f79601;
		}
	</style>
</head>

<body>
	<div class="site-wrap">
		<?php require_once 'components/header.php'; ?>
		<div class="container py-5">
			<div class="breadcrumbs">
				<a href="index.php">Home</a>
				<?php if ($category): ?>
					/ <a
						href="subcategory.php?id=<?= urlencode($category['id']) ?>"><?= htmlspecialchars($category['name']) ?></a>
				<?php endif; ?>
				<?php if ($subcategory): ?>
					/ <a
						href="subcategory.php?id=<?= urlencode($category['id']) ?>&sub=<?= urlencode($subcategory['id']) ?>"><?= htmlspecialchars($subcategory['name']) ?></a>
				<?php endif; ?>
				/ <?= htmlspecialchars($product['name']) ?>
			</div>
			<div class="product-page-row">
				<div class="product-images">
					<img id="mainImg" src="<?= htmlspecialchars($photos[0] ?? 'images/baby-clothes.png') ?>"
						class="product-main-img" alt="Product"
						onclick="document.getElementById('zoomImg').src=this.src;">
					<img id="zoomImg"
						src="<?= htmlspecialchars($photos[1] ?? $photos[0] ?? 'images/baby-clothes.png') ?>"
						class="product-zoom-img" alt="Zoom">
				</div>
				<div class="product-info">
					<div class="product-brand"><a href="index.php" style="color:#f79601;text-decoration:none;">GHM</a>
					</div>
					<div class="product-title"><?= htmlspecialchars($product['name']) ?></div>
					<a href="#" class="product-review">Write a review</a>
					<?php if (!empty($product['material'])): ?>
						<div class="product-material"><strong>Material:</strong>
							<?= htmlspecialchars($product['material']) ?></div>
					<?php endif; ?>
					<div class="product-size-label">Size:
					</div>
					<div class="product-sizes">
						<?php foreach ($sizes as $sz): ?>
							<button class="product-size-btn"><?= htmlspecialchars($sz) ?></button>
						<?php endforeach; ?>
					</div>
					<div class="product-price-row">
						<div class="product-price">₾<?= htmlspecialchars($product['price'] ?? '5.00') ?></div>
						<?php if (!empty($product['old_price'])): ?>
							<div class="product-old-price">₾<?= htmlspecialchars($product['old_price']) ?></div>
						<?php else: ?>
						<?php endif; ?>
					</div>
					<a href="https://www.facebook.com/messages/t/106639162309339?locale=ru_RU" target="_blank"
						class="add-to-cart-btn">
						<button class="add-to-cart-btn">
							მოგვწერეთ მესენჯერში
						</button>
					</a>
					<div style="display:flex;align-items:center;gap:8px;margin:18px 0 0 0;">
						<!-- Truck SVG icon -->
						<svg xmlns="http://www.w3.org/2000/svg" width="28" height="22" viewBox="0 0 28 22" fill="none">
							<rect x="1.5" y="5.5" width="17" height="10" rx="2" stroke="#444" stroke-width="2" />
							<path
								d="M19 8H23.382C23.776 8 24.1482 8.22386 24.3162 8.58579L26 12.1716V15.5C26 16.3284 25.3284 17 24.5 17H23"
								stroke="#444" stroke-width="2" />
							<circle cx="6.5" cy="17.5" r="2.5" fill="#444" />
							<circle cx="21.5" cy="17.5" r="2.5" fill="#444" />
						</svg>
						<span style="font-size:1rem;color:#444;font-weight:500;">
							24 საათში მთელ საქართველოში
						</span>
					</div>
				</div>
			</div>
		</div>
		<?php require_once 'components/footer.php'; ?>
	</div>
	<script>
		function selectStyle(el) {
			document.querySelectorAll('.product-style-thumb').forEach(e => e.classList.remove('selected'));
			el.classList.add('selected');
			document.getElementById('mainImg').src = el.src;
			document.getElementById('zoomImg').src = el.src;
		}
		document.querySelectorAll('.product-size-btn').forEach(btn => {
			btn.onclick = function () {
				document.querySelectorAll('.product-size-btn').forEach(b => b.classList.remove('selected'));
				btn.classList.add('selected');
			}
		});
	</script>
	<script src="js/bootstrap.min.js"></script>
</body>

</html>