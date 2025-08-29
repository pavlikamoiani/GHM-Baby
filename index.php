<?php
require_once 'db/db.php';
$categories = [];
$res = $conn->query("SELECT id, name, photo FROM category");
while ($row = $res->fetch_assoc())
  $categories[] = $row;
?>
<!DOCTYPE html>
<html lang="en">

<head>
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

    <div class="site-blocks-cover" data-aos="fade">
      <div class="container">
        <div class="row">
          <div class="col-md-6 ml-auto order-md-2 align-self-start">
            <div class="site-block-cover-content">
              <h2 class="sub-title">#New Collection 2025</h2>
              <h1>GHM Baby Sales</h1>
              <p><a href="#products" class="btn btn-black rounded-0">Shop Now</a></p>
            </div>
          </div>
          <div class="col-md-6 order-1 align-self-end">
            <img src="images/1fef1befcb5c854fdf53def1b0d916a3-removebg.png" alt="Image" class="img-fluid">
          </div>
        </div>
      </div>
    </div>

    <div class="site-section" id="category">
      <div class="container">
        <div class="title-section mb-5">
          <h2 class="text-uppercase"><span class="d-block">Discover</span> The Collections</h2>
        </div>
        <div class="row align-items-stretch">
          <?php foreach ($categories as $cat): ?>
            <div class="col-lg-3 col-md-6 mb-4">
              <div class="category-card">
                <a href="subcategory.php?id=<?= urlencode($cat['id']) ?>">
                  <img src="<?= htmlspecialchars($cat['photo'] ?: 'images/baby-clothes.png') ?>"
                    alt="<?= htmlspecialchars($cat['name']) ?>" class="category-img">
                  <div class="category-info">
                    <div class="category-title"><?= htmlspecialchars($cat['name']) ?></div>
                  </div>
                </a>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
  <div class="site-section" id="products">
    <div class="container">
      <div class="row">
        <div class="title-section mb-5 col-12">
          <h2 class="text-uppercase">Popular Products</h2>
        </div>
      </div>
      <div class="row">
        <?php
        $products = [];
        $prod_res = $conn->query("SELECT name, photo, price FROM product ORDER BY id DESC LIMIT 12");
        while ($row = $prod_res->fetch_assoc())
          $products[] = $row;
        foreach ($products as $prod):
          ?>
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
    </div>
  </div>

  <div class="site-section">
    <div class="container">
      <div class="row">
        <div class="title-section text-center mb-5 col-12">
          <h2 class="text-uppercase">Most Rated</h2>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12 block-3">
          <div class="nonloop-block-3 owl-carousel">
            <?php
            $rated_products = [];
            $res = $conn->query("SELECT name, photo, price FROM product ORDER BY id DESC LIMIT 10");
            while ($row = $res->fetch_assoc())
              $rated_products[] = $row;
            foreach ($rated_products as $prod):
              ?>
              <div class="item">
                <div class="item-entry">
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
                  <div class="star-rating">
                    <span class="icon-star2 text-warning"></span>
                    <span class="icon-star2 text-warning"></span>
                    <span class="icon-star2 text-warning"></span>
                    <span class="icon-star2 text-warning"></span>
                    <span class="icon-star2 text-warning"></span>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
  </div>


  <div class="site-blocks-cover inner-page py-5" data-aos="fade">
    <div class="container">
      <div class="row">
        <div class="col-md-6 ml-auto order-md-2 align-self-start">
          <div class="site-block-cover-content">
            <h2 class="sub-title">#New Collection 2025</h2>
            <h1>GHM Baby Sales</h1>
            <p><a href="#products" class="btn btn-black rounded-0">Shop Now</a></p>
          </div>
        </div>
        <div class="col-md-6 order-1 align-self-end">
          <img src="images/footer-image.png" alt="Image" class="img-fluid">
        </div>
      </div>
    </div>
  </div>
  <?php require_once 'components/footer.php'; ?>

  <script src="js/jquery-3.3.1.min.js"></script>
  <script src="js/jquery-ui.js"></script>
  <script src="js/popper.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/owl.carousel.min.js"></script>
  <script src="js/jquery.magnific-popup.min.js"></script>
  <script src="js/aos.js"></script>

  <script src="js/main.js"></script>
</body>

</html>