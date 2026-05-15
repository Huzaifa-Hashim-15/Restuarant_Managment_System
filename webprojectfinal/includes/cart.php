<!-- ===== CART OVERLAY + SIDEBAR ===== -->
<div class="cart-overlay" id="cartOverlay" onclick="closeCart()"></div>
<div class="cart-sidebar" id="cartSidebar">
  <div class="cart-header">
    <h3>🛒 Your Cart</h3>
    <button class="cart-close" onclick="closeCart()">✕</button>
  </div>
  <div class="cart-items" id="cartItems">
    <!-- items rendered by JS -->
  </div>
  <div class="cart-empty" id="cartEmpty" style="display:flex">
    <div class="cart-empty-icon">🛒</div>
    <p style="font-weight:600;color:#888">Your cart is empty</p>
    <p style="font-size:.82rem;color:#bbb">Add delicious items from our menu</p>
    <a href="menu.php" onclick="closeCart()" class="btn-primary-gold" style="margin-top:12px;text-decoration:none;font-size:.875rem;padding:10px 24px">Browse Menu</a>
  </div>
  <div class="cart-footer" id="cartFooter" style="display:none">
    <div class="cart-summary">
      <div class="cart-row"><span>Subtotal</span><span id="cartSubtotal">Rs 0</span></div>
      <div class="cart-row"><span>Tax (9%)</span><span id="cartTax">Rs 0</span></div>
      <div class="cart-row"><span>Delivery</span><span id="cartDelivery">Free</span></div>
      <div class="cart-row total"><span>Total</span><span id="cartTotal">Rs 0</span></div>
    </div>
    <button class="btn-checkout" onclick="openCheckout()">Proceed to Checkout →</button>
    <button onclick="closeCart();window.location='menu.php'" style="width:100%;background:none;border:none;color:var(--text-muted);font-size:.82rem;margin-top:10px;cursor:pointer;padding:4px">Continue Shopping</button>
  </div>
</div>
