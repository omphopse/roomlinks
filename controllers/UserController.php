<?php
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../helpers/auth.php';

class UserController {
    // Get user profile and avatar info by user ID
    public function getProfileById($userId) {
        $userModel = new UserModel();
        return $userModel->getProfileById($userId);
    }
    // Handle complete profile POST
    public function completeProfile() {
        checkUserAuth();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
            $userId = $_SESSION['user_id'];
            $data = [
                'name' => $_POST['name'] ?? '',
                'phone' => $_POST['phone'] ?? '',
                'who' => $_POST['who'] ?? '',
                'gender' => $_POST['gender'] ?? '',
                'location' => $_POST['location'] ?? '',
                'avatar' => $_POST['avatar'] ?? ''
            ];
            $file = $_FILES['profile_picture'] ?? null;
            $userModel = new UserModel();
            $result = $userModel->saveProfile($userId, $data, $file);
            if ($isAjax) {
                header('Content-Type: application/json');
                if ($result === true) {
                    echo json_encode(['success' => 'Profile updated successfully!', 'redirect' => APP_URL . '/vibes']);
                } elseif (is_array($result) && isset($result['error'])) {
                    echo json_encode(['error' => $result['error']]);
                } else {
                    echo json_encode(['error' => 'Failed to save profile.']);
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
                    $_SESSION['error'] = 'Failed to save profile.';
                    header('Location: ' . APP_URL . '/complete-profile');
                }
                exit();
            }
        } else {
            require_once __DIR__ . '/../views/complete-profile.php';
        }
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
                    // Use correct user ID key from DB result
                    $userId = isset($user['user_id']) ? $user['user_id'] : (isset($user['id']) ? $user['id'] : null);
                    loginUser($userId, $user['role'] ?? 'user');
                    $success = 'Login successful!';
                } else {
                    $error = 'Invalid credentials.';
                }
            }
            if ($isAjax) {
                header('Content-Type: application/json');
                if ($success) {
                    echo json_encode(['success' => $success]);
                } else {
                    echo json_encode(['error' => $error ?: 'Login failed.']);
                }
                exit();
            } else {
                if ($success) {
                    $_SESSION['success'] = $success;
                } else {
                    $_SESSION['error'] = $error ?: 'Login failed.';
                }
                header('Location: ' . APP_URL . '/');
                exit();
            }
        } else {
            header('Location: ' . APP_URL . '/');
            exit();
        }
    }
    // Handle registration POST
    public function register() {
        session_start();
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
                $exists = UserModel::findByEmail($email);
                if ($exists) {
                    $error = 'Email already registered.';
                } else {
                    $userId = UserModel::registerUser($email, $password);
                    if ($userId) {
                        // Ensure user is logged in after registration using helper
                        loginUser($userId, 'user');
                        $success = 'Registration successful!';
                    } else {
                        $error = 'Registration failed.';
                    }
                }
            }
            if ($isAjax) {
                header('Content-Type: application/json');
                if ($success) {
                    echo json_encode(['success' => $success, 'redirect' => APP_URL . '/complete-profile']);
                } else {
                    echo json_encode(['error' => $error ?: 'Registration failed.']);
                }
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
        session_start();
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
}
