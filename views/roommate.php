<?php require_once __DIR__ . '/../config/constants.php'; ?>
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

    <meta name="description"
        content="Find the best rooms and roommates with RoomLinks. More than just a roommate, find your vibeMate today!" />
    <meta name="author" content="RoomLinks" />
    <!-- Open Graph / Facebook -->
    <meta property="og:title" content="RoomLinks | Official Website" />
    <meta property="og:description"
        content="Find the best rooms and roommates with RoomLinks. More than just a roommate, find your vibeMate today!" />
    <meta property="og:image" content="assets/hero-section/description.png" />
    <meta property="og:url" content="https://roomlinks.in" />
    <meta property="og:type" content="website" />
    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="RoomLinks | Official Website" />
    <meta name="twitter:description"
        content="Find the best rooms and roommates with RoomLinks. More than just a roommate, find your vibeMate today!" />
    <meta name="twitter:image" content="assets/hero-section/description.png" />
    <!-- Canonical URL -->
    <link rel="canonical" href="https://roomlinks.in" />

</head>

<body class="flex flex-col">
    <?php include 'partials/header.php' ?>

    <!-- Add Your Room Start -->
    <section class="py-16 md:py-20 flex justify-center items-center">
        <div
            class="w-full max-w-4xl mx-auto bg-white  p-4 sm:p-8 md:p-10 lg:p-12 xl:p-14 sm:mx-4 md:mx-8 lg:mx-0">
            <h2 class="text-3xl font-semibold text-center mb-1">Add your room details</h2>
            <p class="text-center text-gray-500 mb-6 text-base">so that other users can contact you.</p>
            <form class="space-y-8" id="addRoomForm">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold mb-1">Add Your Location*</label>
                        <input type="text" placeholder="Add Location..."
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-primary-400"
                            required>
                    </div>
                    <div>
                        <label class="block text-sm font-bold mb-1">Approx Rent*</label>
                        <div class="relative">
                            <span class="absolute left-3 top-2.5 text-gray-400">₹</span>
                            <input type="number" placeholder="5000"
                                class="w-full border border-gray-300 rounded-lg pl-8 pr-4 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-primary-400"
                                required>
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
                        <input type="hidden" name="looking_for" id="looking_for_input" value="any">
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
                        <input type="hidden" name="occupancy" id="occupancy_input" value="single">
                    </div>
                </div>
                <div class="mt-8">
                    <label class="block text-sm font-bold mb-1">Upload 5 Photos of your room</label>
                    <div class="border-2 border-dashed border-gray-300 rounded-xl bg-gray-50 flex flex-col items-center justify-center py-10 px-4 text-center transition relative mb-8" id="dropArea">
                        <input id="room_photos" type="file" name="room_photos[]" accept="image/*" multiple class="hidden" required>
                        <button type="button" id="customUploadBtn" class="mb-3 bg-blue-600 text-white font-semibold px-6 py-2 rounded-full flex items-center gap-2 shadow-md transition focus:outline-none focus:ring-2 focus:ring-blue-400"><i class="fas fa-cloud-upload-alt"></i> <span>Choose Images</span></button>
                        <span class="block text-gray-500 mb-1">or Drag Images To Upload</span>
                        <span class="block text-xs text-gray-400">(JPG, PNG, JPEG)</span>
                        <span class="block text-xs text-gray-400 mt-1">You can upload images upto <b>25 MB</b></span>
                        <div id="imagePreviewRow" class="flex gap-3 mt-4"></div>
                    </div>
                </div>
                <div class="mt-4 mb-8">
                    <label class="block text-sm font-bold mb-3">Amenities</label>
                    <div id="amenitiesGridWrapper">
                        <div id="amenitiesGrid" class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 gap-8 mb-2 justify-items-center transition-all duration-300" style="max-height: 8.5rem; overflow: hidden;">
                            <label class="flex flex-col items-center cursor-pointer group">
                                <input type="checkbox" name="amenities[]" value="air-conditioner" class="hidden peer" />
                                <span class="w-24 h-24 rounded-full bg-orange-50 flex items-center justify-center mb-2 border-2 border-transparent shadow-lg peer-checked:border-blue-500 transition">
                                    <img src="assets/amenities/air-conditioner.png" alt="Air Conditioner" class="w-14 h-14 object-contain" />
                                </span>
                                <span class="text-sm text-gray-700 group-hover:text-blue-600 block w-24 truncate text-center">Air Conditioner</span>
                            </label>
                            <label class="flex flex-col items-center cursor-pointer group">
                                <input type="checkbox" name="amenities[]" value="bed" class="hidden peer" />
                                <span class="w-24 h-24 rounded-full bg-orange-50 flex items-center justify-center mb-2 border-2 border-transparent shadow-lg peer-checked:border-blue-500 transition">
                                    <img src="assets/amenities/bed.png" alt="Bed" class="w-14 h-14 object-contain" />
                                </span>
                                <span class="text-sm text-gray-700 group-hover:text-blue-600 block w-24 truncate text-center">Bed</span>
                            </label>
                            <label class="flex flex-col items-center cursor-pointer group">
                                <input type="checkbox" name="amenities[]" value="cctv-survilence" class="hidden peer" />
                                <span class="w-24 h-24 rounded-full bg-orange-50 flex items-center justify-center mb-2 border-2 border-transparent shadow-lg peer-checked:border-blue-500 transition">
                                    <img src="assets/amenities/cctv-survilence.png" alt="CCTV Surveillance" class="w-14 h-14 object-contain" />
                                </span>
                                <span class="text-sm text-gray-700 group-hover:text-blue-600 block w-24 truncate text-center">CCTV Surveillance</span>
                            </label>
                            <label class="flex flex-col items-center cursor-pointer group">
                                <input type="checkbox" name="amenities[]" value="cooking-stove" class="hidden peer" />
                                <span class="w-24 h-24 rounded-full bg-orange-50 flex items-center justify-center mb-2 border-2 border-transparent shadow-lg peer-checked:border-blue-500 transition">
                                    <img src="assets/amenities/cooking-stove.png" alt="Cooking Stove" class="w-14 h-14 object-contain" />
                                </span>
                                <span class="text-sm text-gray-700 group-hover:text-blue-600 block w-24 truncate text-center">Cooking Stove</span>
                            </label>
                            <label class="flex flex-col items-center cursor-pointer group">
                                <input type="checkbox" name="amenities[]" value="elevator" class="hidden peer" />
                                <span class="w-24 h-24 rounded-full bg-orange-50 flex items-center justify-center mb-2 border-2 border-transparent shadow-lg peer-checked:border-blue-500 transition">
                                    <img src="assets/amenities/elevator.png" alt="Elevator" class="w-14 h-14 object-contain" />
                                </span>
                                <span class="text-sm text-gray-700 group-hover:text-blue-600 block w-24 truncate text-center">Elevator</span>
                            </label>
                            <label class="flex flex-col items-center cursor-pointer group">
                                <input type="checkbox" name="amenities[]" value="geyser" class="hidden peer" />
                                <span class="w-24 h-24 rounded-full bg-orange-50 flex items-center justify-center mb-2 border-2 border-transparent shadow-lg peer-checked:border-blue-500 transition">
                                    <img src="assets/amenities/geyser.png" alt="Geyser" class="w-14 h-14 object-contain" />
                                </span>
                                <span class="text-sm text-gray-700 group-hover:text-blue-600 block w-24 truncate text-center">Geyser</span>
                            </label>
                            <label class="flex flex-col items-center cursor-pointer group">
                                <input type="checkbox" name="amenities[]" value="ironing board" class="hidden peer" />
                                <span class="w-24 h-24 rounded-full bg-orange-50 flex items-center justify-center mb-2 border-2 border-transparent shadow-lg peer-checked:border-blue-500 transition">
                                    <img src="assets/amenities/ironing board.png" alt="Ironing Board" class="w-14 h-14 object-contain" />
                                </span>
                                <span class="text-sm text-gray-700 group-hover:text-blue-600 block w-24 truncate text-center">Ironing Board</span>
                            </label>
                            <label class="flex flex-col items-center cursor-pointer group">
                                <input type="checkbox" name="amenities[]" value="mattres" class="hidden peer" />
                                <span class="w-24 h-24 rounded-full bg-orange-50 flex items-center justify-center mb-2 border-2 border-transparent shadow-lg peer-checked:border-blue-500 transition">
                                    <img src="assets/amenities/mattres.png" alt="Mattress" class="w-14 h-14 object-contain" />
                                </span>
                                <span class="text-sm text-gray-700 group-hover:text-blue-600 block w-24 truncate text-center">Mattress</span>
                            </label>
                            <label class="flex flex-col items-center cursor-pointer group">
                                <input type="checkbox" name="amenities[]" value="microwave" class="hidden peer" />
                                <span class="w-24 h-24 rounded-full bg-orange-50 flex items-center justify-center mb-2 border-2 border-transparent shadow-lg peer-checked:border-blue-500 transition">
                                    <img src="assets/amenities/microwave.png" alt="Microwave" class="w-14 h-14 object-contain" />
                                </span>
                                <span class="text-sm text-gray-700 group-hover:text-blue-600 block w-24 truncate text-center">Microwave</span>
                            </label>
                            <label class="flex flex-col items-center cursor-pointer group">
                                <input type="checkbox" name="amenities[]" value="parking" class="hidden peer" />
                                <span class="w-24 h-24 rounded-full bg-orange-50 flex items-center justify-center mb-2 border-2 border-transparent shadow-lg peer-checked:border-blue-500 transition">
                                    <img src="assets/amenities/parking.png" alt="Parking" class="w-14 h-14 object-contain" />
                                </span>
                                <span class="text-sm text-gray-700 group-hover:text-blue-600 block w-24 truncate text-center">Parking</span>
                            </label>
                            <label class="flex flex-col items-center cursor-pointer group">
                                <input type="checkbox" name="amenities[]" value="pet-friendly" class="hidden peer" />
                                <span class="w-24 h-24 rounded-full bg-orange-50 flex items-center justify-center mb-2 border-2 border-transparent shadow-lg peer-checked:border-blue-500 transition">
                                    <img src="assets/amenities/pet-friendly.png" alt="Pet Friendly" class="w-14 h-14 object-contain" />
                                </span>
                                <span class="text-sm text-gray-700 group-hover:text-blue-600 block w-24 truncate text-center">Pet Friendly</span>
                            </label>
                            <label class="flex flex-col items-center cursor-pointer group">
                                <input type="checkbox" name="amenities[]" value="power-backup" class="hidden peer" />
                                <span class="w-24 h-24 rounded-full bg-orange-50 flex items-center justify-center mb-2 border-2 border-transparent shadow-lg peer-checked:border-blue-500 transition">
                                    <img src="assets/amenities/power-backup.png" alt="Power Backup" class="w-14 h-14 object-contain" />
                                </span>
                                <span class="text-sm text-gray-700 group-hover:text-blue-600 block w-24 truncate text-center">Power Backup</span>
                            </label>
                            <label class="flex flex-col items-center cursor-pointer group">
                                <input type="checkbox" name="amenities[]" value="refrigerator" class="hidden peer" />
                                <span class="w-24 h-24 rounded-full bg-orange-50 flex items-center justify-center mb-2 border-2 border-transparent shadow-lg peer-checked:border-blue-500 transition">
                                    <img src="assets/amenities/refrigerator.png" alt="Refrigerator" class="w-14 h-14 object-contain" />
                                </span>
                                <span class="text-sm text-gray-700 group-hover:text-blue-600 block w-24 truncate text-center">Refrigerator</span>
                            </label>
                            <label class="flex flex-col items-center cursor-pointer group">
                                <input type="checkbox" name="amenities[]" value="security-guards" class="hidden peer" />
                                <span class="w-24 h-24 rounded-full bg-orange-50 flex items-center justify-center mb-2 border-2 border-transparent shadow-lg peer-checked:border-blue-500 transition">
                                    <img src="assets/amenities/security-guards.png" alt="Security Guards" class="w-14 h-14 object-contain" />
                                </span>
                                <span class="text-sm text-gray-700 group-hover:text-blue-600 block w-24 truncate text-center">Security Guards</span>
                            </label>
                            <label class="flex flex-col items-center cursor-pointer group">
                                <input type="checkbox" name="amenities[]" value="sofa" class="hidden peer" />
                                <span class="w-24 h-24 rounded-full bg-orange-50 flex items-center justify-center mb-2 border-2 border-transparent shadow-lg peer-checked:border-blue-500 transition">
                                    <img src="assets/amenities/sofa.png" alt="Sofa" class="w-14 h-14 object-contain" />
                                </span>
                                <span class="text-sm text-gray-700 group-hover:text-blue-600 block w-24 truncate text-center">Sofa</span>
                            </label>
                            <label class="flex flex-col items-center cursor-pointer group">
                                <input type="checkbox" name="amenities[]" value="storage" class="hidden peer" />
                                <span class="w-24 h-24 rounded-full bg-orange-50 flex items-center justify-center mb-2 border-2 border-transparent shadow-lg peer-checked:border-blue-500 transition">
                                    <img src="assets/amenities/storage.png" alt="Storage" class="w-14 h-14 object-contain" />
                                </span>
                                <span class="text-sm text-gray-700 group-hover:text-blue-600 block w-24 truncate text-center">Storage</span>
                            </label>
                            <label class="flex flex-col items-center cursor-pointer group">
                                <input type="checkbox" name="amenities[]" value="study-desk" class="hidden peer" />
                                <span class="w-24 h-24 rounded-full bg-orange-50 flex items-center justify-center mb-2 border-2 border-transparent shadow-lg peer-checked:border-blue-500 transition">
                                    <img src="assets/amenities/study-desk.png" alt="Study Desk" class="w-14 h-14 object-contain" />
                                </span>
                                <span class="text-sm text-gray-700 group-hover:text-blue-600 block w-24 truncate text-center">Study Desk</span>
                            </label>
                            <label class="flex flex-col items-center cursor-pointer group">
                                <input type="checkbox" name="amenities[]" value="vacuum-cleanner" class="hidden peer" />
                                <span class="w-24 h-24 rounded-full bg-orange-50 flex items-center justify-center mb-2 border-2 border-transparent shadow-lg peer-checked:border-blue-500 transition">
                                    <img src="assets/amenities/vacuum-cleanner.png" alt="Vacuum Cleaner" class="w-14 h-14 object-contain" />
                                </span>
                                <span class="text-sm text-gray-700 group-hover:text-blue-600 block w-24 truncate text-center">Vacuum Cleaner</span>
                            </label>
                            <label class="flex flex-col items-center cursor-pointer group">
                                <input type="checkbox" name="amenities[]" value="washing-machine" class="hidden peer" />
                                <span class="w-24 h-24 rounded-full bg-orange-50 flex items-center justify-center mb-2 border-2 border-transparent shadow-lg peer-checked:border-blue-500 transition">
                                    <img src="assets/amenities/washing-machine.png" alt="Washing Machine" class="w-14 h-14 object-contain" />
                                </span>
                                <span class="text-sm text-gray-700 group-hover:text-blue-600 block w-24 truncate text-center">Washing Machine</span>
                            </label>
                            <label class="flex flex-col items-center cursor-pointer group">
                                <input type="checkbox" name="amenities[]" value="wifi" class="hidden peer" />
                                <span class="w-24 h-24 rounded-full bg-orange-50 flex items-center justify-center mb-2 border-2 border-transparent shadow-lg peer-checked:border-blue-500 transition">
                                    <img src="assets/amenities/wifi.png" alt="Wifi" class="w-14 h-14 object-contain" />
                                </span>
                                <span class="text-sm text-gray-700 group-hover:text-blue-600 block w-24 truncate text-center">Wifi</span>
                            </label>
                        </div>
                        <div id="seeMoreAmenitiesContainer" class="flex justify-center mt-0 hidden">
                            <button type="button" id="seeMoreAmenitiesBtn" class="text-blue-600 font-semibold underline text-base">See more</button>
                        </div>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-bold mb-1">Description*</label>
                    <textarea id="description" rows="10" maxlength="3000" placeholder="I am looking for a roommate for my room."
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-primary-400"
                        required></textarea>
                    <div class="flex justify-between items-center mt-1">
                        <span id="desc-word-count" class="text-xs text-gray-500">0/300 words</span>
                        <span id="desc-word-warning" class="text-xs text-red-500 hidden">Word limit reached!</span>
                    </div>
                </div>
                <div class="flex justify-center">
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-10 py-3 rounded-full text-lg flex items-center gap-2 shadow-md transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-400 active:scale-95">
                        <span>Submit</span>
                        <i class="fas fa-angle-right"></i>
                    </button>
                </div>
                <div class="text-center mt-2 text-base text-gray-700">
                    Looking for a room? <a href="#" class="text-[--primary-color] font-bold underline">Add Requirement <i
                            class="fas fa-angle-right text-xs"></i></a>
                </div>
            </form>
        </div>
    </section>
    <!-- Add Your Room End -->

    <?php include 'partials/footer.php' ?>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
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
                // Set default
                let defaultBtn = Array.from(btns).find(b => b.dataset.value === defaultValue);
                if (defaultBtn) {
                    defaultBtn.classList.add('selected');
                    input.value = defaultValue;
                }
            }
            setupButtonGroup('looking_for', 'any');
            setupButtonGroup('occupancy', 'single');

            // Multi-select for highlights (chip style)
            document.querySelectorAll('.option-group .highlight-btn').forEach(function (chip) {
                chip.addEventListener('click', function () {
                    chip.classList.toggle('selected');
                    // Optionally, you can manage a hidden input or collect selected values on submit
                });
            });

            // Custom file upload UI with image preview and removal
            const fileInput = document.getElementById('room_photos');
            const uploadBtn = document.getElementById('customUploadBtn');
            const dropArea = document.getElementById('dropArea');
            const imagePreviewRow = document.getElementById('imagePreviewRow');
            let imageFiles = [];

            function updateImagePreview() {
                imagePreviewRow.innerHTML = '';
                imageFiles.forEach((file, idx) => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const wrapper = document.createElement('div');
                        wrapper.className = 'relative w-24 h-28 flex flex-col items-center justify-center rounded-lg overflow-hidden bg-white shadow border';
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.className = 'object-cover w-full h-20 rounded-t-lg';
                        const removeBtn = document.createElement('button');
                        removeBtn.type = 'button';
                        removeBtn.className = 'absolute -top-2 -right-2 bg-black text-white rounded-full w-6 h-6 flex items-center justify-center shadow-lg z-10';
                        removeBtn.innerHTML = '<i class="fas fa-times"></i>';
                        removeBtn.onclick = function() {
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

            uploadBtn.addEventListener('click', function() {
                fileInput.click();
            });

            fileInput.addEventListener('change', function() {
                imageFiles = Array.from(fileInput.files);
                updateImagePreview();
            });

            // Drag & drop support
            ['dragenter', 'dragover'].forEach(eventName => {
                dropArea.addEventListener(eventName, function(e) {
                    e.preventDefault();
                    dropArea.classList.add('border-green-500');
                });
            });
            ['dragleave', 'drop'].forEach(eventName => {
                dropArea.addEventListener(eventName, function(e) {
                    e.preventDefault();
                    dropArea.classList.remove('border-green-500');
                });
            });
            dropArea.addEventListener('drop', function(e) {
                e.preventDefault();
                const files = Array.from(e.dataTransfer.files).filter(f => f.type.startsWith('image/'));
                imageFiles = imageFiles.concat(files);
                // Remove duplicates by name/size/type
                const unique = {};
                imageFiles.forEach(f => { unique[f.name + f.size + f.type] = f; });
                imageFiles = Object.values(unique);
                updateImagePreview();
                syncFileInput();
            });

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
            (function() {
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
                btn.addEventListener('click', function() {
                    expanded = !expanded;
                    updateGrid(expanded);
                });
                window.addEventListener('resize', function() { updateGrid(expanded); });
                updateGrid(expanded);
            })();
        });
    </script>
</body>

</html>