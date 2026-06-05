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
    top: 24px;
    right: 24px;
    transform: translateY(-80px);
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
    opacity: 0;
    transition: all .25s;
    min-width: 260px
  }

  .toast.show {
    transform: translateY(0);
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

  /* ── Responsive ──────────────────────────────────── */
  @media (max-width: 480px) {
    .page {
      padding: 16px;
    }
  }

  @media (max-width: 640px) {
    .card-header {
      padding: 16px 18px;
    }
  }

  @media (max-width: 640px) {
    .card-title {
      font-size: 16px;
    }
  }

  @media (max-width: 640px) {
    .card-subtitle {
      font-size: 12px;
    }
  }

  @media (max-width: 640px) {
    .card-body {
      padding: 16px 18px;
    }
  }

  @media (max-width: 640px) {
    .section-title {
      font-size: 13px;
      margin-bottom: 12px;
      padding-bottom: 10px;
    }
  }

  @media (max-width: 640px) {
    .form-grid {
      grid-template-columns: 1fr;
      gap: 12px;
      margin-bottom: 20px;
    }
  }

  @media (max-width: 640px) {
    label {
      font-size: 11px;
    }
  }

  @media (max-width: 640px) {
    input,
    select,
    textarea {
      font-size: 12px;
      padding: 7px 10px;
    }
  }

  @media (max-width: 640px) {
    textarea {
      min-height: 70px;
    }
  }

  @media (max-width: 640px) {
    .form-actions {
      flex-direction: column;
      gap: 10px;
      padding-top: 14px;
    }
  }

  @media (max-width: 480px) {
    .btn {
      width: 100%;
      justify-content: center;
    }
  }

  @media (max-width: 640px) {
    .toast {
      left: 16px;
      right: 16px;
      bottom: 16px;
      font-size: 12px;
      min-width: auto;
    }
  }

  /* Custom Modal */
  .modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 10000;
    opacity: 0;
    visibility: hidden;
    transition: all 0.2s;
  }

  .modal-overlay.show {
    opacity: 1;
    visibility: visible;
  }

  .modal {
    background: #fff;
    border-radius: 12px;
    padding: 24px;
    min-width: 320px;
    max-width: 400px;
    box-shadow: 0 12px 32px rgba(0, 0, 0, 0.16);
    transform: scale(0.95);
    transition: transform 0.2s;
  }

  .modal-overlay.show .modal {
    transform: scale(1);
  }

  .modal-title {
    font-size: 16px;
    font-weight: 500;
    color: #333;
    margin-bottom: 8px;
  }

  .modal-message {
    font-size: 13px;
    color: #666;
    margin-bottom: 20px;
    line-height: 1.5;
  }

  .modal-actions {
    display: flex;
    justify-content: flex-end;
    gap: 8px;
  }

  .modal-btn {
    padding: 8px 16px;
    font-size: 13px;
    font-weight: 500;
    border-radius: 8px;
    border: 0.5px solid #d1d5db;
    background: #fff;
    color: #333;
    cursor: pointer;
    transition: background 0.15s;
  }

  .modal-btn:hover {
    background: #f9fafb;
  }

  .modal-btn-primary {
    background: #185FA5;
    color: #fff;
    border-color: #185FA5;
  }

  .modal-btn-primary:hover {
    background: #0C447C;
    border-color: #0C447C;
  }

  .modal-btn-danger {
    background: #A32D2D;
    color: #fff;
    border-color: #A32D2D;
  }

  .modal-btn-danger:hover {
    background: #7A2020;
    border-color: #7A2020;
  }
</style>

<!-- Toast -->
<div id="companyToast" class="toast"></div>

<!-- Custom Modal -->
<div id="customModal" class="modal-overlay">
  <div class="modal">
    <div class="modal-title" id="modalTitle">Confirm</div>
    <div class="modal-message" id="modalMessage">Are you sure?</div>
    <div class="modal-actions">
      <button class="modal-btn" id="modalCancel">Cancel</button>
      <button class="modal-btn modal-primary" id="modalConfirm">Confirm</button>
    </div>
  </div>
</div>

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
    document.getElementById('video_title').value = companyData.video_title || '';
    document.getElementById('video_description').value = companyData.video_description || '';
    document.getElementById('video_source_preference').value = companyData.video_source_preference || 'youtube_priority';
    
    // Show current video preview
    const previewDiv = document.getElementById('currentVideoPreview');
    if (companyData.video_file) {
      previewDiv.innerHTML = `
        <video controls style="max-width: 100%; max-height: 200px; border-radius: 8px;">
          <source src="../${companyData.video_file}" type="video/mp4">
          Your browser does not support the video tag.
        </video>
        <br><small><a href="#" onclick="removeVideoFile(); return false;" style="color: #dc3545;">Remove video</a></small>
      `;
    } else if (companyData.youtube_url) {
      previewDiv.innerHTML = `<small>YouTube video: ${companyData.youtube_url}</small>`;
    } else {
      previewDiv.innerHTML = '<small>No video set</small>';
    }

    // Load geocode data
    loadGeocodeData(companyData);
  }

  function showToast(msg, isError) {
    const t = document.getElementById('companyToast');
    t.textContent = msg;
    t.className = 'toast ' + (isError ? 'error' : 'success') + ' show';
    // Keep toast visible - no auto-hide
  }

  /* ─── CUSTOM MODAL FUNCTION ─────────────────────────────────────────────── */
  function showConfirmModal(title, message, onConfirm, onCancel) {
    const modal = document.getElementById('customModal');
    const modalTitle = document.getElementById('modalTitle');
    const modalMessage = document.getElementById('modalMessage');
    const modalConfirm = document.getElementById('modalConfirm');
    const modalCancel = document.getElementById('modalCancel');

    modalTitle.textContent = title;
    modalMessage.textContent = message;
    modal.classList.add('show');

    const cleanup = () => {
      modal.classList.remove('show');
      modalConfirm.removeEventListener('click', onConfirmClick);
      modalCancel.removeEventListener('click', onCancelClick);
    };

    const onConfirmClick = () => {
      cleanup();
      if (onConfirm) onConfirm();
    };

    const onCancelClick = () => {
      cleanup();
      if (onCancel) onCancel();
    };

    modalConfirm.addEventListener('click', onConfirmClick);
    modalCancel.addEventListener('click', onCancelClick);
  }

  function removeVideoFile() {
    showConfirmModal(
      'Remove Video',
      'Are you sure you want to remove the uploaded video?',
      () => {
        document.getElementById('video_file').value = '';
        document.getElementById('remove_video_flag').value = '1';
        document.getElementById('currentVideoPreview').innerHTML = '<small>Video will be removed on save</small>';
      }
    );
  }

  async function saveCompany() {
    const formData = new FormData();
    const companyId = document.getElementById('company_id').value;
    
    // Always send company_id and user_id
    formData.append('company_id', companyId);
    formData.append('user_id', '<?php echo $_SESSION["user_id"] ?? ""; ?>');

    // Helper function to add field if changed
    const addIfChanged = (fieldId, originalValue) => {
      const element = document.getElementById(fieldId);
      if (!element) return;
      const currentValue = element.value;
      if (currentValue !== (originalValue ?? '')) {
        formData.append(fieldId, currentValue);
      }
    };

    // Compare with original data and only send changed fields
    if (companyData) {
      addIfChanged('company_name', companyData.company_name);
      addIfChanged('legal_name', companyData.legal_name);
      addIfChanged('business_reg_number', companyData.business_reg_number);
      addIfChanged('tax_id', companyData.tax_id);
      addIfChanged('address_line1', companyData.address_line1);
      addIfChanged('address_line2', companyData.address_line2);
      addIfChanged('city', companyData.city);
      addIfChanged('province', companyData.province);
      addIfChanged('postal_code', companyData.postal_code);
      addIfChanged('country', companyData.country);
      addIfChanged('phone_number', companyData.phone_number);
      addIfChanged('phone_number_2', companyData.phone_number_2);
      addIfChanged('email', companyData.email);
      addIfChanged('email_2', companyData.email_2);
      addIfChanged('website', companyData.website);
      addIfChanged('google_maps_embed_url', companyData.google_maps_embed_url);
      addIfChanged('description', companyData.description);
      addIfChanged('facebook_url', companyData.facebook_url);
      addIfChanged('instagram_url', companyData.instagram_url);
      addIfChanged('twitter_url', companyData.twitter_url);
      addIfChanged('linkedin_url', companyData.linkedin_url);
      addIfChanged('youtube_url', companyData.youtube_url);
      addIfChanged('video_title', companyData.video_title);
      addIfChanged('video_description', companyData.video_description);
      addIfChanged('video_source_preference', companyData.video_source_preference);
      addIfChanged('region_code', companyData.region_code);
      addIfChanged('province_code', companyData.province_code);
      addIfChanged('city_municipality_code', companyData.city_municipality_code);
      addIfChanged('barangay_code', companyData.barangay_code);
      addIfChanged('barangay', companyData.barangay);
    } else {
      // For new company, send all fields
      formData.append('company_name', document.getElementById('company_name').value);
      formData.append('legal_name', document.getElementById('legal_name').value);
      formData.append('business_reg_number', document.getElementById('business_reg_number').value);
      formData.append('tax_id', document.getElementById('tax_id').value);
      formData.append('address_line1', document.getElementById('address_line1').value);
      formData.append('address_line2', document.getElementById('address_line2').value);
      formData.append('city', document.getElementById('city_input').value);
      formData.append('province', document.getElementById('province_input').value);
      formData.append('postal_code', document.getElementById('postal_code').value);
      formData.append('country', document.getElementById('country').value);
      formData.append('phone_number', document.getElementById('phone_number').value);
      formData.append('phone_number_2', document.getElementById('phone_number_2').value);
      formData.append('email', document.getElementById('email').value);
      formData.append('email_2', document.getElementById('email_2').value);
      formData.append('website', document.getElementById('website').value);
      formData.append('google_maps_embed_url', document.getElementById('google_maps_embed_url').value);
      formData.append('description', document.getElementById('description').value);
      formData.append('facebook_url', document.getElementById('facebook_url').value);
      formData.append('instagram_url', document.getElementById('instagram_url').value);
      formData.append('twitter_url', document.getElementById('twitter_url').value);
      formData.append('linkedin_url', document.getElementById('linkedin_url').value);
      formData.append('youtube_url', document.getElementById('youtube_url').value);
      formData.append('video_title', document.getElementById('video_title').value);
      formData.append('video_description', document.getElementById('video_description').value);
      formData.append('video_source_preference', document.getElementById('video_source_preference').value);
      formData.append('region_code', document.getElementById('region_code').value);
      formData.append('province_code', document.getElementById('province_code').value);
      formData.append('city_municipality_code', document.getElementById('city_municipality_code').value);
      formData.append('barangay_code', document.getElementById('barangay_code').value);
      formData.append('barangay', document.getElementById('barangay_input').value);
    }

    try {
      const videoFileInput = document.getElementById('video_file');
      const hasVideo = videoFileInput.files.length > 0;

      if (hasVideo) {
        const file = videoFileInput.files[0];
        if (file.size > 100 * 1024 * 1024) {
          showToast('Video file is too large. Maximum size is 100MB.', true);
          return;
        }
        // Remove the video from formData — we'll upload it separately via chunks
        formData.delete('video_file');

        try {
          showToast('Uploading video...', false);
          const uploadedPath = await uploadFileInChunks(file);
          formData.append('uploaded_video_path', uploadedPath);
        } catch (e) {
          showToast('Video upload failed: ' + e.message, true);
          return;
        }
      }

      // Now send the main form (no large binary payload)
      const action = formData.get('company_id') ? 'update' : 'create';
      const csrf = '<?php echo htmlspecialchars($_SESSION["csrf_token"] ?? "", ENT_QUOTES, "UTF-8"); ?>';

      const res = await fetch(`../api/company_information.php?action=${action}`, {
        method: 'POST',
        headers: { 'X-CSRF-Token': csrf },
        body: formData
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

  async function uploadFileInChunks(file) {
    const CHUNK_SIZE = 2 * 1024 * 1024; // 2MB per chunk
    const totalChunks = Math.ceil(file.size / CHUNK_SIZE);
    const uploadId = Date.now() + '_' + Math.random().toString(36).slice(2);
    const csrf = '<?php echo htmlspecialchars($_SESSION["csrf_token"] ?? "", ENT_QUOTES, "UTF-8"); ?>';

    for (let i = 0; i < totalChunks; i++) {
      const chunk = file.slice(i * CHUNK_SIZE, (i + 1) * CHUNK_SIZE);
      const fd = new FormData();
      fd.append('chunk', chunk);
      fd.append('chunk_index', i);
      fd.append('total_chunks', totalChunks);
      fd.append('upload_id', uploadId);
      fd.append('filename', file.name);
      fd.append('csrf_token', csrf);

      const percent = Math.round(((i + 1) / totalChunks) * 100);
      showToast(`Uploading video: ${percent}%`, false);

      const res = await fetch('../api/upload_chunk.php', {
        method: 'POST',
        headers: { 'X-CSRF-Token': csrf },
        body: fd
      });

      if (!res.ok) throw new Error(`Chunk ${i + 1} failed`);
      const json = await res.json();
      if (!json.success) throw new Error(json.message || `Chunk ${i + 1} error`);

      if (json.done) return json.path; // final chunk returns assembled path
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
            <input type="url" id="youtube_url" name="youtube_url" placeholder="https://www.youtube.com/watch?v=...">
          </div>
          <div class="form-group">
            <label>Video Section Title</label>
            <input type="text" id="video_title" name="video_title" placeholder="Get Closer View & Different Feeling">
          </div>
          <div class="form-group">
            <label>Video Section Description</label>
            <textarea id="video_description" name="video_description" rows="2" placeholder="Optional description for the video section"></textarea>
          </div>
          <div class="form-group">
            <label>Video Source Preference</label>
            <select id="video_source_preference" name="video_source_preference">
              <option value="youtube_priority">YouTube (if available, otherwise file)</option>
              <option value="file_priority">Uploaded File (if available, otherwise YouTube)</option>
              <option value="youtube">YouTube Only</option>
              <option value="file">Uploaded File Only</option>
            </select>
            <small style="color: #666; font-size: 12px;">Choose which video source to display on the public page</small>
          </div>
          <div class="form-group">
            <label>Or Upload Video File</label>
            <input type="file" id="video_file" name="video_file" accept="video/mp4,video/webm,video/ogg">
            <input type="hidden" id="remove_video_flag" name="remove_video_flag" value="0">
            <small style="color: #666; font-size: 12px;">MP4, WebM, or OGG format. Max 100MB.</small>
          </div>
          <div class="form-group">
            <label>Current Video</label>
            <div id="currentVideoPreview" style="margin-top: 8px;"></div>
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
