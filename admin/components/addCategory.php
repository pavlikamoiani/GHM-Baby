<?php
session_start();
require_once '../../db/db.php';

// Fetch categories
$categories = [];
$res = $conn->query("SELECT id, name, photo FROM category");
while ($row = $res->fetch_assoc())
	$categories[] = $row;

// Fetch subcategories grouped by category_id
$subcategories = [];
$res = $conn->query("SELECT id, category_id, name, photo FROM subcategory");
while ($row = $res->fetch_assoc()) {
	$subcategories[$row['category_id']][] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<title>Category Management</title>
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
		<h2 style="margin-bottom: 20px;">Category Management</h2>
		<button class="btn btn-success" data-toggle="modal" data-target="#addCategoryModal">Add Category</button>
		<button class="btn btn-primary" data-toggle="modal" data-target="#addSubcategoryModal">Add Subcategory</button>
		<hr>
		<table class="table table-bordered">
			<thead>
				<tr>
					<th>#</th>
					<th>Photo</th>
					<th>Name</th>
					<th>Subcategories</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($categories as $cat): ?>
					<tr data-id="<?= $cat['id'] ?>" data-name="<?= htmlspecialchars($cat['name']) ?>"
						data-photo="<?= htmlspecialchars($cat['photo']) ?>">
						<td><?= $cat['id'] ?></td>
						<td><img src="../../<?= htmlspecialchars($cat['photo'] ?: 'images/baby-clothes.png') ?>"
								class="table-img"></td>
						<td><?= htmlspecialchars($cat['name']) ?></td>
						<td>
							<?php if (!empty($subcategories[$cat['id']])): ?>
								<ul style="padding-left: 18px; margin: 0;">
									<?php foreach ($subcategories[$cat['id']] as $sub): ?>
										<li style="display: flex; align-items: center;">
											<img src="../../<?= htmlspecialchars($sub['photo'] ?: 'images/baby-clothes.png') ?>"
												style="width:24px;height:24px;object-fit:cover;border-radius:4px;margin-right:5px;">
											<span><?= htmlspecialchars($sub['name']) ?></span>
										</li>
									<?php endforeach; ?>
								</ul>
							<?php else: ?>
								<em style="color:#888;">No subcategories</em>
							<?php endif; ?>
						</td>
						<td>
							<button class="icon-btn btn-edit" data-id="<?= $cat['id'] ?>">
								<i class="fa-solid fa-pen"></i>
							</button>
							<button class="icon-btn btn-delete" data-id="<?= $cat['id'] ?>">
								<i class="fa-solid fa-trash" style="color: red;"></i>
							</button>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>

	<!-- Add Category Modal -->
	<div class="modal fade" id="addCategoryModal" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document">
			<form method="post" action="addCategoryAction.php" enctype="multipart/form-data" class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Add Category</h5>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				<div class="modal-body">
					<input type="text" name="category_name" class="form-control mb-2" placeholder="Category Name"
						required>
					<input type="file" name="category_photo" class="form-control" accept="image/*">
				</div>
				<div class="modal-footer">
					<button type="submit" name="add_category" class="btn btn-success">Add</button>
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
				</div>
			</form>
		</div>
	</div>

	<!-- Add Subcategory Modal -->
	<div class="modal fade" id="addSubcategoryModal" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document">
			<form method="post" action="addCategoryAction.php" enctype="multipart/form-data" class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Add Subcategory</h5>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				<div class="modal-body">
					<select name="parent_category" class="form-control mb-2" required>
						<option value="">Select Category</option>
						<?php foreach ($categories as $cat): ?>
							<option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
						<?php endforeach; ?>
					</select>
					<input type="text" name="subcategory_name" class="form-control mb-2" placeholder="Subcategory Name"
						required>
					<input type="file" name="subcategory_photo" class="form-control" accept="image/*">
				</div>
				<div class="modal-footer">
					<button type="submit" name="add_subcategory" class="btn btn-primary">Add</button>
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
				</div>
			</form>
		</div>
	</div>

	<!-- Edit Category Modal -->
	<div class="modal fade" id="editCategoryModal" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document">
			<form id="editCategoryForm" method="post" enctype="multipart/form-data" class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Edit Category</h5>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				<div class="modal-body">
					<input type="hidden" name="edit_category_id" id="edit_category_id">
					<input type="text" name="edit_category_name" id="edit_category_name" class="form-control mb-2"
						placeholder="Category Name" required>
					<input type="file" name="edit_category_photo" class="form-control" accept="image/*">
					<img id="edit_category_preview" src="" style="width:60px;height:60px;margin-top:10px;">
					<hr>
					<div id="edit_category_subcategories">
						<!-- Subcategories will be loaded here by JS -->
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" name="edit_category" class="btn btn-success">Save</button>
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
				</div>
			</form>
		</div>
	</div>

	<!-- Edit Subcategory Modal (move outside any form) -->
	<div class="modal fade" id="editSubcategoryModal" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document">
			<form id="editSubcategoryForm" method="post" enctype="multipart/form-data" class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Edit Subcategory</h5>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				<div class="modal-body">
					<input type="hidden" name="edit_subcategory_id" id="edit_subcategory_id">
					<input type="text" name="edit_subcategory_name" id="edit_subcategory_name" class="form-control mb-2"
						placeholder="Subcategory Name" required>
					<input type="file" name="edit_subcategory_photo" class="form-control" accept="image/*">
					<img id="edit_subcategory_preview" src="" style="width:60px;height:60px;margin-top:10px;">
				</div>
				<div class="modal-footer">
					<button type="submit" name="edit_subcategory" class="btn btn-success">Save</button>
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
				</div>
			</form>
		</div>
	</div>

	<script src="../../js/jquery-3.3.1.min.js"></script>
	<script src="../../js/bootstrap.min.js"></script>
	<script>
		// Edit button logic for category
		$('.btn-edit').on('click', function() {
			var tr = $(this).closest('tr');
			var id = tr.data('id');
			var name = tr.data('name');
			var photo = tr.data('photo');
			$('#edit_category_id').val(id);
			$('#edit_category_name').val(name);
			$('#edit_category_preview').attr('src', '../../' + (photo ? photo : 'images/baby-clothes.png'));

			// Load subcategories for this category
			var subcategories = <?php echo json_encode($subcategories); ?>;
			var subs = subcategories[id] || [];
			var html = '';
			if (subs.length) {
				html += '<label style="font-weight:600;">Subcategories:</label><ul style="padding-left:18px;">';
				subs.forEach(function(sub) {
					html += '<li style="display:flex;align-items:center;margin-bottom:4px;">' +
						'<img src="../../' + (sub.photo ? sub.photo : 'images/baby-clothes.png') + '" style="width:24px;height:24px;object-fit:cover;border-radius:4px;margin-right:5px;">' +
						'<span style="flex:1;">' + $('<div>').text(sub.name).html() + '</span>' +
						'<button type="button" class="icon-btn btn-edit-subcategory-modal" data-id="' + sub.id + '" data-name="' + $('<div>').text(sub.name).html() + '" data-photo="' + (sub.photo ? sub.photo : '') + '" style="margin-left:8px;"><i class="fa-solid fa-pen"></i></button>' +
						'<button type="button" class="icon-btn btn-delete-subcategory-modal" data-id="' + sub.id + '" style="margin-left:4px;"><i class="fa-solid fa-trash" style="color:red;"></i></button>' +
						'</li>';
				});
				html += '</ul>';
			} else {
				html = '<em style="color:#888;">No subcategories</em>';
			}
			$('#edit_category_subcategories').html(html);

			$('#editCategoryModal').modal('show');
		});

		// Edit form submit for category
		$('#editCategoryForm').on('submit', function(e) {
			e.preventDefault();
			var formData = new FormData(this);
			$.ajax({
				url: 'editCategoryAction.php',
				type: 'POST',
				data: formData,
				processData: false,
				contentType: false,
				success: function(resp) {
					location.reload();
				}
			});
		});

		// Delete button logic for category
		$('.btn-delete').on('click', function() {
			if (!confirm('Delete this category?')) return;
			var id = $(this).data('id');
			$.post('deleteCategoryAction.php', {
				id: id
			}, function(resp) {
				location.reload();
			});
		});

		// Use event delegation for dynamically created buttons
		$(document).on('click', '.btn-edit-subcategory-modal', function() {
			var id = $(this).data('id');
			var name = $(this).data('name');
			var photo = $(this).data('photo');
			$('#edit_subcategory_id').val(id);
			$('#edit_subcategory_name').val(name);
			$('#edit_subcategory_preview').attr('src', '../../' + (photo ? photo : 'images/baby-clothes.png'));
			$('#editSubcategoryModal').modal('show');
		});

		$(document).on('click', '.btn-delete-subcategory-modal', function() {
			if (!confirm('Delete this subcategory?')) return;
			var id = $(this).data('id');
			$.post('deleteSubcategoryAction.php', {
				id: id
			}, function(resp) {
				location.reload();
			});
		});

		// Edit subcategory form submit
		$('#editSubcategoryForm').on('submit', function(e) {
			e.preventDefault();
			var formData = new FormData(this);
			$.ajax({
				url: 'editSubcategoryAction.php',
				type: 'POST',
				data: formData,
				processData: false,
				contentType: false,
				success: function(resp) {
					location.reload();
				}
			});
		});
	</script>
	<script src="https://kit.fontawesome.com/3c4198ef65.js" crossorigin="anonymous"></script>
</body>

</html>