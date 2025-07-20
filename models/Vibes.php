<?php
class Vibes {
    public function addVibes($post, $files) {
        $vibes_names = $post['vibes_name'] ?? [];
        $images = $files['vibes_image'] ?? [];
        $results = [];
        // Handle both single and multiple uploads
        if (!is_array($vibes_names)) $vibes_names = [$vibes_names];
        if (!isset($images['name'])) return ['success' => false, 'message' => 'No images uploaded.'];
        $count = is_array($images['name']) ? count($images['name']) : 1;
        for ($i = 0; $i < $count; $i++) {
            $vibes_name = trim($vibes_names[$i] ?? '');
            $image = [
                'name' => is_array($images['name']) ? $images['name'][$i] : $images['name'],
                'type' => is_array($images['type']) ? $images['type'][$i] : $images['type'],
                'tmp_name' => is_array($images['tmp_name']) ? $images['tmp_name'][$i] : $images['tmp_name'],
                'error' => is_array($images['error']) ? $images['error'][$i] : $images['error'],
                'size' => is_array($images['size']) ? $images['size'][$i] : $images['size'],
            ];
            if ($vibes_name && $image && $image['tmp_name']) {
                if ($image['size'] > 512000) {
                    $results[] = ['error' => false, 'message' => $vibes_name . ': Image must be less than 500KB.'];
                    continue;
                }
                $date = date('YmdHis');
                $ext = pathinfo($image['name'], PATHINFO_EXTENSION);
                $safe_name = preg_replace('/[^A-Za-z0-9_-]/', '_', $vibes_name);
                $filename = $safe_name . '_' . $date . '.' . $ext;
                $target_dir = __DIR__ . '/../assets/vibes/';
                $target_path = $target_dir . $filename;
                if (!is_dir($target_dir)) {
                    mkdir($target_dir, 0777, true);
                }
                if (move_uploaded_file($image['tmp_name'], $target_path)) {
                    $db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
                    if ($db->connect_error) {
                        $results[] = ['success' => false, 'message' => $vibes_name . ': DB Connection Error'];
                        continue;
                    }
                    $img_path = 'assets/vibes/' . $filename;
                    // Check if table has 'name' column, fallback to images only if not
                    $stmt = $db->prepare("INSERT INTO vibes (name, images) VALUES (?, ?)");
                    if ($stmt === false) {
                        // Try images only
                        $stmt = $db->prepare("INSERT INTO vibes (images) VALUES (?)");
                        if ($stmt) {
                            $stmt->bind_param('s', $img_path);
                        } else {
                            $results[] = ['success' => false, 'message' => $vibes_name . ': DB Prepare Error'];
                            $db->close();
                            continue;
                        }
                    } else {
                        $stmt->bind_param('ss', $vibes_name, $img_path);
                    }
                    if ($stmt->execute()) {
                        $results[] = ['success' => true, 'message' => $vibes_name . ': Vibe added successfully!'];
                    } else {
                        $results[] = ['success' => false, 'message' => $vibes_name . ': DB Insert Error'];
                    }
                    $stmt->close();
                    $db->close();
                } else {
                    $results[] = ['error' => false, 'message' => $vibes_name . ': Image upload failed!'];
                }
            } else {
                $results[] = ['error' => false, 'message' => $vibes_name . ': Please provide all fields.'];
            }
        }
        return $results;
    }

    public function getVibesByDate($start_date, $end_date) {
        $db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($db->connect_error) return [];
        $stmt = $db->prepare("SELECT * FROM vibes WHERE created_at BETWEEN ? AND ? ORDER BY created_at DESC");
        $stmt->bind_param('ss', $start_date, $end_date);
        $stmt->execute();
        $result = $stmt->get_result();
        $vibes = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        $db->close();
        return $vibes;
    }

    public function updateVibe($id, $name, $image = null) {
        $db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($db->connect_error) return false;
        $img_path = null;
        $old_img_path = null;
        if ($image && $image['tmp_name']) {
            // Get old image path
            $stmt = $db->prepare("SELECT images FROM vibes WHERE id = ?");
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($row = $result->fetch_assoc()) {
                $old_img_path = $row['images'];
            }
            $stmt->close();
            // Save new image
            if ($image['size'] > 512000) return false;
            $date = date('YmdHis');
            $ext = pathinfo($image['name'], PATHINFO_EXTENSION);
            $safe_name = preg_replace('/[^A-Za-z0-9_-]/', '_', $name);
            $filename = $safe_name . '_' . $date . '.' . $ext;
            $target_dir = __DIR__ . '/../assets/vibes/';
            $target_path = $target_dir . $filename;
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
            if (move_uploaded_file($image['tmp_name'], $target_path)) {
                $img_path = 'assets/vibes/' . $filename;
                // Delete old image file
                if ($old_img_path) {
                    $old_file_path = __DIR__ . '/../' . $old_img_path;
                    if (file_exists($old_file_path)) {
                        unlink($old_file_path);
                    }
                }
            } else {
                return false;
            }
        }
        if ($img_path) {
            $stmt = $db->prepare("UPDATE vibes SET name = ?, images = ? WHERE id = ?");
            $stmt->bind_param('ssi', $name, $img_path, $id);
        } else {
            $stmt = $db->prepare("UPDATE vibes SET name = ? WHERE id = ?");
            $stmt->bind_param('si', $name, $id);
        }
        $success = $stmt->execute();
        $stmt->close();
        $db->close();
        return $success;
    }

    public function deleteVibe($id) {
        $db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($db->connect_error) return false;
        // Get image path
        $stmt = $db->prepare("SELECT images FROM vibes WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $img_path = null;
        if ($row = $result->fetch_assoc()) {
            $img_path = $row['images'];
        }
        $stmt->close();
        // Delete vibe record
        $stmt = $db->prepare("DELETE FROM vibes WHERE id = ?");
        $stmt->bind_param('i', $id);
        $success = $stmt->execute();
        $stmt->close();
        $db->close();
        // Delete image file
        if ($success && $img_path) {
            $file_path = __DIR__ . '/../' . $img_path;
            if (file_exists($file_path)) {
                unlink($file_path);
            }
        }
        return $success;
    }

    private $conn;
    public function __construct($pdo) {
        $this->conn = $pdo;
    }
    public function getAllVibes() {
        $stmt = $this->conn->prepare("SELECT id, name, images FROM vibes ORDER BY name ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
