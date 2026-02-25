<?php
require_once __DIR__ . '/../models/Vibes.php';
require_once __DIR__ . '/../models/Location.php';

require_once __DIR__ . '/AuditLogController.php';
require_once __DIR__ . '/../config/database.php';
class AdminController
{
    // Admin dashboard
    public function dashboard()
    {
        require_once __DIR__ . '/../models/AdminModel.php';
        $totalUsers = AdminModel::getTotalUsers();
        $totalListings = AdminModel::getTotalListings();
        $totalRooms = AdminModel::getTotalRooms();
        $totalPGs = AdminModel::getTotalPGs();
        $recentListings = AdminModel::getRecentListings(10);
        $recentUsers = AdminModel::getRecentUsers(10);
        include __DIR__ . '/../views/admin/dashboard.php';
    }

    // Show all users with profile and location info and filters
    public function showUsers()
    {
        require_once __DIR__ . '/../models/AdminModel.php';
        // Default to current month if not set
        $start_date = $_GET['start_date'] ?? date('Y-m-01');
        $end_date = $_GET['end_date'] ?? date('Y-m-t');
        $filters = [
            'gender' => $_GET['gender'] ?? 'any',
            'who_are_you' => $_GET['who_are_you'] ?? 'any',
            'start_date' => $start_date,
            'end_date' => $end_date,
        ];
        $users = AdminModel::getUsersWithProfileAndLocation($filters);
        include __DIR__ . '/../views/admin/users.php';
    }

    // Show all listings with owner info and filters
    public function showListings()
    {
        require_once __DIR__ . '/../models/AdminModel.php';
        // Default to current month if not set
        $start_date = $_GET['start_date'] ?? date('Y-m-01');
        $end_date = $_GET['end_date'] ?? date('Y-m-t');
        $filters = [
            'role' => $_GET['role'] ?? 'any',
            'gender' => $_GET['gender'] ?? 'any',
            'occupancy' => $_GET['occupancy'] ?? 'any',
            'start_date' => $start_date,
            'end_date' => $end_date,
        ];
        $listings = AdminModel::getListingsWithOwner($filters);
        include __DIR__ . '/../views/admin/listings.php';
    }

    // Show all reports for admin
    public function showReports()
    {
        require_once __DIR__ . '/../models/Report.php';
        require_once __DIR__ . '/../models/UserModel.php';
        $pdo = getPDO();
        $start_date = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-01');
        $end_date = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-t');
        $stmt = $pdo->prepare('SELECT * FROM report WHERE DATE(created_at) >= ? AND DATE(created_at) <= ? ORDER BY created_at DESC');
        $stmt->execute([$start_date, $end_date]);
        $reportsRaw = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $userModel = new UserModel();
        $reports = [];
        foreach ($reportsRaw as $report) {
            // Get reporter email
            $userStmt = $pdo->prepare('SELECT email FROM user WHERE id = ? LIMIT 1');
            $userStmt->execute([$report['user_id']]);
            $user = $userStmt->fetch(PDO::FETCH_ASSOC);
            $report['user_email'] = $user ? $user['email'] : '';
            $reports[] = $report;
        }
        include __DIR__ . '/../views/admin/roport.php';
    }

    // Edit report status
    public function editReportStatus()
    {
        require_once __DIR__ . '/../models/Report.php';
        if (isset($_POST['report_id'], $_POST['status'])) {
            $report_id = intval($_POST['report_id']);
            $status = $_POST['status'];
            if (in_array($status, ['reported', 'processing', 'resolved'])) {
                $success = Report::updateStatus($report_id, $status);
                if ($success) {
                    // Log to audit_logs using AuditLogController
                    $pdo = getPDO();
                    $auditLogController = new AuditLogController($pdo);
                    $ip = $_SERVER['REMOTE_ADDR'] ?? null;
                    $adminId = $_SESSION['user_id'] ?? null;
                    $userRole = isset($_SESSION['user_role']) ? strtolower(trim($_SESSION['user_role'])) : 'admin';
                    $details = json_encode([
                        'action' => 'editReportStatus',
                        'reportId' => $report_id,
                        'newStatus' => $status
                    ]);
                    $auditLogController->logAction($adminId, $userRole, 'UPDATE', 'report', $report_id, $details, $ip);
                    $_SESSION['success'] = 'Report status updated.';
                } else {
                    $_SESSION['error'] = 'Failed to update status.';
                }
            } else {
                $_SESSION['error'] = 'Invalid status.';
            }
        } else {
            $_SESSION['error'] = 'Invalid request.';
        }
        header('Location: ' . APP_URL . '/admin/reports');
        exit();
    }

    // Show all property highlights (for highlights.php)
    public function showHighlights()
    {
        require_once __DIR__ . '/../models/PropertyHighlights.php';
        $highlightsModel = new PropertyHighlights();
        $start_date = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-01');
        $end_date = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-t');
        $highlights = $highlightsModel->getHighlightsByDate($start_date, $end_date);
        include __DIR__ . '/../views/admin/highlights.php';
    }

    // Edit a property highlight
    public function editHighlight()
    {
        require_once __DIR__ . '/../models/PropertyHighlights.php';
        $highlightsModel = new PropertyHighlights();
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['highlight_id'], $_POST['highlight_name'])) {
            $id = intval($_POST['highlight_id']);
            $name = trim($_POST['highlight_name']);
            if ($name !== '') {
                // Check for duplicate name (exclude current id)
                if ($highlightsModel->highlightExistsForOtherId($name, $id)) {
                    $_SESSION['error'] = 'Highlight name already exists.';
                } else {
                    $highlightsModel->updateHighlight($id, $name);
                    // Log to audit_logs using AuditLogController
                    $pdo = getPDO();
                    $auditLogController = new AuditLogController($pdo);
                    $ip = $_SERVER['REMOTE_ADDR'] ?? null;
                    $adminId = $_SESSION['user_id'] ?? null;
                    $userRole = isset($_SESSION['user_role']) ? strtolower(trim($_SESSION['user_role'])) : 'admin';
                    $details = json_encode([
                        'action' => 'editHighlight',
                        'highlightId' => $id,
                        'newName' => $name
                    ]);
                    $auditLogController->logAction($adminId, $userRole, 'UPDATE', 'property_highlights', $id, $details, $ip);
                    $_SESSION['success'] = 'Highlight updated successfully!';
                }
            } else {
                $_SESSION['error'] = 'Highlight name cannot be empty!';
            }
        }
        header('Location: ' . APP_URL . '/admin/highlights');
        exit();
    }

    // Delete a property highlight
    public function deleteHighlight()
    {
        require_once __DIR__ . '/../models/PropertyHighlights.php';
        $highlightsModel = new PropertyHighlights();
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['highlight_id'])) {
            $id = intval($_POST['highlight_id']);
            $highlightsModel->deleteHighlight($id);
            // Log to audit_logs using AuditLogController
            $pdo = getPDO();
            $auditLogController = new AuditLogController($pdo);
            $ip = $_SERVER['REMOTE_ADDR'] ?? null;
            $adminId = $_SESSION['user_id'] ?? null;
            $userRole = isset($_SESSION['user_role']) ? strtolower(trim($_SESSION['user_role'])) : 'admin';
            $details = json_encode([
                'action' => 'deleteHighlight',
                'highlightId' => $id
            ]);
            $auditLogController->logAction($adminId, $userRole, 'DELETE', 'property_highlights', $id, $details, $ip);
            $_SESSION['success'] = 'Highlight deleted successfully!';
        } else {
            $_SESSION['error'] = 'Invalid request.';
        }
        header('Location: ' . APP_URL . '/admin/highlights');
        exit();
    }

    // Add new property highlight
    public function addPropertyHighlight()
    {
        require_once __DIR__ . '/../models/PropertyHighlights.php';
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['highlight_name'])) {
            $names = array_map('trim', $_POST['highlight_name']);
            $names = array_filter($names, function ($n) {
                return !empty($n);
            });
            if (!empty($names)) {
                $highlightsModel = new PropertyHighlights();
                $added = [];
                $duplicates = [];
                foreach ($names as $name) {
                    if ($highlightsModel->highlightExists($name)) {
                        $duplicates[] = $name;
                    } else {
                        if ($highlightsModel->addPropertyHighlight($name)) {
                            $added[] = $name;
                            // Log to audit_logs using AuditLogController
                            $pdo = getPDO();
                            $auditLogController = new AuditLogController($pdo);
                            $ip = $_SERVER['REMOTE_ADDR'] ?? null;
                            $adminId = $_SESSION['user_id'] ?? null;
                            $userRole = isset($_SESSION['user_role']) ? strtolower(trim($_SESSION['user_role'])) : 'admin';
                            $details = json_encode([
                                'action' => 'addPropertyHighlight',
                                'highlightName' => $name
                            ]);
                            // Get last inserted ID if possible
                            if (method_exists($highlightsModel, 'getLastInsertId')) {
                                $highlightId = $highlightsModel->getLastInsertId();
                            } else {
                                $highlightId = null;
                            }
                            $auditLogController->logAction($adminId, $userRole, 'CREATE', 'property_highlights', $highlightId, $details, $ip);
                        }
                    }
                }
                if (!empty($added)) {
                    $_SESSION['success'] = 'Highlights added successfully!';
                }
                if (!empty($duplicates)) {
                    $_SESSION['error'] = 'Some highlights already exist: ' . implode(', ', $duplicates);
                }
                if (empty($added) && empty($duplicates)) {
                    $_SESSION['error'] = 'Failed to add highlights.';
                }
            } else {
                $_SESSION['error'] = 'Please enter at least one highlight.';
            }
            header('Location: ' . APP_URL . '/admin/addhighlights');
            exit();
        }
    }

    // Show all property highlights
    public function showAddHighlightsPage()
    {
        require_once __DIR__ . '/../models/PropertyHighlights.php';
        $highlightsModel = new PropertyHighlights();
        $highlights = $highlightsModel->getPropertyHighlights();
        include __DIR__ . '/../views/admin/addhighlights.php';
    }
    private $userModel;

    public function __construct($userModel = null)
    {
        if ($userModel === null) {
            require_once __DIR__ . '/../models/UserModel.php';
            $userModel = new UserModel();
        }
        $this->userModel = $userModel;
    }


    public function logout()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        // Clear persistent login cookie and DB token
        require_once __DIR__ . '/../helpers/PersistentLogin.php';
        $persistentLogin = new PersistentLogin();
        $persistentLogin->clearToken();
        // Unset all session variables
        $_SESSION = array();
        // Destroy session cookie if exists
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }
        // Destroy session
        session_destroy();
        header('Location: ' . APP_URL . '/');
        exit();
    }

    public function profile()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            header('Location: ' . APP_URL . '/admin-login');
            exit();
        }
        $userModel = $this->userModel;
        $user = $userModel->getAdminById($_SESSION['user_id']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $newPassword = $_POST['change_password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            if (strlen($newPassword) < 6) {
                $_SESSION['error'] = 'Password must be at least 6 characters.';
            } elseif ($newPassword !== $confirmPassword) {
                $_SESSION['error'] = 'Passwords do not match.';
            } else {
                $updated = $userModel->updateAdminPassword($_SESSION['user_id'], $newPassword);
                if ($updated) {
                    $_SESSION['success'] = 'Password updated successfully!';
                } else {
                    $_SESSION['error'] = 'Failed to update password.';
                }
                // Refresh user data
                $user = $userModel->getAdminById($_SESSION['user_id']);
            }
        }
        include __DIR__ . '/../views/admin/profile.php';
    }

    // Vibes Management
    public function addVibes()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $db = getPDO();
        $vibesModel = new Vibes($db);
        $results = $vibesModel->addVibes($_POST, $_FILES);
        $success_msgs = [];
        $error_msgs = [];
        foreach ($results as $res) {
            if (!empty($res['success'])) {
                $success_msgs[] = $res['message'];
                // Log to audit_logs using AuditLogController
                $pdo = getPDO();
                $auditLogController = new AuditLogController($pdo);
                $ip = $_SERVER['REMOTE_ADDR'] ?? null;
                $adminId = $_SESSION['user_id'] ?? null;
                $userRole = isset($_SESSION['user_role']) ? strtolower(trim($_SESSION['user_role'])) : 'admin';
                $details = json_encode([
                    'action' => 'addVibes',
                    'vibeData' => $res
                ]);
                // If Vibes model provides last insert ID, use it
                if (isset($res['id'])) {
                    $vibeId = $res['id'];
                } else {
                    $vibeId = null;
                }
                $auditLogController->logAction($adminId, $userRole, 'CREATE', 'vibes', $vibeId, $details, $ip);
            } else {
                $error_msgs[] = $res['message'];
            }
        }
        if ($success_msgs) {
            $_SESSION['success'] = implode('<br>', $success_msgs);
        }
        if ($error_msgs) {
            $_SESSION['error'] = implode('<br>', $error_msgs);
        }
        header('Location: ' . APP_URL . '/admin/addnewvibes');
        exit;
    }

    public function showVibes()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $db = getPDO();
        $vibesModel = new Vibes($db);
        // Use start_date and end_date from GET, fallback to current month
        $start_date = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-01');
        $end_date = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-t');
        $vibes = $vibesModel->getVibesByDate($start_date, $end_date);
        $totalVibes = count($vibes);
        include __DIR__ . '/../views/admin/vibes.php';
    }

    public function editVibe()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $db = getPDO();
        $vibesModel = new Vibes($db);
        $id = $_POST['vibe_id'] ?? null;
        $name = $_POST['vibe_name'] ?? '';
        $image = isset($_FILES['vibe_image']) ? $_FILES['vibe_image'] : null;
        if ($id && $name) {
            $success = $vibesModel->updateVibe($id, $name, $image);
            if ($success) {
                // Log to audit_logs using AuditLogController
                $pdo = getPDO();
                $auditLogController = new AuditLogController($pdo);
                $ip = $_SERVER['REMOTE_ADDR'] ?? null;
                $adminId = $_SESSION['user_id'] ?? null;
                $userRole = isset($_SESSION['user_role']) ? strtolower(trim($_SESSION['user_role'])) : 'admin';
                $details = json_encode([
                    'action' => 'editVibe',
                    'vibeId' => $id,
                    'newName' => $name,
                    'image' => $image
                ]);
                $auditLogController->logAction($adminId, $userRole, 'UPDATE', 'vibes', $id, $details, $ip);
                $_SESSION['success'] = 'Vibe updated successfully!';
            } else {
                $_SESSION['error'] = 'Failed to update vibe.';
            }
        } else {
            $_SESSION['error'] = 'Missing required fields.';
        }
        header('Location: ' . APP_URL . '/admin/vibes');
        exit;
    }

    public function deleteVibe()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $db = getPDO();
        $vibesModel = new Vibes($db);
        $id = $_POST['vibe_id'] ?? null;
        if ($id) {
            $success = $vibesModel->deleteVibe($id);
            if ($success) {
                // Log to audit_logs using AuditLogController
                require_once __DIR__ . '/AuditLogController.php';
                require_once __DIR__ . '/../config/database.php';
                $pdo = getPDO();
                $auditLogController = new AuditLogController($pdo);
                $ip = $_SERVER['REMOTE_ADDR'] ?? null;
                $adminId = $_SESSION['user_id'] ?? null;
                $userRole = isset($_SESSION['user_role']) ? strtolower(trim($_SESSION['user_role'])) : 'admin';
                $details = json_encode([
                    'action' => 'deleteVibe',
                    'vibeId' => $id
                ]);
                $auditLogController->logAction($adminId, $userRole, 'DELETE', 'vibes', $id, $details, $ip);
                $_SESSION['success'] = 'Vibe deleted successfully!';
            } else {
                $_SESSION['error'] = 'Failed to delete vibe.';
            }
        } else {
            $_SESSION['error'] = 'Missing vibe ID.';
        }
        header('Location: ' . APP_URL . '/admin/vibes');
        exit;
    }

    public function addNewAmenities()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        require_once __DIR__ . '/../models/Amenities.php';
        $amenitiesModel = new Amenities();
        $results = $amenitiesModel->addAmenities($_POST, $_FILES);
        $success_msgs = [];
        $error_msgs = [];
        foreach ($results as $res) {
            if (!empty($res['success'])) {
                $success_msgs[] = $res['message'];
                // Log to audit_logs using AuditLogController
                $pdo = getPDO();
                $auditLogController = new AuditLogController($pdo);
                $ip = $_SERVER['REMOTE_ADDR'] ?? null;
                $adminId = $_SESSION['user_id'] ?? null;
                $userRole = isset($_SESSION['user_role']) ? strtolower(trim($_SESSION['user_role'])) : 'admin';
                $details = json_encode([
                    'action' => 'addNewAmenities',
                    'amenityData' => $res
                ]);
                // If Amenities model provides last insert ID, use it
                if (isset($res['id'])) {
                    $amenityId = $res['id'];
                } else {
                    $amenityId = null;
                }
                $auditLogController->logAction($adminId, $userRole, 'CREATE', 'amenities', $amenityId, $details, $ip);
            } else {
                $error_msgs[] = $res['message'];
            }
        }
        if ($success_msgs) {
            $_SESSION['success'] = implode('<br>', $success_msgs);
        }
        if ($error_msgs) {
            $_SESSION['error'] = implode('<br>', $error_msgs);
        }
        header('Location: ' . APP_URL . '/admin/addnewamenities');
        exit;
    }

    public function showAmenities()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        require_once __DIR__ . '/../models/Amenities.php';
        $amenitiesModel = new Amenities();
        $start_date = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-01');
        $end_date = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-t');
        $amenities = $amenitiesModel->getAmenitiesByDate($start_date, $end_date);
        $totalAmenities = count($amenities);
        include __DIR__ . '/../views/admin/amenities.php';
    }

    public function editAmenity()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        require_once __DIR__ . '/../models/Amenities.php';
        $amenitiesModel = new Amenities();
        $id = $_POST['amenity_id'] ?? null;
        $name = $_POST['amenity_name'] ?? '';
        $image = isset($_FILES['amenity_image']) ? $_FILES['amenity_image'] : null;
        if ($id && $name) {
            $success = $amenitiesModel->updateAmenity($id, $name, $image);
            if ($success) {
                $_SESSION['success'] = 'Amenity updated successfully!';
                // Log to audit_logs using AuditLogController
                require_once __DIR__ . '/AuditLogController.php';
                require_once __DIR__ . '/../config/database.php';
                $pdo = getPDO();
                $auditLogController = new AuditLogController($pdo);
                $ip = $_SERVER['REMOTE_ADDR'] ?? null;
                $adminId = $_SESSION['user_id'] ?? null;
                $userRole = isset($_SESSION['user_role']) ? strtolower(trim($_SESSION['user_role'])) : 'admin';
                $details = json_encode([
                    'action' => 'editAmenity',
                    'amenityId' => $id,
                    'newName' => $name,
                    'image' => $image
                ]);
                $auditLogController->logAction($adminId, $userRole, 'UPDATE', 'amenities', $id, $details, $ip);
            } else {
                $_SESSION['error'] = 'Failed to update amenity.';
            }
        } else {
            $_SESSION['error'] = 'Missing required fields.';
        }
        header('Location: ' . APP_URL . '/admin/amenities');
        exit;
    }

    public function deleteAmenity()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        require_once __DIR__ . '/../models/Amenities.php';
        $amenitiesModel = new Amenities();
        $id = $_POST['amenity_id'] ?? null;
        if ($id) {
            $success = $amenitiesModel->deleteAmenity($id);
            if ($success) {
                $_SESSION['success'] = 'Amenity deleted successfully!';
                // Log to audit_logs using AuditLogController
                $pdo = getPDO();
                $auditLogController = new AuditLogController($pdo);
                $ip = $_SERVER['REMOTE_ADDR'] ?? null;
                $adminId = $_SESSION['user_id'] ?? null;
                $userRole = isset($_SESSION['user_role']) ? strtolower(trim($_SESSION['user_role'])) : 'admin';
                $details = json_encode([
                    'action' => 'deleteAmenity',
                    'amenityId' => $id
                ]);
                $auditLogController->logAction($adminId, $userRole, 'DELETE', 'amenities', $id, $details, $ip);
            } else {
                $_SESSION['error'] = 'Failed to delete amenity.';
            }
        } else {
            $_SESSION['error'] = 'Missing amenity ID.';
        }
        header('Location: ' . APP_URL . '/admin/amenities');
        exit;
    }

    public function addProfilePicture()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $genders = isset($_POST['gender']) ? $_POST['gender'] : [];
            $files = isset($_FILES['profile_image']) ? $_FILES['profile_image'] : [];
            require_once __DIR__ . '/../models/ProfilePicture.php';
            $profilePictureModel = new ProfilePicture();
            $results = $profilePictureModel->addProfilePictures($genders, $files);
            $success_msgs = [];
            $error_msgs = [];
            foreach ($results as $res) {
                if (!empty($res['success'])) {
                    $success_msgs[] = $res['message'];
                    // Log to audit_logs using AuditLogController
                    $pdo = getPDO();
                    $auditLogController = new AuditLogController($pdo);
                    $ip = $_SERVER['REMOTE_ADDR'] ?? null;
                    $adminId = $_SESSION['user_id'] ?? null;
                    $userRole = isset($_SESSION['user_role']) ? strtolower(trim($_SESSION['user_role'])) : 'admin';
                    $details = json_encode([
                        'action' => 'addProfilePicture',
                        'profilePictureData' => $res
                    ]);
                    // If ProfilePicture model provides last insert ID, use it
                    if (isset($res['id'])) {
                        $profilePictureId = $res['id'];
                    } else {
                        $profilePictureId = null;
                    }
                    $auditLogController->logAction($adminId, $userRole, 'CREATE', 'profile_pictures', $profilePictureId, $details, $ip);
                } else {
                    $error_msgs[] = $res['message'];
                }
            }
            if ($success_msgs) {
                $_SESSION['success'] = implode('<br>', $success_msgs);
            }
            if ($error_msgs) {
                $_SESSION['error'] = implode('<br>', $error_msgs);
            }
            header('Location: ' . APP_URL . '/admin/addprofilepictures');
            exit;
        }
    }

    public function showProfilePictures()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        require_once __DIR__ . '/../models/ProfilePicture.php';
        $profilePictureModel = new ProfilePicture();
        $start_date = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-01');
        $end_date = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-t');
        $pictures = $profilePictureModel->getProfilePictures($start_date, $end_date);
        include __DIR__ . '/../views/admin/profilepicture.php';
    }

    public function deleteProfilePicture()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        require_once __DIR__ . '/../models/ProfilePicture.php';
        $profilePictureModel = new ProfilePicture();
        $id = $_POST['profile_picture_id'] ?? null;
        if ($id) {
            $success = $profilePictureModel->deleteProfilePicture($id);
            if ($success) {
                $_SESSION['success'] = 'Profile picture deleted successfully!';
                // Log to audit_logs using AuditLogController
                $pdo = getPDO();
                $auditLogController = new AuditLogController($pdo);
                $ip = $_SERVER['REMOTE_ADDR'] ?? null;
                $adminId = $_SESSION['user_id'] ?? null;
                $userRole = isset($_SESSION['user_role']) ? strtolower(trim($_SESSION['user_role'])) : 'admin';
                $details = json_encode([
                    'action' => 'deleteProfilePicture',
                    'profilePictureId' => $id
                ]);
                $auditLogController->logAction($adminId, $userRole, 'DELETE', 'profile_pictures', $id, $details, $ip);
            } else {
                $_SESSION['error'] = 'Failed to delete profile picture.';
            }
        } else {
            $_SESSION['error'] = 'Missing profile picture ID.';
        }
        header('Location: ' . APP_URL . '/admin/profilepictures');
        exit;
    }

    // City Management
    public function addCities()
    {
        require_once __DIR__ . '/../models/City.php';
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['location_name'])) {
            $locations = array_map('trim', $_POST['location_name']);
            $locations = array_filter($locations, function ($loc) {
                return !empty($loc);
            });
            if (!empty($locations)) {
                $db = getPDO();
                $cityModel = new City($db);
                $cityModel->addCities($locations);
                // Log to audit_logs using AuditLogController
                $pdo = getPDO();
                $auditLogController = new AuditLogController($pdo);
                $ip = $_SERVER['REMOTE_ADDR'] ?? null;
                $adminId = $_SESSION['user_id'] ?? null;
                $userRole = isset($_SESSION['user_role']) ? strtolower(trim($_SESSION['user_role'])) : 'admin';
                $details = json_encode([
                    'action' => 'addCities',
                    'cities' => $locations
                ]);
                $auditLogController->logAction($adminId, $userRole, 'CREATE', 'cities', null, $details, $ip);
                $_SESSION['success'] = 'Cities added successfully!';
            } else {
                $_SESSION['error'] = 'Please enter at least one city.';
            }
            header('Location: ' . APP_URL . '/admin/addcity');
            exit();
        }
    }

    // Show all cities
    public function showCities()
    {
        require_once __DIR__ . '/../models/City.php';
        $db = getPDO();
        $cityModel = new City($db);
        $start_date = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-01');
        $end_date = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-t');
        $locations = $cityModel->getCitiesByDate($start_date, $end_date);
        include __DIR__ . '/../views/admin/cities.php';
    }

    // Edit a city
    public function editCity()
    {
        require_once __DIR__ . '/../models/City.php';
        $db = getPDO();
        $cityModel = new City($db);
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['location_id'], $_POST['location_name'])) {
            $id = intval($_POST['location_id']);
            $name = trim($_POST['location_name']);
            if ($name !== '') {
                $cityModel->updateCity($id, $name);
                $_SESSION['success'] = 'City updated successfully!';
                // Log to audit_logs using AuditLogController
                $pdo = getPDO();
                $auditLogController = new AuditLogController($pdo);
                $ip = $_SERVER['REMOTE_ADDR'] ?? null;
                $adminId = $_SESSION['user_id'] ?? null;
                $userRole = isset($_SESSION['user_role']) ? strtolower(trim($_SESSION['user_role'])) : 'admin';
                $details = json_encode([
                    'action' => 'editCity',
                    'cityId' => $id,
                    'newName' => $name
                ]);
                $auditLogController->logAction($adminId, $userRole, 'UPDATE', 'cities', $id, $details, $ip);
            } else {
                $_SESSION['error'] = 'City name cannot be empty!';
            }
        }
        header('Location: ' . APP_URL . '/admin/cities');
        exit();
    }

    // Delete a city
    public function deleteCity()
    {
        require_once __DIR__ . '/../models/City.php';
        $db = getPDO();
        $cityModel = new City($db);
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['location_id'])) {
            $id = intval($_POST['location_id']);
            $cityModel->deleteCity($id);
            $_SESSION['success'] = 'City deleted successfully!';
            // Log to audit_logs using AuditLogController
            require_once __DIR__ . '/AuditLogController.php';
            require_once __DIR__ . '/../config/database.php';
            $pdo = getPDO();
            $auditLogController = new AuditLogController($pdo);
            $ip = $_SERVER['REMOTE_ADDR'] ?? null;
            $adminId = $_SESSION['user_id'] ?? null;
            $userRole = isset($_SESSION['user_role']) ? strtolower(trim($_SESSION['user_role'])) : 'admin';
            $details = json_encode([
                'action' => 'deleteCity',
                'cityId' => $id
            ]);
            $auditLogController->logAction($adminId, $userRole, 'DELETE', 'cities', $id, $details, $ip);
        } else {
            $_SESSION['error'] = 'Invalid request.';
        }
        header('Location: ' . APP_URL . '/admin/cities');
        exit();
    }

    // Location Management
    public function addLocations()
    {
        require_once __DIR__ . '/../models/Location.php';
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $locationModel = new Location();
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['city_id'], $_POST['location_name'])) {
            $city_id = intval($_POST['city_id']);
            $names = $_POST['location_name'];
            $added = 0;
            $errors = [];
            foreach ($names as $name) {
                $name = trim($name);
                if ($name === '') continue;
                try {
                    if ($locationModel->addLocation($city_id, $name)) {
                        $added++;
                    }
                } catch (Exception $e) {
                    $errors[] = $name . ': ' . $e->getMessage();
                }
            }
            if ($added) {
                $_SESSION['success'] = "Added $added location(s) successfully.";
            }
            if ($errors) {
                $_SESSION['error'] = implode(' | ', $errors);
            }
        } else {
            $_SESSION['error'] = 'Invalid request.';
        }
        header('Location: ' . APP_URL . '/admin/addlocation');
        exit();
    }

    public function showAddLocationsPage()
    {
        require_once __DIR__ . '/../models/City.php';
        $pdo = getPDO();
        $stmt = $pdo->prepare('SELECT * FROM city ORDER BY location');
        $stmt->execute();
        $cities = $stmt->fetchAll(PDO::FETCH_ASSOC);
        include __DIR__ . '/../views/admin/addlocation.php';
    }

    public function showLocations()
    {
        $locationModel = new Location();
        // optional filters from GET
        $city_id = isset($_GET['city_id']) && $_GET['city_id'] !== '' ? intval($_GET['city_id']) : null;
        $start_date = isset($_GET['start_date']) && $_GET['start_date'] !== '' ? $_GET['start_date'] : null;
        $end_date = isset($_GET['end_date']) && $_GET['end_date'] !== '' ? $_GET['end_date'] : null;
        $locations = $locationModel->getAllLocations($city_id, $start_date, $end_date);
        $pdo = getPDO();
        $stmt = $pdo->prepare('SELECT * FROM city ORDER BY location');
        $stmt->execute();
        $cities = $stmt->fetchAll(PDO::FETCH_ASSOC);
        include __DIR__ . '/../views/admin/locations.php';
    }

    public function editLocation()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $locationModel = new Location();
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['location_id'], $_POST['city_id'], $_POST['location_name'])) {
            $id = intval($_POST['location_id']);
            $city_id = intval($_POST['city_id']);
            $name = trim($_POST['location_name']);
            if ($locationModel->updateLocation($id, $city_id, $name)) {
                $_SESSION['success'] = 'Location updated successfully!';
            } else {
                $_SESSION['error'] = 'Failed to update location.';
            }
        } else {
            $_SESSION['error'] = 'Invalid request.';
        }
        header('Location: ' . APP_URL . '/admin/locations');
        exit();
    }

    public function deleteLocation()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $locationModel = new Location();
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['location_id'])) {
            $id = intval($_POST['location_id']);
            if ($locationModel->deleteLocation($id)) {
                $_SESSION['success'] = 'Location deleted successfully!';
            } else {
                $_SESSION['error'] = 'Failed to delete location.';
            }
        } else {
            $_SESSION['error'] = 'Invalid request.';
        }
        header('Location: ' . APP_URL . '/admin/locations');
        exit();
    }

    // Show all feedbacks for admin
    public function showFeedbacks()
    {
        require_once __DIR__ . '/../models/UserModel.php';
        $userModel = new UserModel();
        $start_date = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-01');
        $end_date = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-t');
        $feedbacks = $userModel->getFeedbacksByDate($start_date, $end_date);
        include __DIR__ . '/../views/admin/feedback.php';
    }

    // Edit feedback (status, subject, feedback)
    public function editFeedback()
    {
        require_once __DIR__ . '/../models/UserModel.php';
        $userModel = new UserModel();
        if (isset($_POST['feedback_id'], $_POST['subject'], $_POST['feedback'], $_POST['status'])) {
            $id = intval($_POST['feedback_id']);
            $subject = trim($_POST['subject']);
            $feedback = trim($_POST['feedback']);
            $status = $_POST['status'];
            $success = $userModel->updateFeedback($id, $subject, $feedback, $status);
            if ($success) {
                $_SESSION['success'] = 'Feedback updated.';
                // Log to audit_logs using AuditLogController
                $pdo = getPDO();
                $auditLogController = new AuditLogController($pdo);
                $ip = $_SERVER['REMOTE_ADDR'] ?? null;
                $adminId = $_SESSION['user_id'] ?? null;
                $userRole = isset($_SESSION['user_role']) ? strtolower(trim($_SESSION['user_role'])) : 'admin';
                $details = json_encode([
                    'action' => 'editFeedback',
                    'feedbackId' => $id,
                    'subject' => $subject,
                    'feedback' => $feedback,
                    'status' => $status
                ]);
                $auditLogController->logAction($adminId, $userRole, 'UPDATE', 'feedback', $id, $details, $ip);
            } else {
                $_SESSION['error'] = 'Failed to update feedback.';
            }
        } else {
            $_SESSION['error'] = 'Invalid request.';
        }
        header('Location: ' . APP_URL . '/admin/feedback');
        exit();
    }

    // Delete feedback
    public function deleteFeedback()
    {
        require_once __DIR__ . '/../models/UserModel.php';
        $userModel = new UserModel();
        if (isset($_POST['feedback_id'])) {
            $id = intval($_POST['feedback_id']);
            $success = $userModel->deleteFeedback($id);
            if ($success) {
                $_SESSION['success'] = 'Feedback deleted.';
                // Log to audit_logs using AuditLogController
                require_once __DIR__ . '/AuditLogController.php';
                require_once __DIR__ . '/../config/database.php';
                $pdo = getPDO();
                $auditLogController = new AuditLogController($pdo);
                $ip = $_SERVER['REMOTE_ADDR'] ?? null;
                $adminId = $_SESSION['user_id'] ?? null;
                $userRole = isset($_SESSION['user_role']) ? strtolower(trim($_SESSION['user_role'])) : 'admin';
                $details = json_encode([
                    'action' => 'deleteFeedback',
                    'feedbackId' => $id
                ]);
                $auditLogController->logAction($adminId, $userRole, 'DELETE', 'feedback', $id, $details, $ip);
            } else {
                $_SESSION['error'] = 'Failed to delete feedback.';
            }
        } else {
            $_SESSION['error'] = 'Invalid request.';
        }
        header('Location: ' . APP_URL . '/admin/feedback');
        exit();
    }
}
