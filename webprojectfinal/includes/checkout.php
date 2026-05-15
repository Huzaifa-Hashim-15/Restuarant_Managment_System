<!-- ===== CHECKOUT MODAL ===== -->
<div class="checkout-modal" id="checkoutModal">
  <div class="checkout-modal-bg" onclick="closeCheckout()"></div>
  <div class="checkout-box">
    <div class="checkout-header">
      <div style="display:flex;align-items:center;justify-content:space-between">
        <h3>Place Your Order</h3>
        <button onclick="closeCheckout()" style="background:rgba(255,255,255,.1);border:none;color:#fff;width:36px;height:36px;border-radius:50%;cursor:pointer;font-size:1.1rem">✕</button>
      </div>
      <p style="color:rgba(255,255,255,.6);font-size:.85rem;margin-top:8px;margin-bottom:0">Complete your details below</p>
    </div>
    <div class="checkout-body">
      <!-- Order Type -->
      <div class="form-group">
        <label class="form-label">Order Type</label>
        <div class="order-type-grid">
          <button class="order-type-btn active" data-type="dine-in">
            <div class="icon">🪑</div><div class="label">Dine-In</div>
          </button>
          <button class="order-type-btn" data-type="takeaway">
            <div class="icon">📦</div><div class="label">Takeaway</div>
          </button>
          <button class="order-type-btn" data-type="delivery">
            <div class="icon">🚀</div><div class="label">Delivery</div>
          </button>
        </div>
      </div>

      <!-- Customer Details -->
      <div class="row g-3">
        <div class="col-12">
          <div class="form-group">
            <label class="form-label" for="cusName">Full Name *</label>
            <input type="text" class="form-input" id="cusName" placeholder="Ahmed Khan">
            <div class="form-error" id="cusNameError"></div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label class="form-label" for="cusPhone">Phone Number *</label>
            <input type="tel" class="form-input" id="cusPhone" placeholder="0300-1234567">
            <div class="form-error" id="cusPhoneError"></div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label class="form-label" for="cusEmail">Email (optional)</label>
            <input type="email" class="form-input" id="cusEmail" placeholder="you@email.com">
          </div>
        </div>
        <div class="col-12" id="addressGroup" style="display:none">
          <div class="form-group">
            <label class="form-label" for="cusAddress">Delivery Address *</label>
            <textarea class="form-input" id="cusAddress" rows="2" placeholder="House #, Street, Area, City"></textarea>
            <div class="form-error" id="cusAddressError"></div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label class="form-label" for="cusPayment">Payment Method</label>
            <select class="form-input" id="cusPayment">
              <option value="cash">💵 Cash on Delivery/Arrival</option>
              <option value="card">💳 Card</option>
              <option value="online">📱 Online Transfer</option>
            </select>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label class="form-label" for="cusNotes">Special Requests</label>
            <input type="text" class="form-input" id="cusNotes" placeholder="Extra spicy, no onions...">
          </div>
        </div>
      </div>

      <!-- Order Summary -->
      <div style="background:var(--cream);border-radius:14px;padding:18px;margin-top:8px">
        <h6 style="font-weight:700;margin-bottom:14px;font-size:.9rem">Order Summary</h6>
        <div id="checkoutItemsList"></div>
      </div>
    </div>
    <div style="padding:0 32px 28px">
      <button class="btn-checkout" id="placeOrderBtn" onclick="placeOrder()">
        Place Order →
      </button>
    </div>
  </div>
</div>

<!-- ===== ORDER SUCCESS MODAL ===== -->
<div class="checkout-modal" id="orderSuccessModal">
  <div class="checkout-modal-bg" onclick="document.getElementById('orderSuccessModal').classList.remove('open');document.body.style.overflow=''"></div>
  <div class="checkout-box" style="max-width:420px;text-align:center">
    <div style="padding:50px 32px">
      <div style="font-size:4rem;margin-bottom:16px">🎉</div>
      <h3 style="font-family:var(--font-display);font-size:1.7rem;font-weight:900;margin-bottom:10px;color:var(--dark)">Order Placed!</h3>
      <p id="successMsg" style="color:var(--text-muted);margin-bottom:16px;line-height:1.6"></p>
      <div style="background:var(--cream);border-radius:12px;padding:16px;margin-bottom:24px">
        <div style="font-size:.8rem;color:var(--text-muted);margin-bottom:4px">Your Order ID</div>
        <div id="successOrderId" style="font-family:var(--font-display);font-size:1.8rem;font-weight:900;color:var(--gold)"></div>
      </div>
      <button class="btn-primary-gold" style="width:100%" onclick="document.getElementById('orderSuccessModal').classList.remove('open');document.body.style.overflow=''">
        Continue Exploring 🍽️
      </button>
    </div>
  </div>
</div>
