<?php require_once __DIR__ . '/../../config/constants.php'; ?>
<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../models/ContactUs.php';
$db = getDatabaseConnection();
$contactModel = new ContactUs($db);

// Get filter values from GET or use current month as default
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-01');
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-t');

// Fix: Only variables can be passed by reference
$start = $start_date . ' 00:00:00';
$end = $end_date . ' 23:59:59';
$stmt = $db->prepare("SELECT * FROM contact_us WHERE created_at BETWEEN ? AND ?");
$stmt->bind_param("ss", $start, $end);
$stmt->execute();
$result = $stmt->get_result();
$contacts = $result->fetch_all(MYSQLI_ASSOC);
$totalContacts = count($contacts);
?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="light" data-menu-color="dark" data-topbar-color="light">

<head>
    <meta charset="utf-8" />
    <title>Contact | Admin Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="<?php echo APP_URL; ?>/admin-asset/libs/morris.js/morris.css" rel="stylesheet" type="text/css" />

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

            <div class="px-3">
                <div class="container-fluid">
                    <div class="py-3 py-lg-4">
                        <div class="row">
                            <div class="col-lg-6">
                                <h4 class="page-title mb-0">Contact From Submission</h4>
                            </div>
                            <div class="col-lg-6">
                                <div class="d-none d-lg-block">
                                    <ol class="breadcrumb m-0 float-end">
                                        <li class="breadcrumb-item"><a
                                                href="<?php echo APP_URL; ?>/admin/dashboard">Admin</a></li>
                                        <li class="breadcrumb-item active">Home</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php if (isset($_SESSION['success'])): ?>
                        <div class="alert alert-success"><?= $_SESSION['success'];
                        unset($_SESSION['success']); ?></div>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger"><?= $_SESSION['error'];
                        unset($_SESSION['error']); ?></div>
                    <?php endif; ?>

                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">

                                    <form method="GET" id="filterForm" class="row g-3 mb-3">

                                        <div class="col-md-2 filter-start_date">
                                            <label for="start_date" class="form-label">Start Date</label>
                                            <input type="date" class="form-control" id="start_date" name="start_date"
                                                value="<?= htmlspecialchars($_GET['start_date'] ?? date('Y-m-01')) ?>">
                                        </div>
                                        <div class="col-md-2 filter-end_date">
                                            <label for="end_date" class="form-label">End Date</label>
                                            <input type="date" class="form-control" id="end_date" name="end_date"
                                                value="<?= htmlspecialchars($_GET['end_date'] ?? date('Y-m-t')) ?>">
                                        </div>

                                        <div class="col-md-4 d-flex align-items-end">
                                            <button type="submit" class="btn btn-primary me-2">Generate Report</button>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-success dropdown-toggle"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                    Export Report
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li><a class="dropdown-item" href="#" id="exportExcel">Export as
                                                            CSV</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </form>

                                    <div class="row">

                                        <div class="col-xl-12 col-md-12">
                                            <div class="card">
                                                <div class="card-body">
                                                    <i class="bx bx-group float-end m-0 h2 text-muted"></i>
                                                    <h4 class="text-muted text-uppercase mt-0">Total Submission
                                                    </h4>
                                                    <h3 class="mb-3" data-plugin="counterup">
                                                        <?= $totalContacts ?>
                                                    </h3>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- end row -->

                                    <table id="row-callback-datatable" class="table dt-responsive nowrap w-100 mt-4">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Phone Number</th>
                                                <th>Subject</th>
                                                <th>Massage</th>
                                                <th>Date</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($contacts as $contact): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($contact['name']) ?></td>
                                                    <td><?= htmlspecialchars($contact['email']) ?></td>
                                                    <td><?= htmlspecialchars($contact['phone']) ?></td>
                                                    <td><?= htmlspecialchars($contact['subject']) ?></td>
                                                    <td>
                                                        <?php
                                                        $words = explode(' ', strip_tags($contact['message']));
                                                        $short = implode(' ', array_slice($words, 0, 3));
                                                        if (count($words) > 3) {
                                                            echo htmlspecialchars($short) . '...';
                                                        } else {
                                                            echo htmlspecialchars($contact['message']);
                                                        }
                                                        ?>
                                                    </td>
                                                    <td><?= date('d M Y', strtotime($contact['created_at'])) ?></td>
                                                    <td>
                                                        <button type="button" class="btn btn-info btn-sm view-btn" 
                                                            data-bs-toggle="modal" data-bs-target="#viewContactModal"
                                                            data-name="<?= htmlspecialchars($contact['name']) ?>"
                                                            data-email="<?= htmlspecialchars($contact['email']) ?>"
                                                            data-phone="<?= htmlspecialchars($contact['phone']) ?>"
                                                            data-subject="<?= htmlspecialchars($contact['subject']) ?>"
                                                            data-message="<?= htmlspecialchars($contact['message']) ?>"
                                                            data-date="<?= date('d M Y', strtotime($contact['created_at'])) ?>">
                                                            View
                                                        </button>
                                                        <form method="POST" action="<?php echo APP_URL; ?>/admin/contact/delete" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this message?');">
                                                            <input type="hidden" name="delete_id" value="<?= $contact['id'] ?>">
                                                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div> <!-- end card body-->
                            </div> <!-- end card -->
                        </div><!-- end col-->
                    </div>
                </div>
            </div>
            <!-- end row-->
            <?php include __DIR__ . '/../partials/admin_footer.php'; ?>

        </div>

        <!-- ============================================================== -->
        <!-- End Page content -->
        <!-- ============================================================== -->

    </div>
    <!-- END wrapper -->

    <!-- App js -->
    <script src="<?php echo APP_URL; ?>/admin-asset/js/vendor.min.js"></script>
    <script src="<?php echo APP_URL; ?>/admin-asset//js/app.js"></script>

    <script src="<?php echo APP_URL; ?>/admin-asset//libs/morris.js/morris.min.js"></script>

    <script src="<?php echo APP_URL; ?>/admin-asset//libs/raphael/raphael.min.js"></script>

    <!-- third party js -->
    <script src="<?php echo APP_URL; ?>/admin-asset//libs/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="<?php echo APP_URL; ?>/admin-asset//libs/datatables.net-bs5/js/dataTables.bootstrap5.min.js"></script>
    <script
        src="<?php echo APP_URL; ?>/admin-asset//libs/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    <script
        src="<?php echo APP_URL; ?>/admin-asset//libs/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js"></script>
    <script src="<?php echo APP_URL; ?>/admin-asset//libs/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
    <script
        src="<?php echo APP_URL; ?>/admin-asset//libs/datatables.net-buttons-bs5/js/buttons.bootstrap5.min.js"></script>
    <script src="<?php echo APP_URL; ?>/admin-asset//libs/datatables.net-buttons/js/buttons.html5.min.js"></script>
    <script src="<?php echo APP_URL; ?>/admin-asset//libs/datatables.net-buttons/js/buttons.flash.min.js"></script>
    <script src="<?php echo APP_URL; ?>/admin-asset//libs/datatables.net-buttons/js/buttons.print.min.js"></script>
    <script
        src="<?php echo APP_URL; ?>/admin-asset//libs/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
    <script src="<?php echo APP_URL; ?>/admin-asset//libs/datatables.net-select/js/dataTables.select.min.js"></script>
    <script src="<?php echo APP_URL; ?>/admin-asset//libs/pdfmake/build/pdfmake.min.js"></script>
    <script src="<?php echo APP_URL; ?>/admin-asset//libs/pdfmake/build/vfs_fonts.js"></script>
    <!-- third party js ends -->

    <!-- Datatables js -->
    <script src="<?php echo APP_URL; ?>/admin-asset//js/pages/datatables.js"></script>

    <!-- Dashboard init-->
    <script src="<?php echo APP_URL; ?>/admin-asset//js/pages/dashboard.js"></script>

    <script>

        document.addEventListener('DOMContentLoaded', function() {
            var viewButtons = document.querySelectorAll('.view-btn');
            viewButtons.forEach(function(btn) {
                btn.addEventListener('click', function() {
                    document.getElementById('modalName').textContent = btn.getAttribute('data-name');
                    document.getElementById('modalEmail').textContent = btn.getAttribute('data-email');
                    document.getElementById('modalPhone').textContent = btn.getAttribute('data-phone');
                    document.getElementById('modalSubject').textContent = btn.getAttribute('data-subject');
                    document.getElementById('modalMessage').textContent = btn.getAttribute('data-message');
                    document.getElementById('modalDate').textContent = btn.getAttribute('data-date');
                });
            });
        });
    </script>

    <!-- View Contact Modal -->
    <div class="modal fade" id="viewContactModal" tabindex="-1" aria-labelledby="viewContactModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="viewContactModalLabel">Contact Details</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <p><strong>Name:</strong> <span id="modalName"></span></p>
            <p><strong>Email:</strong> <span id="modalEmail"></span></p>
            <p><strong>Phone:</strong> <span id="modalPhone"></span></p>
            <p><strong>Subject:</strong> <span id="modalSubject"></span></p>
            <p><strong>Message:</strong> <span id="modalMessage"></span></p>
            <p><strong>Date:</strong> <span id="modalDate"></span></p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>

    <script>
document.getElementById('exportExcel').addEventListener('click', function (e) {
    e.preventDefault();
    const form = document.getElementById('filterForm');
    const params = new URLSearchParams(new FormData(form));
    window.location.href = '<?php echo APP_URL; ?>/admin/contact/export-csv?' + params.toString();
});
</script>

</body>

</html>