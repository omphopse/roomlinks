<?php require_once __DIR__ . '/../../config/constants.php'; ?>
<?php require_once __DIR__ . '/../../models/Vibes.php'; ?>
<?php require_once __DIR__ . '/../../helpers/auth.php'; ?>
<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
checkAdminAuth();
// Vibes and filter data are passed from controller

// Get filter values from GET or use current month as default
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-01');
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-t');


?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="light" data-menu-color="dark" data-topbar-color="light">

<head>
    <meta charset="utf-8" />
    <title>Vibes | Admin Dashboard</title>
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
    <style>
        .vibes-img-preview {
            max-width: 80px;
            max-height: 80px;
            border-radius: 8px;
        }
    </style>
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
                                <h4 class="page-title mb-0">Vibes</h4>
                                <a href="<?php echo APP_URL; ?>/admin/addnewvibes"
                                    style="margin-left: 24px; margin-top: -10px;"
                                    class="btn btn-dark waves-effect waves-light"><i
                                        class="bx bxs-add-to-queue me-1"></i>Add New Vibes</a>
                            </div>
                            <div class="col-lg-6">
                                <div class="d-none d-lg-block">
                                    <ol class="breadcrumb m-0 float-end">
                                        <li class="breadcrumb-item"><a
                                                href="<?php echo APP_URL; ?>/admin/dashboard">Admin</a></li>
                                        <li class="breadcrumb-item active">Vibes</li>
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
                                                    <i class="bx bx-group float-end m-0 h2 text-muted"></i>
                                                    <h4 class="text-muted text-uppercase mt-0">Total Vibes
                                                    </h4>
                                                    <h3 class="mb-3" data-plugin="counterup">
                                                        <?= $totalVibes ?>
                                                    </h3>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <table id="row-callback-datatable" class="table dt-responsive nowrap w-100 mt-4">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Image</th>
                                                <th>Date</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($vibes as $vibe): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($vibe['name']) ?></td>
                                                    <td><img src="<?= APP_URL . '/' . $vibe['images'] ?>"
                                                            class="vibes-img-preview" alt="Vibe Image"></td>
                                                    <td><?= date('d M Y', strtotime($vibe['created_at'])) ?></td>
                                                    <td>
                                                        <button type="button" class="btn btn-info btn-sm edit-vibe-btn"
                                                            data-id="<?= $vibe['id'] ?>"
                                                            data-name="<?= htmlspecialchars($vibe['name']) ?>"
                                                            data-image="<?= APP_URL . '/' . $vibe['images'] ?>">Edit</button>
                                                        <form method="POST" action="<?= APP_URL; ?>/admin/vibes/delete"
                                                            style="display:inline;"
                                                            onsubmit="return confirm('Are you sure you want to delete this vibe?');">
                                                            <input type="hidden" name="vibe_id"
                                                                value="<?= $vibe['id'] ?>">
                                                            <button type="submit"
                                                                class="btn btn-danger btn-sm">Delete</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Edit Vibe Modal -->
                    <div class="modal fade" id="editVibeModal" tabindex="-1" aria-labelledby="editVibeModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form method="POST" action="<?php echo APP_URL; ?>/admin/vibes/edit"
                                    enctype="multipart/form-data" id="editVibeForm">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editVibeModalLabel">Edit Vibe</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="hidden" name="vibe_id" id="editVibeId">
                                        <div class="mb-3">
                                            <label for="editVibeName" class="form-label">Vibe Name</label>
                                            <input type="text" class="form-control" id="editVibeName" name="vibe_name"
                                                required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Vibe Image <span class="text-muted">(max
                                                    500KB)</span></label>
                                            <div class="dropzone border rounded d-flex flex-column align-items-center justify-content-center py-4 position-relative vibes-dropzone"
                                                style="overflow: hidden;">
                                                <input type="file" class="form-control d-none" id="editVibeImage"
                                                    name="vibe_image" accept="image/*">
                                                <div class="dropzone-content" id="editDropzoneContent">
                                                    <i class="fa fa-image"></i>
                                                    <div class="mt-2 vibes-dropzone-text">Click or Drop to upload
                                                        vibe<br>(jpg or png)</div>
                                                </div>
                                                <div class="preview-container vibes-preview-container d-flex align-items-center justify-content-center"
                                                    id="editPreviewContainer" style="display:none;">
                                                    <button type="button" class="vibes-remove-btn btn btn-light me-2"
                                                        id="editRemoveImg" style="display:none;">&times;</button>
                                                    <img src="#" alt="Preview" class="vibes-preview-img"
                                                        id="editPreviewImg" style="display:none;">
                                                </div>
                                            </div>
                                            <div class="mt-2">
                                                <img src="#" id="currentVibeImg" class="vibes-img-preview"
                                                    style="display:none;">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Close</button>
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
        // Edit button logic
        document.querySelectorAll('.edit-vibe-btn').forEach(function (btn) {
            btn.addEventListener('click', function () {
                document.getElementById('editVibeId').value = btn.getAttribute('data-id');
                document.getElementById('editVibeName').value = btn.getAttribute('data-name');
                document.getElementById('currentVibeImg').src = btn.getAttribute('data-image');
                document.getElementById('currentVibeImg').style.display = 'block';
                document.getElementById('editPreviewImg').style.display = 'none';
                document.getElementById('editPreviewContainer').style.display = 'none';
                document.getElementById('editRemoveImg').style.display = 'none';
                document.getElementById('editDropzoneContent').style.display = '';
                var modal = new bootstrap.Modal(document.getElementById('editVibeModal'));
                modal.show();
            });
        });
        // Dropzone and preview logic for edit modal
        const editVibeImage = document.getElementById('editVibeImage');
        const editDropzone = document.querySelector('#editVibeImage').closest('.dropzone');
        const editDropzoneContent = document.getElementById('editDropzoneContent');
        const editPreviewContainer = document.getElementById('editPreviewContainer');
        const editPreviewImg = document.getElementById('editPreviewImg');
        const editRemoveImg = document.getElementById('editRemoveImg');
        editDropzone.addEventListener('click', function (e) {
            if (e.target !== editRemoveImg) editVibeImage.click();
        });
        editDropzone.addEventListener('dragover', function (e) {
            e.preventDefault();
            editDropzone.classList.add('border-primary');
        });
        editDropzone.addEventListener('dragleave', function (e) {
            e.preventDefault();
            editDropzone.classList.remove('border-primary');
        });
        editDropzone.addEventListener('drop', function (e) {
            e.preventDefault();
            editDropzone.classList.remove('border-primary');
            if (e.dataTransfer.files.length) {
                editVibeImage.files = e.dataTransfer.files;
                editVibeImage.dispatchEvent(new Event('change'));
            }
        });
        editVibeImage.addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (file) {
                if (file.size > 512000) {
                    alert('Image must be less than 500KB');
                    e.target.value = '';
                    editPreviewImg.src = '';
                    editPreviewImg.style.display = 'none';
                    editPreviewContainer.style.display = 'none';
                    editRemoveImg.style.display = 'none';
                    editDropzoneContent.style.display = '';
                    return;
                }
                const reader = new FileReader();
                reader.onload = function (ev) {
                    editPreviewImg.src = ev.target.result;
                    editPreviewImg.style.display = 'inline-block';
                    editPreviewContainer.style.display = 'flex';
                    editRemoveImg.style.display = 'inline-block';
                    editDropzoneContent.style.display = 'none';
                    document.getElementById('currentVibeImg').style.display = 'none';
                }
                reader.readAsDataURL(file);
            } else {
                editPreviewImg.src = '';
                editPreviewImg.style.display = 'none';
                editPreviewContainer.style.display = 'none';
                editRemoveImg.style.display = 'none';
                editDropzoneContent.style.display = '';
                document.getElementById('currentVibeImg').style.display = 'block';
            }
        });
        editRemoveImg.addEventListener('click', function (e) {
            editVibeImage.value = '';
            editPreviewImg.src = '';
            editPreviewImg.style.display = 'none';
            editPreviewContainer.style.display = 'none';
            editRemoveImg.style.display = 'none';
            editDropzoneContent.style.display = '';
            document.getElementById('currentVibeImg').style.display = 'block';
        });
    </script>
</body>

</html>