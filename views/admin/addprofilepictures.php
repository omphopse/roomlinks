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
?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="light" data-menu-color="dark" data-topbar-color="light">

<head>
    <meta charset="utf-8" />
    <title>Add Profile Pictures | Admin Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="<?php echo APP_URL; ?>/admin-asset/libs/morris.js/morris.css" rel="stylesheet" type="text/css" />

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

                <!-- Start Content-->
                <div class="container-fluid">

                    <!-- start page title -->
                    <div class="py-3 py-lg-4">
                        <div class="row">
                             <div class="col-lg-6 d-flex">
                                <h4 class="page-title mb-0">Add Profile Pictures</h4>
                                <a href="<?php echo APP_URL; ?>/admin/profilepictures"
                                    style="margin-left: 24px; margin-top: -10px;"
                                    class="btn btn-dark waves-effect waves-light"><i
                                        class="bx bxs-add-to-queue me-1"></i>Profile Pictures</a>
                            </div>
                            <div class="col-lg-6">
                                <div class="d-none d-lg-block">
                                    <ol class="breadcrumb m-0 float-end">
                                        <li class="breadcrumb-item"><a
                                                href="<?php echo APP_URL; ?>/admin/dashboard">Admin</a></li>
                                        <li class="breadcrumb-item active">Add Profile Pictures</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end page title -->

                    <?php if (isset($_SESSION['success'])): ?>
                        <div class="alert alert-success"><?php echo $_SESSION['success'];
                        unset($_SESSION['success']); ?>
                        </div>
                    <?php endif; ?>
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger"><?php echo $_SESSION['error'];
                        unset($_SESSION['error']); ?></div>
                    <?php endif; ?>

                    <div class="d-flex justify-content-center">
                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="header-title">Add Profile Picture</h4>
                                    <div id="profileFormsContainer">
                                        <form class="profileForm" method="POST" enctype="multipart/form-data" action="<?php echo APP_URL; ?>/admin/addprofilepicture">
                                            <div class="mb-3">
                                                <label class="form-label">Gender</label><br>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="gender[]" value="men" required>
                                                    <label class="form-check-label">Men</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="gender[]" value="women" required>
                                                    <label class="form-check-label">Women</label>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Profile Picture <span class="text-muted">(max 500KB)</span></label>
                                                <div class="dropzone border rounded d-flex flex-column align-items-center justify-content-center py-4 position-relative profile-dropzone" style="overflow: hidden;">
                                                    <input type="file" class="form-control d-none profile_image" name="profile_image[]" accept="image/*" required>
                                                    <div class="dropzone-content">
                                                        <i class="fa fa-image"></i>
                                                        <div class="mt-2 profile-dropzone-text">Click or Drop to upload profile picture<br>(jpg or png)</div>
                                                    </div>
                                                    <div class="preview-container profile-preview-container d-flex align-items-center justify-content-center" style="display:none;">
                                                        <button type="button" class="profile-remove-btn btn btn-light me-2 removeImg" style="display:none;">&times;</button>
                                                        <img src="#" alt="Preview" class="profile-preview-img previewImg" style="display:none;">
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <button type="button" class="btn btn-secondary" id="addMoreBtn">Add More</button>
                                    <button type="submit" class="btn btn-primary" id="addProfilePicturesBtn">Add Profile Pictures</button>
                                    <script>
                                        let profileFormCount = 1;
                                        function setupProfileFormEvents(form) {
                                            const profileInput = form.querySelector('.profile_image');
                                            const dropzone = form.querySelector('.dropzone');
                                            const dropzoneContent = form.querySelector('.dropzone-content');
                                            const previewContainer = form.querySelector('.preview-container');
                                            const previewImg = form.querySelector('.previewImg');
                                            const removeImg = form.querySelector('.removeImg');
                                            dropzone.addEventListener('click', function (e) {
                                                if (e.target !== removeImg) profileInput.click();
                                            });
                                            dropzone.addEventListener('dragover', function (e) {
                                                e.preventDefault();
                                                dropzone.classList.add('border-primary');
                                            });
                                            dropzone.addEventListener('dragleave', function (e) {
                                                e.preventDefault();
                                                dropzone.classList.remove('border-primary');
                                            });
                                            dropzone.addEventListener('drop', function (e) {
                                                e.preventDefault();
                                                dropzone.classList.remove('border-primary');
                                                if (e.dataTransfer.files.length) {
                                                    profileInput.files = e.dataTransfer.files;
                                                    profileInput.dispatchEvent(new Event('change'));
                                                }
                                            });
                                            profileInput.addEventListener('change', function (e) {
                                                const file = e.target.files[0];
                                                if (file) {
                                                    if (file.size > 512000) {
                                                        alert('Image must be less than 500KB');
                                                        e.target.value = '';
                                                        previewImg.src = '';
                                                        previewImg.style.display = 'none';
                                                        previewContainer.style.display = 'none';
                                                        removeImg.style.display = 'none';
                                                        dropzoneContent.style.display = '';
                                                        return;
                                                    }
                                                    const reader = new FileReader();
                                                    reader.onload = function (ev) {
                                                        previewImg.src = ev.target.result;
                                                        previewImg.style.display = 'inline-block';
                                                        previewContainer.style.display = 'flex';
                                                        removeImg.style.display = 'inline-block';
                                                        dropzoneContent.style.display = 'none';
                                                    }
                                                    reader.readAsDataURL(file);
                                                } else {
                                                    previewImg.src = '';
                                                    previewImg.style.display = 'none';
                                                    previewContainer.style.display = 'none';
                                                    removeImg.style.display = 'none';
                                                    dropzoneContent.style.display = '';
                                                }
                                            });
                                            removeImg.addEventListener('click', function (e) {
                                                profileInput.value = '';
                                                previewImg.src = '';
                                                previewImg.style.display = 'none';
                                                previewContainer.style.display = 'none';
                                                removeImg.style.display = 'none';
                                                dropzoneContent.style.display = '';
                                            });
                                        }
                                        setupProfileFormEvents(document.querySelector('.profileForm'));
                                        document.getElementById('addMoreBtn').addEventListener('click', function () {
                                            profileFormCount++;
                                            const container = document.getElementById('profileFormsContainer');
                                            const firstForm = container.querySelector('.profileForm');
                                            const clone = firstForm.cloneNode(true);
                                            // Reset radio buttons and file input
                                            clone.querySelectorAll('input[type="radio"]').forEach(radio => radio.checked = false);
                                            clone.querySelector('.profile_image').value = '';
                                            // Reset preview
                                            clone.querySelector('.previewImg').src = '';
                                            clone.querySelector('.previewImg').style.display = 'none';
                                            clone.querySelector('.preview-container').style.display = 'none';
                                            clone.querySelector('.removeImg').style.display = 'none';
                                            clone.querySelector('.dropzone-content').style.display = '';
                                            setupProfileFormEvents(clone);
                                            container.appendChild(clone);
                                        });
                                        document.getElementById('addProfilePicturesBtn').addEventListener('click', function (e) {
                                            e.preventDefault();
                                            const forms = document.querySelectorAll('.profileForm');
                                            let valid = true;
                                            forms.forEach(form => {
                                                const genderInput = form.querySelector('input[type="radio"][name="gender[]"]:checked');
                                                const fileInput = form.querySelector('.profile_image');
                                                if (!genderInput) {
                                                    valid = false;
                                                    alert('Please select gender for all.');
                                                }
                                                if (!fileInput.files[0]) {
                                                    valid = false;
                                                    alert('Please select a profile picture for all.');
                                                } else if (fileInput.files[0].size > 512000) {
                                                    valid = false;
                                                    alert('Image must be less than 500KB');
                                                }
                                            });
                                            if (!valid) return;
                                            // Create a new form to submit all data
                                            const submitForm = document.createElement('form');
                                            submitForm.method = 'POST';
                                            submitForm.enctype = 'multipart/form-data';
                                            submitForm.action = document.querySelector('.profileForm').action;
                                            forms.forEach(form => {
                                                const genderInput = form.querySelector('input[type="radio"][name="gender[]"]:checked');
                                                const fileInput = form.querySelector('.profile_image');
                                                if (genderInput) {
                                                    const genderClone = document.createElement('input');
                                                    genderClone.type = 'hidden';
                                                    genderClone.name = 'gender[]';
                                                    genderClone.value = genderInput.value;
                                                    submitForm.appendChild(genderClone);
                                                }
                                                if (fileInput.files[0]) {
                                                    // File inputs can't be cloned with files, so append the original
                                                    submitForm.appendChild(fileInput);
                                                }
                                            });
                                            document.body.appendChild(submitForm);
                                            submitForm.submit();
                                        });
                                    </script>
                                </div> <!-- end card-body-->
                            </div> <!-- end card-->
                        </div> <!-- end col-->
                    </div>

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
</body>

</html>