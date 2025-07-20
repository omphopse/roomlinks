    
<?php
class ProfilePicture {
    public function addProfilePictures($genders, $files) {
        $results = [];
        $count = is_array($genders) ? count($genders) : 0;
        require_once __DIR__ . '/../config/database.php';
        for ($i = 0; $i < $count; $i++) {
            $gender = isset($genders[$i]) ? $genders[$i] : '';
            $file = [
                'name' => isset($files['name'][$i]) ? $files['name'][$i] : '',
                'type' => isset($files['type'][$i]) ? $files['type'][$i] : '',
                'tmp_name' => isset($files['tmp_name'][$i]) ? $files['tmp_name'][$i] : '',
                'error' => isset($files['error'][$i]) ? $files['error'][$i] : 4,
                'size' => isset($files['size'][$i]) ? $files['size'][$i] : 0,
            ];
            if (!in_array($gender, ['men', 'women'])) {
                $results[] = ['success' => false, 'message' => 'Invalid gender selected for entry #' . ($i+1)];
                continue;
            }
            if ($file['error'] !== UPLOAD_ERR_OK || !$file['tmp_name']) {
                $results[] = ['success' => false, 'message' => 'Profile picture upload failed for entry #' . ($i+1)];
                continue;
            }
            if ($file['size'] > 512000) {
                $results[] = ['success' => false, 'message' => 'Image must be less than 500KB for entry #' . ($i+1)];
                continue;
            }
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            if (!in_array($ext, ['jpg', 'jpeg', 'png'])) {
                $results[] = ['success' => false, 'message' => 'Only JPG and PNG images are allowed for entry #' . ($i+1)];
                continue;
            }
            $targetDir = __DIR__ . '/../assets/profile/' . $gender . '/';
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }
            $filename = uniqid('profile_', true) . '.' . $ext;
            $targetPath = $targetDir . $filename;
            if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
                $results[] = ['success' => false, 'message' => 'Failed to save the profile picture for entry #' . ($i+1)];
                continue;
            }
            $relativePath = 'assets/profile/' . $gender . '/' . $filename;
            $conn = getDatabaseConnection();
            $stmt = $conn->prepare("INSERT INTO profile_pictures (gender, image) VALUES (?, ?)");
            $stmt->bind_param('ss', $gender, $relativePath);
            if ($stmt->execute()) {
                $results[] = ['success' => true, 'message' => 'Profile picture added successfully for entry #' . ($i+1)];
            } else {
                $results[] = ['success' => false, 'message' => 'DB Insert Error for entry #' . ($i+1)];
            }
            $stmt->close();
            $conn->close();
        }
        return $results;
    }

    public function getProfilePictures($start_date = null, $end_date = null) {
        require_once __DIR__ . '/../config/database.php';
        $conn = getDatabaseConnection();
        $query = "SELECT * FROM profile_pictures";
        $params = [];
        if ($start_date && $end_date) {
            $query .= " WHERE created_at BETWEEN ? AND ?";
            $params[] = $start_date;
            $params[] = $end_date;
        }
        $query .= " ORDER BY created_at DESC";
        $stmt = $conn->prepare($query);
        if ($params) {
            $stmt->bind_param('ss', $params[0], $params[1]);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        $pictures = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        $conn->close();
        return $pictures;
    }

    public function deleteProfilePicture($id) {
        require_once __DIR__ . '/../config/database.php';
        $conn = getDatabaseConnection();
        // Get image path
        $stmt = $conn->prepare("SELECT image FROM profile_pictures WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        if ($row && file_exists(__DIR__ . '/../' . $row['image'])) {
            unlink(__DIR__ . '/../' . $row['image']);
        }
        // Delete from DB
        $stmt = $conn->prepare("DELETE FROM profile_pictures WHERE id = ?");
        $stmt->bind_param('i', $id);
        $success = $stmt->execute();
        $stmt->close();
        $conn->close();
        return $success;
    }
}
