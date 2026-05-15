<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Blog – Dastarkhan Restaurant</title>
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
    <a href="menu.php">Menu</a>
    <a href="reservation.php">Reserve</a>
    <a href="blog.php" class="active">Blog</a>
    <a href="contact.php">Contact</a>
    <a href="admin/login.php" style="color:var(--gold-light)!important">⚙️ Admin</a>
    <a href="#" class="nav-cart-btn" onclick="openCart();return false;">
      🛒 Cart <span class="cart-count" style="display:none">0</span>
    </a>
  </div>
  <button class="nav-hamburger" id="hamburger"><span></span><span></span><span></span></button>
</nav>

<div class="page-hero">
  <div class="section-tag" style="justify-content:center">From Our Kitchen</div>
  <h1>The <span style="color:var(--gold);font-style:italic">Dastarkhan</span> Blog</h1>
  <p>Stories, recipes, and culinary wisdom from our kitchen</p>
</div>

<section class="section">
  <div class="container-xl">
    <?php
    require_once 'includes/config.php';
    try {
      $db   = getDB();
      $stmt = $db->query("SELECT * FROM blog_posts WHERE is_published = 1 ORDER BY created_at DESC");
      $posts = $stmt->fetchAll();
    } catch (Exception $e) {
      $posts = [];
    }
    if (!count($posts)):
    ?>
    <!-- Static fallback if DB not ready -->
    <?php
    $posts = [
      ['id'=>1,'title'=>'The Art of Making Perfect Biryani','excerpt'=>'Discover the secrets behind authentic Pakistani Biryani — from selecting the right rice to the perfect dum technique.','image_url'=>'https://images.unsplash.com/photo-1563379091339-03b21ab4a4f8?w=600&q=80','author'=>'Ustad Ghulam Rasool','created_at'=>'2024-11-15'],
      ['id'=>2,'title'=>'History of Karahi: Pakistan\'s Beloved Dish','excerpt'=>'The wok-style Karahi has been central to Pakistani cuisine for centuries. Learn about its origins in Peshawar.','image_url'=>'https://images.unsplash.com/photo-1603894584373-5ac82b2ae398?w=600&q=80','author'=>'Chef Tariq Mehmood','created_at'=>'2024-10-22'],
      ['id'=>3,'title'=>'Secrets of Pakistani BBQ Marinade','excerpt'=>'What makes Pakistani BBQ so uniquely flavorful? We reveal the traditional marinades passed down through generations.','image_url'=>'https://images.unsplash.com/photo-1529193591184-b1d58069ecdd?w=600&q=80','author'=>'Dastarkhan Team','created_at'=>'2024-09-30'],
      ['id'=>4,'title'=>'Nihari: The King of Pakistani Breakfast','excerpt'=>'Slow-cooked for 12 hours, Nihari is more than a dish — it\'s a ritual. Explore the history of this beloved stew.','image_url'=>'https://images.unsplash.com/photo-1547592180-85f173990554?w=600&q=80','author'=>'Ustad Ghulam Rasool','created_at'=>'2024-09-10'],
      ['id'=>5,'title'=>'Chapli Kebab: The Peshwar Street Food King','excerpt'=>'Juicy, spiced, and cooked in ghee — Chapli Kebab from Peshawar is unlike any other kebab in the world.','image_url'=>'https://images.unsplash.com/photo-1529193591184-b1d58069ecdd?w=600&q=80','author'=>'Chef Tariq Mehmood','created_at'=>'2024-08-20'],
      ['id'=>6,'title'=>'Kashmiri Chai: Pakistan\'s Pink Tea Tradition','excerpt'=>'The vibrant pink tea of Kashmir, brewed with special tea leaves, milk, and garnished with crushed almonds.','image_url'=>'https://images.unsplash.com/photo-1585032226651-759b368d7246?w=600&q=80','author'=>'Dastarkhan Team','created_at'=>'2024-08-05'],
    ];
    endif;
    ?>

    <!-- Featured Post -->
    <?php $featured = $posts[0] ?? null; if ($featured): ?>
    <div class="row g-4 mb-5 fade-up">
      <div class="col-lg-7">
        <div style="height:360px;border-radius:20px;overflow:hidden">
          <img src="<?= htmlspecialchars($featured['image_url'] ?? '') ?>" alt="<?= htmlspecialchars($featured['title']) ?>"
               style="width:100%;height:100%;object-fit:cover">
        </div>
      </div>
      <div class="col-lg-5 d-flex align-items-center">
        <div>
          <div class="blog-date">⭐ Featured Story · <?= date('d M Y', strtotime($featured['created_at'])) ?></div>
          <h2 style="font-family:var(--font-display);font-size:1.8rem;font-weight:800;line-height:1.25;margin-bottom:16px"><?= htmlspecialchars($featured['title']) ?></h2>
          <p style="color:var(--text-muted);line-height:1.7;margin-bottom:20px"><?= htmlspecialchars($featured['excerpt'] ?? '') ?></p>
          <div style="display:flex;align-items:center;gap:12px">
            <div style="width:38px;height:38px;border-radius:50%;background:var(--dark);color:var(--gold);display:flex;align-items:center;justify-content:center;font-weight:800"><?= strtoupper(substr($featured['author']??'D',0,1)) ?></div>
            <div style="font-size:.85rem;font-weight:600"><?= htmlspecialchars($featured['author'] ?? 'Dastarkhan Team') ?></div>
          </div>
        </div>
      </div>
    </div>
    <?php endif; ?>

    <!-- All Posts Grid -->
    <div class="row g-4">
      <?php foreach (array_slice($posts, 1) as $post): ?>
      <div class="col-md-4 fade-up">
        <div class="blog-card">
          <div class="blog-img-wrap">
            <img src="<?= htmlspecialchars($post['image_url'] ?? '') ?>" alt="<?= htmlspecialchars($post['title']) ?>">
          </div>
          <div class="blog-card-body">
            <div class="blog-date"><?= date('d M Y', strtotime($post['created_at'])) ?> · <?= htmlspecialchars($post['author'] ?? 'Dastarkhan Team') ?></div>
            <div class="blog-title"><?= htmlspecialchars($post['title']) ?></div>
            <div class="blog-excerpt"><?= htmlspecialchars($post['excerpt'] ?? '') ?></div>
            <div style="margin-top:16px">
              <span style="color:var(--gold);font-weight:700;font-size:.82rem;cursor:pointer">Read More →</span>
            </div>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>

  </div>
</section>

<!-- Newsletter -->
<section style="background:var(--cream);padding:60px 5%;text-align:center">
  <div class="container-lg fade-up">
    <div style="font-size:2rem;margin-bottom:12px">📧</div>
    <h3 style="font-family:var(--font-display);font-size:1.8rem;font-weight:800;margin-bottom:10px">Stay in the <span style="color:var(--gold);font-style:italic">Loop</span></h3>
    <p style="color:var(--text-muted);margin-bottom:24px">Get our latest recipes, events, and special offers — straight to your inbox.</p>
    <div style="display:flex;gap:0;max-width:460px;margin:0 auto;border-radius:50px;overflow:hidden;box-shadow:var(--shadow-sm)">
      <input type="email" placeholder="your@email.com" style="flex:1;border:2px solid var(--border);border-right:none;border-radius:50px 0 0 50px;padding:14px 22px;outline:none;font-family:var(--font-body)">
      <button class="btn-primary-gold" style="border-radius:0 50px 50px 0;padding:14px 28px;white-space:nowrap" onclick="showToast('Subscribed! Welcome to the Dastarkhan family 🎉','success')">Subscribe</button>
    </div>
  </div>
</section>

<?php include 'includes/footer.php'; ?>
<?php include 'includes/cart.php'; ?>
<?php include 'includes/checkout.php'; ?>
<script src="assets/js/main.js"></script>
<script>observeFadeUp();</script>
</body>
</html>