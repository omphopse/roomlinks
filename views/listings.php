<?php require_once __DIR__ . '/../config/constants.php'; ?>
<?php
// Example: Fetch all listings from database or array
// Replace this with your actual data fetching logic
$listings = [
    [
        'image' => 'assets/hero-section/description.png',
        'price' => '₹8,500/mo',
        'location' => 'Bandra West, Mumbai',
        'amenities' => ['2 BHK', 'WiFi', 'Attached Bath'],
        'amenity_icons' => ['fa-bed', 'fa-wifi', 'fa-bath'],
        'profile_img' => 'assets/profile/user.png',
        'profile_name' => 'Amit Sharma',
        'profile_status' => 'Looking for Roommate',
    ],
    [
        'image' => 'assets/hero-section/description.png',
        'price' => '₹12,000/mo',
        'location' => 'Koregaon Park, Pune',
        'amenities' => ['1 BHK', 'WiFi', 'Kitchen'],
        'amenity_icons' => ['fa-bed', 'fa-wifi', 'fa-utensils'],
        'profile_img' => 'assets/profile/user.png',
        'profile_name' => 'Priya Desai',
        'profile_status' => 'Looking for Tenant',
    ],
    [
        'image' => 'assets/hero-section/description.png',
        'price' => '₹7,200/mo',
        'location' => 'HSR Layout, Bangalore',
        'amenities' => ['1 RK', 'WiFi', 'Geyser'],
        'amenity_icons' => ['fa-bed', 'fa-wifi', 'fa-shower'],
        'profile_img' => 'assets/profile/user.png',
        'profile_name' => 'Rahul Verma',
        'profile_status' => 'Looking for Roommate',
    ]
    // ...add more listings here or fetch from DB
];

// Pagination logic
$perPage = 24;
$totalListings = count($listings);
$totalPages = ceil($totalListings / $perPage);
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$start = ($page - 1) * $perPage;
$paginatedListings = array_slice($listings, $start, $perPage);
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

<body class="flex flex-col min-h-screen">
    <div class="flex-grow">

        <?php include 'partials/header.php' ?>

        <!-- start of listing section -->
        <section class="custom-listing-section">
            <!-- Filter Overlay (Drawer Style) -->
            <div id="filterOverlay" class="fixed inset-0 z-50 hidden">
                <div class="absolute inset-0 bg-black bg-opacity-40 transition-opacity duration-300" onclick="closeFilterOverlay()"></div>
                <div id="filterDrawer" class="fixed right-0 top-0 h-full w-full max-w-md bg-white rounded-l-2xl shadow-xl p-6 flex flex-col justify-between transform translate-x-full transition-transform duration-300">
                    <div>
                        <button onclick="closeFilterOverlay()" class="absolute top-3 right-3 text-gray-400 hover:text-gray-700 text-2xl">&times;</button>
                        <h2 class="text-xl font-semibold mb-6 text-center">Filter Listings</h2>
                        <!-- Budget Dropdowns -->
                        <div class="mb-6">
                            <label class="block mb-2 font-medium">Budget (₹)</label>
                            <div class="flex gap-2">
                                <select id="budgetMin" class="w-1/2 border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-200">
                                    <option value="1000">₹1,000</option>
                                    <option value="5000">₹5,000</option>
                                    <option value="10000">₹10,000</option>
                                    <option value="20000">₹20,000</option>
                                    <option value="50000">₹50,000</option>
                                    <option value="100000">₹1,00,000</option>
                                </select>
                                <select id="budgetMax" class="w-1/2 border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-200">
                                    <option value="5000">₹5,000</option>
                                    <option value="10000">₹10,000</option>
                                    <option value="20000">₹20,000</option>
                                    <option value="50000">₹50,000</option>
                                    <option value="100000">₹1,00,000</option>
                                    <option value="1000000">₹10,00,000</option>
                                </select>
                            </div>
                        </div>
                        <!-- Location -->
                        <div class="mb-6">
                            <label class="block mb-2 font-medium">Location</label>
                            <select class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-200">
                                <option value="ahilayanagar">Ahilayanagar</option>
                                <option value="pune">Pune</option>
                            </select>
                        </div>
                        <!-- Gender -->
                        <div class="mb-6">
                            <label class="block mb-2 font-medium">Gender</label>
                            <div class="flex gap-4">
                                <label class="flex items-center gap-2"><input type="radio" name="gender" value="male" class="accent-blue-600"> Male</label>
                                <label class="flex items-center gap-2"><input type="radio" name="gender" value="female" class="accent-blue-600"> Female</label>
                            </div>
                        </div>
                        <!-- Looking for -->
                        <div class="mb-6">
                            <label class="block mb-2 font-medium">Looking for</label>
                            <div class="flex gap-4">
                                <label class="flex items-center gap-2"><input type="checkbox" name="lookingfor" value="roommates" class="accent-blue-600"> Roommates</label>
                                <label class="flex items-center gap-2"><input type="checkbox" name="lookingfor" value="rooms" class="accent-blue-600"> Rooms</label>
                            </div>
                        </div>
                        <div class="flex gap-2 mt-2">
                            <button class="btn-write m-0 w-1/2" type="button" onclick="closeFilterOverlay()">Close</button>
                            <button class="btn-write w-1/2" type="button" onclick="applyFilter()">Apply Filter</button>
                        </div>
                    </div>
                    <div class="mt-8 pt-4 border-t text-center text-sm  text-[--primary-color] rounded-b-2xl shadow-inner">
                        &copy; <?php echo date('Y'); ?> RoomLinks. All rights reserved.
                    </div>
                </div>
            </div>
            <!-- Search and Filter Bar -->
            <div class="flex flex-col items-center justify-center w-[90%] md:w-[95%] lg:w-full mb-8">
                <div class="flex flex-row items-center justify-center gap-4 w-full max-w-2xl">
                    <div class="hero-input-wrap w-full">
                        <i class="fas fa-search hero-search-icon"></i>
                        <input type="text" class="hero-input" placeholder="Search your home here..." />
                    </div>
                    <button class="btn-write flex items-center gap-2 h-[44px] px-6" onclick="openFilterOverlay()">
                        <i class="fas fa-bars"></i>
                        <span>Filters</span>
                    </button>
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 custom-listing-cards">
                <?php foreach ($paginatedListings as $listing): ?>
                <div class="custom-listing-card">
                    <div class="relative flex-shrink-0">
                        <img src="<?php echo $listing['image']; ?>" alt="Room Thumbnail"
                            class="w-full h-56 max-h-56 object-cover object-center">
                        <span
                            class="absolute top-3 left-3 bg-blue-600 text-white px-4 py-1 rounded-full text-sm font-semibold shadow"><?php echo $listing['price']; ?></span>
                    </div>
                    <div class="custom-listing-card-content">
                        <div class="custom-listing-location">
                            <i class="fas fa-map-marker-alt"></i>
                            <span><?php echo $listing['location']; ?></span>
                        </div>
                        <div class="custom-listing-amenities hide-scrollbar">
                            <?php foreach ($listing['amenities'] as $i => $amenity): ?>
                                <div class="custom-listing-amenity" title="<?php echo $amenity; ?>">
                                    <i class="fas <?php echo $listing['amenity_icons'][$i]; ?> text-blue-500"></i> <?php echo $amenity; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="custom-listing-profile">
                            <img src="<?php echo $listing['profile_img']; ?>" alt="User Profile" class="custom-listing-profile-img">
                            <div class="custom-listing-profile-info">
                                <span class="custom-listing-profile-name"><?php echo $listing['profile_name']; ?></span>
                                <span class="custom-listing-profile-status"><?php echo $listing['profile_status']; ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </section>
        <!-- end of listing section -->

        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
        <div class="flex justify-center items-center my-8 gap-2">
            <?php
            // First page button
            if ($page > 1) {
                echo '<a href="?page=1" class="px-3 py-1 border rounded text-gray-600 hover:bg-blue-50">First</a>';
            }
            // Prev button
            if ($page > 1) {
                echo '<a href="?page=' . ($page - 1) . '" class="px-3 py-1 border rounded text-gray-600 hover:bg-blue-50">Prev</a>';
            }

            // Calculate start and end page for 3-page window
            $startPage = max(1, $page - 1);
            $endPage = min($totalPages, $startPage + 2);
            if ($endPage - $startPage < 2) {
                $startPage = max(1, $endPage - 2);
            }
            for ($i = $startPage; $i <= $endPage; $i++) {
                echo '<a href="?page=' . $i . '" class="px-3 py-1 border rounded ' . ($i == $page ? 'bg-blue-600 text-white' : 'text-gray-600 hover:bg-blue-50') . '">' . $i . '</a>';
            }

            // Next button
            if ($page < $totalPages) {
                echo '<a href="?page=' . ($page + 1) . '" class="px-3 py-1 border rounded text-gray-600 hover:bg-blue-50">Next</a>';
            }
            // Last page button
            if ($page < $totalPages) {
                echo '<a href="?page=' . $totalPages . '" class="px-3 py-1 border rounded text-gray-600 hover:bg-blue-50">Last</a>';
            }
            ?>
        </div>
        <?php endif; ?>


        <!-- Connect with us section -->
        <a href="<?php echo APP_URL; ?>/contact">
            <section class="connect-section md:px-8">
                <div class="z-10 mb-8 md:mb-0 md:mr-8 flex-1">
                    <h2 class="connect-title">Connect with Us</h2>
                    <p class="connect-desc">Ready to bring your vision to life? Reach out and let's make it
                        happen!</p>
                </div>
                <a href="<?php echo APP_URL; ?>/contact" class="connect-btn">
                    <i class="fas fa-phone-alt mr-3"></i>
                    Contact Us
                </a>
                <!-- Decorative background shapes using root colors -->
                <div class="absolute inset-0 pointer-events-none">
                    <svg class="absolute right-0 bottom-0 w-80 h-40 opacity-5" viewBox="0 0 420 180" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <ellipse cx="340" cy="150" rx="80" ry="35" fill="#2563eb" />
                        <ellipse cx="230" cy="110" rx="50" ry="20" fill="#3b82f6" />
                        <ellipse cx="390" cy="170" rx="25" ry="10" fill="#60a5fa" />
                    </svg>
                    <svg class="absolute left-0 top-0 w-44 h-24 opacity-5" viewBox="0 0 220 120" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <ellipse cx="70" cy="50" rx="50" ry="20" fill="#2563eb" />
                        <ellipse cx="150" cy="30" rx="30" ry="12" fill="#3b82f6" />
                    </svg>
                </div>
            </section>
        </a>

        <?php include 'partials/footer.php' ?>
    </div>
</body>

</html>
<script>
function openFilterOverlay() {
    document.getElementById('filterOverlay').classList.remove('hidden');
    setTimeout(function() {
        document.getElementById('filterDrawer').classList.remove('translate-x-full');
    }, 10);
}
function closeFilterOverlay() {
    document.getElementById('filterDrawer').classList.add('translate-x-full');
    setTimeout(function() {
        document.getElementById('filterOverlay').classList.add('hidden');
    }, 300);
}
function applyFilter() {
    closeFilterOverlay();
    // Add filter logic here if needed
}
// Attach event listeners after DOM is loaded
window.addEventListener('DOMContentLoaded', function() {
    var filterBtn = document.querySelector('.btn-write.flex.items-center.gap-2.h-\\[44px\\].px-6');
    if (filterBtn) {
        filterBtn.addEventListener('click', function(e) {
            e.preventDefault();
            openFilterOverlay();
        });
    }
});
</script>