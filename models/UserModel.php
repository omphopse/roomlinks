<?php

class UserModel
    
{
    // Static: Login user by email/password
    public static function loginUser($email, $password) {
        require_once __DIR__ . '/../config/database.php';
        $pdo = getPDO();
        $stmt = $pdo->prepare("SELECT * FROM user WHERE email = :email LIMIT 1");
        $stmt->bindValue(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user && password_verify($password, $user['password'])) {
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
        $sql = "SELECT * FROM user WHERE user_id = ? AND role = 'admin'";
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

    // Static: Register a new user (email, password)
    public static function registerUser($email, $password) {
        require_once __DIR__ . '/../config/database.php';
        $pdo = getPDO();
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO user (name, email, password, phone, role) VALUES (:name, :email, :password, NULL, 'user')";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':name', $email);
        $stmt->bindValue(':email', $email);
        $stmt->bindValue(':password', $hashed);
        try {
            $stmt->execute();
            return $pdo->lastInsertId();
        } catch (PDOException $e) {
            return false;
        }
    }

    // Static: Find user by email
    public static function findByEmail($email) {
        require_once __DIR__ . '/../config/database.php';
        $pdo = getPDO();
        $stmt = $pdo->prepare("SELECT * FROM user WHERE email = :email LIMIT 1");
        $stmt->bindValue(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Save or update user profile
    public function saveProfile($userId, $data, $file) {
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

        // Enforce unique phone number
        if (!empty($data['phone'])) {
            $checkPhone = $this->conn->prepare("SELECT id FROM user WHERE phone = ? AND id != ? LIMIT 1");
            $checkPhone->bind_param('si', $data['phone'], $userId);
            $checkPhone->execute();
            $result = $checkPhone->get_result();
            if ($result->fetch_assoc()) {
                // Phone number already exists for another user
                return ['error' => 'Phone number already exists. Please use another number.'];
            }
            $updatePhone = $this->conn->prepare("UPDATE user SET phone = ? WHERE id = ?");
            $updatePhone->bind_param('si', $data['phone'], $userId);
            $updatePhone->execute();
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

    public function updateVibes($userId, $vibesStr) {
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
    public function getProfileById($userId) {
        $stmt = $this->conn->prepare('SELECT name, avatar_id, uploaded_picture FROM profile WHERE id = ? LIMIT 1');
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
}