<?php
// routes/web.php
require_once __DIR__ . '/../controllers/HomeController.php';
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../controllers/ViewController.php';
require_once __DIR__ . '/../controllers/ContactController.php';
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../controllers/AdminController.php';
require_once __DIR__ . '/../helpers/auth.php';
require_once __DIR__ . '/../controllers/UserController.php';


// User registration and login routes handled in switch below

// Parse base path from APP_URL
$parsedUrl = parse_url(APP_URL);
$basePath = isset($parsedUrl['path']) ? rtrim($parsedUrl['path'], '/') : '';

$request = $_SERVER['REQUEST_URI'];

// Remove query string
if (strpos($request, '?') !== false) {
    $request = strstr($request, '?', true);
}

// Remove base path prefix if present
if ($basePath !== '' && substr($request, 0, strlen($basePath)) === $basePath) {
    $request = substr($request, strlen($basePath));
}

switch ($request) {

    case '':
    case '/':
    case '/home':
        $controller = new HomeController();
        $controller->index();
        break;

    case '/submit-message':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller = new HomeController();
            $controller->submitMessage();
        } else {
            http_response_code(405);
            echo "Method Not Allowed";
        }
        break;

    case '/view':
        $controller = new ViewController();
        $controller->show();
        break;

    case '/contact':
        require_once __DIR__ . '/../views/contact.php';
        break;

    case '/listings':
        require_once __DIR__ . '/../views/listings.php';
        break;

    case '/addnewlisting':
        require_once __DIR__ . '/../views/addnewlisting.php';
        break;

    case '/profile':
        require_once __DIR__ . '/../views/profile.php';
        break;

    case '/room':
        require_once __DIR__ . '/../views/room.php';
        break;

    case '/roommate':
        require_once __DIR__ . '/../views/roommate.php';
        break;

    case '/roomlisting':
        require_once __DIR__ . '/../views/roomlisting.php';
        break;

    case '/roommatelisting':
        require_once __DIR__ . '/../views/roommatelisting.php';
        break;

    case '/vibes':
        // Vibes update POST route
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_vibes'])) {
            require_once __DIR__ . '/../controllers/UserController.php';
            $controller = new UserController();
            // Validate vibes selection
            if (isset($_POST['vibes']) && is_array($_POST['vibes']) && count($_POST['vibes']) >= 5) {
                $controller->updateVibes();
                $_SESSION['success'] = 'Vibes updated successfully!';
            } else {
                $_SESSION['error'] = 'Please select at least 5 vibes.';
            }
            header('Location: '.APP_URL.'/vibes');
            exit();
        }
        require_once __DIR__ . '/../views/vibes.php';
        break;

    case '/complete-profile':
        $controller = new UserController();
        $controller->completeProfile();
        break;

    case '/contact/submit':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller = new ContactController();
            $controller->submit();
        } else {
            http_response_code(405);
            echo "Method Not Allowed";
        }
        break;

    case '/admin-login':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userModel = new UserModel();
            $adminController = new AdminController($userModel);
            $adminController->login();
        } else {
            require_once __DIR__ . '/../views/auth/login.php';
        }
        break;

    case '/admin/dashboard':
        checkAdminAuth();
        require_once __DIR__ . '/../views/admin/dashboard.php';
        break;

    case '/admin/addprofilepictures':
        checkAdminAuth();
        require_once __DIR__ . '/../views/admin/addprofilepictures.php';
        break;

    case '/admin/addprofilepicture':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller = new AdminController();
            $controller->addProfilePicture();
        } else {
            http_response_code(405);
            echo "Method Not Allowed";
        }
        break;

    case '/admin/profilepictures':
        $controller = new AdminController();
        $controller->showProfilePictures();
        break;

    case '/admin/profilepictures/delete':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller = new AdminController();
            $controller->deleteProfilePicture();
        } else {
            http_response_code(405);
            echo "Method Not Allowed";
        }
        break;

    case '/admin/addlocation':
        checkAdminAuth();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller = new AdminController(new UserModel());
            $controller->addLocations();
        } else {
            require_once __DIR__ . '/../views/admin/addlocation.php';
        }
        break;

    case '/admin/locations':
        checkAdminAuth();
        $controller = new AdminController(new UserModel());
        $controller->showLocations();
        break;

    case '/admin/locations/edit':
        checkAdminAuth();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller = new AdminController(new UserModel());
            $controller->editLocation();
        } else {
            http_response_code(405);
            echo "Method Not Allowed";
        }
        break;

    case '/admin/locations/delete':
        checkAdminAuth();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller = new AdminController(new UserModel());
            $controller->deleteLocation();
        } else {
            http_response_code(405);
            echo "Method Not Allowed";
        }
        break;

    case '/admin/profile':
        $controller = new AdminController(new UserModel());
        $controller->profile();
        break;

    case '/logout':
        $controller = new AdminController(new UserModel());
        $controller->logout();
        break;

    case '/admin/views':
        checkAdminAuth();
        require_once __DIR__ . '/../views/admin/views.php';
        break;

    case '/admin/addnewvibes':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller = new AdminController(new UserModel());
            $controller->addVibes();
        } else {
            require_once __DIR__ . '/../views/admin/addnewvibes.php';
        }
        break;

    case '/admin/vibes':
        $userModel = new UserModel();
        $controller = new AdminController($userModel);
        $controller->showVibes();
        break;

    case '/admin/vibes/edit':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller = new AdminController(new UserModel());
            $controller->editVibe();
        } else {
            http_response_code(405);
            echo "Method Not Allowed";
        }
        break;

    case '/admin/vibes/delete':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller = new AdminController(new UserModel());
            $controller->deleteVibe();
        } else {
            http_response_code(405);
            echo "Method Not Allowed";
        }
        break;

    case '/admin/addnewamenities':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller = new AdminController(new UserModel());
            $controller->addNewAmenities();
        } else {
            require_once __DIR__ . '/../views/admin/addnewamenities.php';
        }
        break;

    case '/admin/amenities':
        $userModel = new UserModel();
        $controller = new AdminController($userModel);
        $controller->showAmenities();
        break;

    case '/admin/amenities/edit':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller = new AdminController(new UserModel());
            $controller->editAmenity();
        } else {
            http_response_code(405);
            echo "Method Not Allowed";
        }
        break;

    case '/admin/amenities/delete':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller = new AdminController(new UserModel());
            $controller->deleteAmenity();
        } else {
            http_response_code(405);
            echo "Method Not Allowed";
        }
        break;

    case '/register':
        $controller = new UserController();
        $controller->register();
        break;

    case '/login':
        $controller = new UserController();
        $controller->login();
        break;

    default:
        http_response_code(404);
        require_once __DIR__ . '/../404.php';
        break;
}
