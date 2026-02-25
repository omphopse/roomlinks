<?php 
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../controllers/UserController.php';
checkUserAuth();
$listingData = null;
$isEdit = false;
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $userController = new UserController();
    $listingData = $userController->getListingById((int)$_GET['id']);
    $isEdit = $listingData ? true : false;
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="styles/tailwind.css" />
    <link rel="stylesheet" href="styles/style.css" />
    <link rel="stylesheet" href="styles/hide-scrollbar.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css" />

    <title>Add A Room Requirment | RoomLinks | Offical Website</title>

    <link rel="icon" type="image/x-icon" href="assets/logo/favicon.ico" />

</head>

<body class="flex flex-col">

    <?php include 'partials/header.php' ?>

    <!-- Loading Popup Animation -->
    <div id="loadingPopup" class="fixed inset-0 z-[9999] flex items-center justify-center bg-black bg-opacity-40 hidden">
        <div class="bg-white rounded-2xl shadow-2xl p-8 flex flex-col items-center gap-4 min-w-[300px] max-w-xs">
            <img src="assets/banner/success.png" alt="Loading" class="w-32 h-32 object-contain mb-2 animate-bounce" />
            <div class="flex items-center gap-2">
                <span class="loader inline-block w-6 h-6 border-4 border-blue-400 border-t-transparent rounded-full animate-spin"></span>
                <span class="text-lg font-semibold text-gray-700">Uploading images...</span>
            </div>
            <span class="text-sm text-gray-500">Please wait while your images are being uploaded.</span>
        </div>
    </div>

    <!-- Success Popup Animation -->
    <div id="successPopup" class="fixed inset-0 z-[9999] flex items-center justify-center bg-black bg-opacity-40 hidden">
        <div class="bg-white rounded-2xl shadow-2xl p-8 flex flex-col items-center gap-4 min-w-[300px] max-w-xs">
            <span class="inline-block w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mb-2">
                <i class="fas fa-check text-green-600 text-4xl"></i>
            </span>
            <span class="text-lg font-semibold text-gray-700">Room uploaded successfully!</span>
            <span class="text-sm text-gray-500">Your room listing has been submitted.</span>
        </div>
    </div>

    <style>
        /* Success popup animation */
        #successPopup .fa-check {
            animation: pop 0.6s cubic-bezier(.17,.67,.83,.67) 1;
        }
        @keyframes pop {
            0% { transform: scale(0.5); opacity: 0; }
            60% { transform: scale(1.2); opacity: 1; }
            100% { transform: scale(1); opacity: 1; }
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        .animate-spin {
            animation: spin 1s linear infinite;
        }
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-15px); }
        }
        .animate-bounce {
            animation: bounce 1.2s infinite;
        }
    </style>

    <!-- Add Room Requirment Start -->
    <section class="py-16 md:py-20 flex justify-center items-center">
        <div class="w-full max-w-4xl mx-auto bg-white p-4 sm:p-8 md:p-10 lg:p-12 xl:p-14
            sm:mx-4 md:mx-8 lg:mx-0">
            <h2 class="text-2xl font-semibold text-center mb-1">Add your requirement</h2>
            <p class="text-center text-gray-500 mb-6 text-sm">so that other users can contact you.</p>
            <form class="space-y-6" id="requirementForm" method="POST" action="<?php echo $isEdit ? (APP_URL . '/update-roommatelisting') : (APP_URL . '/add-roommatelisting'); ?>">
                <?php if ($isEdit): ?>
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($listingData['id']); ?>">
                <?php endif; ?>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold mb-1">Select City*</label>
                        <select name=\"city\" id=\"city_select\"
                            class=\"w-full border border-gray-300 rounded-lg px-4 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-primary-400\"
                            required>
                            <option value=\"\">-- Select City --</option>
                            <?php 
                                $userController = new UserController();
                                $cities = $userController->getLocations();
                                $selectedCityId = null;
                                
                                // If editing, get the city_id from the location
                                if ($isEdit && !empty($listingData['location'])) {
                                    require_once __DIR__ . '/../models/Location.php';
                                    require_once __DIR__ . '/../config/database.php';
                                    $pdo = getPDO();
                                    $locationModel = new Location($pdo);
                                    $selectedLocation = $locationModel->getLocationById(intval($listingData['location']));
                                    if ($selectedLocation) {
                                        $selectedCityId = $selectedLocation['city_id'];
                                    }
                                }
                                
                                foreach ($cities as $city) {
                                    $isSelected = $selectedCityId && $city['id'] == $selectedCityId ? ' selected' : '';
                                    echo '<option value=\"' . htmlspecialchars($city['id']) . '\"' . $isSelected . '>' . htmlspecialchars($city['location'] ?? $city['name']) . '</option>';
                                }
                            ?>
                        </select>
                    </div>
                    <div>
                        <label class=\"block text-sm font-bold mb-1\">Select Location*</label>
                        <select name=\"location\" id=\"location_select\"
                            class=\"w-full border border-gray-300 rounded-lg px-4 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-primary-400\"
                            required>
                            <option value=\"\">-- Select Location --</option>
                            <?php 
                                if ($isEdit && !empty($listingData['location'])) {
                                    require_once __DIR__ . '/../models/Location.php';
                                    require_once __DIR__ . '/../config/database.php';
                                    $pdo = getPDO();
                                    $locationModel = new Location($pdo);
                                    $selectedLocation = $locationModel->getLocationById(intval($listingData['location']));
                                    if ($selectedLocation) {
                                        echo '<option value=\"' . htmlspecialchars($selectedLocation['id']) . '\" selected>' . htmlspecialchars($selectedLocation['name']) . '</option>';
                                    }
                                }
                            ?>
                            <option value=\"other\">Other (Enter manually)</option>
                        </select>
                        <input type=\"text\" name=\"custom_location\" id=\"custom_location\" placeholder=\"Enter location name\" 
                            class=\"mt-2 border border-gray-300 rounded-lg px-4 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-primary-400 w-full hidden\" 
                            value=\"<?php echo $isEdit && is_string($listingData['location']) && !is_numeric($listingData['location']) ? htmlspecialchars($listingData['location']) : ''; ?>\" />
                    </div>
                </div>
                <script>
                    function loadLocationsByCity(cityId, selectedLocationId = null) {
                        const locationSelect = document.getElementById('location_select');
                        const customLocation = document.getElementById('custom_location');
                        locationSelect.innerHTML = '<option value=\"\">-- Select Location --</option>';
                        
                        if (!cityId) return;
                        
                        fetch('<?php echo APP_URL; ?>/api/get-locations?city_id=' + cityId)
                            .then(response => response.json())
                            .then(data => {
                                data.forEach(location => {
                                    const option = document.createElement('option');
                                    option.value = location.id;
                                    option.textContent = location.name;
                                    if (selectedLocationId && location.id == selectedLocationId) {
                                        option.selected = true;
                                    }
                                    locationSelect.appendChild(option);
                                });
                                // Always add 'Other' option at the end
                                const otherOption = document.createElement('option');
                                otherOption.value = 'other';
                                otherOption.textContent = 'Other (Enter manually)';
                                locationSelect.appendChild(otherOption);
                            })
                            .catch(error => console.error('Error loading locations:', error));
                    }

                    // Handle location dropdown change
                    document.addEventListener('DOMContentLoaded', function() {
                        const locationSelect = document.getElementById('location_select');
                        const customLocation = document.getElementById('custom_location');
                        
                        if (locationSelect) {
                            locationSelect.addEventListener('change', function() {
                                // Only show/hide custom input, don't reload locations
                                if (this.value === 'other') {
                                    customLocation.classList.remove('hidden');
                                    customLocation.required = true;
                                    customLocation.focus();
                                } else {
                                    customLocation.classList.add('hidden');
                                    customLocation.required = false;
                                    customLocation.value = '';
                                }
                            });
                        }
                        
                        document.getElementById('city_select').addEventListener('change', function() {
                            loadLocationsByCity(this.value);
                        });
                    });

                    // On page load, if city is selected, load locations
                    window.addEventListener('load', function() {
                        const citySelect = document.getElementById('city_select');
                        const locationSelect = document.getElementById('location_select');
                        const customLocation = document.getElementById('custom_location');
                        if (citySelect.value) {
                            const selectedLocationId = locationSelect.value || null;
                            loadLocationsByCity(citySelect.value, selectedLocationId);
                            
                            // If custom location has value, mark 'other' as selected
                            if (customLocation.value) {
                                locationSelect.value = 'other';
                                customLocation.classList.remove('hidden');
                            }
                        }
                    });
                </script>
                    <div>
                        <label class="block text-sm font-bold mb-1">Approx Rent*</label>
                        <div class="relative">
                            <span class="absolute left-3 top-2.5 text-gray-400">₹</span>
                            <input type="number" placeholder="5000"
                                name="rent"
                                class="w-full border border-gray-300 rounded-lg pl-8 pr-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-400"
                                required value="<?php echo $isEdit ? htmlspecialchars($listingData['rent']) : ''; ?>">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-bold mb-1">Looking For</label>
                        <div class="select-group option-group">
                            <button type="button" class="select-btn" data-group="looking_for"
                                data-value="male">Male</button>
                            <button type="button" class="select-btn" data-group="looking_for"
                                data-value="female">Female</button>
                            <button type="button" class="select-btn" data-group="looking_for"
                                data-value="any">Any</button>
                        </div>
                        <input type="hidden" name="looking_for" id="looking_for_input" value="<?php echo $isEdit ? htmlspecialchars($listingData['gender']) : 'any'; ?>">
                    </div>
                    <div>
                        <label class="block text-sm font-bold mb-1">Occupancy</label>
                        <div class="select-group option-group">
                            <button type="button" class="select-btn" data-group="occupancy"
                                data-value="single">Single</button>
                            <button type="button" class="select-btn" data-group="occupancy"
                                data-value="shared">Shared</button>
                            <button type="button" class="select-btn" data-group="occupancy"
                                data-value="any">Any</button>
                        </div>
                        <input type="hidden" name="occupancy" id="occupancy_input" value="<?php echo $isEdit ? htmlspecialchars($listingData['occupancy']) : 'single'; ?>">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-bold mb-1">Choose Highlights for your property</label>
                    <div class="flex flex-wrap gap-3 mt-2 option-group">
                        <?php
                        require_once __DIR__ . '/../config/database.php';
                        $conn = isset($conn) ? $conn : (function() {
                            $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
                            if ($conn->connect_error) die('Database connection failed');
                            return $conn;
                        })();
                        $result = $conn->query("SELECT id, name FROM property_highlights ORDER BY name ASC");
                        if ($result && $result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                        ?>
                        <label class="cursor-pointer">
                            <input type="checkbox" name="highlights[]" value="<?php echo (int)$row['id']; ?>" class="hidden peer"
                                <?php if ($isEdit && in_array($row['id'], $listingData['highlights'])) echo 'checked'; ?>>
                            <span class="highlight-btn option-btn peer-checked:bg-[--primary-color] peer-checked:text-white peer-checked:border-[--primary-color]">
                                <?php echo htmlspecialchars($row['name']); ?>
                            </span>
                        </label>
                        <?php
                            }
                        }
                        ?>
                    </div>
                </div>
                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label class="block text-sm font-bold mb-1">Preferred Contact Method</label>
                        <div class="select-group option-group">
                            <button type="button" class="select-btn" data-group="contact_method" data-value="call">Call</button>
                            <button type="button" class="select-btn" data-group="contact_method" data-value="whatsapp">WhatsApp</button>
                            <button type="button" class="select-btn" data-group="contact_method" data-value="email">Email</button>
                        </div>
                        <p class="text-xs text-gray-500 mt-2">Note: The contact methods you select will be shared with
                            other users so they can reach out to you using your preferred options.</p>
                        <input type="hidden" name="contact_method" id="contact_method_input" value="<?php echo $isEdit ? htmlspecialchars($listingData['contact']) : 'call'; ?>">
                        <p id="contact-method-error" class="text-xs text-red-500 mt-2 hidden">Please select at least 2 contact methods.</p>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-bold mb-1">Description*</label>
                    <textarea id="description" rows="10" maxlength="3000" name="description"
                        placeholder="I am looking for a room with roommate."
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-400"
                        required><?php echo $isEdit ? htmlspecialchars($listingData['description']) : ''; ?></textarea>
                    <div class="flex justify-between items-center mt-1">
                        <span id="desc-word-count" class="text-xs text-gray-500">0/300 words</span>
                        <span id="desc-word-warning" class="text-xs text-red-500 hidden">Word limit reached!</span>
                    </div>
                    <p id="desc-minword-error" class="text-xs text-red-500 mt-2 hidden">Please enter at least 20 words in the description.</p>
                </div>
                <div class="flex justify-center">
                    <button type="submit"
                        class="bg-[--primary-color] text-white font-semibold px-10 py-2 rounded-full text-lg">Submit
                        &gt;</button>
                </div>
                <div class="text-center mt-2 text-sm text-gray-500">
                    Have room & need roommate? <a href="#" class="text-[--primary-color] font-semibold underline">Add
                        Room
                        &gt;</a>
                </div>
            </form>
        </div>
    </section>
    <!-- Add Room Requirment End  -->

    <div class="mb-8 md:mb-12"></div>
    <?php include 'partials/footer.php' ?>

    <script>
        // --- Begin: Exact JS from roommate.php for popups ---
        document.addEventListener('DOMContentLoaded', function () {
            // Button group logic (single and multi-select)
            function setupButtonGroup(groupName, defaultValue, multiSelect = false) {
                const btns = document.querySelectorAll('.select-btn[data-group="' + groupName + '"]');
                const input = document.getElementById(groupName + "_input");
                if (multiSelect) {
                    let defaults = [];
                    if (typeof defaultValue === 'string') {
                        defaults = defaultValue.split(/[,|]/).map(v => v.trim()).filter(Boolean);
                    } else if (Array.isArray(defaultValue)) {
                        defaults = defaultValue;
                    }
                    btns.forEach(btn => {
                        btn.addEventListener('click', function () {
                            this.classList.toggle('selected');
                            const selected = Array.from(btns).filter(b => b.classList.contains('selected')).map(b => b.dataset.value);
                            input.value = selected.join(',');
                        });
                        if (defaults.includes(btn.dataset.value)) {
                            btn.classList.add('selected');
                        }
                    });
                    const selected = Array.from(btns).filter(b => b.classList.contains('selected')).map(b => b.dataset.value);
                    input.value = selected.join(',');
                } else {
                    btns.forEach(btn => {
                        btn.addEventListener('click', function () {
                            btns.forEach(b => b.classList.remove('selected'));
                            this.classList.add('selected');
                            input.value = this.dataset.value;
                        });
                    });
                    let defaultBtn = Array.from(btns).find(b => b.dataset.value === defaultValue);
                    if (defaultBtn) {
                        defaultBtn.classList.add('selected');
                        input.value = defaultValue;
                    }
                }
            }
            var lookingForVal = '<?php echo $isEdit ? htmlspecialchars($listingData['gender']) : 'any'; ?>';
            var occupancyVal = '<?php echo $isEdit ? htmlspecialchars($listingData['occupancy']) : 'single'; ?>';
            var contactMethodVal = '<?php echo $isEdit ? htmlspecialchars($listingData['contact']) : 'call'; ?>';
            contactMethodVal = contactMethodVal.replace(/\s*\|\s*/g, ',');
            setupButtonGroup('looking_for', lookingForVal);
            setupButtonGroup('occupancy', occupancyVal);
            setupButtonGroup('contact_method', contactMethodVal, true);

            // Description word limit logic
            const desc = document.getElementById('description');
            const wordCount = document.getElementById('desc-word-count');
            const wordWarning = document.getElementById('desc-word-warning');
            const descMinWordError = document.getElementById('desc-minword-error');
            desc.addEventListener('input', function () {
                let words = desc.value.match(/\b\w+\b/g) || [];
                if (words.length > 300) {
                    desc.value = words.slice(0, 300).join(' ');
                    words = desc.value.match(/\b\w+\b/g) || [];
                    wordWarning.classList.remove('hidden');
                } else {
                    wordWarning.classList.add('hidden');
                }
                wordCount.textContent = words.length + '/300 words';
                if (words.length >= 20) {
                    descMinWordError.classList.add('hidden');
                }
            });

            // Form validation and popup logic
            const form = document.getElementById('requirementForm');
            const contactMethodError = document.getElementById('contact-method-error');
            const loadingPopup = document.getElementById('loadingPopup');
            const successPopup = document.getElementById('successPopup');
            form.addEventListener('submit', function (e) {
                const selectedBtns = document.querySelectorAll('.select-btn[data-group="contact_method"].selected');
                let valid = true;
                if (selectedBtns.length < 2) {
                    contactMethodError.classList.remove('hidden');
                    valid = false;
                } else {
                    contactMethodError.classList.add('hidden');
                }
                let words = desc.value.match(/\b\w+\b/g) || [];
                if (words.length < 20) {
                    descMinWordError.classList.remove('hidden');
                    valid = false;
                } else {
                    descMinWordError.classList.add('hidden');
                }
                if (!valid) {
                    e.preventDefault();
                    return;
                }
                // Show loading popup
                e.preventDefault();
                loadingPopup.classList.remove('hidden');
                setTimeout(() => {
                    loadingPopup.classList.add('hidden');
                    successPopup.classList.remove('hidden');
                    setTimeout(() => {
                        successPopup.classList.add('hidden');
                        form.submit();
                    }, 2000);
                }, 2000);
            });
        });
        // --- End: Exact JS from roommate.php for popups ---
    </script>
</body>

</html>