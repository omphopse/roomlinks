<!-- ========== Left Sidebar ========== -->
<div class="main-menu">
    <!-- Brand Logo -->
    <div class="logo-box">
        <?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
        <!-- Brand Logo Light -->
        <a class='logo-light' href='<?php echo APP_URL; ?><?php echo (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'broker') ? '/dashboard' : '/admin/dashboard'; ?>'>
            <img src="<?php echo APP_URL; ?>/admin-asset/images/logo.png" alt="logo" class="logo-lg" height="50">
            <img src="<?php echo APP_URL; ?>/admin-asset/images/logo.png" alt="small logo" class="logo-sm" height="25">
        </a>

        <!-- Brand Logo Dark -->
        <a class='logo-dark' href='<?php echo APP_URL; ?><?php echo (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'broker') ? '/dashboard' : '/admin/dashboard'; ?>'>
            <img src="<?php echo APP_URL; ?>/admin-asset/images/logo.png" alt="dark logo" class="logo-lg" height="100">
            <img src="<?php echo APP_URL; ?>/admin-asset/images/logo.png" alt="small logo" class="logo-sm" height="50">
        </a>
    </div>

    <!--- Menu -->
    <div data-simplebar>
        <ul class="app-menu">
            <li class="menu-title">Menu</li>

            <?php 
            // Check if user is a broker. Prefer session role, but fall back to profile if needed.
            $isBroker = false;
            if (isset($_SESSION['user_role']) && strtolower(trim($_SESSION['user_role'])) === 'broker') {
                $isBroker = true;
            } elseif (isset($_SESSION['user_id'])) {
                require_once __DIR__ . '/../../models/UserModel.php';
                $userModel = new UserModel();
                $profile = $userModel->getProfileById($_SESSION['user_id']);
                if (strtolower(trim($profile['who_are_you'] ?? '')) === 'broker') {
                    $isBroker = true;
                    $_SESSION['user_role'] = 'broker';
                }
            }
            ?>

            <?php if ($isBroker): ?>
            <!-- Broker Menu Items - Only Dashboard, Listings, and Home -->
            <li class="menu-item">
                <a class='menu-link waves-effect' href="<?php echo APP_URL; ?>/dashboard">
                    <span class="menu-icon"><i class="bx bx-home-smile"></i></span>
                    <span class="menu-text"> Dashboard </span>
                </a>
            </li>

            <li class="menu-item">
                <a class='menu-link waves-effect' href="<?php echo APP_URL; ?>/dashboard/listings">
                    <span class="menu-icon"><i class="bx bx-building-house"></i></span>
                    <span class="menu-text"> Listings </span>
                </a>
            </li>

            <li class="menu-item">
                <a class='menu-link waves-effect' href="<?php echo APP_URL; ?>/dashboard/users">
                    <span class="menu-icon"><i class="bx bx-user-circle"></i></span>
                    <span class="menu-text"> Users </span>
                </a>
            </li>

            <li class="menu-item">
                <a class='menu-link waves-effect' href="<?php echo APP_URL; ?>/home">
                    <span class="menu-icon"><i class="bx bxs-left-arrow-square"></i></span>
                    <span class="menu-text"> Home </span>
                </a>
            </li>

            <?php else: ?>
            <!-- Admin Menu Items - All options -->
            <li class="menu-item">
                <a class='menu-link waves-effect' href="<?php echo APP_URL; ?>/admin/dashboard">
                    <span class="menu-icon"><i class="bx bx-home-smile"></i></span>
                    <span class="menu-text"> Dashboard </span>
                </a>
            </li>

            <li class="menu-item">
                <a class='menu-link waves-effect' href="<?php echo APP_URL; ?>/admin/users">
                    <span class="menu-icon"><i class="bx bx-user-circle"></i></span>
                    <span class="menu-text"> users </span>
                </a>
            </li>

            <li class="menu-item">
                <a class='menu-link waves-effect' href="<?php echo APP_URL; ?>/admin/listings">
                    <span class="menu-icon"><i class="bx bx-building-house"></i></span>
                    <span class="menu-text"> listings </span>
                </a>
            </li>
            <li class="menu-item">
                <a class='menu-link waves-effect' href="<?php echo APP_URL; ?>/admin/contact">
                    <span class="menu-icon"><i class="bx bxs-contact"></i></span>
                    <span class="menu-text"> Contact </span>
                </a>
            </li>

            <li class="menu-item">
                <a class='menu-link waves-effect' href="<?php echo APP_URL; ?>/admin/reports">
                    <span class="menu-icon"><i class="bx bx-support"></i></span>
                    <span class="menu-text"> Reports </span>
                </a>
            </li>

            <li class="menu-item">
                <a class='menu-link waves-effect' href="<?php echo APP_URL; ?>/admin/highlights">
                    <span class="menu-icon"><i class="bx bx-building-house"></i></span>
                    <span class="menu-text"> Highlights </span>
                </a>
            </li>

            <li class="menu-item">
                <a class='menu-link waves-effect' href="<?php echo APP_URL; ?>/admin/cities">
                    <span class="menu-icon"><i class="bx bx-map"></i></span>
                    <span class="menu-text"> Cities </span>
                </a>
            </li>

            <li class="menu-item">
                <a class='menu-link waves-effect' href="<?php echo APP_URL; ?>/admin/locations">
                    <span class="menu-icon"><i class="bx bx-location-plus"></i></span>
                    <span class="menu-text"> Locations </span>
                </a>
            </li>

            <li class="menu-item">
                <a class='menu-link waves-effect' href="<?php echo APP_URL; ?>/admin/profilepictures">
                    <span class="menu-icon"><i class="bx bx-user"></i></span>
                    <span class="menu-text"> Profile Picture </span>
                </a>
            </li>

            <li class="menu-item">
                <a class='menu-link waves-effect' href="<?php echo APP_URL; ?>/admin/vibes">
                    <span class="menu-icon"><i class="bx bxs-heart"></i></span>
                    <span class="menu-text"> Vibes </span>
                </a>
            </li>

            <li class="menu-item">
                <a class='menu-link waves-effect' href="<?php echo APP_URL; ?>/admin/amenities">
                    <span class="menu-icon"><i class="bx bxs-building"></i></span>
                    <span class="menu-text"> amenities </span>
                </a>
            </li>

            <li class="menu-item">
                <a class='menu-link waves-effect' href="<?php echo APP_URL; ?>/admin/feedback">
                    <span class="menu-icon"><i class="bx bx-conversation"></i></span>
                    <span class="menu-text"> Feedback </span>
                </a>
            </li>

            <li class="menu-item">
                <a class='menu-link waves-effect' href="<?php echo APP_URL; ?>/home">
                    <span class="menu-icon"><i class="bx bxs-left-arrow-square"></i></span>
                    <span class="menu-text"> Home </span>
                </a>
            </li>
            <?php endif; ?>
        </ul>
    </div>
</div>