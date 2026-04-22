<?php
/**
 * Shared report filtering and data access utilities for CAR/IES views and exports.
 */

require_once __DIR__ . '/../classes/DBConnection.php';

if (!function_exists('normalizeReportFilters')) {
    function normalizeReportFilters(array $input): array {
        $fromDateRaw = isset($input['from_date']) ? trim((string)$input['from_date']) : '';
        $toDateRaw = isset($input['to_date']) ? trim((string)$input['to_date']) : '';
        $monthRaw = isset($input['filter_month']) ? trim((string)$input['filter_month']) : '';
        $yearRaw = isset($input['filter_year']) ? trim((string)$input['filter_year']) : '';

        $fromDate = null;
        $toDate = null;
        $month = null;
        $year = null;

        if ($fromDateRaw !== '' && preg_match('/^\d{4}-\d{2}-\d{2}$/', $fromDateRaw)) {
            $fromDate = $fromDateRaw;
        }
        if ($toDateRaw !== '' && preg_match('/^\d{4}-\d{2}-\d{2}$/', $toDateRaw)) {
            $toDate = $toDateRaw;
        }

        if ($monthRaw !== '' && ctype_digit($monthRaw)) {
            $monthInt = (int)$monthRaw;
            if ($monthInt >= 1 && $monthInt <= 12) {
                $month = $monthInt;
            }
        }

        if ($yearRaw !== '' && ctype_digit($yearRaw)) {
            $yearInt = (int)$yearRaw;
            if ($yearInt >= 2000 && $yearInt <= 2100) {
                $year = $yearInt;
            }
        }

        if ($fromDate !== null && $toDate !== null && strcmp($fromDate, $toDate) > 0) {
            $tmp = $fromDate;
            $fromDate = $toDate;
            $toDate = $tmp;
        }

        return [
            'from_date' => $fromDate,
            'to_date' => $toDate,
            'filter_month' => $month,
            'filter_year' => $year
        ];
    }
}

if (!function_exists('buildDateFilterClause')) {
    function buildDateFilterClause(string $dateColumn, array $filters, array &$params, string &$types): string {
        $clauses = [];

        if (!empty($filters['from_date'])) {
            $clauses[] = "DATE($dateColumn) >= ?";
            $params[] = $filters['from_date'];
            $types .= 's';
        }

        if (!empty($filters['to_date'])) {
            $clauses[] = "DATE($dateColumn) <= ?";
            $params[] = $filters['to_date'];
            $types .= 's';
        }

        if (!empty($filters['filter_month'])) {
            $clauses[] = "MONTH($dateColumn) = ?";
            $params[] = (int)$filters['filter_month'];
            $types .= 'i';
        }

        if (!empty($filters['filter_year'])) {
            $clauses[] = "YEAR($dateColumn) = ?";
            $params[] = (int)$filters['filter_year'];
            $types .= 'i';
        }

        if (empty($clauses)) {
            return '';
        }

        return ' AND ' . implode(' AND ', $clauses);
    }
}

if (!function_exists('bindDynamicParams')) {
    function bindDynamicParams(mysqli_stmt $stmt, string $types, array $params): void {
        if ($types === '' || empty($params)) {
            return;
        }

        $bindParams = [$types];
        foreach ($params as $idx => $value) {
            $bindParams[] = &$params[$idx];
        }

        call_user_func_array([$stmt, 'bind_param'], $bindParams);
    }
}

if (!function_exists('fetchAllAssocFromPrepared')) {
    function fetchAllAssocFromPrepared(mysqli $conn, string $sql, string $types = '', array $params = []): array {
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            error_log('Prepare failed in fetchAllAssocFromPrepared: ' . $conn->error);
            return [];
        }

        bindDynamicParams($stmt, $types, $params);

        if (!$stmt->execute()) {
            error_log('Execute failed in fetchAllAssocFromPrepared: ' . $stmt->error);
            $stmt->close();
            return [];
        }

        $result = $stmt->get_result();
        $rows = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
        }

        $stmt->close();
        return $rows;
    }
}

if (!function_exists('fetchFilteredPositionsWithResults')) {
    function fetchFilteredPositionsWithResults(mysqli $conn, array $filters): array {
        $types = '';
        $params = [];
        $dateClause = buildDateFilterClause('car.assessment_date', $filters, $params, $types);

        $sql = "SELECT
                    p.id,
                    p.position_name,
                    p.position_group,
                    p.salary_grade,
                    p.item_number,
                    COUNT(car.id) AS result_count
                FROM positions p
                INNER JOIN comparative_assessment_results car ON p.id = car.position_id
                INNER JOIN applicants a ON car.applicant_id = a.id
                WHERE a.archive_status = 'active'" . $dateClause . "
                GROUP BY p.id, p.position_name, p.position_group, p.salary_grade, p.item_number
                HAVING result_count > 0
                ORDER BY p.position_name";

        return fetchAllAssocFromPrepared($conn, $sql, $types, $params);
    }
}

if (!function_exists('fetchFilteredAllResults')) {
    function fetchFilteredAllResults(mysqli $conn, array $filters): array {
        $types = '';
        $params = [];
        $dateClause = buildDateFilterClause('car.assessment_date', $filters, $params, $types);

        $sql = "SELECT
                    car.id,
                    car.position_id,
                    car.applicant_id,
                    car.application_code,
                    car.education_score,
                    car.training_score,
                    car.experience_score,
                    car.performance_score,
                    car.outstanding_accomplishments_score,
                    car.application_of_education_score,
                    car.application_of_ld_score,
                    car.potential_score,
                    car.total_score,
                    car.rank,
                    car.remarks,
                    car.background_yes,
                    car.background_no,
                    car.for_appointment,
                    car.for_probation,
                    car.assessment_date,
                    a.name,
                    p.position_name,
                    p.position_group,
                    p.salary_grade,
                    p.item_number
                FROM comparative_assessment_results car
                INNER JOIN applicants a ON car.applicant_id = a.id
                INNER JOIN positions p ON car.position_id = p.id
                WHERE a.archive_status = 'active'" . $dateClause . "
                ORDER BY
                    p.position_name,
                    car.total_score DESC,
                    car.education_score DESC,
                    car.training_score DESC,
                    car.experience_score DESC,
                    car.performance_score DESC,
                    car.outstanding_accomplishments_score DESC,
                    car.application_of_education_score DESC,
                    car.application_of_ld_score DESC,
                    car.application_code ASC";

        return fetchAllAssocFromPrepared($conn, $sql, $types, $params);
    }
}

if (!function_exists('fetchFilteredResultsByPosition')) {
    function fetchFilteredResultsByPosition(mysqli $conn, int $positionId, array $filters): array {
        $types = 'i';
        $params = [$positionId];
        $dateClause = buildDateFilterClause('car.assessment_date', $filters, $params, $types);

        $sql = "SELECT
                    car.id,
                    car.applicant_id,
                    car.application_code,
                    car.education_score,
                    car.training_score,
                    car.experience_score,
                    car.performance_score,
                    car.outstanding_accomplishments_score,
                    car.application_of_education_score,
                    car.application_of_ld_score,
                    car.potential_score,
                    car.total_score,
                    car.rank,
                    car.remarks,
                    car.background_yes,
                    car.background_no,
                    car.for_appointment,
                    car.for_probation,
                    car.assessment_date,
                    a.name,
                    p.position_name,
                    p.position_group,
                    p.salary_grade,
                    p.item_number
                FROM comparative_assessment_results car
                INNER JOIN applicants a ON car.applicant_id = a.id
                INNER JOIN positions p ON car.position_id = p.id
                WHERE car.position_id = ?
                  AND a.archive_status = 'active'" . $dateClause . "
                ORDER BY
                    car.total_score DESC,
                    car.education_score DESC,
                    car.training_score DESC,
                    car.experience_score DESC,
                    car.performance_score DESC,
                    car.outstanding_accomplishments_score DESC,
                    car.application_of_education_score DESC,
                    car.application_of_ld_score DESC,
                    car.application_code ASC";

        return fetchAllAssocFromPrepared($conn, $sql, $types, $params);
    }
}

if (!function_exists('fetchFilteredIESRows')) {
    function fetchFilteredIESRows(mysqli $conn, array $filters): array {
        $types = '';
        $params = [];
        $dateClause = buildDateFilterClause('e.evaluation_date', $filters, $params, $types);

        $sql = "SELECT
                    a.id AS applicant_id,
                    a.name,
                    COALESCE(p.position_name, e.position_group) AS position_name,
                    e.id AS evaluation_id,
                    e.total_score,
                    e.evaluation_date,
                    ed.criterion,
                    ed.applicant_qualification,
                    ed.applicant_level,
                    ed.baseline_qualification,
                    ed.baseline_level,
                    ed.weight,
                    ed.increment,
                    ed.final_score
                FROM applicants a
                LEFT JOIN evaluations e ON a.id = e.applicant_id
                LEFT JOIN positions p ON e.position_id = p.id
                LEFT JOIN evaluation_details ed ON e.id = ed.evaluation_id
                WHERE e.id IS NOT NULL
                  AND a.archive_status = 'active'" . $dateClause . "
                ORDER BY a.name, e.evaluation_date DESC";

        return fetchAllAssocFromPrepared($conn, $sql, $types, $params);
    }
}

if (!function_exists('buildActiveFilterLabel')) {
    function buildActiveFilterLabel(array $filters): string {
        $parts = [];

        if (!empty($filters['from_date'])) {
            $parts[] = 'From ' . date('F j, Y', strtotime($filters['from_date']));
        }
        if (!empty($filters['to_date'])) {
            $parts[] = 'To ' . date('F j, Y', strtotime($filters['to_date']));
        }
        if (!empty($filters['filter_month'])) {
            $parts[] = 'Month: ' . date('F', mktime(0, 0, 0, (int)$filters['filter_month'], 1));
        }
        if (!empty($filters['filter_year'])) {
            $parts[] = 'Year: ' . (int)$filters['filter_year'];
        }

        if (empty($parts)) {
            return 'Showing latest records (no date filter applied).';
        }

        return 'Showing results for ' . implode(' | ', $parts) . '.';
    }
}

if (!function_exists('hasActiveFilters')) {
    function hasActiveFilters(array $filters): bool {
        return !empty($filters['from_date'])
            || !empty($filters['to_date'])
            || !empty($filters['filter_month'])
            || !empty($filters['filter_year']);
    }
}

if (!function_exists('buildFilterQueryString')) {
    function buildFilterQueryString(array $filters): string {
        $query = [];
        if (!empty($filters['from_date'])) {
            $query['from_date'] = $filters['from_date'];
        }
        if (!empty($filters['to_date'])) {
            $query['to_date'] = $filters['to_date'];
        }
        if (!empty($filters['filter_month'])) {
            $query['filter_month'] = (int)$filters['filter_month'];
        }
        if (!empty($filters['filter_year'])) {
            $query['filter_year'] = (int)$filters['filter_year'];
        }

        return http_build_query($query);
    }
}
