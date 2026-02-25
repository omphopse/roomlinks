<?php
class City {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Get cities by date range
    public function getCitiesByDate($start_date, $end_date) {
        $stmt = $this->conn->prepare("SELECT * FROM city WHERE DATE(created_at) >= ? AND DATE(created_at) <= ? ORDER BY created_at DESC");
        $stmt->execute([$start_date, $end_date]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Add multiple cities
    public function addCities($cities) {
        $stmt = $this->conn->prepare("INSERT INTO city (location) VALUES (?)");
        foreach ($cities as $city) {
            $stmt->execute([$city]);
        }
        return true;
    }

    // Optionally, fetch all cities
    public function getAllCities() {
        $stmt = $this->conn->query("SELECT * FROM city");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Update a city
    public function updateCity($id, $name) {
        $stmt = $this->conn->prepare('UPDATE city SET location = ? WHERE id = ?');
        return $stmt->execute([$name, $id]);
    }

    // Delete a city
    public function deleteCity($id) {
        $stmt = $this->conn->prepare('DELETE FROM city WHERE id = ?');
        return $stmt->execute([$id]);
    }
}
