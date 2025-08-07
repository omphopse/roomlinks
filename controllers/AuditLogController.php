<?php
require_once __DIR__ . '/../models/AuditLog.php';
class AuditLogController {
    private $auditLog;
    public function __construct($pdo) {
        $this->auditLog = new AuditLog($pdo);
    }
    public function logAction($userId, $userRole, $operation, $tableName, $recordId, $details, $ipAddress) {
        $this->auditLog->log($userId, $userRole, $operation, $tableName, $recordId, $details, $ipAddress);
    }
}
