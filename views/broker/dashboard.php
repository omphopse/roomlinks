<?php require_once __DIR__ . '/../../config/constants.php'; ?>
<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/BrokerModel.php';
require_once __DIR__ . '/../../helpers/auth.php';

$brokerId = $_SESSION['user_id'] ?? null;

// Get broker dashboard data
$brokerData = BrokerModel::getBrokerDashboardData($brokerId);

// Extract data for easier access in template
$brokerProfile = $brokerData['profile'] ?? [];
$stats = $brokerData['stats'] ?? [];
$recentListings = $brokerData['recent_listings'] ?? [];
$listingsByLocation = $brokerData['listings_by_location'] ?? [];
$listingsByRole = $brokerData['listings_by_role'] ?? [];

// Set variables for template compatibility
$totalUsers = $stats['total_listings'] ?? 0;
$totalListings = $stats['total_listings'] ?? 0;
$totalRooms = $stats['total_rooms'] ?? 0;
$totalPGs = $stats['total_pgs'] ?? 0;
$totalClicks = $stats['total_clicks'] ?? 0;
$totalRevenue = $stats['total_revenue'] ?? 0;
$averageRent = $stats['average_rent'] ?? 0;

$recentUsers = []; // Brokers only see their own data
?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="light" data-menu-color="dark" data-topbar-color="light">

<head>
    <meta charset="utf-8" />
    <title>Broker Dashboard</title>
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
                                                href="<?php echo APP_URL; ?>/dashboard">Broker</a></li>
                                        <li class="breadcrumb-item active">Dashboard</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end page title -->

                    <div class="row mb-3">
                        <div class="col-12 text-end">
                            <a href="<?php echo APP_URL; ?>/dashboard/listings" class="btn btn-primary">
                                View Broker Listings
                            </a>
                        </div>
                    </div>

                    <div class="row">
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
                                    <i class="bx bx-check-circle float-end m-0 h2 text-success"></i>
                                    <h4 class="text-muted text-uppercase mt-0">Active Listings</h4>
                                    <h3 class="mb-3"><span data-plugin="counterup"><?php echo $stats['active_listings'] ?? 0; ?></span></h3>
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
                                    <h4 class="text-muted text-uppercase mt-0">Total Clicks</h4>
                                    <h3 class="mb-3"> <span data-plugin="counterup"><?= htmlspecialchars($totalClicks); ?> </span></h3>
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
                                                    <th class="border-top-0">Location</th>
                                                    <th class="border-top-0">Role</th>
                                                    <th class="border-top-0">Rent</th>
                                                    <th class="border-top-0">Status</th>
                                                    <th class="border-top-0">Created</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($recentListings as $listing): ?>
                                                    <tr>
                                                        <td><?php echo htmlspecialchars($listing['location'] ?? ''); ?></td>
                                                        <td><span class="badge bg-info"><?php echo htmlspecialchars($listing['role'] ?? ''); ?></span></td>
                                                        <td>₹<?php echo number_format($listing['rent'] ?? 0, 2); ?></td>
                                                        <td><span class="badge <?php echo ($listing['status'] == 'active') ? 'bg-success' : 'bg-warning'; ?>"><?php echo htmlspecialchars($listing['status'] ?? ''); ?></span></td>
                                                        <td><?php echo date('M d, Y', strtotime($listing['created_at'] ?? '')); ?></td>
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

                                    <h4 class="card-title overflow-hidden">Listings by Location</h4>

                                    <div class="table-responsive">
                                        <table class="table table-centered table-hover table-xl mb-0" id="listings-location">
                                            <thead>
                                                <tr>
                                                    <th class="border-top-0">Location</th>
                                                    <th class="border-top-0">Count</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($listingsByLocation as $loc): ?>
                                                    <tr>
                                                        <td><?php echo htmlspecialchars($loc['location'] ?? ''); ?></td>
                                                        <td><span class="badge bg-primary"><?php echo (int)$loc['count']; ?></span></td>
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