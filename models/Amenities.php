<?php
class Amenities {
    public function addAmenities($post, $files) {
        $names = $post['amenity_name'] ?? [];
        $images = $files['amenity_image'] ?? [];
        $results = [];
        if (!is_array($names)) $names = [$names];
        if (!isset($images['name'])) return ['success' => false, 'message' => 'No images uploaded.'];
        $count = is_array($images['name']) ? count($images['name']) : 1;
        for ($i = 0; $i < $count; $i++) {
            $name = trim($names[$i] ?? '');
            $image = [
                'name' => is_array($images['name']) ? $images['name'][$i] : $images['name'],
                'type' => is_array($images['type']) ? $images['type'][$i] : $images['type'],
                'tmp_name' => is_array($images['tmp_name']) ? $images['tmp_name'][$i] : $images['tmp_name'],
                'error' => is_array($images['error']) ? $images['error'][$i] : $images['error'],
                'size' => is_array($images['size']) ? $images['size'][$i] : $images['size'],
            ];
            if ($name && $image && $image['tmp_name']) {
                if ($image['size'] > 512000) {
                    $results[] = ['error' => false, 'message' => $name . ': Image must be less than 500KB.'];
                    continue;
                }
                $date = date('YmdHis');
                $ext = pathinfo($image['name'], PATHINFO_EXTENSION);
                $safe_name = preg_replace('/[^A-Za-z0-9_-]/', '_', $name);
                $filename = $safe_name . '_' . $date . '.' . $ext;
                $target_dir = __DIR__ . '/../assets/amenities/';
                $target_path = $target_dir . $filename;
                if (!is_dir($target_dir)) {
                    mkdir($target_dir, 0777, true);
                }
                if (move_uploaded_file($image['tmp_name'], $target_path)) {
                    $db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
                    if ($db->connect_error) {
                        $results[] = ['success' => false, 'message' => $name . ': DB Connection Error'];
                        continue;
                    }
                    $img_path = 'assets/amenities/' . $filename;
                    $stmt = $db->prepare("INSERT INTO amenities (name, images) VALUES (?, ?)");
                    if ($stmt === false) {
                        $results[] = ['success' => false, 'message' => $name . ': DB Prepare Error'];
                        $db->close();
                        continue;
                    } else {
                        $stmt->bind_param('ss', $name, $img_path);
                    }
                    if ($stmt->execute()) {
                        $results[] = ['success' => true, 'message' => $name . ': Amenity added successfully!'];
                    } else {
                        $results[] = ['success' => false, 'message' => $name . ': DB Insert Error'];
                    }
                    $stmt->close();
                    $db->close();
                } else {
                    $results[] = ['error' => false, 'message' => $name . ': Image upload failed!'];
                }
            } else {
                $results[] = ['error' => false, 'message' => $name . ': Please provide all fields.'];
            }
        }
        return $results;
    }

    public function getAmenitiesByDate($start_date, $end_date) {
        $db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($db->connect_error) return [];
        $stmt = $db->prepare("SELECT * FROM amenities WHERE created_at BETWEEN ? AND ? ORDER BY created_at DESC");
        $stmt->bind_param('ss', $start_date, $end_date);
        $stmt->execute();
        $result = $stmt->get_result();
        $amenities = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        $db->close();
        return $amenities;
    }

    public function updateAmenity($id, $name, $image = null) {
        $db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($db->connect_error) return false;
        $img_path = null;
        $old_img_path = null;
        if ($image && $image['tmp_name']) {
            // Get old image path
            $stmt = $db->prepare("SELECT images FROM amenities WHERE id = ?");
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
            $target_dir = __DIR__ . '/../assets/amenities/';
            $target_path = $target_dir . $filename;
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
            if (move_uploaded_file($image['tmp_name'], $target_path)) {
                $img_path = 'assets/amenities/' . $filename;
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
            $stmt = $db->prepare("UPDATE amenities SET name = ?, images = ? WHERE id = ?");
            $stmt->bind_param('ssi', $name, $img_path, $id);
        } else {
            $stmt = $db->prepare("UPDATE amenities SET name = ? WHERE id = ?");
            $stmt->bind_param('si', $name, $id);
        }
        $success = $stmt->execute();
        $stmt->close();
        $db->close();
        return $success;
    }

    public function deleteAmenity($id) {
        $db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($db->connect_error) return false;
        // Get image path
        $stmt = $db->prepare("SELECT images FROM amenities WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $img_path = null;
        if ($row = $result->fetch_assoc()) {
            $img_path = $row['images'];
        }
        $stmt->close();
        // Delete amenity record
        $stmt = $db->prepare("DELETE FROM amenities WHERE id = ?");
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
}
