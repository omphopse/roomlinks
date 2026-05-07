

<?php
require_once __DIR__ . '/../config/constants.php';

// Persistent login: restore session if cookie is present and session is not set
require_once __DIR__ . '/PersistentLogin.php';
if (session_status() === PHP_SESSION_NONE) session_start();
if (empty($_SESSION['user_id']) && !empty($_COOKIE['roomlinks_auth'])) {
    $persistentLogin = new PersistentLogin();
    $persistentLogin->checkToken();
}

function loginUser($userId, $role = 'user') {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $_SESSION['logged_in'] = true;
    $_SESSION['user_id'] = $userId;
    $_SESSION['user_role'] = $role;
}

function checkAuth() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (empty($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
        $isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
        if ($isAjax) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'You must be logged in to perform this action.', 'redirect' => APP_URL . '/']);
            exit();
        } else {
            header('Location: ' . APP_URL . '/');
            exit();
        }
    }
}

function checkAdminAuth() {
    checkAuth();

    if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
        header('Location: ' . APP_URL . '/');
        exit();
    }
}

function checkBrokerAuth() {
    checkAuth();

    if (!isset($_SESSION['user_role']) || strtolower(trim($_SESSION['user_role'])) !== 'broker') {
        header('Location: ' . APP_URL . '/');
        exit();
    }
}

function checkUserAuth() {
    checkAuth();
    if (!isset($_SESSION['user_id'])) {
        $isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
        if ($isAjax) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'You must be logged in to perform this action.', 'redirect' => APP_URL . '/']);
            exit();
        } else {
            header('Location: ' . APP_URL . '/');
            exit();
        }
    }
}
