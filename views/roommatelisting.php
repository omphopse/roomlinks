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
    <meta property="og:description" content="Find the best rooms and roommates with RoomLinks. More than just a roommate, find your vibeMate today!" />
    <meta property="og:image" content="assets/hero-section/description.png" />
    <meta property="og:url" content="https://roomlinks.in" />
    <meta property="og:type" content="website" />
    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="RoomLinks | Official Website" />
    <meta name="twitter:description" content="Find the best rooms and roommates with RoomLinks. More than just a roommate, find your vibeMate today!" />
    <meta name="twitter:image" content="assets/hero-section/description.png" />
    <!-- Canonical URL -->
    <link rel="canonical" href="https://roomlinks.in" />

</head>

<body class="flex flex-col min-h-screen ">
    <?php include 'partials/header.php' ?>
    <!-- Single Listing Start -->
    <div class="w-full flex justify-center">
        <div class="w-full max-w-6xl py-8 px-2 md:px-8 flex md:flex-row flex-col gap-6">
            <!-- Left Sidebar (Desktop Only) -->
            <div class="hidden md:flex md:w-1/4 w-full flex-col gap-4">
                <div class="bg-white rounded-xl shadow-xl shadow-gray-300 p-6 flex flex-col items-center">
                    <img src="assets/profile/user.png" alt="User Avatar" class="w-24 h-24 rounded-full mb-2 border-4 border-gray-200">
                    <h2 class="font-semibold text-lg mb-2">Abhishek Sharma</h2>
                    <div class="flex gap-2 w-full justify-center mt-2">
                        <button class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded flex items-center gap-2"><i class="fab fa-whatsapp"></i> Chat</button>
                        <button class="bg-blue-100 hover:bg-blue-200 text-blue-700 px-4 py-2 rounded flex items-center gap-2"><i class="fas fa-phone"></i> Call</button>
                    </div>
                </div>
            </div>
            <!-- Main Content -->
            <div class="w-full md:w-3/4">
                <div class="bg-white rounded-xl shadow-xl shadow-gray-300 p-6 mb-6">
                    <div class="mb-4 text-sm text-gray-400">Home / Looking for Roommate / <span class="text-gray-700 font-medium">Abhishek Sharma</span></div>
                    <!-- Tablet/Mobile Profile Top -->
                    <div class="flex flex-col items-center mb-6 md:hidden">
                        <img src="assets/profile/user.png" alt="User Avatar" class="w-24 h-24 rounded-full mb-2 border-4 border-gray-200">
                        <h2 class="font-semibold text-xl mb-2">Abhishek Sharma</h2>
                        <div class="flex gap-2 w-full justify-center mt-2 mb-2 flex-wrap">
                            <button class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded flex items-center gap-2"><i class="fab fa-whatsapp"></i> Chat</button>
                            <button class="bg-blue-100 hover:bg-blue-200 text-blue-700 px-6 py-2 rounded flex items-center gap-2"><i class="fas fa-phone"></i> Call</button>
                        </div>
                        <div class="flex items-center gap-2 text-gray-600"><i class="fas fa-map-marker-alt"></i> Pune, Maharashtra, India</div>
                    </div>
                    <!-- Desktop Location Row -->
                    <div class="hidden md:block border-t pt-8 pb-8">
                        <h3 class="font-semibold text-lg mb-1">Location</h3>
                        <div class="flex items-center gap-2 text-gray-600">
                            <i class="fas fa-map-marker-alt"></i> Pune, Maharashtra, India
                        </div>
                    </div>
                    <div class=" border-t pt-8 pb-8">
                        <h3 class="font-semibold text-lg mb-2">Basic Info</h3>
                        <div class="grid grid-cols-2 gap-4 md:grid-cols-4">
                            <div>
                                <div class="text-xs text-gray-400">Gender</div>
                                <div class="font-medium flex items-center gap-1"><i class="fas fa-mars"></i> Male</div>
                            </div>
                            <div>
                                <div class="text-xs text-gray-400">Approx Rent</div>
                                <div class="font-medium">₹ 7900</div>
                            </div>
                            <div>
                                <div class="text-xs text-gray-400">Occupancy</div>
                                <div class="font-medium flex items-center gap-1"><i class="fas fa-bed"></i> Shared</div>
                            </div>
                            <div>
                                <div class="text-xs text-gray-400">Looking For</div>
                                <div class="font-medium flex items-center gap-1"><i class="fas fa-mars"></i> Male</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class=" border-t pt-8 pb-8">
                        <h3 class="font-semibold text-lg mb-2">Vibes</h3>
                        <div class="flex flex-wrap gap-6">
                            <div class="flex flex-col items-center"><span class="w-20 h-20 md:w-24 md:h-24 rounded-full bg-white shadow-lg flex items-center justify-center mb-1"><img src="assets/vibes/early-bird.png" alt="Early Bird" class="w-12 h-12 md:w-16 md:h-16 object-contain rounded-full" /></span><span class="text-xs mt-1">Early Bird</span></div>
                            <div class="flex flex-col items-center"><span class="w-20 h-20 md:w-24 md:h-24 rounded-full bg-white shadow-lg flex items-center justify-center mb-1"><img src="assets/vibes/reader.png" alt="Studious" class="w-12 h-12 md:w-16 md:h-16 object-contain rounded-full" /></span><span class="text-xs mt-1">Studious</span></div>
                            <div class="flex flex-col items-center"><span class="w-20 h-20 md:w-24 md:h-24 rounded-full bg-white shadow-lg flex items-center justify-center mb-1"><img src="assets/vibes/gym.png" alt="Fitness Freak" class="w-12 h-12 md:w-16 md:h-16 object-contain rounded-full" /></span><span class="text-xs mt-1">Fitness Freak</span></div>
                            <div class="flex flex-col items-center"><span class="w-20 h-20 md:w-24 md:h-24 rounded-full bg-white shadow-lg flex items-center justify-center mb-1"><img src="assets/vibes/party-mode.png" alt="Sporty" class="w-12 h-12 md:w-16 md:h-16 object-contain rounded-full" /></span><span class="text-xs mt-1">Sporty</span></div>
                            <div class="flex flex-col items-center"><span class="w-20 h-20 md:w-24 md:h-24 rounded-full bg-white shadow-lg flex items-center justify-center mb-1"><img src="assets/vibes/explorer.png" alt="Wanderer" class="w-12 h-12 md:w-16 md:h-16 object-contain rounded-full" /></span><span class="text-xs mt-1">Wanderer</span></div>
                        </div>
                    </div>
                    <div class=" border-t pt-8 pb-8">
                        <h3 class="font-semibold text-lg mb-2">Highlights</h3>
                        <div class="flex flex-wrap gap-2">
                            <span class="bg-gray-100 text-gray-700 rounded-full px-3 py-1 text-xs flex items-center gap-1"><i class="fas fa-check text-green-500"></i> Attached washroom</span>
                            <span class="bg-gray-100 text-gray-700 rounded-full px-3 py-1 text-xs flex items-center gap-1"><i class="fas fa-check text-green-500"></i> Market nearby</span>
                            <span class="bg-gray-100 text-gray-700 rounded-full px-3 py-1 text-xs flex items-center gap-1"><i class="fas fa-check text-green-500"></i> Attached balcony</span>
                            <span class="bg-gray-100 text-gray-700 rounded-full px-3 py-1 text-xs flex items-center gap-1"><i class="fas fa-check text-green-500"></i> Gated society</span>
                            <span class="bg-gray-100 text-gray-700 rounded-full px-3 py-1 text-xs flex items-center gap-1"><i class="fas fa-check text-green-500"></i> No Restriction</span>
                            <span class="bg-gray-100 text-gray-700 rounded-full px-3 py-1 text-xs flex items-center gap-1"><i class="fas fa-check text-green-500"></i> House keeping</span>
                            <span class="bg-gray-100 text-gray-700 rounded-full px-3 py-1 text-xs flex items-center gap-1"><i class="fas fa-check text-green-500"></i> Gym nearby</span>
                        </div>
                    </div>
                    
                    <div class=" border-t pt-8 pb-8">
                        <h3 class="font-semibold text-lg mb-2">Description</h3>
                        <div class="bg-gray-50 rounded-lg p-4 text-gray-400 flex items-center gap-2">
                            <span>I need a roommate.</span>
                        </div>
                    </div>
                    <div class="flex justify-start border-t pt-8 pb-8" id="reportArea">
                        <button id="reportBtn" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded">Report Listing</button>
                    </div>
                    <!-- Report Listing Modal -->
                    <div id="reportModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40 hidden">
                        <div class="bg-white rounded-2xl shadow-2xl p-8 w-full max-w-xl relative flex flex-col items-center">
                            <button id="closeReportModal" class="absolute top-4 right-4 text-gray-400 hover:text-gray-700 text-3xl leading-none">&times;</button>
                            <img src="assets/banner/report.png" alt="Alert" class="w-48 h-48 object-contain mb-2 mt-2" />
                            <h3 class="font-bold text-2xl text-center mb-2 mt-2">Found Wrong Information?</h3>
                            <p class="text-gray-500 text-center mb-6">Please choose a option from below</p>
                            <div class="flex gap-4 mb-6 w-full justify-center">
                                <button id="occupiedBtn" class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded-lg font-semibold text-base flex-1 max-w-xs">Not Looking Now</button>
                                <button id="wrongInfoBtn" class="bg-yellow-400 hover:bg-yellow-500 text-white px-6 py-2 rounded-lg font-semibold text-base flex-1 max-w-xs">Wrong Information</button>
                            </div>
                            <div id="reportLoading" class="hidden w-full flex flex-col items-center justify-center mb-2">
                                <svg class="animate-spin h-8 w-8 text-blue-500 mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path></svg>
                                <span class="text-blue-500 font-medium">Submitting your request...</span>
                            </div>
                            <p class="text-xs text-gray-400 text-center mt-2">Note: We'll review this listing and proceed with its removal once we've confirmed the decision with the user who posted it.</p>
                        </div>
                    </div>
                    <script>
                        // Modal open/close logic
                        document.getElementById('reportBtn').onclick = function() {
                            document.getElementById('reportModal').classList.remove('hidden');
                        };
                        document.getElementById('closeReportModal').onclick = function() {
                            document.getElementById('reportModal').classList.add('hidden');
                        };
                        document.getElementById('reportModal').onclick = function(e) {
                            if (e.target === this) this.classList.add('hidden');
                        };
                        // Success message logic with loading
                        function showReportSuccess() {
                            // Show loading spinner
                            document.getElementById('reportLoading').classList.remove('hidden');
                            // Disable buttons
                            document.getElementById('occupiedBtn').disabled = true;
                            document.getElementById('wrongInfoBtn').disabled = true;
                            setTimeout(function() {
                                document.getElementById('reportModal').classList.add('hidden');
                                document.getElementById('reportLoading').classList.add('hidden');
                                document.getElementById('reportArea').innerHTML = `
                                    <div class="w-full flex items-center gap-2 p-4">
                                        <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" fill="none"/><path d="M8 12l2 2l4-4" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                        <span class="text-green-600 font-semibold text-lg">Your request has been successfully submitted.</span>
                                    </div>
                                `;
                            }, 1000);
                        }
                        document.getElementById('occupiedBtn').onclick = showReportSuccess;
                        document.getElementById('wrongInfoBtn').onclick = showReportSuccess;
                    </script>
                </div>
            </div>
        </div>
    </div>
    <!-- Single Lisiting End -->
    <?php include 'partials/footer.php' ?>
</body>
</html>