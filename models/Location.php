<?php
require_once __DIR__ . '/../config/database.php';

class Location
{
    private $pdo;

    public function __construct($pdo = null)
    {
        $this->pdo = $pdo ?: getPDO();
    }

    public function addLocation($city_id, $name)
    {
        $stmt = $this->pdo->prepare('INSERT INTO locations (city_id, name, created_at, updated_at) VALUES (?, ?, NOW(), NOW())');
        return $stmt->execute([$city_id, $name]);
    }

    public function getAllLocations($city_id = null, $start_date = null, $end_date = null)
    {
        $query = 'SELECT l.*, c.location AS city_name FROM locations l LEFT JOIN city c ON l.city_id = c.id';
        $conditions = [];
        $params = [];
        if ($city_id) {
            $conditions[] = 'l.city_id = ?';
            $params[] = $city_id;
        }
        if ($start_date) {
            $conditions[] = 'DATE(l.created_at) >= ?';
            $params[] = $start_date;
        }
        if ($end_date) {
            $conditions[] = 'DATE(l.created_at) <= ?';
            $params[] = $end_date;
        }
        if (!empty($conditions)) {
            $query .= ' WHERE ' . implode(' AND ', $conditions);
        }
        $query .= ' ORDER BY c.location, l.name';
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getLocationById($id)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM locations WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateLocation($id, $city_id, $name)
    {
        $stmt = $this->pdo->prepare('UPDATE locations SET city_id = ?, name = ?, updated_at = NOW() WHERE id = ?');
        return $stmt->execute([$city_id, $name, $id]);
    }

    public function deleteLocation($id)
    {
        $stmt = $this->pdo->prepare('DELETE FROM locations WHERE id = ?');
        return $stmt->execute([$id]);
    }
}
