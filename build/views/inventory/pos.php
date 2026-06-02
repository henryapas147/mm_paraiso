<?php if (false) {
} ?>
<style>
  @import url('https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;1,9..40,300&family=DM+Mono:wght@400;500&display=swap');

  :root {
    --pos-blue: #1A6BBF;
    --pos-blue-dark: #0F4C8A;
    --pos-blue-pale: #EBF3FC;
    --pos-green: #2E7D32;
    --pos-green-pale: #E8F5E9;
    --pos-red: #C62828;
    --pos-red-pale: #FFEBEE;
    --pos-amber: #F59E0B;
    --pos-amber-pale: #FEF3C7;
    --pos-purple: #7C3AED;
    --pos-purple-pale: #EDE9FE;
    --pos-gray: #64748B;
    --pos-dark: #1E293B;
    --pos-radius: 12px;
    --pos-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    --pos-shadow-lg: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    --pos-font: 'DM Sans', sans-serif;
    --pos-mono: 'DM Mono', monospace;
  }

  * {
    box-sizing: border-box;
  }

  .pos-container {
    display: grid;
    grid-template-columns: 1fr 420px;
    gap: 16px;
    padding: 16px;
    min-height: calc(100vh - 80px);
    max-width: 100%;
    margin: 0;
    font-family: var(--pos-font);
  }

  @media (max-width: 1200px) {
    .pos-container {
      grid-template-columns: 1fr;
    }
  }

  /* Fullscreen optimization */
  body {
    overflow-x: hidden;
  }

  main {
    width: 100% !important;
    max-width: 100% !important;
    margin-left: 0 !important;
  }

  /* Left Panel - Products */
  .pos-products-panel {
    background: #fff;
    border-radius: var(--pos-radius);
    box-shadow: var(--pos-shadow);
    display: flex;
    flex-direction: column;
    overflow: hidden;
  }

  .pos-back-btn {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    background: #F1F5F9;
    color: var(--pos-gray);
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    transition: all 0.2s;
  }

  .pos-back-btn:hover {
    background: var(--pos-blue);
    color: #fff;
  }

  .pos-header {
    padding: 20px 24px;
    border-bottom: 1px solid #E2E8F0;
    display: flex;
    align-items: center;
    gap: 16px;
    flex-wrap: wrap;
  }

  .pos-title {
    font-size: 20px;
    font-weight: 600;
    color: var(--pos-dark);
    margin: 0;
  }

  .pos-search-wrap {
    flex: 1;
    min-width: 280px;
    position: relative;
  }

  .pos-search {
    width: 100%;
    padding: 12px 16px 12px 44px;
    font-size: 14px;
    border: 2px solid #E2E8F0;
    border-radius: 10px;
    outline: none;
    transition: all 0.2s;
    font-family: var(--pos-font);
  }

  .pos-search:focus {
    border-color: var(--pos-blue);
    box-shadow: 0 0 0 3px rgba(26, 107, 191, 0.1);
  }

  .pos-search-icon {
    position: absolute;
    left: 16px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--pos-gray);
    font-size: 16px;
  }

  .pos-category-filter {
    display: flex;
    gap: 8px;
    padding: 16px 24px;
    overflow-x: auto;
    border-bottom: 1px solid #E2E8F0;
  }

  .pos-cat-btn {
    padding: 8px 16px;
    font-size: 13px;
    font-weight: 500;
    border: 1px solid #E2E8F0;
    border-radius: 20px;
    background: #fff;
    color: var(--pos-gray);
    cursor: pointer;
    white-space: nowrap;
    transition: all 0.2s;
  }

  .pos-cat-btn:hover {
    border-color: var(--pos-blue);
    color: var(--pos-blue);
  }

  .pos-cat-btn.active {
    background: var(--pos-blue);
    color: #fff;
    border-color: var(--pos-blue);
  }

  .pos-products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
    gap: 16px;
    padding: 20px 24px;
    overflow-y: auto;
    max-height: calc(100vh - 280px);
  }

  .pos-product-card {
    background: #fff;
    border: 1px solid #E2E8F0;
    border-radius: 12px;
    padding: 16px;
    cursor: pointer;
    transition: all 0.2s;
    text-align: center;
    position: relative;
  }

  .pos-product-card:hover {
    border-color: var(--pos-blue);
    box-shadow: var(--pos-shadow);
    transform: translateY(-2px);
  }

  .pos-product-card:active {
    transform: scale(0.98);
  }

  .pos-product-card.low-stock {
    border-color: var(--pos-amber);
    background: var(--pos-amber-pale);
  }

  .pos-product-card.out-of-stock {
    opacity: 0.5;
    cursor: not-allowed;
    pointer-events: none;
  }

  .pos-product-img {
    width: 80px;
    height: 80px;
    border-radius: 10px;
    background: #F1F5F9;
    margin: 0 auto 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 32px;
    color: var(--pos-blue);
    overflow: hidden;
  }

  .pos-product-img img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }

  .pos-product-name {
    font-size: 14px;
    font-weight: 600;
    color: var(--pos-dark);
    margin-bottom: 6px;
    line-height: 1.3;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
  }

  .pos-product-code {
    font-size: 11px;
    color: var(--pos-gray);
    margin-bottom: 8px;
    font-family: var(--pos-mono);
  }

  .pos-product-price {
    font-size: 18px;
    font-weight: 700;
    color: var(--pos-green);
    font-family: var(--pos-mono);
  }

  .pos-product-stock {
    position: absolute;
    top: 8px;
    right: 8px;
    font-size: 10px;
    font-weight: 600;
    padding: 4px 8px;
    border-radius: 20px;
    background: var(--pos-green-pale);
    color: var(--pos-green);
  }

  .pos-product-stock.low {
    background: var(--pos-amber-pale);
    color: var(--pos-amber);
  }

  .pos-loading {
    padding: 40px;
    text-align: center;
    color: var(--pos-gray);
  }

  .pos-empty {
    padding: 60px 20px;
    text-align: center;
    color: var(--pos-gray);
  }

  .pos-empty i {
    font-size: 48px;
    margin-bottom: 16px;
    opacity: 0.3;
  }

  /* Right Panel - Cart */
  .pos-cart-panel {
    background: #fff;
    border-radius: var(--pos-radius);
    box-shadow: var(--pos-shadow);
    display: flex;
    flex-direction: column;
    height: fit-content;
    position: sticky;
    top: 20px;
  }

  .pos-cart-header {
    padding: 20px 24px;
    border-bottom: 1px solid #E2E8F0;
    display: flex;
    align-items: center;
    justify-content: space-between;
  }

  .pos-cart-title {
    font-size: 18px;
    font-weight: 600;
    color: var(--pos-dark);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
  }

  .pos-cart-count {
    background: var(--pos-blue);
    color: #fff;
    font-size: 12px;
    font-weight: 600;
    padding: 2px 10px;
    border-radius: 20px;
  }

  .pos-cart-clear {
    font-size: 12px;
    color: var(--pos-red);
    cursor: pointer;
    background: none;
    border: none;
    padding: 4px 8px;
    border-radius: 6px;
    transition: all 0.2s;
  }

  .pos-cart-clear:hover {
    background: var(--pos-red-pale);
  }

  .pos-cart-items {
    padding: 16px;
    max-height: 400px;
    overflow-y: auto;
  }

  .pos-cart-item {
    display: grid;
    grid-template-columns: 50px 1fr auto auto;
    gap: 12px;
    align-items: center;
    padding: 12px;
    border: 1px solid #E2E8F0;
    border-radius: 10px;
    margin-bottom: 10px;
    transition: all 0.2s;
  }

  .pos-cart-item:hover {
    border-color: var(--pos-blue);
    background: var(--pos-blue-pale);
  }

  .pos-cart-img {
    width: 50px;
    height: 50px;
    border-radius: 8px;
    background: #F1F5F9;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    color: var(--pos-blue);
  }

  .pos-cart-info {
    overflow: hidden;
  }

  .pos-cart-name {
    font-size: 14px;
    font-weight: 600;
    color: var(--pos-dark);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }

  .pos-cart-price {
    font-size: 13px;
    color: var(--pos-green);
    font-weight: 500;
    font-family: var(--pos-mono);
  }

  .pos-cart-qty {
    display: flex;
    align-items: center;
    gap: 8px;
  }

  .pos-qty-btn {
    width: 28px;
    height: 28px;
    border: 1px solid #E2E8F0;
    border-radius: 6px;
    background: #fff;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    font-weight: 600;
    color: var(--pos-dark);
    transition: all 0.2s;
  }

  .pos-qty-btn:hover {
    border-color: var(--pos-blue);
    color: var(--pos-blue);
  }

  .pos-qty-value {
    font-size: 14px;
    font-weight: 600;
    color: var(--pos-dark);
    min-width: 24px;
    text-align: center;
    font-family: var(--pos-mono);
  }

  .pos-cart-total {
    font-size: 15px;
    font-weight: 700;
    color: var(--pos-dark);
    font-family: var(--pos-mono);
    text-align: right;
  }

  .pos-cart-item-remove {
    grid-column: 4;
    font-size: 11px;
    color: var(--pos-gray);
    cursor: pointer;
    background: none;
    border: none;
    padding: 2px 6px;
  }

  .pos-cart-item-remove:hover {
    color: var(--pos-red);
  }

  .pos-cart-empty {
    padding: 40px 20px;
    text-align: center;
    color: var(--pos-gray);
  }

  .pos-cart-empty i {
    font-size: 40px;
    margin-bottom: 12px;
    opacity: 0.4;
  }

  .pos-cart-empty p {
    font-size: 14px;
    margin: 0;
  }

  /* Cart Summary */
  .pos-cart-summary {
    padding: 20px 24px;
    border-top: 1px solid #E2E8F0;
    background: #F8FAFC;
    border-radius: 0 0 var(--pos-radius) var(--pos-radius);
  }

  .pos-summary-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
    font-size: 14px;
  }

  .pos-summary-row.discount {
    color: var(--pos-red);
  }

  .pos-summary-row.total {
    font-size: 20px;
    font-weight: 700;
    color: var(--pos-dark);
    margin-top: 16px;
    padding-top: 16px;
    border-top: 2px solid #E2E8F0;
    font-family: var(--pos-mono);
  }

  .pos-summary-label {
    color: var(--pos-gray);
  }

  .pos-summary-value {
    font-weight: 600;
    color: var(--pos-dark);
    font-family: var(--pos-mono);
  }

  .pos-summary-row.total .pos-summary-value {
    color: var(--pos-green);
    font-size: 24px;
  }

  /* Payment Section */
  .pos-payment-section {
    padding: 0 24px 20px;
  }

  .pos-payment-methods {
    display: flex;
    gap: 8px;
    margin-bottom: 16px;
    flex-wrap: wrap;
  }

  .pos-payment-btn {
    flex: 1;
    min-width: 80px;
    padding: 12px 16px;
    font-size: 13px;
    font-weight: 500;
    border: 2px solid #E2E8F0;
    border-radius: 10px;
    background: #fff;
    cursor: pointer;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 6px;
    transition: all 0.2s;
  }

  .pos-payment-btn i {
    font-size: 20px;
    color: var(--pos-gray);
  }

  .pos-payment-btn:hover {
    border-color: var(--pos-blue);
  }

  .pos-payment-btn.active {
    border-color: var(--pos-blue);
    background: var(--pos-blue-pale);
  }

  .pos-payment-btn.active i {
    color: var(--pos-blue);
  }

  .pos-customer-input {
    width: 100%;
    padding: 12px 16px;
    font-size: 14px;
    border: 2px solid #E2E8F0;
    border-radius: 10px;
    margin-bottom: 16px;
    outline: none;
    font-family: var(--pos-font);
  }

  .pos-customer-input:focus {
    border-color: var(--pos-blue);
  }

  .pos-form-group {
    margin-bottom: 16px;
  }

  .pos-form-label {
    display: block;
    font-size: 13px;
    font-weight: 500;
    color: var(--pos-gray);
    margin-bottom: 6px;
  }

  .pos-form-select {
    width: 100%;
    padding: 10px 14px;
    font-size: 14px;
    border: 1px solid #E2E8F0;
    border-radius: 10px;
    background: #fff;
    color: var(--pos-dark);
    outline: none;
    font-family: var(--pos-font);
  }

  .pos-form-select:focus {
    border-color: var(--pos-blue);
  }

  .pos-checkout-btn {
    width: 100%;
    padding: 16px 24px;
    font-size: 16px;
    font-weight: 600;
    color: #fff;
    background: var(--pos-green);
    border: none;
    border-radius: 12px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    transition: all 0.2s;
    box-shadow: 0 4px 12px rgba(46, 125, 50, 0.3);
  }

  .pos-checkout-btn:hover:not(:disabled) {
    background: #1B5E20;
    transform: translateY(-1px);
    box-shadow: 0 6px 16px rgba(46, 125, 50, 0.4);
  }

  .pos-checkout-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
  }

  /* Modals */
  .pos-modal-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(4px);
    z-index: 9999;
    align-items: center;
    justify-content: center;
    padding: 20px;
  }

  .pos-modal-overlay.open {
    display: flex;
  }

  .pos-modal {
    background: #fff;
    border-radius: 16px;
    width: 100%;
    max-width: 500px;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: var(--pos-shadow-lg);
  }

  .pos-modal-header {
    padding: 24px 24px 16px;
    border-bottom: 1px solid #E2E8F0;
  }

  .pos-modal-title {
    font-size: 20px;
    font-weight: 600;
    color: var(--pos-dark);
    margin: 0 0 4px;
  }

  .pos-modal-subtitle {
    font-size: 14px;
    color: var(--pos-gray);
  }

  .pos-modal-body {
    padding: 24px;
  }

  .pos-modal-footer {
    padding: 16px 24px 24px;
    display: flex;
    gap: 12px;
    justify-content: flex-end;
  }

  .pos-modal-btn {
    padding: 12px 24px;
    font-size: 14px;
    font-weight: 600;
    border: none;
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.2s;
  }

  .pos-modal-btn.secondary {
    background: #F1F5F9;
    color: var(--pos-dark);
  }

  .pos-modal-btn.secondary:hover {
    background: #E2E8F0;
  }

  .pos-modal-btn.primary {
    background: var(--pos-blue);
    color: #fff;
  }

  .pos-modal-btn.primary:hover {
    background: var(--pos-blue-dark);
  }

  .pos-modal-btn.success {
    background: var(--pos-green);
    color: #fff;
  }

  .pos-modal-btn.success:hover {
    background: #1B5E20;
  }

  .pos-receipt-preview {
    background: #F8FAFC;
    border: 1px dashed #CBD5E1;
    border-radius: 8px;
    padding: 20px;
    font-family: var(--pos-mono);
    font-size: 13px;
  }

  .pos-receipt-header {
    text-align: center;
    margin-bottom: 16px;
    padding-bottom: 16px;
    border-bottom: 1px dashed #CBD5E1;
  }

  .pos-receipt-title {
    font-size: 16px;
    font-weight: 700;
    margin-bottom: 4px;
  }

  .pos-receipt-meta {
    color: var(--pos-gray);
    font-size: 12px;
  }

  .pos-receipt-items {
    margin-bottom: 16px;
  }

  .pos-receipt-item {
    display: flex;
    justify-content: space-between;
    margin-bottom: 8px;
  }

  .pos-receipt-total {
    border-top: 1px dashed #CBD5E1;
    padding-top: 12px;
    display: flex;
    justify-content: space-between;
    font-weight: 700;
    font-size: 15px;
  }

  .pos-payment-amount {
    font-size: 32px;
    font-weight: 700;
    color: var(--pos-dark);
    text-align: center;
    margin: 24px 0;
    font-family: var(--pos-mono);
  }

  .pos-amount-received {
    width: 100%;
    padding: 16px;
    font-size: 24px;
    font-weight: 700;
    text-align: center;
    border: 2px solid #E2E8F0;
    border-radius: 12px;
    margin-bottom: 16px;
    font-family: var(--pos-mono);
    outline: none;
  }

  .pos-amount-received:focus {
    border-color: var(--pos-blue);
  }

  .pos-change-display {
    text-align: center;
    padding: 16px;
    background: var(--pos-blue-pale);
    border-radius: 12px;
    margin-bottom: 16px;
  }

  .pos-change-label {
    font-size: 14px;
    color: var(--pos-blue);
    margin-bottom: 4px;
  }

  .pos-change-amount {
    font-size: 28px;
    font-weight: 700;
    color: var(--pos-blue);
    font-family: var(--pos-mono);
  }

  /* Toast Notification */
  .pos-toast {
    position: fixed;
    bottom: 24px;
    right: 24px;
    padding: 16px 24px;
    background: var(--pos-dark);
    color: #fff;
    border-radius: 10px;
    box-shadow: var(--pos-shadow-lg);
    display: flex;
    align-items: center;
    gap: 12px;
    z-index: 10000;
    transform: translateX(150%);
    transition: transform 0.3s ease;
  }

  .pos-toast.show {
    transform: translateX(0);
  }

  .pos-toast.success {
    background: var(--pos-green);
  }

  .pos-toast.error {
    background: var(--pos-red);
  }

  .pos-toast i {
    font-size: 20px;
  }
</style>

<div class="pos-container">
  <!-- Products Panel -->
  <div class="pos-products-panel">
    <div class="pos-header">
      <div style="display: flex; align-items: center; gap: 12px;">
        <a href="<?php echo $base_url ?? './'; ?>" class="pos-back-btn" title="Back to Dashboard">
          <i class="fas fa-arrow-left"></i>
        </a>
        <h1 class="pos-title">Point of Sale</h1>
      </div>
      <div class="pos-search-wrap">
        <i class="fas fa-search pos-search-icon"></i>
        <input type="text" class="pos-search" id="posSearch" placeholder="Search products by name or code...">
      </div>
    </div>

    <div class="pos-category-filter" id="categoryFilter">
      <button class="pos-cat-btn active" data-category="">All Products</button>
    </div>

    <div class="pos-products-grid" id="productsGrid">
      <div class="pos-loading">Loading products...</div>
    </div>
  </div>

  <!-- Cart Panel -->
  <div class="pos-cart-panel">
    <div class="pos-cart-header">
      <h2 class="pos-cart-title">
        <i class="fas fa-shopping-cart"></i>
        Current Order
        <span class="pos-cart-count" id="cartCount">0</span>
      </h2>
      <button class="pos-cart-clear" id="clearCart" style="display:none;">
        <i class="fas fa-trash"></i> Clear
      </button>
    </div>

    <div class="pos-cart-items" id="cartItems">
      <div class="pos-cart-empty">
        <i class="fas fa-cart-plus"></i>
        <p>Your cart is empty</p>
        <p style="font-size:12px;margin-top:8px;">Click products to add them</p>
      </div>
    </div>

    <div class="pos-cart-summary" id="cartSummary" style="display:none;">
      <div class="pos-summary-row">
        <span class="pos-summary-label">Subtotal</span>
        <span class="pos-summary-value" id="subtotal">₱0.00</span>
      </div>
      <div class="pos-summary-row">
        <span class="pos-summary-label">Tax (12%)</span>
        <span class="pos-summary-value" id="tax">₱0.00</span>
      </div>
      <div class="pos-summary-row total">
        <span class="pos-summary-label">TOTAL</span>
        <span class="pos-summary-value" id="total">₱0.00</span>
      </div>
    </div>

    <div class="pos-payment-section" id="paymentSection" style="display:none;">
      <div class="pos-payment-methods">
        <button class="pos-payment-btn active" data-method="cash">
          <i class="fas fa-money-bill-wave"></i>
          Cash
        </button>
        <button class="pos-payment-btn" data-method="gcash">
          <i class="fas fa-mobile-alt"></i>
          GCash
        </button>
        <button class="pos-payment-btn" data-method="card">
          <i class="fas fa-credit-card"></i>
          Card
        </button>
      </div>
      <input type="text" class="pos-customer-input" id="customerName" placeholder="Customer name (optional)">
      <button class="pos-checkout-btn" id="checkoutBtn" disabled>
        <i class="fas fa-check-circle"></i>
        Complete Sale
      </button>
    </div>
  </div>
</div>

<!-- Checkout Modal -->
<div class="pos-modal-overlay" id="checkoutModal">
  <div class="pos-modal">
    <div class="pos-modal-header">
      <h3 class="pos-modal-title">Complete Payment</h3>
      <p class="pos-modal-subtitle">Enter payment details to finalize the sale</p>
    </div>
    <div class="pos-modal-body">
      <div class="pos-form-group">
        <label class="pos-form-label">Sale Type</label>
        <select class="pos-form-select" id="saleTypeSelect">
          <option value="pos">Regular Sale (POS)</option>
          <option value="canteen">Canteen Sale</option>
          <option value="staff_purchase">Staff Purchase</option>
        </select>
      </div>
      <div class="pos-form-group" id="staffMemberGroup" style="display:none;">
        <label class="pos-form-label">Staff Member</label>
        <select class="pos-form-select" id="staffMemberSelect">
          <option value="">Select Staff Member</option>
        </select>
      </div>
      <div class="pos-payment-amount" id="modalTotal">₱0.00</div>
      <input type="number" class="pos-amount-received" id="amountReceived" placeholder="Amount received" min="0" step="0.01">
      <div class="pos-change-display" id="changeDisplay" style="display:none;">
        <div class="pos-change-label">Change</div>
        <div class="pos-change-amount" id="changeAmount">₱0.00</div>
      </div>
    </div>
    <div class="pos-modal-footer">
      <button class="pos-modal-btn secondary" id="cancelCheckout">Cancel</button>
      <button class="pos-modal-btn success" id="confirmCheckout" disabled>
        <i class="fas fa-check"></i> Confirm Sale
      </button>
    </div>
  </div>
</div>

<!-- Receipt Modal -->
<div class="pos-modal-overlay" id="receiptModal">
  <div class="pos-modal">
    <div class="pos-modal-header">
      <h3 class="pos-modal-title"><i class="fas fa-receipt"></i> Sale Complete</h3>
      <p class="pos-modal-subtitle">Transaction saved successfully</p>
    </div>
    <div class="pos-modal-body">
      <div class="pos-receipt-preview" id="receiptPreview"></div>
    </div>
    <div class="pos-modal-footer">
      <button class="pos-modal-btn secondary" id="newSaleBtn">New Sale</button>
      <button class="pos-modal-btn primary" id="printReceiptBtn">
        <i class="fas fa-print"></i> Print Receipt
      </button>
    </div>
  </div>
</div>

<!-- Toast -->
<div class="pos-toast" id="toast">
  <i class="fas fa-check-circle"></i>
  <span id="toastMessage">Operation successful</span>
</div>

<script>
  (function() {
    const API_BASE = window.location.pathname.split('/build/')[0] + '/build/api';
    let products = [];
    let categories = [];
    let cart = [];
    let paymentMethod = 'cash';
    let propertyId = '<?php echo $_SESSION['selected_property_id'] ?? ''; ?>';

    // Initialize
    document.addEventListener('DOMContentLoaded', () => {
      loadProducts();
      loadCategories();
      setupEventListeners();
    });

    // Load products
    async function loadProducts() {
      try {
        const response = await fetch(`${API_BASE}/products.php?action=list`);
        const result = await response.json();
        if (result.success) {
          // Filter only products marked for sale
          products = result.data.filter(p => p.is_for_sale == 1 || p.selling_price > 0);
          renderProducts();
        }
      } catch (error) {
        showToast('Failed to load products', 'error');
      }
    }

    // Load categories
    async function loadCategories() {
      try {
        const response = await fetch(`${API_BASE}/inventory_categories.php?action=list`);
        const result = await response.json();
        if (result.success) {
          categories = result.data;
          renderCategories();
        }
      } catch (error) {
        console.error('Failed to load categories');
      }
    }

    // Render categories
    function renderCategories() {
      const container = document.getElementById('categoryFilter');
      categories.forEach(cat => {
        const btn = document.createElement('button');
        btn.className = 'pos-cat-btn';
        btn.dataset.category = cat.category_id;
        btn.textContent = cat.category_name;
        container.appendChild(btn);
      });
    }

    // Render products
    function renderProducts(filterText = '', categoryId = '') {
      const container = document.getElementById('productsGrid');

      let filtered = products;

      if (filterText) {
        const text = filterText.toLowerCase();
        filtered = filtered.filter(p =>
          p.product_name.toLowerCase().includes(text) ||
          p.product_code.toLowerCase().includes(text)
        );
      }

      if (categoryId) {
        filtered = filtered.filter(p => p.category_id == categoryId);
      }

      if (filtered.length === 0) {
        container.innerHTML = `
        <div class="pos-empty">
          <i class="fas fa-search"></i>
          <p>No products found</p>
        </div>
      `;
        return;
      }

      container.innerHTML = filtered.map(p => {
        const stock = parseFloat(p.current_stock) || 0;
        const reorder = parseFloat(p.reorder_point) || 0;
        let stockClass = '';
        let stockLabel = '';

        if (stock <= 0) {
          stockClass = 'out-of-stock';
          stockLabel = 'Out of Stock';
        } else if (stock <= reorder) {
          stockClass = 'low-stock';
          stockLabel = 'Low Stock';
        } else {
          stockLabel = Math.floor(stock) + ' in stock';
        }

        return `
        <div class="pos-product-card ${stockClass}" data-id="${p.product_id}" ${stock <= 0 ? 'disabled' : ''}>
          <div class="pos-product-stock ${stock <= reorder ? 'low' : ''}">${stockLabel}</div>
          <div class="pos-product-img">
            ${p.product_image_url ? `<img src="${p.product_image_url}" alt="">` : '<i class="fas fa-box"></i>'}
          </div>
          <div class="pos-product-name">${p.product_name}</div>
          <div class="pos-product-code">${p.product_code}</div>
          <div class="pos-product-price">₱${formatMoney(p.selling_price)}</div>
        </div>
      `;
      }).join('');

      // Add click handlers
      container.querySelectorAll('.pos-product-card:not([disabled])').forEach(card => {
        card.addEventListener('click', () => addToCart(card.dataset.id));
      });
    }

    // Add to cart
    function addToCart(productId) {
      const product = products.find(p => p.product_id === productId);
      if (!product) return;

      const stock = parseFloat(product.current_stock) || 0;
      const existingItem = cart.find(item => item.product_id === productId);

      if (existingItem) {
        if (existingItem.quantity >= stock) {
          showToast('Not enough stock available', 'error');
          return;
        }
        existingItem.quantity++;
      } else {
        if (stock < 1) {
          showToast('Product out of stock', 'error');
          return;
        }
        cart.push({
          product_id: productId,
          product_name: product.product_name,
          product_code: product.product_code,
          unit_price: parseFloat(product.selling_price) || 0,
          quantity: 1,
          uom_id: product.uom_id,
          max_stock: stock
        });
      }

      renderCart();
      showToast('Added to cart');
    }

    // Render cart
    function renderCart() {
      const container = document.getElementById('cartItems');
      const countEl = document.getElementById('cartCount');
      const clearBtn = document.getElementById('clearCart');
      const summary = document.getElementById('cartSummary');
      const paymentSection = document.getElementById('paymentSection');
      const checkoutBtn = document.getElementById('checkoutBtn');

      countEl.textContent = cart.reduce((sum, item) => sum + item.quantity, 0);

      if (cart.length === 0) {
        container.innerHTML = `
        <div class="pos-cart-empty">
          <i class="fas fa-cart-plus"></i>
          <p>Your cart is empty</p>
          <p style="font-size:12px;margin-top:8px;">Click products to add them</p>
        </div>
      `;
        clearBtn.style.display = 'none';
        summary.style.display = 'none';
        paymentSection.style.display = 'none';
        checkoutBtn.disabled = true;
        return;
      }

      clearBtn.style.display = 'flex';
      summary.style.display = 'block';
      paymentSection.style.display = 'block';
      checkoutBtn.disabled = false;

      container.innerHTML = cart.map((item, index) => `
      <div class="pos-cart-item" data-index="${index}">
        <div class="pos-cart-img"><i class="fas fa-box"></i></div>
        <div class="pos-cart-info">
          <div class="pos-cart-name">${item.product_name}</div>
          <div class="pos-cart-price">₱${formatMoney(item.unit_price)}</div>
        </div>
        <div class="pos-cart-qty">
          <button class="pos-qty-btn" data-action="decrease" data-index="${index}">-</button>
          <span class="pos-qty-value">${item.quantity}</span>
          <button class="pos-qty-btn" data-action="increase" data-index="${index}">+</button>
        </div>
        <div class="pos-cart-total">₱${formatMoney(item.unit_price * item.quantity)}</div>
        <button class="pos-cart-item-remove" data-action="remove" data-index="${index}"><i class="fas fa-times"></i></button>
      </div>
    `).join('');

      updateSummary();
    }

    // Update summary
    function updateSummary() {
      const subtotal = cart.reduce((sum, item) => sum + (item.unit_price * item.quantity), 0);
      const tax = subtotal * 0.12;
      const total = subtotal + tax;

      document.getElementById('subtotal').textContent = '₱' + formatMoney(subtotal);
      document.getElementById('tax').textContent = '₱' + formatMoney(tax);
      document.getElementById('total').textContent = '₱' + formatMoney(total);
    }

    // Format money
    function formatMoney(amount) {
      return parseFloat(amount).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
    }

    // Setup event listeners
    function setupEventListeners() {
      // Search
      document.getElementById('posSearch').addEventListener('input', (e) => {
        const activeCat = document.querySelector('.pos-cat-btn.active');
        renderProducts(e.target.value, activeCat ? activeCat.dataset.category : '');
      });

      // Category filter
      document.getElementById('categoryFilter').addEventListener('click', (e) => {
        if (e.target.classList.contains('pos-cat-btn')) {
          document.querySelectorAll('.pos-cat-btn').forEach(btn => btn.classList.remove('active'));
          e.target.classList.add('active');
          renderProducts(document.getElementById('posSearch').value, e.target.dataset.category);
        }
      });

      // Cart actions
      document.getElementById('cartItems').addEventListener('click', (e) => {
        const btn = e.target.closest('[data-action]');
        if (!btn) return;

        const index = parseInt(btn.dataset.index);
        const action = btn.dataset.action;

        if (action === 'increase') {
          if (cart[index].quantity >= cart[index].max_stock) {
            showToast('Maximum stock reached', 'error');
            return;
          }
          cart[index].quantity++;
        } else if (action === 'decrease') {
          cart[index].quantity--;
          if (cart[index].quantity <= 0) {
            cart.splice(index, 1);
          }
        } else if (action === 'remove') {
          cart.splice(index, 1);
        }

        renderCart();
      });

      // Clear cart
      document.getElementById('clearCart').addEventListener('click', () => {
        cart = [];
        renderCart();
      });

      // Payment method selection
      document.querySelectorAll('.pos-payment-btn').forEach(btn => {
        btn.addEventListener('click', () => {
          document.querySelectorAll('.pos-payment-btn').forEach(b => b.classList.remove('active'));
          btn.classList.add('active');
          paymentMethod = btn.dataset.method;
        });
      });

      // Checkout
      document.getElementById('checkoutBtn').addEventListener('click', openCheckoutModal);
      document.getElementById('cancelCheckout').addEventListener('click', closeCheckoutModal);
      document.getElementById('confirmCheckout').addEventListener('click', processSale);

      // Sale type change
      document.getElementById('saleTypeSelect').addEventListener('change', function() {
        const staffGroup = document.getElementById('staffMemberGroup');
        if (this.value === 'staff_purchase') {
          staffGroup.style.display = 'block';
          loadStaffMembers();
        } else {
          staffGroup.style.display = 'none';
        }
      });

      // Amount received input
      document.getElementById('amountReceived').addEventListener('input', calculateChange);

      // Receipt modal
      document.getElementById('newSaleBtn').addEventListener('click', () => {
        cart = [];
        renderCart();
        closeReceiptModal();
        document.getElementById('customerName').value = '';
      });

      document.getElementById('printReceiptBtn').addEventListener('click', printReceipt);

      // Close modals on overlay click
      document.querySelectorAll('.pos-modal-overlay').forEach(overlay => {
        overlay.addEventListener('click', (e) => {
          if (e.target === overlay) {
            overlay.classList.remove('open');
          }
        });
      });
    }

    // Open checkout modal
    function openCheckoutModal() {
      const total = cart.reduce((sum, item) => sum + (item.unit_price * item.quantity), 0) * 1.12;
      document.getElementById('modalTotal').textContent = '₱' + formatMoney(total);
      document.getElementById('amountReceived').value = '';
      document.getElementById('changeDisplay').style.display = 'none';
      document.getElementById('confirmCheckout').disabled = true;
      document.getElementById('checkoutModal').classList.add('open');
      setTimeout(() => document.getElementById('amountReceived').focus(), 100);
    }

    // Close checkout modal
    function closeCheckoutModal() {
      document.getElementById('checkoutModal').classList.remove('open');
    }

    // Load staff members
    async function loadStaffMembers() {
      try {
        const response = await fetch('../api/users.php?action=list_staff');
        const result = await response.json();
        if (result.success && result.data) {
          const select = document.getElementById('staffMemberSelect');
          select.innerHTML = '<option value="">Select Staff Member</option>' +
            result.data.map(staff =>
              `<option value="${staff.user_id}">${staff.first_name} ${staff.last_name}</option>`
            ).join('');
        }
      } catch (error) {
        console.error('Error loading staff members:', error);
      }
    }

    // Calculate change
    function calculateChange() {
      const total = cart.reduce((sum, item) => sum + (item.unit_price * item.quantity), 0) * 1.12;
      const received = parseFloat(document.getElementById('amountReceived').value) || 0;
      const change = received - total;

      const changeDisplay = document.getElementById('changeDisplay');
      const confirmBtn = document.getElementById('confirmCheckout');

      if (received >= total) {
        changeDisplay.style.display = 'block';
        document.getElementById('changeAmount').textContent = '₱' + formatMoney(change);
        confirmBtn.disabled = false;
      } else {
        changeDisplay.style.display = 'none';
        confirmBtn.disabled = true;
      }
    }

    // Process sale
    async function processSale() {
      const customerName = document.getElementById('customerName').value.trim();
      const saleType = document.getElementById('saleTypeSelect').value;
      const staffMemberId = document.getElementById('staffMemberSelect').value;
      const subtotal = cart.reduce((sum, item) => sum + (item.unit_price * item.quantity), 0);
      const tax = subtotal * 0.12;
      const total = subtotal + tax;

      // Generate sale number
      const saleNumber = 'POS-' + new Date().getFullYear() + '-' + String(Math.floor(Math.random() * 100000)).padStart(5, '0');

      const saleData = {
        sale_number: saleNumber,
        property_id: propertyId || null,
        sale_date: new Date().toISOString().split('T')[0],
        sale_type: saleType,
        customer_name: customerName || null,
        subtotal: subtotal.toFixed(2),
        tax_amount: tax.toFixed(2),
        total_amount: total.toFixed(2),
        payment_method: paymentMethod,
        payment_status: 'paid',
        notes: saleType === 'staff_purchase' ? 'Staff Purchase' : 'POS Sale',
        staff_member_id: staffMemberId || null,
        items: cart.map(item => ({
          product_id: item.product_id,
          uom_id: item.uom_id,
          quantity: item.quantity,
          unit_price: item.unit_price.toFixed(2),
          total_price: (item.unit_price * item.quantity).toFixed(2)
        }))
      };

      try {
        const response = await fetch(`${API_BASE}/inventory_sales.php?action=create`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify(saleData)
        });

        const result = await response.json();

        if (result.success) {
          showToast('Sale completed successfully!');
          closeCheckoutModal();
          showReceipt(saleData, result.sale_id);
        } else {
          showToast(result.message || 'Failed to process sale', 'error');
        }
      } catch (error) {
        showToast('Network error. Please try again.', 'error');
      }
    }

    // Show receipt
    function showReceipt(saleData, saleId) {
      const now = new Date();
      const receiptHTML = `
      <div class="pos-receipt-header">
        <div class="pos-receipt-title">SALES RECEIPT</div>
        <div class="pos-receipt-meta">
          ${saleId}<br>
          ${now.toLocaleDateString()} ${now.toLocaleTimeString()}
        </div>
      </div>
      <div class="pos-receipt-items">
        ${saleData.items.map(item => `
          <div class="pos-receipt-item">
            <span>${item.quantity}x ${item.product_name}</span>
            <span>₱${formatMoney(item.total_price)}</span>
          </div>
        `).join('')}
      </div>
      <div style="border-top:1px dashed #CBD5E1;padding-top:12px;margin-bottom:12px;">
        <div class="pos-receipt-item">
          <span>Subtotal</span>
          <span>₱${formatMoney(saleData.subtotal)}</span>
        </div>
        <div class="pos-receipt-item">
          <span>Tax (12%)</span>
          <span>₱${formatMoney(saleData.tax_amount)}</span>
        </div>
      </div>
      <div class="pos-receipt-total">
        <span>TOTAL</span>
        <span>₱${formatMoney(saleData.total_amount)}</span>
      </div>
      <div style="margin-top:16px;padding-top:12px;border-top:1px dashed #CBD5E1;font-size:12px;color:var(--pos-gray);">
        <div>Payment: ${saleData.payment_method.toUpperCase()}</div>
        ${saleData.customer_name ? `<div>Customer: ${saleData.customer_name}</div>` : ''}
      </div>
    `;

      document.getElementById('receiptPreview').innerHTML = receiptHTML;
      document.getElementById('receiptModal').classList.add('open');
    }

    // Close receipt modal
    function closeReceiptModal() {
      document.getElementById('receiptModal').classList.remove('open');
    }

    // Print receipt
    function printReceipt() {
      const printWindow = window.open('', '_blank');
      const receiptContent = document.getElementById('receiptPreview').innerHTML;
      printWindow.document.write(`
      <html>
        <head>
          <title>Receipt</title>
          <style>
            body { font-family: monospace; padding: 20px; max-width: 300px; margin: 0 auto; }
            .pos-receipt-header { text-align: center; margin-bottom: 20px; }
            .pos-receipt-title { font-size: 18px; font-weight: bold; }
            .pos-receipt-item { display: flex; justify-content: space-between; margin: 8px 0; }
            .pos-receipt-total { border-top: 2px dashed #000; padding-top: 10px; margin-top: 10px; font-weight: bold; font-size: 16px; display: flex; justify-content: space-between; }
          </style>
        </head>
        <body>
          ${receiptContent}
        </body>
      </html>
    `);
      printWindow.document.close();
      printWindow.print();
    }

    // Show toast
    function showToast(message, type = 'success') {
      const toast = document.getElementById('toast');
      const toastMessage = document.getElementById('toastMessage');

      toast.className = 'pos-toast ' + type;
      toastMessage.textContent = message;
      toast.querySelector('i').className = type === 'success' ? 'fas fa-check-circle' : 'fas fa-exclamation-circle';

      toast.classList.add('show');
      setTimeout(() => toast.classList.remove('show'), 3000);
    }
  })();
</script>