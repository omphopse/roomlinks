<?php
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../controllers/UserController.php';
checkUserAuth();


$userController = new UserController();
$amenities = method_exists($userController, 'getAmenities') ? $userController->getAmenities() : [];
$propertyHighlights = method_exists($userController, 'getPropertyHighlights') ? $userController->getPropertyHighlights() : [];


$listingData = null;
$isEdit = false;
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $listingData = $userController->getListingById((int)$_GET['id']);
    $isEdit = $listingData ? true : false;
    if ($isEdit && $listingData) {
        // Parse highlights and amenities from DB string to array of IDs for checkbox pre-selection
        $listingData['highlights'] = (isset($listingData['propertyhighlights']) && is_string($listingData['propertyhighlights']))
            ? array_map('trim', explode(' | ', $listingData['propertyhighlights'])) : (is_array($listingData['propertyhighlights']) ? $listingData['propertyhighlights'] : []);
        $listingData['amenities'] = (isset($listingData['amenities']) && is_string($listingData['amenities']))
            ? array_map('trim', explode(' | ', $listingData['amenities'])) : (is_array($listingData['amenities']) ? $listingData['amenities'] : []);
        // Contact is handled as string for JS, but can also be array if needed
        $listingData['contact'] = isset($listingData['contact']) ? $listingData['contact'] : '';

        // Collect images from image1 to image5 fields for JS preview
        $images = [];
        for ($i = 1; $i <= 5; $i++) {
            $imgField = 'image' . $i;
            if (!empty($listingData[$imgField])) {
                $images[] = $listingData[$imgField];
            }
        }
        $listingData['images'] = $images;
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'] ?? null;
    $user_email = $_SESSION['email'] ?? null;
    if ($isEdit) {
        $result = $userController->updateRoomListing($_POST, $_FILES, $user_id, $user_email, $amenities, $propertyHighlights, $_POST['id'] ?? null);
    } else {
        $result = $userController->addRoomListing($_POST, $_FILES, $user_id, $user_email, $amenities, $propertyHighlights);
    }
    // The controller/model will handle redirect and session messages
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

    <title>RoomLinks | Offical Website</title>

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

    <!-- Add Your Room Start -->
    <section class="py-16 md:py-20 flex justify-center items-center">
        <div class="w-full max-w-4xl mx-auto bg-white  p-4 sm:p-8 md:p-10 lg:p-12 xl:p-14 sm:mx-4 md:mx-8 lg:mx-0">
            <h2 class="text-3xl font-semibold text-center mb-1">Add your room details</h2>
            <p class="text-center text-gray-500 mb-6 text-base">so that other users can contact you.</p>
            <form class="space-y-8" id="addRoomForm" method="POST" enctype="multipart/form-data" action="<?php echo $isEdit ? (APP_URL . '/update-roomlisting') : (APP_URL . '/add-roomlisting'); ?>">
                <?php if ($isEdit && !empty($listingData['images'])): ?>
                    <input type="hidden" name="existing_images" id="existing_images_input" value="<?php echo htmlspecialchars(is_array($listingData['images']) ? implode(',', $listingData['images']) : $listingData['images']); ?>">
                    <input type="hidden" name="remove_images" id="remove_images_input" value="">
                <?php endif; ?>
                <?php if ($isEdit): ?>
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($listingData['id']); ?>">
                <?php endif; ?>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold mb-1">Add Your Location*</label>
                        <input type="text" name="location" placeholder="Add Location..."
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-primary-400"
                            required value="<?php echo $isEdit ? htmlspecialchars($listingData['location']) : ''; ?>">
                    </div>
                    <div>
                        <label class="block text-sm font-bold mb-1">Approx Rent*</label>
                        <div class="relative">
                            <span class="absolute left-3 top-2.5 text-gray-400">₹</span>
                            <input type="number" name="rent" placeholder="5000"
                                class="w-full border border-gray-300 rounded-lg pl-8 pr-4 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-primary-400"
                                required value="<?php echo $isEdit ? htmlspecialchars($listingData['rent']) : ''; ?>">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-bold mb-1">Looking For</label>
                        <div class="select-group option-group flex gap-2">
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
                        <div class="select-group option-group flex gap-2">
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
                <div class="mt-8">
                    <label class="block text-sm font-bold mb-1">Upload 5 Photos of your room</label>
                    <div class="border-2 border-dashed border-gray-300 rounded-xl bg-gray-50 flex flex-col items-center justify-center py-10 px-4 text-center transition relative mb-8"
                        id="dropArea">
                        <input id="room_photos" type="file" name="room_photos[]" accept="image/*" multiple
                            class="hidden">
                        <button type="button" id="customUploadBtn"
                            class="mb-3 bg-blue-600 text-white font-semibold px-6 py-2 rounded-full flex items-center gap-2 shadow-md transition focus:outline-none focus:ring-2 focus:ring-blue-400"><i
                                class="fas fa-cloud-upload-alt"></i> <span>Choose Images</span></button>
                        <span class="block text-gray-500 mb-1">or Drag Images To Upload</span>
                        <span class="block text-xs text-gray-400">(JPG, PNG, JPEG)</span>
                        <span class="block text-xs text-gray-400 mt-1">You can upload images upto <b>20 MB</b></span>
                        <div id="imagePreviewRow" class="flex gap-3 justify-center w-full mt-4 overflow-x-auto whitespace-nowrap hide-scrollbar"></div>
                        <span id="image-upload-error" class="text-xs text-red-500 hidden block mt-2"></span>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-bold mb-1">Choose Highlights for your property</label>
                    <?php
                        $propertyHighlights = method_exists($userController, 'getPropertyHighlights') ? $userController->getPropertyHighlights() : [];
                    ?>
                    <div class="flex flex-wrap gap-3 mt-2 option-group">
                        <?php foreach ($propertyHighlights as $highlight): ?>
                            <label class="cursor-pointer">
                                <input type="checkbox" name="highlights[]" value="<?php echo htmlspecialchars($highlight['id']); ?>" class="hidden peer" <?php if ($isEdit && in_array($highlight['id'], $listingData['highlights'])) echo 'checked'; ?>>
                                <span class="highlight-btn option-btn peer-checked:bg-[--primary-color] peer-checked:text-white peer-checked:border-[--primary-color]">
                                    <?php echo htmlspecialchars($highlight['name']); ?>
                                </span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="mt-4 mb-8">
                    <label class="block text-sm font-bold mb-3">Amenities</label>
                    <div id="amenitiesGridWrapper">
                        <div id="amenitiesGrid"
                            class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 gap-8 mb-2 justify-items-center transition-all duration-300"
                            style="max-height: 8.5rem; overflow: hidden;">
                            <?php

                            foreach ($amenities as $amenity):
                                ?>
                                <label class="flex flex-col items-center cursor-pointer group">
                                    <input type="checkbox" name="amenities[]"
                                        value="<?php echo htmlspecialchars($amenity['id']); ?>" class="hidden peer" <?php if ($isEdit && in_array($amenity['id'], $listingData['amenities'])) echo 'checked'; ?> />
                                    <span
                                        class="w-24 h-24 rounded-full bg-orange-50 flex items-center justify-center mb-2 border-2 border-transparent shadow-lg peer-checked:border-blue-500 transition">
                                        <img src="<?php echo htmlspecialchars($amenity['images']); ?>"
                                            alt="<?php echo htmlspecialchars($amenity['name']); ?>"
                                            class="w-14 h-14 object-contain" />
                                    </span>
                                    <span
                                        class="text-sm text-gray-700 group-hover:text-blue-600 block w-24 truncate text-center"><?php echo htmlspecialchars($amenity['name']); ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                        <div id="seeMoreAmenitiesContainer" class="justify-center mt-0 hidden">
                            <button type="button" id="seeMoreAmenitiesBtn"
                                class="text-blue-600 font-semibold underline text-base">See more</button>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label class="block text-sm font-bold mb-1">Preferred Contact Method</label>
                        <div class="select-group option-group">
                            <button type="button" class="select-btn" data-group="contact_method"
                                data-value="call">Call</button>
                            <button type="button" class="select-btn" data-group="contact_method"
                                data-value="whatsapp">WhatsApp</button>
                            <button type="button" class="select-btn" data-group="contact_method"
                                data-value="email">Email</button>

                        </div>
                        <span id="contact-method-error" class="text-xs text-red-500 hidden block mt-1"></span>
                        <p class="text-xs text-gray-500 mt-2">Note: The contact methods you select will be shared with
                            other users so they can reach out to you using your preferred options.</p>
                        <input type="hidden" name="contact_method" id="contact_method_input" value="<?php echo $isEdit ? str_replace(' | ', ',', htmlspecialchars($listingData['contact'])) : 'call'; ?>">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-bold mb-1">Description*</label>
                    <textarea id="description" name="description" rows="10" maxlength="3000"
                        placeholder="I am looking for a roommate for my room."
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-primary-400"
                        required><?php echo $isEdit ? htmlspecialchars($listingData['description']) : ''; ?></textarea>
                    <div class="flex justify-between items-center mt-1">
                        <span id="desc-word-count" class="text-xs text-gray-500">0/300 words</span>
                        <span id="desc-word-warning" class="text-xs text-red-500 hidden">Word limit reached!</span>
                    </div>
                    <span id="desc-word-error" class="text-xs text-red-500 hidden block mt-1"></span>
                </div>
                <div class="flex justify-center">
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-10 py-3 rounded-full text-lg flex items-center gap-2 shadow-md transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-400 active:scale-95">
                        <span>Submit</span>
                        <i class="fas fa-angle-right"></i>
                    </button>
                </div>
                <?php if (empty($profile['who_are_you']) || strtolower($profile['who_are_you']) === 'roommate'): ?>
                <div class="text-center mt-2 text-base text-gray-700">
                    Looking for a room? <a href="<?php echo APP_URL; ?>/room" class="text-[--primary-color] font-bold underline">Add Requirement
                        <i class="fas fa-angle-right text-xs"></i></a>
                </div>
                <?php endif; ?>
            </form>
        </div>
    </section>
    <!-- Add Your Room End -->

    <?php include 'partials/footer.php' ?>
    <script>
        // Client-side image compression and resizing before upload
        function compressImage(file, maxWidth = 1024, maxHeight = 1024, quality = 0.7) {
            return new Promise((resolve, reject) => {
                const img = new Image();
                const reader = new FileReader();
                reader.onload = function(e) {
                    img.src = e.target.result;
                };
                img.onload = function() {
                    let width = img.width;
                    let height = img.height;
                    if (width > maxWidth) {
                        height = Math.round(height * (maxWidth / width));
                        width = maxWidth;
                    }
                    if (height > maxHeight) {
                        width = Math.round(width * (maxHeight / height));
                        height = maxHeight;
                    }
                    const canvas = document.createElement('canvas');
                    canvas.width = width;
                    canvas.height = height;
                    const ctx = canvas.getContext('2d');
                    ctx.drawImage(img, 0, 0, width, height);
                    canvas.toBlob(blob => {
                        resolve(blob);
                    }, 'image/jpeg', quality);
                };
                img.onerror = reject;
                reader.onerror = reject;
                reader.readAsDataURL(file);
            });
        }

        document.addEventListener('DOMContentLoaded', function () {
            // Form validation for contact method, images, and description word count
            document.getElementById('addRoomForm').addEventListener('submit', function (e) {
                // Validate contact methods
                const contactBtns = document.querySelectorAll('.select-btn[data-group="contact_method"]');
                const selectedContacts = Array.from(contactBtns).filter(b => b.classList.contains('selected'));
                const contactError = document.getElementById('contact-method-error');
                if (selectedContacts.length < 2) {
                    contactError.textContent = 'Please select at least two contact methods.';
                    contactError.classList.remove('hidden');
                    e.preventDefault();
                    return;
                } else {
                    contactError.textContent = '';
                    contactError.classList.add('hidden');
                }
                // Validate images (count both existing and new)
                const fileInput = document.getElementById('room_photos');
                const imageError = document.getElementById('image-upload-error');
                const files = fileInput.files;
                const existingImages = (window.existingImages || []);
                const totalImages = (files ? files.length : 0) + existingImages.length;
                if (totalImages < 2) {
                    imageError.textContent = 'Please upload at least 2 images.';
                    imageError.classList.remove('hidden');
                    e.preventDefault();
                    return;
                }
                if (totalImages > 5) {
                    imageError.textContent = 'You can upload a maximum of 5 images.';
                    imageError.classList.remove('hidden');
                    e.preventDefault();
                    return;
                }
                let oversize = false;
                for (let i = 0; i < files.length; i++) {
                    if (files[i].size > 5 * 1024 * 1024) { // Lower max size to 5MB for speed
                        oversize = true;
                        break;
                    }
                }
                if (oversize) {
                    imageError.textContent = 'Each image must be less than 5MB.';
                    imageError.classList.remove('hidden');
                    e.preventDefault();
                    return;
                }
                imageError.textContent = '';
                imageError.classList.add('hidden');

                // Compress and resize images before upload
                if (files.length > 0) {
                    e.preventDefault();
                    document.getElementById('loadingPopup').classList.remove('hidden');
                    let compressPromises = [];
                    for (let i = 0; i < files.length; i++) {
                        compressPromises.push(compressImage(files[i]));
                    }
                    Promise.all(compressPromises).then(blobs => {
                        const dt = new DataTransfer();
                        blobs.forEach((blob, idx) => {
                            let fileName = files[idx].name.replace(/\.[^.]+$/, '.jpg');
                            dt.items.add(new File([blob], fileName, { type: 'image/jpeg' }));
                        });
                        fileInput.files = dt.files;
                        setTimeout(() => {
                            document.getElementById('loadingPopup').classList.add('hidden');
                            document.getElementById('successPopup').classList.remove('hidden');
                            setTimeout(() => {
                                e.target.submit();
                            }, 1200); // Show success popup for 1.2 seconds
                        }, 1200); // Loading popup for 1.2 seconds
                    });
                    return;
                }
                // Validate description word count
                const desc = document.getElementById('description');
                let words = desc.value.match(/\b\w+\b/g) || [];
                const descError = document.getElementById('desc-word-error');
                if (words.length < 20) {
                    descError.textContent = 'Description must be at least 20 words.';
                    descError.classList.remove('hidden');
                    e.preventDefault();
                    desc.focus();
                    return;
                } else {
                    descError.textContent = '';
                    descError.classList.add('hidden');
                }

                // Show loading popup and delay form submission for 3 seconds
                e.preventDefault();
                document.getElementById('loadingPopup').classList.remove('hidden');
                setTimeout(() => {
                    document.getElementById('loadingPopup').classList.add('hidden');
                    document.getElementById('successPopup').classList.remove('hidden');
                    setTimeout(() => {
                        e.target.submit();
                    }, 2000); // Show success popup for 2 seconds
                }, 2000); // Loading popup for 3 seconds
            });
            // Single-select button groups (radio-like)
            function setupButtonGroup(groupName, defaultValue) {
                const btns = document.querySelectorAll('.select-btn[data-group="' + groupName + '"]');
                const input = document.getElementById(groupName + "_input");
                btns.forEach(btn => {
                    btn.addEventListener('click', function () {
                        btns.forEach(b => b.classList.remove('selected'));
                        this.classList.add('selected');
                        input.value = this.dataset.value;
                    });
                });
                // Set selected from input value (for edit)
                let selectedValue = input.value && input.value.trim() !== '' ? input.value : defaultValue;
                let selectedBtn = Array.from(btns).find(b => b.dataset.value === selectedValue);
                if (selectedBtn) {
                    btns.forEach(b => b.classList.remove('selected'));
                    selectedBtn.classList.add('selected');
                    input.value = selectedValue;
                } else if (btns.length > 0) {
                    // fallback to default if no match
                    btns.forEach(b => b.classList.remove('selected'));
                    btns[0].classList.add('selected');
                    input.value = btns[0].dataset.value;
                }
            }
            setupButtonGroup('looking_for', 'any');
            setupButtonGroup('occupancy', 'single');

            // Multi-select for contact_method
            (function () {
                const btns = document.querySelectorAll('.select-btn[data-group="contact_method"]');
                const input = document.getElementById('contact_method_input');
                btns.forEach(btn => {
                    btn.addEventListener('click', function () {
                        this.classList.toggle('selected');
                        // Update hidden input with all selected values, comma separated
                        const selected = Array.from(btns).filter(b => b.classList.contains('selected')).map(b => b.dataset.value);
                        input.value = selected.join(',');
                    });
                });
                // Set selected from input value (for edit)
                let selectedValues = (input.value && input.value.trim() !== '' ? input.value : 'call').split(',');
                btns.forEach(b => {
                    if (selectedValues.includes(b.dataset.value)) {
                        b.classList.add('selected');
                    } else {
                        b.classList.remove('selected');
                    }
                });
                input.value = selectedValues.join(',');
            })();

            // Multi-select for highlights (chip style) - robust deselect/select
            document.querySelectorAll('.option-group .highlight-btn').forEach(function (chip) {
                const checkbox = chip.previousElementSibling;
                if (checkbox && checkbox.type === 'checkbox') {
                    // Sync chip on checkbox change
                    checkbox.addEventListener('change', function () {
                        chip.classList.toggle('selected', checkbox.checked);
                    });
                    // Clicking the chip toggles the checkbox (and triggers change event)
                    chip.addEventListener('click', function (e) {
                        e.preventDefault();
                        checkbox.click();
                    });
                    // Initial state
                    chip.classList.toggle('selected', checkbox.checked);
                }
            });

            // Custom file upload UI with image preview and removal
            const fileInput = document.getElementById('room_photos');
            const uploadBtn = document.getElementById('customUploadBtn');
            const dropArea = document.getElementById('dropArea');
            const imagePreviewRow = document.getElementById('imagePreviewRow');
            let imageFiles = [];
            // For edit mode: existing images from DB
            let existingImages = [];
            let removedImages = [];
            <?php if ($isEdit && !empty($listingData['images'])): ?>
                existingImages = <?php echo json_encode(is_array($listingData['images']) ? $listingData['images'] : explode(',', $listingData['images'])); ?>;
            <?php endif; ?>
            // Expose existingImages to global scope for validation
            window.existingImages = existingImages;

            function updateImagePreview() {
                imagePreviewRow.innerHTML = '';
                // Show existing images (from DB) first
                existingImages.slice(0, 5).forEach((imgUrl, idx) => {
                    const wrapper = document.createElement('div');
                    wrapper.className = 'relative w-24 h-28 flex flex-col items-center justify-center rounded-lg overflow-hidden bg-white shadow border';
                    const img = document.createElement('img');
                    img.src = imgUrl.startsWith('http') ? imgUrl
                        : (imgUrl.startsWith('/') || imgUrl.startsWith('assets/') ? imgUrl : 'assets/room-listings/' + imgUrl);
                    img.className = 'object-cover w-full h-20 rounded-t-lg';
                    const removeBtn = document.createElement('button');
                    removeBtn.type = 'button';
                    removeBtn.className = 'absolute top-2 right-2 bg-black text-white rounded-full w-6 h-6 flex items-center justify-center shadow-lg z-10';
                    removeBtn.style.position = 'absolute';
                    removeBtn.style.top = '0px';
                    removeBtn.style.right = '0px';
                    removeBtn.style.transform = '';
                    removeBtn.innerHTML = '<i class="fas fa-times"></i>';
                    removeBtn.onclick = function () {
                        // Remove from existingImages and add to removedImages
                        removedImages.push(imgUrl);
                        existingImages.splice(idx, 1);
                        updateImagePreview();
                        // Update hidden input for backend
                        document.getElementById('remove_images_input').value = removedImages.join(',');
                    };
                    wrapper.appendChild(removeBtn);
                    wrapper.appendChild(img);
                    imagePreviewRow.appendChild(wrapper);
                });
                // Then show new files (up to 5 total)
                let maxNew = 5 - existingImages.length;
                imageFiles.slice(0, maxNew).forEach((file, idx) => {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        const wrapper = document.createElement('div');
                        wrapper.className = 'relative w-24 h-28 flex flex-col items-center justify-center rounded-lg overflow-hidden bg-white shadow border';
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.className = 'object-cover w-full h-20 rounded-t-lg';
                        const removeBtn = document.createElement('button');
                        removeBtn.type = 'button';
                        removeBtn.className = 'absolute top-2 right-2 bg-black text-white rounded-full w-6 h-6 flex items-center justify-center shadow-lg z-10';
                        removeBtn.style.position = 'absolute';
                        removeBtn.style.top = '0px';
                        removeBtn.style.right = '0px';
                        removeBtn.style.transform = '';
                        removeBtn.innerHTML = '<i class="fas fa-times"></i>';
                        removeBtn.onclick = function () {
                            imageFiles.splice(idx, 1);
                            updateImagePreview();
                            syncFileInput();
                        };
                        wrapper.appendChild(removeBtn);
                        wrapper.appendChild(img);
                        imagePreviewRow.appendChild(wrapper);
                    };
                    reader.readAsDataURL(file);
                });
            }

            function syncFileInput() {
                // Create a new DataTransfer to update the file input
                const dt = new DataTransfer();
                imageFiles.forEach(f => dt.items.add(f));
                fileInput.files = dt.files;
            }

            uploadBtn.addEventListener('click', function () {
                fileInput.click();
            });

            fileInput.addEventListener('change', function () {
                // Only allow up to (5 - existingImages.length) new files
                const newFiles = Array.from(fileInput.files);
                let maxNew = 5 - existingImages.length;
                if (maxNew <= 0) {
                    imageFiles = [];
                    updateImagePreview();
                    syncFileInput();
                    return;
                }
                imageFiles = imageFiles.concat(newFiles).slice(0, maxNew);
                // Remove duplicates by name/size/type
                const unique = {};
                imageFiles.forEach(f => { unique[f.name + f.size + f.type] = f; });
                imageFiles = Object.values(unique);
                updateImagePreview();
                syncFileInput();
            });

            // Drag & drop support
            ['dragenter', 'dragover'].forEach(eventName => {
                dropArea.addEventListener(eventName, function (e) {
                    e.preventDefault();
                    dropArea.classList.add('border-green-500');
                });
            });
            ['dragleave', 'drop'].forEach(eventName => {
                dropArea.addEventListener(eventName, function (e) {
                    e.preventDefault();
                    dropArea.classList.remove('border-green-500');
                });
            });
            dropArea.addEventListener('drop', function (e) {
                e.preventDefault();
                let maxNew = 5 - existingImages.length;
                if (maxNew <= 0) {
                    imageFiles = [];
                    updateImagePreview();
                    syncFileInput();
                    return;
                }
                const files = Array.from(e.dataTransfer.files).filter(f => f.type.startsWith('image/'));
                imageFiles = imageFiles.concat(files).slice(0, maxNew);
                // Remove duplicates by name/size/type
                const unique = {};
                imageFiles.forEach(f => { unique[f.name + f.size + f.type] = f; });
                imageFiles = Object.values(unique);
                updateImagePreview();
                syncFileInput();
            });

            // On page load, show existing images if any
            updateImagePreview();
            // Description word limit logic
            const desc = document.getElementById('description');
            const wordCount = document.getElementById('desc-word-count');
            const wordWarning = document.getElementById('desc-word-warning');
            desc.addEventListener('input', function () {
                let words = desc.value.match(/\b\w+\b/g) || [];
                if (words.length > 300) {
                    // Trim to 300 words
                    desc.value = words.slice(0, 300).join(' ');
                    words = desc.value.match(/\b\w+\b/g) || [];
                    wordWarning.classList.remove('hidden');
                } else {
                    wordWarning.classList.add('hidden');
                }
                wordCount.textContent = words.length + '/300 words';
            });

            // Amenities see more/less logic
            (function () {
                const grid = document.getElementById('amenitiesGrid');
                const btn = document.getElementById('seeMoreAmenitiesBtn');
                const btnContainer = document.getElementById('seeMoreAmenitiesContainer');
                if (!grid) return;
                function getColCount() {
                    if (window.innerWidth < 640) return 3;
                    if (window.innerWidth < 768) return 4;
                    return 6;
                }
                function getRowHeight() {
                    const first = grid.children[0];
                    return first ? first.offsetHeight : 150;
                }
                function updateGrid(expanded) {
                    const colCount = getColCount();
                    const rowHeight = getRowHeight();
                    const totalRows = Math.ceil(grid.children.length / colCount);
                    if (expanded) {
                        grid.style.maxHeight = (rowHeight * totalRows + 32 * (totalRows - 1)) + 'px';
                        grid.style.overflow = 'visible';
                        btn.innerHTML = 'See less <i class="fas fa-angle-up"></i>';
                    } else {
                        grid.style.maxHeight = rowHeight + 32 + 'px'; // 32px for gap
                        grid.style.overflow = 'hidden';
                        btn.innerHTML = 'See more <i class="fas fa-angle-down"></i>';
                    }
                    btnContainer.style.display = totalRows > 1 ? 'flex' : 'none';
                }
                let expanded = false;
                btn.addEventListener('click', function () {
                    expanded = !expanded;
                    updateGrid(expanded);
                });
                window.addEventListener('resize', function () { updateGrid(expanded); });
                updateGrid(expanded);
            })();
        });
    </script>
</body>

</html>

