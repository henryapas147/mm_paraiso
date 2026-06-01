<?php if (false) {} ?>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Lora:ital,wght@0,600;1,500&display=swap" rel="stylesheet">
<style>
  /* ── Design Tokens ── */
  :root {
    --brand:        #C84B31;
    --brand-dark:   #A03525;
    --brand-light:  #FDF0ED;
    --accent:       #F0A500;
    --accent-light: #FFF8E7;
    --surface:      #FFFFFF;
    --surface-2:    #FAFAFA;
    --border:       #EDEAE6;
    --border-strong:#D6D0C8;
    --text-primary: #1A1410;
    --text-secondary:#6B6460;
    --text-muted:   #A09890;
    --success:      #2D7D4F;
    --success-bg:   #EDFAF3;
    --warning:      #B45309;
    --warning-bg:   #FEF7EC;
    --danger:       #C0392B;
    --danger-bg:    #FEF0EE;
    --radius-sm:    6px;
    --radius-md:    10px;
    --radius-lg:    16px;
    --radius-xl:    20px;
    --shadow-card:  0 1px 3px rgba(0,0,0,.06), 0 4px 16px rgba(0,0,0,.06);
    --shadow-modal: 0 24px 64px rgba(0,0,0,.18);
    --font-display: 'Lora', Georgia, serif;
    --font-body:    'DM Sans', system-ui, sans-serif;
  }

  /* ── Stats Grid ── */
  .fo-stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
    margin-bottom: 24px;
  }
  @media (max-width: 1024px) { .fo-stats-grid { grid-template-columns: repeat(2, 1fr); } }
  @media (max-width: 600px)  { .fo-stats-grid { grid-template-columns: 1fr 1fr; } }

  .fo-stat-card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    padding: 20px 22px;
    box-shadow: var(--shadow-card);
    display: flex; align-items: center; gap: 16px;
    transition: box-shadow .2s, transform .2s;
  }
  .fo-stat-card:hover { transform: translateY(-2px); box-shadow: 0 6px 24px rgba(0,0,0,.10); }

  .fo-stat-icon {
    width: 48px; height: 48px;
    border-radius: var(--radius-md);
    display: flex; align-items: center; justify-content: center;
    font-size: 20px; flex-shrink: 0;
  }
  .fo-stat-icon.total   { background: #EFF3FF; color: #3B5BDB; }
  .fo-stat-icon.recipes { background: var(--brand-light); color: var(--brand); }
  .fo-stat-icon.cost    { background: var(--success-bg); color: var(--success); }
  .fo-stat-icon.items   { background: var(--accent-light); color: var(--accent); }

  .fo-stat-label {
    font-family: var(--font-body);
    font-size: 11.5px; font-weight: 600;
    letter-spacing: .06em; text-transform: uppercase;
    color: var(--text-muted); margin-bottom: 4px;
  }
  .fo-stat-value {
    font-family: var(--font-display);
    font-size: 28px; font-weight: 600;
    color: var(--text-primary); line-height: 1;
  }

  /* ── Card ── */
  .fo-card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius-xl);
    box-shadow: var(--shadow-card);
    overflow: hidden;
    margin-bottom: 24px;
  }

  .fo-card-header {
    display: flex; align-items: center;
    justify-content: space-between;
    padding: 20px 24px 18px;
    border-bottom: 1px solid var(--border);
    gap: 12px; flex-wrap: wrap;
  }

  .fo-card-title {
    font-family: var(--font-display);
    font-size: 20px; font-weight: 600;
    color: var(--text-primary); margin: 0;
    font-style: italic;
  }

  .fo-card-actions { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }

  /* Search */
  .fo-search-wrap { position: relative; }
  .fo-search-wrap i {
    position: absolute; left: 11px; top: 50%; transform: translateY(-50%);
    color: var(--text-muted); font-size: 13px; pointer-events: none;
  }
  .fo-search {
    font-family: var(--font-body); font-size: 13px;
    background: var(--surface-2); border: 1px solid var(--border);
    border-radius: var(--radius-md);
    padding: 8px 12px 8px 32px;
    color: var(--text-primary); outline: none; width: 220px;
    transition: border-color .15s, box-shadow .15s;
  }
  .fo-search:focus {
    border-color: var(--brand);
    box-shadow: 0 0 0 3px rgba(200,75,49,.10);
    background: var(--surface);
  }

  /* Primary Button */
  .fo-btn-primary {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 8px 18px;
    font-family: var(--font-body); font-size: 13px; font-weight: 600;
    background: var(--brand); color: #fff;
    border: none; border-radius: var(--radius-md);
    cursor: pointer;
    transition: background .15s, box-shadow .15s, transform .1s;
    box-shadow: 0 2px 8px rgba(200,75,49,.30);
    white-space: nowrap;
  }
  .fo-btn-primary:hover { background: var(--brand-dark); transform: translateY(-1px); box-shadow: 0 4px 14px rgba(200,75,49,.38); }
  .fo-btn-primary:active { transform: translateY(0); }

  .fo-btn-secondary {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 8px 18px;
    font-family: var(--font-body); font-size: 13px; font-weight: 600;
    background: var(--surface); color: var(--text-secondary);
    border: 1px solid var(--border-strong); border-radius: var(--radius-md);
    cursor: pointer;
    transition: all .15s;
  }
  .fo-btn-secondary:hover { background: var(--surface-2); color: var(--text-primary); }

  /* ── Form Fields ── */
  .fo-form-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 16px;
  }
  @media (max-width: 768px) { .fo-form-grid { grid-template-columns: 1fr; } }

  .fo-form-group { margin-bottom: 16px; }

  .fo-label {
    display: block; font-size: 12px; font-weight: 600;
    color: var(--text-secondary); margin-bottom: 6px;
    text-transform: uppercase; letter-spacing: .03em;
  }

  .fo-input, .fo-select, .fo-textarea {
    width: 100%; padding: 10px 14px;
    font-family: var(--font-body); font-size: 14px;
    background: var(--surface); border: 1px solid var(--border-strong);
    border-radius: var(--radius-sm); color: var(--text-primary);
    transition: border-color .15s, box-shadow .15s;
  }
  .fo-input:focus, .fo-select:focus, .fo-textarea:focus {
    border-color: var(--brand);
    outline: none;
    box-shadow: 0 0 0 3px rgba(200,75,49,.08);
  }
  .fo-textarea { resize: vertical; min-height: 100px; }

  /* ── Recipe Layout ── */
  .recipe-layout {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 24px;
  }
  @media (max-width: 1200px) { .recipe-layout { grid-template-columns: 1fr; } }

  /* ── Ingredients List ── */
  .ingredients-list {
    margin-top: 16px;
  }
  .ingredient-row {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr auto;
    gap: 12px;
    align-items: center;
    padding: 12px;
    background: var(--surface-2);
    border-radius: var(--radius-md);
    margin-bottom: 12px;
    border: 1px solid var(--border);
  }
  @media (max-width: 768px) {
    .ingredient-row {
      grid-template-columns: 1fr;
      gap: 8px;
    }
  }
  .ingredient-product-select, .ingredient-qty, .ingredient-uom {
    font-family: var(--font-body); font-size: 13px;
    padding: 8px 12px;
    border: 1px solid var(--border-strong);
    border-radius: var(--radius-sm);
    background: var(--surface);
    color: var(--text-primary);
  }
  .ingredient-product-select:focus, .ingredient-qty:focus, .ingredient-uom:focus {
    border-color: var(--brand);
    outline: none;
  }
  .ingredient-remove {
    width: 32px; height: 32px;
    border: none; border-radius: var(--radius-sm);
    background: var(--danger-bg);
    color: var(--danger);
    cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    transition: all .15s;
  }
  .ingredient-remove:hover {
    background: var(--danger);
    color: white;
  }

  /* ── Recipe Stats ── */
  .recipe-stats {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 16px;
    margin-top: 20px;
    padding-top: 20px;
    border-top: 1px solid var(--border);
  }
  .stat-box {
    text-align: center;
    padding: 16px;
    background: var(--surface-2);
    border-radius: var(--radius-md);
  }
  .stat-value {
    font-family: var(--font-display);
    font-size: 24px; font-weight: 700;
    color: var(--brand);
    margin-bottom: 4px;
  }
  .stat-label {
    font-size: 11px; font-weight: 600;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: .04em;
  }

  /* ── Empty State ── */
  .fo-empty {
    text-align: center; padding: 64px 24px;
  }
  .fo-empty-icon {
    width: 72px; height: 72px;
    border-radius: 50%;
    background: var(--surface-2);
    border: 1px solid var(--border);
    display: flex; align-items: center; justify-content: center;
    font-size: 28px; color: var(--text-muted);
    margin: 0 auto 20px;
  }
  .fo-empty h4 {
    font-family: var(--font-display);
    font-size: 18px; font-weight: 600; color: var(--text-primary);
    margin: 0 0 6px; font-style: italic;
  }
  .fo-empty p { font-size: 13.5px; color: var(--text-secondary); margin: 0 0 20px; }

  /* ── Loading State ── */
  .fo-loading {
    text-align: center;
    padding: 48px;
    color: var(--text-muted);
  }

  /* ── Custom Alert Modal ── */
  .alert-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, .45);
    z-index: 10000;
    align-items: center;
    justify-content: center;
    padding: 20px
  }
  .alert-overlay.open { display: flex }
  .alert-box {
    background: #fff;
    border-radius: 12px;
    border: 1px solid var(--border);
    width: 100%;
    max-width: 400px;
    padding: 24px;
    box-shadow: 0 16px 32px rgba(0, 0, 0, .15)
  }
  .alert-title {
    font-family: var(--font-display);
    font-size: 16px;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 8px
  }
  .alert-message {
    font-size: 13px;
    color: var(--text-secondary);
    margin-bottom: 20px;
    line-height: 1.5
  }
  .alert-actions {
    display: flex;
    gap: 10px;
    justify-content: flex-end
  }
  .alert-btn-cancel {
    padding: 8px 16px;
    font-size: 13px;
    font-weight: 600;
    background: var(--surface);
    color: var(--text-secondary);
    border: 1px solid var(--border-strong);
    border-radius: var(--radius-md);
    cursor: pointer;
    transition: all .15s
  }
  .alert-btn-cancel:hover {
    background: var(--surface-2);
    color: var(--text-primary)
  }
  .alert-btn-confirm {
    padding: 8px 16px;
    font-size: 13px;
    font-weight: 600;
    background: var(--brand);
    color: #fff;
    border: none;
    border-radius: var(--radius-md);
    cursor: pointer;
    transition: all .15s
  }
  .alert-btn-confirm:hover {
    background: var(--brand-dark)
  }

  /* ── Recipes Table ── */
  .fo-table { width: 100%; border-collapse: collapse; }
  .fo-table th {
    padding: 11px 16px;
    font-size: 11px; font-weight: 700;
    text-transform: uppercase; letter-spacing: .06em;
    color: var(--text-muted);
    background: var(--surface-2);
    border-bottom: 1px solid var(--border);
    white-space: nowrap;
    text-align: left;
  }
  .fo-table th.center { text-align: center; }
  .fo-table td {
    padding: 13px 16px;
    font-size: 13.5px;
    color: var(--text-primary);
    border-bottom: 1px solid var(--border);
    vertical-align: middle;
  }
  .fo-table td.center { text-align: center; }
  .fo-table tr:last-child td { border-bottom: none; }
  .fo-table tr:hover td { background: var(--surface-2); }
  .fo-tag {
    display: inline-block;
    padding: 3px 9px;
    border-radius: 99px;
    font-size: 11.5px; font-weight: 600;
  }
  .fo-tag-category { background: #EFF3FF; color: #3B5BDB; }

  /* ── Table Footer & Pagination ── */
  .fo-table-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px 20px;
    border-top: 1px solid var(--border);
    gap: 12px;
    flex-wrap: wrap;
  }
  .fo-table-info {
    font-size: 12px;
    color: var(--text-secondary);
  }
  .fo-table-pagination {
    display: flex;
    align-items: center;
    gap: 8px;
  }
  .fo-table-pagination button {
    padding: 6px 10px;
    font-size: 12px;
  }
  .fo-table-pagination button:disabled {
    opacity: 0.4;
    cursor: not-allowed;
  }
  .fo-table-pagination span {
    font-size: 12px;
    color: var(--text-secondary);
  }

  /* ── Searchable Dropdown ── */
  .searchable-dropdown {
    position: relative;
    width: 100%
  }
  .searchable-dropdown-input {
    width: 100%;
    padding: 10px 12px;
    font-size: 14px;
    border: 1px solid var(--border);
    border-radius: var(--radius-md);
    background: var(--surface);
    color: var(--text-primary);
    cursor: pointer;
    transition: all .15s
  }
  .searchable-dropdown-input:focus {
    outline: none;
    border-color: var(--brand);
    box-shadow: 0 0 0 3px rgba(24, 95, 165, .1)
  }
  .searchable-dropdown-input::placeholder {
    color: var(--text-muted)
  }
  .searchable-dropdown-options {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    max-height: 250px;
    overflow-y: auto;
    background: #fff;
    border: 1px solid var(--border);
    border-radius: var(--radius-md);
    box-shadow: 0 8px 24px rgba(0, 0, 0, .12);
    z-index: 1000;
    display: none;
    margin-top: 4px
  }
  .searchable-dropdown-options.show {
    display: block
  }
  .searchable-dropdown-option {
    padding: 10px 12px;
    font-size: 14px;
    color: var(--text-primary);
    cursor: pointer;
    transition: background .15s
  }
  .searchable-dropdown-option:hover {
    background: var(--surface-2)
  }
  .searchable-dropdown-option.selected {
    background: var(--brand);
    color: #fff
  }
  .searchable-dropdown-option.no-results {
    padding: 12px;
    color: var(--text-muted);
    font-size: 13px;
    text-align: center;
    cursor: default
  }
  .searchable-dropdown-arrow {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-muted);
    pointer-events: none
  }
</style>

<!-- Stats Cards -->
<div class="fo-stats-grid">
  <div class="fo-stat-card">
    <div class="fo-stat-icon recipes"><i class="fas fa-utensils"></i></div>
    <div>
      <div class="fo-stat-label">Menu Items</div>
      <div class="fo-stat-value" id="statMenuItems">—</div>
    </div>
  </div>
  <div class="fo-stat-card">
    <div class="fo-stat-icon total"><i class="fas fa-book-open"></i></div>
    <div>
      <div class="fo-stat-label">Recipes</div>
      <div class="fo-stat-value" id="statRecipes">—</div>
    </div>
  </div>
  <div class="fo-stat-card">
    <div class="fo-stat-icon cost"><i class="fas fa-peso-sign"></i></div>
    <div>
      <div class="fo-stat-label">Avg Cost/Serving</div>
      <div class="fo-stat-value" id="statAvgCost">—</div>
    </div>
  </div>
  <div class="fo-stat-card">
    <div class="fo-stat-icon items"><i class="fas fa-shopping-basket"></i></div>
    <div>
      <div class="fo-stat-label">Inventory Items</div>
      <div class="fo-stat-value" id="statInventory">—</div>
    </div>
  </div>
</div>

<!-- Recipes List Card -->
<div class="fo-card" id="recipesListCard">
  <div class="fo-card-header">
    <h2 class="fo-card-title">Recipes</h2>
    <div class="fo-card-actions">
      <div class="fo-search-wrap">
        <i class="fas fa-search"></i>
        <input type="text" class="fo-search" id="recipeSearchInput" placeholder="Search recipes..." oninput="filterRecipesList()">
      </div>
      <button class="fo-btn-primary" onclick="openNewRecipeEditor()">
        <i class="fas fa-plus"></i> Add Recipe
      </button>
    </div>
  </div>
  <div id="recipesTableWrap" style="overflow-x:auto;">
    <table class="fo-table">
      <thead>
        <tr>
          <th>Menu Item</th>
          <th>Recipe Name</th>
          <th>Prep / Cook</th>
          <th>Yield</th>
          <th class="center">Actions</th>
        </tr>
      </thead>
      <tbody id="recipesTableBody">
        <tr><td colspan="5"><div class="fo-empty"><div class="fo-empty-icon"><i class="fas fa-spinner fa-spin"></i></div><p>Loading...</p></div></td></tr>
      </tbody>
    </table>
  </div>
  <div class="fo-table-footer">
    <div class="fo-table-info">
      <span id="recipesTableInfo">Showing 0 of 0 entries</span>
    </div>
    <div class="fo-table-pagination">
      <select id="recipesPerPage" class="fo-select" style="width: auto; padding: 6px 10px; font-size: 12px;" onchange="changeRecipesPerPage()">
        <option value="10">10 per page</option>
        <option value="25">25 per page</option>
        <option value="50">50 per page</option>
        <option value="100">100 per page</option>
      </select>
      <button class="fo-btn-secondary" id="recipesPrevBtn" onclick="changeRecipesPage(-1)" disabled>
        <i class="fas fa-chevron-left"></i>
      </button>
      <span id="recipesPageInfo">Page 1 of 1</span>
      <button class="fo-btn-secondary" id="recipesNextBtn" onclick="changeRecipesPage(1)" disabled>
        <i class="fas fa-chevron-right"></i>
      </button>
    </div>
  </div>
</div>

<!-- Recipe Editor Card (hidden until add/edit) -->
<div class="fo-card" id="recipeEditorCard" style="display:none;">
  <div class="fo-card-header">
    <h2 class="fo-card-title" id="editorTitle">Add Recipe</h2>
    <div class="fo-card-actions">
      <button class="fo-btn-secondary" onclick="closeRecipeEditor()">
        <i class="fas fa-arrow-left"></i> Back to List
      </button>
    </div>
  </div>

  <!-- Menu Item Selector (shown only when adding new) -->
  <div id="menuItemSelectorWrap" style="padding: 20px 24px; border-bottom: 1px solid var(--border);">
    <label class="fo-label">Menu Item <span style="color:var(--danger)">*</span></label>
    <div class="fo-search-wrap" style="width: 320px;">
      <i class="fas fa-search"></i>
      <div class="searchable-dropdown" id="menuSearchableDropdown" style="width: 100%;">
        <input type="text" class="searchable-dropdown-input" id="menuSearchInput" placeholder="Select a menu item..." autocomplete="off" style="border:none;background:transparent;padding-left:32px;">
        <span class="searchable-dropdown-arrow"><i class="fas fa-chevron-down"></i></span>
        <div class="searchable-dropdown-options" id="menuDropdownOptions"></div>
      </div>
    </div>
  </div>

  <!-- Recipe Layout -->
  <div id="recipeContainer" style="padding: 24px;">
    <div class="recipe-layout">
      
      <!-- Left: Recipe Details -->
      <div class="fo-card" style="margin-bottom: 0;">
        <div class="fo-card-header" style="padding: 16px 20px;">
          <h3 class="fo-card-title" style="font-size: 16px;">Recipe Details</h3>
        </div>
        <div style="padding: 20px;">
          <form id="recipeForm">
            <input type="hidden" id="recipeId" name="recipe_id">
            <input type="hidden" id="itemId" name="item_id">
            
            <div class="fo-form-group">
              <label class="fo-label">Recipe Name</label>
              <input type="text" id="recipeName" name="recipe_name" class="fo-input" placeholder="e.g. Classic Burger Recipe">
            </div>
            
            <div class="fo-form-grid">
              <div class="fo-form-group">
                <label class="fo-label">Prep Time (min)</label>
                <input type="number" id="prepTime" name="preparation_time" class="fo-input" min="0" placeholder="30">
              </div>
              <div class="fo-form-group">
                <label class="fo-label">Cook Time (min)</label>
                <input type="number" id="cookTime" name="cooking_time" class="fo-input" min="0" placeholder="20">
              </div>
            </div>
            
            <div class="fo-form-grid">
              <div class="fo-form-group">
                <label class="fo-label">Portion Size</label>
                <input type="text" id="portionSize" name="portion_size" class="fo-input" placeholder="e.g. 1 serving">
              </div>
              <div class="fo-form-group">
                <label class="fo-label">Yield Qty</label>
                <input type="number" id="yieldQty" name="yield_quantity" class="fo-input" min="1" step="0.001" value="1">
              </div>
            </div>
            
            <div class="fo-form-group">
              <label class="fo-label">Instructions</label>
              <textarea id="recipeDesc" name="description" class="fo-textarea" placeholder="Enter preparation instructions..."></textarea>
            </div>
          </form>
        </div>
      </div>

      <!-- Right: Ingredients -->
      <div class="fo-card" style="margin-bottom: 0;">
        <div class="fo-card-header" style="padding: 16px 20px;">
          <h3 class="fo-card-title" style="font-size: 16px;">Ingredients</h3>
        </div>
        <div style="padding: 20px;">
          <div id="ingredientsList" class="ingredients-list"></div>
          
          <button type="button" class="fo-btn-secondary" onclick="addIngredientRow()" style="margin-top: 12px; width: 100%; justify-content: center;">
            <i class="fas fa-plus"></i> Add Ingredient
          </button>
          
          <div class="recipe-stats">
            <div class="stat-box">
              <div class="stat-value" id="totalIngredients">0</div>
              <div class="stat-label">Ingredients</div>
            </div>
            <div class="stat-box">
              <div class="stat-value" id="totalCost">₱0.00</div>
              <div class="stat-label">Total Cost</div>
            </div>
            <div class="stat-box">
              <div class="stat-value" id="costPerServing">₱0.00</div>
              <div class="stat-label">Cost/Serving</div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Action Buttons -->
    <div style="padding: 24px 0 0;">
      <button type="button" class="fo-btn-primary" onclick="saveRecipe()">
        <i class="fas fa-save"></i> Save Recipe
      </button>
      &nbsp;
      <button type="button" class="fo-btn-secondary" onclick="resetForm()">
        <i class="fas fa-undo"></i> Reset
      </button>
    </div>
  </div>
</div>

<!-- Custom Alert Modal -->
<div class="alert-overlay" id="alertModal">
  <div class="alert-box">
    <div class="alert-title" id="alertTitle">Confirm Action</div>
    <div class="alert-message" id="alertMessage">Are you sure you want to proceed?</div>
    <div class="alert-actions">
      <button class="alert-btn-cancel" onclick="closeAlert()">Cancel</button>
      <button class="alert-btn-confirm" id="alertConfirmBtn">Confirm</button>
    </div>
  </div>
</div>

<!-- View Recipe Modal -->
<div class="alert-overlay" id="viewRecipeModal">
  <div class="alert-box" style="max-width: 600px;">
    <div class="alert-title" id="viewRecipeTitle">Recipe Details</div>
    <div id="viewRecipeContent" style="max-height: 400px; overflow-y: auto; margin-bottom: 20px;">
      <!-- Recipe content will be loaded here -->
    </div>
    <div class="alert-actions">
      <button class="alert-btn-cancel" onclick="closeViewRecipeModal()">Close</button>
    </div>
  </div>
</div>

<script>
  let menuItems         = [];
  let inventoryProducts = [];
  let unitsOfMeasure    = [];
  let currentRecipe     = null;
  let ingredientRowCount = 0;
  const csrfToken = '<?php echo CSRF_TOKEN; ?>';

  let allRecipes = [];
  let filteredRecipes = [];
  let currentPage = 1;
  let rowsPerPage = 10;

  // ── Bootstrap ──────────────────────────────────────────────────────────────
  document.addEventListener('DOMContentLoaded', async () => {
    await Promise.all([fetchRelatedData(), loadRecipesList()]);
    updateStatsGrid(allRecipes);
  });

  async function fetchRelatedData() {
    try {
      const response = await fetch('../api/recipes.php?action=related');
      const result   = await response.json();
      if (result.success && result.data) {
        menuItems         = result.data.menuItems  || [];
        inventoryProducts = result.data.products   || [];
        unitsOfMeasure    = result.data.uoms        || [];
        populateMenuItemSelect();
      } else {
        console.error('fetchRelatedData – API error:', result.message);
      }
    } catch (err) {
      console.error('fetchRelatedData – network error:', err);
    }
  }

  // ── Recipes list ───────────────────────────────────────────────────────────
  async function loadRecipesList() {
    try {
      const response = await fetch('../api/recipes.php?action=list');
      const result   = await response.json();
      if (result.success && result.data) {
        allRecipes = result.data;
        filteredRecipes = [...allRecipes];
        currentPage = 1;
        renderRecipesList();
      }
    } catch (err) {
      console.error('loadRecipesList error:', err);
    }
  }

  function renderRecipesList() {
    const tbody = document.getElementById('recipesTableBody');
    if (!filteredRecipes || filteredRecipes.length === 0) {
      tbody.innerHTML = `
        <tr><td colspan="5">
          <div class="fo-empty">
            <div class="fo-empty-icon"><i class="fas fa-book-open"></i></div>
            <h4>No recipes yet</h4>
            <p>Click "Add Recipe" to create your first recipe.</p>
          </div>
        </td></tr>`;
      updatePaginationInfo(0);
      return;
    }

    const start = (currentPage - 1) * rowsPerPage;
    const end = start + rowsPerPage;
    const pageData = filteredRecipes.slice(start, end);

    tbody.innerHTML = pageData.map(r => `
      <tr>
        <td>
          <div style="font-weight:600;">${r.item_name || '—'}</div>
          ${r.category_name ? `<span class="fo-tag fo-tag-category">${r.category_name}</span>` : ''}
        </td>
        <td>${r.recipe_name || '—'}</td>
        <td style="color:var(--text-secondary);">
          ${r.preparation_time ? r.preparation_time + 'm prep' : '—'}
          ${r.cooking_time ? ' / ' + r.cooking_time + 'm cook' : ''}
        </td>
        <td>${r.yield_quantity ? parseFloat(r.yield_quantity).toLocaleString() + ' serving(s)' : '—'}</td>
        <td class="center">
          <button onclick="viewRecipe('${r.item_id}')" class="fo-btn-secondary" style="padding:6px 14px;font-size:12px;">
            <i class="fas fa-eye"></i> View
          </button>
          <button onclick="openEditRecipe('${r.item_id}')" class="fo-btn-secondary" style="padding:6px 14px;font-size:12px;">
            <i class="fas fa-edit"></i> Edit
          </button>
        </td>
      </tr>`).join('');

    updatePaginationInfo(filteredRecipes.length);
  }

  function updatePaginationInfo(totalEntries) {
    const totalPages = Math.ceil(totalEntries / rowsPerPage) || 1;
    const start = totalEntries === 0 ? 0 : (currentPage - 1) * rowsPerPage + 1;
    const end = Math.min(currentPage * rowsPerPage, totalEntries);

    document.getElementById('recipesTableInfo').textContent = `Showing ${start}-${end} of ${totalEntries} entries`;
    document.getElementById('recipesPageInfo').textContent = `Page ${currentPage} of ${totalPages}`;
    document.getElementById('recipesPrevBtn').disabled = currentPage === 1;
    document.getElementById('recipesNextBtn').disabled = currentPage === totalPages;
  }

  function changeRecipesPage(delta) {
    const totalPages = Math.ceil(filteredRecipes.length / rowsPerPage) || 1;
    const newPage = currentPage + delta;
    if (newPage >= 1 && newPage <= totalPages) {
      currentPage = newPage;
      renderRecipesList();
    }
  }

  function changeRecipesPerPage() {
    rowsPerPage = parseInt(document.getElementById('recipesPerPage').value);
    currentPage = 1;
    renderRecipesList();
  }

  function filterRecipesList() {
    const term = document.getElementById('recipeSearchInput').value.toLowerCase();
    filteredRecipes = allRecipes.filter(r =>
      (r.item_name || '').toLowerCase().includes(term) ||
      (r.recipe_name || '').toLowerCase().includes(term) ||
      (r.category_name || '').toLowerCase().includes(term)
    );
    currentPage = 1;
    renderRecipesList();
  }

  function updateStatsGrid(recipes) {
    document.getElementById('statMenuItems').textContent  = menuItems.length;
    document.getElementById('statInventory').textContent  = inventoryProducts.length;
    document.getElementById('statRecipes').textContent    = recipes.length;

    let totalCostPerServing = 0, recipesWithCost = 0;
    recipes.forEach(r => {
      if (r.total_cost && r.yield_quantity) {
        totalCostPerServing += r.total_cost / r.yield_quantity;
        recipesWithCost++;
      }
    });
    const avgCost = recipesWithCost > 0 ? totalCostPerServing / recipesWithCost : 0;
    document.getElementById('statAvgCost').textContent = '₱' + avgCost.toFixed(2);
  }

  // ── Editor open/close ──────────────────────────────────────────────────────
  function openNewRecipeEditor() {
    document.getElementById('editorTitle').textContent = 'Add Recipe';
    document.getElementById('menuItemSelectorWrap').style.display = 'block';
    if (menuDropdown) menuDropdown.clear();
    resetForm();
    document.getElementById('recipesListCard').style.display = 'none';
    document.getElementById('recipeEditorCard').style.display = 'block';
  }

  function openEditRecipe(itemId) {
    document.getElementById('editorTitle').textContent = 'Edit Recipe';
    document.getElementById('menuItemSelectorWrap').style.display = 'none';
    document.getElementById('itemId').value = itemId;
    document.getElementById('recipesListCard').style.display = 'none';
    document.getElementById('recipeEditorCard').style.display = 'block';
    loadRecipe();
  }

  function closeRecipeEditor() {
    document.getElementById('recipeEditorCard').style.display = 'none';
    document.getElementById('recipesListCard').style.display = 'block';
    loadRecipesList();
  }

  // ── View recipe modal ───────────────────────────────────────────────────────
  async function viewRecipe(itemId) {
    try {
      const response = await fetch(`../api/recipes.php?action=by_item&item_id=${itemId}`);
      const result   = await response.json();

      if (result.success && result.data) {
        const recipe = result.data;
        document.getElementById('viewRecipeTitle').textContent = recipe.recipe_name || 'Recipe Details';

        let ingredientsHtml = '';
        if (recipe.ingredients && recipe.ingredients.length > 0) {
          ingredientsHtml = `
            <table style="width:100%; border-collapse: collapse; margin-top: 12px;">
              <thead>
                <tr style="background: var(--surface-2);">
                  <th style="padding: 8px 12px; text-align: left; font-size: 11px; text-transform: uppercase; color: var(--text-muted);">Ingredient</th>
                  <th style="padding: 8px 12px; text-align: left; font-size: 11px; text-transform: uppercase; color: var(--text-muted);">Qty</th>
                  <th style="padding: 8px 12px; text-align: left; font-size: 11px; text-transform: uppercase; color: var(--text-muted);">UOM</th>
                  <th style="padding: 8px 12px; text-align: right; font-size: 11px; text-transform: uppercase; color: var(--text-muted);">Cost</th>
                </tr>
              </thead>
              <tbody>
                ${recipe.ingredients.map(ing => `
                  <tr style="border-bottom: 1px solid var(--border);">
                    <td style="padding: 10px 12px;">${ing.ingredient_name || '—'}</td>
                    <td style="padding: 10px 12px;">${ing.quantity || '—'}</td>
                    <td style="padding: 10px 12px;">${ing.uom_code || '—'}</td>
                    <td style="padding: 10px 12px; text-align: right;">₱${(ing.quantity * ing.cost_per_unit).toFixed(2)}</td>
                  </tr>
                `).join('')}
              </tbody>
            </table>
            <div style="margin-top: 12px; text-align: right; font-weight: 600;">
              Total Cost: ₱${recipe.total_cost ? recipe.total_cost.toFixed(2) : '0.00'}
            </div>
          `;
        } else {
          ingredientsHtml = '<p style="color: var(--text-muted); margin-top: 12px;">No ingredients added.</p>';
        }

        document.getElementById('viewRecipeContent').innerHTML = `
          <div style="margin-bottom: 16px;">
            <div style="font-size: 11px; text-transform: uppercase; color: var(--text-muted); margin-bottom: 4px;">Menu Item</div>
            <div style="font-weight: 600;">${recipe.item_name || '—'}</div>
          </div>
          <div style="margin-bottom: 16px;">
            <div style="font-size: 11px; text-transform: uppercase; color: var(--text-muted); margin-bottom: 4px;">Prep / Cook Time</div>
            <div>${recipe.preparation_time ? recipe.preparation_time + 'm' : '—'} / ${recipe.cooking_time ? recipe.cooking_time + 'm' : '—'}</div>
          </div>
          <div style="margin-bottom: 16px;">
            <div style="font-size: 11px; text-transform: uppercase; color: var(--text-muted); margin-bottom: 4px;">Yield</div>
            <div>${recipe.yield_quantity ? parseFloat(recipe.yield_quantity).toLocaleString() + ' serving(s)' : '—'}</div>
          </div>
          <div style="margin-bottom: 16px;">
            <div style="font-size: 11px; text-transform: uppercase; color: var(--text-muted); margin-bottom: 4px;">Instructions</div>
            <div style="white-space: pre-wrap; color: var(--text-secondary);">${recipe.description || 'No instructions provided.'}</div>
          </div>
          <div style="margin-bottom: 16px;">
            <div style="font-size: 11px; text-transform: uppercase; color: var(--text-muted); margin-bottom: 4px;">Ingredients</div>
            ${ingredientsHtml}
          </div>
          <div style="margin-top: 16px; padding-top: 16px; border-top: 1px solid var(--border);">
            <div style="font-size: 11px; text-transform: uppercase; color: var(--text-muted); margin-bottom: 4px;">Cost per Serving</div>
            <div style="font-weight: 600; color: var(--brand);">₱${recipe.cost_per_serving ? recipe.cost_per_serving.toFixed(2) : '0.00'}</div>
          </div>
        `;

        document.getElementById('viewRecipeModal').classList.add('open');
      } else {
        showAlert('Error', result.message || 'Failed to load recipe.');
      }
    } catch (err) {
      console.error('viewRecipe error:', err);
      showAlert('Error', 'An error occurred while loading the recipe.');
    }
  }

  function closeViewRecipeModal() {
    document.getElementById('viewRecipeModal').classList.remove('open');
  }

  // ── Custom alert ───────────────────────────────────────────────────────────
  function showAlert(title, message, onConfirm) {
    document.getElementById('alertTitle').textContent   = title;
    document.getElementById('alertMessage').textContent = message;
    document.getElementById('alertModal').classList.add('open');

    const confirmBtn = document.getElementById('alertConfirmBtn');
    confirmBtn.onclick = () => {
      closeAlert();
      if (onConfirm) onConfirm();
    };
  }

  function closeAlert() {
    document.getElementById('alertModal').classList.remove('open');
  }

  // ── Fetch UOMs available for a specific product ────────────────────────────
  async function fetchProductUoms(productId) {
    if (!productId) return unitsOfMeasure;

    try {
      const response = await fetch(`../api/product_uom_conversions.php?action=list&product_id=${productId}`);
      const result   = await response.json();

      if (result.success && result.data) {
        const product    = inventoryProducts.find(p => p.product_id === productId);
        const baseUomId  = product ? product.uom_id : null;

        const uomIds = new Set();
        result.data.forEach(conv => {
          uomIds.add(conv.from_uom_id);
          uomIds.add(conv.to_uom_id);
        });
        if (baseUomId) uomIds.add(baseUomId);

        const filtered = unitsOfMeasure.filter(uom => uomIds.has(uom.uom_id));
        return filtered.length > 0 ? filtered : unitsOfMeasure;
      }
    } catch (err) {
      console.error('fetchProductUoms error:', err);
    }

    return unitsOfMeasure; // fallback
  }

  // ── Searchable Dropdown component ──────────────────────────────────────────
  function initSearchableDropdown(inputId, optionsContainerId, data, valueField, labelField, onSelect) {
    const input          = document.getElementById(inputId);
    const optionsContainer = document.getElementById(optionsContainerId);
    let selectedValue    = null;

    function renderOptions(filter = '') {
      const searchTerm = filter.toLowerCase();
      const filtered   = data.filter(item => labelField(item).toLowerCase().includes(searchTerm));

      if (filtered.length === 0) {
        optionsContainer.innerHTML = '<div class="searchable-dropdown-option no-results">No results found</div>';
      } else {
        optionsContainer.innerHTML = filtered.map(item => {
          const value    = valueField(item);
          const label    = labelField(item);
          const sel      = value === selectedValue ? 'selected' : '';
          return `<div class="searchable-dropdown-option ${sel}" data-value="${value}">${label}</div>`;
        }).join('');
      }

      optionsContainer.querySelectorAll('.searchable-dropdown-option:not(.no-results)').forEach(option => {
        option.addEventListener('click', () => {
          const value = option.dataset.value;
          const item  = data.find(d => valueField(d) === value);
          if (item) {
            selectedValue  = value;
            input.value    = labelField(item);
            optionsContainer.classList.remove('show');
            if (onSelect) onSelect(item);
          }
        });
      });
    }

    input.addEventListener('focus', () => {
      renderOptions(input.value);
      optionsContainer.classList.add('show');
    });

    input.addEventListener('input', () => {
      renderOptions(input.value);
      optionsContainer.classList.add('show');
    });

    document.addEventListener('click', e => {
      if (!input.contains(e.target) && !optionsContainer.contains(e.target)) {
        optionsContainer.classList.remove('show');
      }
    });

    return {
      setValue: (value) => {
        selectedValue = value;
        const item = data.find(d => valueField(d) === value);
        if (item) input.value = labelField(item);
      },
      getValue: () => selectedValue,
      clear: () => { selectedValue = null; input.value = ''; }
    };
  }

  // ── Menu item dropdown ─────────────────────────────────────────────────────
  let menuDropdown = null;

  function populateMenuItemSelect() {
    menuDropdown = initSearchableDropdown(
      'menuSearchInput',
      'menuDropdownOptions',
      menuItems,
      item => item.item_id,
      item => item.item_name,          // FIX: category_name not returned by ?action=related
      item => loadRecipeByItemId(item.item_id)
    );
  }

  function loadRecipeByItemId(itemId) {
    document.getElementById('itemId').value = itemId;
    loadRecipe();
  }

  // ── Load recipe for selected menu item ────────────────────────────────────
  async function loadRecipe() {
    const itemId = document.getElementById('itemId').value;

    if (!itemId) {
      return;
    }

    try {
      const response = await fetch(`../api/recipes.php?action=by_item&item_id=${itemId}`);
      const result   = await response.json();

      if (result.success && result.data) {
        currentRecipe = result.data;
        populateRecipeForm(result.data);
      } else {
        // No existing recipe – start a blank form
        currentRecipe = null;
        resetForm();
        const item = menuItems.find(i => i.item_id === itemId);
        document.getElementById('recipeName').value = item ? `${item.item_name} Recipe` : '';
      }
    } catch (err) {
      console.error('loadRecipe error:', err);
      resetForm();
    }
  }

  // ── Populate form from recipe data ─────────────────────────────────────────
  function populateRecipeForm(recipe) {
    document.getElementById('recipeId').value    = recipe.recipe_id        || '';
    document.getElementById('recipeName').value  = recipe.recipe_name      || '';
    document.getElementById('prepTime').value    = recipe.preparation_time || '';
    document.getElementById('cookTime').value    = recipe.cooking_time     || '';
    document.getElementById('portionSize').value = recipe.portion_size     || '';
    document.getElementById('yieldQty').value    = recipe.yield_quantity   || 1;
    document.getElementById('recipeDesc').value  = recipe.description      || '';

    document.getElementById('ingredientsList').innerHTML = '';
    ingredientRowCount = 0;

    if (recipe.ingredients && recipe.ingredients.length > 0) {
      recipe.ingredients.forEach(ing => addIngredientRow(ing));
    }

    updateRecipeStats();
  }

  // ── Ingredient rows ────────────────────────────────────────────────────────
  async function addIngredientRow(ingredient = null) {
    ingredientRowCount++;
    const rowId      = `ingredient-${ingredientRowCount}`;
    const inputId    = `product-input-${ingredientRowCount}`;
    const optionsId  = `product-options-${ingredientRowCount}`;

    const row = document.createElement('div');
    row.className = 'ingredient-row';
    row.id        = rowId;

    // Build UOM options
    const selectedProductId = ingredient ? ingredient.product_id : null;
    const availableUoms     = selectedProductId
      ? await fetchProductUoms(selectedProductId)
      : unitsOfMeasure;

    let uomOptions = '<option value="">UOM</option>';
    availableUoms.forEach(uom => {
      const selected = ingredient && ingredient.uom_id === uom.uom_id ? 'selected' : '';
      uomOptions += `<option value="${uom.uom_id}" ${selected}>${uom.uom_code}</option>`;
    });

    row.innerHTML = `
      <div class="searchable-dropdown" style="flex:1;">
        <input type="text" class="searchable-dropdown-input ingredient-product-input"
               id="${inputId}" placeholder="Select product..." autocomplete="off">
        <span class="searchable-dropdown-arrow"><i class="fas fa-chevron-down"></i></span>
        <div class="searchable-dropdown-options" id="${optionsId}"></div>
      </div>
      <input type="number" class="ingredient-qty" placeholder="Qty" min="0" step="0.001"
             value="${ingredient ? ingredient.quantity : ''}" onchange="updateRecipeStats()">
      <select class="ingredient-uom" onchange="updateRecipeStats()">
        ${uomOptions}
      </select>
      <button type="button" class="ingredient-remove" onclick="removeIngredientRow('${rowId}')">
        <i class="fa fa-times"></i>
      </button>
    `;

    document.getElementById('ingredientsList').appendChild(row);

    // Init searchable dropdown for this ingredient row
    const productDropdown = initSearchableDropdown(
      inputId,
      optionsId,
      inventoryProducts,
      item => item.product_id,
      item => item.product_name,
      item => {
        const r = document.getElementById(rowId);
        r.dataset.productId    = item.product_id;
        r.dataset.productUomId = item.uom_id;
        updateIngredientCost(rowId);
      }
    );

    // Pre-fill when editing an existing ingredient
    if (ingredient && ingredient.product_id) {
      const product = inventoryProducts.find(p => p.product_id === ingredient.product_id);
      if (product) {
        productDropdown.setValue(ingredient.product_id);
        row.dataset.productId    = ingredient.product_id;
        row.dataset.productUomId = product.uom_id;
      }
    }

    updateRecipeStats();
  }

  function removeIngredientRow(rowId) {
    const row = document.getElementById(rowId);
    if (row) { row.remove(); updateRecipeStats(); }
  }

  async function updateIngredientCost(rowId) {
    const row          = document.getElementById(rowId);
    const productId    = row.dataset.productId;
    const productUomId = row.dataset.productUomId;
    const uomSelect    = row.querySelector('.ingredient-uom');
    const currentUom   = uomSelect.value;

    const availableUoms = productId
      ? await fetchProductUoms(productId)
      : unitsOfMeasure;

    uomSelect.innerHTML = '<option value="">UOM</option>';
    availableUoms.forEach(uom => {
      const opt      = document.createElement('option');
      opt.value      = uom.uom_id;
      opt.textContent = uom.uom_code;
      if (uom.uom_id == currentUom) opt.selected = true;
      uomSelect.appendChild(opt);
    });

    // Default to product base UOM if nothing was previously selected
    if (!currentUom && productUomId) uomSelect.value = productUomId;

    updateRecipeStats();
  }

  function updateRecipeStats() {
    const rows = document.querySelectorAll('.ingredient-row');
    let totalCost = 0;

    rows.forEach(row => {
      const productId = row.dataset.productId;
      const qtyInput  = row.querySelector('.ingredient-qty');

      if (productId && qtyInput.value) {
        const product = inventoryProducts.find(p => p.product_id === productId);
        if (product && product.unit_cost) {
          totalCost += parseFloat(qtyInput.value) * parseFloat(product.unit_cost);
        }
      }
    });

    const yieldQty      = parseFloat(document.getElementById('yieldQty').value) || 1;
    const costPerServing = totalCost / yieldQty;

    document.getElementById('totalIngredients').textContent = rows.length;
    document.getElementById('totalCost').textContent        = '₱' + totalCost.toFixed(2);
    document.getElementById('costPerServing').textContent   = '₱' + costPerServing.toFixed(2);
  }

  // ── Reset form ─────────────────────────────────────────────────────────────
  function resetForm() {
    document.getElementById('recipeId').value    = '';
    document.getElementById('recipeName').value  = '';
    document.getElementById('prepTime').value    = '';
    document.getElementById('cookTime').value    = '';
    document.getElementById('portionSize').value = '';
    document.getElementById('yieldQty').value    = '1';
    document.getElementById('recipeDesc').value  = '';
    document.getElementById('ingredientsList').innerHTML = '';
    ingredientRowCount = 0;
    updateRecipeStats();
  }

  // ── Save recipe ────────────────────────────────────────────────────────────
  async function saveRecipe() {
    const recipeData = {
      recipe_id:        document.getElementById('recipeId').value,
      item_id:          document.getElementById('itemId').value,
      recipe_name:      document.getElementById('recipeName').value,
      preparation_time: document.getElementById('prepTime').value,
      cooking_time:     document.getElementById('cookTime').value,
      portion_size:     document.getElementById('portionSize').value,
      yield_quantity:   document.getElementById('yieldQty').value,
      description:      document.getElementById('recipeDesc').value,
      ingredients:      []
    };

    document.querySelectorAll('.ingredient-row').forEach(row => {
      const productId = row.dataset.productId;
      const qtyInput  = row.querySelector('.ingredient-qty');
      const uomSelect = row.querySelector('.ingredient-uom');

      if (productId && qtyInput.value) {
        const product = inventoryProducts.find(p => p.product_id === productId);
        recipeData.ingredients.push({
          product_id:      productId,
          quantity:        parseFloat(qtyInput.value),
          uom_id:          uomSelect.value,
          ingredient_name: product ? product.product_name : '',
          cost_per_unit:   product ? product.unit_cost    : 0
        });
      }
    });

    try {
      const action   = recipeData.recipe_id ? 'update' : 'create';
      const response = await fetch(`../api/recipes.php?action=${action}`, {
        method:  'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-Token': csrfToken
        },
        body: JSON.stringify(recipeData)
      });

      const result = await response.json();

      if (result.success) {
        if (result.recipe_id) {
          document.getElementById('recipeId').value = result.recipe_id;
        }
        showAlert('Success', result.message || 'Recipe saved successfully.', () => closeRecipeEditor());
      } else {
        showAlert('Error', result.message || 'Failed to save recipe.');
      }
    } catch (err) {
      console.error('saveRecipe error:', err);
      showAlert('Error', 'An error occurred while saving the recipe.');
    }
  }
</script>