<?php
require_once __DIR__ . '/../config/database.php';

class BrokerModel
{
    private static function ensureListingClicksTableExists()
    {
        $pdo = getPDO();
        $pdo->exec("CREATE TABLE IF NOT EXISTS listing_clicks (
            id INT AUTO_INCREMENT PRIMARY KEY,
            listing_id INT NOT NULL,
            listing_owner_id INT NOT NULL,
            user_id INT DEFAULT NULL,
            user_name VARCHAR(191) DEFAULT NULL,
            user_email VARCHAR(191) DEFAULT NULL,
            user_ip VARCHAR(45) DEFAULT NULL,
            user_agent TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_listing_owner_id (listing_owner_id),
            INDEX idx_listing_id (listing_id),
            INDEX idx_user_id (user_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
    }

    public static function logListingClick($listingId, $userId = null, $listingOwnerId = null, $userName = null, $userEmail = null, $userIp = null, $userAgent = null)
    {
        self::ensureListingClicksTableExists();
        $pdo = getPDO();
        $stmt = $pdo->prepare("INSERT INTO listing_clicks (listing_id, listing_owner_id, user_id, user_name, user_email, user_ip, user_agent) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $listingId,
            $listingOwnerId,
            $userId,
            $userName,
            $userEmail,
            $userIp,
            $userAgent
        ]);
    }

    public static function getBrokerListingClicks($brokerId, $filters = [])
    {
        self::ensureListingClicksTableExists();
        $pdo = getPDO();
        $where = ["lc.listing_owner_id = ?"];
        $params = [$brokerId];

        if (!empty($filters['start_date'])) {
            $where[] = "DATE(lc.created_at) >= ?";
            $params[] = $filters['start_date'];
        }

        if (!empty($filters['end_date'])) {
            $where[] = "DATE(lc.created_at) <= ?";
            $params[] = $filters['end_date'];
        }

        if (!empty($filters['role']) && $filters['role'] !== 'any') {
            $where[] = "l.role = ?";
            $params[] = $filters['role'];
        }

        if (!empty($filters['location_id'])) {
            $where[] = "l.location = ?";
            $params[] = $filters['location_id'];
        }

        $whereSql = implode(' AND ', $where);
        $sql = "
            SELECT lc.*, loc.name AS location, l.rent, l.role AS listing_role, l.status AS listing_status,
                   COALESCE(lc.user_name, p.name, 'Guest') AS clicker_name,
                   COALESCE(lc.user_email, u.email, '') AS clicker_email,
                   p.phone AS clicker_phone
            FROM listing_clicks lc
            LEFT JOIN listing l ON lc.listing_id = l.id
            LEFT JOIN locations loc ON l.location = loc.id
            LEFT JOIN user u ON lc.user_id = u.id
            LEFT JOIN profile p ON lc.user_id = p.id
            WHERE $whereSql
            ORDER BY lc.created_at DESC
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get broker profile information
     */
    public static function getBrokerProfile($brokerId)
    {
        $pdo = getPDO();
        $stmt = $pdo->prepare("
            SELECT p.id, p.name, p.phone, p.gender, p.city, p.avatar_id, p.uploaded_picture, 
                   p.who_are_you, p.created_at, u.email
            FROM profile p
            LEFT JOIN user u ON p.id = u.id
            WHERE p.id = ? AND p.who_are_you = 'broker'
            LIMIT 1
        ");
        $stmt->execute([$brokerId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Get total listings created by broker
     */
    public static function getBrokerTotalListings($brokerId)
    {
        $pdo = getPDO();
        $stmt = $pdo->prepare("
            SELECT COUNT(*) as cnt 
            FROM listing 
            WHERE user_id = ?
        ");
        $stmt->execute([$brokerId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? (int)$row['cnt'] : 0;
    }

    /**
     * Get active listings count for broker
     */
    public static function getBrokerActiveListings($brokerId)
    {
        $pdo = getPDO();
        $stmt = $pdo->prepare("
            SELECT COUNT(*) as cnt 
            FROM listing 
            WHERE user_id = ? AND status = 'active'
        ");
        $stmt->execute([$brokerId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? (int)$row['cnt'] : 0;
    }

    /**
     * Get inactive listings count for broker
     */
    public static function getBrokerInactiveListings($brokerId)
    {
        $pdo = getPDO();
        $stmt = $pdo->prepare("
            SELECT COUNT(*) as cnt 
            FROM listing 
            WHERE user_id = ? AND status IN ('inactive', 'unavalable')
        ");
        $stmt->execute([$brokerId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? (int)$row['cnt'] : 0;
    }

    /**
     * Get room listings by broker (role = room)
     */
    public static function getBrokerRoomListings($brokerId)
    {
        $pdo = getPDO();
        $stmt = $pdo->prepare("
            SELECT COUNT(*) as cnt 
            FROM listing 
            WHERE user_id = ? AND role = 'room'
        ");
        $stmt->execute([$brokerId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? (int)$row['cnt'] : 0;
    }

    /**
     * Get PG listings by broker (role = pg)
     */
    public static function getBrokerPGListings($brokerId)
    {
        $pdo = getPDO();
        $stmt = $pdo->prepare("
            SELECT COUNT(*) as cnt 
            FROM listing 
            WHERE user_id = ? AND role = 'pg'
        ");
        $stmt->execute([$brokerId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? (int)$row['cnt'] : 0;
    }

    /**
     * Get recent listings by broker
     */
    public static function getBrokerRecentListings($brokerId, $limit = 10)
    {
        $pdo = getPDO();
        $stmt = $pdo->prepare("
            SELECT l.id, l.location, l.rent, l.role, l.status, l.occupancy, 
                   l.gender, l.image1, l.created_at, l.updated_at
            FROM listing l
            WHERE l.user_id = ?
            ORDER BY l.created_at DESC
            LIMIT ?
        ");
        $stmt->bindValue(1, $brokerId, PDO::PARAM_INT);
        $stmt->bindValue(2, (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get all listings by broker with filters
     */
    public static function getBrokerListings($brokerId, $filters = [])
    {
        $pdo = getPDO();
        $where = ["l.user_id = ?"];
        $params = [$brokerId];

        if (!empty($filters['role']) && $filters['role'] !== 'any') {
            $where[] = "l.role = ?";
            $params[] = $filters['role'];
        }

        if (!empty($filters['gender']) && $filters['gender'] !== 'any') {
            $where[] = "l.gender = ?";
            $params[] = $filters['gender'];
        }

        if (!empty($filters['occupancy']) && $filters['occupancy'] !== 'any') {
            $where[] = "l.occupancy = ?";
            $params[] = $filters['occupancy'];
        }

        if (!empty($filters['start_date'])) {
            $where[] = "DATE(l.created_at) >= ?";
            $params[] = $filters['start_date'];
        }

        if (!empty($filters['end_date'])) {
            $where[] = "DATE(l.created_at) <= ?";
            $params[] = $filters['end_date'];
        }

        $whereSql = implode(' AND ', $where);
        $sql = "
            SELECT l.*, p.name as owner_name, p.phone as owner_phone
            FROM listing l
            LEFT JOIN profile p ON l.user_id = p.id
            WHERE $whereSql
            ORDER BY l.created_at DESC
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get listing details by ID and verify it belongs to broker
     */
    public static function getBrokerListingById($brokerId, $listingId)
    {
        $pdo = getPDO();
        $stmt = $pdo->prepare("
            SELECT l.*
            FROM listing l
            WHERE l.id = ? AND l.user_id = ?
            LIMIT 1
        ");
        $stmt->execute([$listingId, $brokerId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Get total revenue from all listings
     */
    public static function getBrokerTotalRevenue($brokerId)
    {
        $pdo = getPDO();
        $stmt = $pdo->prepare("
            SELECT SUM(rent) as total_revenue 
            FROM listing 
            WHERE user_id = ? AND status = 'active'
        ");
        $stmt->execute([$brokerId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row && $row['total_revenue'] ? (float)$row['total_revenue'] : 0;
    }

    /**
     * Get average rent per listing
     */
    public static function getBrokerAverageRent($brokerId)
    {
        $pdo = getPDO();
        $stmt = $pdo->prepare("
            SELECT AVG(rent) as avg_rent 
            FROM listing 
            WHERE user_id = ?
        ");
        $stmt->execute([$brokerId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row && $row['avg_rent'] ? round((float)$row['avg_rent'], 2) : 0;
    }

    /**
     * Get total clicks recorded for this broker
     */
    public static function getBrokerTotalClicks($brokerId)
    {
        self::ensureListingClicksTableExists();
        $pdo = getPDO();
        $stmt = $pdo->prepare(
            "SELECT COUNT(*) AS cnt FROM listing_clicks WHERE listing_owner_id = ?"
        );
        $stmt->execute([$brokerId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? (int)$row['cnt'] : 0;
    }

    /**
     * Get listings by location distribution
     */
    public static function getBrokerListingsByLocation($brokerId)
    {
        $pdo = getPDO();
        $stmt = $pdo->prepare("
            SELECT COALESCE(loc.name, CAST(l.location AS CHAR)) AS location, COUNT(*) as count 
            FROM listing l
            LEFT JOIN locations loc ON l.location = loc.id
            WHERE l.user_id = ?
            GROUP BY COALESCE(loc.name, CAST(l.location AS CHAR))
            ORDER BY count DESC
        ");
        $stmt->execute([$brokerId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get listings by role distribution
     */
    public static function getBrokerListingsByRole($brokerId)
    {
        $pdo = getPDO();
        $stmt = $pdo->prepare("
            SELECT role, COUNT(*) as count 
            FROM listing 
            WHERE user_id = ?
            GROUP BY role
            ORDER BY count DESC
        ");
        $stmt->execute([$brokerId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Update listing status
     */
    public static function updateListingStatus($brokerId, $listingId, $status)
    {
        $pdo = getPDO();
        $stmt = $pdo->prepare("
            UPDATE listing 
            SET status = ?, updated_at = NOW()
            WHERE id = ? AND user_id = ?
        ");
        return $stmt->execute([$status, $listingId, $brokerId]);
    }

    /**
     * Delete listing (soft delete by setting status to inactive)
     */
    public static function deleteBrokerListing($brokerId, $listingId)
    {
        return self::updateListingStatus($brokerId, $listingId, 'inactive');
    }

    /**
     * Get dashboard statistics
     */
    public static function getDashboardStats($brokerId)
    {
        return [
            'total_listings' => self::getBrokerTotalListings($brokerId),
            'active_listings' => self::getBrokerActiveListings($brokerId),
            'inactive_listings' => self::getBrokerInactiveListings($brokerId),
            'total_rooms' => self::getBrokerRoomListings($brokerId),
            'total_pgs' => self::getBrokerPGListings($brokerId),
            'total_clicks' => self::getBrokerTotalClicks($brokerId),
            'total_revenue' => self::getBrokerTotalRevenue($brokerId),
            'average_rent' => self::getBrokerAverageRent($brokerId),
        ];
    }

    /**
     * Get broker dashboard data
     */
    public static function getBrokerDashboardData($brokerId)
    {
        return [
            'profile' => self::getBrokerProfile($brokerId),
            'stats' => self::getDashboardStats($brokerId),
            'recent_listings' => self::getBrokerRecentListings($brokerId, 10),
            'listings_by_location' => self::getBrokerListingsByLocation($brokerId),
            'listings_by_role' => self::getBrokerListingsByRole($brokerId),
        ];
    }
}
?>
