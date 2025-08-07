<!-- ========== Topbar Start ========== -->
<div class="navbar-custom">
    <div class="topbar">
        <div class="topbar-menu d-flex align-items-center gap-lg-2 gap-1">

            <!-- Brand Logo -->
            <div class="logo-box">
                <!-- Brand Logo Light -->
                <a class='logo-light' href='index.html'>
                    <img src="<?php echo APP_URL; ?>/admin-asset/images/logo.png" alt="logo" class="logo-lg"
                        height="22">
                    <img src="<?php echo APP_URL; ?>/admin-asset/images/logo.png" alt="small logo" class="logo-sm"
                        height="50">
                </a>

                <!-- Brand Logo Dark -->
                <a class='logo-dark' href='index.html'>
                    <img src="<?php echo APP_URL; ?>/admin-asset/images/logo.png" alt="dark logo" class="logo-lg"
                        height="22">
                    <img src="<?php echo APP_URL; ?>/admin-asset/images/logo.png" alt="small logo" class="logo-sm"
                        height="50">
                </a>
            </div>

            <!-- Sidebar Menu Toggle Button -->
            <button class="button-toggle-menu">
                <i class="mdi mdi-menu"></i>
            </button>
            
        </div>

        <ul class="topbar-menu d-flex align-items-center gap-4">

            <li class="d-none d-md-inline-block">
                <a class="nav-link" href="" data-bs-toggle="fullscreen">
                    <i class="mdi mdi-fullscreen font-size-24"></i>
                </a>
            </li>
            <li class="nav-link" id="theme-mode">
                <i class="bx bx-moon font-size-24"></i>
            </li>
            <li class="dropdown">
                <a class="nav-link dropdown-toggle nav-user me-0 waves-effect waves-light" data-bs-toggle="dropdown"
                    href="#" role="button" aria-haspopup="false" aria-expanded="false">
                    <i class="bx bx-user-circle float-end m-0 h2 text-muted"></i>
                    <span class="ms-1 d-none d-md-inline-block">
                        <?= $_SESSION['user_name'] ?? 'Admin' ?> <i class="mdi mdi-chevron-down"></i>
                    </span>
                </a>
                <div class="dropdown-menu dropdown-menu-end profile-dropdown">
                    <!-- Header -->
                    <div class="dropdown-header noti-title">
                        <h6 class="text-overflow m-0">Welcome!</h6>
                    </div>

                    <!-- Profile -->
                    <a href="<?php echo APP_URL; ?>/profile" class="dropdown-item notify-item">
                        <i class="bi bi-person me-2"></i>
                        <span>Profile</span>
                    </a>

                    <div class="dropdown-divider"></div>

                    <!-- Logout -->
                    <a class="dropdown-item notify-item" href="<?php echo APP_URL; ?>/logout">
                        <i class="bi bi-box-arrow-right me-2"></i>
                        <span>Logout</span>
                    </a>
                </div>
            </li>



        </ul>
    </div>
</div>
<!-- ========== Topbar End ========== -->