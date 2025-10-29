<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
</head>

<body>
	<div class="site-navbar bg-white py-2">
		<div class="search-wrap">
			<div class="container">
				<a href="#" class="search-close js-search-close"><span class="icon-close2"></span></a>
				<form action="#" method="post">
					<input type="text" class="form-control" placeholder="Search keyword and hit enter...">
				</form>
			</div>
		</div>
		<div class="container" style="padding: 10px;">
			<div class="d-flex align-items-center justify-content-between">
				<div>
					<button class="navbar-toggler" type="button" id="hamburgerBtn" aria-label="Toggle navigation"
						style="font-size:2rem; padding: 0.5rem 1.2rem; border: none; outline: none; box-shadow: none;">
						<span class="icon-menu"></span>
					</button>
				</div>
				<div id="sidebarMenu" class="sidebar-menu">
					<div class="sidebar-header d-flex justify-content-between align-items-center px-3 py-2">
						<span class="font-weight-bold" style="font-size:1.3rem; color:#333;">Menu</span>
						<button type="button" class="close" id="closeSidebar" aria-label="Close"
							style="outline: none; font-size: 2rem; color: #ff4d4f; background: none; border: none;">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<hr style="margin:0 0 20px 0; border-top:1px solid #eee;">
					<ul class="navbar-nav px-3" style="font-size: 17px;">
						<li class="nav-item mb-2">
							<a class="nav-link" href="index.php"
								style="color:#444; padding:10px; border-radius:8px; transition:background 0.2s;"><span
									class="icon-home"></span>მთავარი</a>
						</li>
						<li class="nav-item mb-2 dropdown-category">
							<a class="nav-link" href="#category" id="categoryDropdownBtn"
								style="color:#444; padding:10px; gap: 20px; border-radius:8px; transition:background 0.2s; cursor:pointer; display:flex; align-items:center; justify-content:space-between;">
								<span style="display:flex; align-items:center; gap:20px;"><span
										class="icon-list"></span>კატეგორია</span>
								<span style="margin-left:8px;" id="dropdownArrow">&#9662;</span>
							</a>
							<ul class="dropdown-menu-category" id="dropdownMenuCategory"
								style="display:none; list-style:none; padding-left:20px; margin:0;">
								<?php
								require_once(__DIR__ . '/../db/db.php');
								if ($conn->connect_errno) {
									echo "<li>DB Error</li>";
								} else {
									$catResult = $conn->query("SELECT id, name FROM category ORDER BY name ASC");
									if ($catResult && $catResult->num_rows > 0) {
										while ($cat = $catResult->fetch_assoc()) {
											$subResult = $conn->query("SELECT id, name FROM subcategory WHERE category_id=" . intval($cat['id']) . " ORDER BY name ASC");
											$hasSub = $subResult && $subResult->num_rows > 0;
											?>
											<li class="dropdown-subcategory">
												<a href="subcategory.php?id=<?php echo $cat['id']; ?>" class="category-link" style="color:#444; padding:7px 0; display:flex; align-items:center; justify-content:space-between;<?php if ($hasSub)
													   echo ' cursor:pointer;'; ?>" <?php if ($hasSub)
															 echo 'onclick="event.preventDefault(); toggleSubcategory(this);"'; ?>>
													<span><?php echo htmlspecialchars($cat['name']); ?></span>
													<?php if ($hasSub) { ?>
														<span class="subcategory-arrow" style="margin-left:8px;">&#9656;</span>
													<?php } ?>
												</a>
												<?php if ($hasSub) { ?>
													<ul class="subcategory-menu"
														style="display:none; list-style:none; padding-left:18px; margin:0;">
														<?php while ($sub = $subResult->fetch_assoc()) { ?>
															<li>
																<a href="subcategory.php?id=<?php echo $cat['id']; ?>&sub=<?php echo $sub['id']; ?>"
																	style="color:#444; padding:7px 0; display:block;">
																	<?php echo htmlspecialchars($sub['name']); ?>
																</a>
															</li>
														<?php } ?>
													</ul>
												<?php }
												$subResult && $subResult->free();
												?>
											</li>
											<?php
										}
									} else {
										echo "<li>კატეგორიები არ მოიძებნა</li>";
									}
									$catResult && $catResult->free();
								}
								?>
							</ul>
						</li>
						<li class="nav-item mb-2"><a class="nav-link" href="#"
								style="color:#444; padding:10px; border-radius:8px; transition:background 0.2s;"><span
									class="icon-info"></span>ჩვენს შესახებ</a>
						</li>
						<li class="nav-item"><a class="nav-link" href="#"
								style="color:#444; padding:10px; border-radius:8px; transition:background 0.2s;"><span
									class="icon-phone"></span>კონტაქტი</a>
						</li>
					</ul>
				</div>
				<div id="sidebarOverlay"></div>
				<div class="logo">
					<div class="site-logo">
						<a href="index.php" class="js-logo-clone">
							<img src="./images/WhatsApp_Image_2025-08-24_at_14.22.11_76dec9a9-removebg-preview.png"
								class="logo-img">
						</a>
					</div>
				</div>
				<div class="icons">
					<a href="#" class="icons-btn d-inline-block js-search-open"><span class="icon-search"></span></a>
					<a href="#" class="icons-btn d-inline-block"><span class="icon-heart-o"></span></a>
				</div>
			</div>
		</div>
	</div>
</body>
<script>
	const hamburgerBtn = document.getElementById('hamburgerBtn');
	const sidebarMenu = document.getElementById('sidebarMenu');
	const closeSidebar = document.getElementById('closeSidebar');
	const sidebarOverlay = document.getElementById('sidebarOverlay');

	hamburgerBtn.onclick = function () {
		sidebarMenu.classList.add('active');
		sidebarOverlay.style.display = 'block';
		document.body.style.overflow = 'hidden';
	};
	closeSidebar.onclick = function () {
		sidebarMenu.classList.remove('active');
		sidebarOverlay.style.display = 'none';
		document.body.style.overflow = '';
	};
	sidebarOverlay.onclick = function () {
		sidebarMenu.classList.remove('active');
		sidebarOverlay.style.display = 'none';
		document.body.style.overflow = '';
	};
	// Dropdown for კატეგორია
	const categoryDropdownBtn = document.getElementById('categoryDropdownBtn');
	const dropdownMenuCategory = document.getElementById('dropdownMenuCategory');
	const dropdownArrow = document.getElementById('dropdownArrow');
	let dropdownOpen = false;
	categoryDropdownBtn.onclick = function (e) {
		e.preventDefault();
		dropdownOpen = !dropdownOpen;
		dropdownMenuCategory.style.display = dropdownOpen ? 'block' : 'none';
		dropdownArrow.innerHTML = dropdownOpen ? '&#9652;' : '&#9662;';
	};
	// Optional: close dropdown when clicking outside
	document.addEventListener('click', function (event) {
		if (!categoryDropdownBtn.contains(event.target) && !dropdownMenuCategory.contains(event.target)) {
			dropdownMenuCategory.style.display = 'none';
			dropdownArrow.innerHTML = '&#9662;';
			dropdownOpen = false;
			// Close all subcategory menus
			document.querySelectorAll('.subcategory-menu').forEach(function (el) {
				el.style.display = 'none';
				const arrow = el.parentElement.querySelector('.subcategory-arrow');
				if (arrow) arrow.innerHTML = '&#9656;';
			});
		}
	});
	// Toggle subcategory dropdown
	window.toggleSubcategory = function (el) {
		const menu = el.parentElement.querySelector('.subcategory-menu');
		const arrow = el.querySelector('.subcategory-arrow');
		if (menu.style.display === 'block') {
			menu.style.display = 'none';
			if (arrow) arrow.innerHTML = '&#9656;';
		} else {
			// Close others
			document.querySelectorAll('.subcategory-menu').forEach(function (el2) {
				el2.style.display = 'none';
				const arrow2 = el2.parentElement.querySelector('.subcategory-arrow');
				if (arrow2) arrow2.innerHTML = '&#9656;';
			});
			menu.style.display = 'block';
			if (arrow) arrow.innerHTML = '&#9662;';
		}
	};
</script>

</html>