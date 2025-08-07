<?php
class Location {
    // ...existing code...
    // Get locations by date range
    public function getLocationsByDate($start_date, $end_date) {
        $stmt = $this->conn->prepare("SELECT * FROM locations WHERE DATE(created_at) >= ? AND DATE(created_at) <= ? ORDER BY created_at DESC");
        $stmt->execute([$start_date, $end_date]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    private $conn;
    public function __construct($db) {
        $this->conn = $db;
    }
    // Add multiple locations
    public function addLocations($locations) {
        $stmt = $this->conn->prepare("INSERT INTO locations (location) VALUES (?)");
        foreach ($locations as $location) {
            $stmt->execute([$location]);
        }
        return true;
    }
    // Optionally, fetch all locations
    public function getAllLocations() {
        $stmt = $this->conn->query("SELECT * FROM locations");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Update a location
    public function updateLocation($id, $name) {
        $stmt = $this->conn->prepare('UPDATE locations SET location = ? WHERE id = ?');
        return $stmt->execute([$name, $id]);
    }

    // Delete a location
    public function deleteLocation($id) {
        $stmt = $this->conn->prepare('DELETE FROM locations WHERE id = ?');
        return $stmt->execute([$id]);
    }
}
