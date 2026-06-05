<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Lora:ital,wght@0,600;1,500&display=swap" rel="stylesheet">

<script>
  document.addEventListener('DOMContentLoaded', () => {
    loadBillingData();
  });

  async function loadBillingData() {
    try {
      // Load all data in parallel
      const [summaryRes, invoicesRes, transactionsRes, companyRes, methodsRes] = await Promise.all([
        fetch('../api/billing.php?action=dashboard_summary'),
        fetch('../api/billing.php?action=recent_invoices&limit=5'),
        fetch('../api/billing.php?action=recent_transactions&limit=10'),
        fetch('../api/billing.php?action=company_info'),
        fetch('../api/billing.php?action=payment_methods')
      ]);

      const summary = await summaryRes.json();
      const invoices = await invoicesRes.json();
      const transactions = await transactionsRes.json();
      const company = await companyRes.json();
      const methods = await methodsRes.json();

      if (summary.success) updateSummary(summary.data);
      if (invoices.success) renderInvoices(invoices.data);
      if (transactions.success) renderTransactions(transactions.data);
      if (company.success) {
        window.companyData = company.data;
        renderCompanyInfo(company.data);
      }
      if (methods.success) renderPaymentMethods(methods.data);
    } catch (e) {
      console.error('Error loading billing data:', e);
    }
  }

  function updateSummary(data) {
    // Update mini-cards with real revenue data
    const salaryCard = document.querySelector('.mini-card:nth-child(1)');
    const paypalCard = document.querySelector('.mini-card:nth-child(2)');
    
    if (salaryCard && data.total_revenue !== undefined) {
      salaryCard.querySelector('h6').textContent = 'Total Revenue';
      salaryCard.querySelector('span').textContent = 'All Time';
      salaryCard.querySelector('.amount').textContent = '₱' + parseFloat(data.total_revenue || 0).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
    }
    
    if (paypalCard && data.pending_payments !== undefined) {
      paypalCard.querySelector('h6').textContent = 'Pending';
      paypalCard.querySelector('span').textContent = 'Awaiting Confirmation';
      paypalCard.querySelector('.amount').textContent = '₱' + parseFloat(data.pending_payments || 0).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
    }
  }

  function renderInvoices(data) {
    const list = document.querySelector('.invoice-list');
    if (!list || !data || data.length === 0) return;
    
    list.innerHTML = data.map(inv => {
      const date = inv.invoice_date ? formatDate(inv.invoice_date) : { main: '—', day: '' };
      const statusClass = getInvoiceStatusClass(inv.status);
      const statusLabel = capitalize(inv.status || 'unknown');
      
      return `
        <li class="invoice-item">
          <div>
            <h6>${date.main}</h6>
            <span>${inv.invoice_number || inv.reservation_code || '—'}</span>
          </div>
          <div style="display:flex;align-items:center;gap:12px;">
            <span class="invoice-status-badge ${statusClass}">${statusLabel}</span>
            <span class="amount">₱${parseFloat(inv.total_amount || 0).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})}</span>
            <button class="pdf-btn" onclick="viewInvoice('${inv.invoice_id}')"><i class="fas fa-file-pdf"></i> PDF</button>
          </div>
        </li>
      `;
    }).join('');
  }

  function getInvoiceStatusClass(status) {
    const classes = {
      'paid': 'status-paid',
      'partial': 'status-pending',
      'sent': 'status-pending',
      'draft': 'status-unknown',
      'overdue': 'status-failed',
      'void': 'status-unknown'
    };
    return classes[status] || 'status-unknown';
  }

  function renderTransactions(data) {
    const newestSection = document.querySelector('.transaction-section:first-of-type');
    const yesterdaySection = document.querySelector('.transaction-section:last-of-type');
    
    if (!data || data.length === 0) return;
    
    // Split transactions: first 2 as newest, rest as yesterday
    const newest = data.slice(0, 2);
    const yesterday = data.slice(2);
    
    if (newestSection) {
      newestSection.querySelector('h6').textContent = 'Recent';
      const container = newestSection.querySelector('.transaction-item')?.parentElement;
      if (container) {
        container.innerHTML = newest.map(t => createTransactionRow(t)).join('');
      }
    }
    
    if (yesterdaySection && yesterday.length > 0) {
      yesterdaySection.querySelector('h6').textContent = 'Earlier';
      const container = yesterdaySection.querySelector('.transaction-item')?.parentElement;
      if (container) {
        container.innerHTML = yesterday.map(t => createTransactionRow(t)).join('');
      }
    }
  }

  function createTransactionRow(t) {
    const date = t.payment_date ? formatDate(t.payment_date) : { main: '—', day: '' };
    const isConfirmed = t.payment_status === 'confirmed';
    const iconClass = isConfirmed ? 'up' : 'pending';
    const amountClass = isConfirmed ? 'positive' : 'negative';
    const amountPrefix = isConfirmed ? '+' : '-';
    const customerName = t.first_name && t.last_name ? `${t.first_name} ${t.last_name}` : t.reservation_code || 'Guest';
    
    return `
      <div class="transaction-item">
        <div style="display: flex; align-items: center">
          <div class="icon ${iconClass}"><i class="fas fa-arrow-${isConfirmed ? 'up' : 'down'}"></i></div>
          <div>
            <h6>${customerName}</h6>
            <span>${date.main} at ${t.payment_time || '—'}</span>
          </div>
        </div>
        <span class="amount ${amountClass}">${amountPrefix} ₱${parseFloat(t.amount || 0).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})}</span>
      </div>
    `;
  }

  function renderCompanyInfo(data) {
    if (!data) return;
    
    const billingInfo = document.getElementById('billingInfo');
    if (!billingInfo) return;
    
    // Rebuild the billing item with company data
    billingInfo.innerHTML = `
      <div class="billing-item">
        <div>
          <h6>${data.company_name || 'Company Name'}</h6>
          <span>Company Name: <strong>${data.legal_name || data.company_name || '—'}</strong></span>
          <span>Email Address: <strong>${data.email || '—'}</strong></span>
          <span>Tax ID: <strong>${data.tax_id || '—'}</strong></span>
        </div>
        <div class="actions">
          <a class="edit" onclick="editCompanyInfo()"><i class="fas fa-pencil-alt"></i> Edit</a>
        </div>
      </div>
    `;
  }

  function renderPaymentMethods(data) {
    const container = document.querySelector('.card-body .payment-method-item')?.parentElement;
    if (!container || !data || data.length === 0) return;
    
    container.innerHTML = data.map(m => `
      <div class="payment-method-item">
        <div class="method-icon" style="width: 40px; height: 40px; border-radius: 8px; background: #F3F4F6; display: flex; align-items: center; justify-content: center; margin-right: 12px; font-size: 1rem; color: #6B7280;">
          <i class="${getMethodIcon(m.method_type)}"></i>
        </div>
        <h6>${m.method_label}</h6>
        <i class="fas fa-pencil-alt edit-icon"></i>
      </div>
    `).join('');
  }

  function getMethodIcon(type) {
    const t = type?.toLowerCase() || '';
    if (t.includes('card') || t.includes('credit') || t.includes('debit')) return 'ni ni-credit-card';
    if (t.includes('cash')) return 'ni ni-money-coins';
    if (t.includes('digital') || t.includes('wallet') || t.includes('gcash') || t.includes('maya')) return 'ni ni-mobile-button';
    if (t.includes('bank') || t.includes('transfer')) return 'ni ni-bank';
    return 'ni ni-single-02';
  }

  function capitalize(str) {
    return str.charAt(0).toUpperCase() + str.slice(1);
  }

  function formatDate(dateStr) {
    const date = new Date(dateStr);
    return {
      main: date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }),
      day: date.toLocaleDateString('en-US', { weekday: 'long' })
    };
  }

  function viewInvoice(invoiceId) {
    // TODO: Implement PDF generation or invoice detail view
    alert('View invoice: ' + invoiceId);
  }

  function addPaymentMethod() {
    // TODO: Implement add payment method modal/form
    alert('Add payment method modal - to be implemented');
  }

  function viewAllInvoices() {
    // TODO: Navigate to full invoices page or show modal
    alert('View all invoices - to be implemented');
  }

  function editCompanyInfo() {
    const companyData = window.companyData || {};
    
    const modal = document.createElement('div');
    modal.style.cssText = 'position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.5);display:flex;align-items:center;justify-content:center;z-index:9999;';
    modal.innerHTML = `
      <div style="background:#fff;border-radius:12px;padding:24px;max-width:500px;width:90%;max-height:90vh;overflow-y:auto;">
        <h3 style="margin:0 0 16px;font-family:'Lora',serif;font-size:18px;">Edit Company Information</h3>
        <form id="companyInfoForm">
          <div style="margin-bottom:12px;">
            <label style="display:block;font-size:12px;font-weight:500;margin-bottom:4px;">Company Name</label>
            <input type="text" name="company_name" value="${companyData.company_name || ''}" style="width:100%;padding:8px;border:1px solid #e0e0e0;border-radius:6px;" required>
          </div>
          <div style="margin-bottom:12px;">
            <label style="display:block;font-size:12px;font-weight:500;margin-bottom:4px;">Legal Name</label>
            <input type="text" name="legal_name" value="${companyData.legal_name || ''}" style="width:100%;padding:8px;border:1px solid #e0e0e0;border-radius:6px;">
          </div>
          <div style="margin-bottom:12px;">
            <label style="display:block;font-size:12px;font-weight:500;margin-bottom:4px;">Tax ID</label>
            <input type="text" name="tax_id" value="${companyData.tax_id || ''}" style="width:100%;padding:8px;border:1px solid #e0e0e0;border-radius:6px;">
          </div>
          <div style="margin-bottom:12px;">
            <label style="display:block;font-size:12px;font-weight:500;margin-bottom:4px;">Email</label>
            <input type="email" name="email" value="${companyData.email || ''}" style="width:100%;padding:8px;border:1px solid #e0e0e0;border-radius:6px;" required>
          </div>
          <div style="margin-bottom:12px;">
            <label style="display:block;font-size:12px;font-weight:500;margin-bottom:4px;">Phone</label>
            <input type="text" name="phone_number" value="${companyData.phone_number || ''}" style="width:100%;padding:8px;border:1px solid #e0e0e0;border-radius:6px;" required>
          </div>
          <div style="margin-bottom:12px;">
            <label style="display:block;font-size:12px;font-weight:500;margin-bottom:4px;">Address Line 1</label>
            <input type="text" name="address_line1" value="${companyData.address_line1 || ''}" style="width:100%;padding:8px;border:1px solid #e0e0e0;border-radius:6px;" required>
          </div>
          <div style="margin-bottom:12px;">
            <label style="display:block;font-size:12px;font-weight:500;margin-bottom:4px;">City</label>
            <input type="text" name="city" value="${companyData.city || ''}" style="width:100%;padding:8px;border:1px solid #e0e0e0;border-radius:6px;" required>
          </div>
          <div style="margin-bottom:12px;">
            <label style="display:block;font-size:12px;font-weight:500;margin-bottom:4px;">Province</label>
            <input type="text" name="province" value="${companyData.province || ''}" style="width:100%;padding:8px;border:1px solid #e0e0e0;border-radius:6px;">
          </div>
          <div style="margin-bottom:12px;">
            <label style="display:block;font-size:12px;font-weight:500;margin-bottom:4px;">Postal Code</label>
            <input type="text" name="postal_code" value="${companyData.postal_code || ''}" style="width:100%;padding:8px;border:1px solid #e0e0e0;border-radius:6px;">
          </div>
          <div style="margin-bottom:12px;">
            <label style="display:block;font-size:12px;font-weight:500;margin-bottom:4px;">Website</label>
            <input type="url" name="website" value="${companyData.website || ''}" style="width:100%;padding:8px;border:1px solid #e0e0e0;border-radius:6px;">
          </div>
          <div style="margin-bottom:16px;">
            <label style="display:block;font-size:12px;font-weight:500;margin-bottom:4px;">Description</label>
            <textarea name="description" rows="3" style="width:100%;padding:8px;border:1px solid #e0e0e0;border-radius:6px;">${companyData.description || ''}</textarea>
          </div>
          <input type="hidden" name="company_id" value="${companyData.company_id || ''}">
          <div style="display:flex;gap:8px;justify-content:flex-end;">
            <button type="button" onclick="this.closest('div[style*=fixed]').remove()" style="padding:8px 16px;border:1px solid #e0e0e0;border-radius:6px;background:#fff;cursor:pointer;">Cancel</button>
            <button type="submit" style="padding:8px 16px;border:none;border-radius:6px;background:#185FA5;color:#fff;cursor:pointer;">Save</button>
          </div>
        </form>
      </div>
    `;
    
    document.body.appendChild(modal);
    
    modal.querySelector('#companyInfoForm').addEventListener('submit', async (e) => {
      e.preventDefault();
      const formData = new FormData(e.target);
      const data = Object.fromEntries(formData.entries());
      
      try {
        const res = await fetch('../api/billing.php?action=update_company_info', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(data)
        });
        const result = await res.json();
        
        if (result.success) {
          modal.remove();
          // Reload company info
          const companyRes = await fetch('../api/billing.php?action=company_info');
          const company = await companyRes.json();
          if (company.success) {
            window.companyData = company.data;
            renderCompanyInfo(company.data);
          }
          alert('Company information updated successfully');
        } else {
          alert('Error: ' + result.message);
        }
      } catch (err) {
        alert('Error updating company information');
      }
    });
  }
</script>

<style>
  * {
    box-sizing: border-box;
    margin: 0;
    padding: 0
  }

  body {
    font-family: 'DM Sans', system-ui, -apple-system, sans-serif;
    background: #f5f5f5;
    color: #1a1a1a
  }

  .page {
    padding: 24px;
    max-width: 100%;
    margin: 0 auto
  }

  .dashboard-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 24px;
    margin-bottom: 24px
  }

  @media (max-width: 1024px) {
    .dashboard-grid {
      grid-template-columns: 1fr
    }
  }

  .card {
    background: #fff;
    border: 1px solid #e0e0e0;
    border-radius: 8px
  }

  .card-header {
    padding: 16px 20px;
    border-bottom: 1px solid #e0e0e0;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: wrap
  }

  .card-title {
    font-family: 'Lora', Georgia, serif;
    font-size: 16px;
    font-weight: 600;
    font-style: italic
  }

  .card-body {
    padding: 20px
  }

  .btn {
    padding: 8px 16px;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 500;
    cursor: pointer;
    border: none;
    transition: all .2s
  }

  .btn-primary {
    background: #185FA5;
    color: #fff
  }

  .btn-primary:hover {
    background: #145090
  }

  .btn-secondary {
    background: #fff;
    color: #1a1a1a;
    border: 1px solid #e0e0e0
  }

  .btn-secondary:hover {
    background: #f5f5f5
  }

  .btn-outline {
    background: transparent;
    color: #185FA5;
    border: 1px solid #185FA5
  }

  .btn-outline:hover {
    background: #f0f7ff
  }

  .credit-card {
    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
    border-radius: 12px;
    padding: 24px;
    color: #fff;
    position: relative;
    overflow: hidden
  }

  .credit-card .wifi-icon {
    font-size: 24px;
    margin-bottom: 32px
  }

  .credit-card .card-number {
    font-size: 22px;
    letter-spacing: 4px;
    margin-bottom: 32px
  }

  .credit-card .card-footer {
    display: flex;
    justify-content: space-between;
    align-items: flex-end
  }

  .credit-card .card-holder, .credit-card .card-expires {
    font-size: 12px;
    opacity: 0.8
  }

  .credit-card .card-holder strong, .credit-card .card-expires strong {
    font-size: 14px;
    opacity: 1
  }

  .mini-cards {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px
  }

  .mini-card {
    background: #fff;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    padding: 16px;
    text-align: center
  }

  .mini-card .icon {
    width: 48px;
    height: 48px;
    background: #185FA5;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 12px;
    color: #fff;
    font-size: 20px
  }

  .mini-card h6 {
    font-size: 14px;
    font-weight: 500;
    margin-bottom: 4px
  }

  .mini-card span {
    font-size: 12px;
    color: #666
  }

  .mini-card .amount {
    font-size: 18px;
    font-weight: 600;
    margin-top: 12px
  }

  .payment-method-item {
    display: flex;
    align-items: center;
    padding: 12px 16px;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    margin-bottom: 8px
  }

  .payment-method-item img {
    width: 40px;
    margin-right: 12px
  }

  .payment-method-item h6 {
    font-size: 13px;
    font-weight: 500
  }

  .payment-method-item .edit-icon {
    margin-left: auto;
    cursor: pointer;
    color: #666
  }

  .invoice-list {
    list-style: none
  }

  .invoice-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 0;
    border-bottom: 1px solid #e0e0e0
  }

  .invoice-item:last-child {
    border-bottom: none
  }

  .invoice-item h6 {
    font-size: 13px;
    font-weight: 500;
    margin-bottom: 2px
  }

  .invoice-item span {
    font-size: 11px;
    color: #666
  }

  .invoice-item .amount {
    font-size: 13px;
    font-weight: 500
  }

  .invoice-item .pdf-btn {
    margin-left: 12px;
    padding: 4px 8px;
    font-size: 11px;
    border: 1px solid #e0e0e0;
    border-radius: 4px;
    background: #fff;
    cursor: pointer
  }

  .billing-item {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    padding: 16px;
    background: #f9f9f9;
    border-radius: 8px;
    margin-bottom: 12px
  }

  .billing-item h6 {
    font-size: 14px;
    font-weight: 500;
    margin-bottom: 8px
  }

  .billing-item span {
    font-size: 12px;
    color: #666;
    display: block;
    margin-bottom: 4px
  }

  .billing-item span strong {
    color: #1a1a1a;
    margin-left: 4px
  }

  .billing-item .actions {
    display: flex;
    gap: 8px
  }

  .billing-item .actions a {
    padding: 4px 12px;
    font-size: 12px;
    border-radius: 4px;
    cursor: pointer;
    text-decoration: none
  }

  .billing-item .actions .delete {
    color: #dc2626
  }

  .billing-item .actions .edit {
    color: #1a1a1a
  }

  .transaction-section {
    margin-bottom: 16px
  }

  .transaction-section h6 {
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    color: #666;
    margin-bottom: 12px
  }

  .transaction-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 0;
    border-bottom: 1px solid #e0e0e0
  }

  .transaction-item:last-child {
    border-bottom: none
  }

  .transaction-item .icon {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 12px;
    font-size: 12px
  }

  .transaction-item .icon.down {
    background: #FEE2E2;
    color: #DC2626
  }

  .transaction-item .icon.up {
    background: #D1FAE5;
    color: #059669
  }

  .transaction-item .icon.pending {
    background: #F5F5F5;
    color: #666
  }

  .transaction-item h6 {
    font-size: 13px;
    font-weight: 500;
    margin-bottom: 2px
  }

  .transaction-item span {
    font-size: 11px;
    color: #666
  }

  .transaction-item .amount {
    font-size: 13px;
    font-weight: 600
  }

  .transaction-item .amount.negative {
    color: #DC2626
  }

  .transaction-item .amount.positive {
    color: #059669
  }

  .bottom-grid {
    display: grid;
    grid-template-columns: 7fr 5fr;
    gap: 24px
  }

  @media (max-width: 768px) {
    .bottom-grid {
      grid-template-columns: 1fr
    }
  }

  /* ── Additional Responsive ──────────────────────────────────── */
  @media (max-width: 480px) {
    .page {
      padding: 16px;
    }
  }

  @media (max-width: 768px) {
    .card-header {
      flex-direction: column;
      align-items: stretch;
      gap: 16px;
      padding: 16px 18px;
    }
  }

  @media (max-width: 480px) {
    .btn {
      width: 100%;
      justify-content: center;
    }
  }

  @media (max-width: 640px) {
    .credit-card {
      padding: 20px;
    }
  }

  @media (max-width: 640px) {
    .credit-card .wifi-icon {
      font-size: 20px;
      margin-bottom: 24px;
    }
  }

  @media (max-width: 640px) {
    .credit-card .card-number {
      font-size: 18px;
      letter-spacing: 2px;
      margin-bottom: 24px;
    }
  }

  @media (max-width: 640px) {
    .credit-card .card-holder, .credit-card .card-expires {
      font-size: 11px;
    }
  }

  @media (max-width: 640px) {
    .credit-card .card-holder strong, .credit-card .card-expires strong {
      font-size: 12px;
    }
  }

  @media (max-width: 640px) {
    .mini-cards {
      grid-template-columns: 1fr;
      gap: 12px;
    }
  }

  @media (max-width: 640px) {
    .mini-card {
      padding: 14px;
    }
  }

  @media (max-width: 640px) {
    .mini-card .icon {
      width: 40px;
      height: 40px;
      font-size: 18px;
    }
  }

  @media (max-width: 640px) {
    .mini-card h6 {
      font-size: 13px;
    }
  }

  @media (max-width: 640px) {
    .mini-card span {
      font-size: 11px;
    }
  }

  @media (max-width: 640px) {
    .mini-card .amount {
      font-size: 16px;
    }
  }

  @media (max-width: 640px) {
    .payment-method-item {
      padding: 10px 12px;
    }
  }

  @media (max-width: 640px) {
    .payment-method-item img {
      width: 32px;
      margin-right: 10px;
    }
  }

  @media (max-width: 640px) {
    .payment-method-item h6 {
      font-size: 12px;
    }
  }

  @media (max-width: 640px) {
    .invoice-item {
      flex-direction: column;
      align-items: flex-start;
      gap: 8px;
    }
  }

  @media (max-width: 640px) {
    .invoice-item h6 {
      font-size: 12px;
    }
  }

  @media (max-width: 640px) {
    .invoice-item span {
      font-size: 10px;
    }
  }

  @media (max-width: 640px) {
    .invoice-item .amount {
      font-size: 12px;
    }
  }

  @media (max-width: 640px) {
    .invoice-item .pdf-btn {
      margin-left: 0;
      margin-top: 4px;
    }
  }

  @media (max-width: 640px) {
    .billing-item {
      flex-direction: column;
      gap: 12px;
      padding: 14px;
    }
  }

  @media (max-width: 640px) {
    .billing-item h6 {
      font-size: 13px;
    }
  }

  @media (max-width: 640px) {
    .billing-item span {
      font-size: 11px;
    }
  }

  @media (max-width: 640px) {
    .billing-item .actions {
      width: 100%;
      justify-content: flex-end;
    }
  }

  @media (max-width: 640px) {
    .transaction-item {
      padding: 8px 0;
    }
  }

  @media (max-width: 640px) {
    .transaction-item .icon {
      width: 24px;
      height: 24px;
      font-size: 10px;
    }
  }

  @media (max-width: 640px) {
    .transaction-item h6 {
      font-size: 12px;
    }
  }

  @media (max-width: 640px) {
    .transaction-item span {
      font-size: 10px;
    }
  }

  @media (max-width: 640px) {
    .transaction-item .amount {
      font-size: 12px;
    }
  }

  @media (max-width: 768px) {
    .card-body {
      padding: 18px;
    }
  }

  @media (max-width: 480px) {
    .card-body {
      padding: 16px;
    }
  }

  /* Invoice status badges */
  .invoice-status-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    padding: 0.25rem 0.75rem;
    border-radius: 50px;
    font-size: 0.7rem;
    font-weight: 500;
    letter-spacing: 0.04em;
    white-space: nowrap;
  }
</style>

<div class="page">
  <div class="dashboard-grid">
    <div>
      <div class="card" style="margin-bottom: 24px">
        <div class="card-body">
          <div class="mini-cards">
            <div class="credit-card">
              <i class="fas fa-wifi wifi-icon"></i>
              <div class="card-number">4562  1122  4594  7852</div>
              <div class="card-footer">
                <div>
                  <div class="card-holder">Card Holder</div>
                  <strong>Jack Peterson</strong>
                </div>
                <div>
                  <div class="card-expires">Expires</div>
                  <strong>11/22</strong>
                </div>
              </div>
            </div>
            <div class="mini-cards" id="revenueCards">
              <div class="mini-card">
                <div class="icon"><i class="fas fa-chart-line"></i></div>
                <h6>Total Revenue</h6>
                <span>All Time</span>
                <div class="amount">₱0.00</div>
              </div>
              <div class="mini-card">
                <div class="icon"><i class="fas fa-clock"></i></div>
                <h6>Pending</h6>
                <span>Awaiting Confirmation</span>
                <div class="amount">₱0.00</div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="card">
        <div class="card-header">
          <div class="card-title">Payment Method</div>
          <button class="btn btn-primary" onclick="addPaymentMethod()"><i class="fas fa-plus mr-1"></i> Add New Card</button>
        </div>
        <div class="card-body" id="paymentMethodsContainer">
          <div class="payment-method-item">
            <h6>Loading...</h6>
          </div>
        </div>
      </div>
    </div>

    <div class="card">
      <div class="card-header">
        <div class="card-title">Invoices</div>
        <button class="btn btn-outline" onclick="viewAllInvoices()">View All</button>
      </div>
      <div class="card-body">
        <ul class="invoice-list" id="invoiceList">
          <li class="invoice-item">
            <div>
              <h6>Loading...</h6>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </div>

  <div class="bottom-grid">
    <div class="card">
      <div class="card-header">
        <div class="card-title">Billing Information</div>
      </div>
      <div class="card-body" id="billingInfo">
        <div class="billing-item">
          <div>
            <h6>Loading...</h6>
          </div>
        </div>
      </div>
    </div>

    <div class="card">
      <div class="card-header">
        <div class="card-title">Your Transactions</div>
        <div style="font-size: 12px; color: #666">
          <i class="far fa-calendar-alt mr-1"></i> 23 - 30 March 2020
        </div>
      </div>
      <div class="card-body" id="transactionsContainer">
        <div class="transaction-section">
          <h6>Recent</h6>
          <div class="transaction-item">
            <div style="display: flex; align-items: center">
              <div class="icon pending"><i class="fas fa-spinner"></i></div>
              <div>
                <h6>Loading...</h6>
              </div>
            </div>
          </div>
        </div>
        <div class="transaction-section">
          <h6>Earlier</h6>
        </div>
      </div>
    </div>
  </div>
</div>
