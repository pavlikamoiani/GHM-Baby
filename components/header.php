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
						<li class="nav-item mb-2"><a class="nav-link" href="index.php"
								style="color:#444; padding:10px; border-radius:8px; transition:background 0.2s;"><span
									class="icon-home"></span>მთავარი</a></li>
						<li class="nav-item mb-2"><a class="nav-link" href="#category"
								style="color:#444; padding:10px; border-radius:8px; transition:background 0.2s;"><span
									class="icon-list"></span>კატეგორია</a></li>
						<li class="nav-item mb-2"><a class="nav-link" href="#"
								style="color:#444; padding:10px; border-radius:8px; transition:background 0.2s;"><span
									class="icon-info"></span>ჩვენს შესახებ</a></li>
						<li class="nav-item"><a class="nav-link" href="#"
								style="color:#444; padding:10px; border-radius:8px; transition:background 0.2s;"><span
									class="icon-phone"></span>კონტაქტი</a></li>
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
</script>

</html>