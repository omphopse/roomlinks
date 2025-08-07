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
                        <label class="block text-sm font-bold mb-1">Add Your Location*</label>
                        <input type="text" placeholder="Add Location.." name="location"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-400"
                            required value="<?php echo $isEdit ? htmlspecialchars($listingData['location']) : ''; ?>">
                    </div>
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
        function setupButtonGroup(groupName, defaultValue, multiSelect = false) {
            const btns = document.querySelectorAll('.select-btn[data-group="' + groupName + '"]');
            const input = document.getElementById(groupName + "_input");
            if (multiSelect) {
                // Support multiple default values (comma or pipe separated)
                let defaults = [];
                if (typeof defaultValue === 'string') {
                    defaults = defaultValue.split(/[,|]/).map(v => v.trim()).filter(Boolean);
                } else if (Array.isArray(defaultValue)) {
                    defaults = defaultValue;
                }
                btns.forEach(btn => {
                    btn.addEventListener('click', function () {
                        this.classList.toggle('selected');
                        // Collect all selected values
                        const selected = Array.from(btns).filter(b => b.classList.contains('selected')).map(b => b.dataset.value);
                        input.value = selected.join(',');
                    });
                    // Set initial selection if in defaults
                    if (defaults.includes(btn.dataset.value)) {
                        btn.classList.add('selected');
                    }
                });
                // Set input value to all selected
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
                // Set default
                let defaultBtn = Array.from(btns).find(b => b.dataset.value === defaultValue);
                if (defaultBtn) {
                    defaultBtn.classList.add('selected');
                    input.value = defaultValue;
                }
            }
        }
        document.addEventListener('DOMContentLoaded', function () {
            // Use PHP to inject the correct values for edit mode, else use defaults
            var lookingForVal = '<?php echo $isEdit ? htmlspecialchars($listingData['gender']) : 'any'; ?>';
            var occupancyVal = '<?php echo $isEdit ? htmlspecialchars($listingData['occupancy']) : 'single'; ?>';
            var contactMethodVal = '<?php echo $isEdit ? htmlspecialchars($listingData['contact']) : 'call'; ?>';
            // contactMethodVal may be pipe-separated, convert to comma for JS
            contactMethodVal = contactMethodVal.replace(/\s*\|\s*/g, ',');
            setupButtonGroup('looking_for', lookingForVal);
            setupButtonGroup('occupancy', occupancyVal);
            setupButtonGroup('pg_interest', 'no');
            setupButtonGroup('team_interest', 'no');
            setupButtonGroup('mobile_visible', 'yes');
            setupButtonGroup('contact_method', contactMethodVal, true);

            // Description word limit logic
            const desc = document.getElementById('description');
            const wordCount = document.getElementById('desc-word-count');
            const wordWarning = document.getElementById('desc-word-warning');
            const descMinWordError = document.getElementById('desc-minword-error');
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
                // Hide min word error on input
                if (words.length >= 20) {
                    descMinWordError.classList.add('hidden');
                }
            });

            // Form validation for contact method
            const form = document.getElementById('requirementForm');
            const contactMethodError = document.getElementById('contact-method-error');
            form.addEventListener('submit', function (e) {
                const selectedBtns = document.querySelectorAll('.select-btn[data-group="contact_method"].selected');
                if (selectedBtns.length < 2) {
                    contactMethodError.classList.remove('hidden');
                    e.preventDefault();
                } else {
                    contactMethodError.classList.add('hidden');
                }
                let words = desc.value.match(/\b\w+\b/g) || [];
                if (words.length < 20) {
                    descMinWordError.classList.remove('hidden');
                    e.preventDefault();
                } else {
                    descMinWordError.classList.add('hidden');
                }
            });
        });
    </script>
</body>

</html>