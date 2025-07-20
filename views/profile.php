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

<body class="flex flex-col min-h-screen ">
    <?php include 'partials/header.php' ?>
    <div class="flex-grow flex items-center justify-center px-4 ">

        <!-- Profile Start -->
        <form
            class="w-full max-w-5xl mx-auto bg-white rounded-2xl shadow-2xl shadow-blue-300/60 p-4 sm:p-10 mt-10 mb-12 flex flex-col space-y-6 relative before:content-[''] before:absolute before:top-0 before:left-0 before:w-full before:h-6 before:rounded-t-2xl before:shadow-2xl before:shadow-blue-300/60 before:z-10">
            <h2 class="text-3xl font-semibold text-blue-600 text-start">My Profile</h2>
            <hr class="border-gray-300 mb-3">
            <div class="flex flex-col md:flex-row gap-8">
                <div class="flex-1">
                    <label class="block text-gray-700 font-semibold mb-1" for="first_name">First Name</label>
                    <input id="first_name" type="text" value="John" name="first_name"
                        class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400" />
                </div>
                <div class="flex-1">
                    <label class="block text-gray-700 font-semibold mb-1" for="last_name">Last Name</label>
                    <input id="last_name" type="text" value="Doe" name="last_name"
                        class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400" />
                </div>
            </div>
            <div class="flex flex-col md:flex-row gap-8">
                <div class="flex-1">
                    <label class="block text-gray-700 font-semibold mb-1" for="email">Email</label>
                    <input id="email" type="email" value="johndoe@example.com" name="email"
                        class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400" />
                </div>
                <div class="flex-1">
                    <label class="block text-gray-700 font-semibold mb-1" for="phone">Phone Number</label>
                    <input id="phone" type="text" value="+91 9876543210" name="phone"
                        class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400" />
                </div>
            </div>
            <div>
                <label class="block text-gray-700 font-semibold mb-1" for="gender">Gender</label>
                <div class="flex gap-4 mt-2">
                    <button type="button" id="gender-male" class="gender-btn px-4 py-2 rounded border border-gray-300 text-gray-700 bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-400" data-value="Male">Male</button>
                    <button type="button" id="gender-female" class="gender-btn px-4 py-2 rounded border border-gray-300 text-gray-700 bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-400" data-value="Female">Female</button>
                    <button type="button" id="gender-other" class="gender-btn px-4 py-2 rounded border border-gray-300 text-gray-700 bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-400" data-value="Other">Other</button>
                </div>
                <input type="hidden" name="gender" id="gender-input" value="Male" />
            </div>
            <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded transition duration-200 mt-4">Update
                Changes</button>
            <div class="mt-8 w-full">
                <hr class="border-gray-300 mb-3">
                <div class="text-left mb-2 text-sm text-gray-500">If you no longer need your RoomLinks account, you can
                    delete your profile permanently. This action cannot be undone.</div>
                <div class="flex justify-start">
                    <button type="button" id="deleteProfileBtn"
                        class="flex items-center gap-2 text-red-600 py-2 px-4 rounded transition duration-200 mt-0">
                        <i class="fas fa-trash-alt"></i>
                        Delete Profile
                    </button>
                </div>
            </div>
        </form>

        <!-- Delete Confirmation Modal -->
        <div id="deleteModal"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40 hidden p-2 sm:p-0 m-0">
            <div class="bg-white rounded-lg shadow-2xl p-4 sm:p-8 w-full max-w-md m-0">
                <h3 class="text-2xl font-bold mb-4 text-center text-[--primary-color]">Are you sure?</h3>
                <p class="mb-6 text-gray-700 text-center">Your data will be deleted from the server and this action
                    cannot be undone.</p>
                <div class="flex justify-center gap-4">
                    <button id="confirmDelete" type="button"
                        class="px-4 py-2 rounded bg-red-600 text-white hover:bg-red-700">Delete</button>
                     <button id="cancelDelete" type="button"
                        class="px-4 py-2 rounded border border-gray-300 text-gray-700 hover:bg-gray-100">Cancel</button>
                </div>
            </div>
        </div>

        <script>
            // Modal logic
            const deleteBtn = document.getElementById('deleteProfileBtn');
            const modal = document.getElementById('deleteModal');
            const cancelBtn = document.getElementById('cancelDelete');
            const confirmBtn = document.getElementById('confirmDelete');
            if (deleteBtn && modal && cancelBtn && confirmBtn) {
                deleteBtn.onclick = () => { modal.classList.remove('hidden'); };
                cancelBtn.onclick = () => { modal.classList.add('hidden'); };
                confirmBtn.onclick = () => {
                    modal.classList.add('hidden');
                    // Add your delete logic here
                    alert('Profile deleted (demo only)');
                };
            }
            // Gender button logic
            const genderBtns = document.querySelectorAll('.gender-btn');
            const genderInput = document.getElementById('gender-input');
            genderBtns.forEach(btn => {
                btn.onclick = function() {
                    genderBtns.forEach(b => b.classList.remove('bg-blue-600', 'text-white'));
                    this.classList.add('bg-blue-600', 'text-black');
                    genderInput.value = this.getAttribute('data-value');
                };
            });
            // Set default selected
            document.getElementById('gender-male').classList.add('bg-blue-600', 'text-black');
        </script>
        <!-- Profile End -->
    </div>

    <?php include 'partials/footer.php' ?>
</body>

</html>