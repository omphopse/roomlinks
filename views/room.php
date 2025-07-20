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

    <!-- Add Room Requirment Start -->
    <section class="py-16 md:py-20 flex justify-center items-center">
        <div class="w-full max-w-4xl mx-auto bg-white p-4 sm:p-8 md:p-10 lg:p-12 xl:p-14
            sm:mx-4 md:mx-8 lg:mx-0">
            <h2 class="text-2xl font-semibold text-center mb-1">Add your requirement</h2>
            <p class="text-center text-gray-500 mb-6 text-sm">so that other users can contact you.</p>
            <form class="space-y-6" id="requirementForm">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold mb-1">Add Your Location*</label>
                        <input type="text" placeholder="Add Location.."
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-400"
                            required>
                    </div>
                    <div>
                        <label class="block text-sm font-bold mb-1">Approx Rent*</label>
                        <div class="relative">
                            <span class="absolute left-3 top-2.5 text-gray-400">₹</span>
                            <input type="number" placeholder="5000"
                                class="w-full border border-gray-300 rounded-lg pl-8 pr-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-400"
                                required>
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
                        <input type="hidden" name="looking_for" id="looking_for_input" value="any">
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
                        <input type="hidden" name="occupancy" id="occupancy_input" value="single">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-bold mb-1">Choose Highlights for your property</label>
                    <div class="flex flex-wrap gap-3 mt-2 option-group">
                        <label class="cursor-pointer">
                            <input type="checkbox" name="highlights[]" value="Working full time" class="hidden peer">
                            <span
                                class="highlight-btn option-btn peer-checked:bg-[--primary-color] peer-checked:text-white peer-checked:border-[--primary-color]">Working
                                full time</span>
                        </label>
                        <label class="cursor-pointer">
                            <input type="checkbox" name="highlights[]" value="College student" class="hidden peer">
                            <span
                                class="highlight-btn option-btn peer-checked:bg-[--primary-color] peer-checked:text-white peer-checked:border-[--primary-color]">College
                                student</span>
                        </label>
                        <label class="cursor-pointer">
                            <input type="checkbox" name="highlights[]" value="25+ age" class="hidden peer">
                            <span
                                class="highlight-btn option-btn peer-checked:bg-[--primary-color] peer-checked:text-white peer-checked:border-[--primary-color]">25+
                                age</span>
                        </label>
                        <label class="cursor-pointer">
                            <input type="checkbox" name="highlights[]" value="<25 age" class="hidden peer">
                            <span
                                class="highlight-btn option-btn peer-checked:bg-[--primary-color] peer-checked:text-white peer-checked:border-[--primary-color]">&lt;25
                                age</span>
                        </label>
                        <label class="cursor-pointer">
                            <input type="checkbox" name="highlights[]" value="Working night shifts" class="hidden peer">
                            <span
                                class="highlight-btn option-btn peer-checked:bg-[--primary-color] peer-checked:text-white peer-checked:border-[--primary-color]">Working
                                night shifts</span>
                        </label>
                        <label class="cursor-pointer">
                            <input type="checkbox" name="highlights[]" value="Have 2 wheeler" class="hidden peer">
                            <span
                                class="highlight-btn option-btn peer-checked:bg-[--primary-color] peer-checked:text-white peer-checked:border-[--primary-color]">Have
                                2 wheeler</span>
                        </label>
                        <label class="cursor-pointer">
                            <input type="checkbox" name="highlights[]" value="Have 4 wheeler" class="hidden peer">
                            <span
                                class="highlight-btn option-btn peer-checked:bg-[--primary-color] peer-checked:text-white peer-checked:border-[--primary-color]">Have
                                4 wheeler</span>
                        </label>
                        <label class="cursor-pointer">
                            <input type="checkbox" name="highlights[]" value="Will shift immediately"
                                class="hidden peer">
                            <span
                                class="highlight-btn option-btn peer-checked:bg-[--primary-color] peer-checked:text-white peer-checked:border-[--primary-color]">Will
                                shift immediately</span>
                        </label>
                        <label class="cursor-pointer">
                            <input type="checkbox" name="highlights[]" value="Have pets" class="hidden peer">
                            <span
                                class="highlight-btn option-btn peer-checked:bg-[--primary-color] peer-checked:text-white peer-checked:border-[--primary-color]">Have
                                pets</span>
                        </label>
                        <label class="cursor-pointer">
                            <input type="checkbox" name="highlights[]" value="Need no furnishing" class="hidden peer">
                            <span
                                class="highlight-btn option-btn peer-checked:bg-[--primary-color] peer-checked:text-white peer-checked:border-[--primary-color]">Need
                                no furnishing</span>
                        </label>
                        <label class="cursor-pointer">
                            <input type="checkbox" name="highlights[]" value="Pure vegetarian" class="hidden peer">
                            <span
                                class="highlight-btn option-btn peer-checked:bg-[--primary-color] peer-checked:text-white peer-checked:border-[--primary-color]">Pure
                                vegetarian</span>
                        </label>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold mb-1">Are you interested in PG too?</label>
                        <div class="select-group option-group">
                            <button type="button" class="select-btn" data-group="pg_interest"
                                data-value="yes">Yes</button>
                            <button type="button" class="select-btn" data-group="pg_interest"
                                data-value="no">No</button>
                        </div>
                        <input type="hidden" name="pg_interest" id="pg_interest_input" value="no">
                    </div>
                    <div>
                        <label class="block text-sm font-bold mb-1">Preferred Contact Method</label>
                        <div class="select-group option-group">
                            <button type="button" class="select-btn" data-group="contact_method" data-value="call">Call</button>
                            <button type="button" class="select-btn" data-group="contact_method" data-value="whatsapp">WhatsApp</button>
                            <button type="button" class="select-btn" data-group="contact_method" data-value="email">Email</button>
                        </div>
                        <input type="hidden" name="contact_method" id="contact_method_input" value="call">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-bold mb-1">Description*</label>
                    <textarea id="description" rows="3" maxlength="3000" placeholder="I am looking for a room with roommate."
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-400"
                        required></textarea>
                    <div class="flex justify-between items-center mt-1">
                        <span id="desc-word-count" class="text-xs text-gray-500">0/300 words</span>
                        <span id="desc-word-warning" class="text-xs text-red-500 hidden">Word limit reached!</span>
                    </div>
                </div>
                <div class="flex justify-center">
                    <button type="submit"
                        class="bg-[--primary-color] text-white font-semibold px-10 py-2 rounded-full text-lg">Submit
                        &gt;</button>
                </div>
                <div class="text-center mt-2 text-sm text-gray-500">
                    Have room & need roommate? <a href="#" class="text-[--primary-color] font-semibold underline">Add Room
                        &gt;</a>
                </div>
            </form>
        </div>
    </section>
    <!-- Add Room Requirment End  -->

    <div class="mb-8 md:mb-12"></div>
    <?php include 'partials/footer.php' ?>

    <script>
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
        document.addEventListener('DOMContentLoaded', function () {
            setupButtonGroup('looking_for', 'any');
            setupButtonGroup('occupancy', 'single');
            setupButtonGroup('pg_interest', 'no');
            setupButtonGroup('team_interest', 'no');
            setupButtonGroup('mobile_visible', 'yes');
            setupButtonGroup('contact_method', 'call');

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
        });
    </script>
</body>

</html>