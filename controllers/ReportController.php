<?php
// controllers/ReportController.php
require_once __DIR__ . '/../config/database.php';
session_start();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'You must be logged in to report.']);
    exit;
}

$user_id = intval($_SESSION['user_id']);
$listing_id = isset($_POST['listing_id']) ? intval($_POST['listing_id']) : 0;
$query = isset($_POST['query']) ? $_POST['query'] : '';

if (!$listing_id || !in_array($query, ['occupied', 'wrong info', 'NLN'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid data.']);
    exit;
}

try {
    $pdo = getPDO();
    // Prevent duplicate reports by same user for same listing and query
    $stmt = $pdo->prepare('SELECT id FROM report WHERE listing_id = ? AND user_id = ? AND query = ?');
    $stmt->execute([$listing_id, $user_id, $query]);
    if ($stmt->fetch()) {
        echo json_encode(['success' => false, 'message' => 'You have already reported this.']);
        exit;
    }
    $stmt = $pdo->prepare('INSERT INTO report (listing_id, user_id, query, status) VALUES (?, ?, ?, ?)');
    $stmt->execute([$listing_id, $user_id, $query, 'reported']);
    echo json_encode(['success' => true, 'message' => 'Report submitted successfully.']);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error.']);
}
