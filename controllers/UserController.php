<?php
require_once __DIR__ . '/../models/UserModel.php';

require_once __DIR__ . '/../helpers/auth.php';
require_once __DIR__ . '/../helpers/PersistentLogin.php';
// Log to audit_logs using AuditLogController
            require_once __DIR__ . '/AuditLogController.php';
            require_once __DIR__ . '/../config/database.php';

// Persistent login check on every request
if (session_status() === PHP_SESSION_NONE) session_start();
$persistentLogin = new PersistentLogin();
$persistentLogin->checkToken();

// Handle AJAX delete request (must be outside the class definition)
if (isset($_GET['action']) && $_GET['action'] === 'deleteUser') {
    UserController::deleteUser();
}

class UserController {
    // Check if a user has set their vibes
    public function hasUserSetVibes($userId) {
        $userModel = new UserModel();
        $profile = $userModel->getProfileById($userId);
        return !empty($profile) && !empty($profile['vibes']);
    }

    // Get vibe match percentage between two users
    public function getVibeMatchPercentage($userId1, $userId2) {
        $userModel = new UserModel();
        return $userModel->getVibeMatchPercentage($userId1, $userId2);
    }


    //Delete a single listing and its images by listing ID
    public function deleteListing($listingId) {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Unauthorized']);
            exit;
        }
        $userId = $_SESSION['user_id'];
        $userRole = isset($_SESSION['user_role']) ? strtolower(trim($_SESSION['user_role'])) : '';
        $userModel = new UserModel();
        // If admin, allow delete without userId restriction
        $result = ($userRole === 'admin')
            ? $userModel->deleteListingById($listingId, null)
            : $userModel->deleteListingById($listingId, $userId);
        if ($result) {
            
            $pdo = getPDO();
            $auditLogController = new AuditLogController($pdo);
            $ip = $_SERVER['REMOTE_ADDR'] ?? null;
            $details = json_encode(['action' => 'deleteListing', 'listingId' => $listingId]);
            $auditLogController->logAction($userId, $userRole, 'DELETE', 'listings', $listingId, $details, $ip);
            if (php_sapi_name() === 'cli' || (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')) {
                echo json_encode(['success' => true]);
            } else {
                header('Location: ' . APP_URL . '/listings');
            }
        } else {
            if (php_sapi_name() === 'cli' || (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')) {
                http_response_code(500);
                echo json_encode(['error' => 'Failed to delete listing.']);
            } else {
                echo "<script>alert('Failed to delete listing.');window.history.back();</script>";
            }
        }
        exit;
    }
   

    // Handle update for room listing (edit form submission)
    public function updateRoomListing($postData, $fileData, $user_id, $user_email, $amenities, $propertyHighlights, $listingId, $role = null) {
        // Validate required fields
        $location = trim($postData['location'] ?? '');
        $rent = floatval($postData['rent'] ?? 0);
        $gender = trim($postData['looking_for'] ?? 'any');
        $occupancy = 'single';
        if (isset($postData['occupancy'])) {
            $occVal = strtolower(trim($postData['occupancy']));
            if (in_array($occVal, ['single', 'shared', 'any'])) {
                $occupancy = $occVal;
            }
        }
        $description = trim($postData['description'] ?? '');
        // Validate description word count
        $descWords = preg_match_all('/\b\w+\b/', $description, $matches);
        if ($descWords < 20) {
            echo "<script>alert('Description must be at least 20 words.');window.history.back();</script>";
            exit();
        }
        // Get user's who_are_you value from profile
        $userModel = new UserModel();
        $profile = $userModel->getProfileById($user_id);
        $role = 'room'; // default
        if (!empty($profile['who_are_you'])) {
            $who = strtolower(trim($profile['who_are_you']));
            if ($who === 'flat_owner') {
                $role = 'tenant';
            } elseif ($who === 'pg_owner') {
                $role = 'pg';
            } elseif ($who === 'looking_room') {
                $role = 'room';
            }
        }
        // Fix contact: split by comma, join by ' | '
        $contactRaw = trim($postData['contact_method'] ?? '');
        $contactArr = array_filter(array_map('trim', explode(',', $contactRaw)));
        $contact = implode(' | ', $contactArr);
        $highlightsArr = $postData['highlights'] ?? [];
        $amenitiesArr = $postData['amenities'] ?? [];
        $errors = [];
        if (!$user_id || !$location || !$rent || !$description || !$contact) {
            echo "<script>alert('Missing required fields.');window.history.back();</script>";
            exit();
        }
        // Map highlight names to IDs
        $highlightNameToId = [];
        foreach ($propertyHighlights as $h) {
            $highlightNameToId[$h['name']] = $h['id'];
        }
        $highlightsIds = array_map(function($name) use ($highlightNameToId) {
            return isset($highlightNameToId[$name]) ? $highlightNameToId[$name] : $name;
        }, $highlightsArr);
        $highlightsStr = implode(' | ', $highlightsIds);

        // Map amenity name to IDs
        $amenityNameToId = [];
        foreach ($amenities as $a) {
            $amenityNameToId[$a['name']] = $a['id'];
        }
        $amenitiesIds = array_map(function($name) use ($amenityNameToId) {
            return isset($amenityNameToId[$name]) ? $amenityNameToId[$name] : $name;
        }, $amenitiesArr);
        $amenitiesStr = implode(' | ', $amenitiesIds);

        // Handle images: merge existing, remove, and new uploads
        $existingImages = isset($postData['existing_images']) ? array_filter(array_map('trim', explode(',', $postData['existing_images']))) : [];
        $removeImages = isset($postData['remove_images']) ? array_filter(array_map('trim', explode(',', $postData['remove_images']))) : [];
        // Remove any images marked for removal
        $finalImages = array_values(array_diff($existingImages, $removeImages));
        // Handle new uploads
        $newImagePaths = [];
        if ($fileData && isset($fileData['room_photos']) && !empty($fileData['room_photos']['name'][0])) {
            $newImagePaths = $this->handleRoomImages($fileData['room_photos'], $user_id, $user_email, 5 - count($finalImages));
            if (is_string($newImagePaths)) {
                echo "<script>alert('" . addslashes($newImagePaths) . "');window.history.back();</script>";
                exit();
            }
        }
        // Merge existing (kept) and new images
        $allImages = array_merge($finalImages, $newImagePaths);
        // Validate total image count
        $allImages = array_filter($allImages); // remove empty
        if (count($allImages) < 2) {
            echo "<script>alert('Please upload at least 2 images.');window.history.back();</script>";
            exit();
        }
        if (count($allImages) > 5) {
            echo "<script>alert('You can upload a maximum of 5 images.');window.history.back();</script>";
            exit();
        }
        // Pad to 5 for DB
        while (count($allImages) < 5) $allImages[] = '';
        // Call UserModel to update listing
        $result = $userModel->updateRoomListing($listingId, $user_id, $location, $rent, $gender, $occupancy, $highlightsStr, $amenitiesStr, $description, $contact, $allImages, $role);
        if ($result === true) {
            // Log to audit_logs using AuditLogController
            $pdo = getPDO();
            $auditLogController = new AuditLogController($pdo);
            $ip = $_SERVER['REMOTE_ADDR'] ?? null;
            $details = json_encode([
                'action' => 'updateRoomListing',
                'listingId' => $listingId,
                'location' => $location,
                'rent' => $rent,
                'gender' => $gender,
                'occupancy' => $occupancy,
                'highlights' => $highlightsStr,
                'amenities' => $amenitiesStr,
                'description' => $description,
                'contact' => $contact,
                'images' => $allImages
            ]);
            $userRole = isset($_SESSION['user_role']) ? strtolower(trim($_SESSION['user_role'])) : '';
            $auditLogController->logAction($user_id, $userRole, 'UPDATE', 'listings', $listingId, $details, $ip);
            header('Location: ' . APP_URL . '/roomlisting?id=' . $listingId);
            exit();
        } else {
            $msg = is_string($result) ? $result : 'Failed to update listing.';
            echo "<script>alert('" . addslashes($msg) . "');window.history.back();</script>";
            exit();
        }
    }
    
    // Handle requirement update POST from room.php
    public function updateRoommatelisting() {
        checkUserAuth();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user_id = $_SESSION['user_id'] ?? null;
            $id = $_POST['id'] ?? null;
            $location = $_POST['location'] ?? '';
            $rent = $_POST['rent'] ?? '';
            $gender = $_POST['looking_for'] ?? '';
            $occupancy = $_POST['occupancy'] ?? '';
            $highlights = isset($_POST['highlights']) ? implode(' | ', $_POST['highlights']) : '';
            $contact = isset($_POST['contact_method']) ? str_replace(',', ' | ', $_POST['contact_method']) : '';
            $description = $_POST['description'] ?? '';
            $role = 'roommate';
            $userModel = new UserModel();
            $result = $userModel->updateRoommatelisting($id, $user_id, $location, $rent, $gender, $occupancy, $highlights, $contact, $description, $role);
            if ($result === true) {
                // Log to audit_logs using AuditLogController
                $pdo = getPDO();
                $auditLogController = new AuditLogController($pdo);
                $ip = $_SERVER['REMOTE_ADDR'] ?? null;
                $details = json_encode([
                    'action' => 'updateRoommatelisting',
                    'roommateId' => $id,
                    'location' => $location,
                    'rent' => $rent,
                    'gender' => $gender,
                    'occupancy' => $occupancy,
                    'highlights' => $highlights,
                    'contact' => $contact,
                    'description' => $description
                ]);
                $userRole = isset($_SESSION['user_role']) ? strtolower(trim($_SESSION['user_role'])) : '';
                $auditLogController->logAction($user_id, $userRole, 'UPDATE', 'roommatelisting', $id, $details, $ip);
                $_SESSION['success'] = 'Requirement updated successfully!';
                header('Location: ' . APP_URL . '/roomlisting?id=' . $id);
            } else {
                $_SESSION['error'] = is_string($result) ? $result : 'Failed to update requirement.';
                header('Location: ' . APP_URL . '/room?id=' . $id);
            }
            exit();
        }
    }
    

    // Show listings for the logged-in user only
    public function myListings() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . APP_URL . '/');
            exit;
        }
        $userId = $_SESSION['user_id'];
        $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
        $perPage = 24;
        $start = ($page - 1) * $perPage;
        $userModel = new UserModel();
        $listings = $userModel->getListingsByUser($userId, $start, $perPage);
        $totalListings = $userModel->getListingsCountByUser($userId);
        $totalPages = ceil($totalListings / $perPage);
        require __DIR__ . '/../views/mylistings.php';
    }


    //Handle feedback form submission
    public function submitFeedback() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            echo "<script>alert('You must be logged in to submit feedback.');window.location.href='/login';</script>";
            exit;
        }
        $userId = $_SESSION['user_id'];
        $subject = isset($_POST['subject']) ? trim($_POST['subject']) : '';
        $feedback = isset($_POST['feedback']) ? trim($_POST['feedback']) : '';
        if ($subject === '' || $feedback === '') {
            echo "<script>alert('Subject and feedback are required.');window.history.back();</script>";
            exit;
        }
        $wordCount = str_word_count($feedback);
        if ($wordCount < 20) {
            echo "<script>alert('Feedback must be at least 20 words.');window.history.back();</script>";
            exit;
        }
        if ($wordCount > 200) {
            echo "<script>alert('Feedback must not exceed 200 words.');window.history.back();</script>";
            exit;
        }
        $userModel = new UserModel();
        $result = $userModel->insertFeedback($userId, $subject, $feedback);
        if ($result === true) {
            $_SESSION['success'] = 'Thank you for your feedback!';
            header('Location: ' . APP_URL . '/feedback');
        } else {
            $_SESSION['error'] = 'Failed to submit feedback. Please try again.';
            header('Location: ' . APP_URL . '/feedback');
        }
        exit;
    }

    // Delete user and all related data
    public static function deleteUser() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Unauthorized']);
            exit;
        }
        $userId = $_SESSION['user_id'];
        require_once __DIR__ . '/../models/UserModel.php';
        $userModel = new UserModel();
        $result = $userModel->deleteUserAndData($userId);
        if ($result === true) {
            // Log to audit_logs using AuditLogController
            $pdo = getPDO();
            $auditLogController = new AuditLogController($pdo);
            $ip = $_SERVER['REMOTE_ADDR'] ?? null;
            $userRole = isset($_SESSION['user_role']) ? strtolower(trim($_SESSION['user_role'])) : '';
            $details = json_encode(['action' => 'deleteUser', 'userId' => $userId]);
            $auditLogController->logAction($userId, $userRole, 'DELETE', 'user', $userId, $details, $ip);
            // Destroy session
            require_once __DIR__ . '/../helpers/PersistentLogin.php';
            $persistentLogin = new PersistentLogin();
            $persistentLogin->clearToken();
            session_destroy();
            echo json_encode(['success' => true]);
        } else {
            http_response_code(500);
            echo json_encode(['error' => $result]);
        }
        exit;
    }

    // Delete user and all related data (admin only)
    public static function admindeleteUser() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            http_response_code(401);
            echo "<script>alert('Unauthorized');window.history.back();</script>";
            exit;
        }
        if (!isset($_POST['user_id']) || empty($_POST['user_id'])) {
            http_response_code(400);
            echo "<script>alert('Missing user ID.');window.history.back();</script>";
            exit;
        }
        $userEmail = $_POST['user_id'];
        require_once __DIR__ . '/../models/UserModel.php';
        $userModel = new UserModel();
        $result = $userModel->deleteUserAndDataByAdmin($userEmail);
        if ($result === true) {
            // Log to audit_logs using AuditLogController
            $pdo = getPDO();
            $auditLogController = new AuditLogController($pdo);
            $ip = $_SERVER['REMOTE_ADDR'] ?? null;
            $adminId = $_SESSION['user_id'];
            $userRole = isset($_SESSION['user_role']) ? strtolower(trim($_SESSION['user_role'])) : '';
            $details = json_encode(['action' => 'admindeleteUser', 'deletedUserEmail' => $userEmail]);
            $auditLogController->logAction($adminId, $userRole, 'DELETE', 'user', $userEmail, $details, $ip);
            $_SESSION['success'] = 'User deleted successfully!';
            header('Location: ' . APP_URL . '/admin/users');
        } else {
            $_SESSION['error'] = is_string($result) ? $result : 'Failed to delete user.';
            header('Location: ' . APP_URL . '/admin/users');
        }
        exit;
    }

    // Get avatar image by id (for profile display)
    public function getAvatarImage($avatarId) {
        $userModel = new UserModel();
        return $userModel->getAvatarImageById($avatarId);
    }

    // Update profile fields and email, only if changed
    public function updateProfile($userId, $data) {
        $userModel = new UserModel();
        $profile = $userModel->getProfileById($userId);
        $user = $userModel->getUserById($userId);

        $fieldsToUpdate = [];
        if (isset($data['name']) && $data['name'] !== $profile['name']) {
            $fieldsToUpdate['name'] = $data['name'];
        }
        if (isset($data['phone']) && $data['phone'] !== $profile['phone']) {
            $fieldsToUpdate['phone'] = $data['phone'];
        }
        if (isset($data['gender']) && $data['gender'] !== $profile['gender']) {
            $fieldsToUpdate['gender'] = $data['gender'];
        }
        $result = true;
        if (!empty($fieldsToUpdate)) {
            $result = $userModel->updateProfileFields($userId, $fieldsToUpdate);
        }
        // Email update (user table)
        if (isset($data['email']) && $data['email'] !== $user['email']) {
            $resultEmail = $userModel->updateUserEmail($userId, $data['email']);
            if ($resultEmail !== true) {
                return ['error' => $resultEmail];
            }
        }
        if ($result === true) {
            // Log to audit_logs using AuditLogController
            $pdo = getPDO();
            $auditLogController = new AuditLogController($pdo);
            $ip = $_SERVER['REMOTE_ADDR'] ?? null;
            $userRole = isset($_SESSION['user_role']) ? strtolower(trim($_SESSION['user_role'])) : '';
            $details = json_encode([
                'action' => 'updateProfile',
                'userId' => $userId,
                'fieldsUpdated' => $fieldsToUpdate,
                'emailUpdated' => (isset($data['email']) && $data['email'] !== $user['email']) ? $data['email'] : null
            ]);
            $auditLogController->logAction($userId, $userRole, 'UPDATE', 'user', $userId, $details, $ip);
            return true;
        } else {
            return ['error' => $result];
        }
    }

    // Get user by id from user table
    public function getUserById($userId) {
        $userModel = new UserModel();
        return $userModel->getUserById($userId);
    }

    /**
     * Get property highlight names by array of IDs
     */
    public function getPropertyHighlightsByIds($ids) {
        require_once __DIR__ . '/../models/UserModel.php';
        return UserModel::getPropertyHighlightsByIds($ids);
    }

    //Get contact buttons for a listing
    public function getContactButtons($listingId) {
        require_once __DIR__ . '/../models/UserModel.php';
        $userModel = new UserModel();
        $contactArr = $userModel->getContactOptionsByListingId($listingId);
        $phone = $userModel->getPhoneByListingId($listingId);
        $email = $userModel->getEmailByListingId($listingId);
        $buttons = [];
        // If all three are selected, show only chat and call
        $allThree = in_array('whatsapp', $contactArr) && in_array('call', $contactArr) && in_array('email', $contactArr);
        if (in_array('whatsapp', $contactArr) && $phone && (!$allThree || $allThree)) {
            $waMsg = urlencode('Hi, I am interested in your room listing on RoomLinks. Can you share more details?');
            $waLink = "https://wa.me/91{$phone}?text={$waMsg}";
            $buttons[] = '<a href="' . $waLink . '" target="_blank" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded flex items-center gap-2"><i class="fab fa-whatsapp"></i> Chat</a>';
        }
        if (in_array('call', $contactArr) && $phone && (!$allThree || $allThree)) {
            $buttons[] = '<a href="tel:+91' . $phone . '" class="bg-blue-100 hover:bg-blue-200 text-blue-700 px-6 py-2 rounded flex items-center gap-2"><i class="fas fa-phone"></i> Call</a>';
        }
        if (in_array('email', $contactArr) && $email && !$allThree) {
            $mailto = 'mailto:' . $email . '?subject=' . urlencode('RoomLinks Listing Inquiry') . '&body=' . urlencode('Hi, I am interested in your room listing on RoomLinks. Can you share more details?');
            $buttons[] = '<a href="' . $mailto . '" class="bg-green-100 hover:bg-green-200 text-green-700 px-6 py-2 rounded flex items-center gap-2"><i class="fas fa-envelope"></i> Mail</a>';
        }
        return implode("\n", $buttons);
    }

    //Get amenities (image, name) by array of IDs
    public function getAmenitiesByIds($ids) {
        require_once __DIR__ . '/../models/UserModel.php';
        return UserModel::getAmenitiesByIds($ids);
    }

    // Get paginated listings for views/listings.php
    public function getListings($start = 0, $perPage = 24, $location = '', $gender = '', $looking_for = '') {
        $userModel = new UserModel();
        return $userModel->getListings($start, $perPage, $location, $gender, $looking_for);
    }

    // Get a single listing by its ID
    public function getListingById($id) {
        $userModel = new UserModel();
        return $userModel->getListingById($id);
    }

    // Handle requirement form POST from room.php
    public function addroommatelisting() {
        checkUserAuth();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user_id = $_SESSION['user_id'] ?? null;
            $location = $_POST['location'] ?? '';
            $rent = $_POST['rent'] ?? '';
            $gender = $_POST['looking_for'] ?? '';
            $occupancy = $_POST['occupancy'] ?? '';
            $highlights = isset($_POST['highlights']) ? implode(' | ', $_POST['highlights']) : '';
            $contact = isset($_POST['contact_method']) ? str_replace(',', ' | ', $_POST['contact_method']) : '';
            $description = $_POST['description'] ?? '';
            $role = 'roommate';
            $userModel = new UserModel();
            $insertId = $userModel->addroommatelisting($user_id, $location, $rent, $gender, $occupancy, $highlights, $contact, $description, $role);
            if ($insertId && is_numeric($insertId)) {
                // Log to audit_logs using AuditLogController
                $pdo = getPDO();
                $auditLogController = new AuditLogController($pdo);
                $ip = $_SERVER['REMOTE_ADDR'] ?? null;
                $details = json_encode([
                    'action' => 'addroommatelisting',
                    'roommateId' => $insertId,
                    'location' => $location,
                    'rent' => $rent,
                    'gender' => $gender,
                    'occupancy' => $occupancy,
                    'highlights' => $highlights,
                    'contact' => $contact,
                    'description' => $description
                ]);
                $userRole = isset($_SESSION['user_role']) ? strtolower(trim($_SESSION['user_role'])) : '';
                $auditLogController->logAction($user_id, $userRole, 'CREATE', 'roommatelisting', $insertId, $details, $ip);
                $_SESSION['success'] = 'Requirement added successfully!';
                header('Location: ' . APP_URL . '/roomlisting?id=' . $insertId);
            } else {
                $_SESSION['error'] = is_string($insertId) ? $insertId : 'Failed to add requirement.';
                header('Location: ' . APP_URL . '/room.php');
            }
            exit();
        }
    }

    // Get user profile and avatar info by user ID
    public function getProfileById($userId) {
        $userModel = new UserModel();
        return $userModel->getProfileById($userId);
    }
    
    // Handle complete profile POST
    public function completeProfile() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
            // Registration: only allow if pending_registration exists
            if (isset($_SESSION['registration_incomplete']) && $_SESSION['registration_incomplete'] && isset($_SESSION['pending_registration'])) {
                $regData = $_SESSION['pending_registration'];
                $email = $regData['email'];
                $password = $regData['password'];
                $data = [
                    'name' => $_POST['name'] ?? '',
                    'phone' => $_POST['phone'] ?? '',
                    'who' => $_POST['who'] ?? '',
                    'gender' => $_POST['gender'] ?? '',
                    'location' => $_POST['location'] ?? '',
                    'avatar' => $_POST['avatar'] ?? '',
                    'custom_location' => $_POST['custom_location'] ?? ''
                ];
                $file = $_FILES['profile_picture'] ?? null;
                // Insert user into DB now
                $userId = UserModel::registerUser($email, $password);
                if ($userId) {
                    loginUser($userId, 'user');
                    // Set persistent login token after registration (like login)
                    require_once __DIR__ . '/../config/database.php';
                    $pdo = getPDO();
                    $persistentLogin = new PersistentLogin($pdo);
                    $persistentLogin->setToken($userId);
                    // Save profile
                    $userModel = new UserModel();
                    $result = $userModel->saveProfile($userId, $data, $file);
                    unset($_SESSION['registration_incomplete']);
                    unset($_SESSION['pending_registration']);
                } else {
                    $result = 'Registration failed.';
                }
            } else {
                // Normal profile update for existing users
                $userId = $_SESSION['user_id'];
                $data = [
                    'name' => $_POST['name'] ?? '',
                    'phone' => $_POST['phone'] ?? '',
                    'who' => $_POST['who'] ?? '',
                    'gender' => $_POST['gender'] ?? '',
                    'location' => $_POST['location'] ?? '',
                    'avatar' => $_POST['avatar'] ?? '',
                    'custom_location' => $_POST['custom_location'] ?? ''
                ];
                $file = $_FILES['profile_picture'] ?? null;
                $userModel = new UserModel();
                $result = $userModel->saveProfile($userId, $data, $file);
                if ($result === true) {
                    unset($_SESSION['registration_incomplete']);
                }
            }
            if ($isAjax) {
                header('Content-Type: application/json');
                if ($result === true) {
                    echo json_encode(['success' => 'Profile updated successfully!', 'redirect' => APP_URL . '/vibes']);
                } elseif (is_array($result) && isset($result['error'])) {
                    echo json_encode(['error' => $result['error']]);
                } else {
                    echo json_encode(['error' => is_string($result) ? $result : 'Failed to save profile.']);
                }
                exit();
            } else {
                if ($result === true) {
                    $_SESSION['success'] = 'Profile updated successfully!';
                    header('Location: ' . APP_URL . '/vibes');
                } elseif (is_array($result) && isset($result['error'])) {
                    $_SESSION['error'] = $result['error'];
                    header('Location: ' . APP_URL . '/complete-profile');
                } else {
                    $_SESSION['error'] = is_string($result) ? $result : 'Failed to save profile.';
                    header('Location: ' . APP_URL . '/complete-profile');
                }
                exit();
            }
        } else {
            require_once __DIR__ . '/../views/complete-profile.php';
        }
    }

    // Fetch all property highlights for use in forms
    public function getPropertyHighlights() {
        $userModel = new UserModel();
        return $userModel->getAllPropertyHighlights();
    }

    // Fetch all amenities for use in forms
    public function getAmenities() {
        $userModel = new UserModel();
        return $userModel->getAllAmenities();
    }

    // Fetch locations for profile form
    public function getLocations() {
        require_once __DIR__ . '/../models/Location.php';
        require_once __DIR__ . '/../config/database.php';
        $pdo = getPDO();
        $locationModel = new Location($pdo);
        return $locationModel->getAllLocations();
    }

    public static function getRandomAvatars($avatars, $limit = 4) {
        if (count($avatars) > $limit) {
            $keys = array_rand($avatars, $limit);
            if (!is_array($keys)) $keys = [$keys];
            $result = [];
            foreach ($keys as $k) {
                $result[] = $avatars[$k];
            }
            return $result;
        }
        return $avatars;
    }
    
    // Handle login POST
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $error = null;
            $success = null;
            if (!$email || !$password) {
                $error = 'Email and password required.';
            } else {
                // Allow both admin and normal users to login
                $user = UserModel::loginUser($email, $password);
                if ($user) {
                    // Use correct user ID key from DB result (your table uses 'id')
                    $userId = $user['id'];
                    $role = isset($user['role']) ? strtolower(trim($user['role'])) : 'user';
                    // Set session user_role for reliability (used by checkAdminAuth)
                    loginUser($userId, $role);
                    $_SESSION['user_role'] = $role;
                    // Set persistent login token with PDO
                    $pdo = getPDO();
                    $persistentLogin = new PersistentLogin($pdo);
                    $debugMsg = $persistentLogin->setToken($userId);
                    $success = 'Login successful!';
                } else {
                    $error = 'Invalid credentials.';
                }
            }
            if ($isAjax) {
                header('Content-Type: application/json');
                if ($success) {
                    echo json_encode([
                        'success' => $success,
                        'user_role' => $_SESSION['user_role'],
                        'role' => $role,
                        'debug' => $debugMsg
                    ]);
                } else {
                    echo json_encode(['error' => $error ?: 'Login failed.']);
                }
                exit();
            } else {
                if ($success) {
                    $_SESSION['success'] = $success;
                    
                    // Redirect based on user_role
                    if ($_SESSION['user_role'] === 'admin') {
                        header('Location: ' . APP_URL . '/admin/dashboard');
                        exit();
                    } else {
                        header('Location: ' . APP_URL . '/');
                        exit();
                    }
                } else {
                    $_SESSION['error'] = $error ?: 'Login failed.';
                    header('Location: ' . APP_URL . '/');
                    exit();
                }
            }
        } else {
            header('Location: ' . APP_URL . '/');
            exit();
        }
    }

    // Handle registration POST
    public function register() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirm = $_POST['confirm_password'] ?? '';
            $error = null;
            $success = null;
            if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = 'Invalid email.';
            } elseif (!$password || strlen($password) < 6) {
                $error = 'Password must be at least 6 characters.';
            } elseif ($password !== $confirm) {
                $error = 'Passwords do not match.';
            } else {
                // Check if email already exists in DB
                $exists = UserModel::findByEmail($email);
                if ($exists) {
                    $error = 'Email already registered.';
                } else {
                    // Store registration data in session, do not insert into DB yet
                    $_SESSION['pending_registration'] = [
                        'email' => $email,
                        'password' => $password
                    ];
                    $_SESSION['registration_incomplete'] = true;
                    $success = 'Proceed to complete your profile.';
                }
            }
            if ($isAjax) {
                header('Content-Type: application/json');
                $response = [];
                if ($success) {
                    $response['success'] = $success;
                    $response['redirect'] = APP_URL . '/complete-profile';
                } else {
                    // If debug info exists, append it to the error message for easier troubleshooting
                    $response['error'] = $error ?: 'Registration failed.';
                    if (!empty($debug)) {
                        $response['debug'] = $debug;
                        $response['error'] .= ' [' . implode('; ', $debug) . ']';
                    }
                }
                echo json_encode($response);
                exit();
            } else {
                if ($success) {
                    $_SESSION['success'] = $success;
                    header('Location: ' . APP_URL . '/complete-profile');
                } else {
                    $_SESSION['error'] = $error ?: 'Registration failed.';
                    header('Location: ' . APP_URL . '/');
                }
                exit();
            }
        } else {
            header('Location: ' . APP_URL . '/');
            exit();
        }
    }

    // Fetch all vibes for vibes page
    public function getVibes() {
        require_once __DIR__ . '/../models/Vibes.php';
        require_once __DIR__ . '/../config/database.php';
        $pdo = getPDO();
        $vibesModel = new Vibes($pdo);
        return $vibesModel->getAllVibes();
    }

    // Handle vibes update POST
    public function updateVibes() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_vibes']) && isset($_POST['vibes'])) {
            $isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
            if (!isset($_SESSION['user_id'])) {
                if ($isAjax) {
                    header('Content-Type: application/json');
                    echo json_encode(['error' => 'You must be logged in to update vibes.', 'redirect' => APP_URL . '/vibes']);
                    exit();
                } else {
                    $_SESSION['error'] = 'You must be logged in to update vibes.';
                    header('Location: ' . APP_URL . '/vibes');
                    exit();
                }
            }
            $userId = $_SESSION['user_id'];
            // Always store vibes as a string separated by " | "
            $vibesStr = is_array($_POST['vibes']) ? implode(' | ', $_POST['vibes']) : $_POST['vibes'];
            require_once __DIR__ . '/../config/database.php';
            $pdo = getPDO();
            $userModel = new UserModel();
            if (method_exists($userModel, 'setPDO')) {
                $userModel->setPDO($pdo);
            }
            $success = $userModel->updateVibes($userId, $vibesStr);
            if ($isAjax) {
                header('Content-Type: application/json');
                if ($success) {
                    echo json_encode(['success' => 'Vibes updated successfully!', 'redirect' => APP_URL . '/listings']);
                } else {
                    echo json_encode(['error' => 'Failed to update vibes.', 'redirect' => APP_URL . '/vibes']);
                }
                exit();
            } else {
                if ($success) {
                    $_SESSION['success'] = 'Vibes updated successfully!';
                    header('Location: ' . APP_URL . '/listings');
                } else {
                    $_SESSION['error'] = 'Failed to update vibes.';
                    header('Location: ' . APP_URL . '/vibes');
                }
                exit();
            }
        }
    }

    // Handle form data from roommate.php and add a new room listing
    public function addRoomListing($postData, $fileData, $user_id, $user_email, $amenities, $propertyHighlights, $role = null) {
        // Validate required fields
        $location = trim($postData['location'] ?? '');
        $rent = floatval($postData['rent'] ?? 0);
        $gender = trim($postData['looking_for'] ?? 'any');
        // Fix occupancy: get from hidden input, not from button value
        // Fix occupancy: get from hidden input, not from button value, and ensure correct value
        $occupancy = 'single';
        if (isset($postData['occupancy'])) {
            $occVal = strtolower(trim($postData['occupancy']));
            if (in_array($occVal, ['single', 'shared', 'any'])) {
                $occupancy = $occVal;
            }
        }
        $description = trim($postData['description'] ?? '');
        // Validate description word count
        $descWords = preg_match_all('/\b\w+\b/', $description, $matches);
        if ($descWords < 20) {
            return 'Description must be at least 20 words.';
        }
        // Get user's who_are_you value from profile
        $userModel = new UserModel();
        $profile = $userModel->getProfileById($user_id);
        $role = 'room'; // default
        if (!empty($profile['who_are_you'])) {
            $who = strtolower(trim($profile['who_are_you']));
            if ($who === 'flat_owner') {
                $role = 'tenant';
            } elseif ($who === 'pg_owner') {
                $role = 'pg';
            } elseif ($who === 'looking_room') {
                $role = 'room';
            }
        }
        // Fix contact: split by comma, join by ' | '
        $contactRaw = trim($postData['contact_method'] ?? '');
        $contactArr = array_filter(array_map('trim', explode(',', $contactRaw)));
        $contact = implode(' | ', $contactArr);
        $highlightsArr = $postData['highlights'] ?? [];
        $amenitiesArr = $postData['amenities'] ?? [];
        $errors = [];
        if (!$user_id || !$location || !$rent || !$description || !$contact) {
            return 'Missing required fields.';
        }
        // Map highlight names to IDs
        $highlightNameToId = [];
        foreach ($propertyHighlights as $h) {
            $highlightNameToId[$h['name']] = $h['id'];
        }
        $highlightsIds = array_map(function($name) use ($highlightNameToId) {
            return isset($highlightNameToId[$name]) ? $highlightNameToId[$name] : $name;
        }, $highlightsArr);
        $highlightsStr = implode(' | ', $highlightsIds);

        // Map amenity names to IDs
        $amenityNameToId = [];
        foreach ($amenities as $a) {
            $amenityNameToId[$a['name']] = $a['id'];
        }
        $amenitiesIds = array_map(function($name) use ($amenityNameToId) {
            return isset($amenityNameToId[$name]) ? $amenityNameToId[$name] : $name;
        }, $amenitiesArr);
        $amenitiesStr = implode(' | ', $amenitiesIds);
        // Handle image uploads (max 5)
        $imagePaths = $this->handleRoomImages($fileData['room_photos'] ?? null, $user_id, $user_email);
        if (is_string($imagePaths)) {
            return $imagePaths; // error message
        }
        // Call UserModel to insert listing with role
        $listingId = $userModel->addRoomListing($user_id, $location, $rent, $gender, $occupancy, $highlightsStr, $amenitiesStr, $description, $contact, $imagePaths, $role);
        
        // Log to audit_logs using AuditLogController if insert was successful
        if ($listingId && is_numeric($listingId)) {
            $pdo = getPDO();
            $auditLogController = new AuditLogController($pdo);
            $ip = $_SERVER['REMOTE_ADDR'] ?? null;
            $details = json_encode([
                'action' => 'addRoomListing',
                'listingId' => $listingId,
                'location' => $location,
                'rent' => $rent,
                'gender' => $gender,
                'occupancy' => $occupancy,
                'highlights' => $highlightsStr,
                'amenities' => $amenitiesStr,
                'description' => $description,
                'contact' => $contact,
                'images' => $imagePaths,
                'role' => $role
            ]);
            $userRole = isset($_SESSION['user_role']) ? strtolower(trim($_SESSION['user_role'])) : '';
            $auditLogController->logAction($user_id, $userRole, 'CREATE', 'listings', $listingId, $details, $ip);
        }
        return $listingId;
    }

    // Helper to handle image uploads for room listing
    private function handleRoomImages($images, $user_id, $user_email, $maxImages = 5) {
        if (!$images || !isset($images['name'])) return [];
        $numToProcess = 0;
        foreach ($images['name'] as $name) {
            if (!empty($name)) $numToProcess++;
        }
        if ($numToProcess == 0) return [];
        if ($numToProcess > $maxImages) {
            return 'You can upload a maximum of ' . $maxImages . ' images.';
        }
        $uploadDir = __DIR__ . '/../assets/listings/' . $user_id . '_' . $user_email . '_' . date('Ym');
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        $imagePaths = [];
        $allowedTypes = ['image/jpeg','image/png','image/jpg'];
        for ($i = 0, $added = 0; $i < count($images['name']) && $added < $maxImages; $i++) {
            $name = $images['name'][$i];
            if (empty($name)) continue;
            $tmpName = $images['tmp_name'][$i];
            $type = $images['type'][$i];
            $size = $images['size'][$i];
            if (!in_array($type, $allowedTypes)) {
                return 'Invalid image type: ' . $type;
            }
            if ($size > 20 * 1024 * 1024) {
                return 'Image too large: ' . $name . ' (max 20MB)';
            }
            $targetPath = $uploadDir . '/' . uniqid() . '_' . basename($name);
            if (!move_uploaded_file($tmpName, $targetPath)) {
                return 'Failed to upload image: ' . $name;
            }
            $imagePaths[] = 'assets/listings/' . $user_id . '_' . $user_email . '_' . date('Ym') . '/' . basename($targetPath);
            $added++;
        }
        return $imagePaths;
    }

    // Get vibes by array of IDs
    public function getVibesByIds($ids) {
        if (empty($ids)) return [];
        require_once __DIR__ . '/../models/Vibes.php';
        return Vibes::getVibesByIds($ids);
    }
    
}
