<?php
require_once 'db/db.php';

$category_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($category_id <= 0) {
	header('Location: index.php');
	exit;
}

$subcategories = [];
$res = $conn->query("SELECT id, name, photo FROM subcategory WHERE category_id = $category_id");
while ($row = $res->fetch_assoc())
	$subcategories[] = $row;

$cat_res = $conn->query("SELECT name FROM category WHERE id = $category_id");
$category = $cat_res->fetch_assoc();

?>
<!DOCTYPE html>
<html lang="en">

<head>
	<title><?= htmlspecialchars($category['name'] ?? 'Category') ?></title>
	<title>GHM Baby</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Mukta:300,400,700">
	<link rel="stylesheet" href="fonts/icomoon/style.css">

	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/magnific-popup.css">
	<link rel="stylesheet" href="css/jquery-ui.css">
	<link rel="stylesheet" href="css/owl.carousel.min.css">
	<link rel="stylesheet" href="css/owl.theme.default.min.css">


	<link rel="stylesheet" href="css/aos.css">

	<link rel="stylesheet" href="css/style.css">
</head>

<body>
	<div class="site-wrap">
		<?php require_once 'components/header.php'; ?>

		<div class="container mt-5">
			<h2 class="mb-4"><?= htmlspecialchars($category['name'] ?? 'Category') ?></h2>
			<?php if (count($subcategories) > 0): ?>
				<div class="row">
					<?php foreach ($subcategories as $sub): ?>
						<div class="col-lg-3 col-md-6 mb-4">
							<div class="category-card">
								<img src="<?= htmlspecialchars($sub['photo'] ?: 'images/baby-clothes.png') ?>"
									alt="<?= htmlspecialchars($sub['name']) ?>" class="category-img">
								<div class="category-info">
									<div class="category-title"><?= htmlspecialchars($sub['name']) ?></div>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			<?php else: ?>
				<?php
				$products = [];
				$prod_res = $conn->query("SELECT name, photo, price FROM product WHERE category_id = $category_id");
				while ($row = $prod_res->fetch_assoc())
					$products[] = $row;
				if (count($products) === 0) {
					echo '<p>ამ კატეგორიაში პროდუქტები არ არის.</p>';
				}
				?>
				<div class="row">
					<?php foreach ($products as $prod): ?>
						<div class="col-lg-4 col-md-6 item-entry mb-4">
							<a href="#" class="product-item md-height bg-gray d-block">
								<?php
								$photos = json_decode($prod['photo'], true);
								if (is_array($photos) && count($photos) > 0 && !empty($photos[0])) {
									echo '<img src="' . htmlspecialchars($photos[0]) . '" alt="Image" class="img-fluid">';
								} else {
									echo '<img src="images/baby-clothes.png" alt="Image" class="img-fluid">';
								}
								?>
							</a>
							<h2 class="item-title"><a href="#"><?= htmlspecialchars($prod['name']) ?></a></h2>
							<?php if (!empty($prod['price'])): ?>
								<strong class="item-price">₾<?= htmlspecialchars($prod['price']) ?></strong>
							<?php endif; ?>
						</div>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>

		<?php require_once 'components/footer.php'; ?>
	</div>
	<script src="js/bootstrap.min.js"></script>
</body>

</html>