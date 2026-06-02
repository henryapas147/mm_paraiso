<?php
require_once 'config/config.php';

$companyName = $companyInfo['company_name'] ?? 'M&M Paraiso';
$propertyId = $_GET['property'] ?? '';

// Fetch properties for selector
$properties = [];
try {
  $stmt = $pdo->query("SELECT property_id, property_name FROM properties WHERE is_active = 1 ORDER BY property_name");
  $properties = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  error_log("Failed to fetch properties: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <?php include('structure/head.php'); ?>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
  <title><?php echo htmlspecialchars($companyName); ?> - Food Menu</title>
  <style>
    /* Toast Notifications */
    .toast-container {
      position: fixed;
      top: 20px;
      right: 20px;
      z-index: 9999;
    }

    .custom-toast {
      background: #fff;
      border-radius: 8px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
      padding: 16px 20px;
      margin-bottom: 10px;
      display: flex;
      align-items: center;
      gap: 12px;
      min-width: 300px;
      animation: slideIn 0.3s ease;
    }

    .custom-toast.error {
      border-left: 4px solid #ef4444;
    }

    .custom-toast.success {
      border-left: 4px solid #22c55e;
    }

    .custom-toast.info {
      border-left: 4px solid #3b82f6;
    }

    .custom-toast i {
      font-size: 20px;
    }

    .custom-toast.error i {
      color: #ef4444;
    }

    .custom-toast.success i {
      color: #22c55e;
    }

    .custom-toast.info i {
      color: #3b82f6;
    }

    .toast-message {
      flex: 1;
      font-size: 14px;
      color: #374151;
    }

    .toast-close {
      background: none;
      border: none;
      color: #9ca3af;
      cursor: pointer;
      font-size: 18px;
      padding: 0;
    }

    .toast-close:hover {
      color: #6b7280;
    }

    @keyframes slideIn {
      from {
        transform: translateX(100%);
        opacity: 0;
      }

      to {
        transform: translateX(0);
        opacity: 1;
      }
    }

    @keyframes fadeOut {
      from {
        opacity: 1;
      }

      to {
        opacity: 0;
      }
    }

    .food-card {
      transition: transform 0.2s, box-shadow 0.2s;
      border: none;
      border-radius: 15px;
      overflow: hidden;
    }

    .food-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
    }

    .food-img {
      height: 200px;
      object-fit: cover;
    }

    .category-pill {
      cursor: pointer;
      transition: all 0.3s;
    }

    .category-pill.active {
      background: #f35525 !important;
      color: white !important;
    }

    .cart-badge {
      position: fixed;
      bottom: 30px;
      right: 30px;
      width: 60px;
      height: 60px;
      border-radius: 50%;
      background: #f35525;
      color: white;
      border: none;
      box-shadow: 0 5px 20px rgba(243, 85, 37, 0.4);
      z-index: 1000;
      font-size: 20px;
    }

    .cart-count {
      position: absolute;
      top: -5px;
      right: -5px;
      background: #fff;
      color: #f35525;
      border-radius: 50%;
      width: 24px;
      height: 24px;
      font-size: 12px;
      font-weight: bold;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .qty-btn {
      width: 32px;
      height: 32px;
      border-radius: 8px;
      border: 1px solid #ddd;
      background: #fff;
      font-weight: bold;
    }

    .qty-input {
      width: 50px;
      text-align: center;
      border: 1px solid #ddd;
      border-radius: 8px;
    }

    .add-to-cart-btn {
      background: #f35525;
      color: white;
      border: none;
      padding: 8px 20px;
      border-radius: 25px;
      font-weight: 500;
      transition: all 0.3s;
    }

    .add-to-cart-btn:hover {
      background: #e14a1f;
      transform: scale(1.05);
    }

    .menu-item-price {
      color: #f35525;
      font-weight: 700;
      font-size: 1.1rem;
    }

    .empty-state {
      text-align: center;
      padding: 60px 20px;
    }

    .empty-state i {
      font-size: 64px;
      color: #ddd;
      margin-bottom: 20px;
    }

    .loading-skeleton {
      background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
      background-size: 200% 100%;
      animation: loading 1.5s infinite;
    }

    @keyframes loading {
      0% {
        background-position: 200% 0;
      }

      100% {
        background-position: -200% 0;
      }
    }

    .cart-item-img {
      width: 60px;
      height: 60px;
      object-fit: cover;
      border-radius: 8px;
    }

    .order-type-selector {
      display: flex;
      gap: 10px;
      margin-bottom: 20px;
    }

    .order-type-btn {
      flex: 1;
      padding: 15px;
      border: 2px solid #ddd;
      border-radius: 10px;
      background: white;
      cursor: pointer;
      transition: all 0.3s;
      text-align: center;
    }

    .order-type-btn:hover {
      border-color: #f35525;
    }

    .order-type-btn.active {
      border-color: #f35525;
      background: #fff5f2;
    }

    .order-type-btn i {
      font-size: 24px;
      margin-bottom: 5px;
      display: block;
      color: #f35525;
    }
  </style>
</head>

<body>
  <?php include('structure/preloader.php'); ?>
  <?php include('structure/subheader.php'); ?>
  <?php include('structure/header.php'); ?>

  <div class="page-heading header-text">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <span class="breadcrumb"><a href="index.php">Home</a> / Food Menu</span>
          <h3>Order Food & Beverages</h3>
        </div>
      </div>
    </div>
  </div>

  <div class="section" style="padding: 60px 0;">
    <div class="container">
      <!-- Property Selector -->
      <div class="row mb-4">
        <div class="col-lg-4">
          <select id="propertySelector" class="form-select form-select-lg">
            <option value="">Select Property</option>
            <?php foreach ($properties as $prop): ?>
              <option value="<?php echo htmlspecialchars($prop['property_id']); ?>" <?php echo $prop['property_id'] === $propertyId ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($prop['property_name']); ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>

      <!-- Category Pills -->
      <div class="row mb-4">
        <div class="col-lg-12">
          <div id="categoryContainer" class="d-flex flex-wrap gap-2">
            <button class="btn category-pill active" data-category="all">All Items</button>
            <!-- Categories loaded dynamically -->
          </div>
        </div>
      </div>

      <!-- Menu Items Grid -->
      <div id="menuContainer" class="row g-4">
        <div class="col-12 text-center py-5">
          <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
          </div>
          <p class="mt-2 text-muted">Loading menu...</p>
        </div>
      </div>
    </div>
  </div>

  <!-- Toast Container -->
  <div id="toastContainer" class="toast-container"></div>

  <!-- Cart Button -->
  <button class="cart-badge" data-bs-toggle="modal" data-bs-target="#cartModal">
    <i class="fa fa-shopping-cart"></i>
    <span class="cart-count" id="cartCount">0</span>
  </button>

  <!-- Cart Modal -->
  <div class="modal fade" id="cartModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title"><i class="fa fa-shopping-cart me-2"></i>Your Order</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <!-- Order Type Selection -->
          <div class="order-type-selector">
            <div class="order-type-btn active" data-type="dine_in">
              <i class="fa fa-cutlery"></i>
              <span>Dine In</span>
            </div>
            <div class="order-type-btn" data-type="room_service">
              <i class="fa fa-bed"></i>
              <span>Room Service</span>
            </div>
            <div class="order-type-btn" data-type="takeout">
              <i class="fa fa-shopping-bag"></i>
              <span>Takeout</span>
            </div>
          </div>

          <!-- Room Selection (for room service) -->
          <div id="roomSelection" class="mb-3" style="display: none;">
            <label class="form-label">Select Your Room</label>
            <select id="roomSelector" class="form-select">
              <option value="">Select Room</option>
            </select>
          </div>

          <!-- Table Selection (for dine in) -->
          <div id="tableInput" class="mb-3">
            <label class="form-label">Select Table</label>
            <select id="tableSelector" class="form-select">
              <option value="">Select a table</option>
            </select>
            <div id="tableInfo" class="mt-2 small text-muted"></div>
          </div>

          <!-- Cart Items -->
          <div id="cartItems" class="mb-3">
            <p class="text-muted text-center py-4">Your cart is empty</p>
          </div>

          <!-- Customer Email (optional, for order confirmation) -->
          <div class="mb-3">
            <label class="form-label">Email <span class="text-muted">(optional - for order confirmation)</span></label>
            <input type="email" id="customerEmail" class="form-control" placeholder="your@email.com">
          </div>

          <!-- Special Requests -->
          <div class="mb-3">
            <label class="form-label">Special Requests</label>
            <textarea id="specialRequests" class="form-control" rows="2" placeholder="Any allergies or special instructions?"></textarea>
          </div>

          <!-- Order Summary -->
          <div class="border-top pt-3" id="orderSummary">
            <div class="d-flex justify-content-between align-items-center">
              <span class="h5 mb-0">Items Total:</span>
              <span class="h4 mb-0 text-primary" id="cartTotal">₱0.00</span>
            </div>
            <div class="small text-muted mt-1">Prices include VAT</div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Continue Shopping</button>
          <button type="button" id="checkoutBtn" class="btn btn-primary" style="background: #f35525; border: none;" disabled>
            Place Order
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Order Success Modal -->
  <div class="modal fade" id="successModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content text-center p-4">
        <div class="mb-3">
          <i class="fa fa-check-circle text-success" style="font-size: 64px;"></i>
        </div>
        <h4>Order Placed Successfully!</h4>
        <p class="text-muted">Your order number is <strong id="orderNumber"></strong></p>
        <p class="text-muted">We'll prepare your food shortly.</p>
        <button type="button" class="btn btn-primary mt-3" data-bs-dismiss="modal" style="background: #f35525; border: none;">
          Continue
        </button>
      </div>
    </div>
  </div>

  <?php include('structure/footer.php'); ?>
  <?php include('structure/scripts.php'); ?>

  <script>
    // State
    let cart = JSON.parse(localStorage.getItem('foodCart')) || [];
    let menuItems = [];
    let categories = [];
    let currentProperty = '<?php echo $propertyId; ?>';
    let currentCategory = 'all';
    let foodSettings = {
      tax_rate_percent: 12,
      currency: 'PHP',
      currency_symbol: '₱'
    };

    // Load food settings (tax rate, etc.)
    async function loadSettings() {
      try {
        const res = await fetch('api/food-menu.php?action=settings');
        const data = await res.json();
        if (data.success && data.data) {
          foodSettings = data.data;
          console.log('Food settings loaded:', foodSettings);
          // Tax label removed - prices are tax-inclusive
        }
      } catch (err) {
        console.error('Failed to load food settings:', err);
      }
    }

    // Initialize
    document.addEventListener('DOMContentLoaded', async function() {
      // Load settings first
      await loadSettings();

      // Update cart UI
      updateCartUI();

      // Auto-select if only one property
      const propSelector = document.getElementById('propertySelector');
      if (!currentProperty && propSelector.options.length === 2) {
        propSelector.selectedIndex = 1;
        propSelector.dispatchEvent(new Event('change'));
        return;
      }

      if (currentProperty) {
        loadCategories();
        loadMenuItems();
        loadRooms();
        loadTables();
      } else {
        // Load all properties data and all rooms
        loadCategories();
        loadMenuItems();
        loadRooms();
        loadTables();
      }
    });

    // Property selector change
    document.getElementById('propertySelector').addEventListener('change', async function() {
      currentProperty = this.value;
      await loadSettings();
      loadCategories();
      loadMenuItems();
      loadRooms();
      loadTables();
      // Update URL without reload
      if (currentProperty) {
        const url = new URL(window.location);
        url.searchParams.set('property', currentProperty);
        window.history.pushState({}, '', url);
      }
    });

    // Load categories
    async function loadCategories() {
      try {
        const res = await fetch(`api/food-menu.php?action=categories&property_id=${currentProperty}`);
        const data = await res.json();
        if (data.success) {
          categories = data.data;
          renderCategories();
        }
      } catch (err) {
        console.error('Failed to load categories:', err);
      }
    }

    function renderCategories() {
      const container = document.getElementById('categoryContainer');
      const pills = categories.map(cat => `
        <button class="btn category-pill" data-category="${cat.category_id}">
          ${escapeHtml(cat.category_name)}
        </button>
      `).join('');
      container.innerHTML = '<button class="btn category-pill active" data-category="all">All Items</button>' + pills;

      // Add click handlers
      container.querySelectorAll('.category-pill').forEach(btn => {
        btn.addEventListener('click', function() {
          container.querySelectorAll('.category-pill').forEach(b => b.classList.remove('active'));
          this.classList.add('active');
          currentCategory = this.dataset.category;
          renderMenuItems();
        });
      });
    }

    // Load menu items
    async function loadMenuItems() {
      try {
        document.getElementById('menuContainer').innerHTML = `
          <div class="col-12 text-center py-5">
            <div class="spinner-border text-primary" role="status"></div>
            <p class="mt-2 text-muted">Loading menu...</p>
          </div>
        `;

        const res = await fetch(`api/food-menu.php?action=menu&property_id=${currentProperty}`);
        const data = await res.json();
        if (data.success) {
          menuItems = data.data;
          renderMenuItems();
        }
      } catch (err) {
        document.getElementById('menuContainer').innerHTML = `
          <div class="col-12 text-center py-5">
            <p class="text-danger">Failed to load menu. Please try again.</p>
          </div>
        `;
      }
    }

    function renderMenuItems() {
      const container = document.getElementById('menuContainer');
      const filtered = currentCategory === 'all' ?
        menuItems :
        menuItems.filter(item => item.category_id == currentCategory);

      if (filtered.length === 0) {
        container.innerHTML = `
          <div class="col-12 empty-state">
            <i class="fa fa-utensils"></i>
            <h4>No items available</h4>
            <p class="text-muted">Check back later for new menu items</p>
          </div>
        `;
        return;
      }

      container.innerHTML = filtered.map(item => {
        // Fix image path - use uploads/foods directory
        let imgUrl = '../uploads/foods/default-food.png';
        if (item.image_url) {
          if (item.image_url.startsWith('http')) {
            imgUrl = item.image_url;
          } else if (item.image_url.startsWith('/uploads/foods/')) {
            imgUrl = '..' + item.image_url;
          } else if (item.image_url.startsWith('/')) {
            imgUrl = '..' + item.image_url;
          } else {
            imgUrl = '../uploads/foods/' + item.image_url;
          }
        } else if (item.thumbnail_url) {
          if (item.thumbnail_url.startsWith('http')) {
            imgUrl = item.thumbnail_url;
          } else if (item.thumbnail_url.startsWith('/uploads/foods/')) {
            imgUrl = '..' + item.thumbnail_url;
          } else if (item.thumbnail_url.startsWith('/')) {
            imgUrl = '..' + item.thumbnail_url;
          } else {
            imgUrl = '../uploads/foods/' + item.thumbnail_url;
          }
        }

        return `
        <div class="col-lg-3 col-md-4 col-sm-6">
          <div class="card food-card h-100">
            <img src="${imgUrl}" 
                 class="card-img-top food-img" alt="${escapeHtml(item.item_name)}">
            <div class="card-body d-flex flex-column">
              <span class="badge bg-light text-dark mb-2" style="width: fit-content;">${escapeHtml(item.category_name)}</span>
              <h5 class="card-title">${escapeHtml(item.item_name)}</h5>
              <p class="card-text text-muted small flex-grow-1">${escapeHtml(item.description || '')}</p>
              <div class="d-flex justify-content-between align-items-center mt-3">
                <span class="menu-item-price">₱${parseFloat(item.price).toFixed(2)}</span>
                <div class="d-flex align-items-center gap-2">
                  <button class="qty-btn" onclick="updateQty('${item.item_id}', -1)">-</button>
                  <input type="text" class="qty-input" id="qty-${item.item_id}" value="1" readonly>
                  <button class="qty-btn" onclick="updateQty('${item.item_id}', 1)">+</button>
                </div>
              </div>
              <button class="add-to-cart-btn w-100 mt-3" onclick="addToCart('${item.item_id}')">
                <i class="fa fa-plus me-1"></i> Add to Order
              </button>
            </div>
          </div>
        </div>
      `;
      }).join('');
    }

    function updateQty(itemId, delta) {
      const input = document.getElementById(`qty-${itemId}`);
      let val = parseInt(input.value) + delta;
      if (val < 1) val = 1;
      if (val > 20) val = 20;
      input.value = val;
    }

    function addToCart(itemId) {
      const item = menuItems.find(i => i.item_id === itemId);
      const qty = parseInt(document.getElementById(`qty-${itemId}`).value);

      // Fix image path for cart
      let cartImgUrl = '../uploads/foods/default-food.png';
      if (item.image_url) {
        if (item.image_url.startsWith('http')) {
          cartImgUrl = item.image_url;
        } else if (item.image_url.startsWith('/uploads/foods/')) {
          cartImgUrl = '..' + item.image_url;
        } else if (item.image_url.startsWith('/')) {
          cartImgUrl = '..' + item.image_url;
        } else {
          cartImgUrl = '../uploads/foods/' + item.image_url;
        }
      } else if (item.thumbnail_url) {
        if (item.thumbnail_url.startsWith('http')) {
          cartImgUrl = item.thumbnail_url;
        } else if (item.thumbnail_url.startsWith('/uploads/foods/')) {
          cartImgUrl = '..' + item.thumbnail_url;
        } else if (item.thumbnail_url.startsWith('/')) {
          cartImgUrl = '..' + item.thumbnail_url;
        } else {
          cartImgUrl = '../uploads/foods/' + item.thumbnail_url;
        }
      }

      const existing = cart.find(c => c.item_id === itemId);
      if (existing) {
        existing.qty += qty;
      } else {
        cart.push({
          item_id: itemId,
          name: item.item_name,
          price: parseFloat(item.price),
          image: cartImgUrl,
          qty: qty
        });
      }

      saveCart();
      updateCartUI();

      // Reset quantity
      document.getElementById(`qty-${itemId}`).value = 1;

      // Show feedback
      const btn = document.querySelector(`button[onclick="addToCart('${itemId}')"]`);
      const originalText = btn.innerHTML;
      btn.innerHTML = '<i class="fa fa-check me-1"></i> Added!';
      btn.style.background = '#28a745';
      setTimeout(() => {
        btn.innerHTML = originalText;
        btn.style.background = '';
      }, 1500);
    }

    function removeFromCart(itemId) {
      cart = cart.filter(c => c.item_id !== itemId);
      saveCart();
      updateCartUI();
    }

    function updateCartItemQty(itemId, delta) {
      const item = cart.find(c => c.item_id === itemId);
      if (item) {
        item.qty += delta;
        if (item.qty < 1) {
          removeFromCart(itemId);
          return;
        }
        saveCart();
        updateCartUI();
      }
    }

    function saveCart() {
      localStorage.setItem('foodCart', JSON.stringify(cart));
    }

    function updateCartUI() {
      const count = cart.reduce((sum, item) => sum + item.qty, 0);
      document.getElementById('cartCount').textContent = count;

      const cartItems = document.getElementById('cartItems');
      const checkoutBtn = document.getElementById('checkoutBtn');

      if (cart.length === 0) {
        cartItems.innerHTML = '<p class="text-muted text-center py-4">Your cart is empty</p>';
        checkoutBtn.disabled = true;
        document.getElementById('cartTotal').textContent = '₱0.00';
        return;
      }

      checkoutBtn.disabled = false;

      // Calculate total (tax-inclusive: prices already include VAT)
      const total = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);

      // Update display
      document.getElementById('cartTotal').textContent = '₱' + total.toFixed(2);

      cartItems.innerHTML = cart.map(item => `
        <div class="d-flex align-items-center gap-3 mb-3 p-2 border rounded">
          <img src="${item.image}" class="cart-item-img" alt="${escapeHtml(item.name)}">
          <div class="flex-grow-1">
            <h6 class="mb-1">${escapeHtml(item.name)}</h6>
            <p class="text-muted mb-0">₱${item.price.toFixed(2)}</p>
          </div>
          <div class="d-flex align-items-center gap-2">
            <button class="qty-btn" onclick="updateCartItemQty('${item.item_id}', -1)">-</button>
            <span class="mx-2">${item.qty}</span>
            <button class="qty-btn" onclick="updateCartItemQty('${item.item_id}', 1)">+</button>
          </div>
          <button class="btn btn-link text-danger" onclick="removeFromCart('${item.item_id}')">
            <i class="fa fa-trash"></i>
          </button>
        </div>
      `).join('');
    }

    // Order type selection
    document.querySelectorAll('.order-type-btn').forEach(btn => {
      btn.addEventListener('click', function() {
        document.querySelectorAll('.order-type-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');

        const type = this.dataset.type;
        document.getElementById('tableInput').style.display = type === 'dine_in' ? 'block' : 'none';
        document.getElementById('roomSelection').style.display = type === 'room_service' ? 'block' : 'none';
      });
    });

    // Load rooms for room service
    async function loadRooms() {
      // If user is logged in and has reservation, load their rooms
      try {
        const propertyParam = currentProperty ? `&property_id=${currentProperty}` : '';
        const res = await fetch(`api/food-menu.php?action=rooms${propertyParam}`);
        const data = await res.json();
        const selector = document.getElementById('roomSelector');
        const roomSelectionDiv = document.getElementById('roomSelection');
        if (data.success && data.data.length > 0) {
          selector.innerHTML = '<option value="">Select Room</option>' +
            data.data.map(r => {
              // Get guest name from walk-in reservation
              const guestName = (r.guest_name && r.guest_name.trim()) ? r.guest_name : '';

              // Build room label with guest name
              let roomLabel = currentProperty ?
                `${escapeHtml(r.room_number)} - ${escapeHtml(r.room_name || 'Room')}` :
                `${escapeHtml(r.property_name)} - ${escapeHtml(r.room_number)}`;

              // Add guest name if available
              if (guestName) {
                roomLabel += ` (${escapeHtml(guestName)})`;
              }

              // Add notes indicator if notes exist
              const notesIndicator = r.notes ? ' 📝' : '';

              return `<option value="${r.room_id}" data-notes="${escapeHtml(r.notes || '')}" data-guest="${escapeHtml(guestName)}">${roomLabel}${notesIndicator}</option>`;
            }).join('');
          // Remove any existing info message if rooms are available
          const existingMsg = roomSelectionDiv.querySelector('.room-info-msg');
          if (existingMsg) existingMsg.remove();
        } else {
          // No checked-in guests available for room service
          selector.innerHTML = '<option value="">No checked-in guests</option>';
          // Add info message below the selector
          let infoMsg = roomSelectionDiv.querySelector('.room-info-msg');
          if (!infoMsg) {
            infoMsg = document.createElement('div');
            infoMsg.className = 'room-info-msg alert alert-info mt-2 small';
            infoMsg.innerHTML = '<i class="fa fa-info-circle me-1"></i> Room service is only available for guests who are currently checked in. Please select <strong>Dine In</strong> or <strong>Takeout</strong> instead.';
            roomSelectionDiv.appendChild(infoMsg);
          }
        }
      } catch (err) {
        console.error('Failed to load rooms:', err);
        document.getElementById('roomSelector').innerHTML = '<option value="">Error loading rooms</option>';
      }
    }

    // Load tables for dine-in
    async function loadTables() {
      try {
        const propertyParam = currentProperty ? `&property_id=${currentProperty}` : '';
        const res = await fetch(`api/food-menu.php?action=tables${propertyParam}`);
        const data = await res.json();
        const selector = document.getElementById('tableSelector');
        const tableInfo = document.getElementById('tableInfo');

        if (data.success && data.data.length > 0) {
          selector.innerHTML = '<option value="">Select a table</option>' +
            data.data.map(t => {
              const label = t.table_name ?
                `${escapeHtml(t.table_number)} - ${escapeHtml(t.table_name)}` :
                `Table ${escapeHtml(t.table_number)}`;
              const occupiedBadge = t.is_occupied == 1 ? ' 🔴' : ' 🟢';
              return `<option value="${t.table_id}" data-capacity="${t.capacity}" data-location="${escapeHtml(t.location || '')}" data-occupied="${t.is_occupied}">${label}${occupiedBadge}</option>`;
            }).join('');

          // Add change event to show table info
          selector.onchange = function() {
            const selected = this.options[this.selectedIndex];
            if (selected.value) {
              const capacity = selected.dataset.capacity;
              const location = selected.dataset.location;
              const occupied = selected.dataset.occupied;
              let info = [];
              if (capacity) info.push(`Capacity: ${capacity}`);
              if (location) info.push(`Location: ${location}`);
              info.push(occupied == 1 ? 'Status: Occupied' : 'Status: Available');
              tableInfo.innerHTML = info.join(' | ');
            } else {
              tableInfo.innerHTML = '';
            }
          };
        } else {
          selector.innerHTML = '<option value="">No tables available</option>';
          tableInfo.innerHTML = '<span class="text-warning"><i class="fa fa-exclamation-triangle me-1"></i>Please ask staff to configure tables</span>';
        }
      } catch (err) {
        console.error('Failed to load tables:', err);
        document.getElementById('tableSelector').innerHTML = '<option value="">Error loading tables</option>';
        document.getElementById('tableInfo').innerHTML = '';
      }
    }

    // Toast notification function
    function showToast(message, type = 'info', duration = 4000) {
      const container = document.getElementById('toastContainer');
      const toast = document.createElement('div');
      toast.className = `custom-toast ${type}`;

      const icons = {
        error: 'fa-exclamation-circle',
        success: 'fa-check-circle',
        info: 'fa-info-circle'
      };

      toast.innerHTML = `
        <i class="fa ${icons[type]}"></i>
        <span class="toast-message">${message}</span>
        <button class="toast-close" onclick="this.parentElement.remove()">&times;</button>
      `;

      container.appendChild(toast);

      setTimeout(() => {
        toast.style.animation = 'fadeOut 0.3s ease';
        setTimeout(() => toast.remove(), 300);
      }, duration);
    }

    // Checkout
    document.getElementById('checkoutBtn').addEventListener('click', async function() {
      const orderType = document.querySelector('.order-type-btn.active').dataset.type;
      const tableId = document.getElementById('tableSelector').value;
      const roomId = document.getElementById('roomSelector').value;
      const customerEmail = document.getElementById('customerEmail').value;
      const notes = document.getElementById('specialRequests').value;

      if (orderType === 'dine_in' && !tableId) {
        showToast('Please select a table', 'error');
        return;
      }

      if (orderType === 'room_service' && !roomId) {
        showToast('Please select your room', 'error');
        return;
      }

      // Get table number for display if dine-in
      let tableNumber = null;
      if (orderType === 'dine_in' && tableId) {
        const tableSelect = document.getElementById('tableSelector');
        const selectedOption = tableSelect.options[tableSelect.selectedIndex];
        tableNumber = selectedOption.text.split(' ')[0].replace('Table ', '');
      }

      const orderData = {
        property_id: currentProperty || null, // null for takeout when no property selected
        order_type: orderType,
        table_id: tableId || null,
        table_number: tableNumber,
        room_id: roomId || null,
        customer_email: customerEmail || null,
        notes: notes,
        items: cart.map(item => ({
          item_id: item.item_id,
          qty: item.qty,
          unit_price: item.price
        }))
      };

      try {
        const res = await fetch('api/food-order.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify(orderData)
        });

        const data = await res.json();

        if (data.success) {
          // Clear cart
          cart = [];
          saveCart();
          updateCartUI();

          // Show success
          document.getElementById('orderNumber').textContent = data.order_number;
          bootstrap.Modal.getInstance(document.getElementById('cartModal')).hide();
          new bootstrap.Modal(document.getElementById('successModal')).show();
        } else {
          showToast('Failed to place order: ' + (data.error || 'Unknown error'), 'error');
        }
      } catch (err) {
        showToast('Failed to place order. Please try again.', 'error');
        console.error(err);
      }
    });

    function escapeHtml(text) {
      if (!text) return '';
      const div = document.createElement('div');
      div.textContent = text;
      return div.innerHTML;
    }
  </script>
</body>

</html>