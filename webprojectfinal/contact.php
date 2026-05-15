<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Contact – Dastarkhan Restaurant</title>
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
    <a href="blog.php">Blog</a>
    <a href="contact.php" class="active">Contact</a>
    <a href="admin/login.php" style="color:var(--gold-light)!important">⚙️ Admin</a>
    <a href="#" class="nav-cart-btn" onclick="openCart();return false;">
      🛒 Cart <span class="cart-count" style="display:none">0</span>
    </a>
  </div>
  <button class="nav-hamburger" id="hamburger"><span></span><span></span><span></span></button>
</nav>

<div class="page-hero">
  <div class="section-tag" style="justify-content:center">Get In Touch</div>
  <h1>Contact <span style="color:var(--gold);font-style:italic">Us</span></h1>
  <p>We'd love to hear from you — reservations, catering, or just to say salaam!</p>
</div>

<section class="section">
  <div class="container-xl">
    <div class="row g-5">

      <!-- Contact Info -->
      <div class="col-lg-4 fade-up">
        <div class="contact-info-card">
          <h3 style="font-family:var(--font-display);font-size:1.5rem;font-weight:700;color:#fff;margin-bottom:6px">Let's Connect</h3>
          <p style="color:rgba(255,255,255,.4);font-size:.85rem;margin-bottom:32px">We respond within 24 hours on all channels.</p>

          <div class="contact-info-item">
            <div class="contact-info-icon">📍</div>
            <div>
              <div style="color:#fff;font-weight:600;margin-bottom:4px">Our Location</div>
              <div style="color:rgba(255,255,255,.5);font-size:.875rem;line-height:1.6">12 Food Street, Anarkali<br>Lahore, Punjab – Pakistan</div>
            </div>
          </div>

          <div class="contact-info-item">
            <div class="contact-info-icon">📞</div>
            <div>
              <div style="color:#fff;font-weight:600;margin-bottom:4px">Phone</div>
              <div style="color:rgba(255,255,255,.5);font-size:.875rem">+92 42 3576 8900<br>+92 300 1234567</div>
            </div>
          </div>

          <div class="contact-info-item">
            <div class="contact-info-icon">✉️</div>
            <div>
              <div style="color:#fff;font-weight:600;margin-bottom:4px">Email</div>
              <div style="color:rgba(255,255,255,.5);font-size:.875rem">info@dastarkhan.pk<br>reservations@dastarkhan.pk</div>
            </div>
          </div>

          <div class="contact-info-item" style="margin-bottom:0">
            <div class="contact-info-icon">🕐</div>
            <div>
              <div style="color:#fff;font-weight:600;margin-bottom:4px">Opening Hours</div>
              <div style="color:rgba(255,255,255,.5);font-size:.875rem">Mon – Sun: 12:00 PM – 12:00 AM<br>Closed on Eid-ul-Fitr Day 1</div>
            </div>
          </div>

          <div style="margin-top:28px;padding-top:24px;border-top:1px solid rgba(255,255,255,.1)">
            <div style="color:rgba(255,255,255,.4);font-size:.75rem;text-transform:uppercase;letter-spacing:1.5px;margin-bottom:14px">Follow Us</div>
            <div class="social-links">
              <a href="#" class="social-link"><i class="bi bi-facebook"></i></a>
              <a href="#" class="social-link"><i class="bi bi-instagram"></i></a>
              <a href="#" class="social-link"><i class="bi bi-twitter-x"></i></a>
              <a href="#" class="social-link"><i class="bi bi-youtube"></i></a>
            </div>
          </div>
        </div>
      </div>

      <!-- Contact Form -->
      <div class="col-lg-8 fade-up">
        <div style="background:#fff;border-radius:24px;padding:40px;box-shadow:var(--shadow-sm);border:1px solid rgba(0,0,0,.05)">
          <h3 style="font-family:var(--font-display);font-size:1.5rem;font-weight:700;margin-bottom:6px">Send Us a Message</h3>
          <p style="color:var(--text-muted);font-size:.875rem;margin-bottom:28px">Fill out the form and we'll get back to you shortly.</p>

          <form id="contactFormEl" onsubmit="submitContact(event)" novalidate>
            <div class="row g-3">
              <div class="col-md-6">
                <div class="form-group">
                  <label class="form-label" for="contactName">Full Name *</label>
                  <input type="text" class="form-input" id="contactName" name="name" placeholder="Ahmed Khan" required>
                  <div class="form-error" id="contactNameError"></div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="form-label" for="contactEmail">Email Address *</label>
                  <input type="email" class="form-input" id="contactEmail" name="email" placeholder="ahmed@example.com" required>
                  <div class="form-error" id="contactEmailError"></div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="form-label" for="contactPhone">Phone (optional)</label>
                  <input type="tel" class="form-input" id="contactPhone" name="phone" placeholder="0300-1234567">
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="form-label" for="contactSubject">Subject</label>
                  <select class="form-input" id="contactSubject" name="subject">
                    <option value="">Select subject...</option>
                    <option>General Inquiry</option>
                    <option>Catering / Event</option>
                    <option>Feedback</option>
                    <option>Reservation Help</option>
                    <option>Other</option>
                  </select>
                </div>
              </div>
              <div class="col-12">
                <div class="form-group">
                  <label class="form-label" for="contactMessage">Message *</label>
                  <textarea class="form-input" id="contactMessage" name="message" rows="5" placeholder="Write your message here..." required style="resize:vertical"></textarea>
                  <div class="form-error" id="contactMessageError"></div>
                </div>
              </div>
              <div class="col-12">
                <button type="submit" class="btn-primary-gold" style="width:100%;justify-content:center">
                  <i class="bi bi-send-fill"></i> Send Message
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Map Placeholder -->
    <div class="mt-5 fade-up">
      <div style="background:var(--cream);border-radius:20px;overflow:hidden;height:300px;display:flex;align-items:center;justify-content:center;border:2px dashed var(--border)">
        <div style="text-align:center;color:var(--text-muted)">
          <div style="font-size:3rem;margin-bottom:12px">📍</div>
          <div style="font-weight:600;font-size:1.1rem">12 Food Street, Anarkali, Lahore</div>
          <a href="https://maps.google.com/?q=Food+Street+Lahore" target="_blank" class="btn-primary-gold" style="margin-top:16px;text-decoration:none;font-size:.875rem;padding:10px 24px;display:inline-flex">
            Open in Google Maps ↗
          </a>
        </div>
      </div>
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