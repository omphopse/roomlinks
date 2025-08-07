<?php require_once __DIR__ . '/../../config/constants.php'; ?>
<?php require_once __DIR__ . '/../../helpers/auth.php'; ?>
<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
checkAdminAuth();
// $locations is passed from controller
?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="light" data-menu-color="dark" data-topbar-color="light">

<head>
    <meta charset="utf-8" />
    <title>Feedback | Admin Dashboard</title>
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
                            <div class="col-lg-6 d-flex">
                                <h4 class="page-title mb-0">Feedbacks</h4>
                            </div>
                            <div class="col-lg-6">
                                <div class="d-none d-lg-block">
                                    <ol class="breadcrumb m-0 float-end">
                                        <li class="breadcrumb-item"><a
                                                href="<?php echo APP_URL; ?>/admin/dashboard">Admin</a></li>
                                        <li class="breadcrumb-item active">Feedbacks</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php if (isset($_SESSION['success'])): ?>
                        <div class="alert alert-success"><?php echo $_SESSION['success'];
                        unset($_SESSION['success']); ?>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger"><?php echo $_SESSION['error'];
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
                                            </div>
                                        </div>
                                    </form>

                                    <div class="row">
                                        <div class="col-xl-12 col-md-12">
                                            <div class="card">
                                                <div class="card-body">
                                                    <i class="bx bx-message-square-dots float-end m-0 h2 text-muted"></i>
                                                    <h4 class="text-muted text-uppercase mt-0">Total Feedbacks</h4>
                                                    <h3 class="mb-3" data-plugin="counterup">
                                                        <?= isset($feedbacks) ? count($feedbacks) : 0 ?>
                                                    </h3>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <table id="row-callback-datatable" class="table dt-responsive nowrap w-100 mt-4">
                                        <thead>
                                            <tr>
                                                <th>User Name</th>
                                                <th>Phone</th>
                                                <th>Subject</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (isset($feedbacks) && count($feedbacks) > 0): ?>
                                                <?php foreach ($feedbacks as $feedback): ?>
                                                    <tr>
                                                        <td><?= htmlspecialchars($feedback['user_name'] ?? '-') ?></td>
                                                        <td><?= htmlspecialchars($feedback['user_phone'] ?? '-') ?></td>
                                                        <td><?= htmlspecialchars($feedback['subject']) ?></td>
                                                        <td>
                                                            <span class="badge bg-<?= $feedback['status'] === 'resolved' ? 'success' : ($feedback['status'] === 'processing' ? 'warning' : 'danger') ?>">
                                                                <?= ucfirst($feedback['status']) ?>
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <button type="button" class="btn btn-info btn-sm edit-feedback-btn"
                                                                data-id="<?= $feedback['id'] ?>"
                                                                data-username="<?= htmlspecialchars($feedback['user_name'] ?? '-') ?>"
                                                                data-userphone="<?= htmlspecialchars($feedback['user_phone'] ?? '-') ?>"
                                                                data-subject="<?= htmlspecialchars($feedback['subject']) ?>"
                                                                data-feedback="<?= htmlspecialchars($feedback['feedback']) ?>"
                                                                data-status="<?= $feedback['status'] ?>">Edit</button>
                                                            <form method="POST" action="<?php echo APP_URL; ?>/admin/feedback/delete" style="display:inline;">
                                                                <input type="hidden" name="feedback_id" value="<?= $feedback['id'] ?>">
                                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Delete this feedback?')">Delete</button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Edit Feedback Modal -->
                    <div class="modal fade" id="editFeedbackModal" tabindex="-1" aria-labelledby="editFeedbackModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form method="POST" action="<?php echo APP_URL; ?>/admin/feedback/edit" id="editFeedbackForm">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editFeedbackModalLabel">Edit Feedback</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="hidden" name="feedback_id" id="editFeedbackId">
                                        <div class="mb-3">
                                            <label class="form-label">User Name</label>
                                            <input type="text" class="form-control" id="editUserName" readonly>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">User Phone</label>
                                            <input type="text" class="form-control" id="editUserPhone" readonly>
                                        </div>
                                        <div class="mb-3">
                                            <label for="editSubject" class="form-label">Subject</label>
                                            <input type="text" class="form-control" id="editSubject" name="subject" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="editFeedbackText" class="form-label">Feedback</label>
                                            <textarea class="form-control" id="editFeedbackText" name="feedback" rows="4" required></textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label for="editFeedbackStatus" class="form-label">Status</label>
                                            <select class="form-control" id="editFeedbackStatus" name="status" required>
                                                <option value="reported">Reported</option>
                                                <option value="processing">Processing</option>
                                                <option value="resolved">Resolved</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
    <script src="<?php echo APP_URL; ?>/admin-asset/libs/datatables.net-bs5/js/dataTables.bootstrap5.min.js"></script>
    <script
        src="<?php echo APP_URL; ?>/admin-asset/libs/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    <script
        src="<?php echo APP_URL; ?>/admin-asset/libs/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js"></script>
    <script src="<?php echo APP_URL; ?>/admin-asset/libs/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
    <script
        src="<?php echo APP_URL; ?>/admin-asset/libs/datatables.net-buttons-bs5/js/buttons.bootstrap5.min.js"></script>
    <script src="<?php echo APP_URL; ?>/admin-asset/libs/datatables.net-buttons/js/buttons.html5.min.js"></script>
    <script src="<?php echo APP_URL; ?>/admin-asset/libs/datatables.net-buttons/js/buttons.flash.min.js"></script>
    <script src="<?php echo APP_URL; ?>/admin-asset/libs/datatables.net-buttons/js/buttons.print.min.js"></script>
    <script
        src="<?php echo APP_URL; ?>/admin-asset/libs/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
    <script src="<?php echo APP_URL; ?>/admin-asset/libs/datatables.net-select/js/dataTables.select.min.js"></script>
    <script src="<?php echo APP_URL; ?>/admin-asset/libs/pdfmake/build/pdfmake.min.js"></script>
    <script src="<?php echo APP_URL; ?>/admin-asset/libs/pdfmake/build/vfs_fonts.js"></script>
    <!-- third party js ends -->

    <!-- Datatables js -->
    <script src="<?php echo APP_URL; ?>/admin-asset/js/pages/datatables.js"></script>

    <!-- Dashboard init-->
    <script src="<?php echo APP_URL; ?>/admin-asset/js/pages/dashboard.js"></script>

    <script>
        // Edit feedback button logic
        document.querySelectorAll('.edit-feedback-btn').forEach(function (btn) {
            btn.addEventListener('click', function () {
                document.getElementById('editFeedbackId').value = btn.getAttribute('data-id');
                document.getElementById('editUserName').value = btn.getAttribute('data-username');
                document.getElementById('editUserPhone').value = btn.getAttribute('data-userphone');
                document.getElementById('editSubject').value = btn.getAttribute('data-subject');
                document.getElementById('editFeedbackText').value = btn.getAttribute('data-feedback');
                document.getElementById('editFeedbackStatus').value = btn.getAttribute('data-status');
                var modal = new bootstrap.Modal(document.getElementById('editFeedbackModal'));
                modal.show();
            });
        });
    </script>
</body>

</html>