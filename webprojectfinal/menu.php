<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Menu – Dastarkhan Restaurant</title>
<link rel="stylesheet" href="assets/css/style.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>

<nav class="navbar scrolled">
  <a href="index.php" class="nav-brand">دسترخوان <span>Dastarkhan</span></a>
  <div class="nav-menu" id="navMenu">
    <a href="index.php">Home</a>
    <a href="about.php">About</a>
    <a href="menu.php" class="active">Menu</a>
    <a href="reservation.php">Reserve</a>
    <a href="blog.php">Blog</a>
    <a href="contact.php">Contact</a>
    <a href="admin/login.php" style="color:var(--gold-light)!important">⚙️ Admin</a>
    <a href="#" class="nav-cart-btn" onclick="openCart();return false;">
      🛒 Cart <span class="cart-count" style="display:none">0</span>
    </a>
  </div>
  <button class="nav-hamburger" id="hamburger"><span></span><span></span><span></span></button>
</nav>

<!-- PAGE HERO -->
<div class="page-hero">
  <div class="section-tag" style="justify-content:center">Our Offerings</div>
  <h1>The Full Menu</h1>
  <p>Explore our complete selection of authentic Pakistani dishes</p>
</div>

<!-- MENU SECTION -->
<section class="section">
  <div class="container-xl">

    <!-- Search + Filter Row -->
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
      <div class="menu-search-bar">
        <i class="bi bi-search"></i>
        <input type="text" placeholder="Search biryani, karahi..." oninput="handleMenuSearch(this.value)" id="menuSearchInput">
      </div>
    </div>

    <!-- Category Filter -->
    <div class="cat-filter" id="catFilter">
      <button class="cat-btn active" onclick="allCats(this)">🍽️ All Items</button>
      <!-- loaded via JS -->
    </div>

    <!-- Menu Grid -->
    <div class="row g-4" id="menuGrid">
      <div class="page-loader col-12"><div class="spinner" style="width:40px;height:40px;border-width:4px"></div></div>
    </div>
  </div>
</section>

<?php include 'includes/footer.php'; ?>
<?php include 'includes/cart.php'; ?>
<?php include 'includes/checkout.php'; ?>

<script src="assets/js/main.js"></script>
<script>
function allCats(btn) {
  document.querySelectorAll('.cat-btn').forEach(b=>b.classList.remove('active'));
  btn.classList.add('active');
  currentCategory = '';
  loadMenuItems('', currentSearch);
}

// Load initial category from URL param
const urlParams = new URLSearchParams(window.location.search);
const initCat = urlParams.get('cat') || '';
loadCategories();
loadMenuItems(initCat);
</script>
</body>
</html>