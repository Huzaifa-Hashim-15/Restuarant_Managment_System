const API = 'api'; // relative path from root
 
// ===== CART STATE =====
let cart = JSON.parse(localStorage.getItem('dastarkhan_cart') || '[]');
 
function saveCart() {
  localStorage.setItem('dastarkhan_cart', JSON.stringify(cart));
  updateCartUI();
}
 
function addToCart(item) {
  const existing = cart.find(c => c.id === item.id);
  if (existing) {
    existing.qty += 1;
    showToast(`Added another ${item.name}`, 'success');
  } else {
    cart.push({ ...item, qty: 1 });
    showToast(`${item.name} added to cart!`, 'success');
  }
  saveCart();
  animateCartButton();
}
 
function removeFromCart(id) {
  cart = cart.filter(c => c.id !== id);
  saveCart();
}
 
function updateQty(id, delta) {
  const item = cart.find(c => c.id === id);
  if (!item) return;
  item.qty = Math.max(1, item.qty + delta);
  saveCart();
}
 
function clearCart() {
  cart = [];
  saveCart();
}
 
function getCartSubtotal() {
  return cart.reduce((sum, i) => sum + i.price * i.qty, 0);
}
 
function getCartCount() {
  return cart.reduce((sum, i) => sum + i.qty, 0);
}
 
function updateCartUI() {
  const count = getCartCount();
 
  // Update all cart count badges
  document.querySelectorAll('.cart-count').forEach(el => {
    el.textContent = count;
    el.style.display = count > 0 ? 'flex' : 'none';
  });
 
  // Render cart sidebar items
  const cartItemsEl = document.getElementById('cartItems');
  const cartEmptyEl = document.getElementById('cartEmpty');
  const cartFooterEl = document.getElementById('cartFooter');
 
  if (!cartItemsEl) return;
 
  if (cart.length === 0) {
    cartItemsEl.innerHTML = '';
    if (cartEmptyEl) cartEmptyEl.style.display = 'flex';
    if (cartFooterEl) cartFooterEl.style.display = 'none';
    return;
  }
 
  if (cartEmptyEl) cartEmptyEl.style.display = 'none';
  if (cartFooterEl) cartFooterEl.style.display = 'block';
 
  cartItemsEl.innerHTML = cart.map(item => `
    <div class="cart-item" id="cart-item-${item.id}">
      <img src="${item.image}" alt="${item.name}" class="cart-item-img"
           onerror="this.src='https://images.unsplash.com/photo-1565557623262-b51c2513a641?w=200&q=60'">
      <div class="cart-item-info">
        <div class="cart-item-name">${item.name}</div>
        <div class="cart-item-price">Rs ${(item.price * item.qty).toLocaleString()}</div>
        <div class="cart-qty-controls">
          <button class="qty-btn" onclick="updateQty(${item.id},-1)">−</button>
          <span class="qty-num">${item.qty}</span>
          <button class="qty-btn" onclick="updateQty(${item.id},1)">+</button>
        </div>
      </div>
      <button class="cart-remove" onclick="removeFromCart(${item.id})" title="Remove">✕</button>
    </div>
  `).join('');
 
  // Update totals
  const subtotal  = getCartSubtotal();
  const tax       = subtotal * 0.09;
  const orderType = document.querySelector('.order-type-btn.active')?.dataset.type || 'dine-in';
  const delivery  = orderType === 'delivery' ? 100 : 0;
  const total     = subtotal + tax + delivery;
 
  setEl('cartSubtotal',   'Rs ' + subtotal.toLocaleString('en-PK', { minimumFractionDigits: 0 }));
  setEl('cartTax',        'Rs ' + Math.round(tax).toLocaleString());
  setEl('cartDelivery',   delivery ? 'Rs 100' : 'Free');
  setEl('cartTotal',      'Rs ' + Math.round(total).toLocaleString());
}
 
function animateCartButton() {
  const btn = document.querySelector('.nav-cart-btn');
  if (btn) {
    btn.style.transform = 'scale(1.15)';
    setTimeout(() => btn.style.transform = '', 200);
  }
}
 
// ===== CART SIDEBAR =====
function openCart() {
  document.getElementById('cartOverlay')?.classList.add('open');
  document.getElementById('cartSidebar')?.classList.add('open');
  document.body.style.overflow = 'hidden';
}
 
function closeCart() {
  document.getElementById('cartOverlay')?.classList.remove('open');
  document.getElementById('cartSidebar')?.classList.remove('open');
  document.body.style.overflow = '';
}
 
// ===== CHECKOUT MODAL =====
function openCheckout() {
  if (cart.length === 0) { showToast('Your cart is empty!', 'warning'); return; }
  closeCart();
  document.getElementById('checkoutModal')?.classList.add('open');
  document.body.style.overflow = 'hidden';
  renderCheckoutSummary();
}
 
function closeCheckout() {
  document.getElementById('checkoutModal')?.classList.remove('open');
  document.body.style.overflow = '';
}
 
function renderCheckoutSummary() {
  const el = document.getElementById('checkoutItemsList');
  if (!el) return;
  const subtotal = getCartSubtotal();
  const tax      = Math.round(subtotal * 0.09);
  const type     = document.querySelector('.order-type-btn.active')?.dataset.type || 'dine-in';
  const delivery = type === 'delivery' ? 100 : 0;
  el.innerHTML = cart.map(i => `
    <div style="display:flex;justify-content:space-between;align-items:center;font-size:.85rem;margin-bottom:8px">
      <span>${i.name} × ${i.qty}</span>
      <span style="font-weight:700">Rs ${(i.price*i.qty).toLocaleString()}</span>
    </div>`).join('') + `
    <div style="border-top:1px dashed #ddd;margin:12px 0"></div>
    <div style="display:flex;justify-content:space-between;font-size:.85rem;margin-bottom:4px">
      <span style="color:#888">Tax (9%)</span><span>Rs ${tax.toLocaleString()}</span>
    </div>
    ${delivery?`<div style="display:flex;justify-content:space-between;font-size:.85rem;margin-bottom:4px"><span style="color:#888">Delivery</span><span>Rs 100</span></div>`:''}
    <div style="display:flex;justify-content:space-between;font-weight:800;font-size:1rem;margin-top:8px">
      <span>Total</span><span style="color:#c9a84c">Rs ${(subtotal+tax+delivery).toLocaleString()}</span>
    </div>`;
}
 
async function placeOrder() {
  const name    = document.getElementById('cusName')?.value.trim();
  const phone   = document.getElementById('cusPhone')?.value.trim();
  const email   = document.getElementById('cusEmail')?.value.trim();
  const type    = document.querySelector('.order-type-btn.active')?.dataset.type || 'dine-in';
  const address = document.getElementById('cusAddress')?.value.trim();
  const notes   = document.getElementById('cusNotes')?.value.trim();
  const payment = document.getElementById('cusPayment')?.value || 'cash';
 
  let valid = true;
  if (!name)  { showFieldError('cusName',  'Name is required'); valid = false; }
  if (!phone) { showFieldError('cusPhone', 'Phone is required'); valid = false; }
  if (type === 'delivery' && !address) { showFieldError('cusAddress', 'Address required for delivery'); valid = false; }
  if (!valid) return;
 
  const btn = document.getElementById('placeOrderBtn');
  btn.disabled = true;
  btn.innerHTML = '<span class="spinner"></span> Placing Order...';
 
  try {
    const res = await fetch(`${API}/orders.php`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ customer_name: name, customer_phone: phone, customer_email: email, order_type: type, delivery_address: address, items: cart, notes, payment_method: payment })
    });
    const data = await res.json();
 
    if (data.success) {
      closeCheckout();
      clearCart();
      showSuccessScreen(data.message, data.order_id);
    } else {
      showToast(data.message || 'Failed to place order', 'error');
    }
  } catch (e) {
    showToast('Network error. Please try again.', 'error');
  } finally {
    btn.disabled = false;
    btn.textContent = 'Place Order';
  }
}
 
function showSuccessScreen(msg, orderId) {
  const el = document.getElementById('orderSuccessModal');
  if (el) {
    document.getElementById('successMsg').textContent   = msg;
    document.getElementById('successOrderId').textContent = '#' + String(orderId).padStart(4, '0');
    el.classList.add('open');
  } else {
    showToast(msg, 'success');
  }
}
 
// ===== MENU LOAD =====
async function loadMenuItems(categoryId = '', search = '') {
  const grid = document.getElementById('menuGrid');
  if (!grid) return;
  grid.innerHTML = `<div class="page-loader col-12"><div class="spinner" style="width:40px;height:40px;border-width:4px"></div></div>`;
 
  try {
    let url = `${API}/menu.php?`;
    if (categoryId) url += `category=${categoryId}&`;
    if (search) url += `search=${encodeURIComponent(search)}&`;
    const res  = await fetch(url);
    const data = await res.json();
 
    if (!data.success || !data.data.length) {
      grid.innerHTML = `<div class="col-12 text-center py-5"><div style="font-size:3rem;opacity:.3">🍽️</div><p style="color:#888;margin-top:12px">No items found</p></div>`;
      return;
    }
 
    grid.innerHTML = data.data.map(item => buildMenuCard(item)).join('');
    observeFadeUp();
  } catch (e) {
    grid.innerHTML = `<div class="col-12 text-center py-5" style="color:red">Failed to load menu. Is the server running?</div>`;
  }
}
 
async function loadFeaturedItems() {
  const grid = document.getElementById('featuredGrid');
  if (!grid) return;
  try {
    const res  = await fetch(`${API}/menu.php?action=featured`);
    const data = await res.json();
    if (data.success) {
      grid.innerHTML = data.data.map(item => buildMenuCard(item)).join('');
      observeFadeUp();
    }
  } catch (e) {}
}
 
async function loadCategories() {
  const filter = document.getElementById('catFilter');
  if (!filter) return;
  try {
    const res  = await fetch(`${API}/menu.php?action=categories`);
    const data = await res.json();
    if (data.success) {
      const select = document.getElementById('menuCatSelect');
      data.data.forEach(cat => {
        const btn = document.createElement('button');
        btn.className = 'cat-btn fade-up';
        btn.innerHTML = `${cat.icon} ${cat.name} <small style="opacity:.6">(${cat.item_count})</small>`;
        btn.dataset.id = cat.id;
        btn.addEventListener('click', () => {
          document.querySelectorAll('.cat-btn').forEach(b => b.classList.remove('active'));
          btn.classList.add('active');
          currentCategory = cat.id;
          loadMenuItems(cat.id, currentSearch);
        });
        filter.appendChild(btn);
 
        if (select) {
          const opt = document.createElement('option');
          opt.value = cat.id;
          opt.textContent = cat.name;
          select.appendChild(opt);
        }
      });
    }
  } catch (e) {}
}
 
function buildMenuCard(item) {
  const spiceMap = { 'Mild': 'spice-mild', 'Medium': 'spice-medium', 'Hot': 'spice-hot', 'Extra Hot': 'spice-extra' };
  const spiceClass = spiceMap[item.spice_level] || 'spice-medium';
  // Safely encode item data into data-attributes — no inline onclick escaping hell
  const safeName  = item.name.replace(/'/g, '&apos;').replace(/"/g, '&quot;');
  const safeImage = (item.image_url || '').replace(/'/g, '').replace(/"/g, '');
  return `
    <div class="col-lg-3 col-md-4 col-sm-6 fade-up">
      <div class="menu-card">
        <div class="menu-card-img-wrap">
          <img src="${item.image_url}" alt="${safeName}" class="menu-card-img" loading="lazy"
               onerror="this.src='https://images.unsplash.com/photo-1565557623262-b51c2513a641?w=400&q=60'">
          ${item.is_featured ? '<div class="menu-card-badge">⭐ Featured</div>' : ''}
          <div class="menu-card-spice">
            <span class="${spiceClass}" style="padding:2px 6px;border-radius:10px;font-size:.65rem;font-weight:700">${item.spice_level}</span>
          </div>
        </div>
        <div class="menu-card-body">
          <div class="menu-card-cat">${item.category_name || ''}</div>
          <div class="menu-card-name">${item.name}</div>
          <div class="menu-card-desc">${item.description || ''}</div>
          <div class="menu-card-footer">
            <div class="menu-card-price">Rs ${parseFloat(item.price).toLocaleString()}<span>/serving</span></div>
            <button class="btn-add-cart"
              data-id="${item.id}"
              data-name="${safeName}"
              data-price="${item.price}"
              data-image="${safeImage}">
              <span class="cart-icon">🛒</span> Add
            </button>
          </div>
        </div>
      </div>
    </div>`;
}
 
// ===== RESERVATION FORM =====
async function submitReservation(e) {
  e.preventDefault();
  const form  = e.target;
  const data  = Object.fromEntries(new FormData(form));
  const btn   = form.querySelector('[type=submit]');
 
  if (!validateResForm(data)) return;
 
  btn.disabled = true;
  btn.innerHTML = '<span class="spinner"></span> Booking...';
 
  try {
    const res  = await fetch(`${API}/reservations.php`, { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(data) });
    const resp = await res.json();
    if (resp.success) {
      form.reset();
      showToast(resp.message, 'success');
    } else {
      showToast(resp.message || 'Booking failed', 'error');
    }
  } catch { showToast('Network error', 'error'); }
  finally {
    btn.disabled = false;
    btn.textContent = 'Book My Table';
  }
}
 
function validateResForm(data) {
  let ok = true;
  const req = ['name','email','phone','date','time','guests'];
  req.forEach(f => {
    if (!data[f] || data[f].trim() === '') { showFieldError(f, 'This field is required'); ok = false; }
  });
  if (data.email && !/\S+@\S+\.\S+/.test(data.email)) { showFieldError('email', 'Invalid email'); ok = false; }
  return ok;
}
 
// ===== CONTACT FORM =====
async function submitContact(e) {
  e.preventDefault();
  const form = e.target;
  const data = Object.fromEntries(new FormData(form));
  const btn  = form.querySelector('[type=submit]');
 
  if (!data.name?.trim()) { showFieldError('contactName', 'Name required'); return; }
  if (!/\S+@\S+\.\S+/.test(data.email)) { showFieldError('contactEmail', 'Valid email required'); return; }
  if (!data.message?.trim()) { showFieldError('contactMessage', 'Message required'); return; }
 
  btn.disabled = true;
  btn.innerHTML = '<span class="spinner"></span> Sending...';
 
  try {
    const res  = await fetch(`${API}/contact.php`, { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(data) });
    const resp = await res.json();
    if (resp.success) { form.reset(); showToast(resp.message, 'success'); }
    else showToast(resp.message, 'error');
  } catch { showToast('Network error', 'error'); }
  finally { btn.disabled = false; btn.textContent = 'Send Message'; }
}
 
// ===== NAVBAR =====
function initNavbar() {
  const navbar = document.querySelector('.navbar');
  if (!navbar) return;
 
  const onScroll = () => {
    if (window.scrollY > 60) { navbar.classList.add('scrolled'); navbar.classList.remove('transparent'); }
    else { navbar.classList.remove('scrolled'); if (navbar.dataset.transparent === 'true') navbar.classList.add('transparent'); }
  };
  window.addEventListener('scroll', onScroll, { passive: true });
  onScroll();
 
  // Hamburger
  const ham = document.getElementById('hamburger');
  const menu = document.querySelector('.nav-menu');
  if (ham && menu) {
    ham.addEventListener('click', () => {
      menu.classList.toggle('open');
      document.body.style.overflow = menu.classList.contains('open') ? 'hidden' : '';
    });
    menu.querySelectorAll('a').forEach(a => a.addEventListener('click', () => {
      menu.classList.remove('open');
      document.body.style.overflow = '';
    }));
  }
}
 
// ===== ANIMATIONS =====
function observeFadeUp() {
  const io = new IntersectionObserver((entries) => {
    entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('visible'); io.unobserve(e.target); } });
  }, { threshold: 0.1 });
  document.querySelectorAll('.fade-up:not(.visible)').forEach(el => io.observe(el));
}
 
// ===== TOAST =====
function showToast(message, type = 'success') {
  let container = document.getElementById('toastContainer');
  if (!container) {
    container = document.createElement('div');
    container.id = 'toastContainer';
    container.className = 'toast-container';
    document.body.appendChild(container);
  }
  const icons = { success: '✓', error: '✕', warning: '⚠', info: 'ℹ' };
  const toast = document.createElement('div');
  toast.className = `toast-item ${type}`;
  toast.innerHTML = `<span style="font-size:1rem">${icons[type]||'•'}</span><span>${message}</span>`;
  container.appendChild(toast);
  setTimeout(() => {
    toast.style.animation = 'slideOut .3s ease forwards';
    setTimeout(() => toast.remove(), 300);
  }, 3500);
}
 
// ===== FIELD ERROR =====
function showFieldError(id, msg) {
  const el  = document.getElementById(id);
  const err = document.getElementById(id + 'Error');
  if (el)  { el.classList.add('error'); el.addEventListener('input', () => { el.classList.remove('error'); if (err) err.classList.remove('show'); }, { once: true }); }
  if (err) { err.textContent = msg; err.classList.add('show'); }
  if (el)  el.focus();
}
 
// ===== HELPERS =====
function setEl(id, val) {
  const el = document.getElementById(id);
  if (el) el.textContent = val;
}
 
// ===== SEARCH DEBOUNCE =====
let currentCategory = '';
let currentSearch   = '';
let searchTimer     = null;
 
function handleMenuSearch(val) {
  currentSearch = val;
  clearTimeout(searchTimer);
  searchTimer = setTimeout(() => loadMenuItems(currentCategory, val), 400);
}
 
// ===== MIN DATE FOR RESERVATION =====
function setMinDate() {
  const dateInput = document.querySelector('input[name="date"], input[type="date"]#resDate');
  if (dateInput) {
    const today = new Date().toISOString().split('T')[0];
    dateInput.min = today;
  }
}
 
// ===== EVENT DELEGATION FOR DYNAMIC ADD-TO-CART BUTTONS =====
// Must use delegation because cards are injected after page load
document.addEventListener('click', function(e) {
  const btn = e.target.closest('.btn-add-cart');
  if (!btn) return;
 
  const id    = parseInt(btn.dataset.id);
  const name  = btn.dataset.name;
  const price = parseFloat(btn.dataset.price);
  const image = btn.dataset.image;
 
  if (!id || !name || !price) return;
 
  addToCart({ id, name, price, image });
 
  // Visual feedback
  btn.innerHTML = '✓ Added';
  btn.classList.add('added');
  btn.disabled = true;
  setTimeout(() => {
    btn.innerHTML = '<span class="cart-icon">🛒</span> Add';
    btn.classList.remove('added');
    btn.disabled = false;
  }, 2000);
});
 
// ===== INIT =====
document.addEventListener('DOMContentLoaded', () => {
  initNavbar();
  updateCartUI();
  observeFadeUp();
  setMinDate();
 
  // Smooth scroll for anchor links
  document.querySelectorAll('a[href^="#"]').forEach(a => {
    a.addEventListener('click', e => {
      const target = document.querySelector(a.getAttribute('href'));
      if (target) { e.preventDefault(); target.scrollIntoView({ behavior: 'smooth', block: 'start' }); }
    });
  });
 
  // Order type selection
  document.querySelectorAll('.order-type-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      document.querySelectorAll('.order-type-btn').forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
      const addressGroup = document.getElementById('addressGroup');
      if (addressGroup) {
        addressGroup.style.display = btn.dataset.type === 'delivery' ? 'block' : 'none';
      }
      updateCartUI();
      renderCheckoutSummary();
    });
  });
});