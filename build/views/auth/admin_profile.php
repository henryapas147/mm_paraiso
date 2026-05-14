<?php

/**
 * @var string $message
 * @var string $messageType
 * @var array|null $user
 * @var array|null $profile
 * @var string $assets_path
 */
// session_start() is already called in config/db.php
// Database connection is handled by the controller
// Layout includes are handled by the Controller's view() method
// This view should only contain the page content

// Default variable declarations to fix static analysis warnings
$message = $message ?? '';
$messageType = $messageType ?? '';
$user = $user ?? null;
$profile = $profile ?? null;

// Get profile photo URL - will be fetched via AJAX
// Dynamic default profile image path based on environment
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'];
$basePath = dirname($_SERVER['SCRIPT_NAME']); // Gets /admin_booking/build or equivalent
$defaultProfilePath = $basePath . '/assets/images/default_profile.jpg';
$profilePhotoUrl = $defaultProfilePath; // Default, will be updated by AJAX
?>

<!-- PAGE CONTENT -->
<div class="relative flex flex-col flex-auto min-w-0 p-4 overflow-hidden break-words bg-white border-0 dark:bg-slate-850 dark:shadow-dark-xl shadow-3xl rounded-2xl bg-clip-border">
  <div class="flex flex-wrap -mx-3">
    <div class="flex-none w-auto max-w-full px-3">
      <div class="relative inline-flex items-center justify-center text-white transition-all duration-200 ease-in-out text-base h-19 w-19 rounded-xl">
        <img id="profilePhoto" src="<?php echo htmlspecialchars($profilePhotoUrl); ?>" alt="profile_image" class="w-full h-full object-cover shadow-2xl rounded-xl" />
      </div>
    </div>
    <div class="flex-none w-auto max-w-full px-3 my-auto">
      <div class="h-full">
        <h5 id="profileDisplayName" class="mb-1 dark:text-white"><?php echo htmlspecialchars($profile['display_name'] ?? ($profile ? ($profile['first_name'] . ' ' . $profile['last_name']) : 'User')); ?></h5>
        <p id="profileRoleLabel" class="mb-0 font-semibold leading-normal dark:text-white dark:opacity-60 text-sm"><?php echo htmlspecialchars($user['role_label'] ?? 'User'); ?></p>
      </div>
    </div>
  </div>
</div>

<div class="mt-6">
  <?php if ($message): ?>
    <style>
      .swal2-confirm {
        background-color: #1d4ed8 !important;
        color: white !important;
        border: none !important;
        padding: 8px 16px !important;
        font-size: 14px !important;
        border-radius: 6px !important;
      }
      .swal2-popup.swal2-modal {
        border-radius: 12px !important;
      }
    </style>
    <script>
      <?php if ($messageType === 'success'): ?>
        Swal.fire({ icon: 'success', title: 'Success', text: '<?php echo addslashes($message); ?>', confirmButtonText: 'OK', customClass: { confirmButton: 'swal2-confirm' }});
      <?php else: ?>
        Swal.fire({ icon: 'error', title: 'Error', text: '<?php echo addslashes($message); ?>', confirmButtonText: 'OK', customClass: { confirmButton: 'swal2-confirm' }});
        document.querySelector('.swal2-confirm').style.backgroundColor = '#dc2626';
      <?php endif; ?>
    </script>
  <?php endif; ?>

  <!-- Profile Edit Form -->
  <div class="bg-white border-0 shadow-xl dark:bg-slate-850 dark:shadow-dark-xl rounded-2xl">
    <div class="border-black/12.5 rounded-t-2xl border-b-0 border-solid p-6 pb-0">
      <div class="flex items-center">
        <p class="mb-0 dark:text-white/80">Edit Profile</p>
      </div>
    </div>
    <div class="flex-auto p-6">
          <form method="POST" action="<?php echo $base_url ?? './'; ?>profile">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>">
            <input type="hidden" name="update_profile" value="1">

            <p class="leading-normal uppercase dark:text-white dark:opacity-60 text-sm">User Information</p>
            <div class="flex flex-wrap -mx-3">
              <div class="w-full max-w-full px-3 shrink-0 md:w-6/12 md:flex-0">
                <div class="mb-4">
                  <label for="username" class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">Username</label>
                  <input type="text" id="username" name="username" value="<?php echo htmlspecialchars(explode('@', $user['email'])[0] ?? ''); ?>" class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" readonly />
                </div>
              </div>
              <div class="w-full max-w-full px-3 shrink-0 md:w-6/12 md:flex-0">
                <div class="mb-4">
                  <label for="email" class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">Email address</label>
                  <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" readonly />
                </div>
              </div>
              <div class="w-full max-w-full px-3 shrink-0 md:w-6/12 md:flex-0">
                <div class="mb-4">
                  <label for="first_name" class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">First name</label>
                  <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($profile['first_name'] ?? ''); ?>" class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" required />
                </div>
              </div>
              <div class="w-full max-w-full px-3 shrink-0 md:w-6/12 md:flex-0">
                <div class="mb-4">
                  <label for="last_name" class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">Last name</label>
                  <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($profile['last_name'] ?? ''); ?>" class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" required />
                </div>
              </div>
            </div>
            <hr class="h-px mx-0 my-4 bg-transparent border-0 opacity-25 bg-gradient-to-r from-transparent via-black/40 to-transparent dark:bg-gradient-to-r dark:from-transparent dark:via-white dark:to-transparent " />

            <p class="leading-normal uppercase dark:text-white dark:opacity-60 text-sm">Contact Information</p>
            <div class="flex flex-wrap -mx-3">
              <div class="w-full max-w-full px-3 shrink-0 md:w-6/12 md:flex-0">
                <div class="mb-4">
                  <label for="region_code_display" class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">Region</label>
                  <input type="text" id="region_code_display" list="region_list" value="<?php echo htmlspecialchars($profile['region_name'] ?? ''); ?>" data-code="<?php echo htmlspecialchars($profile['region_code'] ?? ''); ?>" class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" onchange="onRegionChange()" />
                  <input type="hidden" id="region_code" name="region_code" value="<?php echo htmlspecialchars($profile['region_code'] ?? ''); ?>" />
                  <datalist id="region_list"></datalist>
                </div>
              </div>
              <div class="w-full max-w-full px-3 shrink-0 md:w-6/12 md:flex-0">
                <div class="mb-4">
                  <label for="province_code_display" class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">Province</label>
                  <input type="text" id="province_code_display" list="province_list" value="<?php echo htmlspecialchars($profile['province_name'] ?? ''); ?>" data-code="<?php echo htmlspecialchars($profile['province_code'] ?? ''); ?>" class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" onchange="onProvinceChange()" />
                  <input type="hidden" id="province_code" name="province_code" value="<?php echo htmlspecialchars($profile['province_code'] ?? ''); ?>" />
                  <datalist id="province_list"></datalist>
                </div>
              </div>
              <div class="w-full max-w-full px-3 shrink-0 md:w-6/12 md:flex-0">
                <div class="mb-4">
                  <label for="city_municipality_code_display" class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">City/Municipality</label>
                  <input type="text" id="city_municipality_code_display" list="city_list" value="<?php echo htmlspecialchars($profile['city_municipality_name'] ?? ''); ?>" data-code="<?php echo htmlspecialchars($profile['city_municipality_code'] ?? ''); ?>" class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" onchange="onCityChange()" />
                  <input type="hidden" id="city_municipality_code" name="city_municipality_code" value="<?php echo htmlspecialchars($profile['city_municipality_code'] ?? ''); ?>" />
                  <datalist id="city_list"></datalist>
                </div>
              </div>
              <div class="w-full max-w-full px-3 shrink-0 md:w-6/12 md:flex-0">
                <div class="mb-4">
                  <label for="barangay_code_display" class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">Barangay</label>
                  <input type="text" id="barangay_code_display" list="barangay_list" value="<?php echo htmlspecialchars($profile['barangay_name'] ?? ''); ?>" data-code="<?php echo htmlspecialchars($profile['barangay_code'] ?? ''); ?>" class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" />
                  <input type="hidden" id="barangay_code" name="barangay_code" value="<?php echo htmlspecialchars($profile['barangay_code'] ?? ''); ?>" />
                  <datalist id="barangay_list"></datalist>
                </div>
              </div>
              <div class="w-full max-w-full px-3 shrink-0 md:w-6/12 md:flex-0">
                <div class="mb-4">
                  <label for="address_line1" class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">Address Line 1</label>
                  <input type="text" id="address_line1" name="address_line1" value="<?php echo htmlspecialchars($profile['address_line1'] ?? ''); ?>" placeholder="House/Unit number, Street name" class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" />
                </div>
              </div>
              <div class="w-full max-w-full px-3 shrink-0 md:w-6/12 md:flex-0">
                <div class="mb-4">
                  <label for="address_line2" class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">Address Line 2</label>
                  <input type="text" id="address_line2" name="address_line2" value="<?php echo htmlspecialchars($profile['address_line2'] ?? ''); ?>" placeholder="Apartment, Suite, Unit, Building, Floor, etc." class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" />
                </div>
              </div>
              <div class="w-full max-w-full px-3 shrink-0 md:w-6/12 md:flex-0">
                <div class="mb-4">
                  <label for="country" class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">Country</label>
                  <input type="text" id="country" name="country" value="<?php echo htmlspecialchars($profile['country'] ?? 'Philippines'); ?>" class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" />
                </div>
              </div>
              <div class="w-full max-w-full px-3 shrink-0 md:w-6/12 md:flex-0">
                <div class="mb-4">
                  <label for="postal_code" class="inline-block mb-2 ml-1 font-bold text-xs text-slate-700 dark:text-white/80">Postal Code</label>
                  <input type="text" id="postal_code" name="postal_code" value="<?php echo htmlspecialchars($profile['postal_code'] ?? ''); ?>" class="focus:shadow-primary-outline dark:bg-slate-850 dark:text-white text-sm leading-5.6 ease block w-full appearance-none rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding px-3 py-2 font-normal text-gray-700 outline-none transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none" />
                </div>
              </div>
            </div>

            <div class="mt-6">
              <button type="submit" class="bg-blue-500 text-white active:bg-blue-600 font-bold text-sm px-6 py-3 rounded shadow hover:shadow-lg outline-none focus:outline-none ease-linear transition-all duration-150">
                Save Changes
              </button>
            </div>
          </form>
    </div>
  </div>
</div>

<!-- Profile Photo Upload Script -->
<script>
  // Fetch profile data when page loads
  document.addEventListener('DOMContentLoaded', async () => {
    const regions = await fetchRegions();
    populateDatalist('region_list', regions, 'region_name');
    fetchProfileData();
  });

  function fetchProfileData() {
    fetch('api/profile.php?action=get_profile')
      .then(r => {
        if (!r.ok) {
          throw new Error(`HTTP error! status: ${r.status}`);
        }
        return r.json();
      })
      .then(data => {
        if (data.success) {
          // Update profile photo
          const profilePhotoElement = document.getElementById('profilePhoto');
          if (profilePhotoElement && data.data.profile_photo_url) {
            profilePhotoElement.src = normalizeProfilePhotoUrl(data.data.profile_photo_url);
          }

          // Update display name
          const firstName = data.data.first_name || '';
          const lastName = data.data.last_name || '';
          const displayName = data.data.display_name || (firstName && lastName ? firstName + ' ' + lastName : 'User');
          const roleLabel = data.data.role_label || 'User';

          const displayNameElement = document.getElementById('profileDisplayName');
          if (displayNameElement) {
            displayNameElement.textContent = displayName;
          }
          // Update role label
          const roleLabelElement = document.getElementById('profileRoleLabel');
          if (roleLabelElement) {
            roleLabelElement.textContent = roleLabel;
          }

          // Populate all form fields
          const fields = ['first_name', 'last_name', 'address_line1', 'address_line2', 'country', 'postal_code'];
          fields.forEach(field => {
            const element = document.getElementById(field);
            if (element && data.data[field] !== undefined) {
              element.value = data.data[field];
            }
          });

          // Populate geography fields (names in display input, codes in hidden input)
          // Also populate dependent datalists
          if (data.data.region_code) {
            const regionInput = document.getElementById('region_code_display');
            const regionHidden = document.getElementById('region_code');
            if (regionInput) {
              regionInput.value = data.data.region_name || '';
              regionInput.dataset.code = data.data.region_code || '';
            }
            if (regionHidden) {
              regionHidden.value = data.data.region_code || '';
            }

            // Populate provinces based on region
            fetchProvinces(data.data.region_code).then(provinces => {
              populateDatalist('province_list', provinces, 'province_name');
            });
          }
          if (data.data.province_code) {
            const provinceInput = document.getElementById('province_code_display');
            const provinceHidden = document.getElementById('province_code');
            if (provinceInput) {
              provinceInput.value = data.data.province_name || '';
              provinceInput.dataset.code = data.data.province_code || '';
            }
            if (provinceHidden) {
              provinceHidden.value = data.data.province_code || '';
            }

            // Populate cities based on province
            fetchCitiesMunicipalities(data.data.province_code).then(cities => {
              populateDatalist('city_list', cities, 'city_municipality_name');
            });
          }
          if (data.data.city_municipality_code) {
            const cityInput = document.getElementById('city_municipality_code_display');
            const cityHidden = document.getElementById('city_municipality_code');
            if (cityInput) {
              cityInput.value = data.data.city_municipality_name || '';
              cityInput.dataset.code = data.data.city_municipality_code || '';
            }
            if (cityHidden) {
              cityHidden.value = data.data.city_municipality_code || '';
            }

            // Populate barangays based on city
            fetchBarangays(data.data.city_municipality_code).then(barangays => {
              populateDatalist('barangay_list', barangays, 'barangay_name');
            });
          }
          if (data.data.barangay_code) {
            const barangayInput = document.getElementById('barangay_code_display');
            const barangayHidden = document.getElementById('barangay_code');
            if (barangayInput) {
              barangayInput.value = data.data.barangay_name || '';
              barangayInput.dataset.code = data.data.barangay_code || '';
            }
            if (barangayHidden) {
              barangayHidden.value = data.data.barangay_code || '';
            }
          }
        }
      })
      .catch(err => console.error('Error fetching profile data:', err));
  }

  // Change handlers for geography fields
  async function onRegionChange() {
    const regionInput = document.getElementById('region_code_display');
    const regionName = regionInput.value;
    const regions = await fetchRegions();
    const region = regions.find(r => r.region_name === regionName);
    if (region) {
      regionInput.dataset.code = region.region_code;
      const regionHidden = document.getElementById('region_code');
      if (regionHidden) regionHidden.value = region.region_code || '';
      const provinces = await fetchProvinces(region.region_code);
      populateDatalist('province_list', provinces, 'province_name');
    }
  }

  async function onProvinceChange() {
    const provinceInput = document.getElementById('province_code_display');
    const provinceName = provinceInput.value;
    const regionCode = document.getElementById('region_code').value;
    if (regionCode) {
      const provinces = await fetchProvinces(regionCode);
      const province = provinces.find(p => p.province_name === provinceName);
      if (province) {
        provinceInput.dataset.code = province.province_code;
        const provinceHidden = document.getElementById('province_code');
        if (provinceHidden) provinceHidden.value = province.province_code || '';
        const cities = await fetchCitiesMunicipalities(province.province_code);
        populateDatalist('city_list', cities, 'city_municipality_name');
      }
    }
  }

  async function onCityChange() {
    const cityInput = document.getElementById('city_municipality_code_display');
    const cityName = cityInput.value;
    const provinceCode = document.getElementById('province_code').value;
    if (provinceCode) {
      const cities = await fetchCitiesMunicipalities(provinceCode);
      const city = cities.find(c => c.city_municipality_name === cityName);
      if (city) {
        cityInput.dataset.code = city.city_municipality_code;
        const cityHidden = document.getElementById('city_municipality_code');
        if (cityHidden) cityHidden.value = city.city_municipality_code || '';
        const barangays = await fetchBarangays(city.city_municipality_code);
        populateDatalist('barangay_list', barangays, 'barangay_name');
      }
    }
  }

  // Handle barangay input to update hidden code
  document.addEventListener('input', function(e) {
    if (e.target.id === 'barangay_code_display') {
      const barangayInput = document.getElementById('barangay_code_display');
      const barangayDatalist = document.getElementById('barangay_list');
      const barangayHidden = document.getElementById('barangay_code');
      if (barangayHidden) {
        const options = Array.from(barangayDatalist.options);
        const match = options.find(opt => opt.value === barangayInput.value);
        if (match) {
          barangayHidden.value = match.dataset.code || '';
        }
      }
    }
  });

  // Helper function to populate datalist
  function populateDatalist(datalistId, data, textField) {
    const datalist = document.getElementById(datalistId);
    if (!datalist) return;
    datalist.innerHTML = '';
    data.forEach(item => {
      const option = document.createElement('option');
      option.value = item[textField];
      datalist.appendChild(option);
    });
  }

  // Normalize profile photo URL based on environment
  function normalizeProfilePhotoUrl(url) {
    if (!url || url.includes('default_profile')) {
      return '<?php echo $defaultProfilePath; ?>';
    }

    const isLocalhost = window.location.hostname === 'localhost' ||
      window.location.hostname === '127.0.0.1' ||
      window.location.hostname.match(/^192\.168\./) ||
      window.location.hostname.match(/^10\./);

    // Ensure path starts with /
    if (!url.startsWith('/')) {
      url = '/' + url;
    }

    if (isLocalhost) {
      // Localhost expects /admin_booking/uploads/profile_photos/
      if (url.startsWith('/admin_booking/uploads/profile_photos/')) {
        return url;
      }
      // Convert Hostinger path to localhost path
      if (url.startsWith('/uploads/profile_photos/')) {
        const filename = url.split('/').pop();
        return '/admin_booking/uploads/profile_photos/' + filename;
      }
      // Handle old /build/assets/images/ format
      if (url.startsWith('/build/assets/images/')) {
        return url;
      }
    }

    return url;
  }

  /* ─── GEOCODE DROPDOWNS ─────────────────────────────────────────────────────────── */
  let regionsData = [];

  async function fetchRegions() {
    if (regionsData.length > 0) return regionsData;
    try {
      const res = await fetch('api/geocode.php?action=regions');
      const json = await res.json();
      if (json.success) {
        regionsData = json.data;
        return regionsData;
      }
    } catch (e) {
      console.error('fetchRegions:', e);
    }
    return [];
  }

  async function fetchProvinces(regionCode) {
    try {
      const res = await fetch(`api/geocode.php?action=provinces&region_code=${encodeURIComponent(regionCode)}`);
      const json = await res.json();
      if (json.success) return json.data;
    } catch (e) {
      console.error('fetchProvinces:', e);
    }
    return [];
  }

  async function fetchCitiesMunicipalities(provinceCode) {
    try {
      const res = await fetch(`api/geocode.php?action=cities_municipalities&province_code=${encodeURIComponent(provinceCode)}`);
      const json = await res.json();
      if (json.success) return json.data;
    } catch (e) {
      console.error('fetchCitiesMunicipalities:', e);
    }
    return [];
  }

  async function fetchBarangays(cityMunicipalityCode) {
    try {
      const res = await fetch(`api/geocode.php?action=barangays&city_municipality_code=${encodeURIComponent(cityMunicipalityCode)}`);
      const json = await res.json();
      if (json.success) return json.data;
    } catch (e) {
      console.error('fetchBarangays:', e);
    }
    return [];
  }

  // Initialize region dropdown
</script>
