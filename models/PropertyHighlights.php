<?php
class PropertyHighlights {
    // ...existing code...
    // Get highlights by date range
    public function getHighlightsByDate($start_date, $end_date) {
        $stmt = $this->conn->prepare("SELECT * FROM property_highlights WHERE DATE(created_at) >= ? AND DATE(created_at) <= ? ORDER BY created_at DESC");
        $stmt->bind_param("ss", $start_date, $end_date);
        $stmt->execute();
        $result = $stmt->get_result();
        $highlights = [];
        while ($row = $result->fetch_assoc()) {
            $highlights[] = $row;
        }
        $stmt->close();
        return $highlights;
    }
    // Check if a highlight name exists for another id
    public function highlightExistsForOtherId($name, $excludeId) {
        $stmt = $this->conn->prepare("SELECT id FROM property_highlights WHERE name = ? AND id != ? LIMIT 1");
        $stmt->bind_param("si", $name, $excludeId);
        $stmt->execute();
        $stmt->store_result();
        $exists = $stmt->num_rows > 0;
        $stmt->close();
        return $exists;
    }
    // Update a highlight by id
    public function updateHighlight($id, $name) {
        $stmt = $this->conn->prepare("UPDATE property_highlights SET name = ? WHERE id = ?");
        $stmt->bind_param("si", $name, $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    // Delete a highlight by id
    public function deleteHighlight($id) {
        $stmt = $this->conn->prepare("DELETE FROM property_highlights WHERE id = ?");
        $stmt->bind_param("i", $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
    // Check if a highlight already exists by name
    public function highlightExists($name) {
        $stmt = $this->conn->prepare("SELECT id FROM property_highlights WHERE name = ? LIMIT 1");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $stmt->store_result();
        $exists = $stmt->num_rows > 0;
        $stmt->close();
        return $exists;
    }
    private $conn;
    public function __construct() {
        require_once __DIR__ . '/../config/database.php';
        $this->conn = getDatabaseConnection();
    }
    // Add new property highlight
    public function addPropertyHighlight($name) {
        $stmt = $this->conn->prepare("INSERT INTO property_highlights (name) VALUES (?)");
        $stmt->bind_param("s", $name);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
    // Get all property highlights
    public function getPropertyHighlights() {
        $result = $this->conn->query("SELECT * FROM property_highlights ORDER BY id DESC");
        $highlights = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $highlights[] = $row;
            }
            $result->free();
        }
        return $highlights;
    }
}
