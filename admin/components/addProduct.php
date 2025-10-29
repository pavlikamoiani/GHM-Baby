<?php
session_start();
require_once '../../db/db.php';

$categories = [];
$res = $conn->query("SELECT id, name FROM category");
while ($row = $res->fetch_assoc())
	$categories[] = $row;

$subcategories = [];
$res = $conn->query("SELECT id, category_id, name FROM subcategory");
while ($row = $res->fetch_assoc()) {
	$subcategories[$row['category_id']][] = $row;
}

$products = [];
$res = $conn->query("SELECT p.id, p.name, p.photo, p.price, p.size, p.material, p.color, p.category_id, p.subcategory_id, 
	c.name as category_name, s.name as subcategory_name
	FROM product p
	LEFT JOIN category c ON p.category_id = c.id
	LEFT JOIN subcategory s ON p.subcategory_id = s.id");
while ($row = $res->fetch_assoc())
	$products[] = $row;
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<title>Product Management</title>
	<link rel="stylesheet" href="../../css/bootstrap.min.css">
	<style>
		body {
			background: #f7f9fb;
			font-family: 'Segoe UI', Arial, sans-serif;
		}

		.container {
			background: #fff;
			border-radius: 14px;
			box-shadow: 0 4px 24px rgba(0, 0, 0, 0.07);
			padding: 32px 28px 24px 28px;
			margin-top: 40px;
		}

		.btn {
			border-radius: 6px !important;
			box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
			font-weight: 500;
			transition: background 0.2s, color 0.2s;
		}

		.btn-success,
		.btn-primary {
			color: #fff !important;
		}

		.btn-success {
			border: none;
		}

		.btn-primary {
			border: none;
		}

		.btn-secondary {
			background: #e2e8f0 !important;
			color: #333 !important;
			border: none;
		}

		.table {
			background: #fff;
			border-radius: 10px;
			overflow: hidden;
			box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
		}

		.table th {
			background: #f1f5f9;
			border-bottom: 2px solid #e2e8f0;
			font-weight: 600;
		}

		.table td,
		.table th {
			vertical-align: middle !important;
			border-top: none;
		}

		.table-img {
			width: 44px;
			height: 44px;
			object-fit: cover;
			border-radius: 8px;
			box-shadow: 0 1px 4px rgba(0, 0, 0, 0.07);
		}

		.icon-btn {
			background: #f1f5f9;
			border: none;
			cursor: pointer;
			font-size: 1.2rem;
			margin: 0 5px;
			border-radius: 5px;
			padding: 6px 10px;
			transition: background 0.15s;
		}

		.icon-btn:hover {
			background: #e2e8f0;
		}

		.modal-content {
			border-radius: 12px;
			box-shadow: 0 4px 24px rgba(0, 0, 0, 0.10);
		}

		.modal-header {
			border-bottom: none;
		}

		.modal-title {
			font-weight: 600;
		}

		.form-control {
			border-radius: 7px;
			border: 1px solid #e2e8f0;
			box-shadow: none;
			margin-bottom: 10px;
		}

		input[type="file"].form-control {
			padding: 3px 6px;
		}

		hr {
			border-top: 1px solid #e2e8f0;
		}

		ul {
			margin-bottom: 0;
		}

		em {
			color: #a0aec0;
		}

		@media (max-width: 700px) {
			.container {
				padding: 10px 2vw 10px 2vw;
				margin-top: 10px;
				border-radius: 7px;
			}

			.table th,
			.table td {
				font-size: 0.95rem;
				padding: 6px 4px;
			}

			.btn,
			.btn-success,
			.btn-primary,
			.btn-secondary {
				font-size: 1rem !important;
				padding: 8px 10px !important;
				margin-bottom: 8px !important;
				width: 100%;
				box-sizing: border-box;
			}

			h2 {
				font-size: 1.2rem !important;
				margin-bottom: 12px !important;
			}

			.table-img {
				width: 32px;
				height: 32px;
			}
		}
	</style>
</head>

<body>
	<div class="container mt-4">
		<a href="../dashboard.php" class="btn btn-secondary" style="margin-bottom: 18px;">&#8592; Back</a>
		<h2 style="margin-bottom: 20px;">Product Management</h2>
		<button class="btn btn-success" data-toggle="modal" data-target="#addProductModal">Add Product</button>
		<hr>
		<table class="table table-bordered">
			<thead>
				<tr>
					<th>#</th>
					<th>Photo</th>
					<th>Name</th>
					<th>Price</th>
					<th>Size</th>
					<th>Material</th>
					<th>Category</th>
					<th>Subcategory</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($products as $prod): ?>
					<tr data-id="<?= $prod['id'] ?>" data-name="<?= htmlspecialchars($prod['name']) ?>"
						data-photo="<?= htmlspecialchars($prod['photo']) ?>"
						data-price="<?= htmlspecialchars($prod['price']) ?>"
						data-size="<?= htmlspecialchars($prod['size']) ?>"
						data-material="<?= htmlspecialchars($prod['material']) ?>"
						data-category="<?= htmlspecialchars($prod['category_id']) ?>"
						data-subcategory="<?= htmlspecialchars($prod['subcategory_id']) ?>">
						<td><?= $prod['id'] ?></td>
						<td>
							<?php
							$photos = json_decode($prod['photo'], true);
							if (is_array($photos) && count($photos) > 0) {
								foreach ($photos as $p) {
									echo '<img src="../../' . htmlspecialchars($p) . '" class="table-img" style="margin-right:2px;">';
								}
							} else {
								echo '<img src="../../images/baby-clothes.png" class="table-img">';
							}
							?>
						</td>
						<td><?= htmlspecialchars($prod['name']) ?></td>
						<td><?= htmlspecialchars($prod['price']) ?></td>
						<td><?= htmlspecialchars($prod['size']) ?></td>
						<td><?= htmlspecialchars($prod['material']) ?></td>
						<td><?= htmlspecialchars($prod['category_name']) ?></td>
						<td><?= htmlspecialchars($prod['subcategory_name']) ?></td>
						<td>
							<button class="icon-btn btn-edit-product" data-id="<?= $prod['id'] ?>">
								<i class="fa-solid fa-pen"></i>
							</button>
							<button class="icon-btn btn-delete-product" data-id="<?= $prod['id'] ?>">
								<i class="fa-solid fa-trash" style="color: red;"></i>
							</button>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>

	<div class="modal fade" id="addProductModal" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document">
			<form method="post" action="addProductAction.php" enctype="multipart/form-data" class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Add Product</h5>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				<div class="modal-body">
					<input type="text" name="product_name" class="form-control mb-2" placeholder="Product Name"
						required>
					<input type="number" step="0.01" name="product_price" class="form-control mb-2" placeholder="Price">
					<input type="text" name="product_size" class="form-control mb-2" placeholder="Size">
					<input type="text" name="product_material" class="form-control mb-2" placeholder="Material">
					<input type="file" name="product_photo[]" class="form-control mb-2" accept="image/*" multiple>
					<select name="category_id" id="category_select" class="form-control mb-2" required>
						<option value="">Select Category</option>
						<?php foreach ($categories as $cat): ?>
							<option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
						<?php endforeach; ?>
					</select>
					<select name="subcategory_id" id="subcategory_select" class="form-control mb-2"
						style="display:none;">
						<option value="">Select Subcategory</option>
					</select>
				</div>
				<div class="modal-footer">
					<button type="submit" name="add_product" class="btn btn-success">Add</button>
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
				</div>
			</form>
		</div>
	</div>

	<div class="modal fade" id="editProductModal" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document">
			<form id="editProductForm" method="post" enctype="multipart/form-data" class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Edit Product</h5>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				<div class="modal-body">
					<input type="hidden" name="edit_product_id" id="edit_product_id">
					<input type="text" name="edit_product_name" id="edit_product_name" class="form-control mb-2"
						placeholder="Product Name" required>
					<input type="number" step="0.01" name="edit_product_price" id="edit_product_price"
						class="form-control mb-2" placeholder="Price">
					<input type="text" name="edit_product_size" id="edit_product_size" class="form-control mb-2"
						placeholder="Size">
					<input type="text" name="edit_product_material" id="edit_product_material" class="form-control mb-2"
						placeholder="Material">
					<input type="file" name="edit_product_photo[]" class="form-control mb-2" accept="image/*" multiple>
					<div id="edit_product_preview_container" style="margin-top:10px;"></div>
					<select name="edit_category_id" id="edit_category_select" class="form-control mb-2" required>
						<option value="">Select Category</option>
						<?php foreach ($categories as $cat): ?>
							<option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
						<?php endforeach; ?>
					</select>
					<select name="edit_subcategory_id" id="edit_subcategory_select" class="form-control mb-2">
						<option value="">Select Subcategory</option>
						<!-- Options will be loaded by JS -->
					</select>
				</div>
				<div class="modal-footer">
					<button type="submit" name="edit_product" class="btn btn-success">Save</button>
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
				</div>
			</form>
		</div>
	</div>

	<script src="../../js/jquery-3.3.1.min.js"></script>
	<script src="../../js/bootstrap.min.js"></script>
	<script>
		var subcategories = <?php echo json_encode($subcategories); ?>;
		$('#category_select').on('change', function () {
			var catId = $(this).val();
			var subs = subcategories[catId] || [];
			if (subs.length > 0) {
				$('#subcategory_select').empty().append('<option value="">Select Subcategory</option>');
				subs.forEach(function (sub) {
					$('#subcategory_select').append('<option value="' + sub.id + '">' + sub.name + '</option>');
				});
				$('#subcategory_select').show().prop('required', true);
			} else {
				$('#subcategory_select').hide().prop('required', false);
			}
		});

		$(document).on('click', '.btn-edit-product', function () {
			var tr = $(this).closest('tr');
			var id = tr.data('id');
			var name = tr.data('name');
			var photo = tr.data('photo');
			var price = tr.data('price');
			var size = tr.data('size');
			var material = tr.data('material');
			var category = tr.data('category');
			var subcategory = tr.data('subcategory');
			$('#edit_product_id').val(id);
			$('#edit_product_name').val(name);
			$('#edit_product_price').val(price);
			$('#edit_product_size').val(size);
			$('#edit_product_material').val(material);

			var previewContainer = $('#edit_product_preview_container');
			previewContainer.empty();
			try {
				var photos = JSON.parse(photo);
				if (Array.isArray(photos) && photos.length > 0) {
					photos.forEach(function (p) {
						previewContainer.append('<img src="../../' + p + '" style="width:60px;height:60px;margin-right:5px;">');
					});
				} else {
					previewContainer.append('<img src="../../images/baby-clothes.png" style="width:60px;height:60px;">');
				}
			} catch (e) {
				previewContainer.append('<img src="../../images/baby-clothes.png" style="width:60px;height:60px;">');
			}

			$('#edit_category_select').val(category).trigger('change');
			setTimeout(function () {
				$('#edit_subcategory_select').val(subcategory);
			}, 100);

			$('#editProductModal').modal('show');
		});

		$('#editProductForm').on('submit', function (e) {
			e.preventDefault();
			var formData = new FormData(this);
			$.ajax({
				url: 'editProductAction.php',
				type: 'POST',
				data: formData,
				processData: false,
				contentType: false,
				success: function (resp) {
					location.reload();
				}
			});
		});

		$(document).on('click', '.btn-delete-product', function () {
			if (!confirm('Delete this product?')) return;
			var id = $(this).data('id');
			$.post('deleteProductAction.php', {
				id: id
			}, function (resp) {
				location.reload();
			});
		});

		var subcategories = <?php echo json_encode($subcategories); ?>;
		$('#edit_category_select').on('change', function () {
			var catId = $(this).val();
			var subs = subcategories[catId] || [];
			$('#edit_subcategory_select').empty().append('<option value="">Select Subcategory</option>');
			if (subs.length > 0) {
				subs.forEach(function (sub) {
					$('#edit_subcategory_select').append('<option value="' + sub.id + '">' + sub.name + '</option>');
				});
				$('#edit_subcategory_select').show().prop('required', true);
			} else {
				$('#edit_subcategory_select').hide().prop('required', false);
			}
		});

		var subcategoriesAdd = <?php echo json_encode($subcategories); ?>;
		$('#category_select').on('change', function () {
			var catId = $(this).val();
			var subs = subcategoriesAdd[catId] || [];
			if (subs.length > 0) {
				$('#subcategory_select').empty().append('<option value="">Select Subcategory</option>');
				subs.forEach(function (sub) {
					$('#subcategory_select').append('<option value="' + sub.id + '">' + sub.name + '</option>');
				});
				$('#subcategory_select').show().prop('required', true);
			} else {
				$('#subcategory_select').hide().prop('required', false);
			}
		});
	</script>
	<script src="https://kit.fontawesome.com/3c4198ef65.js" crossorigin="anonymous"></script>
</body>

</html>