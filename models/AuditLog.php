<?php
class AuditLog {
    private $pdo;
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    public function log($userId, $userRole, $operation, $tableName, $recordId, $details, $ipAddress) {
        $stmt = $this->pdo->prepare('INSERT INTO audit_logs (user_id, user_role, operation, table_name, record_id, details, ip_address) VALUES (?, ?, ?, ?, ?, ?, ?)');
        $stmt->execute([
            $userId,
            $userRole,
            $operation,
            $tableName,
            $recordId,
            $details,
            $ipAddress
        ]);
    }
}
