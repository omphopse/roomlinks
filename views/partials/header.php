<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<!-- header section starts -->
<header class="header-main">
    <div class="top-bar-main">
        <h1 class="top-bar-title">
            Find your <span class="text-[#FFF176]">Vibe,</span> Find your <span class="text-[#FFF176]">Flatmate</span> – it’s that <span
                class="text-[#FFF176]">Simple!</span>
        </h1>
    </div>
    <nav class="nav-bar mt-5 px-5 lg:justify-evenly py-5 flex justify-between items-center h-[50px]">
        <div class="logo">
            <a href="<?php echo APP_URL; ?>/">
                <img src="<?php echo APP_URL; ?>/assets/logo/logo.png" alt="RoomLinks Logo" class="h-12 w-auto" />
            </a>
        </div>

        <div class="btn-container-main">
            <div style="display: flex; align-items: center; gap: 16px;">
                <?php
                require_once __DIR__ . '/../../helpers/auth.php';
                require_once __DIR__ . '/../../controllers/UserController.php';
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
                if (!empty($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
                    $userId = $_SESSION['user_id'];
                    $userController = new UserController();
                    $profile = $userController->getProfileById($userId);
                    $avatarUrl = 'assets/profile/default-avatar.png';
                    if (!empty($profile['uploaded_picture'])) {
                        $avatarUrl = $profile['uploaded_picture'];
                    } elseif (!empty($profile['avatar_image'])) {
                        $avatarUrl = $profile['avatar_image'];
                    }
                    elseif (!empty($profile['avatar'])) {
                        $avatarUrl = 'assets/profile/user.png';
                    }
                    ?>
                    <?php
                    $listingUrl = APP_URL . '/addnewlisting';
                    if (!empty($profile['who_are_you']) && strtolower($profile['who_are_you']) !== 'roommate') {
                        $listingUrl = APP_URL . '/roommate';
                    }
                    ?>
                    <a href="<?php echo $listingUrl; ?>"
                        class="btn-add-listing relative bg-yellow-300 rounded-2xl px-4 py-2 font-medium text-gray-800 items-center hidden md:inline-flex">
                        + Add Listing
                        <span
                            class="absolute -top-2 -right-2 bg-green-700 text-white rounded-lg px-2 py-0.5 text-xs font-semibold">FREE</span>
                    </a>
                    <div class="relative">
                        <a href="<?php echo APP_URL; ?>/profile" class="block md:hidden">
                            <img src="<?php echo APP_URL . '/' . $avatarUrl; ?>" alt="Profile"
                                class="w-12 h-12 rounded-full object-cover shadow-md border-2 border-yellow-300 cursor-pointer" />
                        </a>
                        <img src="<?php echo APP_URL . '/' . $avatarUrl; ?>" alt="Profile"
                            class="w-12 h-12 rounded-full object-cover shadow-md border-2 border-yellow-300 cursor-pointer hidden md:block"
                            id="profileDropdownBtn" />
                        <div class="hidden absolute right-0 mt-2 w-64 bg-white rounded-xl shadow-lg z-50 border border-gray-100"
                            id="profileDropdownMenu">
                            <div class="px-6 py-4 border-b">
                                <div class="font-semibold text-lg text-gray-700">Hi
                                    <?php echo !empty($profile['name']) ? htmlspecialchars($profile['name']) : 'Guest'; ?>
                                </div>
                            </div>
                            <ul class="py-2 text-gray-600">
                                <?php if (empty($profile['who_are_you']) || strtolower($profile['who_are_you']) !== 'broker'): ?>
                                <li><a href="<?php echo APP_URL; ?>/vibes" class="block px-6 py-2 hover:bg-gray-50">My
                                        Vibes</a></li>
                                <?php endif; ?>
                                <li><a href="<?php echo APP_URL; ?>/profile" class="block px-6 py-2 hover:bg-gray-50">My
                                        Profile</a></li>
                                <?php if (empty($profile['who_are_you']) || strtolower($profile['who_are_you']) !== 'broker'): ?>
                                <li><a href="<?php echo APP_URL; ?>/mylistings" class="block px-6 py-2 hover:bg-gray-50">My
                                        Listings</a></li>
                                <?php endif; ?>
                                <?php if (!empty($profile['who_are_you']) && strtolower($profile['who_are_you']) === 'broker'): ?>
                                <li><a href="<?php echo APP_URL; ?>/dashboard" class="block px-6 py-2 hover:bg-gray-50">Dashboard</a></li>
                                <?php endif; ?>
                                <li><a href="<?php echo APP_URL; ?>/feedback" class="block px-6 py-2 hover:bg-gray-50">Write
                                        a FeedBack</a></li>
                                <li><a href=" <?php echo APP_URL; ?>/contact" class="block px-6 py-2 hover:bg-gray-50">Need
                                        Help?</a></li>
                                <li><a href="<?php echo APP_URL; ?>/logout"
                                        class="block px-6 py-2 text-red-500 hover:bg-gray-50">Logout</a></li>
                            </ul>
                        </div>
                    </div>
                    <script>
                        // Show dropdown on click (for md+ screens only)
                        document.addEventListener('DOMContentLoaded', function () {
                            var btn = document.getElementById('profileDropdownBtn');
                            var menu = document.getElementById('profileDropdownMenu');
                            function isDesktop() {
                                return window.innerWidth >= 768;
                            }
                            if (btn && menu) {
                                btn.addEventListener('click', function (e) {
                                    if (isDesktop()) {
                                        e.stopPropagation();
                                        menu.classList.toggle('hidden');
                                    }
                                });
                                document.addEventListener('click', function (e) {
                                    if (isDesktop() && !btn.contains(e.target) && !menu.contains(e.target)) {
                                        menu.classList.add('hidden');
                                    }
                                });
                            }
                        });
                    </script>
                    <?php
                } else {
                    ?>
                    <a href="#" class="btn-write" onclick="showAuthModal('login'); return false;">Login</a>
                    <a href="#" class="btn-views" onclick="showAuthModal('register'); return false;">Register</a>
                    <?php
                }
                ?>
            </div>
        </div>
    </nav>
</header>

<!-- Mobile Bottom Navigation -->
<?php if (!empty($_SESSION['logged_in']) && $_SESSION['logged_in'] === true): ?>
<nav id="mobileBottomNav"
    class="fixed bottom-0 left-0 right-0 z-50 bg-white border-t border-gray-200 shadow-lg flex items-center py-2 md:hidden transition-transform duration-500 ease-in-out rounded-t-2xl gap-2"
    style="transform: translateY(0);">
    <a href="<?php echo APP_URL; ?>/listings"
        class="flex-1 flex flex-col items-center text-gray-600 hover:text-[var(--primary-color)]">
        <span class="material-icons">search</span>
        <span class="text-xs">Veiw Listings</span>
    </a>
    <a href="<?php echo $listingUrl; ?>"
        class="flex-1 flex flex-col items-center text-gray-600 hover:text-[var(--primary-color)] relative">
        <span class="relative flex items-center justify-center">
            <span class="material-icons">home</span>
            <span class="absolute -top-2 left-1/2 -translate-x-1/2 bg-green-600 text-white rounded-lg px-2 py-0.5 text-[0.60rem] font-semibold whitespace-nowrap">FREE</span>
        </span>
        <span class="text-xs">Add Listing</span>
    </a>
    <a href="<?php echo APP_URL; ?>/feedback"
        class="flex-1 flex flex-col items-center text-gray-600 hover:text-[var(--primary-color)]">
        <span class="material-icons">chat</span>
        <span class="text-xs">FeedBack</span>
    </a>
    <a href="<?php echo APP_URL; ?>/more"
        class="flex-1 flex flex-col items-center text-gray-600 hover:text-[var(--primary-color)]">
        <span class="material-icons">more_horiz</span>
        <span class="text-xs">More</span>
    </a>
</nav>
<?php endif; ?>
<!-- header section ends -->

<script>
    // Mobile bottom nav hide on scroll down, show on scroll up (smooth animation)
    document.addEventListener('DOMContentLoaded', function () {
        const nav = document.getElementById('mobileBottomNav');
        if (!nav) return;
        let lastScrollY = window.scrollY;
        let ticking = false;
        function isMobile() {
            return window.innerWidth < 768;
        }
        function onScroll() {
            if (!isMobile()) {
                nav.classList.remove('hide');
                nav.style.transform = 'translateY(0)';
                return;
            }
            if (!ticking) {
                window.requestAnimationFrame(function () {
                    if (window.scrollY <= 0) {
                        nav.classList.remove('hide');
                        nav.style.transform = 'translateY(0)';
                    } else if (window.scrollY > lastScrollY && window.scrollY > 60) {
                        nav.classList.add('hide');
                        nav.style.transform = 'translateY(100%)';
                    } else if (window.scrollY < lastScrollY) {
                        nav.classList.remove('hide');
                        nav.style.transform = 'translateY(0)';
                    }
                    lastScrollY = window.scrollY;
                    ticking = false;
                });
                ticking = true;
            }
        }
        window.addEventListener('scroll', onScroll);
        window.addEventListener('resize', function() {
            if (!isMobile()) {
                nav.classList.remove('hide');
                nav.style.transform = 'translateY(0)';
            }
        });
    });
</script>