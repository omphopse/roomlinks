<?php require_once __DIR__ . '/../../config/constants.php'; ?>
<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../models/UserModel.php';
require_once __DIR__ . '/../../helpers/auth.php';

$userModel = new UserModel();
$user = null;
if (isset($_SESSION['user_id'])) {
    $user = $userModel->getAdminById($_SESSION['user_id']);
}

checkAdminAuth();
// All dashboard data is now provided by AdminController
?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="light" data-menu-color="dark" data-topbar-color="light">

<head>
    <meta charset="utf-8" />
    <title>Admin Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="<?php echo APP_URL; ?>/admin-asset/libs/morris.js/morris.css" rel="stylesheet" type="text/css" />

    <link rel="icon" type="image/x-icon" href="<?php echo APP_URL; ?>/admin-asset/images/favicon.ico" />

    <link rel="icon" type="image/x-icon" href="<?php echo APP_URL; ?>/admin-asset/images/favicon.ico" />

    <!-- third party css -->
    <link href="<?php echo APP_URL; ?>/admin-asset/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css"
        rel="stylesheet" type="text/css" />
    <link href="<?php echo APP_URL; ?>/admin-asset/libs/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css"
        rel="stylesheet" type="text/css" />
    <link href="<?php echo APP_URL; ?>/admin-asset/libs/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css"
        rel="stylesheet" type="text/css" />
    <link href="<?php echo APP_URL; ?>/admin-asset/libs/datatables.net-select-bs5/css/select.bootstrap5.min.css"
        rel="stylesheet" type="text/css" />
    <!-- third party css end -->

    <!-- App css -->
    <link href="<?php echo APP_URL; ?>/admin-asset/css/style.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo APP_URL; ?>/admin-asset/css/icons.min.css" rel="stylesheet" type="text/css">
    <script src="<?php echo APP_URL; ?>/admin-asset/js/config.js"></script>

</head>

<body>

    <!-- Begin page -->
    <div class="layout-wrapper">

        <?php include __DIR__ . '/../partials/admin_nav.php'; ?>

        <!-- ============================================================== -->
        <!-- Start Page Content here -->
        <!-- ============================================================== -->

        <div class="page-content">

            <?php include __DIR__ . '/../partials/topbar.php'; ?>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success"><?= $_SESSION['success'];
                unset($_SESSION['success']); ?></div>
            <?php endif; ?>

            <div class="px-3">

                <!-- Start Content-->
                <div class="container-fluid">

                    <!-- start page title -->
                    <div class="py-3 py-lg-4">
                        <div class="row">
                            <div class="col-lg-6">
                                <h4 class="page-title mb-0">Dashboard</h4>
                            </div>
                            <div class="col-lg-6">
                                <div class="d-none d-lg-block">
                                    <ol class="breadcrumb m-0 float-end">
                                        <li class="breadcrumb-item"><a
                                                href="<?php echo APP_URL; ?>/admin/dashboard">Admin</a></li>
                                        <li class="breadcrumb-item active">Dashboard</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end page title -->

                    <div class="row">
                        <div class="col-xl-3 col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <i class="bx bx-group float-end m-0 h2 text-muted"></i>
                                    <h4 class="text-muted text-uppercase mt-0">Total Users</h4>
                                    <h3 class="mb-3"><span data-plugin="counterup"><?php echo $totalUsers; ?></span></h3>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <i class="bx bx-building-house float-end m-0 h2 text-muted"></i>
                                    <h4 class="text-muted text-uppercase mt-0">Total Listings</h4>
                                    <h3 class="mb-3"><span data-plugin="counterup"><?php echo $totalListings; ?></span></h3>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <i class="bx bx-home-alt float-end m-0 h2 text-muted"></i>
                                    <h4 class="text-muted text-uppercase mt-0">Total Rooms</h4>
                                    <h3 class="mb-3"><span data-plugin="counterup"><?php echo $totalRooms; ?></span></h3>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <i class="bx bx-building float-end m-0 h2 text-muted"></i>
                                    <h4 class="text-muted text-uppercase mt-0">Total PG's</h4>
                                    <h3 class="mb-3"> <span data-plugin="counterup"><?php echo $totalPGs; ?> </span></h3>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end row -->

                    <div class="row">
                        <div class="col-xl-6">
                            <div class="card">
                                <div class="card-body">

                                    <h4 class="card-title overflow-hidden">Recent Listings</h4>

                                    <div class="table-responsive">
                                        <table class="table table-centered table-hover table-xl mb-0" id="recent-orders">
                                            <thead>
                                                <tr>
                                                    <th class="border-top-0">User Name</th>
                                                    <th class="border-top-0">Listing Role</th>
                                                    <th class="border-top-0">Contact Number</th>
                                                    <th class="border-top-0">Location</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($recentListings as $listing): ?>
                                                    <tr>
                                                        <td><?php echo htmlspecialchars($listing['user_name'] ?? ''); ?></td>
                                                        <td><?php echo htmlspecialchars($listing['role'] ?? ''); ?></td>
                                                        <td><?php echo htmlspecialchars($listing['owner_phone'] ?? ''); ?></td>
                                                        <td class="text-truncate" style="max-width: 100px;">
                                                            <?php echo htmlspecialchars($listing['location'] ?? ''); ?>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>

                                </div> <!-- end card-body-->
                            </div> <!-- end card-->
                        </div> <!-- end col -->

                        <div class="col-xl-6">
                            <div class="card">
                                <div class="card-body">

                                    <h4 class="card-title overflow-hidden">Recent User</h4>
                                    <!-- <p class="card-subtitle mb-4 font-size-13 overflow-hidden">Transaction period from 21 July to 25 Aug
                                    </p> -->

                                    <div class="table-responsive">
                                        <table class="table table-centered table-hover table-xl mb-0" id="recent-users">
                                            <thead>
                                                <tr>
                                                    <th class="border-top-0">Name</th>
                                                    <th class="border-top-0">Phone Number</th>
                                                    <th class="border-top-0">Role</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($recentUsers as $user): ?>
                                                    <tr>
                                                        <td><?php echo htmlspecialchars($user['name'] ?? ''); ?></td>
                                                        <td><?php echo htmlspecialchars($user['phone'] ?? ''); ?></td>
                                                        <td><?php echo htmlspecialchars($user['who_are_you'] ?? ''); ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>

                                </div> <!-- end card-body-->
                            </div> <!-- end card-->
                        </div> <!-- end col -->
                    </div> <!-- end row -->

                </div> <!-- container -->

            </div> <!-- content -->

            <?php include __DIR__ . '/../partials/admin_footer.php'; ?>

        </div>

        <!-- ============================================================== -->
        <!-- End Page content -->
        <!-- ============================================================== -->

    </div>
    <!-- END wrapper -->

  <!-- App js -->
        <script src="<?php echo APP_URL; ?>/admin-asset/js/vendor.min.js"></script>
        <script src="<?php echo APP_URL; ?>/admin-asset/js/app.js"></script>

        <script src="<?php echo APP_URL; ?>/admin-asset/libs/morris.js/morris.min.js"></script>

        <script src="<?php echo APP_URL; ?>/admin-asset/libs/raphael/raphael.min.js"></script>

        <!-- third party js -->
        <script src="<?php echo APP_URL; ?>/admin-asset/libs/datatables.net/js/jquery.dataTables.min.js"></script>
        <script
            src="<?php echo APP_URL; ?>/admin-asset/libs/datatables.net-bs5/js/dataTables.bootstrap5.min.js"></script>
        <script
            src="<?php echo APP_URL; ?>/admin-asset/libs/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
        <script
            src="<?php echo APP_URL; ?>/admin-asset/libs/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js"></script>
        <script
            src="<?php echo APP_URL; ?>/admin-asset/libs/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
        <script
            src="<?php echo APP_URL; ?>/admin-asset/libs/datatables.net-buttons-bs5/js/buttons.bootstrap5.min.js"></script>
        <script src="<?php echo APP_URL; ?>/admin-asset/libs/datatables.net-buttons/js/buttons.html5.min.js"></script>
        <script src="<?php echo APP_URL; ?>/admin-asset/libs/datatables.net-buttons/js/buttons.flash.min.js"></script>
        <script src="<?php echo APP_URL; ?>/admin-asset/libs/datatables.net-buttons/js/buttons.print.min.js"></script>
        <script
            src="<?php echo APP_URL; ?>/admin-asset/libs/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
        <script
            src="<?php echo APP_URL; ?>/admin-asset/libs/datatables.net-select/js/dataTables.select.min.js"></script>
        <script src="<?php echo APP_URL; ?>/admin-asset/libs/pdfmake/build/pdfmake.min.js"></script>
        <script src="<?php echo APP_URL; ?>/admin-asset/libs/pdfmake/build/vfs_fonts.js"></script>
        <!-- third party js ends -->
</body>

</html>