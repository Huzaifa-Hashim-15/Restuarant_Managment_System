<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>About Us – Dastarkhan Restaurant</title>
<link rel="stylesheet" href="assets/css/style.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>

<nav class="navbar scrolled">
  <a href="index.php" class="nav-brand">دسترخوان <span>Dastarkhan</span></a>
  <div class="nav-menu" id="navMenu">
    <a href="index.php">Home</a>
    <a href="about.php" class="active">About</a>
    <a href="menu.php">Menu</a>
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

<div class="page-hero">
  <div class="section-tag" style="justify-content:center">Our Story</div>
  <h1>About <span style="color:var(--gold);font-style:italic">Dastarkhan</span></h1>
  <p>A legacy of flavors, served with love since 2009</p>
</div>

<!-- OUR STORY -->
<section class="section">
  <div class="container-xl">
    <div class="row g-5 align-items-center">
      <div class="col-lg-5 fade-up">
        <div class="about-img-collage">
          <img src="https://images.unsplash.com/photo-1567620905732-2d1ec7ab7445?w=700&q=80" alt="Restaurant Interior" class="about-img-main">
          <img src="https://images.unsplash.com/photo-1529193591184-b1d58069ecdd?w=400&q=80" alt="BBQ" class="about-img-secondary">
          <div class="about-award">🏆<br><span style="font-size:1.3rem;font-weight:900;display:block">15+</span><span style="font-size:.65rem;font-weight:500">Years<br>Serving</span></div>
        </div>
      </div>
      <div class="col-lg-7 fade-up">
        <div class="section-tag">Our Heritage</div>
        <h2 class="section-title">A Table Set With <span class="italic">Tradition</span></h2>
        <p style="color:var(--text-muted);line-height:1.8;margin-bottom:20px;font-size:1.02rem">
          Dastarkhan was born from a simple dream: to bring the soul of Pakistani home cooking to the world. Founded by Ustad Ghulam Rasool in the heart of Lahore's Food Street, our kitchen has been perfecting the same recipes for over 15 years.
        </p>
        <p style="color:var(--text-muted);line-height:1.8;margin-bottom:32px;font-size:1.02rem">
          Every morning, our chefs hand-grind their own spice blends. Our Nihari simmers for 12 hours. Our Biryani rice is sourced from the finest paddy fields of Sindh. We believe great food cannot be rushed — and our guests taste the difference.
        </p>

        <div class="row g-3 mb-4">
          <?php
          $features = [
            ['🌿','Farm Fresh','Locally sourced produce, delivered fresh daily'],
            ['🔥','Charcoal Grilled','Traditional coal & wood-fire cooking'],
            ['👨‍🍳','Master Chefs','25+ years culinary expertise each'],
            ['🏆','Award Winning','Best Pakistani Restaurant 2022, 2023'],
          ];
          foreach ($features as $f): ?>
          <div class="col-6">
            <div style="display:flex;gap:12px;align-items:flex-start;padding:16px;background:var(--cream);border-radius:14px">
              <span style="font-size:1.4rem"><?= $f[0] ?></span>
              <div>
                <div style="font-weight:700;font-size:.875rem;margin-bottom:2px"><?= $f[1] ?></div>
                <div style="font-size:.75rem;color:var(--text-muted);line-height:1.4"><?= $f[2] ?></div>
              </div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
        <a href="menu.php" class="btn-primary-gold" style="text-decoration:none">Explore Our Menu →</a>
      </div>
    </div>
  </div>
</section>

<!-- TEAM -->
<section class="section" style="background:var(--cream)">
  <div class="container-xl">
    <div class="text-center mb-5 fade-up">
      <div class="section-tag">The People Behind the Flavors</div>
      <h2 class="section-title">Meet Our <span class="italic">Team</span></h2>
    </div>
    <div class="row g-4 justify-content-center">
      <?php
      $team = [
        ['Ustad Ghulam Rasool','Head Chef & Founder','https://images.unsplash.com/photo-1577219491135-ce391730fb2c?w=300&q=80','30 years of experience. Lahore most celebrated karahi master.'],
        ['Chef Tariq Mehmood','BBQ & Grill Specialist','https://images.unsplash.com/photo-1581299894007-aaa50297cf16?w=300&q=80','Trained in Peshawar, brings authentic Peshwari techniques to every grill.'],
        ['Chef Zainab Akhtar','Desserts & Halwai','https://images.unsplash.com/photo-1607631568010-a87245c0daf8?w=300&q=80','Her Kheer and Gajar Halwa are legendary. Recipes passed down from her nani.'],
      ];
      foreach ($team as $m): ?>
      <div class="col-md-4 fade-up">
        <div style="text-align:center;background:#fff;border-radius:20px;padding:32px 24px;box-shadow:var(--shadow-sm)">
          <img src="<?= $m[2] ?>" alt="<?= $m[0] ?>" style="width:100px;height:100px;border-radius:50%;object-fit:cover;border:4px solid var(--gold);margin:0 auto 16px">
          <h5 style="font-family:var(--font-display);font-weight:700;margin-bottom:4px"><?= $m[0] ?></h5>
          <div style="color:var(--gold);font-size:.8rem;font-weight:700;text-transform:uppercase;letter-spacing:1px;margin-bottom:12px"><?= $m[1] ?></div>
          <p style="color:var(--text-muted);font-size:.875rem;line-height:1.6"><?= $m[3] ?></p>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- STATS -->
<section style="background:var(--dark);padding:60px 5%">
  <div class="container-xl">
    <div class="row g-4 text-center">
      <?php
      $stats = [['10,000+','Happy Customers'],['50+','Menu Items'],['15+','Years of Excellence'],['12hrs','Nihari Slow-Cooked']];
      foreach ($stats as $s): ?>
      <div class="col-md-3 col-6 fade-up">
        <div style="font-family:var(--font-display);font-size:2.5rem;font-weight:900;color:var(--gold);line-height:1"><?= $s[0] ?></div>
        <div style="color:rgba(255,255,255,.5);font-size:.85rem;margin-top:6px;text-transform:uppercase;letter-spacing:1px"><?= $s[1] ?></div>
      </div>
      <?php endforeach; ?>
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