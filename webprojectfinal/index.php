<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dastarkhan Restaurant – Authentic Pakistani Cuisine</title>
<meta name="description" content="Experience the finest authentic Pakistani cuisine at Dastarkhan. Biryani, Karahi, BBQ, Nihari and more.">
<!-- Bootstrap 5 CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Noto+Naskh+Arabic:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
  :root {
    --deep-burgundy: #6b2e2a;
    --gold: #c9a03d;
    --gold-light: #daaf4b;
    --gold-deep: #b8860b;
    --dark: #1f1a17;
    --darker: #151110;
    --text: #2c241f;
    --text-muted: #6b5c54;
    --cream: #fff8ef;
    --border: #e9e0d5;
    --shadow-sm: 0 12px 30px rgba(0, 0, 0, 0.05);
    --shadow-md: 0 20px 35px -10px rgba(0, 0, 0, 0.1);
    --font-display: 'Poppins', 'Segoe UI', sans-serif;
    --font-accent: 'Noto Naskh Arabic', 'Amiri', serif;
  }

  * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
  }

  body {
    font-family: 'Poppins', 'Segoe UI', sans-serif;
    background: #fefaf5;
    color: var(--text);
    overflow-x: hidden;
  }

  /* ===== NAVBAR ===== */
  .navbar {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    padding: 1.2rem 2rem;
    background: transparent;
    transition: background 0.3s ease, box-shadow 0.2s;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    z-index: 1000;
    flex-wrap: wrap;
  }

  .navbar.scrolled {
    background: #221c1a;
    box-shadow: 0 4px 18px rgba(0, 0, 0, 0.12);
  }

  .nav-brand {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    text-decoration: none;
    line-height: 1.2;
    transition: all 0.25s ease;
  }

  .brand-main {
    display: flex;
    align-items: baseline;
    flex-wrap: wrap;
    gap: 0.5rem;
    font-size: 1.6rem;
    font-weight: 700;
    letter-spacing: -0.3px;
  }

  .brand-main .urdu {
    font-family: 'Noto Naskh Arabic', 'Amiri', serif;
    font-size: 1.7rem;
    font-weight: 600;
    color: #f7e5c2;
    text-shadow: 1px 1px 2px rgba(0,0,0,0.2);
  }

  .brand-main .eng {
    font-family: 'Poppins', sans-serif;
    font-size: 1.5rem;
    font-weight: 600;
    color: var(--gold-light);
    letter-spacing: 0.5px;
  }

  .brand-tagline {
    font-size: 0.78rem;
    font-family: 'Noto Naskh Arabic', 'Amiri', cursive;
    color: #FFFFFF !important;
    margin-top: 4px;
    letter-spacing: 0.3px;
    font-weight: 500;
    background: rgba(0, 0, 0, 0.28);
    backdrop-filter: blur(2px);
    padding: 2px 8px;
    border-radius: 30px;
    display: inline-block;
    line-height: 1.3;
    text-shadow: 0 1px 1px rgba(0,0,0,0.3);
  }

  .navbar.transparent .brand-tagline {
    color: #FFFFFF !important;
    background: rgba(0, 0, 0, 0.3);
  }
  .navbar.scrolled .brand-tagline {
    color: #FFFFFF !important;
    background: rgba(0, 0, 0, 0.45);
  }

  .nav-menu {
    display: flex;
    align-items: center;
    gap: 1.6rem;
    flex-wrap: wrap;
  }

  .nav-menu a {
    text-decoration: none;
    font-weight: 500;
    font-size: 0.95rem;
    transition: all 0.2s;
    color: #fff6e5;
    letter-spacing: 0.3px;
    padding: 0.4rem 0;
    border-bottom: 2px solid transparent;
  }

  .navbar.transparent .nav-menu a {
    color: #fff5ea;
  }

  .navbar.scrolled .nav-menu a {
    color: #f7e5cf;
  }

  .nav-menu a:hover,
  .nav-menu a.active {
    border-bottom-color: var(--gold);
    color: var(--gold);
  }

  .nav-cart-btn {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    background: rgba(201, 160, 61, 0.2);
    padding: 0.3rem 0.9rem;
    border-radius: 40px;
    font-weight: 600;
  }

  .cart-count {
    background: var(--gold);
    color: #2a1e1a;
    border-radius: 30px;
    padding: 0px 6px;
    font-size: 0.7rem;
    font-weight: bold;
  }

  .nav-hamburger {
    display: none;
    flex-direction: column;
    background: transparent;
    border: none;
    cursor: pointer;
    gap: 5px;
    padding: 5px;
  }

  .nav-hamburger span {
    width: 26px;
    height: 2.5px;
    background-color: #ffefcf;
    transition: 0.2s;
    border-radius: 4px;
  }

  /* Hero Section */
  .hero {
    min-height: 100vh;
    background: linear-gradient(125deg, #1e1612 0%, #2a201b 100%);
    display: flex;
    align-items: center;
    padding: 120px 5% 60px;
    position: relative;
    overflow: hidden;
  }
  .hero-bg-pattern {
    position: absolute;
    inset: 0;
    opacity: 0.08;
    background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 80 80"><path fill="none" stroke="%23c9a03d" stroke-width="0.8" d="M20 20 L60 20 M20 40 L60 40 M20 60 L60 60 M40 20 L40 60"/><circle cx="40" cy="40" r="8" stroke="%23c9a03d" fill="none"/></svg>');
    background-repeat: repeat;
    background-size: 48px;
  }
  .hero-content {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    align-items: center;
    gap: 3rem;
    position: relative;
    z-index: 2;
    width: 100%;
    max-width: 1400px;
    margin: 0 auto;
  }
  .hero-tag {
    display: inline-block;
    background: rgba(201,160,61,0.2);
    backdrop-filter: blur(4px);
    padding: 6px 18px;
    border-radius: 40px;
    font-size: 0.8rem;
    letter-spacing: 0.5px;
    color: var(--gold-light);
    margin-bottom: 1.5rem;
  }
  .hero-title {
    font-size: 3.2rem;
    font-weight: 800;
    color: #fff3e6;
    line-height: 1.2;
    letter-spacing: -0.02em;
  }
  .hero-title .accent {
    color: var(--gold);
    font-style: italic;
  }
  .hero-subtitle {
    font-size: 1.1rem;
    color: rgba(255,245,235,0.85);
    max-width: 520px;
    margin: 1.5rem 0 2rem;
    line-height: 1.6;
  }
  .hero-cta {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
    margin-bottom: 2rem;
  }
  .btn-primary-gold, .btn-outline-gold {
    padding: 12px 28px;
    border-radius: 60px;
    font-weight: 600;
    text-decoration: none;
    transition: 0.2s;
    display: inline-flex;
    align-items: center;
    gap: 10px;
  }
  .btn-primary-gold {
    background: var(--gold);
    color: #1a130f;
    border: none;
  }
  .btn-primary-gold:hover {
    background: #b8860b;
    color: white;
    transform: translateY(-2px);
  }
  .btn-outline-gold {
    border: 1.5px solid var(--gold);
    color: var(--gold);
    background: transparent;
  }
  .btn-outline-gold:hover {
    background: var(--gold);
    color: #1a130f;
  }
  .hero-stats {
    display: flex;
    gap: 2rem;
    align-items: center;
  }
  .hero-stat-num {
    font-size: 1.6rem;
    font-weight: 800;
    color: var(--gold);
  }
  .hero-stat-label {
    font-size: 0.75rem;
    color: rgba(255,255,255,0.6);
  }
  .hero-images {
    position: relative;
    min-width: 280px;
  }
  .hero-img-main {
    width: 320px;
    border-radius: 32px;
    box-shadow: var(--shadow-md);
    object-fit: cover;
    aspect-ratio: 1/1;
  }
  .hero-img-float {
    width: 140px;
    border-radius: 24px;
    position: absolute;
    bottom: -20px;
    right: -30px;
    box-shadow: var(--shadow-sm);
    border: 3px solid rgba(255,255,255,0.3);
  }
  .hero-badge {
    position: absolute;
    top: -10px;
    left: -20px;
    background: var(--gold);
    color: #2a1e1a;
    border-radius: 40px;
    padding: 10px 16px;
    text-align: center;
    font-weight: bold;
    font-size: 1rem;
    backdrop-filter: blur(8px);
    box-shadow: var(--shadow-sm);
  }

  /* Sections */
  .section {
    padding: 80px 5%;
  }
  .section-tag {
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 2px;
    color: var(--gold);
    font-weight: 600;
    margin-bottom: 12px;
  }
  .section-title {
    font-size: 2.3rem;
    font-weight: 800;
    color: var(--dark);
  }
  .section-title .italic {
    font-style: italic;
    color: var(--gold);
  }
  .spinner {
    border: 3px solid #e0d6cc;
    border-top-color: var(--gold);
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
  }
  @keyframes spin { to { transform: rotate(360deg); } }
  .fade-up {
    opacity: 0;
    transform: translateY(20px);
    transition: opacity 0.5s ease, transform 0.5s ease;
  }
  .fade-up.visible {
    opacity: 1;
    transform: translateY(0);
  }

  /* Responsive */
  @media (max-width: 992px) {
    .hero-title { font-size: 2.4rem; }
    .nav-menu {
      position: fixed;
      top: 80px;
      left: -100%;
      width: 75%;
      max-width: 300px;
      height: calc(100vh - 80px);
      background: #2b221ee6;
      backdrop-filter: blur(14px);
      flex-direction: column;
      align-items: flex-start;
      padding: 2rem 1.8rem;
      transition: 0.3s;
      border-radius: 0 28px 28px 0;
    }
    .nav-menu.active { left: 0; }
    .nav-hamburger { display: flex; }
    .hero-content { flex-direction: column; text-align: center; }
    .hero-stats { justify-content: center; }
    .hero-images { margin-top: 2rem; }
  }
  @media (max-width: 550px) {
    .brand-main .urdu { font-size: 1.3rem; }
    .brand-main .eng { font-size: 1.1rem; }
    .brand-tagline { font-size: 0.65rem; }
  }
</style>
</head>
<body>

<!-- ===== NAVBAR ===== -->
<nav class="navbar transparent" data-transparent="true" id="mainNav">
  <a href="index.php" class="nav-brand">
    <div class="brand-main">
      <span class="urdu">دسترخوان</span>
      <span class="eng">Dastarkhan</span>
    </div>
    <span class="brand-tagline">دل نے دسترخوان بچھایا</span>
  </a>
  <div class="nav-menu" id="navMenu">
    <a href="index.php" class="active">Home</a>
    <a href="about.php">About</a>
    <a href="menu.php">Menu</a>
    <a href="menu.php">Menu</a>
    <a href="reservation.php">Reserve</a>
    <a href="blog.php">Blog</a>
    <a href="contact.php">Contact</a>
    <a href="admin/login.php" style="color:var(--gold-light)!important">⚙️ Admin</a>
    <a href="#" class="nav-cart-btn" onclick="openCart();return false;">
      🛒 Cart <span class="cart-count" style="display:none">0</span>
    </a>
  </div>
  <button class="nav-hamburger" id="hamburger" aria-label="Menu">
    <span></span><span></span><span></span>
  </button>
</nav>

<!-- ===== HERO ===== -->
<section class="hero">
  <div class="hero-bg-pattern"></div>
  <div class="hero-content">
    <div>
      <div class="hero-tag">🌿 Authentic Pakistani Cuisine</div>
      <h1 class="hero-title">
        A Feast For The <span class="accent">Soul</span><br>& The Palate
      </h1>
      <p class="hero-subtitle">
        Generations of flavor, crafted with love. From slow-cooked Nihari to smoky charcoal BBQ — every dish tells a story of Pakistan's rich culinary heritage.
      </p>
      <div class="hero-cta">
        <a href="menu.php" class="btn-primary-gold">
          <i class="bi bi-journal-bookmark-fill"></i> Explore Menu
        </a>
        <a href="reservation.php" class="btn-outline-gold">
          <i class="bi bi-calendar2-check"></i> Book a Table
        </a>
      </div>
      <div class="hero-stats">
        <div><div class="hero-stat-num">50+</div><div class="hero-stat-label">Dishes</div></div>
        <div style="width:1px;background:rgba(255,255,255,.15)"></div>
        <div><div class="hero-stat-num">15+</div><div class="hero-stat-label">Years</div></div>
        <div style="width:1px;background:rgba(255,255,255,.15)"></div>
        <div><div class="hero-stat-num">10K+</div><div class="hero-stat-label">Happy Guests</div></div>
      </div>
    </div>
    <div class="hero-images">
      <img src="https://images.unsplash.com/photo-1563379091339-03b21ab4a4f8?w=800&q=80" alt="Pakistani Biryani" class="hero-img-main">
      <img src="https://images.unsplash.com/photo-1603894584373-5ac82b2ae398?w=400&q=80" alt="Karahi" class="hero-img-float">
      <div class="hero-badge">⭐<br>4.9/5<br><small style="font-size:.65rem;font-weight:400">Rating</small></div>
    </div>
  </div>
</section>

<!-- ===== WHY US ===== -->
<section class="section" style="background:var(--cream)">
  <div class="container-xl">
    <div class="text-center mb-5 fade-up">
      <div class="section-tag">Why Choose Us</div>
      <h2 class="section-title">The <span class="italic">Dastarkhan</span> Difference</h2>
    </div>
    <div class="row g-4">
      <div class="col-md-4 fade-up">
        <div style="text-align:center;padding:32px 24px;background:#fff;border-radius:20px;box-shadow:var(--shadow-sm);height:100%">
          <div style="font-size:2.5rem;margin-bottom:16px">🔥</div>
          <h4 style="font-family:var(--font-display);font-weight:700;margin-bottom:10px">Traditional Cooking</h4>
          <p style="color:var(--text-muted);font-size:.9rem;line-height:1.7">Slow-cooked on wood fire and coal just like our grandmothers taught us. No shortcuts, ever.</p>
        </div>
      </div>
      <div class="col-md-4 fade-up">
        <div style="text-align:center;padding:32px 24px;background:#fff;border-radius:20px;box-shadow:var(--shadow-sm);height:100%">
          <div style="font-size:2.5rem;margin-bottom:16px">🌿</div>
          <h4 style="font-family:var(--font-display);font-weight:700;margin-bottom:10px">Fresh Ingredients</h4>
          <p style="color:var(--text-muted);font-size:.9rem;line-height:1.7">We source fresh produce daily from local markets. Our spices are ground fresh in-house every morning.</p>
        </div>
      </div>
      <div class="col-md-4 fade-up">
        <div style="text-align:center;padding:32px 24px;background:#fff;border-radius:20px;box-shadow:var(--shadow-sm);height:100%">
          <div style="font-size:2.5rem;margin-bottom:16px">👨‍🍳</div>
          <h4 style="font-family:var(--font-display);font-weight:700;margin-bottom:10px">Master Chefs</h4>
          <p style="color:var(--text-muted);font-size:.9rem;line-height:1.7">Our ustads have over 25 years of experience in Pakistani cuisine, trained in the kitchens of Lahore and Peshawar.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ===== FEATURED MENU ===== -->
<section class="section">
  <div class="container-xl">
    <div class="d-flex justify-content-between align-items-end mb-5 flex-wrap gap-3">
      <div class="fade-up">
        <div class="section-tag">Our Specialties</div>
        <h2 class="section-title">Chef's <span class="italic">Recommendations</span></h2>
      </div>
      <a href="menu.php" class="btn-outline-gold fade-up" style="color:var(--dark);border-color:var(--border);padding:10px 24px;font-size:.875rem">
        View Full Menu →
      </a>
    </div>
    <div class="row g-4" id="featuredGrid">
      <div class="page-loader col-12"><div class="spinner" style="width:40px;height:40px;border-width:4px"></div></div>
    </div>
  </div>
</section>

<!-- ===== CTA BANNER ===== -->
<section style="background:linear-gradient(135deg,var(--dark),#3a2010);padding:80px 5%;text-align:center">
  <div class="container-lg fade-up">
    <div style="font-size:2.5rem;margin-bottom:12px">🍽️</div>
    <h2 style="font-family:var(--font-display);font-size:2.5rem;color:#fff;font-weight:900;margin-bottom:12px">
      Ready to <span style="color:var(--gold);font-style:italic">Dine?</span>
    </h2>
    <p style="color:rgba(255,255,255,.6);font-size:1.05rem;max-width:480px;margin:0 auto 32px">
      Reserve your table today or order online. We promise an unforgettable culinary experience.
    </p>
    <div style="display:flex;gap:16px;justify-content:center;flex-wrap:wrap">
      <a href="reservation.php" class="btn-primary-gold">📅 Reserve a Table</a>
      <a href="menu.php" class="btn-outline-gold">🛒 Order Now</a>
    </div>
  </div>
</section>

<!-- ===== TESTIMONIALS ===== -->
<section class="section" style="background:var(--cream)">
  <div class="container-xl">
    <div class="text-center mb-5 fade-up">
      <div class="section-tag">Testimonials</div>
      <h2 class="section-title">What Our <span class="italic">Guests</span> Say</h2>
    </div>
    <div class="row g-4" id="testimonialsRow">
    </div>
  </div>
</section>

<!-- ===== FOOTER ===== -->
<footer style="background:#1f1916; color:#c9b69a; text-align:center; padding:2rem; font-size:0.85rem">
  <p>© 2025 Dastarkhan — dil se pesh kardah | Authentic Pakistani Flavors</p>
  <p style="margin-top:0.5rem; font-size:0.75rem;">دل نے دسترخوان بچھایا — warmth on every plate</p>
</footer>

<script>
  // ----- NAVBAR SCROLL & HAMBURGER -----
  (function() {
    const navbar = document.getElementById('mainNav');
    const hamburger = document.getElementById('hamburger');
    const navMenu = document.getElementById('navMenu');

    function handleScroll() {
      if (window.scrollY > 50) {
        navbar.classList.add('scrolled');
        navbar.classList.remove('transparent');
        navbar.setAttribute('data-transparent', 'false');
      } else {
        navbar.classList.remove('scrolled');
        navbar.classList.add('transparent');
        navbar.setAttribute('data-transparent', 'true');
      }
    }
    window.addEventListener('scroll', handleScroll);
    handleScroll();

    if (hamburger && navMenu) {
      hamburger.addEventListener('click', (e) => {
        e.stopPropagation();
        navMenu.classList.toggle('active');
        const spans = hamburger.querySelectorAll('span');
        if (navMenu.classList.contains('active')) {
          spans[0].style.transform = 'rotate(45deg) translate(5px, 5px)';
          spans[1].style.opacity = '0';
          spans[2].style.transform = 'rotate(-45deg) translate(5px, -5px)';
        } else {
          spans[0].style.transform = 'none';
          spans[1].style.opacity = '1';
          spans[2].style.transform = 'none';
        }
      });
      const links = navMenu.querySelectorAll('a');
      links.forEach(link => link.addEventListener('click', () => {
        navMenu.classList.remove('active');
        if (hamburger) {
          const spans = hamburger.querySelectorAll('span');
          spans[0].style.transform = 'none';
          spans[1].style.opacity = '1';
          spans[2].style.transform = 'none';
        }
      }));
    }
    window.addEventListener('resize', () => {
      if (window.innerWidth > 992 && navMenu && navMenu.classList.contains('active')) {
        navMenu.classList.remove('active');
        if (hamburger) {
          const spans = hamburger.querySelectorAll('span');
          spans[0].style.transform = 'none';
          spans[1].style.opacity = '1';
          spans[2].style.transform = 'none';
        }
      }
    });
  })();

  // ----- FEATURED MENU LOAD (IMAGES FIXED) -----
  function loadFeaturedItems() {
    const featuredContainer = document.getElementById('featuredGrid');
    if (!featuredContainer) return;
    const items = [
      { name: "Seekh Kebab", price: "Rs 450", desc: "Charcoal grilled minced meat with secret masala", img: "https://images.unsplash.com/photo-1599487488170-d11ec9c172f0?w=400&q=80" },
      { name: "Chicken Biryani", price: "Rs 550", desc: "Fragrant basmati layered with spiced chicken and fried onions", img: "https://images.unsplash.com/photo-1563379091339-03b21ab4a4f8?w=400&q=80" },
      { name: "Mutton Nihari", price: "Rs 890", desc: "Slow-cooked overnight with bone marrow & traditional spices", img: "https://images.unsplash.com/photo-1603894584373-5ac82b2ae398?w=400&q=80" },
      { name: "Chicken Karahi", price: "Rs 990", desc: "Tomato based curry with fresh ginger & green chilies", img: "https://images.unsplash.com/photo-1588166524941-3bf61a9c41db?w=400&q=80" }
    ];
    featuredContainer.innerHTML = '';
    items.forEach(item => {
      const col = document.createElement('div');
      col.className = 'col-md-6 col-lg-3 fade-up';
      col.innerHTML = `
        <div style="background:#fff; border-radius:1.5rem; overflow:hidden; box-shadow:var(--shadow-sm); transition:transform 0.2s">
          <img src="${item.img}" alt="${item.name}" style="width:100%; height:200px; object-fit:cover">
          <div style="padding:1.2rem">
            <div style="display:flex; justify-content:space-between; align-items:center"><h5 style="font-weight:800;margin:0">${item.name}</h5><span style="color:var(--gold);font-weight:700">${item.price}</span></div>
            <p style="font-size:0.8rem; color:#6c5a50; margin-top:8px">${item.desc}</p>
            <button class="btn-primary-gold" style="padding:6px 20px; font-size:0.8rem" onclick="addToCart('${item.name}', '${item.price}')">Add to Cart</button>
          </div>
        </div>
      `;
      featuredContainer.appendChild(col);
    });
    observeFadeUp();
  }

  // ----- TESTIMONIALS -----
  function loadTestimonials() {
    const testimonials = [
      { name: 'Ahmed Raza', city: 'Lahore', rating: 5, text: 'The Chicken Karahi here is hands down the best I\'ve ever had. The masala is perfectly balanced. I visit every weekend!' },
      { name: 'Fatima Siddiqui', city: 'Karachi', rating: 5, text: 'Came for my anniversary dinner and the staff made it so special. The Nihari with fresh naan... absolutely divine!' },
      { name: 'Usman Tariq', city: 'Islamabad', rating: 5, text: 'Biryani lovers, this is your place. The basmati is perfectly cooked and the aroma hits you as soon as you walk in.' }
    ];
    const container = document.getElementById('testimonialsRow');
    if(!container) return;
    container.innerHTML = '';
    testimonials.forEach(t => {
      const col = document.createElement('div');
      col.className = 'col-md-4 fade-up';
      col.innerHTML = `
        <div style="background:#fff;border-radius:20px;padding:28px;box-shadow:var(--shadow-sm);height:100%">
          <div style="color:var(--gold);font-size:1.1rem;margin-bottom:14px">${'★'.repeat(t.rating)}</div>
          <p style="font-family:var(--font-accent);font-size:1.05rem;line-height:1.7;color:var(--text);margin-bottom:20px">"${t.text}"</p>
          <div style="display:flex;align-items:center;gap:12px">
            <div style="width:44px;height:44px;border-radius:50%;background:var(--dark);color:var(--gold);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:1.1rem">${t.name[0]}</div>
            <div><div style="font-weight:700;font-size:.9rem">${t.name}</div><div style="font-size:.75rem;color:var(--text-muted)">${t.city}</div></div>
          </div>
        </div>
      `;
      container.appendChild(col);
    });
    observeFadeUp();
  }

  function observeFadeUp() {
    const fades = document.querySelectorAll('.fade-up');
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) entry.target.classList.add('visible');
      });
    }, { threshold: 0.1 });
    fades.forEach(el => observer.observe(el));
  }

  window.openCart = function() { alert("🛒 Cart preview: your items will appear here soon."); };
  window.addToCart = function(name, price) { alert(`✓ ${name} added to cart (${price})`); };

  loadFeaturedItems();
  loadTestimonials();
  observeFadeUp();
</script>
</body>
</html>