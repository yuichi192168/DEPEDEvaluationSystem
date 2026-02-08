<?php
/**
 * ApplicantManager Class
 * Manages applicants including archiving, searching, filtering, and restoration
 * DepEd HRMPSB Evaluation System
 */

class ApplicantManager {
    
    private $conn;
    private $positionGroupsSynced = false;
    
    public function __construct($mysqli_connection) {
        $this->conn = $mysqli_connection;
    }
    
    /**
     * Get all active applicants with optional search and filtering
     * @param string $search Search term for applicant name
     * @param string $positionGroup Filter by position group (A, B, C)
     * @param int $limit Limit results
     * @param int $offset Offset for pagination
     * @return array Array of applicants
     */
    public function getActiveApplicants($search = '', $positionGroup = '', $limit = 50, $offset = 0) {
        $this->ensurePositionGroupsSynced();
        $query = "SELECT a.*, p.position_name, p.position_group as actual_group
                  FROM applicants a
                  LEFT JOIN positions p ON a.position_applied_id = p.id
                  WHERE a.archive_status = 'active'";
        
        $params = [];
        $types = '';
        
        if (!empty($search)) {
            $query .= " AND a.name LIKE ?";
            $params[] = '%' . $search . '%';
            $types .= 's';
        }
        
        if (!empty($positionGroup)) {
            $query .= " AND p.position_group = ?";
            $params[] = $positionGroup;
            $types .= 's';
        }
        
        $query .= " ORDER BY a.created_at DESC LIMIT ? OFFSET ?";
        $params[] = $limit;
        $params[] = $offset;
        $types .= 'ii';
        
        return $this->executeQuery($query, $types, $params);
    }
    
    /**
     * Get all archived applicants with optional search and filtering
     * @param string $search Search term for applicant name
     * @param string $positionGroup Filter by position group
     * @param int $limit Limit results
     * @param int $offset Offset for pagination
     * @return array Array of archived applicants
     */
    public function getArchivedApplicants($search = '', $positionGroup = '', $limit = 50, $offset = 0) {
        $this->ensurePositionGroupsSynced();
        $query = "SELECT a.*, p.position_name, p.position_group as actual_group
                  FROM applicants a
                  LEFT JOIN positions p ON a.position_applied_id = p.id
                  WHERE a.archive_status = 'archived'";
        
        $params = [];
        $types = '';
        
        if (!empty($search)) {
            $query .= " AND a.name LIKE ?";
            $params[] = '%' . $search . '%';
            $types .= 's';
        }
        
        if (!empty($positionGroup)) {
            $query .= " AND p.position_group = ?";
            $params[] = $positionGroup;
            $types .= 's';
        }
        
        $query .= " ORDER BY a.archived_at DESC LIMIT ? OFFSET ?";
        $params[] = $limit;
        $params[] = $offset;
        $types .= 'ii';
        
        return $this->executeQuery($query, $types, $params);
    }
    
    /**
     * Get count of active applicants
     * @param string $search Optional search term
     * @param string $positionGroup Optional position group filter
     * @return int Count of active applicants
     */
    public function getActiveApplicantsCount($search = '', $positionGroup = '') {
        $this->ensurePositionGroupsSynced();
        $query = "SELECT COUNT(*) as count FROM applicants a LEFT JOIN positions p ON a.position_applied_id = p.id WHERE a.archive_status = 'active'";
        
        $params = [];
        $types = '';
        
        if (!empty($search)) {
            $query .= " AND a.name LIKE ?";
            $params[] = '%' . $search . '%';
            $types .= 's';
        }
        
        if (!empty($positionGroup)) {
            $query .= " AND p.position_group = ?";
            $params[] = $positionGroup;
            $types .= 's';
        }
        
        $result = $this->executeQuery($query, $types, $params);
        return isset($result[0]['count']) ? (int)$result[0]['count'] : 0;
    }
    
    /**
     * Get count of archived applicants
     * @param string $search Optional search term
     * @param string $positionGroup Optional position group filter
     * @return int Count of archived applicants
     */
    public function getArchivedApplicantsCount($search = '', $positionGroup = '') {
        $this->ensurePositionGroupsSynced();
        $query = "SELECT COUNT(*) as count FROM applicants a LEFT JOIN positions p ON a.position_applied_id = p.id WHERE a.archive_status = 'archived'";
        
        $params = [];
        $types = '';
        
        if (!empty($search)) {
            $query .= " AND a.name LIKE ?";
            $params[] = '%' . $search . '%';
            $types .= 's';
        }
        
        if (!empty($positionGroup)) {
            $query .= " AND p.position_group = ?";
            $params[] = $positionGroup;
            $types .= 's';
        }
        
        $result = $this->executeQuery($query, $types, $params);
        return isset($result[0]['count']) ? (int)$result[0]['count'] : 0;
    }
    
    /**
     * Archive an applicant
     * @param int $applicantId ID of applicant to archive
     * @param string $reason Reason for archiving
     * @param string $archivedBy Name of who archived the applicant
     * @return array Result with success status and message
     */
    public function archiveApplicant($applicantId, $reason = '', $archivedBy = 'System') {
        $applicantId = intval($applicantId);
        
        // Get applicant info first
        $query = "SELECT id, name FROM applicants WHERE id = ?";
        $applicant = $this->executeQuery($query, 'i', [$applicantId]);
        
        if (empty($applicant)) {
            return ['success' => false, 'message' => 'Applicant not found'];
        }
        
        $applicantName = $applicant[0]['name'];
        
        // Archive the applicant
        $query = "UPDATE applicants SET archive_status = 'archived', archived_at = NOW(), archive_reason = ? 
                  WHERE id = ?";
        
        if (!$this->executeUpdate($query, 'si', [$reason, $applicantId])) {
            return ['success' => false, 'message' => 'Failed to archive applicant'];
        }
        
        // Log the archiving action
        $logQuery = "INSERT INTO archived_applicants_audit (applicant_id, applicant_name, action, reason, archived_by) 
                    VALUES (?, ?, 'archived', ?, ?)";
        $this->executeUpdate($logQuery, 'isss', [$applicantId, $applicantName, $reason, $archivedBy]);
        
        return ['success' => true, 'message' => 'Applicant archived successfully'];
    }
    
    /**
     * Restore an archived applicant
     * @param int $applicantId ID of applicant to restore
     * @param string $restoredBy Name of who restored the applicant
     * @return array Result with success status and message
     */
    public function restoreApplicant($applicantId, $restoredBy = 'System') {
        $applicantId = intval($applicantId);
        
        // Get applicant info first
        $query = "SELECT id, name FROM applicants WHERE id = ?";
        $applicant = $this->executeQuery($query, 'i', [$applicantId]);
        
        if (empty($applicant)) {
            return ['success' => false, 'message' => 'Applicant not found'];
        }
        
        $applicantName = $applicant[0]['name'];
        
        // Restore the applicant
        $query = "UPDATE applicants SET archive_status = 'active', archived_at = NULL, archive_reason = NULL 
                  WHERE id = ?";
        
        if (!$this->executeUpdate($query, 'i', [$applicantId])) {
            return ['success' => false, 'message' => 'Failed to restore applicant'];
        }
        
        // Log the restoration action
        $logQuery = "INSERT INTO archived_applicants_audit (applicant_id, applicant_name, action, archived_by) 
                    VALUES (?, ?, 'restored', ?)";
        $this->executeUpdate($logQuery, 'iss', [$applicantId, $applicantName, $restoredBy]);
        
        return ['success' => true, 'message' => 'Applicant restored successfully'];
    }
    
    /**
     * Bulk archive applicants
     * @param array $applicantIds Array of applicant IDs
     * @param string $reason Reason for archiving
     * @param string $archivedBy Name of who archived
     * @return array Result with success count and failed count
     */
    public function bulkArchive($applicantIds, $reason = '', $archivedBy = 'System') {
        $successCount = 0;
        $failCount = 0;
        
        foreach ($applicantIds as $id) {
            $result = $this->archiveApplicant($id, $reason, $archivedBy);
            if ($result['success']) {
                $successCount++;
            } else {
                $failCount++;
            }
        }
        
        return [
            'success' => true,
            'message' => "Archived $successCount applicant(s). Failed: $failCount",
            'archived' => $successCount,
            'failed' => $failCount
        ];
    }
    
    /**
     * Get applicant details
     * @param int $applicantId ID of applicant
     * @return array Applicant details
     */
    public function getApplicantDetails($applicantId) {
        $this->ensurePositionGroupsSynced();
        $query = "SELECT a.*, p.position_name 
                  FROM applicants a
                  LEFT JOIN positions p ON a.position_applied_id = p.id
                  WHERE a.id = ?";
        
        $result = $this->executeQuery($query, 'i', [$applicantId]);
        return !empty($result) ? $result[0] : null;
    }
    
    /**
     * Get archiving history for an applicant
     * @param int $applicantId ID of applicant
     * @return array History records
     */
    public function getArchiveHistory($applicantId) {
        $query = "SELECT * FROM archived_applicants_audit 
                  WHERE applicant_id = ? 
                  ORDER BY archived_at DESC";
        
        return $this->executeQuery($query, 'i', [$applicantId]);
    }
    
    /**
     * Search applicants across active and archived
     * @param string $search Search term
     * @param string $status 'active', 'archived', or 'all'
     * @param int $limit Results limit
     * @return array Search results
     */
    public function searchApplicants($search, $status = 'all', $limit = 100) {
        $this->ensurePositionGroupsSynced();
        $query = "SELECT a.*, p.position_name FROM applicants a
                  LEFT JOIN positions p ON a.position_applied_id = p.id
                  WHERE a.name LIKE ?";
        
        $params = ['%' . $search . '%'];
        $types = 's';
        
        if ($status !== 'all') {
            $query .= " AND a.archive_status = ?";
            $params[] = $status;
            $types .= 's';
        }
        
        $query .= " ORDER BY a.name LIMIT ?";
        $params[] = $limit;
        $types .= 'i';
        
        return $this->executeQuery($query, $types, $params);
    }
    
    /**
     * Get statistics about applicants
     * @return array Statistics
     */
    public function getStatistics() {
        $this->ensurePositionGroupsSynced();
        $stats = [];
        
        // Total active applicants
        $result = $this->executeQuery("SELECT COUNT(*) as count FROM applicants WHERE archive_status = 'active'", '', []);
        $stats['active_total'] = isset($result[0]['count']) ? (int)$result[0]['count'] : 0;
        
        // Total archived applicants
        $result = $this->executeQuery("SELECT COUNT(*) as count FROM applicants WHERE archive_status = 'archived'", '', []);
        $stats['archived_total'] = isset($result[0]['count']) ? (int)$result[0]['count'] : 0;
        
        // Total all applicants
        $stats['total'] = $stats['active_total'] + $stats['archived_total'];
        
        // Active by position group
        $result = $this->executeQuery(
            "SELECT position_group, COUNT(*) as count FROM applicants WHERE archive_status = 'active' GROUP BY position_group",
            '',
            []
        );
        $stats['active_by_group'] = [];
        foreach ($result as $row) {
            $stats['active_by_group'][$row['position_group']] = (int)$row['count'];
        }
        
        return $stats;
    }
    
    /**
     * Execute query with prepared statements
     * @param string $query SQL query
     * @param string $types Parameter types
     * @param array $params Parameter values
     * @return array Query results
     */
    private function executeQuery($query, $types, $params) {
        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            error_log("Query prepare error: " . $this->conn->error);
            return [];
        }
        
        if (!empty($types) && !empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        
        if (!$stmt->execute()) {
            error_log("Query execute error: " . $stmt->error);
            $stmt->close();
            return [];
        }
        
        $result = $stmt->get_result();
        $rows = [];
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        $stmt->close();
        
        return $rows;
    }
    
    /**
     * Execute update query
     * @param string $query SQL query
     * @param string $types Parameter types
     * @param array $params Parameter values
     * @return bool Success status
     */
    private function executeUpdate($query, $types, $params) {
        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            error_log("Update prepare error: " . $this->conn->error);
            return false;
        }
        
        if (!empty($types) && !empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        
        if (!$stmt->execute()) {
            error_log("Update execute error: " . $stmt->error);
            $stmt->close();
            return false;
        }
        
        $stmt->close();
        return true;
    }

    private function normalizeGroupName($group) {
        $normalized = strtoupper(trim((string)$group));
        if ($normalized === '') {
            return '';
        }

        $map = [
            'TEACHING POSITIONS' => 'TEACHING',
            'HIGHER TEACHING POSITIONS' => 'HIGHER TEACHING',
            'RELATED TEACHING POSITION' => 'RELATED TEACHING',
            'SCHOOL ADMINISTRATION POSITION' => 'SCHOOL ADMINISTRATION'
        ];

        return $map[$normalized] ?? $normalized;
    }

    private function normalizePositionName($name) {
        $normalized = strtolower(trim((string)$name));
        if ($normalized === '') {
            return '';
        }

        $normalized = preg_replace('/[^a-z0-9\s]+/', '', $normalized);
        $normalized = preg_replace('/\s+/', ' ', $normalized);
        return $normalized;
    }

    private function ensurePositionGroupsSynced() {
        if ($this->positionGroupsSynced) {
            return;
        }

        $this->positionGroupsSynced = true;

        if (!function_exists('getAllPositions')) {
            require_once __DIR__ . '/../config/baseline_library.php';
        }

        $baselinePositions = getAllPositions();
        if (!is_array($baselinePositions)) {
            require_once __DIR__ . '/../config/baseline_library.php';
            $baselinePositions = getAllPositions();
        }

        if (!is_array($baselinePositions)) {
            return;
        }
        $nameToGroup = [];

        foreach ($baselinePositions as $position) {
            $name = $this->normalizePositionName($position['position_name'] ?? '');
            $group = $this->normalizeGroupName($position['position_group'] ?? '');
            if ($name !== '' && $group !== '') {
                $nameToGroup[$name] = $group;
            }
        }

        if (empty($nameToGroup)) {
            return;
        }

        $rows = $this->executeQuery("SELECT id, position_name, position_group FROM positions", '', []);
        $updates = [];

        foreach ($rows as $row) {
            $nameKey = $this->normalizePositionName($row['position_name'] ?? '');
            if ($nameKey === '' || !isset($nameToGroup[$nameKey])) {
                continue;
            }

            $desiredGroup = $nameToGroup[$nameKey];
            if ($desiredGroup !== '' && $desiredGroup !== $row['position_group']) {
                $updates[(int)$row['id']] = $desiredGroup;
            }
        }

        foreach ($updates as $positionId => $desiredGroup) {
            $this->executeUpdate("UPDATE positions SET position_group = ? WHERE id = ?", 'si', [$desiredGroup, $positionId]);
            $this->executeUpdate("UPDATE applicants SET position_group = ? WHERE position_applied_id = ?", 'si', [$desiredGroup, $positionId]);
        }
    }
}
?>
