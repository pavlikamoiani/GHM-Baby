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
$colors = [];
if (!empty($product['color'])) {
	$colors = json_decode($product['color'], true);
	if (!is_array($colors))
		$colors = [];
}

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
			gap: 32px;
			align-items: flex-start;
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

		.product-thumb.selected {
			border-color: #f79601;
			box-shadow: 0 0 0 2px #f7960155;
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

		#zoomOverlay {
			display: none;
			position: fixed;
			z-index: 1000;
			top: 0;
			left: 0;
			width: 100vw;
			height: 100vh;
			background: #000b;
			align-items: center;
			justify-content: center;
		}

		#zoomOverlay img {
			max-width: 90vw;
			max-height: 90vh;
			border-radius: 12px;
			box-shadow: 0 4px 32px #0008;
		}

		#zoomOverlay button#closeZoom {
			position: absolute;
			top: 32px;
			right: 48px;
			background: #fff;
			border: none;
			border-radius: 50%;
			width: 44px;
			height: 44px;
			font-size: 2rem;
			cursor: pointer;
			box-shadow: 0 2px 8px #0002;
		}

		@media (max-width: 600px) {
			.product-page-row {
				flex-direction: column;
				gap: 24px;
			}

			.product-images {
				flex-direction: column;
				align-items: center;
			}

			.product-main-img,
			.product-zoom-img {
				width: 45vw !important;
				max-width: 98vw;
				height: 45vw !important;
				max-height: 60vw;
				min-width: 0;
				min-height: 0;
			}

			.product-thumb {
				width: 60px !important;
				height: 60px !important;
			}

			.product-info {
				padding-left: 0;
			}

			.product-title {
				font-size: 1.2rem;
			}

			.product-price {
				font-size: 1.1rem;
			}

			.product-old-price {
				font-size: 0.95rem;
			}

			.add-to-cart-btn {
				font-size: 1rem;
				padding: 10px 0;
			}

			#zoomOverlay img {
				max-width: 98vw;
				max-height: 80vh;
			}

			#zoomOverlay button#closeZoom {
				top: 12px;
				right: 12px;
				width: 36px;
				height: 36px;
				font-size: 1.5rem;
			}

			.breadcrumbs {
				font-size: 0.95rem;
			}
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
				<div class="product-images" style="flex-direction:row;align-items:flex-start;gap:32px;">
					<!-- Vertical thumbnails -->
					<div style="display:flex;flex-direction:column;gap:16px;">
						<?php foreach ($photos as $idx => $img): ?>
							<img src="<?= htmlspecialchars($img) ?>"
								class="product-thumb<?= $idx === 0 ? ' selected' : '' ?>" data-idx="<?= $idx ?>" alt="Thumb"
								style="width:100px;height:100px;">
						<?php endforeach; ?>
					</div>
					<!-- Main image with overlays -->
					<div style="position:relative;width:350px;">
						<!-- Bluetooth icon overlay (show if product has bluetooth property) -->
						<?php if (!empty($product['bluetooth'])): ?>
							<img src="https://upload.wikimedia.org/wikipedia/commons/8/8c/Bluetooth.svg" alt="Bluetooth"
								style="position:absolute;top:18px;right:18px;width:38px;height:38px;z-index:3;opacity:0.85;">
						<?php endif; ?>
						<!-- Fullscreen/zoom button overlay -->
						<button id="zoomBtn"
							style="position:absolute;bottom:18px;right:18px;background:#fff;border:none;border-radius:50%;width:38px;height:38px;box-shadow:0 2px 8px #0002;z-index:3;cursor:pointer;display:flex;align-items:center;justify-content:center;">
							<svg width="22" height="22" fill="none" stroke="#444" stroke-width="2" viewBox="0 0 24 24">
								<rect x="3" y="3" width="7" height="7" rx="2" />
								<rect x="14" y="3" width="7" height="7" rx="2" />
								<rect x="14" y="14" width="7" height="7" rx="2" />
								<rect x="3" y="14" width="7" height="7" rx="2" />
							</svg>
						</button>
						<img id="mainImg" src="<?= htmlspecialchars($photos[0] ?? 'images/baby-clothes.png') ?>"
							class="product-main-img" alt="Product" style="display:block;margin:0 auto; height: 65vh;">
						<!-- Zoom overlay modal -->
						<div id="zoomOverlay"
							style="display:none;position:fixed;z-index:1000;top:0;left:0;width:100vw;height:100vh;background:#000b;align-items:center;justify-content:center;">
							<img id="zoomImg" src=""
								style="max-width:90vw;max-height:90vh;border-radius:12px;box-shadow:0 4px 32px #0008;">
							<button id="closeZoom"
								style="position:absolute;top:32px;right:48px;background:#fff;border:none;border-radius:50%;width:44px;height:44px;font-size:2rem;cursor:pointer;">&times;</button>
						</div>
					</div>
				</div>
				<div class="product-info">
					<div class="product-brand"><a href="index.php" style="color:#f79601;text-decoration:none;">GHM</a>
					</div>
					<div class="product-title"><?= htmlspecialchars($product['name']) ?></div>
					<?php if (!empty($colors)): ?>
						<!-- <div class="product-style-label">Color:</div> -->
						<div class="product-style-thumbs" style="margin-bottom:18px;">
							<?php foreach ($colors as $c): ?>
								<span class="product-style-thumb"
									style="background:<?= htmlspecialchars($c) ?>;border:2px solid #eee;width:32px;height:32px;display:inline-block;cursor:pointer;border-radius:50%;"
									title="<?= htmlspecialchars($c) ?>"></span>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
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
		// Slider logic
		const photos = <?= json_encode($photos) ?>;
		let currentIdx = 0;
		const mainImg = document.getElementById('mainImg');
		const thumbs = document.querySelectorAll('.product-thumb');
		function updateMainImg(idx) {
			currentIdx = idx;
			mainImg.src = photos[idx] || 'images/baby-clothes.png';
			thumbs.forEach((t, i) => t.classList.toggle('selected', i === idx));
		}
		thumbs.forEach((thumb, idx) => {
			thumb.onclick = () => updateMainImg(idx);
		});
		document.getElementById('sliderPrev').onclick = function () {
			let idx = (currentIdx - 1 + photos.length) % photos.length;
			updateMainImg(idx);
		};
		document.getElementById('sliderNext').onclick = function () {
			let idx = (currentIdx + 1) % photos.length;
			updateMainImg(idx);
		};
		document.querySelectorAll('.product-size-btn').forEach(btn => {
			btn.onclick = function () {
				document.querySelectorAll('.product-size-btn').forEach(b => b.classList.remove('selected'));
				btn.classList.add('selected');
			}
		});
		// Zoom/fullscreen logic
		const zoomBtn = document.getElementById('zoomBtn');
		const zoomOverlay = document.getElementById('zoomOverlay');
		const zoomImg = document.getElementById('zoomImg');
		const closeZoom = document.getElementById('closeZoom');
		zoomBtn.onclick = function () {
			zoomImg.src = mainImg.src;
			zoomOverlay.style.display = 'flex';
		};
		closeZoom.onclick = function () {
			zoomOverlay.style.display = 'none';
		};
		zoomOverlay.onclick = function (e) {
			if (e.target === zoomOverlay) zoomOverlay.style.display = 'none';
		};
	</script>
	<script src="js/bootstrap.min.js"></script>
</body>

</html>