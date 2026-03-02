<?php
/**
 * PerformanceEvaluationManager
 * Manages reclassification performance evaluation records with role-aware access.
 */
class PerformanceEvaluationManager
{
    private mysqli $conn;

    public function __construct(mysqli $mysqli_connection)
    {
        $this->conn = $mysqli_connection;
    }

    public function listEvaluations(int $userId, bool $isAdmin, string $search = ''): array
    {
        $query = "SELECT pe.id,
                         pe.name,
                         pe.current_position,
                         pe.position_applied,
                         pe.station,
                         pe.item_number,
                         pe.result,
                         pe.performance_payload,
                         pe.created_by_user_id,
                         pe.updated_at,
                         pe.created_at,
                         u.full_name AS created_by_name,
                         u.username AS created_by_username
                  FROM performance_evaluations pe
                  LEFT JOIN users u ON u.id = pe.created_by_user_id
                  WHERE 1=1";

        $params = [];
        $types = '';

        if (!$isAdmin) {
            $query .= " AND pe.created_by_user_id = ?";
            $params[] = $userId;
            $types .= 'i';
        }

        if ($search !== '') {
            $query .= " AND (
                pe.name LIKE ? OR
                pe.current_position LIKE ? OR
                pe.position_applied LIKE ? OR
                pe.station LIKE ? OR
                pe.result LIKE ?
            )";
            $searchTerm = '%' . $search . '%';
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $types .= 'sssss';
        }

        $query .= " ORDER BY pe.created_at DESC";

        return $this->executeSelect($query, $types, $params);
    }

    public function createEvaluation(array $data, ?int $userId): int
    {
        $payloadJson = json_encode($data['performance_payload'] ?? [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        $query = "INSERT INTO performance_evaluations
                  (name, current_position, position_applied, station, item_number, result, performance_payload, created_by_user_id)
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            throw new RuntimeException('Failed to prepare create statement.');
        }

        // Prepare variables for binding
        $name = $data['name'];
        $currentPos = $data['current_position'];
        $positionApplied = $data['position_applied'];
        $station = $data['station'];
        $itemNumber = $data['item_number'];
        $result = $data['result'];
        $userIdRef = $userId;  // Create reference for bind_param

        // Bind parameters with proper types (including nullable int)
        $stmt->bind_param(
            'sssssssi',
            $name,
            $currentPos,
            $positionApplied,
            $station,
            $itemNumber,
            $result,
            $payloadJson,
            $userIdRef
        );

        if (!$stmt->execute()) {
            $message = $stmt->error;
            $stmt->close();
            throw new RuntimeException('Failed to create evaluation: ' . $message);
        }

        $id = (int)$stmt->insert_id;
        $stmt->close();

        return $id;
    }

    public function updateEvaluation(int $evaluationId, array $data): bool
    {
        $payloadJson = json_encode($data['performance_payload'] ?? [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        $query = "UPDATE performance_evaluations
                  SET name = ?,
                      current_position = ?,
                      position_applied = ?,
                      station = ?,
                      item_number = ?,
                      result = ?,
                      performance_payload = ?
                  WHERE id = ?";

        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            throw new RuntimeException('Failed to prepare update statement.');
        }

        $stmt->bind_param(
            'sssssssi',
            $data['name'],
            $data['current_position'],
            $data['position_applied'],
            $data['station'],
            $data['item_number'],
            $data['result'],
            $payloadJson,
            $evaluationId
        );

        $ok = $stmt->execute();
        $stmt->close();

        return $ok;
    }

    public function deleteEvaluation(int $evaluationId): bool
    {
        $stmt = $this->conn->prepare("DELETE FROM performance_evaluations WHERE id = ?");
        if (!$stmt) {
            return false;
        }

        $stmt->bind_param('i', $evaluationId);
        $ok = $stmt->execute();
        $stmt->close();

        return $ok;
    }

    public function hasDuplicate(string $name, string $itemNumber, ?int $userId, bool $isAdmin, ?int $excludeId = null): bool
    {
        $query = "SELECT id
                  FROM performance_evaluations
                  WHERE LOWER(TRIM(name)) = LOWER(TRIM(?))
                    AND LOWER(TRIM(item_number)) = LOWER(TRIM(?))";

        $params = [$name, $itemNumber];
        $types = 'ss';

        if (!$isAdmin) {
            // Filter by user ID if authenticated, otherwise only guest records
            if ($userId !== null && $userId > 0) {
                $query .= " AND created_by_user_id = ?";
                $params[] = $userId;
                $types .= 'i';
            } else {
                // For guests, only check guest records (created_by_user_id IS NULL)
                $query .= " AND created_by_user_id IS NULL";
            }
        }

        if ($excludeId !== null) {
            $query .= " AND id <> ?";
            $params[] = $excludeId;
            $types .= 'i';
        }

        $query .= " LIMIT 1";

        $rows = $this->executeSelect($query, $types, $params);
        return !empty($rows);
    }

    private function executeSelect(string $query, string $types, array $params): array
    {
        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            return [];
        }

        if ($types !== '') {
            $stmt->bind_param($types, ...$params);
        }

        if (!$stmt->execute()) {
            $stmt->close();
            return [];
        }

        $result = $stmt->get_result();
        $rows = [];

        if ($result) {
            while ($row = $result->fetch_assoc()) {
                if (isset($row['performance_payload']) && is_string($row['performance_payload'])) {
                    $decoded = json_decode($row['performance_payload'], true);
                    $row['performance_payload'] = is_array($decoded) ? $decoded : [];
                }
                $rows[] = $row;
            }
        }

        $stmt->close();
        return $rows;
    }
}
