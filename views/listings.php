<?php require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../controllers/UserController.php';
require_once __DIR__ . '/../models/UserModel.php';
$userController = new UserController();
$userModel = new UserModel();
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$perPage = 24;
$start = ($page - 1) * $perPage;

// AJAX handler for filter
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest' && isset($_POST['action']) && $_POST['action'] === 'filter_listings') {
    $location = trim($_POST['location'] ?? '');
    $gender = trim($_POST['gender'] ?? '');
    $looking_for = trim($_POST['looking_for'] ?? '');
    $search = trim($_POST['search'] ?? '');
    // If search is set, use it as the location filter (elastic search)
    if ($search !== '') {
        $filtered = $userController->getListings(0, 100, $search, '', '');
    } else {
        $filtered = $userController->getListings(0, 100, $location, $gender, $looking_for);
    }
    if (empty($filtered)) {
        echo '<div class="col-span-3 text-center text-gray-500 text-xl py-16">No listings found.</div>';
    } else {
        foreach ($filtered as $listing) {
            ?>
            <a href="<?php echo APP_URL; ?>/roomlisting?id=<?php echo $listing['id']; ?>">
                <div class="custom-listing-card" style="text-decoration:none;color:inherit;">
                    <div class="relative flex-shrink-0">
                        <?php
                        $profileImg = UserModel::getProfileImageByUserId($listing['user_id']);
                        $profileImg = str_replace(['\\', '//'], '/', $profileImg);
                        if (strpos($profileImg, 'assets/') === 0) {
                            $localPath = str_replace('/', DIRECTORY_SEPARATOR, $profileImg);
                            if (!file_exists(__DIR__ . '/../' . $localPath)) {
                                $profileImg = 'assets/images/profile/user.png';
                            }
                        }
                        $mainImg = ($listing['role'] === 'roommate') ? $profileImg : $listing['image'];
                        ?>
                        <img src="<?php echo $mainImg; ?>" alt="Room Thumbnail"
                            class="w-full h-56 max-h-56 object-cover object-center">
                        <span
                            class="absolute top-3 left-3 bg-blue-600 text-white px-4 py-1 rounded-full text-sm font-semibold shadow"><?php echo $listing['price']; ?></span>
                    </div>
                    <div class="custom-listing-card-content">
                        <div class="custom-listing-location">
                            <i class="fas fa-map-marker-alt"></i>
                            <span class="block max-w-[160px] truncate align-middle"
                                title=\"<?php 
                                    $locationId = $listing['location'];
                                    $locationName = $userModel->getLocationNameById($locationId);
                                    echo htmlspecialchars($locationName ?? 'Location not found');
                                ?>\"><?php 
                                    $locationId = $listing['location'];
                                    $locationName = $userModel->getLocationNameById($locationId);
                                    echo htmlspecialchars($locationName ?? 'Location not found');
                                ?></span>
                        </div>
                        <div class="custom-listing-amenities hide-scrollbar">
                            <div class="custom-listing-amenity" title="Gender">
                                <i class="fas fa-venus-mars text-pink-500"></i>
                                Looking for : <?php echo ucfirst($listing['gender']); ?>
                            </div>
                            <?php
                            // Show vibe match percentage if logged in and not self, else show 'Set Your Vibes' button
                            $vibePercent = null;
                            $showSetVibes = false;
                            if (session_status() === PHP_SESSION_NONE)
                                session_start();
                            if (isset($_SESSION['user_id']) && $_SESSION['user_id'] != $listing['user_id']) {
                                $userVibes = $userController->hasUserSetVibes($_SESSION['user_id']);
                                if ($userVibes) {
                                    $vibePercent = $userController->getVibeMatchPercentage($_SESSION['user_id'], $listing['user_id']);
                                } else {
                                    $showSetVibes = true;
                                }
                            }
                            if ($vibePercent !== null) {
                                echo '<div class="flex justify-end mt-1">'
                                    . '<button class="vibe-match-percentage px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700 border border-blue-300 hover:bg-blue-200 transition-all">'
                                    . '<i class="fas fa-bolt mr-1 text-yellow-400"></i>Vibe Match: ' . $vibePercent . '%'
                                    . '</button></div>';
                            } elseif ($showSetVibes) {
                                echo '<div class="flex justify-end mt-1">'
                                    . '<a href="' . APP_URL . '/vibes" class="px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700 border border-yellow-300 hover:bg-yellow-200 transition-all">'
                                    . '<i class="fas fa-bolt mr-1 text-yellow-400"></i>Set Your Vibes'
                                    . '</a></div>';
                            }
                            ?>
                        </div>
                        <div class="custom-listing-profile">
                            <?php
                            $profileImg = UserModel::getProfileImageByUserId($listing['user_id']);
                            $profileImg = str_replace(['\\', '//'], '/', $profileImg);
                            if (strpos($profileImg, 'assets/') === 0) {
                                $localPath = str_replace('/', DIRECTORY_SEPARATOR, $profileImg);
                                if (!file_exists(__DIR__ . '/../' . $localPath)) {
                                    $profileImg = 'assets/images/profile/user.png';
                                }
                            }
                            ?>
                            <img src="<?php echo $profileImg; ?>" alt="User Profile" class="custom-listing-profile-img">
                            <div class="custom-listing-profile-info">
                                <span class="custom-listing-profile-name"><?php echo $listing['profile_name']; ?></span>
                                <span class="custom-listing-profile-status">
                                    <?php
                                    if (isset($listing['role'])) {
                                        if ($listing['role'] === 'room') {
                                            echo 'Looking for Roommate';
                                        } elseif ($listing['role'] === 'roommate') {
                                            echo 'Looking for Room';
                                        } elseif ($listing['role'] === 'pg') {
                                            echo 'Looking for Paying Guest';
                                        } elseif ($listing['role'] === 'tenant') {
                                            echo 'Looking for Tenant';
                                        } else {
                                            echo ucfirst($listing['role']);
                                        }
                                    }
                                    ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
            <?php
        }
    }
    exit;
}

// Only set these for normal (non-AJAX) requests
$listings = $userController->getListings($start, $perPage);
$totalListings = is_array($listings) ? count($listings) : 0;
$totalPages = $perPage > 0 ? ceil($totalListings / $perPage) : 1;
$paginatedListings = $listings;
?>

<?php
require_once __DIR__ . '/../models/City.php';
require_once __DIR__ . '/../config/database.php';
$pdo = getPDO();
$locationModel = new City($pdo);
$locations = $locationModel->getAllCities();
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

    <title>Listings |RoomLinks | Offical Website</title>

    <link rel="icon" type="image/x-icon" href="assets/logo/favicon.ico" />
    
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

<body class="flex flex-col min-h-fit">
    <div class="flex-grow flex flex-col min-h-[70vh]">

        <?php include 'partials/header.php' ?>

        <!-- start of listing section -->
        <section class="custom-listing-section">
            <!-- Filter Overlay (Drawer Style) -->
            <div id="filterOverlay" class="fixed inset-0 z-50 hidden">
                <div class="absolute inset-0 bg-black bg-opacity-40 transition-opacity duration-300"
                    onclick="closeFilterOverlay()"></div>
                <div id="filterDrawer"
                    class="fixed right-0 top-0 h-full w-full max-w-md bg-white rounded-l-2xl shadow-xl p-6 flex flex-col justify-between transform translate-x-full transition-transform duration-300">
                    <div>
                        <button onclick="closeFilterOverlay()"
                            class="absolute top-3 right-3 text-gray-400 hover:text-gray-700 text-2xl">&times;</button>
                        <h2 class="text-xl font-semibold mb-6 text-center">Filter Listings</h2>

                        <!-- Location -->

                        <div class="flex flex-col items-start gap-2 mb-6">
                            <label class="block mb-2 font-medium" for="location_filter_select">Location</label>
                            <select id="location_filter_select" name="location"
                                class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-200"
                                required>
                                <option value="">Select location</option>
                                <?php if (!empty($locations)): ?>
                                    <?php foreach ($locations as $loc): ?>
                                        <option value="<?= htmlspecialchars($loc['location']) ?>">
                                            <?= htmlspecialchars($loc['location']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <!-- Gender -->
                        <div class="mb-6">
                            <label class="block mb-2 font-medium">Gender</label>
                            <div class="select-group option-group">
                                <button type="button" class="select-btn" data-group="gender"
                                    data-value="male">Male</button>
                                <button type="button" class="select-btn" data-group="gender"
                                    data-value="female">Female</button>
                                <button type="button" class="select-btn" data-group="gender"
                                    data-value="any">Any</button>
                            </div>
                            <input type="hidden" name="gender" id="gender_input" value="any">
                        </div>
                        <!-- Looking for -->
                        <div class="mb-6">
                            <label class="block text-sm font-bold mb-1">Looking For</label>
                            <div class="select-group option-group">
                                <button type="button" class="select-btn" data-group="looking_for"
                                    data-value="room">Room</button>
                                <button type="button" class="select-btn" data-group="looking_for"
                                    data-value="pg">PG</button>
                                <button type="button" class="select-btn" data-group="looking_for"
                                    data-value="roommate">Roommate</button>
                            </div>
                            <input type="hidden" name="looking_for" id="looking_for_input" value="">
                        </div>
                        <div class="flex gap-2 mt-2">
                            <button class="btn-write m-0 w-1/2" type="button"
                                onclick="closeFilterOverlay()">Close</button>
                            <button class="btn-write w-1/2" type="button" onclick="applyFilter()">Apply Filter</button>
                        </div>
                    </div>
                    <div
                        class="mt-8 pt-4 border-t text-center text-sm  text-[--primary-color] rounded-b-2xl shadow-inner">
                        &copy; <?php echo date('Y'); ?> RoomLinks. All rights reserved.
                    </div>
                </div>
            </div>
            <!-- Search and Filter Bar -->
            <div class="flex flex-col items-center justify-center w-[90%] md:w-[95%] lg:w-full mb-8">
                <div class="flex flex-row items-center justify-center gap-4 w-full max-w-2xl">
                    <div class="hero-input-wrap w-full">
                        <i class="fas fa-search hero-search-icon"></i>
                        <input type="text" class="hero-input" id="searchInput" placeholder="Search your home here..." />
                        <script>
                            // On page load, if ?search= is present, fill the search bar and trigger elastic search
                            document.addEventListener('DOMContentLoaded', function () {
                                var urlParams = new URLSearchParams(window.location.search);
                                var searchVal = urlParams.get('search');
                                if (searchVal) {
                                    var searchInput = document.getElementById('searchInput');
                                    if (searchInput) {
                                        searchInput.value = searchVal;
                                        doElasticSearch();
                                    }
                                }
                            });
                        </script>
                    </div>
                    <button class="btn-write flex items-center gap-2 h-[44px] px-6" onclick="openFilterOverlay()">
                        <i class="fas fa-bars"></i>
                        <span>Filters</span>
                    </button>
                </div>
            </div>
            <div id="listingResults" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 custom-listing-cards">
                <?php
                if (empty($paginatedListings)) {
                    echo '<div class="col-span-3 text-center text-gray-500 text-xl py-16">No listings found.</div>';
                } else {
                    foreach ($paginatedListings as $listing): ?>
                        <a href="<?php echo APP_URL; ?>/roomlisting?id=<?php echo $listing['id']; ?>">
                            <div class="custom-listing-card" style="text-decoration:none;color:inherit;">
                                <div class="relative flex-shrink-0">
                                    <?php
                                    $profileImg = UserModel::getProfileImageByUserId($listing['user_id']);
                                    $profileImg = str_replace(['\\', '//'], '/', $profileImg);
                                    if (strpos($profileImg, 'assets/') === 0) {
                                        $localPath = str_replace('/', DIRECTORY_SEPARATOR, $profileImg);
                                        if (!file_exists(__DIR__ . '/../' . $localPath)) {
                                            $profileImg = 'assets/images/profile/user.png';
                                        }
                                    }
                                    $mainImg = ($listing['role'] === 'roommate') ? $profileImg : $listing['image'];
                                    ?>
                                    <img src="<?php echo $mainImg; ?>" alt="Room Thumbnail"
                                        class="w-full h-56 max-h-56 object-cover object-center">
                                    <span
                                        class="absolute top-3 left-3 bg-blue-600 text-white px-4 py-1 rounded-full text-sm font-semibold shadow"><?php echo $listing['price']; ?></span>
                                </div>
                                <div class="custom-listing-card-content">
                                    <div class="custom-listing-location">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <span
                                            class="block max-w-[190px] truncate align-middle"><?php 
                                                $locationId = $listing['location'];
                                                $locationName = $userModel->getLocationNameById($locationId);
                                                echo htmlspecialchars($locationName ?? 'Location not found');
                                            ?></span>
                                    </div>
                                    <div class="custom-listing-amenities hide-scrollbar">
                                        <div class="custom-listing-amenity" title="Gender">
                                            <i class="fas fa-venus-mars text-pink-500"></i>
                                            Looking for : <?php echo ucfirst($listing['gender']); ?>
                                        </div>
                                        <?php
                                        // Show vibe match percentage if logged in and not self, else show 'Set Your Vibes' button
                                        $vibePercent = null;
                                        $showSetVibes = false;
                                        if (session_status() === PHP_SESSION_NONE)
                                            session_start();
                                        if (isset($_SESSION['user_id']) && $_SESSION['user_id'] != $listing['user_id']) {
                                            // Check if logged-in user has set their vibes
                                            $userVibes = $userController->hasUserSetVibes($_SESSION['user_id']);
                                            if ($userVibes) {
                                                $vibePercent = $userController->getVibeMatchPercentage($_SESSION['user_id'], $listing['user_id']);
                                            } else {
                                                $showSetVibes = true;
                                            }
                                        }
                                        if ($vibePercent !== null) {
                                            echo '<div class="flex justify-end mt-1">'
                                                . '<button class="vibe-match-percentage px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700 border border-blue-300 hover:bg-blue-200 transition-all">'
                                                . '<i class="fas fa-bolt mr-1 text-yellow-400"></i>Vibe Match: ' . $vibePercent . '%'
                                                . '</button></div>';
                                        } elseif ($showSetVibes) {
                                            echo '<div class="flex justify-end mt-1">'
                                                . '<button type="button" onclick="event.stopPropagation(); event.preventDefault(); window.location=\'' . APP_URL . '/vibes\'" onmousedown="event.stopPropagation(); event.preventDefault();" class="px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700 border border-yellow-300 hover:bg-yellow-200 transition-all">'
                                                . '<i class="fas fa-bolt mr-1 text-yellow-400"></i>Set Your Vibes'
                                                . '</button></div>';
                                        }
                                        ?>
                                    </div>
                                    <div class="custom-listing-profile">
                                        <?php
                                        $profileImg = UserModel::getProfileImageByUserId($listing['user_id']);
                                        $profileImg = str_replace(['\\', '//'], '/', $profileImg);
                                        if (strpos($profileImg, 'assets/') === 0) {
                                            $localPath = str_replace('/', DIRECTORY_SEPARATOR, $profileImg);
                                            if (!file_exists(__DIR__ . '/../' . $localPath)) {
                                                $profileImg = 'assets/images/profile/user.png';
                                            }
                                        }
                                        ?>
                                        <img src="<?php echo $profileImg; ?>" alt="User Profile"
                                            class="custom-listing-profile-img">
                                        <div class="custom-listing-profile-info">
                                            <span
                                                class="custom-listing-profile-name"><?php echo $listing['profile_name']; ?></span>
                                            <span class="custom-listing-profile-status">
                                                <?php
                                                if (isset($listing['role'])) {
                                                    if ($listing['role'] === 'room') {
                                                        echo 'Looking for Roommate';
                                                    } elseif ($listing['role'] === 'roommate') {
                                                        echo 'Looking for Room';
                                                    } elseif ($listing['role'] === 'pg') {
                                                        echo 'Looking for Paying Guest';
                                                    } elseif ($listing['role'] === 'tenant') {
                                                        echo 'Looking for Tenant';
                                                    } else {
                                                        echo ucfirst($listing['role']);
                                                    }
                                                }
                                                ?>
                                            </span>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </a>
                    <?php endforeach;
                }
                ?>
            </div>
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


<script>
    function openFilterOverlay() {
        document.getElementById('filterOverlay').classList.remove('hidden');
        setTimeout(function () {
            document.getElementById('filterDrawer').classList.remove('translate-x-full');
        }, 10);
    }
    function closeFilterOverlay() {
        document.getElementById('filterDrawer').classList.add('translate-x-full');
        setTimeout(function () {
            document.getElementById('filterOverlay').classList.add('hidden');
        }, 300);
    }
    function applyFilter() {
        closeFilterOverlay();
        var location = document.getElementById('location_filter_select').value;
        var gender = document.getElementById('gender_input').value;
        var looking_for = document.getElementById('looking_for_input').value;
        var xhr = new XMLHttpRequest();
        xhr.open('POST', window.location.href, true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                document.getElementById('listingResults').innerHTML = xhr.responseText;
            }
        };
        var params = 'action=filter_listings'
            + '&location=' + encodeURIComponent(location)
            + '&gender=' + encodeURIComponent(gender)
            + '&looking_for=' + encodeURIComponent(looking_for)
            + '&search=';
        xhr.send(params);
    }

    // Elastic search for location
    document.addEventListener('DOMContentLoaded', function () {
        var searchInput = document.getElementById('searchInput');
        if (searchInput) {
            searchInput.addEventListener('keydown', function (e) {
                if (e.key === 'Enter') {
                    doElasticSearch();
                }
            });
            // Optional: debounce for live search
            /*
            let debounceTimer;
            searchInput.addEventListener('input', function () {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(doElasticSearch, 400);
            });
            */
        }
    });
    function doElasticSearch() {
        var search = document.getElementById('searchInput').value;
        var xhr = new XMLHttpRequest();
        xhr.open('POST', window.location.href, true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                document.getElementById('listingResults').innerHTML = xhr.responseText;
            }
        };
        var params = 'action=filter_listings&search=' + encodeURIComponent(search);
        xhr.send(params);
    }
    // Button group logic for filter overlay
    function setupButtonGroup(groupName, defaultValue) {
        const btns = document.querySelectorAll('.select-btn[data-group="' + groupName + '"]');
        const input = document.getElementById(groupName + "_input");
        btns.forEach(btn => {
            btn.addEventListener('click', function () {
                if (this.classList.contains('selected')) {
                    // Deselect if already selected
                    this.classList.remove('selected');
                    input.value = '';
                } else {
                    btns.forEach(b => b.classList.remove('selected'));
                    this.classList.add('selected');
                    input.value = this.dataset.value;
                }
                // Auto-apply filter on any change
                applyFilter();
            });
        });
        // Set default
        let defaultBtn = Array.from(btns).find(b => b.dataset.value === defaultValue);
        if (defaultBtn && defaultValue !== '') {
            defaultBtn.classList.add('selected');
            input.value = defaultValue;
        } else {
            input.value = '';
        }
    }
    window.addEventListener('DOMContentLoaded', function () {
        var filterBtn = document.querySelector('.btn-write.flex.items-center.gap-2.h-\\[44px\\].px-6');
        if (filterBtn) {
            filterBtn.addEventListener('click', function (e) {
                e.preventDefault();
                openFilterOverlay();
            });
        }
        // Setup button group for 'Gender' and 'Looking For' filters
        setupButtonGroup('gender', '');
        setupButtonGroup('looking_for', '');
    });
</script>

</html>