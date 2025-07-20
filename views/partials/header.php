<!-- header section starts -->
<header class="header-main" >
    <div class="top-bar-main">
        <h1 class="top-bar-title" >Vibe,</span>
            Find your <span class="text-[#FFF176]">Flatmate</span> – it’s that <span
                class="text-[#FFF176]">Simple!</span>
        </h1>
    </div>
    <nav class="nav-bar mt-5 px-5 lg:justify-evenly py-5 flex justify-between items-center h-[50px]">
        <div class="logo-main">
            <a href="<?php echo APP_URL; ?>/" >
                <h1 class="logo-title"><span
                        class="logo-title-highlight">RoomLinks</span></h1>
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
            ?>
                <a href="<?php echo APP_URL; ?>/addnewlisting" class="btn-add-listing relative bg-yellow-300 rounded-2xl px-4 py-2 font-medium text-gray-800 items-center hidden md:inline-flex">
                    + Add Listing
                    <span class="absolute -top-2 -right-2 bg-green-700 text-white rounded-lg px-2 py-0.5 text-xs font-semibold">FREE</span>
                </a>
                <div class="relative">
                    <a href="<?php echo APP_URL; ?>/profile" class="block md:hidden">
                        <img src="<?php echo APP_URL . '/' . $avatarUrl; ?>" alt="Profile" class="w-12 h-12 rounded-full object-cover shadow-md border-2 border-yellow-300 cursor-pointer" />
                    </a>
                    <img src="<?php echo APP_URL . '/' . $avatarUrl; ?>" alt="Profile" class="w-12 h-12 rounded-full object-cover shadow-md border-2 border-yellow-300 cursor-pointer hidden md:block" id="profileDropdownBtn" />
                    <div class="hidden absolute right-0 mt-2 w-64 bg-white rounded-xl shadow-lg z-50 border border-gray-100" id="profileDropdownMenu">
                        <div class="px-6 py-4 border-b">
                            <div class="font-semibold text-lg text-gray-700">Hi <?php echo !empty($profile['name']) ? htmlspecialchars($profile['name']) : 'Guest'; ?></div>
                        </div>
                        <ul class="py-2 text-gray-600">
                            <li><a href="#" class="block px-6 py-2 hover:bg-gray-50">My Vibes</a></li>
                            <li><a href="<?php echo APP_URL; ?>/profile" class="block px-6 py-2 hover:bg-gray-50">My Profile</a></li>
                            <li><a href="mailto:report@roomlinks.in" class="block px-6 py-2 hover:bg-gray-50">Need Help?</a></li>
                            <li><a href="<?php echo APP_URL; ?>/logout" class="block px-6 py-2 text-red-500 hover:bg-gray-50">Logout</a></li>
                        </ul>
                    </div>
                </div>
                <script>
                // Show dropdown on click (for md+ screens only)
                document.addEventListener('DOMContentLoaded', function() {
                    var btn = document.getElementById('profileDropdownBtn');
                    var menu = document.getElementById('profileDropdownMenu');
                    function isDesktop() {
                        return window.innerWidth >= 768;
                    }
                    if (btn && menu) {
                        btn.addEventListener('click', function(e) {
                            if (isDesktop()) {
                                e.stopPropagation();
                                menu.classList.toggle('hidden');
                            }
                        });
                        document.addEventListener('click', function(e) {
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
<!-- header section ends -->