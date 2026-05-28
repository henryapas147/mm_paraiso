<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Lora:ital,wght@0,600;1,500&display=swap" rel="stylesheet">

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
            <div class="mini-cards">
              <div class="mini-card">
                <div class="icon"><i class="fas fa-landmark"></i></div>
                <h6>Salary</h6>
                <span>Belong Interactive</span>
                <div class="amount">+$2000</div>
              </div>
              <div class="mini-card">
                <div class="icon"><i class="fab fa-paypal"></i></div>
                <h6>Paypal</h6>
                <span>Freelance Payment</span>
                <div class="amount">$455.00</div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="card">
        <div class="card-header">
          <div class="card-title">Payment Method</div>
          <button class="btn btn-primary"><i class="fas fa-plus mr-1"></i> Add New Card</button>
        </div>
        <div class="card-body">
          <div class="payment-method-item">
            <img src="../assets/img/logos/mastercard.png" alt="Mastercard" />
            <h6>****  ****  ****  7852</h6>
            <i class="fas fa-pencil-alt edit-icon"></i>
          </div>
          <div class="payment-method-item">
            <img src="../assets/img/logos/visa.png" alt="Visa" />
            <h6>****  ****  ****  5248</h6>
            <i class="fas fa-pencil-alt edit-icon"></i>
          </div>
        </div>
      </div>
    </div>

    <div class="card">
      <div class="card-header">
        <div class="card-title">Invoices</div>
        <button class="btn btn-outline">View All</button>
      </div>
      <div class="card-body">
        <ul class="invoice-list">
          <li class="invoice-item">
            <div>
              <h6>March, 01, 2020</h6>
              <span>#MS-415646</span>
            </div>
            <div>
              <span class="amount">$180</span>
              <button class="pdf-btn"><i class="fas fa-file-pdf"></i> PDF</button>
            </div>
          </li>
          <li class="invoice-item">
            <div>
              <h6>February, 10, 2021</h6>
              <span>#RV-126749</span>
            </div>
            <div>
              <span class="amount">$250</span>
              <button class="pdf-btn"><i class="fas fa-file-pdf"></i> PDF</button>
            </div>
          </li>
          <li class="invoice-item">
            <div>
              <h6>April, 05, 2020</h6>
              <span>#FB-212562</span>
            </div>
            <div>
              <span class="amount">$560</span>
              <button class="pdf-btn"><i class="fas fa-file-pdf"></i> PDF</button>
            </div>
          </li>
          <li class="invoice-item">
            <div>
              <h6>June, 25, 2019</h6>
              <span>#QW-103578</span>
            </div>
            <div>
              <span class="amount">$120</span>
              <button class="pdf-btn"><i class="fas fa-file-pdf"></i> PDF</button>
            </div>
          </li>
          <li class="invoice-item">
            <div>
              <h6>March, 01, 2019</h6>
              <span>#AR-803481</span>
            </div>
            <div>
              <span class="amount">$300</span>
              <button class="pdf-btn"><i class="fas fa-file-pdf"></i> PDF</button>
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
      <div class="card-body">
        <div class="billing-item">
          <div>
            <h6>Oliver Liam</h6>
            <span>Company Name: <strong>Viking Burrito</strong></span>
            <span>Email Address: <strong>oliver@burrito.com</strong></span>
            <span>VAT Number: <strong>FRB1235476</strong></span>
          </div>
          <div class="actions">
            <a class="delete"><i class="far fa-trash-alt"></i> Delete</a>
            <a class="edit"><i class="fas fa-pencil-alt"></i> Edit</a>
          </div>
        </div>
        <div class="billing-item">
          <div>
            <h6>Lucas Harper</h6>
            <span>Company Name: <strong>Stone Tech Zone</strong></span>
            <span>Email Address: <strong>lucas@stone-tech.com</strong></span>
            <span>VAT Number: <strong>FRB1235476</strong></span>
          </div>
          <div class="actions">
            <a class="delete"><i class="far fa-trash-alt"></i> Delete</a>
            <a class="edit"><i class="fas fa-pencil-alt"></i> Edit</a>
          </div>
        </div>
        <div class="billing-item">
          <div>
            <h6>Ethan James</h6>
            <span>Company Name: <strong>Fiber Notion</strong></span>
            <span>Email Address: <strong>ethan@fiber.com</strong></span>
            <span>VAT Number: <strong>FRB1235476</strong></span>
          </div>
          <div class="actions">
            <a class="delete"><i class="far fa-trash-alt"></i> Delete</a>
            <a class="edit"><i class="fas fa-pencil-alt"></i> Edit</a>
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
      <div class="card-body">
        <div class="transaction-section">
          <h6>Newest</h6>
          <div class="transaction-item">
            <div style="display: flex; align-items: center">
              <div class="icon down"><i class="fas fa-arrow-down"></i></div>
              <div>
                <h6>Netflix</h6>
                <span>27 March 2020, at 12:30 PM</span>
              </div>
            </div>
            <span class="amount negative">- $2,500</span>
          </div>
          <div class="transaction-item">
            <div style="display: flex; align-items: center">
              <div class="icon up"><i class="fas fa-arrow-up"></i></div>
              <div>
                <h6>Apple</h6>
                <span>27 March 2020, at 04:30 AM</span>
              </div>
            </div>
            <span class="amount positive">+ $2,000</span>
          </div>
        </div>
        <div class="transaction-section">
          <h6>Yesterday</h6>
          <div class="transaction-item">
            <div style="display: flex; align-items: center">
              <div class="icon up"><i class="fas fa-arrow-up"></i></div>
              <div>
                <h6>Stripe</h6>
                <span>26 March 2020, at 13:45 PM</span>
              </div>
            </div>
            <span class="amount positive">+ $750</span>
          </div>
          <div class="transaction-item">
            <div style="display: flex; align-items: center">
              <div class="icon up"><i class="fas fa-arrow-up"></i></div>
              <div>
                <h6>HubSpot</h6>
                <span>26 March 2020, at 12:30 PM</span>
              </div>
            </div>
            <span class="amount positive">+ $1,000</span>
          </div>
          <div class="transaction-item">
            <div style="display: flex; align-items: center">
              <div class="icon up"><i class="fas fa-arrow-up"></i></div>
              <div>
                <h6>Creative Tim</h6>
                <span>26 March 2020, at 08:30 AM</span>
              </div>
            </div>
            <span class="amount positive">+ $2,500</span>
          </div>
          <div class="transaction-item">
            <div style="display: flex; align-items: center">
              <div class="icon pending"><i class="fas fa-exclamation"></i></div>
              <div>
                <h6>Webflow</h6>
                <span>26 March 2020, at 05:00 AM</span>
              </div>
            </div>
            <span style="font-size: 13px; font-weight: 500; color: #666">Pending</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
