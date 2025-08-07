<?php
// models/Report.php
require_once __DIR__ . '/../config/database.php';

class Report {
    public static function addReport($listing_id, $user_id, $query) {
        $pdo = getPDO();
        // Accept 'NLN' for roommate listings
        $validQueries = ['occupied', 'wrong info', 'NLN'];
        if (!in_array($query, $validQueries)) {
            return ['success' => false, 'message' => 'Invalid query type.'];
        }
        // Prevent duplicate report
        $stmt = $pdo->prepare('SELECT id FROM report WHERE listing_id = ? AND user_id = ? AND query = ?');
        $stmt->execute([$listing_id, $user_id, $query]);
        if ($stmt->fetch()) {
            return ['success' => false, 'message' => 'You have already reported this.'];
        }
        $stmt = $pdo->prepare('INSERT INTO report (listing_id, user_id, query, status) VALUES (?, ?, ?, ?)');
        $stmt->execute([$listing_id, $user_id, $query, 'reported']);
        return ['success' => true, 'message' => 'Report submitted successfully.'];
    }

    public static function getReportsByListing($listing_id) {
        $pdo = getPDO();
        $stmt = $pdo->prepare('SELECT * FROM report WHERE listing_id = ? ORDER BY created_at DESC');
        $stmt->execute([$listing_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getReportsByUser($user_id) {
        $pdo = getPDO();
        $stmt = $pdo->prepare('SELECT * FROM report WHERE user_id = ? ORDER BY created_at DESC');
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function updateStatus($report_id, $status) {
        $pdo = getPDO();
        $stmt = $pdo->prepare('UPDATE report SET status = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?');
        return $stmt->execute([$status, $report_id]);
    }
}
