<?php require_once __DIR__ . '/../../config/constants.php'; ?>
<?php
require_once __DIR__ . '/../../models/Message.php';

// Pagination and filter
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 20;
$offset = ($page - 1) * $limit;
$order = isset($_GET['order']) ? $_GET['order'] : 'latest';
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-01');
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-t');

$start = $start_date . ' 00:00:00';
$end = $end_date . ' 23:59:59';

// Custom query for date range
$conn = getDatabaseConnection();
$sql = "SELECT id, uname, massage, created_at FROM massage WHERE created_at BETWEEN ? AND ? ORDER BY created_at DESC LIMIT ? OFFSET ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssii", $start, $end, $limit, $offset);
$stmt->execute();
$result = $stmt->get_result();
$messages = [];
while ($row = $result->fetch_assoc()) {
    $messages[] = $row;
}
$stmt->close();
// Get total count for pagination
$countStmt = $conn->prepare("SELECT COUNT(*) as total FROM massage WHERE created_at BETWEEN ? AND ?");
$countStmt->bind_param("ss", $start, $end);
$countStmt->execute();
$countResult = $countStmt->get_result();
$totalMessages = $countResult->fetch_assoc()['total'];
$countStmt->close();
$conn->close();
$totalPages = ceil($totalMessages / $limit);

// Handle multiple delete POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_selected']) && !empty($_POST['selected_messages'])) {
    $ids = $_POST['selected_messages'];
    $conn = getDatabaseConnection();
    $in = str_repeat('?,', count($ids) - 1) . '?';
    $types = str_repeat('i', count($ids));
    $stmt = $conn->prepare("DELETE FROM massage WHERE id IN ($in)");
    $stmt->bind_param($types, ...$ids);
    $stmt->execute();
    $stmt->close();
    $conn->close();
    header("Location: " . $_SERVER['REQUEST_URI']);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="light" data-menu-color="dark" data-topbar-color="light">

<head>
    <meta charset="utf-8" />
    <title>Messages Table View</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="<?php echo APP_URL; ?>/admin-asset/images/favicon.ico">
    <link href="<?php echo APP_URL; ?>/admin-asset/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo APP_URL; ?>/admin-asset/css/style.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo APP_URL; ?>/admin-asset/css/icons.min.css" rel="stylesheet" type="text/css">
    <script src="<?php echo APP_URL; ?>/admin-asset/js/config.js"></script>
</head>

<body>
    <div class="layout-wrapper">
        <?php include __DIR__ . '/../partials/admin_nav.php'; ?>

        <div class="page-content">
            <?php include __DIR__ . '/../partials/topbar.php'; ?>

            <div class="px-3">
                <div class="container-fluid">
                    <div class="py-3 py-lg-4">
                        <div class="row">
                            <div class="col-lg-6">
                                <h4 class="page-title mb-0">Messages</h4>
                            </div>
                            <div class="col-lg-6">
                                <div class="d-none d-lg-block">
                                    <ol class="breadcrumb m-0 float-end">
                                        <li class="breadcrumb-item"><a
                                                href="<?php echo APP_URL; ?>/admin/dashboard">Messages</a></li>
                                        <li class="breadcrumb-item active">Home</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <form method="GET" id="filterForm" class="row g-3 mb-3">
                                        <div class="col-md-2 filter-start_date">
                                            <label for="start_date" class="form-label">Start Date</label>
                                            <input type="date" class="form-control" id="start_date" name="start_date" value="<?= htmlspecialchars($start_date) ?>">
                                        </div>
                                        <div class="col-md-2 filter-end_date">
                                            <label for="end_date" class="form-label">End Date</label>
                                            <input type="date" class="form-control" id="end_date" name="end_date" value="<?= htmlspecialchars($end_date) ?>">
                                        </div>
                                        <div class="col-md-4 d-flex align-items-end">
                                            <button type="submit" class="btn btn-primary me-2 w-50">
                                                <i class="mdi mdi-filter"></i> Filter
                                            </button>
                                            <button type="submit" name="delete_selected" class="btn btn-danger w-50"
                                                onclick="return confirm('Delete selected messages?')">
                                                <i class="mdi mdi-delete"></i> Delete Selected
                                            </button>
                                        </div>
                                    </form>
                                    <form method="POST" id="multiDeleteForm">
                                        <table id="row-callback-datatable" class="table dt-responsive nowrap w-100 mt-4">
                                            <thead>
                                                <tr>
                                                    <th><input type="checkbox" id="select-all"></th>
                                                    <th>Username</th>
                                                    <th>Message</th>
                                                    <th>Date</th>
                                                    <th>Options</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($messages as $msg): ?>
                                                <tr>
                                                    <td><input type="checkbox" name="selected_messages[]" value="<?= htmlspecialchars($msg['id']) ?>"></td>
                                                    <td><?= htmlspecialchars($msg['uname']) ?></td>
                                                    <td><?= htmlspecialchars(mb_strimwidth($msg['massage'], 0, 30, '...')) ?></td>
                                                    <td><?= date('d M Y H:i', strtotime($msg['created_at'])) ?></td>
                                                    <td>
                                                        <button type="button" class="btn btn-info btn-sm view-btn"
                                                            data-bs-toggle="modal" data-bs-target="#viewMessageModal"
                                                            data-uname="<?= htmlspecialchars($msg['uname']) ?>"
                                                            data-massage="<?= htmlspecialchars($msg['massage']) ?>"
                                                            data-date="<?= date('d M Y H:i', strtotime($msg['created_at'])) ?>">
                                                            View
                                                        </button>
                                                    </td>
                                                </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php include __DIR__ . '/../partials/admin_footer.php'; ?>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="viewMessageModal" tabindex="-1" aria-labelledby="viewMessageModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="viewMessageModalLabel">Message Details</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <p><strong>Username:</strong> <span id="modalUname"></span></p>
            <p><strong>Message:</strong> <span id="modalMassage"></span></p>
            <p><strong>Date:</strong> <span id="modalDate"></span></p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
   
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
                document.getElementById('modalUname').textContent = btn.getAttribute('data-uname');
                document.getElementById('modalMassage').textContent = btn.getAttribute('data-massage');
                document.getElementById('modalDate').textContent = btn.getAttribute('data-date');
            });
        });
        // Optionally, initialize DataTables
        if (window.jQuery && window.jQuery.fn.dataTable) {
            $('#messages-table').DataTable();
        }
        // Select all checkboxes
        const selectAll = document.getElementById('select-all');
        if (selectAll) {
            selectAll.addEventListener('change', function() {
                document.querySelectorAll('input[name="selected_messages[]"]').forEach(cb => cb.checked = this.checked);
            });
        }
    });
    </script>
</body>

</html>