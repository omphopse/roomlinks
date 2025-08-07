<?php
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../helpers/auth.php';
checkAuth();
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

    <title>Mylistings| RoomLinks | Offical Website</title>

    <link rel="icon" type="image/x-icon" href="assets/logo/favicon.ico" />

    

</head>

<body class="flex flex-col min-h-screen">
    <div class="flex-grow flex flex-col min-h-[70vh]">

        <?php include 'partials/header.php' ?>

        <!-- start of listing section -->
        <section class="custom-listing-section">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 custom-listing-cards">
                <?php
                if (empty($listings)) {
                    echo '<div class="col-span-3 text-center text-gray-500 text-xl py-16">No listings found.</div>';
                } else {
                    foreach ($listings as $listing): ?>
                    <a href="<?php echo APP_URL; ?>/roomlisting?id=<?php echo $listing['id']; ?>">
                        <div class="custom-listing-card" style="text-decoration:none;color:inherit;">
                            <div class="relative flex-shrink-0">
                                <?php
                                    require_once __DIR__ . '/../models/UserModel.php';
                                    $profileImg = UserModel::getProfileImageByUserId($listing['user_id']);
                                    $profileImg = str_replace(['\\', '//'], '/', $profileImg);
                                    if (strpos($profileImg, 'assets/') === 0) {
                                        $localPath = str_replace('/', DIRECTORY_SEPARATOR, $profileImg);
                                        if (!file_exists(__DIR__ . '/../' . $localPath)) {
                                            $profileImg = 'assets/images/profile/user.png';
                                        }
                                    }
                                    $mainImg = ($listing['role'] === 'roommate') 
                                        ? $profileImg 
                                        : (isset($listing['image1']) && !empty($listing['image1']) ? $listing['image1'] : 'assets/images/profile/user.png');
                                ?>
                                <img src="<?php echo $mainImg; ?>" alt="Room Thumbnail"
                                    class="w-full h-56 max-h-56 object-cover object-center">
                                <span
                                    class="absolute top-3 left-3 bg-blue-600 text-white px-4 py-1 rounded-full text-sm font-semibold shadow">
                                    <?php echo isset($listing['rent']) ? $listing['rent'] : 'N/A'; ?>
                                </span>
                            </div>
                            <div class="custom-listing-card-content">
                                <div class="custom-listing-location">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span><?php echo $listing['location']; ?></span>
                                </div>
                                <div class="custom-listing-amenities hide-scrollbar">
                                    <div class="custom-listing-amenity" title="Gender">
                                        <i class="fas fa-venus-mars text-pink-500"></i>
                                        Looking for : <?php echo ucfirst($listing['gender']); ?>
                                    </div>
                                </div>
                                <div class="custom-listing-profile">
                                     <?php
                                         require_once __DIR__ . '/../models/UserModel.php';
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
                                        <span
                                            class="custom-listing-profile-status">
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
        // Add filter logic here if needed
    }
    // Button group logic for filter overlay
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
    window.addEventListener('DOMContentLoaded', function () {
        var filterBtn = document.querySelector('.btn-write.flex.items-center.gap-2.h-\\[44px\\].px-6');
        if (filterBtn) {
            filterBtn.addEventListener('click', function (e) {
                e.preventDefault();
                openFilterOverlay();
            });
        }
        // Setup button group for 'Gender' and 'Looking For' filters
        setupButtonGroup('gender', 'any');
        setupButtonGroup('looking_for', '');
    });
</script>