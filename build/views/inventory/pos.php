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

  /* ═══════════════════════════════════════════════════
     LAYOUT — mobile-first base (single column stack)
     Breakpoints:  base < 768  |  tablet ≥ 768  |  desktop ≥ 1201
     ═══════════════════════════════════════════════════ */
  .pos-container {
    display: flex;
    flex-direction: column;
    gap: 12px;
    padding: 12px;
    min-height: calc(100vh - 60px);
    max-width: 100%;
    margin: 0;
    font-family: var(--pos-font);
  }

  /* Desktop/tablet-only chrome is hidden by default (revealed on mobile) */
  .pos-cart-fab,
  .pos-mobile-summary,
  .pos-cart-overlay {
    display: none;
  }

  /* Touch ergonomics for every interactive element */
  .pos-product-card,
  .pos-cat-btn,
  .pos-qty-btn,
  .pos-payment-btn,
  .pos-checkout-btn,
  .pos-cart-fab,
  .pos-mobile-summary-btn {
    -webkit-tap-highlight-color: transparent;
    user-select: none;
    -webkit-user-select: none;
    touch-action: manipulation;
  }

  body { overflow-x: hidden; }

  main {
    width: 100% !important;
    max-width: 100% !important;
    margin-left: 0 !important;
  }

  /* ═══ DESKTOP ≥ 1201px — two-column grid, wide cart ═══ */
  @media (min-width: 1201px) {
    .pos-container {
      display: grid;
      grid-template-columns: 1fr 400px;
      gap: 16px;
      padding: 16px;
      min-height: calc(100vh - 80px);
    }
  }

  /* Tablet grid (768–1200) is defined in the detailed tablet block below */

  /* ── Mobile Responsive (< 768px) ──────────────────── */
  @media (max-width: 767px) {
    .pos-container {
      padding: 8px;
      padding-bottom: 72px; /* clearance for fixed bottom summary bar */
      gap: 10px;
      min-height: calc(100vh - 60px);
    }

    .pos-header {
      padding: 12px 16px;
      gap: 10px;
    }

    .pos-title {
      font-size: 16px;
    }

    .pos-back-btn {
      width: 32px;
      height: 32px;
    }

    .pos-search-wrap {
      min-width: auto;
      flex: 1;
    }

    .pos-search {
      padding: 10px 12px 10px 38px;
      font-size: 13px;
    }

    .pos-category-filter {
      padding: 10px 12px;
      gap: 6px;
      overflow-x: auto;
    }

    .pos-cat-btn {
      padding: 6px 12px;
      font-size: 12px;
    }

    .pos-products-grid {
      padding: 12px;
      gap: 10px;
      grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
      max-height: none;
    }

    .pos-product-card {
      padding: 10px;
    }

    .pos-product-img {
      width: 60px;
      height: 60px;
      font-size: 24px;
    }

    .pos-product-name {
      font-size: 12px;
    }

    .pos-product-price {
      font-size: 14px;
    }

    /* Cart panel becomes a bottom sheet */
    .pos-cart-panel {
      position: fixed;
      bottom: 0;
      left: 0;
      right: 0;
      top: auto;
      height: 70vh;
      border-radius: 16px 16px 0 0;
      z-index: 8000;
      transform: translateY(100%);
      transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      box-shadow: 0 -8px 32px rgba(0, 0, 0, 0.15);
      display: flex;
      flex-direction: column;
    }

    .pos-cart-panel.open {
      transform: translateY(0);
    }

    .pos-cart-header {
      padding: 14px 16px;
      flex-shrink: 0;
    }

    .pos-cart-title {
      font-size: 15px;
    }

    .pos-cart-items {
      flex: 1;
      overflow-y: auto;
      max-height: none;
      padding: 10px 12px;
    }

    .pos-cart-item {
      padding: 10px;
      gap: 10px;
      grid-template-columns: 40px 1fr auto auto;
    }

    .pos-cart-img {
      width: 40px;
      height: 40px;
      font-size: 16px;
    }

    .pos-cart-img img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      border-radius: 4px;
    }

    .pos-cart-name {
      font-size: 13px;
    }

    .pos-cart-price {
      font-size: 12px;
    }

    .pos-qty-btn {
      width: 26px;
      height: 26px;
    }

    .pos-cart-summary {
      padding: 12px 16px;
      flex-shrink: 0;
    }

    .pos-payment-section {
      padding: 10px 16px 16px;
      flex-shrink: 0;
    }

    .pos-payment-btn {
      padding: 10px 8px;
      font-size: 12px;
      min-width: auto;
      flex: 1;
    }

    .pos-checkout-btn {
      padding: 12px;
      font-size: 14px;
    }

    /* Cart overlay */
    .pos-cart-overlay {
      display: none;
      position: fixed;
      inset: 0;
      background: rgba(0, 0, 0, 0.4);
      z-index: 7999;
      backdrop-filter: blur(2px);
    }

    .pos-cart-overlay.open {
      display: block;
    }

    /* Floating cart button */
    .pos-cart-fab {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 6px;
      position: fixed;
      bottom: 16px;
      right: 16px;
      z-index: 7000;
      background: var(--pos-blue);
      color: #fff;
      border: none;
      border-radius: 50px;
      padding: 12px 18px;
      font-size: 14px;
      font-weight: 600;
      font-family: var(--pos-font);
      box-shadow: 0 4px 16px rgba(26, 107, 191, 0.4);
      cursor: pointer;
      transition: transform 0.2s, box-shadow 0.2s;
      -webkit-tap-highlight-color: transparent;
    }

    .pos-cart-fab:active {
      transform: scale(0.95);
    }

    .pos-cart-fab-badge {
      background: var(--pos-red);
      color: #fff;
      font-size: 11px;
      font-weight: 700;
      min-width: 20px;
      height: 20px;
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 0 5px;
      font-family: var(--pos-mono);
    }

    /* Mobile bottom summary bar */
    .pos-mobile-summary {
      display: flex;
      align-items: center;
      justify-content: space-between;
      position: fixed;
      bottom: 0;
      left: 0;
      right: 0;
      z-index: 6000;
      background: #fff;
      padding: 10px 16px;
      border-top: 1px solid #E2E8F0;
      box-shadow: 0 -4px 16px rgba(0, 0, 0, 0.08);
    }

    .pos-mobile-summary-total {
      font-size: 18px;
      font-weight: 700;
      color: var(--pos-dark);
      font-family: var(--pos-mono);
    }

    .pos-mobile-summary-total small {
      font-size: 11px;
      font-weight: 500;
      color: var(--pos-gray);
      display: block;
      font-family: var(--pos-font);
    }

    .pos-mobile-summary-btn {
      background: var(--pos-blue);
      color: #fff;
      border: none;
      border-radius: 10px;
      padding: 10px 20px;
      font-size: 14px;
      font-weight: 600;
      font-family: var(--pos-font);
      cursor: pointer;
      display: flex;
      align-items: center;
      gap: 6px;
      box-shadow: 0 2px 8px rgba(26, 107, 191, 0.3);
      -webkit-tap-highlight-color: transparent;
    }

    .pos-mobile-summary-btn:disabled {
      background: #CBD5E1;
      box-shadow: none;
      cursor: not-allowed;
    }

    /* Modal sizing for mobile */
    .pos-modal {
      max-width: 100%;
      margin: 10px;
      border-radius: 14px;
    }

    .pos-payment-amount {
      font-size: 28px;
    }
  }

  /* ── Tablet (768px - 1200px) ─────────────────────── */
  @media (min-width: 768px) and (max-width: 1200px) {
    /* Main layout: tighter gap, wider cart panel for better balance */
    .pos-container {
      display: grid;
      grid-template-columns: 1fr 310px;
      gap: 10px;
      padding: 10px;
      min-height: calc(100vh - 70px);
    }

    /* Product panel fills height */
    .pos-products-panel {
      display: flex;
      flex-direction: column;
      overflow-y: hidden;
      height: calc(100vh - 90px);
    }

    /* Header: tighter, search always visible */
    .pos-header {
      padding: 12px 16px;
      gap: 10px;
      flex-wrap: nowrap;
    }

    .pos-title {
      font-size: 16px;
      white-space: nowrap;
    }

    .pos-back-btn {
      width: 34px;
      height: 34px;
      flex-shrink: 0;
    }

    .pos-search-wrap {
      min-width: 0;
      flex: 1;
    }

    .pos-search {
      padding: 9px 12px 9px 38px;
      font-size: 13px;
    }

    /* Category filter: compact pills */
    .pos-category-filter {
      padding: 8px 14px;
      gap: 6px;
      overflow-x: auto;
    }

    .pos-cat-btn {
      padding: 6px 13px;
      font-size: 12px;
    }

    /* Product grid: 3 columns on tablet for better card proportions */
    .pos-products-grid {
      grid-template-columns: repeat(3, 1fr);
      gap: 8px;
      padding: 10px 12px;
      flex: 1;
      overflow-y: auto;
      max-height: none;
    }

    .pos-product-card {
      padding: 8px 6px;
    }

    .pos-product-img {
      width: 40px;
      height: 40px;
      font-size: 18px;
      margin-bottom: 4px;
    }

    .pos-product-stock {
      font-size: 8px;
      padding: 2px 4px;
    }

    .pos-product-name {
      font-size: 11px;
      margin-bottom: 2px;
      -webkit-line-clamp: 1;
      line-clamp: 1;
    }

    .pos-product-code {
      font-size: 9px;
      margin-bottom: 3px;
    }

    .pos-product-price {
      font-size: 13px;
    }

    /* Cart panel: sticky full height sidebar */
    .pos-cart-panel {
      position: sticky;
      top: 10px;
      height: calc(100vh - 90px);
      max-height: none;
      overflow: hidden;
      display: flex;
      flex-direction: column;
      border-radius: var(--pos-radius);
    }

    /* Cart header: compact */
    .pos-cart-header {
      padding: 12px 14px;
      flex-shrink: 0;
    }

    .pos-cart-title {
      font-size: 14px;
      gap: 8px;
    }

    .pos-cart-count {
      font-size: 11px;
      padding: 2px 8px;
    }

    .pos-cart-clear {
      font-size: 11px;
      padding: 3px 7px;
    }

    /* Cart items: compact rows with 3-column layout (icon | info | qty+total) */
    .pos-cart-items {
      flex: 1;
      overflow-y: auto;
      padding: 8px 10px;
      max-height: none;
    }

    .pos-cart-item {
      grid-template-columns: 34px 1fr auto;
      gap: 8px;
      padding: 8px 10px;
      margin-bottom: 7px;
      border-radius: 9px;
    }

    /* Stack qty controls + total in the 3rd column */
    .pos-cart-item .pos-cart-qty {
      flex-direction: column;
      align-items: flex-end;
      gap: 3px;
    }

    .pos-cart-item .pos-cart-total {
      font-size: 12px;
      font-weight: 700;
    }

    .pos-cart-img {
      width: 34px;
      height: 34px;
      font-size: 14px;
      border-radius: 7px;
    }

    .pos-cart-img img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      border-radius: 7px;
    }

    .pos-cart-name {
      font-size: 12px;
    }

    .pos-cart-price {
      font-size: 11px;
    }

    .pos-qty-btn {
      width: 22px;
      height: 22px;
      font-size: 13px;
    }

    .pos-qty-value {
      font-size: 12px;
      min-width: 18px;
    }

    .pos-cart-item-remove {
      display: none;
    }

    /* Summary: tighter rows */
    .pos-cart-summary {
      padding: 10px 14px;
      flex-shrink: 0;
      background: #F8FAFC;
    }

    .pos-summary-row {
      font-size: 12px;
      margin-bottom: 7px;
    }

    .pos-summary-row.total {
      font-size: 15px;
      margin-top: 10px;
      padding-top: 10px;
    }

    .pos-summary-row.total .pos-summary-value {
      font-size: 19px;
    }

    /* Payment section: 3-column payment buttons */
    .pos-payment-section {
      padding: 8px 12px 12px;
      flex-shrink: 0;
    }

    .pos-payment-methods {
      display: grid;
      grid-template-columns: 1fr 1fr 1fr;
      gap: 6px;
      margin-bottom: 10px;
    }

    .pos-payment-btn {
      padding: 8px 4px;
      font-size: 11px;
      gap: 4px;
      border-radius: 9px;
      min-width: 0;
      flex: none;
    }

    .pos-payment-btn i {
      font-size: 16px;
    }

    .pos-customer-input {
      padding: 9px 12px;
      font-size: 13px;
      margin-bottom: 10px;
      border-radius: 9px;
    }

    .pos-checkout-btn {
      padding: 12px;
      font-size: 14px;
      border-radius: 10px;
      gap: 7px;
    }

    /* Hide mobile-only elements */
    .pos-cart-fab,
    .pos-mobile-summary,
    .pos-cart-overlay {
      display: none !important;
    }
  }

  /* ═══════════════════════════════════════════════════
     COMPONENTS — shared baseline (desktop sizing)
     Mobile/tablet overrides live in the media blocks above
     ═══════════════════════════════════════════════════ */

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

  .pos-cart-img img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 8px;
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

  /* UOM Selector Modal Styles */
  .pos-uom-options {
    padding: 16px;
    max-height: 400px;
    overflow-y: auto;
  }

  .pos-uom-option {
    padding: 16px;
    border: 1px solid #E2E8F0;
    border-radius: 8px;
    margin-bottom: 12px;
    cursor: pointer;
    transition: all 0.2s;
    background: #fff;
  }

  .pos-uom-option:hover {
    border-color: var(--pos-blue);
    background: var(--pos-blue-pale);
  }

  .pos-uom-name {
    font-weight: 600;
    font-size: 14px;
    color: var(--pos-dark);
    margin-bottom: 4px;
  }

  .pos-uom-code {
    font-size: 11px;
    color: var(--pos-gray);
    margin-bottom: 4px;
    font-family: var(--pos-mono);
  }

  .pos-uom-stock {
    font-size: 12px;
    color: var(--pos-gray);
    margin-bottom: 4px;
  }

  .pos-uom-conversion {
    font-size: 11px;
    color: var(--pos-blue);
    font-style: italic;
    margin-bottom: 4px;
  }

  .pos-uom-price {
    font-size: 14px;
    font-weight: 700;
    color: var(--pos-green);
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
        <input type="text" class="pos-search" id="posSearch" placeholder="Search by name, code, or scan barcode..." autofocus>
      </div>
    </div>

    <div class="pos-category-filter" id="categoryFilter">
      <button class="pos-cat-btn active" data-category="">All Products</button>
    </div>

    <div class="pos-products-grid" id="productsGrid">
      <div class="pos-loading">Loading products...</div>
    </div>
  </div>

  <!-- Mobile Cart Overlay -->
  <div class="pos-cart-overlay" id="cartOverlay"></div>

  <!-- Mobile Floating Cart Button -->
  <button class="pos-cart-fab" id="cartFab">
    <i class="fas fa-shopping-cart"></i>
    <span>Cart</span>
    <span class="pos-cart-fab-badge" id="cartFabBadge">0</span>
  </button>

  <!-- Mobile Bottom Summary Bar -->
  <div class="pos-mobile-summary" id="mobileSummary">
    <div class="pos-mobile-summary-total">
      <small>Total</small>
      <span id="mobileTotal">₱0.00</span>
    </div>
    <button class="pos-mobile-summary-btn" id="mobileCheckoutBtn" disabled>
      <i class="fas fa-check-circle"></i> Pay
    </button>
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
      <div class="pos-summary-row" id="taxRow" style="display:none;">
        <span class="pos-summary-label">Tax (<span id="taxRate">12</span>%)</span>
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
    const csrfToken = '<?php echo htmlspecialchars(CSRF_TOKEN, ENT_QUOTES, 'UTF-8'); ?>';
    let products = [];
    let categories = [];
    let cart = [];
    let paymentMethod = 'cash';
    let propertyId = '<?php echo $_SESSION['selected_property_id'] ?? ''; ?>';
    const CART_STORAGE_KEY = 'pos_cart_' + (propertyId || 'default');
    let posSettings = { vat_enabled: 0, vat_rate: 12, tax_inclusive: 0 };
    // Track current search/category to re-render products when cart changes
    let currentFilterText = '';
    let currentCategoryId = '';

    // Save cart to localStorage
    function saveCart() {
      try {
        localStorage.setItem(CART_STORAGE_KEY, JSON.stringify(cart));
      } catch (e) {
        console.error('Failed to save cart:', e);
      }
    }

    // Refocus search input after any cart operation
    function focusSearch() {
      const searchInput = document.getElementById('posSearch');
      if (searchInput) {
        setTimeout(() => searchInput.focus(), 50);
      }
    }

    // Load cart from localStorage
    function loadCart() {
      try {
        const saved = localStorage.getItem(CART_STORAGE_KEY);
        if (saved) {
          cart = JSON.parse(saved);
          // Validate stock after loading, but products might not be loaded yet
          // so we'll validate in renderCart when products are available
          renderCart();
        }
      } catch (e) {
        console.error('Failed to load cart:', e);
        cart = [];
      }
    }

    // Real-time stock validation - reconcile cart with current inventory
    function validateCartStock() {
      if (!products || products.length === 0 || cart.length === 0) return;

      let adjustments = [];
      let removedItems = [];

      cart = cart.filter(item => {
        const product = products.find(p => p.product_id == item.product_id);

        // Product not found in the POS product list (already filtered for active + for_sale by API)
        if (!product) {
          removedItems.push(item.product_name || 'Unknown Product');
          return false;
        }

        const currentStock = parseFloat(product.current_stock) || 0;
        const maxAllowed = Math.floor(currentStock / item.conversion_factor);

        // Check if quantity exceeds available stock
        if (item.quantity > maxAllowed) {
          const oldQty = item.quantity;

          if (maxAllowed <= 0) {
            // No stock available, remove item
            removedItems.push(`${product.product_name} (${item.uom_code})`);
            return false;
          } else {
            // Reduce quantity to available stock
            item.quantity = maxAllowed;
            adjustments.push({
              name: product.product_name,
              uom: item.uom_code,
              oldQty: oldQty,
              newQty: maxAllowed
            });
          }
        }

        // Update stored base stock to current value for future checks
        item.base_stock = currentStock;

        return true;
      });

      // Show notifications for adjustments
      if (removedItems.length > 0) {
        showToast(`Removed from cart (no stock): ${removedItems.join(', ')}`, 'warning', 4000);
      }

      if (adjustments.length > 0) {
        const adjText = adjustments.map(a => `${a.name}: ${a.oldQty}→${a.newQty} ${a.uom}`).join(', ');
        showToast(`Stock adjusted: ${adjText}`, 'warning', 4000);
      }

      if (removedItems.length > 0 || adjustments.length > 0) {
        saveCart();
      }
    }

    // Clear saved cart
    function clearSavedCart() {
      try {
        localStorage.removeItem(CART_STORAGE_KEY);
      } catch (e) {
        console.error('Failed to clear cart:', e);
      }
    }

    // Normalize product image URL for display (same as product.php)
    function normalizeProductImageUrl(url) {
      if (!url) return null;
      // If it's already an absolute URL (http/https), use as-is
      if (url.startsWith('http')) return url;
      // Extract filename from various path formats
      const filename = url.split('/').pop();
      // Return relative path from build/views/inventory/ to uploads/products/
      return '../../uploads/products/' + filename;
    }

    // Load POS settings (VAT/tax configuration)
    async function loadPosSettings() {
      try {
        const response = await fetch(`${API_BASE}/system_settings.php?action=pos_settings`);
        const result = await response.json();
        if (result.success) {
          posSettings = result.data;
          updateTaxDisplay();
          updateSummary(); // Recalculate with VAT settings
        }
      } catch (error) {
        console.error('Failed to load POS settings:', error);
      }
    }

    // Update tax row visibility based on settings
    function updateTaxDisplay() {
      const taxRow = document.getElementById('taxRow');
      const taxRate = document.getElementById('taxRate');
      if (taxRow) {
        taxRow.style.display = posSettings.vat_enabled ? 'flex' : 'none';
      }
      if (taxRate) {
        taxRate.textContent = posSettings.vat_rate;
      }
    }

    // Initialize
    document.addEventListener('DOMContentLoaded', () => {
      loadPosSettings();
      loadProducts(); // loadCart() will be called inside after products load
      loadCategories();
      setupEventListeners();
    });

    // Load products
    async function loadProducts() {
      try {
        const response = await fetch(`${API_BASE}/pos_products.php?action=list`);
        const result = await response.json();
        if (result.success) {
          products = result.data;
          renderProducts();
          // Now load cart AFTER products are available
          // This ensures validateCartStock() can find products
          loadCart();
        }
      } catch (error) {
        showToast('Failed to load products', 'error');
      }
    }

    // Load categories (only those with products for sale)
    async function loadCategories() {
      try {
        const response = await fetch(`${API_BASE}/pos_products.php?action=categories`);
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

    // Helper: get total base units reserved in cart for a product
    function getCartReservedBaseStock(productId) {
      return cart
        .filter(item => item.product_id == productId)
        .reduce((sum, item) => sum + (item.quantity * item.conversion_factor), 0);
    }

    // Render products
    function renderProducts(filterText = '', categoryId = '') {
      currentFilterText = filterText;
      currentCategoryId = categoryId;
      const container = document.getElementById('productsGrid');

      let filtered = products;

      if (filterText) {
        const text = filterText.toLowerCase().trim();
        filtered = filtered.filter(p => {
          // Clear any previous matched UOM ID to prevent stale data
          delete p._matchedUomId;
          
          // Check base product fields
          const matchesBase = p.product_name.toLowerCase().includes(text) ||
            p.product_code.toLowerCase().includes(text) ||
            (p.barcode && p.barcode.toLowerCase() === text);
          
          // Check UOM conversion fields (alt_product_code and barcode)
          let matchesConversion = false;
          let matchedUomId = null;
          if (p.uom_conversions && p.uom_conversions.length) {
            const match = p.uom_conversions.find(c => 
              (c.alt_product_code && c.alt_product_code.toLowerCase() === text) ||
              (c.conversion_barcode && c.conversion_barcode.toLowerCase() === text)
            );
            if (match) {
              matchesConversion = true;
              matchedUomId = match.to_uom_id;
            }
          }
          
          // Store matched UOM ID on product for later use
          if (matchedUomId) {
            p._matchedUomId = matchedUomId;
          }
          
          return matchesBase || matchesConversion;
        });
        
        // Auto-add to cart if exact barcode match found (base or conversion)
        if (filtered.length === 1) {
          const p = filtered[0];
          const isBaseBarcode = p.barcode && p.barcode.toLowerCase() === text;
          const isConversionMatch = p._matchedUomId !== undefined;
          
          if (isBaseBarcode || isConversionMatch) {
            addToCart(p.product_id, p._matchedUomId || null);
            document.getElementById('posSearch').value = '';
            renderProducts('', categoryId);
            return;
          }
        }
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
        const rawStock = parseFloat(p.current_stock) || 0;
        // Subtract base units currently reserved in cart for this product
        const reserved = cart
          .filter(item => item.product_id == p.product_id)
          .reduce((sum, item) => sum + (item.quantity * item.conversion_factor), 0);
        const stock = Math.max(0, rawStock - reserved);
        const reorder = parseFloat(p.reorder_point) || 0;
        let stockClass = '';
        let stockLabel = '';

        if (stock <= 0) {
          stockClass = 'out-of-stock';
          stockLabel = rawStock > 0 ? 'In Cart' : 'Out of Stock';
        } else if (stock <= reorder) {
          stockClass = 'low-stock';
          stockLabel = Math.floor(stock) + ' left';
        } else {
          stockLabel = Math.floor(stock) + ' in stock';
        }

        const uomLabel = p.base_uom_code ? `/${p.base_uom_code}` : '';
        return `
        <div class="pos-product-card ${stockClass}" data-id="${p.product_id}" ${stock <= 0 && rawStock <= 0 ? 'disabled' : ''}>
          <div class="pos-product-stock ${stock <= reorder ? 'low' : ''}">${stockLabel}</div>
          <div class="pos-product-img">
            ${p.product_image_url ? `<img src="${normalizeProductImageUrl(p.product_image_url)}" alt="${p.product_name}" onerror="this.parentElement.innerHTML='<i class=\\'fas fa-box\\'></i>'">` : '<i class="fas fa-box"></i>'}
          </div>
          <div class="pos-product-name">${p.product_name}</div>
          <div class="pos-product-code">${p.product_code}${p.barcode ? ` | ${p.barcode}` : ''}</div>
          <div class="pos-product-price">₱${formatMoney(p.selling_price)}${uomLabel}</div>
        </div>
      `;
      }).join('');

      // Add click handlers
      container.querySelectorAll('.pos-product-card:not([disabled])').forEach(card => {
        card.addEventListener('click', () => {
          addToCart(card.dataset.id);
          focusSearch();
        });
      });
    }

    // Add to cart with UOM selection
    function addToCart(productId, selectedUomId = null) {
      const product = products.find(p => p.product_id == productId);
      if (!product) return;

      const baseStock = parseFloat(product.current_stock) || 0;
      const baseUomId = parseInt(product.base_uom_id) || product.base_uom_id;

      // Determine which UOM to use
      let uomId = selectedUomId || baseUomId;
      let uomCode = product.base_uom_code;
      let uomName = product.base_uom_name;
      let conversionFactor = 1;

      // Check for UOM conversion
      let conversionSellingPrice = null;
      let altProductCode = null;
      // Convert uomId to number for consistent comparison (DB returns integers)
      const uomIdNum = parseInt(uomId) || uomId;
      if (uomIdNum != baseUomId && product.uom_conversions && product.uom_conversions.length) {
        const conversion = product.uom_conversions.find(c => c.to_uom_id == uomIdNum);
        if (conversion) {
          conversionFactor = parseFloat(conversion.conversion_factor) || 1;
          uomCode = conversion.to_uom_code;
          uomName = conversion.to_uom_name;
          // Use conversion selling price if available, otherwise calculate from base
          conversionSellingPrice = conversion.conversion_selling_price ? parseFloat(conversion.conversion_selling_price) : null;
          altProductCode = conversion.alt_product_code;
        }
      }
      
      // Calculate unit price: use conversion selling price if available, otherwise base price * factor
      const unitPrice = conversionSellingPrice !== null 
        ? conversionSellingPrice 
        : parseFloat(product.selling_price) * conversionFactor;

      // Calculate available stock in selected UOM
      const availableStock = Math.floor(baseStock / conversionFactor);

      // Check if item already exists in cart with same UOM
      // Use loose equality (==) to handle type mismatches between string/number IDs
      const existingItemIndex = cart.findIndex(item =>
        item.product_id == productId && item.uom_id == uomIdNum
      );

      if (existingItemIndex >= 0) {
        const existingItem = cart[existingItemIndex];
        const newQty = existingItem.quantity + 1;
        const baseQtyNeeded = newQty * existingItem.conversion_factor;

        if (baseQtyNeeded > baseStock) {
          showToast('Not enough stock available', 'error');
          return;
        }
        existingItem.quantity = newQty;
      } else {
        if (availableStock < 1) {
          showToast('Product out of stock', 'error');
          return;
        }

        // Show UOM selector if product has conversions
        if (product.uom_conversions && product.uom_conversions.length && !selectedUomId) {
          showUomSelector(product, baseStock);
          return;
        }

        cart.push({
          product_id: productId,
          product_name: product.product_name,
          product_code: altProductCode || product.product_code,
          product_image_url: normalizeProductImageUrl(product.product_image_url),
          unit_price: unitPrice,
          quantity: 1,
          uom_id: uomIdNum,
          uom_code: uomCode,
          uom_name: uomName,
          base_uom_id: baseUomId,
          base_uom_code: product.base_uom_code,
          conversion_factor: conversionFactor,
          base_stock: baseStock,
          is_alt_product_code: !!altProductCode
        });
      }

      renderCart();
      saveCart();
      showToast('Added to cart');
    }

    // Show UOM selector modal
    function showUomSelector(product, baseStock) {
      const baseUomId = product.base_uom_id;
      const baseCodeDisplay = product.product_code;
      let uomOptions = `
        <div class="pos-uom-option" data-uom-id="${baseUomId}" data-factor="1" data-code="${product.base_uom_code}">
          <div class="pos-uom-name">${product.base_uom_name} (${product.base_uom_code})</div>
          <div class="pos-uom-code">${baseCodeDisplay}${product.barcode ? ` | ${product.barcode}` : ''}</div>
          <div class="pos-uom-stock">Stock: ${Math.floor(baseStock)} ${product.base_uom_code}</div>
          <div class="pos-uom-price">₱${formatMoney(product.selling_price)}</div>
        </div>
      `;

      if (product.uom_conversions && product.uom_conversions.length) {
        product.uom_conversions.forEach(conv => {
          const factor = parseFloat(conv.conversion_factor) || 1;
          const stockInUom = Math.floor(baseStock / factor);
          // Use conversion selling price if set, otherwise calculate from base
          const convPrice = conv.conversion_selling_price
            ? parseFloat(conv.conversion_selling_price)
            : parseFloat(product.selling_price) * factor;
          const codeDisplay = conv.alt_product_code || product.product_code;
          const barcodeDisplay = conv.conversion_barcode ? ` | ${conv.conversion_barcode}` : '';
          uomOptions += `
            <div class="pos-uom-option" data-uom-id="${conv.to_uom_id}" data-factor="${factor}" data-code="${conv.to_uom_code}">
              <div class="pos-uom-name">${conv.to_uom_name} (${conv.to_uom_code})</div>
              <div class="pos-uom-code">${codeDisplay}${barcodeDisplay}</div>
              <div class="pos-uom-stock">Stock: ${stockInUom} ${conv.to_uom_code}</div>
              <div class="pos-uom-conversion">1 ${conv.to_uom_code} = ${factor} ${product.base_uom_code}</div>
              <div class="pos-uom-price">₱${formatMoney(convPrice)}</div>
            </div>
          `;
        });
      }

      const modalHtml = `
        <div class="pos-modal-overlay open" id="uomModal" style="z-index:1000;">
          <div class="pos-modal">
            <div class="pos-modal-header">
              <h3>Select Unit of Measure</h3>
              <p>${product.product_name}</p>
              <button class="pos-modal-close" id="uomModalClose">&times;</button>
            </div>
            <div class="pos-uom-options">
              ${uomOptions}
            </div>
          </div>
        </div>
      `;

      document.body.insertAdjacentHTML('beforeend', modalHtml);

      // Close button handler
      document.getElementById('uomModalClose').addEventListener('click', closeUomModal);

      // Close on overlay click
      document.getElementById('uomModal').addEventListener('click', (e) => {
        if (e.target.id === 'uomModal') closeUomModal();
      });

      // Add click handlers
      document.querySelectorAll('.pos-uom-option').forEach(option => {
        option.addEventListener('click', () => {
          const uomId = option.dataset.uomId;
          closeUomModal();
          addToCart(product.product_id, uomId);
        });
      });
    }

    function closeUomModal() {
      const modal = document.getElementById('uomModal');
      if (modal) modal.remove();
    }

    // Render cart
    function renderCart() {
      // Real-time stock validation before rendering
      validateCartStock();

      const container = document.getElementById('cartItems');
      const countEl = document.getElementById('cartCount');
      const clearBtn = document.getElementById('clearCart');
      const summary = document.getElementById('cartSummary');
      const paymentSection = document.getElementById('paymentSection');
      const checkoutBtn = document.getElementById('checkoutBtn');
      const fabBadge = document.getElementById('cartFabBadge');
      const mobileTotal = document.getElementById('mobileTotal');
      const mobileCheckoutBtn = document.getElementById('mobileCheckoutBtn');

      const totalQty = cart.reduce((sum, item) => sum + item.quantity, 0);
      countEl.textContent = totalQty;
      if (fabBadge) fabBadge.textContent = totalQty;

      const subtotal = cart.reduce((sum, item) => sum + (item.unit_price * item.quantity), 0);
      const total = subtotal * 1.12;
      if (mobileTotal) mobileTotal.textContent = '₱' + formatMoney(total);

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
        if (mobileCheckoutBtn) mobileCheckoutBtn.disabled = true;
        updateProductStockBadges();
        return;
      }

      clearBtn.style.display = 'flex';
      summary.style.display = 'block';
      paymentSection.style.display = 'block';
      checkoutBtn.disabled = false;
      if (mobileCheckoutBtn) mobileCheckoutBtn.disabled = false;

      container.innerHTML = cart.map((item, index) => `
      <div class="pos-cart-item" data-index="${index}">
        <div class="pos-cart-img">
          ${item.product_image_url 
            ? `<img src="${item.product_image_url}" alt="${item.product_name}" onerror="this.style.display='none';this.nextElementSibling.style.display='flex';"><i class="fas fa-box" style="display:none;"></i>` 
            : '<i class="fas fa-box"></i>'}
        </div>
        <div class="pos-cart-info">
          <div class="pos-cart-name">${item.product_name}</div>
          ${item.is_alt_product_code ? `<div class="pos-cart-alt-code" style="font-size:10px;color:var(--pos-blue);font-family:var(--pos-mono);">${item.product_code}</div>` : ''}
          <div class="pos-cart-price">&#x20B1;${formatMoney(item.unit_price)} / ${item.uom_code || 'unit'}</div>
          ${item.conversion_factor !== 1 ? `<div class="pos-cart-uom" style="font-size:11px;color:#666;">(${item.conversion_factor} ${item.base_uom_code})</div>` : ''}
        </div>
        <div class="pos-cart-qty">
          <div class="pos-cart-total">&#x20B1;${formatMoney(item.unit_price * item.quantity)}</div>
          <div style="display:flex;align-items:center;gap:5px;">
            <button class="pos-qty-btn" data-action="decrease" data-index="${index}">-</button>
            <span class="pos-qty-value">${item.quantity} ${item.uom_code || ''}</span>
            <button class="pos-qty-btn" data-action="increase" data-index="${index}">+</button>
          </div>
        </div>
        <button class="pos-cart-item-remove" data-action="remove" data-index="${index}"><i class="fas fa-times"></i></button>
      </div>
    `).join('');

      updateSummary();
      updateProductStockBadges();
    }

    // Update stock badges on product cards in-place (avoids re-render / infinite loop)
    function updateProductStockBadges() {
      document.querySelectorAll('.pos-product-card').forEach(card => {
        const productId = card.dataset.id;
        const product = products.find(p => p.product_id == productId);
        if (!product) return;

        const rawStock = parseFloat(product.current_stock) || 0;
        const reserved = cart
          .filter(item => item.product_id == productId)
          .reduce((sum, item) => sum + (item.quantity * item.conversion_factor), 0);
        const stock = Math.max(0, rawStock - reserved);
        const reorder = parseFloat(product.reorder_point) || 0;

        const badge = card.querySelector('.pos-product-stock');
        if (!badge) return;

        if (stock <= 0) {
          card.classList.add('out-of-stock');
          card.classList.remove('low-stock');
          badge.classList.add('low');
          badge.textContent = rawStock > 0 ? 'In Cart' : 'Out of Stock';
          if (rawStock <= 0) card.setAttribute('disabled', true);
        } else if (stock <= reorder) {
          card.classList.remove('out-of-stock');
          card.classList.add('low-stock');
          badge.classList.add('low');
          badge.textContent = Math.floor(stock) + ' left';
          card.removeAttribute('disabled');
        } else {
          card.classList.remove('out-of-stock');
          card.classList.remove('low-stock');
          badge.classList.remove('low');
          badge.textContent = Math.floor(stock) + ' in stock';
          card.removeAttribute('disabled');
        }
      });
    }

    // Update summary
    function updateSummary() {
      const subtotal = cart.reduce((sum, item) => sum + (item.unit_price * item.quantity), 0);
      
      let tax = 0;
      let total = subtotal;
      
      if (posSettings.vat_enabled) {
        tax = subtotal * (posSettings.vat_rate / 100);
        total = subtotal + tax;
      }

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
      // Search with barcode scanner support
      const searchInput = document.getElementById('posSearch');
      searchInput.addEventListener('input', (e) => {
        const activeCat = document.querySelector('.pos-cat-btn.active');
        renderProducts(e.target.value, activeCat ? activeCat.dataset.category : '');
      });

      // Barcode scanner Enter key support - increments qty for consecutive scans
      searchInput.addEventListener('keydown', (e) => {
        if (e.key !== 'Enter') return;
        e.preventDefault();

        const input = searchInput.value.trim();
        if (!input) return;

        const inputLower = input.toLowerCase();

        // Find product by barcode or product code (exact match)
        // Also check UOM conversion barcodes
        let matchedProduct = null;
        let matchedUomId = null;

        for (const p of products) {
          const barcode = p.barcode ? p.barcode.trim().toLowerCase() : '';
          const code = p.product_code ? p.product_code.trim().toLowerCase() : '';

          // Check base product barcode/code
          if (barcode === inputLower || code === inputLower) {
            matchedProduct = p;
            matchedUomId = parseInt(p.base_uom_id);
            break;
          }

          // Check UOM conversion barcodes
          if (p.uom_conversions && p.uom_conversions.length) {
            const conversion = p.uom_conversions.find(c =>
              c.conversion_barcode && c.conversion_barcode.trim().toLowerCase() === inputLower
            );
            if (conversion) {
              matchedProduct = p;
              matchedUomId = parseInt(conversion.to_uom_id);
              break;
            }
          }
        }

        if (!matchedProduct) {
          showToast('Product not found: ' + input.substring(0, 20), 'error');
          searchInput.value = '';
          focusSearch();
          return;
        }

        const existingItem = cart.find(item =>
          item.product_id == matchedProduct.product_id &&
          item.uom_id == matchedUomId
        );

        if (existingItem) {
          // Increment quantity
          const newQty = existingItem.quantity + 1;
          const baseStock = parseFloat(matchedProduct.current_stock) || 0;
          const baseQtyNeeded = newQty * existingItem.conversion_factor;

          if (baseQtyNeeded > baseStock) {
            showToast('Not enough stock available', 'error');
          } else {
            existingItem.quantity = newQty;
            renderCart();
            saveCart();
            showToast(`${matchedProduct.product_name} × ${newQty}`, 'success');
          }
        } else {
          // Add new item with specific UOM (base or conversion)
          // Pass matchedUomId to skip the selector modal
          addToCart(matchedProduct.product_id, matchedUomId);
          showToast(`${matchedProduct.product_name} added`, 'success');
        }

        searchInput.value = '';
        renderProducts('', document.querySelector('.pos-cat-btn.active')?.dataset.category || '');
        focusSearch();
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
          const item = cart[index];
          // Check real-time stock from products data
          const product = products.find(p => p.product_id == item.product_id);
          const currentStock = product ? (parseFloat(product.current_stock) || 0) : item.base_stock;
          const baseQtyNeeded = (item.quantity + 1) * item.conversion_factor;
          if (baseQtyNeeded > currentStock) {
            showToast('Not enough stock available', 'error');
            return;
          }
          // Update the stored base_stock to current value
          cart[index].base_stock = currentStock;
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
        saveCart();
        focusSearch();
      });

      // Clear cart
      document.getElementById('clearCart').addEventListener('click', () => {
        cart = [];
        renderCart();
        clearSavedCart();
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
        clearSavedCart();
        renderCart();
        closeReceiptModal();
        document.getElementById('customerName').value = '';
        focusSearch();
      });

      document.getElementById('printReceiptBtn').addEventListener('click', printReceipt);

      // Mobile cart FAB toggle
      const cartFab = document.getElementById('cartFab');
      const cartOverlay = document.getElementById('cartOverlay');
      const cartPanel = document.querySelector('.pos-cart-panel');

      if (cartFab) {
        cartFab.addEventListener('click', () => {
          cartPanel.classList.add('open');
          cartOverlay.classList.add('open');
        });
      }

      if (cartOverlay) {
        cartOverlay.addEventListener('click', () => {
          cartPanel.classList.remove('open');
          cartOverlay.classList.remove('open');
        });
      }

      // Mobile checkout button
      const mobileCheckoutBtn = document.getElementById('mobileCheckoutBtn');
      if (mobileCheckoutBtn) {
        mobileCheckoutBtn.addEventListener('click', () => {
          cartPanel.classList.remove('open');
          cartOverlay.classList.remove('open');
          openCheckoutModal();
        });
      }

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
      const subtotal = cart.reduce((sum, item) => sum + (item.unit_price * item.quantity), 0);
      let tax = 0;
      if (posSettings.vat_enabled) {
        tax = subtotal * (posSettings.vat_rate / 100);
      }
      const total = subtotal + tax;
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
      const subtotal = cart.reduce((sum, item) => sum + (item.unit_price * item.quantity), 0);
      let tax = 0;
      if (posSettings.vat_enabled) {
        tax = subtotal * (posSettings.vat_rate / 100);
      }
      const total = subtotal + tax;
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
      // Final stock validation before processing
      validateCartStock();

      // Check if cart is empty after validation
      if (cart.length === 0) {
        showToast('Cart is empty. Please add items.', 'error');
        closeCheckoutModal();
        return;
      }

      // Check if any items are out of stock
      const outOfStockItems = cart.filter(item => {
        const product = products.find(p => p.product_id == item.product_id);
        if (!product) return true;
        const currentStock = parseFloat(product.current_stock) || 0;
        return item.quantity > Math.floor(currentStock / item.conversion_factor);
      });

      if (outOfStockItems.length > 0) {
        const itemNames = outOfStockItems.map(i => i.product_name).join(', ');
        showToast(`Cannot complete sale: ${itemNames} - insufficient stock`, 'error');
        closeCheckoutModal();
        renderCart(); // Re-render to show adjusted quantities
        return;
      }

      const customerName = document.getElementById('customerName').value.trim();
      const saleType = document.getElementById('saleTypeSelect').value;
      const staffMemberId = document.getElementById('staffMemberSelect').value;
      const subtotal = cart.reduce((sum, item) => sum + (item.unit_price * item.quantity), 0);
      let tax = 0;
      if (posSettings.vat_enabled) {
        tax = subtotal * (posSettings.vat_rate / 100);
      }
      const total = subtotal + tax;

      // Generate sale number
      const saleNumber = 'POS-' + new Date().getFullYear() + '-' + String(Math.floor(Math.random() * 100000)).padStart(5, '0');

      const saleData = {
        csrf_token: csrfToken,
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
          product_name: item.product_name,
          uom_id: item.uom_id,
          quantity: item.quantity,
          unit_price: item.unit_price.toFixed(2),
          total_price: (item.unit_price * item.quantity).toFixed(2),
          uom_code: item.uom_code,
          uom_name: item.uom_name,
          base_uom_code: item.base_uom_code,
          conversion_factor: item.conversion_factor
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
          cart = [];
          clearSavedCart();
          renderCart();
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
            <span>${item.quantity}${item.uom_code ? ' ' + item.uom_code : ''} x ${item.product_name}${item.conversion_factor && item.conversion_factor !== 1 ? ' (' + item.conversion_factor + ' ' + item.base_uom_code + ')' : ''}</span>
            <span>₱${formatMoney(item.total_price)}</span>
          </div>
        `).join('')}
      </div>
      <div style="border-top:1px dashed #CBD5E1;padding-top:12px;margin-bottom:12px;">
        <div class="pos-receipt-item">
          <span>Subtotal</span>
          <span>₱${formatMoney(saleData.subtotal)}</span>
        </div>
        ${parseFloat(saleData.tax_amount) > 0 ? `
        <div class="pos-receipt-item">
          <span>Tax (${posSettings.vat_rate}%)</span>
          <span>₱${formatMoney(saleData.tax_amount)}</span>
        </div>` : ''}
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
      const receiptContent = document.getElementById('receiptPreview').innerHTML;
      const printHTML = `
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
    `;
      const printWindow = window.open('data:text/html;charset=utf-8,' + encodeURIComponent(printHTML), '_blank');
      printWindow.onload = function() {
        printWindow.print();
      };
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