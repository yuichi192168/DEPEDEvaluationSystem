<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

require_once(__DIR__ . '/../classes/DBConnection.php');

function isRomanNumeral($token) {
    return preg_match('/^[IVXLCDM]+$/i', $token) === 1;
}

function normalizeToken($token) {
    $token = trim($token);
    if ($token === '') {
        return '';
    }

    $token = preg_replace('/[^A-Za-z0-9]/', '', $token);
    return strtoupper($token);
}

function buildPositionGroupInitials($group) {
    $group = strtoupper(trim((string)$group));
    if ($group === '') {
        return '';
    }

    $map = [
        'NON-TEACHING LEVEL I' => 'NT',
        'NON-TEACHING LEVEL II' => 'NT',
        'SCHOOL ADMINISTRATION POSITION' => 'SA',
        'RELATED TEACHING POSITION' => 'RT',
        'TEACHING POSITIONS' => 'TP',
        'HIGHER TEACHING POSITIONS' => 'HT'
    ];

    if (isset($map[$group])) {
        return $map[$group];
    }

    $tokens = preg_split('/[\s\-\/]+/', $group);
    $skip = ['POSITION', 'POSITIONS', 'LEVEL'];
    $initials = '';

    foreach ($tokens as $token) {
        $token = normalizeToken($token);
        if ($token === '' || in_array($token, $skip, true) || isRomanNumeral($token)) {
            continue;
        }
        $initials .= substr($token, 0, 1);
    }

    return $initials !== '' ? $initials : 'PG';
}

function buildPositionTitleInitials($title) {
    $title = strtoupper(trim((string)$title));
    if ($title === '') {
        return '';
    }

    $tokens = preg_split('/[\s\-\/]+/', $title);
    $initials = '';

    foreach ($tokens as $token) {
        $token = normalizeToken($token);
        if ($token === '') {
            continue;
        }

        if (isRomanNumeral($token)) {
            $initials .= $token;
        } else {
            $initials .= substr($token, 0, 1);
        }
    }

    return $initials !== '' ? $initials : 'POS';
}

function nextApplicationCode($conn, $group, $title, $year) {
    $groupCode = buildPositionGroupInitials($group);
    $titleCode = buildPositionTitleInitials($title);
    $year = preg_replace('/[^0-9]/', '', (string)$year);
    if ($year === '') {
        $year = date('Y');
    }

    if ($groupCode === '' || $titleCode === '') {
        return null;
    }

    $prefix = $groupCode . '-' . $titleCode . '-' . $year . '-';
    $like = $prefix . '%';
    $stmt = $conn->prepare("SELECT application_code FROM comparative_assessment_results WHERE application_code LIKE ?");
    $stmt->bind_param('s', $like);
    $stmt->execute();
    $res = $stmt->get_result();

    $maxSeq = 0;
    while ($row = $res->fetch_assoc()) {
        $code = (string)($row['application_code'] ?? '');
        if (preg_match('/^' . preg_quote($prefix, '/') . '(\d{3,})$/', $code, $m)) {
            $seq = intval($m[1]);
            if ($seq > $maxSeq) {
                $maxSeq = $seq;
            }
        }
    }
    $stmt->close();

    $nextSeq = str_pad((string)($maxSeq + 1), 3, '0', STR_PAD_LEFT);
    return [
        'application_code' => $prefix . $nextSeq,
        'group_code' => $groupCode,
        'title_code' => $titleCode,
        'year' => $year,
        'next_sequence' => $nextSeq
    ];
}

$group = trim($_REQUEST['position_group_name'] ?? $_REQUEST['position_group'] ?? '');
$title = trim($_REQUEST['position_title'] ?? $_REQUEST['position_applied'] ?? '');
$year = trim($_REQUEST['year'] ?? date('Y'));

if ($group === '' || $title === '') {
    echo json_encode([
        'success' => false,
        'message' => 'position_group_name and position_title are required'
    ]);
    exit;
}

$conn = DBConnection::getConnection();
$generated = nextApplicationCode($conn, $group, $title, $year);

if (!$generated) {
    echo json_encode([
        'success' => false,
        'message' => 'Unable to generate application code'
    ]);
    exit;
}

echo json_encode(array_merge(['success' => true], $generated));

?>
