<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Reservations – Dastarkhan Restaurant</title>
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
    <a href="reservation.php" class="active">Reserve</a>
    <a href="blog.php">Blog</a>
    <a href="contact.php">Contact</a>
    <a href="admin/login.php" style="color:var(--gold-light)!important">⚙️ Admin</a>
    <a href="#" class="nav-cart-btn" onclick="openCart();return false;">
      🛒 Cart <span class="cart-count" style="display:none">0</span>
    </a>
  </div>
  <button class="nav-hamburger" id="hamburger"><span></span><span></span><span></span></button>
</nav>

<!-- HERO -->
<section class="reservation-section section" style="padding-top:120px">
  <div class="container-xl" style="position:relative;z-index:2">
    <div class="row g-5 align-items-center">
      <!-- Info Side -->
      <div class="col-lg-5 fade-up">
        <div class="section-tag" style="color:var(--gold)">Reserve Your Spot</div>
        <h2 class="section-title" style="color:#fff">Book a <span class="italic">Table</span></h2>
        <p style="color:rgba(255,255,255,.6);font-size:1rem;line-height:1.7;margin-bottom:36px">
          Whether it's a family gathering, romantic dinner, or a business meal — we'll set the perfect ambiance for your occasion. Call us or use the form to book.
        </p>

        <div style="display:flex;flex-direction:column;gap:20px">
          <div style="display:flex;gap:16px;align-items:center">
            <div style="width:52px;height:52px;background:rgba(201,168,76,.15);border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:1.3rem;flex-shrink:0">📍</div>
            <div>
              <div style="color:#fff;font-weight:600;margin-bottom:2px">Location</div>
              <div style="color:rgba(255,255,255,.5);font-size:.875rem">12 Food Street, Anarkali, Lahore</div>
            </div>
          </div>
          <div style="display:flex;gap:16px;align-items:center">
            <div style="width:52px;height:52px;background:rgba(201,168,76,.15);border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:1.3rem;flex-shrink:0">🕐</div>
            <div>
              <div style="color:#fff;font-weight:600;margin-bottom:2px">Opening Hours</div>
              <div style="color:rgba(255,255,255,.5);font-size:.875rem">Daily: 12:00 PM – 12:00 Midnight</div>
            </div>
          </div>
          <div style="display:flex;gap:16px;align-items:center">
            <div style="width:52px;height:52px;background:rgba(201,168,76,.15);border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:1.3rem;flex-shrink:0">📞</div>
            <div>
              <div style="color:#fff;font-weight:600;margin-bottom:2px">Call to Reserve</div>
              <div style="color:rgba(255,255,255,.5);font-size:.875rem">+92 42 3576 8900</div>
            </div>
          </div>
        </div>
      </div>

      <!-- Form Side -->
      <div class="col-lg-7 fade-up">
        <div class="reservation-form">
          <h3 style="font-family:var(--font-display);font-size:1.5rem;font-weight:700;color:#fff;margin-bottom:6px">Make a Reservation</h3>
          <p style="color:rgba(255,255,255,.4);font-size:.85rem;margin-bottom:28px">We'll confirm your booking within 2 hours</p>

          <form id="reservationForm" onsubmit="submitReservation(event)" novalidate>
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label-light" for="name">Full Name *</label>
                <input type="text" class="form-input-dark form-input" id="name" name="name" placeholder="Ahmed Khan" required>
                <div class="form-error" id="nameError"></div>
              </div>
              <div class="col-md-6">
                <label class="form-label-light" for="phone">Phone Number *</label>
                <input type="tel" class="form-input-dark form-input" id="phone" name="phone" placeholder="0300-1234567" required>
                <div class="form-error" id="phoneError"></div>
              </div>
              <div class="col-12">
                <label class="form-label-light" for="email">Email Address *</label>
                <input type="email" class="form-input-dark form-input" id="email" name="email" placeholder="you@example.com" required>
                <div class="form-error" id="emailError"></div>
              </div>
              <div class="col-md-6">
                <label class="form-label-light" for="resDate">Date *</label>
                <input type="date" class="form-input-dark form-input" id="resDate" name="date" required>
                <div class="form-error" id="dateError"></div>
              </div>
              <div class="col-md-3">
                <label class="form-label-light" for="time">Time *</label>
                <select class="form-input-dark form-input" id="time" name="time" required>
                  <option value="">Select...</option>
                  <?php
                  $times = ['12:00','12:30','13:00','13:30','14:00','14:30','15:00','15:30','16:00','16:30','17:00','17:30','18:00','18:30','19:00','19:30','20:00','20:30','21:00','21:30','22:00','22:30','23:00','23:30'];
                  foreach ($times as $t) echo "<option value='$t'>$t</option>";
                  ?>
                </select>
                <div class="form-error" id="timeError"></div>
              </div>
              <div class="col-md-3">
                <label class="form-label-light" for="guests">Guests *</label>
                <select class="form-input-dark form-input" id="guests" name="guests" required>
                  <?php for ($i=1;$i<=20;$i++) echo "<option value='$i'" . ($i==2?' selected':'') . ">$i Person" . ($i>1?'s':'') . "</option>"; ?>
                </select>
                <div class="form-error" id="guestsError"></div>
              </div>
              <div class="col-md-6">
                <label class="form-label-light" for="occasion">Occasion (optional)</label>
                <select class="form-input-dark form-input" id="occasion" name="occasion">
                  <option value="">Select occasion...</option>
                  <option>Birthday</option>
                  <option>Anniversary</option>
                  <option>Business Meeting</option>
                  <option>Family Gathering</option>
                  <option>Engagement</option>
                  <option>Other</option>
                </select>
              </div>
              <div class="col-md-6">
                <label class="form-label-light" for="special_requests">Special Requests</label>
                <input type="text" class="form-input-dark form-input" id="special_requests" name="special_requests" placeholder="Wheelchair access, high chair...">
              </div>
            </div>
            <button type="submit" class="btn-primary-gold" style="width:100%;margin-top:24px;justify-content:center">
              <i class="bi bi-calendar2-check"></i> Book My Table
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>

<?php include 'includes/footer.php'; ?>
<?php include 'includes/cart.php'; ?>
<?php include 'includes/checkout.php'; ?>

<script src="assets/js/main.js"></script>
<script>
// Fix: min date for reservation input
const rd = document.getElementById('resDate');
if(rd) rd.min = new Date().toISOString().split('T')[0];
</script>
</body>
</html>