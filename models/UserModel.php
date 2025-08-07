    
   
<?php

class UserModel
{
    /**
     * Calculate vibe match percentage between two users by user ID
     */
    public function getVibeMatchPercentage($userId1, $userId2)
    {
        // Get vibes for both users
        $stmt = $this->conn->prepare('SELECT id, vibes FROM profile WHERE id IN (?, ?)');
        $stmt->bind_param('ii', $userId1, $userId2);
        $stmt->execute();
        $result = $stmt->get_result();
        $vibes = [];
        while ($row = $result->fetch_assoc()) {
            $vibes[$row['id']] = $row['vibes'];
        }
        $stmt->close();
        if (empty($vibes[$userId1]) || empty($vibes[$userId2])) {
            return 0;
        }
        $v1 = array_map('trim', explode('|', $vibes[$userId1]));
        $v2 = array_map('trim', explode('|', $vibes[$userId2]));
        if (empty($v1) || empty($v2)) return 0;
        $matches = count(array_intersect($v1, $v2));
        $total = count(array_unique(array_merge($v1, $v2)));
        if ($total === 0) return 0;
        $percent = round(($matches / $total) * 100);
        return $percent;
    }
    
     /**
     * Admin: Delete user and all related data by email (admin passes email as user_id)
     */
    public function deleteUserAndDataByAdmin($userEmail) {
        // Ensure connection is initialized
        if (!$this->conn) {
            require_once __DIR__ . '/../config/database.php';
            $this->conn = getDatabaseConnection();
        }
        // Look up user by email
        $stmt = $this->conn->prepare("SELECT id FROM user WHERE email = ? LIMIT 1");
        $stmt->bind_param('s', $userEmail);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();
        if (!$user || empty($user['id'])) {
            return 'User not found.';
        }
        $userId = $user['id'];
        // Call the existing deleteUserAndData method
        return $this->deleteUserAndData($userId);
    }

    /**
     * Get feedbacks by date range with user info
     */
    public function getFeedbacksByDate($start_date, $end_date)
    {
        if (!$this->conn) {
            require_once __DIR__ . '/../config/database.php';
            $this->conn = getDatabaseConnection();
        }
        $sql = "SELECT f.id, f.subject, f.feedback, f.user_id, f.status, f.created_at, f.updated_at, p.name as user_name, p.phone as user_phone FROM feedback f LEFT JOIN profile p ON f.user_id = p.id WHERE DATE(f.created_at) >= ? AND DATE(f.created_at) <= ? ORDER BY f.created_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('ss', $start_date, $end_date);
        $stmt->execute();
        $result = $stmt->get_result();
        $feedbacks = [];
        while ($row = $result->fetch_assoc()) {
            $feedbacks[] = $row;
        }
        $stmt->close();
        return $feedbacks;
    }
     /**
     * Delete a single listing and its images by listing ID
     */
    public function deleteListingById($listingId, $userId = null) {
        if (!$this->conn) {
            require_once __DIR__ . '/../config/database.php';
            $this->conn = getDatabaseConnection();
        }
        // Get all images for this listing
        $stmt = $this->conn->prepare("SELECT image1, image2, image3, image4, image5 FROM listing WHERE id = ?" . ($userId ? " AND user_id = ?" : ""));
        if ($userId) {
            $stmt->bind_param('ii', $listingId, $userId);
        } else {
            $stmt->bind_param('i', $listingId);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        $images = [];
        if ($row = $result->fetch_assoc()) {
            for ($i = 1; $i <= 5; $i++) {
                $imgField = 'image' . $i;
                if (!empty($row[$imgField])) {
                    $images[] = $row[$imgField];
                }
            }
        }
        $stmt->close();
        // Delete the listing
        $stmt = $this->conn->prepare("DELETE FROM listing WHERE id = ?" . ($userId ? " AND user_id = ?" : ""));
        if ($userId) {
            $stmt->bind_param('ii', $listingId, $userId);
        } else {
            $stmt->bind_param('i', $listingId);
        }
        $success = $stmt->execute();
        $stmt->close();
        // Remove images from server
        foreach ($images as $img) {
            $imgPath = __DIR__ . '/../' . $img;
            if ($img && file_exists($imgPath)) {
                @unlink($imgPath);
            }
        }
        return $success;
    }
        
     // Update a room listing in the database (store highlight IDs in propertyhighlights)
    public function updateRoomListing($listingId, $user_id, $location, $rent, $gender, $occupancy, $highlightsStr, $amenitiesStr, $description, $contact, $imagePaths = null, $role = null)
    {
        // Build SQL dynamically to optionally update images
        $fields = [
            'location = ?',
            'rent = ?',
            'gender = ?',
            'occupancy = ?',
            'propertyhighlights = ?',
            'amenities = ?',
            'description = ?',
            'contact = ?',
            'role = ?'
        ];
        $params = [$location, $rent, $gender, $occupancy, $highlightsStr, $amenitiesStr, $description, $contact, $role];
        $types = 'sdsssssss';
        // If images are provided, update them
        if ($imagePaths && is_array($imagePaths)) {
            for ($i = 1; $i <= 5; $i++) {
                $fields[] = 'image' . $i . ' = ?';
                $params[] = isset($imagePaths[$i-1]) ? $imagePaths[$i-1] : '';
                $types .= 's';
            }
        }
        $params[] = $listingId;
        $params[] = $user_id;
        $types .= 'ii';
        $sql = 'UPDATE listing SET ' . implode(', ', $fields) . ' WHERE id = ? AND user_id = ?';
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) return 'DB error: prepare failed.';
        $stmt->bind_param($types, ...$params);
        $result = $stmt->execute();
        $stmt->close();
        return $result ? true : 'Failed to update room listing.';
    }
       // Update a requirement (roommate post) in the database
    public function updateRoommatelisting($id, $user_id, $location, $rent, $gender, $occupancy, $highlights, $contact, $description, $role) {
        if (!$id || !$user_id) return 'Invalid ID or user.';
        $sql = "UPDATE listing SET location=?, rent=?, gender=?, occupancy=?, propertyhighlights=?, contact=?, description=?, role=? WHERE id=? AND user_id=?";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) return 'DB error: prepare failed.';
        $stmt->bind_param('sdssssssii', $location, $rent, $gender, $occupancy, $highlights, $contact, $description, $role, $id, $user_id);
        $result = $stmt->execute();
        $stmt->close();
        return $result ? true : 'Failed to update requirement.';
    }


     /**
     * Get listings for a specific user (paginated)
     */
    public function getListingsByUser($userId, $start = 0, $perPage = 24) {
        if (!$this->conn) {
            require_once __DIR__ . '/../config/database.php';
            $this->conn = getDatabaseConnection();
        }
        $sql = "SELECT l.*, p.name as profile_name FROM listing l LEFT JOIN profile p ON l.user_id = p.id WHERE l.user_id = ? ORDER BY l.id DESC LIMIT ?, ?";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            throw new Exception('Prepare failed: ' . $this->conn->error);
        }
        $stmt->bind_param('iii', $userId, $start, $perPage);
        $stmt->execute();
        $result = $stmt->get_result();
        $listings = [];
        while ($row = $result->fetch_assoc()) {
            $listings[] = $row;
        }
        $stmt->close();
        return $listings;
    }

    /**
     * Get total listing count for a specific user
     */
    public function getListingsCountByUser($userId) {
        if (!$this->conn) {
            require_once __DIR__ . '/../config/database.php';
            $this->conn = getDatabaseConnection();
        }
        $sql = "SELECT COUNT(*) as count FROM listing WHERE user_id = ?";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            throw new Exception('Prepare failed: ' . $this->conn->error);
        }
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();
        return $count;
    }

        /**
     * Insert feedback into feedback table
     */
    public function insertFeedback($user_id, $subject, $feedback) {
        // Ensure connection is initialized
        if (!$this->conn) {
            require_once __DIR__ . '/../config/database.php';
            $this->conn = getDatabaseConnection();
        }
        $stmt = $this->conn->prepare("INSERT INTO feedback (subject, feedback, user_id) VALUES (?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param('ssi', $subject, $feedback, $user_id);
            $success = $stmt->execute();
            $stmt->close();
            return $success ? true : $this->conn->error;
        } else {
            return $this->conn->error;
        }
    }

    // Update user password in user table
    public function updateUserPassword($userId, $password)
    {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE user SET password = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param('si', $hash, $userId);
            $success = $stmt->execute();
            $stmt->close();
            return $success ? true : "Failed to update password.";
        } else {
            error_log("Prepare failed in updateUserPassword: " . $this->conn->error);
            return "Failed to update password.";
        }
    }

    // Delete user and all related data (profile, listings, images)
    public function deleteUserAndData($userId) {
        // Ensure connection is initialized
        if (!$this->conn) {
            require_once __DIR__ . '/../config/database.php';
            $this->conn = getDatabaseConnection();
        }
        $conn = $this->conn;
        $conn->begin_transaction();
        try {
            // Get profile picture path (fix: use id, not user_id)
            $profileRes = $conn->prepare("SELECT uploaded_picture FROM profile WHERE id = ? LIMIT 1");
            $profileRes->bind_param('i', $userId);
            $profileRes->execute();
            $result = $profileRes->get_result();
            $profilePic = ($result && $result->num_rows > 0) ? $result->fetch_assoc()['uploaded_picture'] : '';
            $profileRes->close();
            // Delete from listings
            // Remove uploaded_picture from DB before deleting profile
            $conn->query("UPDATE profile SET uploaded_picture = NULL WHERE id = " . intval($userId));

            // Get all listing images for this user
            $listingImages = [];
            $listingRes = $conn->query("SELECT image1, image2, image3, image4, image5 FROM listing WHERE user_id = " . intval($userId));
            if ($listingRes) {
                while ($row = $listingRes->fetch_assoc()) {
                    for ($i = 1; $i <= 5; $i++) {
                        $imgField = 'image' . $i;
                        if (!empty($row[$imgField])) {
                            $listingImages[] = $row[$imgField];
                        }
                    }
                }
            }

            // Delete from listings
            $conn->query("DELETE FROM listing WHERE user_id = " . intval($userId));
            // Delete from profile
            $conn->query("DELETE FROM profile WHERE id = " . intval($userId));
            // Delete from user table
            $conn->query("DELETE FROM user WHERE id = " . intval($userId));
            // Delete other related tables if needed (add here)
            // Remove images from server
            // Remove profile image
            if ($profilePic && file_exists(__DIR__ . '/../' . $profilePic)) {
                @unlink(__DIR__ . '/../' . $profilePic);
            }
            // Remove all listing images
            foreach ($listingImages as $img) {
                if ($img && file_exists(__DIR__ . '/../' . $img)) {
                    @unlink(__DIR__ . '/../' . $img);
                }
            }
            $conn->commit();
            return true;
        } catch (Exception $e) {
            $conn->rollback();
            return $e->getMessage();
        }
    }
    // Ensure database connection is initialized
    // Fetch N random avatars by gender
    public function getRandomAvatarsByGender($gender, $limit = 4)
    {
        $conn = $this->getConnection();
        $stmt = $conn->prepare("SELECT id, image FROM profile_pictures WHERE gender = ? ORDER BY RAND() LIMIT ?");
        $stmt->bind_param('si', $gender, $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        $avatars = [];
        while ($row = $result->fetch_assoc()) {
            $avatars[] = $row;
        }
        $stmt->close();
        return $avatars;
    }
    // Public getter for DB connection
    public function getConnection()
    {
        return $this->conn;
    }

    // Get avatar image by id from profile_pictures table
    public function getAvatarImageById($avatarId)
    {
        $sql = "SELECT image FROM profile_pictures WHERE id = ? LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $avatarId);
        $stmt->execute();
        $result = $stmt->get_result();
        $image = '';
        if ($row = $result->fetch_assoc()) {
            $image = $row['image'];
        }
        $stmt->close();
        return $image;
    }


    // Get user by id from user table
    public function getUserById($userId)
    {
        $sql = "SELECT * FROM user WHERE id = ? LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();
        return $user;
    }


    // Update email in user table with uniqueness check
    // Update only changed profile fields
    public function updateProfileFields($userId, $fields)
    {
        if (empty($fields))
            return true; // Nothing to update

        $updates = [];
        $types = '';
        $params = [];
        foreach ($fields as $key => $value) {
            if ($key === 'phone') {
                // Check uniqueness only if phone is being changed
                $sql = "SELECT id FROM profile WHERE phone = ? AND id != ?";
                $stmt = $this->conn->prepare($sql);
                $stmt->bind_param('si', $value, $userId);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result->fetch_assoc()) {
                    $stmt->close();
                    return "Phone number already exists.";
                }
                $stmt->close();
            }
            $updates[] = "$key = ?";
            $types .= 's';
            $params[] = $value;
        }
        $types .= 'i';
        $params[] = $userId;
        $sql = "UPDATE profile SET " . implode(', ', $updates) . " WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param($types, ...$params);
        $success = $stmt->execute();
        $stmt->close();
        return $success ? true : "Failed to update profile.";
    }

    // Update email in user table with uniqueness check
    public function updateUserEmail($userId, $email)
    {
        $sql = "SELECT id FROM user WHERE email = ? AND id != ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('si', $email, $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->fetch_assoc()) {
            $stmt->close();
            return "Email already exists.";
        }
        $stmt->close();
        $sql = "UPDATE user SET email = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('si', $email, $userId);
        $success = $stmt->execute();
        $stmt->close();
        return $success ? true : "Failed to update email.";
    }

    /**
     * Get email for a listing's user
     */
    public function getEmailByListingId($listingId)
    {
        $sql = "SELECT u.email FROM listing l LEFT JOIN user u ON l.user_id = u.id WHERE l.id = ? LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $listingId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            return trim($row['email']);
        }
        return '';
    }


    /**
     * Get phone number for a listing's user
     */
    public function getPhoneByListingId($listingId)
    {
        $sql = "SELECT p.phone FROM listing l LEFT JOIN profile p ON l.user_id = p.id WHERE l.id = ? LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $listingId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            return preg_replace('/[^0-9]/', '', $row['phone']); // Only digits
        }
        return '';
    }

    /**
     * Get contact options for a listing by ID
     */
    public function getContactOptionsByListingId($listingId)
    {
        $sql = "SELECT contact FROM listing WHERE id = ? LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $listingId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $contactRaw = strtolower(trim($row['contact']));
            $contactArr = array_filter(array_map('trim', preg_split('/\s*\|\s*/', $contactRaw)));
            return $contactArr;
        }
        return [];
    }

    /**
     * Get amenities (image, name) by array of IDs
     */
    public static function getAmenitiesByIds($ids)
    {
        if (empty($ids) || !is_array($ids))
            return [];
        require_once __DIR__ . '/../config/database.php';
        $conn = $GLOBALS['conn'] ?? null;
        if (!$conn) {
            $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        }
        $idsStr = implode(',', array_map('intval', $ids));
        $sql = "SELECT name, images FROM amenities WHERE id IN ($idsStr)";
        $result = $conn->query($sql);
        $amenities = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $amenities[] = $row;
            }
        }
        return $amenities;
    }
    /**
     * Get property highlight names by array of IDs
     */
    public static function getPropertyHighlightsByIds($ids)
    {
        if (empty($ids) || !is_array($ids))
            return [];
        require_once __DIR__ . '/../config/database.php';
        $conn = $GLOBALS['conn'] ?? null;
        if (!$conn) {
            $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        }
        $idsStr = implode(',', array_map('intval', $ids));
        $sql = "SELECT name FROM property_highlights WHERE id IN ($idsStr)";
        $result = $conn->query($sql);
        $names = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $names[] = $row['name'];
            }
        }
        return $names;
    }
    // Get paginated listings from the database
    public function getListings($start = 0, $perPage = 24, $location = '', $gender = '', $looking_for = '')
    {
        if (!$this->conn) {
            require_once __DIR__ . '/../config/database.php';
            $this->conn = getDatabaseConnection();
        }
        $where = [];
        $params = [];
        $types = '';
        // Location filter (elastic: match any word)
        if (!empty($location)) {
            $originalLocation = $location;
            if (is_numeric($location)) {
                $stmtLoc = $this->conn->prepare('SELECT location FROM location WHERE id = ?');
                if ($stmtLoc) {
                    $stmtLoc->bind_param('i', $location);
                    $stmtLoc->execute();
                    $resLoc = $stmtLoc->get_result();
                    $rowLoc = $resLoc->fetch_assoc();
                    $stmtLoc->close();
                    if ($rowLoc && !empty($rowLoc['location'])) {
                        $location = $rowLoc['location'];
                    } else {
                        // Location lookup failed, return no listings
                        return [];
                    }
                } else {
                    error_log('Failed to prepare statement for location lookup in getListings: ' . $this->conn->error);
                    // Location lookup failed, return no listings
                    return [];
                }
            }
            if (!empty($location)) {
                $words = preg_split('/\s+/', $location);
                $pattern = [];
                foreach ($words as $w) {
                    $w = trim($w);
                    if ($w !== '') {
                        // Escape regex special chars for MySQL
                        $pattern[] = preg_replace('/([\\.\[\](){}?*+^$|!<>:=\-])/', '\\$1', $w);
                    }
                }
                if ($pattern) {
                    $regex = '(' . implode('|', $pattern) . ')';
                    $where[] = 'l.location REGEXP ?';
                    $params[] = $regex;
                    $types .= 's';
                }
            }
        }
        // Gender filter
        if (!empty($gender) && $gender !== 'any') {
            $where[] = 'l.gender = ?';
            $params[] = $gender;
            $types .= 's';
        }
        // Looking for (role) filter
        if (!empty($looking_for)) {
            if ($looking_for === 'room') {
                $where[] = '(l.role = ? OR l.role = ?)';
                $params[] = 'room';
                $params[] = 'tenant';
                $types .= 'ss';
            } elseif ($looking_for === 'pg') {
                $where[] = 'l.role = ?';
                $params[] = 'pg';
                $types .= 's';
            } elseif ($looking_for === 'roommate') {
                $where[] = 'l.role = ?';
                $params[] = 'roommate';
                $types .= 's';
            }
        }
        $sql = "SELECT l.*, p.name AS profile_name, p.uploaded_picture AS profile_img FROM listing l LEFT JOIN profile p ON l.user_id = p.id";
        if ($where) {
            $sql .= ' WHERE ' . implode(' AND ', $where);
        }
        $sql .= ' ORDER BY l.created_at DESC LIMIT ?, ?';
        $params[] = $start;
        $params[] = $perPage;
        $types .= 'ii';
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            error_log('Failed to prepare statement in getListings: ' . $this->conn->error . "\nSQL: $sql\nParams: " . json_encode($params));
            return [];
        }
        if ($types) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        $listings = [];
        while ($row = $result->fetch_assoc()) {
            $row['amenities'] = isset($row['amenities']) ? explode(' | ', $row['amenities']) : [];
            $row['propertyhighlights'] = isset($row['propertyhighlights']) ? explode(' | ', $row['propertyhighlights']) : [];
            $row['image'] = $row['image1'] ?: 'assets/hero-section/description.png';
            $row['price'] = '₹' . number_format($row['rent']) . '/mo';
            $row['profile_img'] = !empty($row['profile_img']) ? $row['profile_img'] : 'assets/profile/user.png';
            $row['profile_name'] = !empty($row['profile_name']) ? $row['profile_name'] : 'User';
            $row['amenity_icons'] = array_fill(0, count($row['amenities']), 'fa-bed');
            $listings[] = $row;
        }
        $stmt->close();
        return $listings;
    }

    // Get a single listing by its ID, including profile info
    public function getListingById($id)
    {
        $sql = "SELECT l.*, p.name AS profile_name, p.uploaded_picture AS profile_img FROM listing l LEFT JOIN profile p ON l.user_id = p.id WHERE l.id = ? LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
            error_log('Failed to prepare statement in getListingById: ' . $this->conn->error);
            return null;
        }
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        if ($row) {
            // Prepare amenities and highlights arrays
            $row['amenities'] = isset($row['amenities']) ? explode(' | ', $row['amenities']) : [];
            $row['highlights'] = isset($row['propertyhighlights']) ? explode(' | ', $row['propertyhighlights']) : [];
            // Prepare images
            $row['image'] = $row['image1'] ?: 'assets/hero-section/description.png';
            $row['price'] = $row['rent'];
            $row['profile_img'] = !empty($row['profile_img']) ? $row['profile_img'] : 'assets/images/profile/user.png';
            $row['profile_name'] = !empty($row['profile_name']) ? $row['profile_name'] : 'User';
            return $row;
        }
        return null;
    }

    // Get profile image for a user by user_id
    public static function getProfileImageByUserId($userId)
    {
        require_once __DIR__ . '/../config/database.php';
        $conn = getPDO();
        $defaultImg = 'assets/images/profile/user.png';
        $sql = 'SELECT uploaded_picture, avatar_id FROM profile WHERE id = ? LIMIT 1';
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->execute([$userId]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row) {
                if (!empty($row['uploaded_picture'])) {
                    return $row['uploaded_picture'];
                } elseif (!empty($row['avatar_id'])) {
                    $sql2 = 'SELECT image FROM profile_pictures WHERE id = ? LIMIT 1';
                    $stmt2 = $conn->prepare($sql2);
                    if ($stmt2) {
                        $stmt2->execute([$row['avatar_id']]);
                        $row2 = $stmt2->fetch(PDO::FETCH_ASSOC);
                        if ($row2 && !empty($row2['image'])) {
                            return $row2['image'];
                        }
                    }
                }
            }
        }
        return $defaultImg;
    }

    // Get total count of listings
    public function getListingsCount()
    {
        $sql = "SELECT COUNT(*) as cnt FROM listing";
        $result = $this->conn->query($sql);
        $row = $result->fetch_assoc();
        return (int) $row['cnt'];
    }
    // Insert requirement for roommate form
    public function addroommatelisting($user_id, $location, $rent, $gender, $occupancy, $highlights, $contact, $description, $role)
    {
        $sql = "INSERT INTO listing (user_id, location, rent, gender, occupancy, propertyhighlights, contact, description, role) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('isdssssss', $user_id, $location, $rent, $gender, $occupancy, $highlights, $contact, $description, $role);
        $result = $stmt->execute();
        $insertId = $result ? $this->conn->insert_id : false;
        $stmt->close();
        return $insertId;
    }
    // SQL to add role column:
    // ALTER TABLE listing ADD COLUMN role ENUM('room', 'roommate') NOT NULL DEFAULT 'room' AFTER contact;
    // Fetch all amenities from the amenities table
    public function getAllAmenities()
    {
        $sql = "SELECT id, name, images FROM amenities ORDER BY name ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        $amenities = [];
        while ($row = $result->fetch_assoc()) {
            $amenities[] = $row;
        }
        $stmt->close();
        return $amenities;
    }
    // Static: Login user by email/password
    public static function loginUser($email, $password)
    {
        require_once __DIR__ . '/../config/database.php';
        $pdo = getPDO();
        $stmt = $pdo->prepare("SELECT id, name, email, password, phone, role FROM user WHERE email = :email LIMIT 1");
        $stmt->bindValue(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user && password_verify($password, $user['password'])) {
            // Always return the user with the correct role field
            return $user;
        }
        return false;
    }
    private $conn;


    public function __construct()
    {
        require_once __DIR__ . '/../config/database.php';
        $this->conn = getDatabaseConnection();
    }


    private function sanitizeInput($input)
    {
        return htmlspecialchars(strip_tags($input));
    }

    private function executeQuery($sql, $params)
    {
        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
            throw new Exception("Failed to prepare statement: " . $this->conn->error);
        }
        if ($params) {
            $types = str_repeat('s', count($params));
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        return $stmt;
    }

    public function login($email, $password)
    {
        $email = $this->sanitizeInput($email);

        $sql = "SELECT * FROM user WHERE email = ? AND role = 'admin'";
        $stmt = $this->executeQuery($sql, [$email]);
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                return $user;
            }
        }
        return false;
    }

    public function getAdminById($id)
    {
        $sql = "SELECT * FROM user WHERE id = ? AND role = 'admin'";
        $stmt = $this->conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_assoc();
        } else {
            error_log("Prepare failed in getAdminById: " . $this->conn->error);
            return false;
        }
    }

    public function updateAdminPassword($id, $newPassword)
    {
        $hash = password_hash($newPassword, PASSWORD_DEFAULT);
        $sql = "UPDATE user SET password = ? WHERE user_id = ? AND role = 'admin'";
        $stmt = $this->conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param('si', $hash, $id);
            return $stmt->execute();
        } else {
            error_log("Prepare failed in updateAdminPassword: " . $this->conn->error);
            return false;
        }
    }

    // Fetch all property highlights from the property_highlights table
    public function getAllPropertyHighlights()
    {
        $sql = "SELECT id, name FROM property_highlights ORDER BY name ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        $highlights = [];
        while ($row = $result->fetch_assoc()) {
            $highlights[] = $row;
        }
        $stmt->close();
        return $highlights;
    }

    // Static: Register a new user (email, password)
    public static function registerUser($email, $password)
    {
        require_once __DIR__ . '/../config/database.php';
        $pdo = getPDO();
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO user (name, email, password, phone, role) VALUES (:name, :email, :password, :phone, 'user')";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':name', $email); // Using email as name for now
        $stmt->bindValue(':email', $email);
        $stmt->bindValue(':password', $hashed);
        $stmt->bindValue(':phone', null, PDO::PARAM_NULL);
        try {
            $result = $stmt->execute();
            if ($result) {
                return $pdo->lastInsertId();
            } else {
                $errorInfo = $stmt->errorInfo();
                error_log('User registration failed: ' . implode(' | ', $errorInfo));
                return false;
            }
        } catch (PDOException $e) {
            error_log('User registration exception: ' . $e->getMessage());
            return false;
        }
    }

    // Save or update user profile
    public function saveProfile($userId, $data, $file)
    {
        $uploaded_picture = null;
        if ($file && isset($file['error']) && $file['error'] === UPLOAD_ERR_OK) {
            $monthYear = date('F_Y');
            $dir = __DIR__ . '/../assets/profile/profilepictures/' . $monthYear;
            if (!is_dir($dir)) {
                mkdir($dir, 0777, true);
            }
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = $userId . '_' . date('YmdHis') . '.' . $ext;
            $target = $dir . '/' . $filename;
            move_uploaded_file($file['tmp_name'], $target);
            $uploaded_picture = 'assets/profile/profilepictures/' . $monthYear . '/' . $filename;
        }

        // Enforce unique phone number only if changed
        if (!empty($data['phone'])) {
            $currentPhone = '';
            $getCurrent = $this->conn->prepare("SELECT phone FROM user WHERE id = ? LIMIT 1");
            $getCurrent->bind_param('i', $userId);
            $getCurrent->execute();
            $resCurrent = $getCurrent->get_result();
            if ($rowCurrent = $resCurrent->fetch_assoc()) {
                $currentPhone = $rowCurrent['phone'];
            }
            $getCurrent->close();
            if ($data['phone'] !== $currentPhone) {
                $checkPhone = $this->conn->prepare("SELECT id FROM user WHERE phone = ? AND id != ? LIMIT 1");
                $checkPhone->bind_param('si', $data['phone'], $userId);
                $checkPhone->execute();
                $result = $checkPhone->get_result();
                if ($result->fetch_assoc()) {
                    // Phone number already exists for another user
                    return ['error' => 'Phone number already exists. Please use another number.'];
                }
                $checkPhone->close();
                $updatePhone = $this->conn->prepare("UPDATE user SET phone = ? WHERE id = ?");
                $updatePhone->bind_param('si', $data['phone'], $userId);
                $updatePhone->execute();
                $updatePhone->close();
            }
        }

        // If avatar selected, get its id from profile_pictures table
        $avatar_id = null;
        if (!empty($data['avatar'])) {
            $stmt = $this->conn->prepare("SELECT id FROM profile_pictures WHERE image = ? LIMIT 1");
            $stmt->bind_param('s', $data['avatar']);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($row = $result->fetch_assoc()) {
                $avatar_id = $row['id'];
            }
        }

        $sql = "INSERT INTO profile (id, name, phone, who_are_you, gender, location, avatar_id, uploaded_picture)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)
                ON DUPLICATE KEY UPDATE
                name=VALUES(name), phone=VALUES(phone), who_are_you=VALUES(who_are_you), gender=VALUES(gender),
                location=VALUES(location), avatar_id=VALUES(avatar_id), uploaded_picture=VALUES(uploaded_picture)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param(
            'issssiss',
            $userId,
            $data['name'],
            $data['phone'],
            $data['who'],
            $data['gender'],
            $data['location'],
            $avatar_id,
            $uploaded_picture
        );
        if ($stmt->execute()) {
            return true;
        } else {
            return ['error' => 'Failed to save profile.'];
        }
    }

    public function updateVibes($userId, $vibesStr)
    {
        require_once __DIR__ . '/../config/database.php';
        $pdo = getPDO();
        // Check if profile row exists
        $checkStmt = $pdo->prepare("SELECT id FROM profile WHERE id = :id");
        $checkStmt->bindValue(':id', $userId);
        $checkStmt->execute();
        if ($checkStmt->fetch()) {
            // Row exists, update
            $stmt = $pdo->prepare("UPDATE profile SET vibes = :vibes WHERE id = :id");
            $stmt->bindValue(':vibes', $vibesStr);
            $stmt->bindValue(':id', $userId);
            $stmt->execute();
            return $stmt->rowCount() > 0;
        } else {
            // Row does not exist, insert
            $stmt = $pdo->prepare("INSERT INTO profile (id, vibes) VALUES (:id, :vibes)");
            $stmt->bindValue(':id', $userId);
            $stmt->bindValue(':vibes', $vibesStr);
            $stmt->execute();
            return $stmt->rowCount() > 0;
        }
    }

    // Get user profile and avatar info by user ID
    public function getProfileById($userId)
    {
        $stmt = $this->conn->prepare('SELECT name, phone, gender, avatar_id, uploaded_picture, vibes, who_are_you FROM profile WHERE id = ? LIMIT 1');
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $profile = $result->fetch_assoc();
        $stmt->close();
        // Get avatar image if avatar_id exists
        if (!empty($profile['avatar_id'])) {
            $stmt2 = $this->conn->prepare('SELECT image FROM profile_pictures WHERE id = ? LIMIT 1');
            $stmt2->bind_param('i', $profile['avatar_id']);
            $stmt2->execute();
            $result2 = $stmt2->get_result();
            if ($row = $result2->fetch_assoc()) {
                $profile['avatar_image'] = $row['image'];
            }
            $stmt2->close();
        }
        return $profile;
    }

    // Add a new room listing to the database
    public function addRoomListing($user_id, $location, $rent, $gender, $occupancy, $highlightsStr, $amenitiesStr, $description, $contact, $imagePaths, $role)
    {
        $sql = "INSERT INTO listing (user_id, location, rent, gender, occupancy, propertyhighlights, amenities, description, contact, image1, image2, image3, image4, image5, role) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $img1 = isset($imagePaths[0]) ? $imagePaths[0] : '';
        $img2 = isset($imagePaths[1]) ? $imagePaths[1] : '';
        $img3 = isset($imagePaths[2]) ? $imagePaths[2] : '';
        $img4 = isset($imagePaths[3]) ? $imagePaths[3] : '';
        $img5 = isset($imagePaths[4]) ? $imagePaths[4] : '';
        $stmt->bind_param(
            'isdssssssssssss',
            $user_id,
            $location,
            $rent,
            $gender,
            $occupancy,
            $highlightsStr,
            $amenitiesStr,
            $description,
            $contact,
            $img1,
            $img2,
            $img3,
            $img4,
            $img5,
            $role
        );
        $result = $stmt->execute();
        $insertId = $stmt->insert_id;
        $stmt->close();
        if ($result && is_numeric($insertId) && $insertId > 0) {
            $_SESSION['success'] = 'Room listing added successfully!';
            header('Location: ' . APP_URL . '/roomlisting?id=' . $insertId);
            exit();
        }
        return $result;
    }

    // Optionally, add a helper for image upload if needed elsewhere
    public function uploadRoomImage($file, $targetDir)
    {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
        if (!in_array($file['type'], $allowedTypes)) {
            return false;
        }
        if ($file['size'] > 25 * 1024 * 1024) {
            return false;
        }
        $targetPath = $targetDir . '/' . uniqid() . '_' . basename($file['name']);
        if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
            return false;
        }
        return $targetPath;
    }

    // Static: Find user by email
    public static function findByEmail($email)
    {
        require_once __DIR__ . '/../config/database.php';
        $pdo = getPDO();
        $stmt = $pdo->prepare("SELECT * FROM user WHERE email = :email LIMIT 1");
        $stmt->bindValue(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    /**
     * Get all feedback with user name and phone
     */
    public function getAllFeedbackWithUser()
    {
        if (!$this->conn) {
            require_once __DIR__ . '/../config/database.php';
            $this->conn = getDatabaseConnection();
        }
        $sql = "SELECT f.id, f.subject, f.feedback, f.user_id, f.status, f.created_at, f.updated_at, p.name as user_name, p.phone as user_phone FROM feedback f LEFT JOIN profile p ON f.user_id = p.id ORDER BY f.created_at DESC";
        $result = $this->conn->query($sql);
        $feedbacks = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $feedbacks[] = $row;
            }
        }
        return $feedbacks;
    }
    /**
     * Get single feedback by id with user info
     */
    public function getFeedbackById($id)
    {
        if (!$this->conn) {
            require_once __DIR__ . '/../config/database.php';
            $this->conn = getDatabaseConnection();
        }
        $sql = "SELECT f.*, p.name as user_name, p.phone as user_phone FROM feedback f LEFT JOIN profile p ON f.user_id = p.id WHERE f.id = ? LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $feedback = $result->fetch_assoc();
        $stmt->close();
        return $feedback;
    }
    /**
     * Update feedback status and content
     */
    public function updateFeedback($id, $subject, $feedback, $status)
    {
        if (!$this->conn) {
            require_once __DIR__ . '/../config/database.php';
            $this->conn = getDatabaseConnection();
        }
        $sql = "UPDATE feedback SET subject = ?, feedback = ?, status = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('sssi', $subject, $feedback, $status, $id);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }
    /**
     * Delete feedback by id
     */
    public function deleteFeedback($id)
    {
        if (!$this->conn) {
            require_once __DIR__ . '/../config/database.php';
            $this->conn = getDatabaseConnection();
        }
        $sql = "DELETE FROM feedback WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $id);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }
}