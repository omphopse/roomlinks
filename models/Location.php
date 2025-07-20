    
<?php
class Location {
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
