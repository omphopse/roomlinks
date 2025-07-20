<?php require_once __DIR__ . '/../../config/constants.php'; ?>
<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../models/UserModel.php';
$userModel = new UserModel();
$user = null;
if (isset($_SESSION['user_id'])) {
    $user = $userModel->getAdminById($_SESSION['user_id']);
}
?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="light" data-menu-color="dark" data-topbar-color="light">

<head>
  <meta charset="utf-8" />
  <title>Profile | Admin Dashboard</title>
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
                            <div class="col-lg-6">
                                <h4 class="page-title mb-0">Admin Profile</h4>
                            </div>
                            <div class="col-lg-6">
                                <div class="d-none d-lg-block">
                                    <ol class="breadcrumb m-0 float-end">
                                        <li class="breadcrumb-item"><a href="<?php echo APP_URL; ?>/admin/dashboard">Admin</a></li>
                                        <li class="breadcrumb-item active">Partner Profile</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end page title -->

                    <?php if (isset($_SESSION['success'])): ?>
                        <div class="alert alert-success"><?= $_SESSION['success'];
                        unset($_SESSION['success']); ?></div>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger"><?= $_SESSION['error'];
                        unset($_SESSION['error']); ?></div>
                    <?php endif; ?>
                    
                    <div class="d-flex justify-content-center">
                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="header-title">Admin's Profile</h4>

                                    <form class="needs-validation" novalidate method="POST"
                                        action="<?php echo APP_URL; ?>/admin/profile" id="adminProfileForm">
                                        <div class="position-relative mb-3 col-lg-12">
                                            <label for="validationTooltip01" class="form-label">Name</label>
                                            <input type="text" class="form-control" id="validationTooltip01" name="name"
                                                placeholder="Name"
                                                value="<?php echo htmlspecialchars($user['name'] ?? ''); ?>"
                                                readonly>
                                            <div class="valid-feedback">
                                                Looks good!
                                            </div>
                                            <div class="invalid-feedback">
                                                Please Enter Full name
                                            </div>
                                        </div>
                                        <div class="position-relative mb-3 col-lg-12">
                                            <label for="validationTooltip02" class="form-label">Email</label>
                                            <input type="email" class="form-control" id="validationTooltip02"
                                                name="email" placeholder="name@example.com"
                                                value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>"
                                                readonly pattern="^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$"
                                                title="Please enter a valid email address">
                                            <div class="valid-feedback">
                                                Looks good!
                                            </div>
                                            <div class="invalid-feedback">
                                                Please enter valid email.
                                            </div>
                                        </div>
                                        <div class="position-relative mb-3 col-lg-12">
                                            <label for="validationTooltip03" class="form-label">Phone number</label>
                                            <input type="tel" class="form-control" id="validationTooltip03" name="phone"
                                                placeholder="1234567890"
                                                value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>"
                                                readonly pattern="^\d{10}$"
                                                title="Please enter a valid 10-digit phone number">
                                            <div class="invalid-feedback">
                                                Please enter valid phone number.
                                            </div>
                                        </div>

                                        <div class="position-relative mb-3 col-lg-12">
                                            <label for="changePassword" class="form-label">Change Password</label>
                                            <div class="input-group">
                                                <input type="password" class="form-control" id="changePassword" name="change_password" placeholder="Enter new password" minlength="6" required>
                                                <button class="btn btn-outline-secondary" type="button" id="toggleChangePassword">
                                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                                </button>
                                            </div>
                                            <div class="invalid-feedback" id="changePasswordFeedback" style="display:none;">
                                                Password must be at least 6 characters.
                                            </div>
                                        </div>

                                        <div class="position-relative mb-3 col-lg-12">
                                            <label for="confirmPassword" class="form-label">Confirm Password</label>
                                            <div class="input-group">
                                                <input type="password" class="form-control" id="confirmPassword" name="confirm_password" placeholder="Confirm new password" minlength="6" required>
                                                <button class="btn btn-outline-secondary" type="button" id="toggleConfirmPassword">
                                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                                </button>
                                            </div>
                                            <div class="invalid-feedback" id="confirmPasswordFeedback" style="display:none;">
                                                Passwords do not match.
                                            </div>
                                        </div>

                                        <button class="btn btn-primary" type="submit">Update Password</button>

                                    </form>

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
  <script src="<?php echo APP_URL; ?>/admin-asset/libs/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
  <script src="<?php echo APP_URL; ?>/admin-asset/libs/datatables.net-select/js/dataTables.select.min.js"></script>
  <script src="<?php echo APP_URL; ?>/admin-asset/libs/pdfmake/build/pdfmake.min.js"></script>
  <script src="<?php echo APP_URL; ?>/admin-asset/libs/pdfmake/build/vfs_fonts.js"></script>
  <!-- third party js ends -->

  <script>
    // JavaScript for confirm password and minimum length validation
    (function () {
      'use strict'

      var form = document.getElementById('adminProfileForm');
      var changePassword = document.getElementById('changePassword');
      var confirmPassword = document.getElementById('confirmPassword');
      var changePasswordFeedback = document.getElementById('changePasswordFeedback');
      var confirmPasswordFeedback = document.getElementById('confirmPasswordFeedback');

      var toggleChangePassword = document.getElementById('toggleChangePassword');
      var toggleConfirmPassword = document.getElementById('toggleConfirmPassword');

      toggleChangePassword.addEventListener('click', function () {
        if (changePassword.type === 'password') {
          changePassword.type = 'text';
          toggleChangePassword.innerHTML = '<i class="fa fa-eye-slash" aria-hidden="true"></i>';
        } else {
          changePassword.type = 'password';
          toggleChangePassword.innerHTML = '<i class="fa fa-eye" aria-hidden="true"></i>';
        }
      });

      toggleConfirmPassword.addEventListener('click', function () {
        if (confirmPassword.type === 'password') {
          confirmPassword.type = 'text';
          toggleConfirmPassword.innerHTML = '<i class="fa fa-eye-slash" aria-hidden="true"></i>';
        } else {
          confirmPassword.type = 'password';
          toggleConfirmPassword.innerHTML = '<i class="fa fa-eye" aria-hidden="true"></i>';
        }
      });

      form.addEventListener('submit', function (event) {
        var valid = true;

        // Check minimum length for change password
        if (changePassword.value.length < 6) {
          changePassword.classList.add('is-invalid');
          changePasswordFeedback.style.display = 'block';
          valid = false;
        } else {
          changePassword.classList.remove('is-invalid');
          changePasswordFeedback.style.display = 'none';
        }

        // Check if passwords match
        if (changePassword.value !== confirmPassword.value) {
          confirmPassword.classList.add('is-invalid');
          confirmPasswordFeedback.style.display = 'block';
          valid = false;
        } else {
          confirmPassword.classList.remove('is-invalid');
          confirmPasswordFeedback.style.display = 'none';
        }

        if (!valid) {
          event.preventDefault();
          event.stopPropagation();
        }
      }, false);
    })();
  </script>

</body>

</html>
