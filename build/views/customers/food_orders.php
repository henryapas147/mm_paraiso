<?php
// Ensure user is logged in as customer
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || ($_SESSION['role_code'] ?? '') !== 'customer') {
    header('Location: ' . dirname($_SERVER['SCRIPT_NAME']) . '/sign-in');
    exit;
}
?>

<style>
@import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;500;600&family=DM+Sans:wght@300;400;500&display=swap');

:root {
    --gold: #C9A96E;
    --gold-light: #E8D5B0;
    --gold-dark: #A07840;
    --ink: #1A1714;
    --ink-soft: #3D3530;
    --parchment: #FAF7F2;
    --card-bg: #FFFFFF;
    --border: rgba(201,169,110,0.2);
    --border-table: #F0EBE1;
}

.food-wrap {
    font-family: 'DM Sans', sans-serif;
    color: var(--ink);
    padding: 0 0 2rem;
}

/* ── Page header card ── */
.header-card {
    background: var(--card-bg);
    border: 1px solid var(--border);
    border-radius: 16px;
    padding: 2rem 2.25rem;
    margin-bottom: 1.75rem;
}

.page-header {
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    gap: 1rem;
}

.page-eyebrow {
    font-size: 0.68rem;
    font-weight: 500;
    letter-spacing: 0.18em;
    text-transform: uppercase;
    color: var(--gold);
    margin-bottom: 0.3rem;
}

.page-title {
    font-family: 'Cormorant Garamond', serif;
    font-size: 2.2rem;
    font-weight: 400;
    color: var(--ink);
    margin: 0;
    line-height: 1.1;
}

.page-sub {
    font-size: 0.82rem;
    color: #9CA3AF;
    margin-top: 0.3rem;
    font-weight: 300;
}

/* ── Table card ── */
.table-card {
    background: var(--card-bg);
    border: 1px solid var(--border);
    border-radius: 16px;
    overflow: hidden;
}

.table-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1.25rem 1.5rem;
    border-bottom: 1px solid var(--border-table);
}

.table-title {
    font-family: 'Cormorant Garamond', serif;
    font-size: 1.15rem;
    font-weight: 500;
    color: var(--ink);
    margin: 0;
}

/* ── Orders list ── */
.orders-list {
    padding: 0;
}

.order-item {
    border-bottom: 1px solid var(--border-table);
    padding: 1.5rem;
}

.order-item:last-child {
    border-bottom: none;
}

.order-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 1rem;
}

.order-number {
    font-weight: 600;
    font-size: 0.95rem;
    color: var(--ink);
}

.order-status {
    display: inline-block;
    padding: 0.35rem 0.85rem;
    border-radius: 50px;
    font-size: 0.75rem;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.status-pending {
    background: #FEF3C7;
    color: #92400E;
}

.status-preparing {
    background: #DBEAFE;
    color: #1E40AF;
}

.status-ready {
    background: #D1FAE5;
    color: #065F46;
}

.status-delivered {
    background: #E0E7FF;
    color: #3730A3;
}

.status-cancelled {
    background: #FEE2E2;
    color: #991B1B;
}

.order-meta {
    display: flex;
    gap: 1.5rem;
    font-size: 0.8rem;
    color: #6B7280;
    margin-bottom: 1rem;
}

.order-meta span {
    display: flex;
    align-items: center;
    gap: 0.4rem;
}

.order-meta i {
    color: var(--gold);
}

/* ── Order items ── */
.order-items {
    background: var(--parchment);
    border-radius: 12px;
    padding: 1rem;
    margin-top: 1rem;
}

.order-item-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0.75rem 0;
    border-bottom: 1px dashed var(--border);
}

.order-item-row:last-child {
    border-bottom: none;
}

.item-info {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.item-qty {
    background: var(--gold);
    color: white;
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    font-size: 0.75rem;
    font-weight: 600;
}

.item-name {
    font-weight: 500;
    font-size: 0.9rem;
}

.item-price {
    color: var(--gold-dark);
    font-weight: 500;
    font-size: 0.9rem;
}

.order-total {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px solid var(--border);
    font-weight: 600;
    font-size: 1rem;
}

.order-total .amount {
    color: var(--gold-dark);
    font-size: 1.1rem;
}

/* ── Empty state ── */
.empty-state {
    text-align: center;
    padding: 4rem 2rem;
}

.empty-state i {
    font-size: 3rem;
    color: var(--gold-light);
    margin-bottom: 1rem;
}

.empty-state h3 {
    font-family: 'Cormorant Garamond', serif;
    font-size: 1.5rem;
    color: var(--ink);
    margin-bottom: 0.5rem;
}

.empty-state p {
    color: #9CA3AF;
    font-size: 0.9rem;
}

/* ── New Order Button ── */
.btn-new-order {
    background: linear-gradient(135deg, var(--gold) 0%, var(--gold-dark) 100%);
    color: white;
    border: none;
    padding: 0.75rem 1.5rem;
    border-radius: 10px;
    font-size: 0.85rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-new-order:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(201, 169, 110, 0.3);
    color: white;
}

/* ── Status History ── */
.status-history {
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px solid var(--border);
}

.status-history-header {
    font-size: 0.85rem;
    font-weight: 600;
    color: var(--ink);
    margin-bottom: 0.75rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.status-history-header i {
    color: var(--gold);
}

.status-history-list {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.status-history-item {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
    padding: 0.5rem 0;
    font-size: 0.8rem;
}

.status-history-time {
    color: #9CA3AF;
    font-size: 0.75rem;
    min-width: 80px;
}

.status-history-change {
    flex: 1;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.status-history-old {
    color: #9CA3AF;
    text-decoration: line-through;
}

.status-history-new {
    color: var(--ink);
    font-weight: 500;
}

.status-history-user {
    color: #6B7280;
    font-size: 0.75rem;
}

.btn-toggle-history {
    background: transparent;
    border: 1px solid var(--border);
    color: var(--gold-dark);
    padding: 0.4rem 0.8rem;
    border-radius: 8px;
    font-size: 0.75rem;
    cursor: pointer;
    transition: all 0.2s;
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
}

.btn-toggle-history:hover {
    background: var(--parchment);
    border-color: var(--gold);
}

/* ── Responsive ── */
@media (max-width: 768px) {
    .page-header {
        flex-direction: column;
        align-items: flex-start;
    }

    .order-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }

    .order-meta {
        flex-wrap: wrap;
        gap: 0.75rem;
    }
}
</style>

<div class="food-wrap">
    <div class="header-card">
        <div class="page-header">
            <div class="page-header-left">
                <div class="page-eyebrow">Room Service</div>
                <h1 class="page-title">My Food Orders</h1>
                <p class="page-sub">Track and manage your room service orders</p>
            </div>
            <a href="<?php echo dirname($_SERVER['SCRIPT_NAME']); ?>/food-menu" class="btn-new-order">
                <i class="ni ni-cart"></i> Order Food
            </a>
        </div>
    </div>

    <div class="table-card">
        <div class="table-header">
            <h2 class="table-title">Order History</h2>
        </div>

        <div id="ordersContainer">
            <!-- Orders will be loaded here via JavaScript -->
        </div>
    </div>
</div>

<script>
async function loadFoodOrders() {
    const container = document.getElementById('ordersContainer');
    container.innerHTML = '<div class="empty-state"><i class="ni ni-spaceship"></i><h3>Loading...</h3></div>';

    try {
        const response = await fetch('../api/food_orders.php?action=my_orders');
        const result = await response.json();

        if (!result.success) {
            container.innerHTML = '<div class="empty-state"><i class="ni ni-fat-remove"></i><h3>Error loading orders</h3><p>Please try again later.</p></div>';
            return;
        }

        const orders = result.data || [];

        if (orders.length === 0) {
            container.innerHTML = `
                <div class="empty-state">
                    <i class="ni ni-cart"></i>
                    <h3>No orders yet</h3>
                    <p>You haven't placed any food orders. Order room service from our menu!</p>
                </div>
            `;
            return;
        }

        let html = '<div class="orders-list">';
        orders.forEach(order => {
            const orderStatus = order.order_status || 'pending';
            const statusLabel = orderStatus.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
            const historyId = `history-${order.order_id}`;

            html += `
                <div class="order-item">
                    <div class="order-header">
                        <span class="order-number">Order #${escapeHtml(order.order_number)}</span>
                        <span class="order-status status-${orderStatus}">${statusLabel}</span>
                    </div>

                    <div class="order-meta">
                        <span><i class="ni ni-building"></i> ${escapeHtml(order.property_name || 'N/A')}</span>
                        ${order.room_number ? `<span><i class="ni ni-shop"></i> Room ${escapeHtml(order.room_number)}</span>` : ''}
                        <span><i class="ni ni-calendar-grid-58"></i> ${formatDate(order.created_at)}</span>
                        ${order.reservation_code ? `<span><i class="ni ni-key-25"></i> ${escapeHtml(order.reservation_code)}</span>` : ''}
                    </div>

                    <div class="order-items">
                        ${(order.items || []).map(item => `
                            <div class="order-item-row">
                                <div class="item-info">
                                    <span class="item-qty">${item.quantity}</span>
                                    <span class="item-name">${escapeHtml(item.item_name)}</span>
                                </div>
                                <span class="item-price">₱${formatPrice(item.total_price)}</span>
                            </div>
                        `).join('')}

                        <div class="order-total">
                            <span>Total</span>
                            <span class="amount">₱${formatPrice(order.total_amount)}</span>
                        </div>
                    </div>

                    ${order.notes ? `<div style="margin-top: 1rem; font-size: 0.8rem; color: #6B7280; font-style: italic;"><i class="ni ni-tag"></i> Note: ${escapeHtml(order.notes)}</div>` : ''}

                    <div class="status-history">
                        <button class="btn-toggle-history" onclick="toggleStatusHistory('${order.order_id}')">
                            <i class="ni ni-time-alarm"></i> View Status History
                        </button>
                        <div id="${historyId}" class="status-history-list" style="display:none;">
                            <div style="padding: 0.5rem; color: #9CA3AF; font-size: 0.8rem;">Loading...</div>
                        </div>
                    </div>
                </div>
            `;
        });
        html += '</div>';
        container.innerHTML = html;

    } catch (error) {
        console.error('Error loading food orders:', error);
        container.innerHTML = '<div class="empty-state"><i class="ni ni-fat-remove"></i><h3>Error loading orders</h3><p>Please try again later.</p></div>';
    }
}

async function toggleStatusHistory(orderId) {
    const historyContainer = document.getElementById(`history-${orderId}`);
    const button = historyContainer.previousElementSibling;
    
    if (historyContainer.style.display === 'none') {
        historyContainer.style.display = 'flex';
        button.innerHTML = '<i class="ni ni-fat-delete"></i> Hide Status History';
        
        // Fetch status history
        try {
            const response = await fetch(`../api/food_orders.php?action=status_history&id=${orderId}`);
            const result = await response.json();
            
            if (result.success && result.data.length > 0) {
                historyContainer.innerHTML = result.data.map(h => `
                    <div class="status-history-item">
                        <div class="status-history-time">${formatDate(h.created_at)}</div>
                        <div class="status-history-change">
                            <span class="status-history-old">${h.old_status || '—'}</span>
                            <i class="ni ni-bold-right" style="font-size: 10px; color: #9CA3AF;"></i>
                            <span class="status-history-new">${h.new_status}</span>
                        </div>
                        <div class="status-history-user">${h.first_name ? h.first_name + ' ' + h.last_name : 'System'}</div>
                    </div>
                `).join('');
            } else {
                historyContainer.innerHTML = '<div style="padding: 0.5rem; color: #9CA3AF; font-size: 0.8rem;">No status history yet.</div>';
            }
        } catch (error) {
            console.error('Error loading status history:', error);
            historyContainer.innerHTML = '<div style="padding: 0.5rem; color: #991B1B; font-size: 0.8rem;">Error loading history.</div>';
        }
    } else {
        historyContainer.style.display = 'none';
        button.innerHTML = '<i class="ni ni-time-alarm"></i> View Status History';
    }
}

function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function formatPrice(amount) {
    const num = parseFloat(amount) || 0;
    return num.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
}

function formatDate(dateStr) {
    if (!dateStr) return 'N/A';
    const date = new Date(dateStr);
    return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }) +
           ' ' + date.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });
}

// Load orders on page load
document.addEventListener('DOMContentLoaded', loadFoodOrders);
</script>
