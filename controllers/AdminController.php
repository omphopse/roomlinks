   
    
<?php
require_once __DIR__ . '/../models/Vibes.php';
class AdminController
{
    private $userModel;

    public function __construct($userModel = null)
    {
        if ($userModel === null) {
            require_once __DIR__ . '/../models/UserModel.php';
            $userModel = new UserModel();
        }
        $this->userModel = $userModel;
    }

    public function login()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];

            $user = $this->userModel->login($email, $password);

            if ($user) {
                // Start session
                session_regenerate_id(true);
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['user_role'] = $user['role'];
                $_SESSION['logged_in'] = true;

                // Redirect to admin dashboard only
                header('Location:' . APP_URL . '/admin/dashboard');
                exit();
            } else {
                $_SESSION['error'] = 'Invalid email or password';
                // Redirect back to login page
                header('Location: ' . APP_URL . '/admin-login');
                exit();
            }
        }
        // For GET requests, do nothing (let the view render the form)
    }

    public function logout()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        // Unset all session variables
        $_SESSION = array();
        // Destroy session cookie if exists
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
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

    public function addVibes() {
        session_start();
        $vibesModel = new Vibes();
        $results = $vibesModel->addVibes($_POST, $_FILES);
        $success_msgs = [];
        $error_msgs = [];
        foreach ($results as $res) {
            if (!empty($res['success'])) {
                $success_msgs[] = $res['message'];
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
        header('Location: '.APP_URL.'/admin/addnewvibes');
        exit;
    }

    public function showVibes() {
        session_start();
        $vibesModel = new Vibes();
        // Use start_date and end_date from GET, fallback to current month
        $start_date = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-01');
        $end_date = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-t');
        $vibes = $vibesModel->getVibesByDate($start_date, $end_date);
        $totalVibes = count($vibes);
        include __DIR__ . '/../views/admin/vibes.php';
    }

    public function editVibe() {
        session_start();
        $vibesModel = new Vibes();
        $id = $_POST['vibe_id'] ?? null;
        $name = $_POST['vibe_name'] ?? '';
        $image = isset($_FILES['vibe_image']) ? $_FILES['vibe_image'] : null;
        if ($id && $name) {
            $success = $vibesModel->updateVibe($id, $name, $image);
            if ($success) {
                $_SESSION['success'] = 'Vibe updated successfully!';
            } else {
                $_SESSION['error'] = 'Failed to update vibe.';
            }
        } else {
            $_SESSION['error'] = 'Missing required fields.';
        }
        header('Location: '.APP_URL.'/admin/vibes');
        exit;
    }

    public function deleteVibe() {
        session_start();
        $vibesModel = new Vibes();
        $id = $_POST['vibe_id'] ?? null;
        if ($id) {
            $success = $vibesModel->deleteVibe($id);
            if ($success) {
                $_SESSION['success'] = 'Vibe deleted successfully!';
            } else {
                $_SESSION['error'] = 'Failed to delete vibe.';
            }
        } else {
            $_SESSION['error'] = 'Missing vibe ID.';
        }
        header('Location: '.APP_URL.'/admin/vibes');
        exit;
    }

    public function addNewAmenities() {
        session_start();
        require_once __DIR__ . '/../models/Amenities.php';
        $amenitiesModel = new Amenities();
        $results = $amenitiesModel->addAmenities($_POST, $_FILES);
        $success_msgs = [];
        $error_msgs = [];
        foreach ($results as $res) {
            if (!empty($res['success'])) {
                $success_msgs[] = $res['message'];
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
        header('Location: '.APP_URL.'/admin/addnewamenities');
        exit;
    }

    public function showAmenities() {
        session_start();
        require_once __DIR__ . '/../models/Amenities.php';
        $amenitiesModel = new Amenities();
        $start_date = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-01');
        $end_date = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-t');
        $amenities = $amenitiesModel->getAmenitiesByDate($start_date, $end_date);
        $totalAmenities = count($amenities);
        include __DIR__ . '/../views/admin/amenities.php';
    }

    public function editAmenity() {
        session_start();
        require_once __DIR__ . '/../models/Amenities.php';
        $amenitiesModel = new Amenities();
        $id = $_POST['amenity_id'] ?? null;
        $name = $_POST['amenity_name'] ?? '';
        $image = isset($_FILES['amenity_image']) ? $_FILES['amenity_image'] : null;
        if ($id && $name) {
            $success = $amenitiesModel->updateAmenity($id, $name, $image);
            if ($success) {
                $_SESSION['success'] = 'Amenity updated successfully!';
            } else {
                $_SESSION['error'] = 'Failed to update amenity.';
            }
        } else {
            $_SESSION['error'] = 'Missing required fields.';
        }
        header('Location: '.APP_URL.'/admin/amenities');
        exit;
    }

    public function deleteAmenity() {
        session_start();
        require_once __DIR__ . '/../models/Amenities.php';
        $amenitiesModel = new Amenities();
        $id = $_POST['amenity_id'] ?? null;
        if ($id) {
            $success = $amenitiesModel->deleteAmenity($id);
            if ($success) {
                $_SESSION['success'] = 'Amenity deleted successfully!';
            } else {
                $_SESSION['error'] = 'Failed to delete amenity.';
            }
        } else {
            $_SESSION['error'] = 'Missing amenity ID.';
        }
        header('Location: '.APP_URL.'/admin/amenities');
        exit;
    }

    public function addProfilePicture() {
        session_start();
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

    public function showProfilePictures() {
        session_start();
        require_once __DIR__ . '/../models/ProfilePicture.php';
        $profilePictureModel = new ProfilePicture();
        $start_date = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-01');
        $end_date = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-t');
        $pictures = $profilePictureModel->getProfilePictures($start_date, $end_date);
        include __DIR__ . '/../views/admin/profilepicture.php';
    }

    public function deleteProfilePicture() {
        session_start();
        require_once __DIR__ . '/../models/ProfilePicture.php';
        $profilePictureModel = new ProfilePicture();
        $id = $_POST['profile_picture_id'] ?? null;
        if ($id) {
            $success = $profilePictureModel->deleteProfilePicture($id);
            if ($success) {
                $_SESSION['success'] = 'Profile picture deleted successfully!';
            } else {
                $_SESSION['error'] = 'Failed to delete profile picture.';
            }
        } else {
            $_SESSION['error'] = 'Missing profile picture ID.';
        }
        header('Location: ' . APP_URL . '/admin/profilepictures');
        exit;
    }

    // Location Management
    public function addLocations() {
        require_once __DIR__ . '/../models/Location.php';
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['location_name'])) {
            $locations = array_map('trim', $_POST['location_name']);
            $locations = array_filter($locations, function($loc) { return !empty($loc); });
            if (!empty($locations)) {
                $db = getPDO();
                $locationModel = new Location($db);
                $locationModel->addLocations($locations);
                $_SESSION['success'] = 'Locations added successfully!';
            } else {
                $_SESSION['error'] = 'Please enter at least one location.';
            }
            header('Location: ' . APP_URL . '/admin/addlocation');
            exit();
        }
    }

     // Show all locations
    public function showLocations() {
        require_once __DIR__ . '/../models/Location.php';
        $db = getPDO();
        $locationModel = new Location($db);
        $locations = $locationModel->getAllLocations();
        include __DIR__ . '/../views/admin/location.php';
    }

    // Edit a location
    public function editLocation() {
        require_once __DIR__ . '/../models/Location.php';
        $db = getPDO();
        $locationModel = new Location($db);
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['location_id'], $_POST['location_name'])) {
            $id = intval($_POST['location_id']);
            $name = trim($_POST['location_name']);
            if ($name !== '') {
                $locationModel->updateLocation($id, $name);
                $_SESSION['success'] = 'Location updated successfully!';
            } else {
                $_SESSION['error'] = 'Location name cannot be empty!';
            }
        }
        header('Location: ' . APP_URL . '/admin/locations');
        exit();
    }

    // Delete a location
    public function deleteLocation() {
        require_once __DIR__ . '/../models/Location.php';
        $db = getPDO();
        $locationModel = new Location($db);
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['location_id'])) {
            $id = intval($_POST['location_id']);
            $locationModel->deleteLocation($id);
            $_SESSION['success'] = 'Location deleted successfully!';
        } else {
            $_SESSION['error'] = 'Invalid request.';
        }
        header('Location: ' . APP_URL . '/admin/locations');
        exit();
    }
}
