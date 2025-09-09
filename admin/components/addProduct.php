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
		.table-img {
			width: 40px;
			height: 40px;
			object-fit: cover;
			border-radius: 6px;
		}

		.icon-btn {
			background: none;
			border: none;
			cursor: pointer;
			font-size: 1.2rem;
			margin: 0 5px;
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
					<th>Color</th> <!-- new -->
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
						data-color="<?= htmlspecialchars($prod['color'] ?? '') ?>"
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
						<td>
							<?php
							$colors = json_decode($prod['color'] ?? '', true);
							if (is_array($colors) && count($colors) > 0) {
								foreach ($colors as $c) {
									echo '<span style="display:inline-block;width:18px;height:18px;border-radius:50%;background:' . htmlspecialchars($c) . ';border:1px solid #ccc;margin-right:2px;"></span>';
								}
							}
							?>
						</td>
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
					<!-- Color palette input -->
					<div class="mb-2">
						<label>Add Colors:</label>
						<div id="color_palette_add" style="display:flex;gap:8px;flex-wrap:wrap;margin-bottom:8px;">
						</div>
						<div style="display:flex;align-items:center;">
							<input type="color" id="color_picker_add"
								style="width:40px;height:40px;padding:0;border:none;">
							<button type="button" id="btn_add_color" class="btn btn-sm btn-primary ml-2">Add
								Color</button>
						</div>
						<input type="hidden" name="product_color" id="product_color_add">
					</div>
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
					<!-- Color palette input for edit -->
					<div class="mb-2">
						<label>Edit Colors:</label>
						<div id="color_palette_edit" style="display:flex;gap:8px;flex-wrap:wrap;margin-bottom:8px;">
						</div>
						<div style="display:flex;align-items:center;">
							<input type="color" id="color_picker_edit"
								style="width:40px;height:40px;padding:0;border:none;">
							<button type="button" id="btn_edit_color" class="btn btn-sm btn-primary ml-2">Add
								Color</button>
						</div>
						<input type="hidden" name="edit_product_color" id="edit_product_color">
					</div>
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
			var color = tr.data('color');
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

			// Make sure to set edit colors from data
			setEditColorsFromData(color);

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
			$.post('deleteProductAction.php', { id: id }, function (resp) {
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

		// Color palette logic for add
		let addColors = [];
		$('#btn_add_color').on('click', function () {
			const color = $('#color_picker_add').val();
			if (!addColors.includes(color)) {
				addColors.push(color);
				renderColorPalette('add');
			}
		});
		function renderColorPalette(type) {
			let palette = type === 'add' ? $('#color_palette_add') : $('#color_palette_edit');
			let colors = type === 'add' ? addColors : editColors;
			let hiddenInput = type === 'add' ? $('#product_color_add') : $('#edit_product_color');
			palette.empty();
			colors.forEach((c, idx) => {
				const span = $('<span>')
					.css({
						display: 'inline-block',
						width: '28px',
						height: '28px',
						'border-radius': '50%',
						background: c,
						border: '2px solid #ccc',
						cursor: 'pointer',
						'margin-right': '2px',
						position: 'relative'
					})
					.attr('title', 'Click to remove')
					.on('click', function () {
						if (type === 'add') {
							addColors.splice(idx, 1);
						} else {
							editColors.splice(idx, 1);
						}
						renderColorPalette(type);
					});

				// Add a small x in the corner
				const xMark = $('<span>')
					.text('×')
					.css({
						position: 'absolute',
						top: '-8px',
						right: '-8px',
						background: '#fff',
						borderRadius: '50%',
						width: '16px',
						height: '16px',
						lineHeight: '14px',
						textAlign: 'center',
						border: '1px solid #ccc',
						fontSize: '12px'
					});

				span.append(xMark);
				palette.append(span);
			});
			hiddenInput.val(JSON.stringify(colors));
		}
		$('form[action="addProductAction.php"]').on('submit', function () {
			$('#product_color_add').val(JSON.stringify(addColors));
		});

		// Color palette logic for edit
		let editColors = [];
		$('#btn_edit_color').on('click', function () {
			const color = $('#color_picker_edit').val();
			if (!editColors.includes(color)) {
				editColors.push(color);
				renderColorPalette('edit');
			}
		});
		function setEditColorsFromData(data) {
			try {
				if (data && data !== '') {
					const arr = JSON.parse(data);
					editColors = Array.isArray(arr) ? arr : [];
				} else {
					editColors = [];
				}
			} catch (e) {
				console.log('Error parsing color data:', e);
				editColors = [];
			}
			renderColorPalette('edit');
		}
		function renderColorPalette(type) {
			let palette = type === 'add' ? $('#color_palette_add') : $('#color_palette_edit');
			let colors = type === 'add' ? addColors : editColors;
			let hiddenInput = type === 'add' ? $('#product_color_add') : $('#edit_product_color');

			palette.empty();
			colors.forEach((c, idx) => {
				const span = $('<span>')
					.css({
						display: 'inline-block',
						width: '28px',
						height: '28px',
						'border-radius': '50%',
						background: c,
						border: '2px solid #ccc',
						cursor: 'pointer',
						'margin-right': '2px',
						position: 'relative'
					})
					.attr('title', 'Click to remove')
					.on('click', function () {
						if (type === 'add') {
							addColors.splice(idx, 1);
						} else {
							editColors.splice(idx, 1);
						}
						renderColorPalette(type);
					});

				// Add a small x in the corner
				const xMark = $('<span>')
					.text('×')
					.css({
						position: 'absolute',
						top: '-8px',
						right: '-8px',
						background: '#fff',
						borderRadius: '50%',
						width: '16px',
						height: '16px',
						lineHeight: '14px',
						textAlign: 'center',
						border: '1px solid #ccc',
						fontSize: '12px'
					});

				span.append(xMark);
				palette.append(span);
			});
			hiddenInput.val(JSON.stringify(colors));
		}
		$('#editProductForm').on('submit', function () {
			$('#edit_product_color').val(JSON.stringify(editColors));
		});

		$(document).on('click', '.btn-edit-product', function () {
			var tr = $(this).closest('tr');
			var id = tr.data('id');
			var name = tr.data('name');
			var photo = tr.data('photo');
			var price = tr.data('price');
			var size = tr.data('size');
			var material = tr.data('material');
			var color = tr.data('color');
			var category = tr.data('category');
			var subcategory = tr.data('subcategory');

			$('#edit_product_id').val(id);
			$('#edit_product_name').val(name);
			$('#edit_product_price').val(price);
			$('#edit_product_size').val(size);
			$('#edit_product_material').val(material);

			// Clear and repopulate colors
			editColors = [];
			setEditColorsFromData(color);

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
			setEditColorsFromData(color);
			$('#editProductModal').modal('show');
		});

		// ...existing code...
	</script>
	<script src="https://kit.fontawesome.com/3c4198ef65.js" crossorigin="anonymous"></script>
</body>

</html>