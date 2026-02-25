<?php
// routes/web.php
require_once __DIR__ . '/../controllers/HomeController.php';
require_once __DIR__ . '/../config/constants.php';
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

    case '/api/get-locations':
        // API endpoint to get locations by city_id
        header('Content-Type: application/json');
        if (isset($_GET['city_id'])) {
            require_once __DIR__ . '/../models/Location.php';
            $pdo = getPDO();
            $locationModel = new Location($pdo);
            $locations = $locationModel->getAllLocations(intval($_GET['city_id']));
            echo json_encode($locations);
        } else {
            echo json_encode([]);
        }
        exit;

    case '/register':
        $controller = new UserController();
        $controller->register();
        break;

    case '/login':
        $controller = new UserController();
        $controller->login();
        break;

    case '':
    case '/':
    case '/home':
        $controller = new HomeController();
        $controller->index();
        break;

    case '/profile':
        require_once __DIR__ . '/../views/profile.php';
        break;

    case '/contact':
        require_once __DIR__ . '/../views/contact.php';
        break;

    case '/more':
        require_once __DIR__ . '/../views/more.php';
        break;

    case '/mylistings':
        if (session_status() === PHP_SESSION_NONE)
            session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . APP_URL . '/login');
            exit;
        }
        $controller = new UserController();
        $controller->myListings();
        break;

    case '/add-roommatelisting':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller = new UserController();
            $controller->addroommatelisting();
        } else {
            http_response_code(405);
            echo "Method Not Allowed";
        }
        break;

    case '/privacy-policy':
        require_once __DIR__ . '/../views/privacy-policy.php';
        break;

    case '/termsandconditions':
        require_once __DIR__ . '/../views/termsandconditions.php';
        break;

    case '/listings':
        require_once __DIR__ . '/../views/listings.php';
        break;

    case '/addnewlisting':
        require_once __DIR__ . '/../views/addnewlisting.php';
        break;


    case '/room':
        require_once __DIR__ . '/../views/room.php';
        break;

    case '/roommate':
        require_once __DIR__ . '/../views/roommate.php';
        break;

    case '/update-roomlisting':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (session_status() === PHP_SESSION_NONE)
                session_start();
            $user_id = $_SESSION['user_id'] ?? null;
            $user_email = $_SESSION['email'] ?? null;
            $controller = new UserController();
            $amenities = method_exists($controller, 'getAmenities') ? $controller->getAmenities() : [];
            $propertyHighlights = method_exists($controller, 'getPropertyHighlights') ? $controller->getPropertyHighlights() : [];
            $controller->updateRoomListing($_POST, $_FILES, $user_id, $user_email, $amenities, $propertyHighlights, $_POST['id'] ?? null);
        } else {
            http_response_code(405);
            echo "Method Not Allowed";
        }
        break;

    case '/add-roomlisting':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (session_status() === PHP_SESSION_NONE)
                session_start();
            $user_id = $_SESSION['user_id'] ?? null;
            $user_email = $_SESSION['email'] ?? null;
            $controller = new UserController();
            $amenities = method_exists($controller, 'getAmenities') ? $controller->getAmenities() : [];
            $propertyHighlights = method_exists($controller, 'getPropertyHighlights') ? $controller->getPropertyHighlights() : [];
            $controller->addRoomListing($_POST, $_FILES, $user_id, $user_email, $amenities, $propertyHighlights, $_POST['id'] ?? null);
        } else {
            http_response_code(405);
            echo "Method Not Allowed";
        }
        break;

    case '/update-roommatelisting':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller = new UserController();
            $controller->updateRoommatelisting();
        } else {
            http_response_code(405);
            echo "Method Not Allowed";
        }
        break;

    case '/deletelisting':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller = new UserController();
            $listingId = $_POST['listing_id'] ?? null;
            if ($listingId) {
                $controller->deleteListing($listingId);
            } else {
                http_response_code(400);
                echo "Missing listing ID.";
            }
        } else {
            http_response_code(405);
            echo "Method Not Allowed";
        }
        break;

    case '/roomlisting':
        // Validate and pass the GET parameter 'id' to roomlisting.php
        $listingId = isset($_GET['id']) ? intval($_GET['id']) : 0;
        if (!$listingId) {
            http_response_code(404);
            require_once __DIR__ . '/../404.php';
            break;
        }
        require_once __DIR__ . '/../models/UserModel.php';
        $userModel = new UserModel();
        $listing = $userModel->getListingById($listingId);
        if (!$listing) {
            http_response_code(404);
            require_once __DIR__ . '/../404.php';
            break;
        }
        require_once __DIR__ . '/../views/roomlisting.php';
        break;

    case '/roommatelisting':
        require_once __DIR__ . '/../views/roommatelisting.php';
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
            header('Location: ' . APP_URL . '/vibes');
            exit();
        }
        require_once __DIR__ . '/../views/vibes.php';
        break;



    case '/admin/highlights':
        checkAdminAuth();
        $controller = new AdminController(new UserModel());
        $controller->showHighlights();
        break;

    case '/admin/highlights/edit':
        checkAdminAuth();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller = new AdminController(new UserModel());
            $controller->editHighlight();
        } else {
            http_response_code(405);
            echo "Method Not Allowed";
        }
        break;

    case '/admin/highlights/delete':
        checkAdminAuth();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller = new AdminController(new UserModel());
            $controller->deleteHighlight();
        } else {
            http_response_code(405);
            echo "Method Not Allowed";
        }
        break;
    case '/admin/addhighlights':
        checkAdminAuth();
        $controller = new AdminController(new UserModel());
        $controller->showAddHighlightsPage();
        break;

    case '/admin/addpropertyhighlight':
        checkAdminAuth();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller = new AdminController(new UserModel());
            $controller->addPropertyHighlight();
        } else {
            http_response_code(405);
            echo "Method Not Allowed";
        }
        break;

    case '/admin/reports':
        checkAdminAuth();
        $controller = new AdminController(new UserModel());
        $controller->showReports();
        break;

    case '/admin/reports/editstatus':
        checkAdminAuth();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller = new AdminController(new UserModel());
            $controller->editReportStatus();
        } else {
            http_response_code(405);
            echo "Method Not Allowed";
        }
        break;

    case '/admin/dashboard':
        checkAdminAuth();
        $controller = new AdminController(new UserModel());
        $controller->dashboard();
        break;

    case '/admin/contact':
        checkAdminAuth();
        require_once __DIR__ . '/../views/admin/contact.php';
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

    case '/admin/addcity':
        checkAdminAuth();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller = new AdminController(new UserModel());
            $controller->addCities();
        } else {
            require_once __DIR__ . '/../views/admin/addcity.php';
        }
        break;

    case '/admin/addlocation':
        checkAdminAuth();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller = new AdminController(new UserModel());
            $controller->addLocations();
        } else {
            $controller = new AdminController(new UserModel());
            $controller->showAddLocationsPage();
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

    case '/admin/cities':
        checkAdminAuth();
        $controller = new AdminController(new UserModel());
        $controller->showCities();
        break;

    case '/admin/cities/edit':
        checkAdminAuth();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller = new AdminController(new UserModel());
            $controller->editCity();
        } else {
            http_response_code(405);
            echo "Method Not Allowed";
        }
        break;

    case '/admin/cities/delete':
        checkAdminAuth();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller = new AdminController(new UserModel());
            $controller->deleteCity();
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

    case '/admin/feedback':
        checkAdminAuth();
        $controller = new AdminController();
        $controller->showFeedbacks();
        break;

    case '/admin/feedback/edit':
        checkAdminAuth();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller = new AdminController();
            $controller->editFeedback();
        } else {
            http_response_code(405);
            echo "Method Not Allowed";
        }
        break;

    case '/admin/feedback/delete':
        checkAdminAuth();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller = new AdminController();
            $controller->deleteFeedback();
        } else {
            http_response_code(405);
            echo "Method Not Allowed";
        }
        break;

    case '/admin/delete':
        checkAdminAuth();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller = new UserController();
            $controller->admindeleteUser();
        } else {
            http_response_code(405);
            echo "Method Not Allowed";
        }
        break;

    case '/admin/users':
        checkAdminAuth();
        $controller = new AdminController(new UserModel());
        $controller->showUsers();
        break;

    case '/admin/listings':
        checkAdminAuth();
        $controller = new AdminController(new UserModel());
        $controller->showListings();
        break;

    case '/feedback':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller = new UserController();
            $controller->submitFeedback();
        } else {
            require_once __DIR__ . '/../views/feedback.php';
        }
        break;

    default:
        http_response_code(404);
        require_once __DIR__ . '/../404.php';
        break;
}
