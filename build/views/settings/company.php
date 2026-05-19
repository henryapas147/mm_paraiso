<style>
  * {
    box-sizing: border-box;
    margin: 0;
    padding: 0
  }

  .page {
    padding: 24px;
    max-width: 100%;
    margin: 0 auto
  }

  .card {
    background: #fff;
    border: 0.5px solid #e5e7eb;
    border-radius: 12px
  }

  .card-header {
    padding: 20px;
    border-bottom: 0.5px solid #e5e7eb
  }

  .card-title {
    font-size: 18px;
    font-weight: 500;
    color: #333;
    margin-bottom: 4px
  }

  .card-subtitle {
    font-size: 13px;
    color: #666
  }

  .card-body {
    padding: 20px
  }

  .section-title {
    font-size: 14px;
    font-weight: 500;
    color: #333;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 16px;
    padding-bottom: 12px;
    border-bottom: 0.5px solid #e5e7eb
  }

  .form-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 16px;
    margin-bottom: 24px
  }

  .form-group {
    display: flex;
    flex-direction: column;
    gap: 6px
  }

  label {
    font-size: 12px;
    font-weight: 500;
    color: #666;
    text-transform: uppercase;
    letter-spacing: 0.05em
  }

  label .req {
    color: #A32D2D;
    margin-left: 2px
  }

  input,
  select,
  textarea {
    font-family: system-ui, sans-serif;
    font-size: 13px;
    color: #333;
    background: #fff;
    border: 0.5px solid #d1d5db;
    border-radius: 8px;
    padding: 8px 12px;
    outline: none;
    width: 100%;
    transition: border .15s
  }

  input:focus,
  select:focus,
  textarea:focus {
    border-color: #185FA5;
    background: #fff
  }

  input[readonly] {
    background: #f9fafb;
    color: #666
  }

  textarea {
    resize: vertical;
    min-height: 80px
  }

  .form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    padding-top: 16px;
    border-top: 0.5px solid #e5e7eb
  }

  .btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 16px;
    font-size: 13px;
    font-weight: 500;
    border-radius: 8px;
    border: 0.5px solid #d1d5db;
    background: #fff;
    color: #333;
    cursor: pointer;
    transition: background .15s
  }

  .btn:hover {
    background: #f9fafb
  }

  .btn-primary {
    background: #185FA5;
    color: #fff;
    border-color: #185FA5
  }

  .btn-primary:hover {
    background: #0C447C;
    border-color: #0C447C
  }

  .toast {
    position: fixed;
    bottom: 24px;
    right: 24px;
    background: #fff;
    border: 0.5px solid #d1d5db;
    border-radius: 8px;
    padding: 12px 16px;
    font-size: 13px;
    display: flex;
    align-items: center;
    gap: 8px;
    z-index: 9999;
    box-shadow: 0 8px 24px rgba(0, 0, 0, .12);
    transform: translateY(80px);
    opacity: 0;
    transition: all .25s;
    min-width: 260px
  }

  .toast.show {
    transform: none;
    opacity: 1
  }

  .toast.success {
    border-color: #9FE1CB;
    color: #085041
  }

  .toast.error {
    border-color: #F7C1C1;
    color: #791F1F
  }

  .toast-close {
    background: none;
    border: none;
    cursor: pointer;
    color: #666;
    font-size: 16px;
    line-height: 1;
    padding: 4px;
    margin-left: auto
  }

  .toast-close:hover {
    color: #333
  }
</style>

<!-- Toast -->
<div id="companyToast" class="toast"></div>

<script>
  let companyData = null;
  let regionsData = [];

  /* ─── GEOCODE FUNCTIONS ───────────────────────────────────────────────── */

  async function fetchRegions() {
    if (regionsData.length > 0) return regionsData;
    try {
      const res = await fetch('../api/geocode.php?action=regions');
      const json = await res.json();
      if (json.success) {
        regionsData = json.data;
        populateDatalist('region_list', regionsData, 'region_name', 'region_code');
        return regionsData;
      }
    } catch (e) {
      console.error('fetchRegions:', e);
    }
    return [];
  }

  function populateDatalist(listId, items, labelKey, codeKey) {
    const datalist = document.getElementById(listId);
    if (!datalist) return;
    datalist.innerHTML = items.map(item =>
      `<option value="${item[labelKey]}" data-code="${item[codeKey]}">`
    ).join('');
  }

  function resolveCode(inputEl, datalistEl, labelKey, codeKey) {
    if (!inputEl || !datalistEl) return null;
    const options = Array.from(datalistEl.options);
    const match = options.find(opt => opt.value === inputEl.value);
    if (match) {
      return match.dataset.code;
    }
    return null;
  }

  async function onRegionChange() {
    const regionInput = document.getElementById('region_input');
    const regionDatalist = document.getElementById('region_list');
    const regionCode = resolveCode(regionInput, regionDatalist, 'region_name', 'region_code');

    document.getElementById('region_code').value = regionCode || '';
    document.getElementById('province_input').value = '';
    document.getElementById('province_code').value = '';
    document.getElementById('city_input').value = '';
    document.getElementById('city_municipality_code').value = '';
    document.getElementById('barangay_input').value = '';
    document.getElementById('barangay_code').value = '';

    if (!regionCode) return;

    try {
      const res = await fetch(`../api/geocode.php?action=provinces&region_code=${regionCode}`);
      const json = await res.json();
      if (json.success) {
        populateDatalist('province_list', json.data, 'province_name', 'province_code');
      }
    } catch (e) {
      console.error('onRegionChange:', e);
    }
  }

  async function onProvinceChange() {
    const provinceInput = document.getElementById('province_input');
    const provinceDatalist = document.getElementById('province_list');
    const provinceCode = resolveCode(provinceInput, provinceDatalist, 'province_name', 'province_code');

    document.getElementById('province_code').value = provinceCode || '';
    document.getElementById('city_input').value = '';
    document.getElementById('city_municipality_code').value = '';
    document.getElementById('barangay_input').value = '';
    document.getElementById('barangay_code').value = '';

    if (!provinceCode) return;

    try {
      const res = await fetch(`../api/geocode.php?action=cities_municipalities&province_code=${provinceCode}`);
      const json = await res.json();
      if (json.success) {
        populateDatalist('city_list', json.data, 'city_municipality_name', 'city_municipality_code');
      }
    } catch (e) {
      console.error('onProvinceChange:', e);
    }
  }

  async function onCityChange() {
    const cityInput = document.getElementById('city_input');
    const cityDatalist = document.getElementById('city_list');
    const cityCode = resolveCode(cityInput, cityDatalist, 'city_municipality_name', 'city_municipality_code');

    document.getElementById('city_municipality_code').value = cityCode || '';
    document.getElementById('barangay_input').value = '';
    document.getElementById('barangay_code').value = '';

    if (!cityCode) return;

    try {
      const res = await fetch(`../api/geocode.php?action=barangays&city_municipality_code=${cityCode}`);
      const json = await res.json();
      if (json.success) {
        populateDatalist('barangay_list', json.data, 'barangay_name', 'barangay_code');
      }
    } catch (e) {
      console.error('onCityChange:', e);
    }
  }

  function onBarangayInput() {
    const barangayInput = document.getElementById('barangay_input');
    const barangayDatalist = document.getElementById('barangay_list');
    const barangayCode = resolveCode(barangayInput, barangayDatalist, 'barangay_name', 'barangay_code');
    document.getElementById('barangay_code').value = barangayCode || '';
  }

  async function loadGeocodeData(company) {
    if (!company) return;

    // Load regions first
    await fetchRegions();

    // Set region input if code exists
    if (company.region_code) {
      const region = regionsData.find(r => r.region_code === company.region_code);
      if (region) {
        document.getElementById('region_input').value = region.region_name;
        document.getElementById('region_code').value = region.region_code;

        // Load provinces for this region
        try {
          const res = await fetch(`../api/geocode.php?action=provinces&region_code=${company.region_code}`);
          const json = await res.json();
          if (json.success) {
            populateDatalist('province_list', json.data, 'province_name', 'province_code');

            // Set province input
            if (company.province_code) {
              const province = json.data.find(p => p.province_code === company.province_code);
              if (province) {
                document.getElementById('province_input').value = province.province_name;
                document.getElementById('province_code').value = province.province_code;

                // Load cities
                const cityRes = await fetch(`../api/geocode.php?action=cities_municipalities&province_code=${company.province_code}`);
                const cityJson = await cityRes.json();
                if (cityJson.success) {
                  populateDatalist('city_list', cityJson.data, 'city_municipality_name', 'city_municipality_code');

                  // Set city input
                  if (company.city_municipality_code) {
                    const city = cityJson.data.find(c => c.city_municipality_code === company.city_municipality_code);
                    if (city) {
                      document.getElementById('city_input').value = city.city_municipality_name;
                      document.getElementById('city_municipality_code').value = city.city_municipality_code;

                      // Load barangays
                      const brgyRes = await fetch(`../api/geocode.php?action=barangays&city_municipality_code=${company.city_municipality_code}`);
                      const brgyJson = await brgyRes.json();
                      if (brgyJson.success) {
                        populateDatalist('barangay_list', brgyJson.data, 'barangay_name', 'barangay_code');

                        // Set barangay input
                        if (company.barangay_code) {
                          const brgy = brgyJson.data.find(b => b.barangay_code === company.barangay_code);
                          if (brgy) {
                            document.getElementById('barangay_input').value = brgy.barangay_name;
                            document.getElementById('barangay_code').value = brgy.barangay_code;
                          }
                        }
                      }
                    }
                  }
                }
              }
            }
          }
        } catch (e) {
          console.error('loadGeocodeData:', e);
        }
      }
    }
  }

  async function fetchCompany() {
    try {
      const res = await fetch('../api/company_information.php?action=list');
      const json = await res.json();
      if (json.success) {
        companyData = json.data;
        populateForm();
      }
    } catch (e) {
      console.error('Error fetching company:', e);
    }
  }

  function populateForm() {
    if (!companyData) return;
    document.getElementById('company_id').value = companyData.company_id || '';
    document.getElementById('company_name').value = companyData.company_name || '';
    document.getElementById('legal_name').value = companyData.legal_name || '';
    document.getElementById('business_reg_number').value = companyData.business_reg_number || '';
    document.getElementById('tax_id').value = companyData.tax_id || '';
    document.getElementById('address_line1').value = companyData.address_line1 || '';
    document.getElementById('address_line2').value = companyData.address_line2 || '';
    document.getElementById('postal_code').value = companyData.postal_code || '';
    document.getElementById('country').value = companyData.country || 'Philippines';
    document.getElementById('phone_number').value = companyData.phone_number || '';
    document.getElementById('phone_number_2').value = companyData.phone_number_2 || '';
    document.getElementById('email').value = companyData.email || '';
    document.getElementById('email_2').value = companyData.email_2 || '';
    document.getElementById('website').value = companyData.website || '';
    document.getElementById('google_maps_embed_url').value = companyData.google_maps_embed_url || '';
    document.getElementById('description').value = companyData.description || '';
    document.getElementById('facebook_url').value = companyData.facebook_url || '';
    document.getElementById('instagram_url').value = companyData.instagram_url || '';
    document.getElementById('twitter_url').value = companyData.twitter_url || '';
    document.getElementById('linkedin_url').value = companyData.linkedin_url || '';
    document.getElementById('youtube_url').value = companyData.youtube_url || '';

    // Load geocode data
    loadGeocodeData(companyData);
  }

  function showToast(msg, isError) {
    const t = document.getElementById('companyToast');
    t.textContent = msg;
    t.className = 'toast ' + (isError ? 'error' : 'success') + ' show';
    setTimeout(() => { t.classList.remove('show'); }, 3500);
  }

  async function saveCompany() {
    const data = {
      company_id: document.getElementById('company_id').value,
      company_name: document.getElementById('company_name').value,
      legal_name: document.getElementById('legal_name').value,
      business_reg_number: document.getElementById('business_reg_number').value,
      tax_id: document.getElementById('tax_id').value,
      address_line1: document.getElementById('address_line1').value,
      address_line2: document.getElementById('address_line2').value,
      city: document.getElementById('city_input').value,
      province: document.getElementById('province_input').value,
      postal_code: document.getElementById('postal_code').value,
      country: document.getElementById('country').value,
      phone_number: document.getElementById('phone_number').value,
      phone_number_2: document.getElementById('phone_number_2').value,
      email: document.getElementById('email').value,
      email_2: document.getElementById('email_2').value,
      website: document.getElementById('website').value,
      google_maps_embed_url: document.getElementById('google_maps_embed_url').value,
      description: document.getElementById('description').value,
      facebook_url: document.getElementById('facebook_url').value,
      instagram_url: document.getElementById('instagram_url').value,
      twitter_url: document.getElementById('twitter_url').value,
      linkedin_url: document.getElementById('linkedin_url').value,
      youtube_url: document.getElementById('youtube_url').value,
      region_code: document.getElementById('region_code').value,
      province_code: document.getElementById('province_code').value,
      city_municipality_code: document.getElementById('city_municipality_code').value,
      barangay_code: document.getElementById('barangay_code').value,
      barangay: document.getElementById('barangay_input').value,
      user_id: '<?php echo $_SESSION["user_id"] ?? ""; ?>'
    };

    try {
      const action = data.company_id ? 'update' : 'create';
      const res = await fetch(`../api/company_information.php?action=${action}`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-Token': '<?php echo htmlspecialchars($_SESSION["csrf_token"] ?? "", ENT_QUOTES, "UTF-8"); ?>'
        },
        body: JSON.stringify(data)
      });
      const json = await res.json();
      if (json.success) {
        showToast('Company information saved successfully!');
        fetchCompany();
      } else {
        showToast('Error: ' + json.message, true);
      }
    } catch (e) {
      console.error('Error saving company:', e);
      showToast('Failed to save company information', true);
    }
  }

  document.addEventListener('DOMContentLoaded', fetchCompany);
</script>

<div class="page">
  <div class="card">
    <div class="card-header">
      <div class="card-title">Company Settings</div>
      <div class="card-subtitle">Manage your company profile and contact information</div>
    </div>
    <div class="card-body">
      <form id="companyForm" onsubmit="event.preventDefault(); saveCompany();">
        <input type="hidden" id="company_id" name="company_id">

        <!-- Basic Information -->
        <div class="section-title">Basic Information</div>
        <div class="form-grid">
          <div class="form-group">
            <label>Company Name <span class="req">*</span></label>
            <input type="text" id="company_name" name="company_name" required>
          </div>
          <div class="form-group">
            <label>Legal Name</label>
            <input type="text" id="legal_name" name="legal_name">
          </div>
          <div class="form-group">
            <label>Business Reg. Number</label>
            <input type="text" id="business_reg_number" name="business_reg_number">
          </div>
          <div class="form-group">
            <label>Tax ID</label>
            <input type="text" id="tax_id" name="tax_id">
          </div>
        </div>

        <!-- Address with Geocode -->
        <div class="section-title">Address</div>
        <!-- Hidden geocode fields -->
        <input type="hidden" id="region_code" name="region_code">
        <input type="hidden" id="province_code" name="province_code">
        <input type="hidden" id="city_municipality_code" name="city_municipality_code">
        <input type="hidden" id="barangay_code" name="barangay_code">

        <div class="form-grid">
          <div class="form-group">
            <label>Street Address <span class="req">*</span></label>
            <input type="text" id="address_line1" name="address_line1" required>
          </div>
          <div class="form-group">
            <label>Address Line 2</label>
            <input type="text" id="address_line2" name="address_line2">
          </div>
          <div class="form-group">
            <label>Region</label>
            <input type="text" id="region_input" list="region_list" placeholder="Search region..." oninput="onRegionChange()">
            <datalist id="region_list"></datalist>
          </div>
          <div class="form-group">
            <label>Province</label>
            <input type="text" id="province_input" list="province_list" placeholder="Search province..." oninput="onProvinceChange()">
            <datalist id="province_list"></datalist>
          </div>
          <div class="form-group">
            <label>City / Municipality</label>
            <input type="text" id="city_input" list="city_list" placeholder="Search city..." oninput="onCityChange()">
            <datalist id="city_list"></datalist>
          </div>
          <div class="form-group">
            <label>Barangay</label>
            <input type="text" id="barangay_input" list="barangay_list" placeholder="Search barangay..." oninput="onBarangayInput()">
            <datalist id="barangay_list"></datalist>
          </div>
          <div class="form-group">
            <label>Postal Code</label>
            <input type="text" id="postal_code" name="postal_code">
          </div>
          <div class="form-group">
            <label>Country</label>
            <input type="text" id="country" name="country" value="Philippines" readonly>
          </div>
        </div>

        <!-- Contact -->
        <div class="section-title">Contact Information</div>
        <div class="form-grid">
          <div class="form-group">
            <label>Primary Phone <span class="req">*</span></label>
            <input type="text" id="phone_number" name="phone_number" required>
          </div>
          <div class="form-group">
            <label>Secondary Phone</label>
            <input type="text" id="phone_number_2" name="phone_number_2">
          </div>
          <div class="form-group">
            <label>Primary Email <span class="req">*</span></label>
            <input type="email" id="email" name="email" required>
          </div>
          <div class="form-group">
            <label>Secondary Email</label>
            <input type="email" id="email_2" name="email_2">
          </div>
          <div class="form-group">
            <label>Website</label>
            <input type="url" id="website" name="website">
          </div>
          <div class="form-group">
            <label>Google Maps Embed URL</label>
            <input type="url" id="google_maps_embed_url" name="google_maps_embed_url" placeholder="https://www.google.com/maps/embed?pb=...">
          </div>
        </div>

        <!-- Social Media -->
        <div class="section-title">Social Media</div>
        <div class="form-grid">
          <div class="form-group">
            <label>Facebook URL</label>
            <input type="url" id="facebook_url" name="facebook_url">
          </div>
          <div class="form-group">
            <label>Instagram URL</label>
            <input type="url" id="instagram_url" name="instagram_url">
          </div>
          <div class="form-group">
            <label>Twitter URL</label>
            <input type="url" id="twitter_url" name="twitter_url">
          </div>
          <div class="form-group">
            <label>LinkedIn URL</label>
            <input type="url" id="linkedin_url" name="linkedin_url">
          </div>
          <div class="form-group">
            <label>YouTube URL</label>
            <input type="url" id="youtube_url" name="youtube_url">
          </div>
        </div>

        <!-- Description -->
        <div class="section-title">Description</div>
        <div class="form-grid" style="grid-template-columns: 1fr;">
          <div class="form-group">
            <label>Company Description</label>
            <textarea id="description" name="description" rows="4"></textarea>
          </div>
        </div>

        <!-- Actions -->
        <div class="form-actions">
          <button type="button" class="btn" onclick="fetchCompany();">Reset</button>
          <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Changes</button>
        </div>
      </form>
    </div>
  </div>
</div>
