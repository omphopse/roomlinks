
<?php
class AdminModel {

    // Dashboard: get total users
    public static function getTotalUsers() {
        $pdo = getPDO();
        $stmt = $pdo->query("SELECT COUNT(*) as cnt FROM profile");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? (int)$row['cnt'] : 0;
    }

    // Dashboard: get total listings
    public static function getTotalListings() {
        $pdo = getPDO();
        $stmt = $pdo->query("SELECT COUNT(*) as cnt FROM listing");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? (int)$row['cnt'] : 0;
    }

    // Dashboard: get total rooms (role room or tenant)
    public static function getTotalRooms() {
        $pdo = getPDO();
        $stmt = $pdo->query("SELECT COUNT(*) as cnt FROM listing WHERE role IN ('room', 'tenant')");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? (int)$row['cnt'] : 0;
    }

    // Dashboard: get total PGs (role pg)
    public static function getTotalPGs() {
        $pdo = getPDO();
        $stmt = $pdo->query("SELECT COUNT(*) as cnt FROM listing WHERE role = 'pg'");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? (int)$row['cnt'] : 0;
    }

    // Dashboard: get recent listings (last 10)
    public static function getRecentListings($limit = 10) {
        $pdo = getPDO();
        $stmt = $pdo->prepare("SELECT l.*, p.name as user_name, p.phone as owner_phone FROM listing l LEFT JOIN profile p ON l.user_id = p.id ORDER BY l.id DESC LIMIT ?");
        $stmt->bindValue(1, (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Dashboard: get recent users (last 10)
    public static function getRecentUsers($limit = 10) {
        $pdo = getPDO();
        $stmt = $pdo->prepare("SELECT name, phone, who_are_you FROM profile ORDER BY id DESC LIMIT ?");
        $stmt->bindValue(1, (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fetch users with profile and location, with filters
    public static function getUsersWithProfileAndLocation($filters = []) {
        $pdo = getPDO();
        $where = [];
        $params = [];
        // Gender filter
        if (!empty($filters['gender']) && $filters['gender'] !== 'any') {
            $where[] = 'p.gender = ?';
            $params[] = $filters['gender'];
        }
        // Who are you filter
        if (!empty($filters['who_are_you']) && $filters['who_are_you'] !== 'any') {
            $where[] = 'p.who_are_you = ?';
            $params[] = $filters['who_are_you'];
        }
        // Date filter (created_at in profile)
        if (!empty($filters['start_date'])) {
            $where[] = 'DATE(p.created_at) >= ?';
            $params[] = $filters['start_date'];
        }
        if (!empty($filters['end_date'])) {
            $where[] = 'DATE(p.created_at) <= ?';
            $params[] = $filters['end_date'];
        }
        $whereSql = $where ? ('WHERE ' . implode(' AND ', $where)) : '';
        $sql = "SELECT p.id, p.name, p.phone, u.email, p.gender, p.who_are_you, p.city as location_name
            FROM profile p 
            LEFT JOIN user u ON p.id = u.id 
            $whereSql ORDER BY p.created_at DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public static function getListingsWithOwner($filters = []) {
        $pdo = getPDO();
        $where = [];
        $params = [];
        if (!empty($filters['role']) && $filters['role'] !== 'any') {
            $where[] = 'l.role = ?';
            $params[] = $filters['role'];
        }
        if (!empty($filters['gender']) && $filters['gender'] !== 'any') {
            $where[] = 'l.gender = ?';
            $params[] = $filters['gender'];
        }
        if (!empty($filters['occupancy']) && $filters['occupancy'] !== 'any') {
            $where[] = 'l.occupancy = ?';
            $params[] = $filters['occupancy'];
        }
        // Date filter
        if (!empty($filters['start_date'])) {
            $where[] = 'DATE(l.created_at) >= ?';
            $params[] = $filters['start_date'];
        }
        if (!empty($filters['end_date'])) {
            $where[] = 'DATE(l.created_at) <= ?';
            $params[] = $filters['end_date'];
        }
        $whereSql = $where ? ('WHERE ' . implode(' AND ', $where)) : '';
        $sql = "SELECT l.*, p.name as owner_name, p.phone as owner_phone FROM listing l LEFT JOIN profile p ON l.user_id = p.id $whereSql ORDER BY l.created_at DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
