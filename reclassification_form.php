<?php
// DBM-DepEd JC 01 s.2025 Reclassification Form (Teaching Positions)
// Plain PHP, single-file implementation for easy integration.

session_start();

require_once(__DIR__ . '/classes/DBConnection.php');
require_once(__DIR__ . '/classes/AuthenticationHelper.php');
require_once(__DIR__ . '/classes/PerformanceEvaluationManager.php');

$authConn = DBConnection::getConnection();
$auth = new AuthenticationHelper($authConn);
$currentUser = $auth->getCurrentUser();
$isAuthenticated = $auth->isAuthenticated();
$isAdmin = $auth->isAdmin();

$positions = [
    "Teacher II",
    "Teacher III",
    "Teacher IV",
    "Teacher V",
    "Teacher VI",
    "Teacher VII",
    "Master Teacher I",
    "Master Teacher II",
    "Master Teacher III",
];

$formType = post_value("form_type", "form1");
$form1Positions = [
    "Teacher II",
    "Teacher III",
    "Teacher IV",
    "Teacher V",
    "Teacher VI",
    "Teacher VII",
    "Master Teacher I",
];
$form2Positions = [
    "Master Teacher II",
    "Master Teacher III",
];
$form1Order = $form1Positions;
$form2Order = [
    "Master Teacher I",
    "Master Teacher II",
    "Master Teacher III",
];
$currentPositionOptions = $formType === "form2" ? $form2Order : $form1Order;
$appliedPositionOptions = $formType === "form2" ? $form2Positions : $form1Positions;

// Baseline Qualification Standards (QS) by position.
$qsByPosition = [
    "Teacher II" => [
        "education" => "Bachelor's degree in Education; or Bachelor's degree in relevant subject or learning area with at least 18 professional units in Education",
        "training" => "8 hours of training in any of or a cumulative of the following: Curriculum, Pedagogy, Subject Specialization acquired within the last 5 years",
        "experience" => "1 year teaching experience",
        "eligibility" => "Elementary: RA 1080 (Teacher-Elementary/Secondary). Secondary: RA 1080 (Teacher-Secondary).",
        "competency" => "",
    ],
    "Teacher III" => [
        "education" => "Bachelor's degree in Education; or Bachelor's degree in relevant subject or learning area with at least 18 professional units in Education",
        "training" => "16 hours of training in any of or a cumulative of the following: Curriculum, Pedagogy, Subject Specialization acquired within the last 5 years",
        "experience" => "2 years teaching experience",
        "eligibility" => "Elementary: RA 1080 (Teacher-Elementary/Secondary). Secondary: RA 1080 (Teacher-Secondary).",
        "competency" => "",
    ],
    "Teacher IV" => [
        "education" => "Bachelor's degree in Education; or Bachelor's degree in relevant subject or learning area with at least 18 professional units in Education",
        "training" => "16 hours of training in Curriculum, Pedagogy, Subject Specialization within the last 5 years; or completion of NEAP-requisite professional development program for Career Stage II",
        "experience" => "3 years teaching experience",
        "eligibility" => "Elementary: RA 1080 (Teacher-Elementary/Secondary). Secondary: RA 1080 (Teacher-Secondary).",
        "competency" => "",
    ],
    "Teacher V" => [
        "education" => "Bachelor's degree in Education; or Bachelor's degree in relevant subject or learning area with at least 18 professional units in Education",
        "training" => "24 hours of training in Curriculum, Pedagogy, Subject Specialization within the last 5 years; or completion of NEAP-requisite professional development program",
        "experience" => "3 years teaching experience",
        "eligibility" => "Elementary: RA 1080 (Teacher-Elementary/Secondary). Secondary: RA 1080 (Teacher-Secondary).",
        "competency" => "",
    ],
    "Teacher VI" => [
        "education" => "Bachelor's degree in Education; or Bachelor's degree in relevant subject or learning area with at least 18 professional units in Education",
        "training" => "24 hours of training in Curriculum, Pedagogy, Subject Specialization, Instructional Supervision within the last 5 years; or completion of NEAP-requisite professional development program",
        "experience" => "4 years teaching experience",
        "eligibility" => "Elementary: RA 1080 (Teacher-Elementary/Secondary). Secondary: RA 1080 (Teacher-Secondary).",
        "competency" => "",
    ],
    "Teacher VII" => [
        "education" => "Bachelor's degree in Education; or Bachelor's degree in relevant subject or learning area with at least 18 professional units in Education",
        "training" => "32 hours of training in Curriculum, Pedagogy, Subject Specialization, Instructional Supervision within the last 5 years; or completion of NEAP-requisite professional development program",
        "experience" => "4 years teaching experience",
        "eligibility" => "Elementary: RA 1080 (Teacher-Elementary/Secondary). Secondary: RA 1080 (Teacher-Secondary).",
        "competency" => "",
    ],
    "Master Teacher I" => [
        "education" => "Master's degree in Education, or Educational Leadership, or Educational Management, or relevant subject or learning area",
        "training" => "24 hours of training in any of or a cumulative of the following: Curriculum, Pedagogy, Subject Specialization and 8 hours of training in Instructional Supervision acquired within the last 5 years; or completion of NEAP-requisite professional development program for Career Stage III (Highly Proficient Teacher)",
        "experience" => "5 years teaching experience",
        "eligibility" => "Elementary: RA 1080 (Teacher-Elementary/Secondary). Secondary: RA 1080 (Teacher-Secondary).",
        "competency" => "",
    ],
    "Master Teacher II" => [
        "education" => "Master's degree in Education, or Educational Leadership, or Educational Management, or relevant subject or learning area",
        "training" => "24 hours of training in any of or a cumulative of the following: Curriculum, Pedagogy, Subject Specialization and 8 hours of training in Instructional Supervision acquired within the last 5 years; or completion of NEAP-requisite professional development program for Career Stage III (Highly Proficient Teacher)",
        "experience" => "5 years teaching experience and 1 year relevant experience in instructional supervision and technical assistance to teachers",
        "eligibility" => "Elementary: RA 1080 (Teacher-Elementary/Secondary). Secondary: RA 1080 (Teacher-Secondary).",
        "competency" => "",
    ],
    "Master Teacher III" => [
        "education" => "Master's degree in Education, or Educational Leadership, or Educational Management, or relevant subject or learning area",
        "training" => "24 hours of training in any of or a cumulative of the following: Curriculum, Pedagogy, Subject Specialization and 8 hours of training in Instructional Supervision acquired within the last 5 years; or completion of NEAP-requisite professional development program for Career Stage IV (Distinguished Teacher)",
        "experience" => "5 years teaching experience and 2 years relevant experience in instructional supervision and technical assistance to teachers",
        "eligibility" => "Elementary: RA 1080 (Teacher-Elementary/Secondary). Secondary: RA 1080 (Teacher-Secondary).",
        "competency" => "",
    ],
];

// Performance requirements by position.
$performanceRules = [
    "Teacher II" => [
        "coi_vs" => 6,
        "ncoi_vs" => 4,
        "coi_o" => 0,
        "ncoi_o" => 0,
    ],
    "Teacher III" => [
        "coi_vs" => 12,
        "ncoi_vs" => 8,
        "coi_o" => 0,
        "ncoi_o" => 0,
    ],
    "Teacher IV" => [
        "coi_vs" => 21,
        "ncoi_vs" => 16,
        "coi_o" => 0,
        "ncoi_o" => 0,
    ],
    "Teacher V" => [
        "coi_vs" => 0,
        "ncoi_vs" => 0,
        "coi_o" => 6,
        "ncoi_o" => 4,
    ],
    "Teacher VI" => [
        "coi_vs" => 0,
        "ncoi_vs" => 4,
        "coi_o" => 12,
        "ncoi_o" => 4,
    ],
    "Teacher VII" => [
        "coi_vs" => 0,
        "ncoi_vs" => 6,
        "coi_o" => 18,
        "ncoi_o" => 6,
    ],
    "Master Teacher I" => [
        "coi_vs" => 0,
        "ncoi_vs" => 8,
        "coi_o" => 21,
        "ncoi_o" => 8,
    ],
    "Master Teacher II" => [
        "coi_vs" => 0,
        "ncoi_vs" => 5,
        "coi_o" => 10,
        "ncoi_o" => 5,
    ],
    "Master Teacher III" => [
        "coi_vs" => 0,
        "ncoi_vs" => 8,
        "coi_o" => 21,
        "ncoi_o" => 8,
    ],
];

$salaryGradesByPosition = [
    "Teacher II" => 12,
    "Teacher III" => 13,
    "Teacher IV" => 14,
    "Teacher V" => 15,
    "Teacher VI" => 16,
    "Teacher VII" => 17,
    "Master Teacher I" => 18,
    "Master Teacher II" => 19,
    "Master Teacher III" => 20,
];

$ppstIndicators = [
    [
        "domain" => "Domain 1. Content Knowledge and Pedagogy",
        "items" => [
            ["code" => "1.1.2", "text" => "Apply knowledge of content within and across curriculum teaching areas. (COI)"],
            ["code" => "1.2.2", "text" => "Use research-based knowledge and principles of teaching and learning to enhance professional practice. (NCOI)"],
            ["code" => "1.3.2", "text" => "Ensure the positive use of ICT to facilitate the teaching and learning process. (COI)"],
            ["code" => "1.4.2", "text" => "Use a range of teaching strategies that enhance learner achievement in literacy and numeracy skills. (COI)"],
            ["code" => "1.5.2", "text" => "Apply a range of teaching strategies to develop critical and creative thinking, as well as other higher-order thinking skills. (COI)"],
            ["code" => "1.6.2", "text" => "Display proficient use of Mother Tongue, Filipino and English to facilitate teaching and learning. (COI)"],
            ["code" => "1.7.2", "text" => "Use effective verbal and non-verbal classroom communication strategies to support learner understanding, participation, engagement and achievement. (COI)"],
        ],
    ],
    [
        "domain" => "Domain 2. Learning Environment",
        "items" => [
            ["code" => "2.1.2", "text" => "Establish safe and secure learning environments to enhance learning through consistent implementation of policies, guidelines and procedures. (COI)"],
            ["code" => "2.2.2", "text" => "Maintain learning environments that promote fairness, respect and care to encourage learning. (COI)"],
            ["code" => "2.3.2", "text" => "Manage classroom structure to engage learners, individually or in groups, in meaningful exploration, discovery and hands-on activities within a range of physical learning environments. (COI)"],
            ["code" => "2.4.2", "text" => "Maintain supportive learning environments that nurture and inspire learners to participate, cooperate and collaborate in continued learning. (COI)"],
            ["code" => "2.5.2", "text" => "Apply a range of successful strategies that maintain learning environments that motivate learners to work productively by assuming responsibility for their own learning. (COI)"],
            ["code" => "2.6.2", "text" => "Manage learner behavior constructively by applying positive and non-violent discipline to ensure learning-focused environments. (COI)"],
        ],
    ],
    [
        "domain" => "Domain 3. Diversity of Learners",
        "items" => [
            ["code" => "3.1.2", "text" => "Use differentiated, developmentally appropriate learning experiences to address learners' gender, needs, strengths, interests and experiences. (COI)"],
            ["code" => "3.2.2", "text" => "Establish a learner-centered culture by using teaching strategies that respond to learners' linguistic, cultural, socio-economic and religious backgrounds. (COI)"],
            ["code" => "3.3.2", "text" => "Design, adapt and implement teaching strategies that are responsive to learners with disabilities, giftedness and talents. (COI)"],
            ["code" => "3.4.2", "text" => "Plan and deliver teaching strategies that are responsive to the special educational needs of learners in difficult circumstances, including geographic isolation, chronic illness; displacement due to armed conflict, urban resettlement or disasters; child abuse and child labor practices. (COI)"],
            ["code" => "3.5.2", "text" => "Adapt and use culturally appropriate teaching strategies to address the needs of learners from indigenous groups. (COI)"],
        ],
    ],
    [
        "domain" => "Domain 4. Curriculum and Planning",
        "items" => [
            ["code" => "4.1.2", "text" => "Plan, manage and implement developmentally sequenced teaching and learning process to meet curriculum requirements and varied teaching contexts. (COI)"],
            ["code" => "4.2.2", "text" => "Set achievable and appropriate learning outcomes that are aligned with learning competencies. (NCOI)"],
            ["code" => "4.3.2", "text" => "Adapt and implement learning programs that ensure relevance and responsiveness to the needs of all learners. (NCOI)"],
            ["code" => "4.4.2", "text" => "Participate in collegial discussions that use teacher and learner feedback to enrich teaching practice. (NCOI)"],
            ["code" => "4.5.2", "text" => "Select, develop, organize and use appropriate teaching and learning resources, including ICT, to address learning goals. (COI)"],
             ["code" => "4.6.2", "text" => "Use a variety of strategies to manage time, activities and resources effectively in the teaching and learning process. (NCOI)"],
        ],
    ],
    [
        "domain" => "Domain 5. Assessment and Reporting",
        "items" => [
            ["code" => "5.1.2", "text" => "Design, select, organize and use diagnostic, formative, and summative assessment strategies consistent with curriculum requirements. (COI)"],
             ["code" => "5.2.2", "text" => "Monitor and evaluate learner progress and achievement using learner attainment data. (NCOI)"],
             ["code" => "5.3.2", "text" => "Use strategies for providing timely, accurate and constructive feedback to improve learner performance. (COI)"],
             ["code" => "5.4.2", "text" => "Communicate promptly and clearly the learners' needs, progress and achievement to key stakeholders, including parents/guardians. (NCOI)"],
             ["code" => "5.5.2", "text" => "Utilize assessment data to inform the modification of teaching and learning practices and programs. (NCOI)"],
            ["code" => "5.2.2", "text" => "Monitor and evaluate learner progress and achievement using learner attainment data. (NCOI)"],
            ["code" => "5.3.2", "text" => "Use strategies for providing timely, accurate and constructive feedback to improve learner performance. (COI)"],
            ["code" => "5.4.2", "text" => "Communicate promptly and clearly the learners' needs, progress and achievement to key stakeholders, including parents/guardians. (NCOI)"],
            ["code" => "5.5.2", "text" => "Utilize assessment data to inform the modification of teaching and learning practices and programs. (NCOI)"],
        ],
    ],
    [
        "domain" => "Domain 6. Community Linkages and Professional Engagement",
        "items" => [
            ["code" => "6.1.2", "text" => "Maintain learning environments that are responsive to community contexts. (NCOI)"],
            ["code" => "6.2.2", "text" => "Build relationships with parents/guardians and the wider school community to facilitate involvement in the educative process. (NCOI)"],
            ["code" => "6.3.2", "text" => "Review regularly personal teaching practice using existing laws and regulations that apply to the teaching profession and the responsibilities specified in the Code of Ethics for Professional Teachers. (NCOI)"],
            ["code" => "6.4.2", "text" => "Comply with and implement school policies and procedures consistently to foster harmonious relationships with learners, parents, and other stakeholders. (NCOI)"],
        ],
    ],
    [
        "domain" => "Domain 7. Personal Growth and Professional Development",
        "items" => [
            ["code" => "7.1.2", "text" => "Apply a personal philosophy of teaching that is learner-centered. (NCOI)"],
            ["code" => "7.2.2", "text" => "Adopt practices that uphold the dignity of teaching as a profession by exhibiting qualities such as caring attitude, respect and integrity. (NCOI)"],
            ["code" => "7.3.2", "text" => "Participate in professional networks to share knowledge and to enhance practice. (NCOI)"],
            ["code" => "7.4.2", "text" => "Develop a personal professional improvement plan based on reflection of one's practice and ongoing professional learning. (NCOI)"],
            ["code" => "7.5.2", "text" => "Set professional development goals based on the Philippine Professional Standards for Teachers. (NCOI)"],
        ],
    ],
];

// Helper to fetch POST values safely.
function post_value(string $key, $default = "")
{
    return isset($_POST[$key]) ? trim((string)$_POST[$key]) : $default;
}

function post_int(string $key, int $default = 0): int
{
    if (!isset($_POST[$key])) {
        return $default;
    }
    $value = filter_var($_POST[$key], FILTER_VALIDATE_INT);
    return ($value === false || $value < 0) ? $default : $value;
}

$currentPosition = post_value("current_position");
$positionApplied = post_value("position_applied");

$selectedQs = array_merge(
    [
        "education" => "",
        "training" => "",
        "experience" => "",
        "eligibility" => "",
        "competency" => "",
    ],
    $qsByPosition[$positionApplied] ?? []
);

// Performance inputs.
$coiVs = post_int("coi_vs");
$ncoiVs = post_int("ncoi_vs");
$coiO = post_int("coi_o");
$ncoiO = post_int("ncoi_o");
$totalVs = $coiVs + $ncoiVs;
$totalO = $coiO + $ncoiO;

$ncoiIndicators = [2, 20, 21, 22, 25, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37];
$ncoiLookup = array_fill_keys($ncoiIndicators, true);

function compute_ppst_counts(array $ppstIndicators, array $ncoiLookup): array
{
    $rowNumber = 1;
    $counts = [
        "coi_vs" => 0,
        "coi_o" => 0,
        "ncoi_vs" => 0,
        "ncoi_o" => 0,
        "total_vs" => 0,
        "total_o" => 0,
    ];

    foreach ($ppstIndicators as $domain) {
        foreach ($domain["items"] as $_item) {
            $isNcoi = isset($ncoiLookup[$rowNumber]);
            if (post_value("ppst_o_" . $rowNumber) !== "") {
                if ($isNcoi) {
                    $counts["ncoi_o"] += 1;
                } else {
                    $counts["coi_o"] += 1;
                }
            }
            if (post_value("ppst_vs_" . $rowNumber) !== "") {
                if ($isNcoi) {
                    $counts["ncoi_vs"] += 1;
                } else {
                    $counts["coi_vs"] += 1;
                }
            }
            $rowNumber++;
        }
    }

    $counts["total_vs"] = $counts["coi_vs"] + $counts["ncoi_vs"];
    $counts["total_o"] = $counts["coi_o"] + $counts["ncoi_o"];

    return $counts;
}

$ppstCounts = compute_ppst_counts($ppstIndicators, $ncoiLookup);

$activeOrder = $formType === "form2" ? $form2Order : $form1Order;
$positionRanks = array_flip($activeOrder);
$errorMessage = null;

$result = null;
if ($_SERVER["REQUEST_METHOD"] === "POST" && $positionApplied !== "") {
    $currentIndex = $positionRanks[$currentPosition] ?? null;
    $appliedIndex = $positionRanks[$positionApplied] ?? null;

    if ($currentIndex === null || $appliedIndex === null) {
        $errorMessage = "Please select valid positions.";
    } else {
        $stepGap = $appliedIndex - $currentIndex;
        if ($stepGap <= 0 || $stepGap > 3) {
            $errorMessage = "Position applied must be within 1 to 3 levels higher than current position.";
        }
    }
}

$actionFrom = $currentPosition;
$actionTo = $positionApplied;
$actionFromSg = $salaryGradesByPosition[$currentPosition] ?? "";
$actionToSg = $salaryGradesByPosition[$positionApplied] ?? "";
$actionRemarks = $result ? ($result["passed"] ? "QUALIFIED" : "NOT QUALIFIED") : "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && $positionApplied !== "" && $errorMessage === null) {
    $rules = $performanceRules[$positionApplied] ?? null;

    if ($rules) {
        $meetsCoiVs = $coiVs >= $rules["coi_vs"];
        $meetsNcoiVs = $ncoiVs >= $rules["ncoi_vs"];
        $meetsCoiO = $coiO >= $rules["coi_o"];
        $meetsNcoiO = $ncoiO >= $rules["ncoi_o"];

        $passed = $meetsCoiVs && $meetsNcoiVs && $meetsCoiO && $meetsNcoiO;

        $result = [
            "passed" => $passed,
            "total_vs" => $coiVs + $ncoiVs,
            "total_o" => $coiO + $ncoiO,
            "ppst" => $ppstCounts,
            "rules" => $rules,
            "checks" => [
                "coi_vs" => $meetsCoiVs,
                "ncoi_vs" => $meetsNcoiVs,
                "coi_o" => $meetsCoiO,
                "ncoi_o" => $meetsNcoiO,
            ],
        ];

        // Allow both authenticated and unauthenticated users to submit evaluations
        $recordId = post_int("evaluation_record_id", 0);
        $ppstSelections = [];
        foreach ($_POST as $key => $value) {
            if (strpos((string)$key, 'ppst_') === 0) {
                $ppstSelections[$key] = trim((string)$value) !== '' ? 1 : 0;
            }
        }

        $performancePayload = [
            "form_type" => post_value("form_type"),
            "sg_salary" => post_value("sg_salary"),
            "level" => post_value("level"),
            "app_education" => post_value("app_education"),
            "app_training" => post_value("app_training"),
            "app_experience" => post_value("app_experience"),
            "app_eligibility" => post_value("app_eligibility"),
            "app_competency" => post_value("app_competency"),
            "qs_remark_education" => post_value("qs_remark_education"),
            "qs_remark_training" => post_value("qs_remark_training"),
            "qs_remark_experience" => post_value("qs_remark_experience"),
            "qs_remark_eligibility" => post_value("qs_remark_eligibility"),
            "qs_remark_competency" => post_value("qs_remark_competency"),
            "coi_vs" => $coiVs,
            "ncoi_vs" => $ncoiVs,
            "coi_o" => $coiO,
            "ncoi_o" => $ncoiO,
            "total_vs" => $totalVs,
            "total_o" => $totalO,
            "ppst_counts" => $ppstCounts,
            "ppst_selections" => $ppstSelections,
            "action_date" => post_value("action_date"),
            "region_date" => post_value("region_date"),
        ];

        $manager = new PerformanceEvaluationManager($authConn);
        $recordData = [
            "name" => post_value("name"),
            "current_position" => $currentPosition,
            "position_applied" => $positionApplied,
            "station" => post_value("station"),
            "item_number" => post_value("item_number"),
            "result" => $passed ? "PASSED" : "FAILED",
            "performance_payload" => $performancePayload,
        ];

        // Only authenticated users can edit existing records
        if ($recordId > 0) {
            if (!$isAuthenticated || !$isAdmin) {
                $errorMessage = "Unauthorized: Only Admin can edit evaluation records.";
            } elseif ($manager->hasDuplicate($recordData["name"], $recordData["item_number"], (int)$currentUser["id"], true, $recordId)) {
                $errorMessage = "Duplicate evaluation record for the same name and item number.";
            } else {
                $manager->updateEvaluation($recordId, $recordData);
            }
        } else {
            // New submissions: check for duplicates and create record
            // Use NULL for unauthenticated users (guest submissions)
            $submittedByUserId = $isAuthenticated ? (int)$currentUser["id"] : null;
            
            if ($manager->hasDuplicate($recordData["name"], $recordData["item_number"], $submittedByUserId, $isAdmin)) {
                $errorMessage = "Duplicate evaluation record for the same name and item number.";
            } else {
                $manager->createEvaluation($recordData, $submittedByUserId);
            }
        }
    }
}

function selected(string $value, string $current): string
{
    return $value === $current ? "selected" : "";
}

function checked(string $value, string $current): string
{
    return $value === $current ? "checked" : "";
}

function format_performance_requirements(array $rules): string
{
    $parts = [];
    if (($rules["coi_vs"] ?? 0) > 0) {
        $parts[] = "At least " . $rules["coi_vs"] . " Proficient COIs at Very Satisfactory";
    }
    if (($rules["ncoi_vs"] ?? 0) > 0) {
        $parts[] = "At least " . $rules["ncoi_vs"] . " Proficient NCOIs at Very Satisfactory";
    }
    if (($rules["coi_o"] ?? 0) > 0) {
        $parts[] = "At least " . $rules["coi_o"] . " Proficient COIs at Outstanding";
    }
    if (($rules["ncoi_o"] ?? 0) > 0) {
        $parts[] = "At least " . $rules["ncoi_o"] . " Proficient NCOIs at Outstanding";
    }

    if (!$parts) {
        return "";
    }
    if (count($parts) === 1) {
        return $parts[0];
    }

    $last = array_pop($parts);
    return implode("; ", $parts) . "; and " . $last;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require_once(__DIR__ . '/includes/favicon.php'); ?>
    <title>RFTP</title>
    <style>
        body {
            font-family: "Times New Roman", serif;
            margin: 24px;
            background: #fff;
            color: #000;
        }
        h1, h2 {
            margin: 0 0 12px 0;
        }
        form {
            background: transparent;
            border: none;
            padding: 0;
            border-radius: 0;
        }
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 12px;
        }
        label {
            display: block;
            font-size: 13px;
            margin-bottom: 4px;
        }
        input[type="text"], input[type="number"], select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input[type="checkbox"],
        input[type="radio"] {
            accent-color: #000;
        }
        input[readonly] {
            background: #f3f3f3;
        }
        .section {
            margin-top: 18px;
            padding-top: 12px;
            border-top: 1px solid #eee;
        }
        .radio-row {
            display: flex;
            gap: 16px;
            flex-wrap: wrap;
            padding-top: 6px;
        }
        .actions {
            margin-top: 16px;
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 8px;
            position: sticky;
            bottom: 16px;
            margin-left: auto;
            margin-right: auto;
            width: fit-content;
            background: #fff;
            padding: 10px 16px;
            border: 1px solid #ddd;
            border-radius: 6px;
            box-shadow: 0 8px 18px rgba(0, 0, 0, 0.12);
            z-index: 5;
        }
        .button-secondary {
            border: 1px solid #bbb;
            background: #fff;
            padding: 8px 12px;
            border-radius: 4px;
            cursor: pointer;
        }
        .toolbar {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 8px;
        }
        .search-input {
            max-width: 320px;
        }
        .is-invalid {
            border-color: #c62828;
            box-shadow: 0 0 0 2px rgba(198, 40, 40, 0.15);
        }
        .validation-hint {
            color: #c62828;
            font-size: 12px;
            margin-top: 6px;
            display: none;
        }
        .validation-hint.is-visible {
            display: block;
        }
        .result {
            margin-top: 16px;
            padding: 12px;
            border-radius: 6px;
            border: 1px solid #ccc;
            background: #fafafa;
        }
        .pass {
            border-color: #2e7d32;
            background: #e8f5e9;
        }
        .fail {
            border-color: #c62828;
            background: #ffebee;
        }
        .small {
            font-size: 12px;
            color: #555;
        }
        .info-table,
        .qs-table,
        .perf-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }
        .info-table td {
            padding: 4px 6px;
            vertical-align: middle;
        }
        .label-cell {
            width: 160px;
            white-space: nowrap;
        }
        .line-input {
            width: 100%;
            border: none;
            border-bottom: 1px solid #000;
            border-radius: 0;
            padding: 2px 4px;
            background: transparent;
            box-sizing: border-box;
            display: block;
        }
        select.line-input {
            appearance: none;
        }
        .level-options {
            display: grid;
            grid-template-columns: repeat(2, minmax(180px, 1fr));
            gap: 4px 12px;
        }
        .qs-table th,
        .qs-table td,
        .perf-table th,
        .perf-table td {
            border: 1px solid #000;
            padding: 4px 6px;
            vertical-align: middle;
        }
        .qs-table th,
        .perf-table th {
            text-align: center;
            font-weight: 600;
        }
        .qs-table td:first-child {
            width: 140px;
        }
        .perf-table td:first-child {
            width: 160px;
            text-align: center;
        }
        .perf-notes {
            margin: 6px 0 8px 18px;
            font-size: 13px;
        }
        .ppst-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
            font-size: 13px;
        }
        .ppst-table th,
        .ppst-table td {
            border: 1px solid #ccc;
            padding: 6px 8px;
            vertical-align: top;
        }
        .ppst-table th {
            background: #f3f3f3;
            text-align: left;
        }
        .ppst-table .col-no {
            width: 40px;
            text-align: center;
        }
        .ppst-table .col-check {
            width: 48px;
            text-align: center;
        }
        .ppst-table .domain-row td {
            font-weight: 600;
            background: #fafafa;
        }
        .ppst-table input[type="checkbox"] {
            transform: scale(1.1);
        }
        .applicants-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
            font-size: 13px;
        }
        .applicants-table th,
        .applicants-table td {
            border: 1px solid #ccc;
            padding: 6px 8px;
            text-align: left;
        }
        .applicants-table th {
            background: #f3f3f3;
        }
        .section-title {
            font-weight: 700;
            margin: 8px 0 6px;
        }
        .assessment-table,
        .action-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }
        .assessment-table th,
        .assessment-table td,
        .action-table th,
        .action-table td {
            border: 1px solid #000;
            padding: 6px 8px;
            text-align: center;
            vertical-align: middle;
        }
        .action-table .line-input {
            text-align: center;
        }
        .assessment-table th,
        .action-table th {
            font-weight: 600;
        }
        .signature-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
            margin-top: 10px;
        }
        .signature-row.signature-single {
            grid-template-columns: 1fr;
            justify-items: center;
        }
        .signature-block {
            text-align: center;
        }
        .signature-line {
            border-bottom: 1px solid #000;
            height: 20px;
            margin: 6px 0 4px;
        }
        .banner {
            margin-bottom: 12px;
            padding: 10px 12px;
            border-radius: 6px;
            border: 1px solid transparent;
            font-size: 14px;
        }
        .banner-success {
            background: #e8f5e9;
            border-color: #2e7d32;
            color: #1b5e20;
        }
        .banner-fail {
            background: #ffebee;
            border-color: #c62828;
            color: #b71c1c;
        }
        .banner-info {
            background: #e3f2fd;
            border-color: #1565c0;
            color: #0d47a1;
        }
        .page-header {
            text-align: center;
            margin-bottom: 8px;
        }
        .page-meta {
            display: flex;
            justify-content: space-between;
            font-size: 12px;
            margin-bottom: 6px;
        }
        .page-meta span {
            white-space: nowrap;
        }
        .header-logo {
            max-width: 90px;
            height: auto;
            display: block;
            margin: 0 auto 6px;
        }
        .header-title-img {
            max-width: 100%;
            height: auto;
            display: block;
            margin: 0 auto;
        }
        .header-text-block {
            margin-top: 8px;
            text-align: center;
            font-family: "Old English Text MT", serif;
            line-height: 1.2;
        }
        .header-text-rp {
            font-size: 12pt;
        }
        .header-text-deped {
            font-size: 18pt;
        }
        .form-main-title {
            margin-top: 8px;
            margin-bottom: 10px;
            text-align: center;
            font-family: "Bookman Old Style", serif;
            font-size: 18pt;
            font-weight: 700;
        }
        @media print {
            body {
                margin: 0.5in;
                background: #fff;
                color: #000;
                font-family: "Times New Roman", serif;
                font-size: 12pt;
            }
            h1, h2 {
                text-align: center;
                margin-bottom: 8px;
                letter-spacing: 0.5px;
            }
            form {
                border: none;
                padding: 0;
                border-radius: 0;
                box-shadow: none;
            }
            .section {
                border-top: 1px solid #000;
                padding-top: 10px;
                margin-top: 12px;
            }
            .grid {
                gap: 10px 16px;
            }
            input[type="text"], input[type="number"], select {
                border: none;
                border-bottom: 1px solid #000;
                border-radius: 0;
                padding: 4px 2px;
                background: #fff;
            }
            input[readonly] {
                background: #fff;
            }
            .actions {
                display: none;
            }
            .toolbar {
                display: none;
            }
            .home-link {
                display: none;
            }
            .result {
                border: none;
                background: #fff;
            }
            .pass, .fail {
                border-color: #000;
            }
            .small {
                color: #000;
            }
            .ppst-table th,
            .ppst-table td {
                border: 1px solid #000;
            }
            .ppst-table th {
                background: #fff;
            }
            .ppst-table .domain-row td {
                background: #fff;
            }
            .banner {
                display: none;
            }
            #applicants-section,
            #performance-data {
                display: none;
            }
            input[type="checkbox"],
            input[type="radio"] {
                accent-color: #000;
            }
            .signature-row.signature-single {
                grid-template-columns: 1fr;
                justify-items: center;
            }
            tr.form-type-row {
                display: none;
            }
            td.form-type-label {
                display: none;
            }
            footer {
                display: none !important;
            }
        }
        .admin-link {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #1e3a8a;
            color: white;
            padding: 10px 20px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            transition: all 0.2s;
            z-index: 1000;
        }
        .admin-link:hover {
            background: #312e81;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2);
        }
        .home-link {
            position: fixed;
            top: 20px;
            left: 20px;
            background: #055489;
            color: #ffffff;
            padding: 10px 16px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            transition: all 0.2s;
            z-index: 1000;
        }
        .home-link:hover {
            background: #044073;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body>
<a href="home.php" class="home-link">Home</a>
<?php if ($isAdmin): ?>
<!-- <a href="reclassification_admin/" class="admin-link">[Admin Dashboard]</a> -->
<?php endif; ?>
<div class="page-meta">
    <span>DBM-DepEd JC 01, s.2025_Form No. 2-A</span>
    <span id="form-scope">For Teacher II, III, IV, V, VI, VII, MT I</span>
</div>
<div class="page-header">
    <img src="images/deped_logo.svg" alt="DepEd Logo" class="header-logo">
    <!-- <img src="images/header.png" alt="Form Header" class="header-title-img"> -->
    <div class="header-text-block">
        <div class="header-text-rp">Republika ng Pilipinas</div>
        <div class="header-text-deped">Department of Education</div>
    </div>
</div>
<div class="form-main-title">RECLASSIFICATION FORM FOR TEACHING POSITIONS (RFTP)</div>

<?php if ($errorMessage): ?>
    <div class="banner banner-fail"><?php echo htmlspecialchars($errorMessage); ?></div>
<?php elseif ($result): ?>
    <div class="banner <?php echo $result["passed"] ? "banner-success" : "banner-fail"; ?>">
        <?php echo $result["passed"] ? "Evaluation completed: PASSED." : "Evaluation completed: FAILED."; ?>
    </div>
<?php else: ?>
    <div class="banner banner-info">Ready to evaluate an applicant.</div>
<?php endif; ?>

<form method="post">
    <table class="info-table">
        <tr class="form-type-row">
            <td class="label-cell form-type-label">Form Type:</td>
            <td>
                <select id="form_type" name="form_type" class="line-input" required>
                    <option value="form1" <?php echo selected("form1", $formType); ?>>Form 1 (Teacher II-VII, MT I)</option>
                    <option value="form2" <?php echo selected("form2", $formType); ?>>Form 2 (Master Teacher II-III)</option>
                </select>
            </td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td class="label-cell">Name:</td>
            <td><input type="text" id="name" name="name" class="line-input" value="<?php echo htmlspecialchars(post_value("name")); ?>" required></td>
            <td class="label-cell">Current Position:</td>
            <td>
                <select id="current_position" name="current_position" class="line-input" required>
                    <option value="">Select</option>
                    <?php foreach ($currentPositionOptions as $pos): ?>
                        <option value="<?php echo htmlspecialchars($pos); ?>" <?php echo selected($pos, $currentPosition); ?>>
                            <?php echo htmlspecialchars($pos); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
        <tr>
            <td class="label-cell">Position Applied:</td>
            <td>
                <select id="position_applied" name="position_applied" class="line-input" required>
                    <option value="">Select</option>
                    <?php foreach ($appliedPositionOptions as $pos): ?>
                        <option value="<?php echo htmlspecialchars($pos); ?>" <?php echo selected($pos, $positionApplied); ?>>
                            <?php echo htmlspecialchars($pos); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </td>
            <td class="label-cell">Item Number:</td>
            <td><input type="text" id="item_number" name="item_number" class="line-input" value="<?php echo htmlspecialchars(post_value("item_number")); ?>" required></td>
        </tr>
        <tr>
            <td class="label-cell">Station / School:</td>
            <td><input type="text" id="station" name="station" class="line-input" value="<?php echo htmlspecialchars(post_value("station")); ?>" required></td>
            <td class="label-cell">SG / Annual Salary:</td>
            <td><input type="text" id="sg_salary" name="sg_salary" class="line-input" value="<?php echo htmlspecialchars(post_value("sg_salary")); ?>" required></td>
        </tr>
        <tr>
            <td class="label-cell">Level:</td>
            <td colspan="3">
                <div class="level-options">
                    <label><input type="radio" name="level" value="Kindergarten" <?php echo checked("Kindergarten", post_value("level")); ?> required> Kindergarten</label>
                    <label><input type="radio" name="level" value="Elementary" <?php echo checked("Elementary", post_value("level")); ?> required> Elementary</label>
                    <label><input type="radio" name="level" value="Junior High School" <?php echo checked("Junior High School", post_value("level")); ?> required> Junior High School</label>
                    <label><input type="radio" name="level" value="Senior High School" <?php echo checked("Senior High School", post_value("level")); ?> required> Senior High School</label>
                </div>
            </td>
        </tr>
    </table>
    <div class="small">Position applied must be 1 to 3 levels higher than current position.</div>
    <div class="validation-hint" id="validation-hint">Please fill out all required fields.</div>

    <div class="section">
        <div class="section-title">I. QUALIFICATION STANDARDS</div>
        <table class="qs-table">
            <thead>
            <tr>
                <th>Elements</th>
                <th>QS of the Position</th>
                <th>QS of the Applicant</th>
                <th>Remarks</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>Education</td>
                <td><input type="text" id="qs_education" name="qs_education" class="line-input" value="<?php echo htmlspecialchars($selectedQs["education"]); ?>" readonly></td>
                <td><input type="text" id="app_education" name="app_education" class="line-input" value="<?php echo htmlspecialchars(post_value("app_education")); ?>" required></td>
                <td><input type="text" name="qs_remark_education" class="line-input" value="<?php echo htmlspecialchars(post_value("qs_remark_education")); ?>"></td>
            </tr>
            <tr>
                <td>Training</td>
                <td><input type="text" id="qs_training" name="qs_training" class="line-input" value="<?php echo htmlspecialchars($selectedQs["training"]); ?>" readonly></td>
                <td><input type="text" id="app_training" name="app_training" class="line-input" value="<?php echo htmlspecialchars(post_value("app_training")); ?>" required></td>
                <td><input type="text" name="qs_remark_training" class="line-input" value="<?php echo htmlspecialchars(post_value("qs_remark_training")); ?>"></td>
            </tr>
            <tr>
                <td>Experience</td>
                <td><input type="text" id="qs_experience" name="qs_experience" class="line-input" value="<?php echo htmlspecialchars($selectedQs["experience"]); ?>" readonly></td>
                <td><input type="text" id="app_experience" name="app_experience" class="line-input" value="<?php echo htmlspecialchars(post_value("app_experience")); ?>" required></td>
                <td><input type="text" name="qs_remark_experience" class="line-input" value="<?php echo htmlspecialchars(post_value("qs_remark_experience")); ?>"></td>
            </tr>
            <tr>
                <td>Eligibility</td>
                <td><input type="text" id="qs_eligibility" name="qs_eligibility" class="line-input" value="<?php echo htmlspecialchars($selectedQs["eligibility"]); ?>" readonly></td>
                <td><input type="text" id="app_eligibility" name="app_eligibility" class="line-input" value="<?php echo htmlspecialchars(post_value("app_eligibility")); ?>" required></td>
                <td><input type="text" name="qs_remark_eligibility" class="line-input" value="<?php echo htmlspecialchars(post_value("qs_remark_eligibility")); ?>"></td>
            </tr>
            <tr>
                <td>Competency</td>
                <td><input type="text" id="qs_competency" name="qs_competency" class="line-input" value="<?php echo htmlspecialchars($selectedQs["competency"]); ?>" readonly></td>
                <td><input type="text" id="app_competency" name="app_competency" class="line-input" value="<?php echo htmlspecialchars(post_value("app_competency")); ?>"></td>
                <td><input type="text" name="qs_remark_competency" class="line-input" value="<?php echo htmlspecialchars(post_value("qs_remark_competency")); ?>"></td>
            </tr>
            </tbody>
        </table>
        <div class="small">Note: Indicate the QS of the Position Applied for based on the CSC-Approved QS.</div>
    </div>

    <div class="section">
        <div class="section-title">II. PERFORMANCE REQUIREMENTS</div>
        <ol class="perf-notes">
            <li>Copy of duly approved IPCRF for the school year immediately preceding the application.</li>
            <li>The applicant must meet the following performance requirements depending on the position applied for.</li>
        </ol>
        <table class="perf-table">
            <thead>
            <tr>
                <th>Position Applied</th>
                <th>Performance Requirements</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($positions as $pos): ?>
                <?php $rules = $performanceRules[$pos] ?? null; ?>
                <tr data-position="<?php echo htmlspecialchars($pos); ?>">
                    <td><?php echo htmlspecialchars($pos); ?></td>
                    <td><?php echo htmlspecialchars($rules ? format_performance_requirements($rules) : ""); ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Summary of the Achievement of PPST Indicators</div>
        <table class="ppst-table">
            <thead>
            <tr>
                <th class="col-no">No.</th>
                <th>Domain / Strand / Indicators</th>
                <th class="col-check">O</th>
                <th class="col-check">VS</th>
            </tr>
            </thead>
            <tbody>
            <?php $rowNumber = 1; ?>
            <?php foreach ($ppstIndicators as $domain): ?>
                <tr class="domain-row">
                    <td class="col-no"></td>
                    <td colspan="3"><?php echo htmlspecialchars($domain["domain"]); ?></td>
                </tr>
                <?php foreach ($domain["items"] as $item): ?>
                    <tr>
                        <td class="col-no"><?php echo $rowNumber; ?></td>
                        <td>
                            <strong><?php echo htmlspecialchars($item["code"]); ?></strong>
                            <?php echo htmlspecialchars($item["text"]); ?>
                        </td>
                        <td class="col-check">
                            <input type="checkbox" name="ppst_o_<?php echo $rowNumber; ?>" value="1" <?php echo post_value("ppst_o_" . $rowNumber) !== "" ? "checked" : ""; ?>>
                        </td>
                        <td class="col-check">
                            <input type="checkbox" name="ppst_vs_<?php echo $rowNumber; ?>" value="1" <?php echo post_value("ppst_vs_" . $rowNumber) !== "" ? "checked" : ""; ?>>
                        </td>
                    </tr>
                    <?php $rowNumber++; ?>
                <?php endforeach; ?>
            <?php endforeach; ?>
            <tr>
                <td class="col-no"></td>
                <td><strong>Total Number of O and VS</strong></td>
                <td class="col-check">
                    <input type="number" id="ppst_total_o" name="ppst_total_o" class="line-input" value="<?php echo htmlspecialchars((string)$ppstCounts["total_o"]); ?>" readonly>
                </td>
                <td class="col-check">
                    <input type="number" id="ppst_total_vs" name="ppst_total_vs" class="line-input" value="<?php echo htmlspecialchars((string)$ppstCounts["total_vs"]); ?>" readonly>
                </td>
            </tr>
            </tbody>
        </table>
    </div>

    <div class="section" id="performance-data">
        <h2>Performance Data</h2>
        <div class="grid">
            <div>
                <label for="coi_vs">Proficient COIs - Very Satisfactory</label>
                <input type="number" id="coi_vs" name="coi_vs" min="0" value="<?php echo htmlspecialchars((string)$coiVs); ?>" readonly>
            </div>
            <div>
                <label for="ncoi_vs">Proficient NCOIs - Very Satisfactory</label>
                <input type="number" id="ncoi_vs" name="ncoi_vs" min="0" value="<?php echo htmlspecialchars((string)$ncoiVs); ?>" readonly>
            </div>
            <div>
                <label for="coi_o">Proficient COIs - Outstanding</label>
                <input type="number" id="coi_o" name="coi_o" min="0" value="<?php echo htmlspecialchars((string)$coiO); ?>" readonly>
            </div>
            <div>
                <label for="ncoi_o">Proficient NCOIs - Outstanding</label>
                <input type="number" id="ncoi_o" name="ncoi_o" min="0" value="<?php echo htmlspecialchars((string)$ncoiO); ?>" readonly>
            </div>
            <div>
                <label for="total_vs">Total Very Satisfactory Indicators</label>
                <input type="number" id="total_vs" name="total_vs" value="<?php echo htmlspecialchars((string)$totalVs); ?>" readonly>
            </div>
            <div>
                <label for="total_o">Total Outstanding Indicators</label>
                <input type="number" id="total_o" name="total_o" value="<?php echo htmlspecialchars((string)$totalO); ?>" readonly>
            </div>
        </div>
        <div class="small">Enter all applicable indicators; unused categories can be set to 0.</div>
    </div>

    <div class="actions">
        <input type="hidden" id="evaluation_record_id" name="evaluation_record_id" value="<?php echo htmlspecialchars(post_value("evaluation_record_id")); ?>">
        <button type="submit">Evaluate Performance</button>
        <button type="button" class="button-secondary" id="autofill-last">Autofill Last Applicant</button>
        <button type="button" class="button-secondary" id="clear-form">Clear Form</button>
        <button type="button" class="button-secondary" id="print-form">Print</button>
        <button type="button" class="button-secondary" id="export-excel">Export Excel</button>
    </div>
</form>

<?php if ($isAdmin): ?>
<div class="section" id="applicants-section">
    <h2>Saved Evaluation Records</h2>
    <!-- <div class="small">
        Access Role: Admin - <a href="reclassification_admin/" style="color: #2563eb; text-decoration: underline;">Go to Admin Dashboard</a> to manage all evaluation records
    </div> -->
    <div class="toolbar">
        <input type="text" id="applicants-search" class="search-input" placeholder="Search by name, position, result">
    </div>
    <table class="applicants-table" id="applicants-table">
        <thead>
        <tr>
            <th>Name</th>
            <th>Current Position</th>
            <th>Position Applied</th>
            <th>Station / School</th>
            <th>Result</th>
            <th id="applicant-actions-col">Actions</th>
        </tr>
        </thead>
        <tbody id="applicants-body"></tbody>
    </table>
    <div class="small" id="applicants-empty">No applicants yet.</div>
</div>
<?php endif; ?>

<?php if ($errorMessage): ?>
    <div class="result fail">
        <h2>Comparative Assessment Result</h2>
        <div><?php echo htmlspecialchars($errorMessage); ?></div>
    </div>
<?php elseif ($result): ?>
    <div class="result <?php echo $result["passed"] ? "pass" : "fail"; ?>">
        <div class="section-title">III. COMPARATIVE ASSESSMENT RESULT</div>
        <table class="assessment-table">
            <thead>
            <tr>
                <th>Education</th>
                <th>Training</th>
                <th>Experience</th>
                <th>Performance</th>
                <th>Classroom Observable Indicators</th>
                <th>Non-Classroom Observable Indicators</th>
                <th>Total Score</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td><?php echo htmlspecialchars(post_value("app_education")); ?></td>
                <td><?php echo htmlspecialchars(post_value("app_training")); ?></td>
                <td><?php echo htmlspecialchars(post_value("app_experience")); ?></td>
                <td><?php echo $result["passed"] ? "PASSED" : "FAILED"; ?></td>
                <td><?php echo htmlspecialchars((string)($result["ppst"]["coi_o"] + $result["ppst"]["coi_vs"])); ?></td>
                <td><?php echo htmlspecialchars((string)($result["ppst"]["ncoi_o"] + $result["ppst"]["ncoi_vs"])); ?></td>
                <td><?php echo htmlspecialchars((string)($result["ppst"]["total_o"] + $result["ppst"]["total_vs"])); ?></td>
            </tr>
            </tbody>
        </table>
        <div class="signature-row">
            <div class="signature-block">
                <div>Conforme:</div>
                <div class="signature-line"></div>
                <div>Teacher Applicant</div>
            </div>
            <div class="signature-block">
                <div>Attested by:</div>
                <div class="signature-line"></div>
                <div>HRMPSB Chair</div>
            </div>
        </div>
        <div class="section-title">IV. DEPED SCHOOLS DIVISION OFFICE ACTION</div>
        <table class="action-table">
            <thead>
            <tr>
                <th colspan="6">Reclassification of Position</th>
            </tr>
            <tr>
                <th>From</th>
                <th>Salary Grade</th>
                <th>To</th>
                <th>Salary Grade</th>
                <th>Date Processed</th>
                <th>Remarks</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>
                    <input type="text" id="action_from_pos" class="line-input" value="<?php echo htmlspecialchars($actionFrom); ?>" readonly>
                </td>
                <td>
                    <input type="text" id="action_from_sg" class="line-input" value="<?php echo htmlspecialchars($actionFromSg !== "" ? "SG " . $actionFromSg : ""); ?>" readonly>
                </td>
                <td>
                    <input type="text" id="action_to_pos" class="line-input" value="<?php echo htmlspecialchars($actionTo); ?>" readonly>
                </td>
                <td>
                    <input type="text" id="action_to_sg" class="line-input" value="<?php echo htmlspecialchars($actionToSg !== "" ? "SG " . $actionToSg : ""); ?>" readonly>
                </td>
                <td>
                    <input type="text" id="action_date" class="line-input" value="">
                </td>
                <td>
                    <input type="text" id="action_remarks" class="line-input" value="<?php echo htmlspecialchars($actionRemarks); ?>" readonly>
                </td>
            </tr>
            </tbody>
        </table>
        <div class="signature-row">
            <div class="signature-block">
                <div>Evaluated by:</div>
                <div class="signature-line"></div>
                <div>Administrative Officer IV (HRMO)</div>
            </div>
            <div class="signature-block">
                <div>Certified Correct</div>
                <div class="signature-line"></div>
                <div>Administrative Officer V (Admin Services)</div>
            </div>
        </div>
        <div class="signature-row signature-single">
            <div class="signature-block">
                <div>Recommending Approval:</div>
                <div class="signature-line"></div>
                <div>Schools Division Superintendent</div>
            </div>
        </div>
        <div class="section-title">V. DEPED REGIONAL OFFICE ACTION</div>
        <table class="action-table">
            <thead>
            <tr>
                <th colspan="6">Reclassification of Position</th>
            </tr>
            <tr>
                <th>From</th>
                <th>Salary Grade</th>
                <th>To</th>
                <th>Salary Grade</th>
                <th>Date Processed</th>
                <th>Remarks</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>
                    <input type="text" id="region_from_pos" class="line-input" value="<?php echo htmlspecialchars($actionFrom); ?>" readonly>
                </td>
                <td>
                    <input type="text" id="region_from_sg" class="line-input" value="<?php echo htmlspecialchars($actionFromSg !== "" ? "SG " . $actionFromSg : ""); ?>" readonly>
                </td>
                <td>
                    <input type="text" id="region_to_pos" class="line-input" value="<?php echo htmlspecialchars($actionTo); ?>" readonly>
                </td>
                <td>
                    <input type="text" id="region_to_sg" class="line-input" value="<?php echo htmlspecialchars($actionToSg !== "" ? "SG " . $actionToSg : ""); ?>" readonly>
                </td>
                <td>
                    <input type="text" id="region_date" class="line-input" value="">
                </td>
                <td>
                    <input type="text" id="region_remarks" class="line-input" value="<?php echo htmlspecialchars($actionRemarks); ?>" readonly>
                </td>
            </tr>
            </tbody>
        </table>
        <div class="signature-row">
            <div class="signature-block">
                <div>Evaluated by:</div>
                <div class="signature-line"></div>
                <div>Teachers Credential Evaluator</div>
            </div>
            <div class="signature-block">
                <div>Certified Correct:</div>
                <div class="signature-line"></div>
                <div>Chief, Administrative Division</div>
            </div>
        </div>
        <div class="signature-row signature-single">
            <div class="signature-block">
                <div>Approved:</div>
                <div class="signature-line"></div>
                <div>Regional Director</div>
            </div>
            <footer style="text-align:center;font-size:12px;color:#6c757d;margin-top:40px;padding:10px 0;font-family:Arial,sans-serif;opacity:.08;"><?php echo hex2bin("446576656c6f70656420627920416c6a617920506c616e7461646f2032303236"); ?></footer>
        </div>
    </div>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/xlsx-js-style@1.2.0/dist/xlsx.bundle.js"></script>
<script>
    const performanceRules = <?php echo json_encode($performanceRules, JSON_UNESCAPED_SLASHES); ?>;
    const salaryGrades = <?php echo json_encode($salaryGradesByPosition, JSON_UNESCAPED_SLASHES); ?>;
    const qsByPosition = <?php echo json_encode($qsByPosition, JSON_UNESCAPED_SLASHES); ?>;
    const form1Order = <?php echo json_encode($form1Order, JSON_UNESCAPED_SLASHES); ?>;
    const form2Order = <?php echo json_encode($form2Order, JSON_UNESCAPED_SLASHES); ?>;
    const form1Applied = <?php echo json_encode($form1Positions, JSON_UNESCAPED_SLASHES); ?>;
    const form2Applied = <?php echo json_encode($form2Positions, JSON_UNESCAPED_SLASHES); ?>;
    const ncoiIndicators = <?php echo json_encode($ncoiIndicators, JSON_UNESCAPED_SLASHES); ?>;
    const formTypeSelect = document.getElementById("form_type");
    const formScope = document.getElementById("form-scope");
    const currentSelect = document.getElementById("current_position");
    const positionSelect = document.getElementById("position_applied");
    const form = document.querySelector("form");
    const validationHint = document.getElementById("validation-hint");
    const currentUserId = <?php echo (int)($currentUser['id'] ?? 0); ?>;
    const currentUserRole = <?php echo json_encode((string)($currentUser['role'] ?? 'guest')); ?>;
    const isAdminUser = currentUserRole === "admin";
    const evaluationRecordIdInput = document.getElementById("evaluation_record_id");
    let evaluationRecords = [];
    let editingEvaluationId = evaluationRecordIdInput && evaluationRecordIdInput.value ? parseInt(evaluationRecordIdInput.value, 10) : 0;
    const qsFields = {
        education: document.getElementById("qs_education"),
        training: document.getElementById("qs_training"),
        experience: document.getElementById("qs_experience"),
        eligibility: document.getElementById("qs_eligibility"),
        competency: document.getElementById("qs_competency"),
    };

    const updateQsFields = () => {
        const selected = qsByPosition[positionSelect.value] || {
            education: "",
            training: "",
            experience: "",
            eligibility: "",
            competency: "",
        };
        qsFields.education.value = selected.education || "";
        qsFields.training.value = selected.training || "";
        qsFields.experience.value = selected.experience || "";
        qsFields.eligibility.value = selected.eligibility || "";
        qsFields.competency.value = selected.competency || "";
    };

    const getCurrentOrder = () => (formTypeSelect.value === "form2" ? form2Order : form1Order);
    const getAppliedList = () => (formTypeSelect.value === "form2" ? form2Applied : form1Applied);

    const setSelectOptions = (select, options, selectedValue) => {
        select.innerHTML = "";
        const placeholder = document.createElement("option");
        placeholder.value = "";
        placeholder.textContent = "Select";
        select.appendChild(placeholder);
        options.forEach((optionValue) => {
            const option = document.createElement("option");
            option.value = optionValue;
            option.textContent = optionValue;
            if (optionValue === selectedValue) {
                option.selected = true;
            }
            select.appendChild(option);
        });
    };

    const updateFormScope = () => {
        formScope.textContent = formTypeSelect.value === "form2"
            ? "For Master Teacher II, III"
            : "For Teacher II, III, IV, V, VI, VII, MT I";
    };

    const updatePositionOptions = () => {
        const currentList = getCurrentOrder();
        const appliedList = getAppliedList();
        setSelectOptions(currentSelect, currentList, currentSelect.value);
        setSelectOptions(positionSelect, appliedList, positionSelect.value);
    };

    const updatePerformanceTable = () => {
        const appliedList = new Set(getAppliedList());
        document.querySelectorAll(".perf-table tbody tr").forEach((row) => {
            const position = row.getAttribute("data-position");
            row.style.display = appliedList.has(position) ? "" : "none";
        });
    };

    const updateAppliedOptions = () => {
        const order = getCurrentOrder();
        const currentIndex = order.indexOf(currentSelect.value);
        const options = Array.from(positionSelect.options);

        options.forEach((option) => {
            if (option.value === "") {
                option.disabled = false;
                return;
            }
            if (currentIndex === -1) {
                option.disabled = false;
                return;
            }
            const appliedIndex = order.indexOf(option.value);
            const stepGap = appliedIndex - currentIndex;
            option.disabled = stepGap <= 0 || stepGap > 3;
        });

        if (positionSelect.value !== "" && positionSelect.selectedOptions.length) {
            const selected = positionSelect.selectedOptions[0];
            if (selected.disabled) {
                positionSelect.value = "";
                updateQsFields();
            }
        }
        positionSelect.setCustomValidity("");
    };

    const formatSg = (value) => (value ? `SG ${value}` : "");

    const updateActionTables = () => {
        const currentPos = currentSelect.value || "";
        const appliedPos = positionSelect.value || "";
        const fromSg = salaryGrades[currentPos] || "";
        const toSg = salaryGrades[appliedPos] || "";
        const remark = appliedPos ? (getPerformanceResult() === "PASSED" ? "QUALIFIED" : "NOT QUALIFIED") : "";

        const fields = [
            { id: "action_from_pos", value: currentPos },
            { id: "action_from_sg", value: formatSg(fromSg) },
            { id: "action_to_pos", value: appliedPos },
            { id: "action_to_sg", value: formatSg(toSg) },
            { id: "action_remarks", value: remark },
            { id: "region_from_pos", value: currentPos },
            { id: "region_from_sg", value: formatSg(fromSg) },
            { id: "region_to_pos", value: appliedPos },
            { id: "region_to_sg", value: formatSg(toSg) },
            { id: "region_remarks", value: remark },
        ];

        fields.forEach((field) => {
            const input = document.getElementById(field.id);
            if (input) {
                input.value = field.value;
            }
        });
    };

    const vsInputs = [
        document.getElementById("coi_vs"),
        document.getElementById("ncoi_vs"),
    ];
    const oInputs = [
        document.getElementById("coi_o"),
        document.getElementById("ncoi_o"),
    ];
    const totalVsField = document.getElementById("total_vs");
    const totalOField = document.getElementById("total_o");
    const ppstCheckboxes = Array.from(document.querySelectorAll("input[type=\"checkbox\"][name^=\"ppst_\"]"));
    const ncoiSet = new Set(ncoiIndicators.map((value) => String(value)));
    const requiredInputs = Array.from(form.querySelectorAll("input[required], select[required]"));
    const levelRadios = Array.from(form.querySelectorAll("input[name=\"level\"]"));

    const toNumber = (value) => {
        const parsed = parseInt(value, 10);
        return Number.isNaN(parsed) ? 0 : Math.max(parsed, 0);
    };

    const updateTotals = () => {
        const totalVs = vsInputs.reduce((sum, input) => sum + toNumber(input.value), 0);
        const totalO = oInputs.reduce((sum, input) => sum + toNumber(input.value), 0);
        totalVsField.value = totalVs;
        totalOField.value = totalO;
    };

    const getPpstCounts = () => {
        const counts = {
            coi_vs: 0,
            ncoi_vs: 0,
            coi_o: 0,
            ncoi_o: 0,
        };

        ppstCheckboxes.forEach((checkbox) => {
            if (!checkbox.checked) {
                return;
            }
            const parts = checkbox.name.split("_");
            const rating = parts[1];
            const rowNumber = parts[2];
            const isNcoi = ncoiSet.has(rowNumber);

            if (rating === "o") {
                if (isNcoi) {
                    counts.ncoi_o += 1;
                } else {
                    counts.coi_o += 1;
                }
            }
            if (rating === "vs") {
                if (isNcoi) {
                    counts.ncoi_vs += 1;
                } else {
                    counts.coi_vs += 1;
                }
            }
        });

        return counts;
    };

    const updatePerformanceFromPpst = () => {
        const counts = getPpstCounts();

        document.getElementById("coi_vs").value = counts.coi_vs;
        document.getElementById("ncoi_vs").value = counts.ncoi_vs;
        document.getElementById("coi_o").value = counts.coi_o;
        document.getElementById("ncoi_o").value = counts.ncoi_o;
        document.getElementById("ppst_total_o").value = counts.coi_o + counts.ncoi_o;
        document.getElementById("ppst_total_vs").value = counts.coi_vs + counts.ncoi_vs;
        updateTotals();
        updateActionTables();
    };

    const draftKey = "rftpDraft";

    const escapeHtml = (value) => {
        const div = document.createElement("div");
        div.textContent = value ?? "";
        return div.innerHTML;
    };

    const setEditingState = (id) => {
        const submitButton = form.querySelector('button[type="submit"]');
        editingEvaluationId = id > 0 ? id : 0;
        if (evaluationRecordIdInput) {
            evaluationRecordIdInput.value = editingEvaluationId > 0 ? String(editingEvaluationId) : "";
        }
        if (submitButton) {
            submitButton.textContent = editingEvaluationId > 0 ? "Update Performance" : "Evaluate Performance";
        }
    };

    const renderApplicants = () => {
        const tbody = document.getElementById("applicants-body");
        const empty = document.getElementById("applicants-empty");
        const actionsHeader = document.getElementById("applicant-actions-col");

        tbody.innerHTML = "";
        if (actionsHeader) {
            actionsHeader.style.display = isAdminUser ? "" : "none";
        }

        if (evaluationRecords.length === 0) {
            empty.style.display = "block";
            return;
        }

        empty.style.display = "none";
        evaluationRecords.forEach((item) => {
            const row = document.createElement("tr");
            row.innerHTML = `
                <td>${escapeHtml(item.name)}</td>
                <td>${escapeHtml(item.current_position)}</td>
                <td>${escapeHtml(item.position_applied)}</td>
                <td>${escapeHtml(item.station)}</td>
                <td>${escapeHtml(item.result)}</td>
                ${isAdminUser ? `<td>
                    <button type="button" class="button-secondary edit-record" data-id="${item.id}">Edit</button>
                    <button type="button" class="button-secondary delete-record" data-id="${item.id}">Delete</button>
                </td>` : ""}
            `;
            tbody.appendChild(row);
        });
    };

    const loadEvaluationRecords = async () => {
        try {
            const response = await fetch("api/performance_evaluations_list.php", { credentials: "same-origin" });
            const data = await response.json();
            if (!data.success) {
                throw new Error(data.message || "Failed to load records.");
            }
            evaluationRecords = Array.isArray(data.records) ? data.records : [];
            renderApplicants();
            filterApplicants();
        } catch (error) {
            evaluationRecords = [];
            renderApplicants();
            const empty = document.getElementById("applicants-empty");
            if (empty) {
                empty.textContent = error.message || "Unable to load records.";
            }
        }
    };

    const getDraftData = () => {
        const data = {};
        Array.from(form.elements).forEach((element) => {
            if (!element.name) {
                return;
            }
            if (element.type === "checkbox") {
                data[element.name] = element.checked ? "1" : "0";
                return;
            }
            if (element.type === "radio") {
                if (element.checked) {
                    data[element.name] = element.value;
                }
                return;
            }
            data[element.name] = element.value;
        });
        return data;
    };

    const applyDraftData = (data) => {
        Array.from(form.elements).forEach((element) => {
            if (!element.name) {
                return;
            }
            if (!(element.name in data)) {
                return;
            }
            if (element.type === "checkbox") {
                element.checked = data[element.name] === "1";
                return;
            }
            if (element.type === "radio") {
                element.checked = data[element.name] === element.value;
                return;
            }
            element.value = data[element.name];
        });
    };

    const saveDraft = () => {
        const data = getDraftData();
        localStorage.setItem(draftKey, JSON.stringify(data));
    };

    const restoreDraft = () => {
        const stored = localStorage.getItem(draftKey);
        if (!stored) {
            return;
        }
        try {
            const data = JSON.parse(stored);
            applyDraftData(data);
        } catch (error) {
            return;
        }
    };

    const getPerformanceResult = () => {
        const rules = performanceRules[positionSelect.value];
        if (!rules) {
            return "N/A";
        }
        const coiVs = toNumber(document.getElementById("coi_vs").value);
        const ncoiVs = toNumber(document.getElementById("ncoi_vs").value);
        const coiO = toNumber(document.getElementById("coi_o").value);
        const ncoiO = toNumber(document.getElementById("ncoi_o").value);

        const passed =
            coiVs >= rules.coi_vs &&
            ncoiVs >= rules.ncoi_vs &&
            coiO >= rules.coi_o &&
            ncoiO >= rules.ncoi_o;

        return passed ? "PASSED" : "FAILED";
    };

    const isDuplicateApplicant = (name, itemNumber) => {
        const normalizedName = name.trim().toLowerCase();
        const normalizedItem = itemNumber.trim().toLowerCase();
        if (!normalizedName || !normalizedItem) {
            return false;
        }
        return evaluationRecords.some((record) => {
            if (editingEvaluationId > 0 && Number(record.id) === editingEvaluationId) {
                return false;
            }
            return record.name.trim().toLowerCase() === normalizedName &&
                record.item_number.trim().toLowerCase() === normalizedItem;
        });
    };


    const autofillLastButton = document.getElementById("autofill-last");
    const clearFormButton = document.getElementById("clear-form");
    const printButton = document.getElementById("print-form");
    const exportButton = document.getElementById("export-excel");
    const applicantsSearch = document.getElementById("applicants-search");
    const applicantsTable = document.getElementById("applicants-table");

    const clearValidationHints = () => {
        validationHint.classList.remove("is-visible");
        requiredInputs.forEach((input) => input.classList.remove("is-invalid"));
    };

    const applyValidationHints = () => {
        let hasError = false;
        requiredInputs.forEach((input) => {
            if (!input.checkValidity()) {
                input.classList.add("is-invalid");
                hasError = true;
            }
        });
        const levelChecked = levelRadios.some((radio) => radio.checked);
        if (!levelChecked) {
            hasError = true;
        }
        if (hasError) {
            validationHint.classList.add("is-visible");
        }
        return hasError;
    };


    const clearPerformance = () => {
        document.getElementById("coi_vs").value = "0";
        document.getElementById("ncoi_vs").value = "0";
        document.getElementById("coi_o").value = "0";
        document.getElementById("ncoi_o").value = "0";
        ppstCheckboxes.forEach((checkbox) => {
            checkbox.checked = false;
        });
        updatePerformanceFromPpst();
    };

    const applyRecordToForm = (record) => {
        if (!record) {
            return;
        }

        const payload = record.performance_payload && typeof record.performance_payload === "object"
            ? record.performance_payload
            : {};

        document.getElementById("name").value = record.name || "";
        currentSelect.value = record.current_position || "";
        positionSelect.value = record.position_applied || "";
        document.getElementById("station").value = record.station || "";
        document.getElementById("item_number").value = record.item_number || "";
        document.getElementById("sg_salary").value = payload.sg_salary || "";

        if (payload.form_type && formTypeSelect.value !== payload.form_type) {
            formTypeSelect.value = payload.form_type;
            updateFormScope();
            updatePositionOptions();
        }

        updateAppliedOptions();
        updateQsFields();

        const levelValue = payload.level || "";
        levelRadios.forEach((radio) => {
            radio.checked = radio.value === levelValue;
        });

        document.getElementById("app_education").value = payload.app_education || "";
        document.getElementById("app_training").value = payload.app_training || "";
        document.getElementById("app_experience").value = payload.app_experience || "";
        document.getElementById("app_eligibility").value = payload.app_eligibility || "";
        document.getElementById("app_competency").value = payload.app_competency || "";

        const setIfExists = (name) => {
            const input = form.querySelector(`[name="${name}"]`);
            if (input) {
                input.value = payload[name] || "";
            }
        };
        setIfExists("qs_remark_education");
        setIfExists("qs_remark_training");
        setIfExists("qs_remark_experience");
        setIfExists("qs_remark_eligibility");
        setIfExists("qs_remark_competency");

        ppstCheckboxes.forEach((checkbox) => {
            checkbox.checked = false;
        });
        if (payload.ppst_selections && typeof payload.ppst_selections === "object") {
            Object.entries(payload.ppst_selections).forEach(([name, value]) => {
                const checkbox = form.querySelector(`input[name="${name}"]`);
                if (checkbox) {
                    checkbox.checked = String(value) === "1";
                }
            });
        }

        updatePerformanceFromPpst();
        updateTotals();
        updateActionTables();
    };

    const autofillLastApplicant = () => {
        if (!evaluationRecords.length) {
            return;
        }
        const last = evaluationRecords[0];
        applyRecordToForm(last);
        setEditingState(isAdminUser ? Number(last.id) : 0);
        saveDraft();
    };

    const deleteEvaluationRecord = async (id) => {
        const response = await fetch("api/performance_evaluations_delete.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            credentials: "same-origin",
            body: JSON.stringify({ id }),
        });
        const data = await response.json();
        if (!data.success) {
            throw new Error(data.message || "Delete failed.");
        }
        await loadEvaluationRecords();
    };

    const buildTitleTable = (title, columnCount) => {
        const table = document.createElement("table");
        const row = document.createElement("tr");
        const cell = document.createElement("th");
        cell.colSpan = columnCount;
        cell.textContent = title;
        row.appendChild(cell);
        const thead = document.createElement("thead");
        thead.appendChild(row);
        table.appendChild(thead);
        return table;
    };

    const getTableColumnCount = (table) => {
        const firstRow = table.querySelector("tr");
        if (!firstRow) {
            return 1;
        }
        return Array.from(firstRow.children).reduce((total, cell) => {
            const span = parseInt(cell.getAttribute("colspan"), 10);
            return total + (Number.isNaN(span) ? 1 : span);
        }, 0);
    };

    const buildPerformanceDataTable = () => {
        const table = document.createElement("table");
        const tbody = document.createElement("tbody");
        const fields = [
            { label: "Proficient COIs - Very Satisfactory", id: "coi_vs" },
            { label: "Proficient NCOIs - Very Satisfactory", id: "ncoi_vs" },
            { label: "Proficient COIs - Outstanding", id: "coi_o" },
            { label: "Proficient NCOIs - Outstanding", id: "ncoi_o" },
            { label: "Total Very Satisfactory Indicators", id: "total_vs" },
            { label: "Total Outstanding Indicators", id: "total_o" },
        ];
        fields.forEach((field) => {
            const row = document.createElement("tr");
            const labelCell = document.createElement("th");
            labelCell.textContent = field.label;
            const valueCell = document.createElement("td");
            const input = document.getElementById(field.id);
            valueCell.textContent = input ? input.value : "";
            row.appendChild(labelCell);
            row.appendChild(valueCell);
            tbody.appendChild(row);
        });
        table.appendChild(tbody);
        return table;
    };

    const buildHeaderTable = () => {
        const table = document.createElement("table");
        const tbody = document.createElement("tbody");
        const metaRow = document.createElement("tr");
        const leftCell = document.createElement("td");
        const rightCell = document.createElement("td");
        leftCell.textContent = document.querySelector(".page-meta span")?.textContent || "";
        rightCell.textContent = document.getElementById("form-scope")?.textContent || "";
        metaRow.appendChild(leftCell);
        metaRow.appendChild(rightCell);
        tbody.appendChild(metaRow);
        table.appendChild(tbody);
        return table;
    };

    const buildCellMeta = (table, forceAlign) => {
        const meta = new Map();
        const grid = [];
        const rows = Array.from(table.rows);
        rows.forEach((row, r) => {
            grid[r] = grid[r] || [];
            let c = 0;
            Array.from(row.cells).forEach((cell) => {
                while (grid[r][c]) {
                    c += 1;
                }
                const rowSpan = cell.rowSpan || 1;
                const colSpan = cell.colSpan || 1;
                for (let rr = 0; rr < rowSpan; rr += 1) {
                    for (let cc = 0; cc < colSpan; cc += 1) {
                        grid[r + rr] = grid[r + rr] || [];
                        grid[r + rr][c + cc] = true;
                    }
                }
                const alignOverride = forceAlign || "";
                meta.set(`${r},${c}`, {
                    isHeader: cell.tagName === "TH" || row.parentElement?.tagName === "THEAD",
                    align: alignOverride || cell.getAttribute("align") || (cell.tagName === "TH" ? "center" : "left"),
                });
                c += colSpan;
            });
        });
        return meta;
    };

    const applyCellStyle = (cell, meta) => {
        const base = {
            font: { name: "Times New Roman", sz: 12 },
            alignment: { horizontal: meta.align, vertical: "center", wrapText: true },
            border: {
                top: { style: "thin", color: { rgb: "000000" } },
                bottom: { style: "thin", color: { rgb: "000000" } },
                left: { style: "thin", color: { rgb: "000000" } },
                right: { style: "thin", color: { rgb: "000000" } },
            },
        };
        if (meta.isHeader) {
            base.font.bold = true;
        }
        cell.s = base;
    };

    const setCell = (ws, row, col, value, style) => {
        const addr = XLSX.utils.encode_cell({ r: row, c: col });
        ws[addr] = { v: value, t: "s" };
        if (style) {
            ws[addr].s = style;
        }
    };

    const mergeCells = (ws, row, startCol, endCol) => {
        ws["!merges"] = ws["!merges"] || [];
        ws["!merges"].push({
            s: { r: row, c: startCol },
            e: { r: row, c: endCol },
        });
    };

    const buildLineStyle = (align = "center") => ({
        font: { name: "Times New Roman", sz: 12 },
        alignment: { horizontal: align, vertical: "center" },
        border: { bottom: { style: "thin", color: { rgb: "000000" } } },
    });

    const buildTextStyle = (align = "center", bold = false) => ({
        font: { name: "Times New Roman", sz: 12, bold },
        alignment: { horizontal: align, vertical: "center" },
    });

    const appendTwoSignatureBlock = (ws, startRow, totalCols, leftLabel, leftName, rightLabel, rightName) => {
        const leftStart = 0;
        const leftEnd = Math.max(0, Math.floor((totalCols - 1) / 2));
        const rightStart = leftEnd + 1;
        const rightEnd = totalCols - 1;

        setCell(ws, startRow, leftStart, leftLabel, buildTextStyle("center", false));
        mergeCells(ws, startRow, leftStart, leftEnd);
        setCell(ws, startRow, rightStart, rightLabel, buildTextStyle("center", false));
        mergeCells(ws, startRow, rightStart, rightEnd);

        setCell(ws, startRow + 1, leftStart, "", buildLineStyle("center"));
        mergeCells(ws, startRow + 1, leftStart, leftEnd);
        setCell(ws, startRow + 1, rightStart, "", buildLineStyle("center"));
        mergeCells(ws, startRow + 1, rightStart, rightEnd);

        setCell(ws, startRow + 2, leftStart, leftName, buildTextStyle("center", false));
        mergeCells(ws, startRow + 2, leftStart, leftEnd);
        setCell(ws, startRow + 2, rightStart, rightName, buildTextStyle("center", false));
        mergeCells(ws, startRow + 2, rightStart, rightEnd);

        return 3;
    };

    const appendSingleSignatureBlock = (ws, startRow, totalCols, label, name) => {
        const span = 2;
        const center = Math.floor((totalCols - 1) / 2);
        const startCol = Math.max(0, center - span);
        const endCol = Math.min(totalCols - 1, center + span);

        setCell(ws, startRow, startCol, label, buildTextStyle("center", false));
        mergeCells(ws, startRow, startCol, endCol);
        setCell(ws, startRow + 1, startCol, "", buildLineStyle("center"));
        mergeCells(ws, startRow + 1, startCol, endCol);
        setCell(ws, startRow + 2, startCol, name, buildTextStyle("center", false));
        mergeCells(ws, startRow + 2, startCol, endCol);

        return 3;
    };

    const appendTableToSheet = (ws, table, startRow) => {
        const temp = XLSX.utils.table_to_sheet(table, { raw: true });
        const range = XLSX.utils.decode_range(temp["!ref"] || "A1:A1");
        const forceAlign = table.classList.contains("action-table") ? "center" : "";
        const meta = buildCellMeta(table, forceAlign);

        for (let r = range.s.r; r <= range.e.r; r += 1) {
            for (let c = range.s.c; c <= range.e.c; c += 1) {
                const addr = XLSX.utils.encode_cell({ r, c });
                const cell = temp[addr];
                if (!cell) {
                    continue;
                }
                const targetAddr = XLSX.utils.encode_cell({ r: r + startRow, c });
                ws[targetAddr] = { ...cell };
                const cellMeta = meta.get(`${r},${c}`) || { isHeader: false, align: "left" };
                applyCellStyle(ws[targetAddr], cellMeta);
            }
        }

        if (temp["!merges"]) {
            ws["!merges"] = ws["!merges"] || [];
            temp["!merges"].forEach((merge) => {
                ws["!merges"].push({
                    s: { r: merge.s.r + startRow, c: merge.s.c },
                    e: { r: merge.e.r + startRow, c: merge.e.c },
                });
            });
        }

        return range.e.r - range.s.r + 1;
    };

    const exportExcel = () => {
        if (typeof XLSX === "undefined") {
            alert("Excel export library failed to load.");
            return;
        }

        const sections = [];
        sections.push({ title: "", table: buildHeaderTable() });

        const infoTable = document.querySelector(".info-table");
        if (infoTable) {
            sections.push({ title: "", table: infoTable });
        }

        const qsTable = document.querySelector(".qs-table");
        if (qsTable) {
            sections.push({ title: "I. QUALIFICATION STANDARDS", table: qsTable });
        }

        const perfTable = document.querySelector(".perf-table");
        if (perfTable) {
            sections.push({ title: "II. PERFORMANCE REQUIREMENTS", table: perfTable });
        }

        const ppstTable = document.querySelector(".ppst-table");
        if (ppstTable) {
            sections.push({ title: "Summary of the Achievement of PPST Indicators", table: ppstTable });
        }

        sections.push({ title: "Performance Data", table: buildPerformanceDataTable() });

        const applicantsTable = document.querySelector(".applicants-table");
        if (applicantsTable) {
            sections.push({ title: "Applicants", table: applicantsTable });
        }

        const assessmentTable = document.querySelector(".assessment-table");
        if (assessmentTable) {
            sections.push({ title: "III. COMPARATIVE ASSESSMENT RESULT", table: assessmentTable });
        }

        const actionTables = Array.from(document.querySelectorAll(".action-table"));
        if (actionTables[0]) {
            sections.push({ title: "IV. DEPED SCHOOLS DIVISION OFFICE ACTION", table: actionTables[0] });
        }
        if (actionTables[1]) {
            sections.push({ title: "V. DEPED REGIONAL OFFICE ACTION", table: actionTables[1] });
        }

        const ws = {};
        let rowOffset = 0;
        sections.forEach((section) => {
            if (!section.table) {
                return;
            }
            const colCount = getTableColumnCount(section.table);
            if (section.title) {
                const titleTable = buildTitleTable(section.title, colCount);
                rowOffset += appendTableToSheet(ws, titleTable, rowOffset);
                rowOffset += 1;
            }

            rowOffset += appendTableToSheet(ws, section.table, rowOffset);
            rowOffset += 1;

            if (section.title === "III. COMPARATIVE ASSESSMENT RESULT") {
                rowOffset += appendTwoSignatureBlock(
                    ws,
                    rowOffset,
                    colCount,
                    "Conforme:",
                    "Teacher Applicant",
                    "Attested by:",
                    "HRMPSB Chair"
                );
                rowOffset += 1;
            }

            if (section.title === "IV. DEPED SCHOOLS DIVISION OFFICE ACTION") {
                rowOffset += appendTwoSignatureBlock(
                    ws,
                    rowOffset,
                    colCount,
                    "Evaluated by:",
                    "Administrative Officer IV (HRMO)",
                    "Certified Correct",
                    "Administrative Officer V (Admin Services)"
                );
                rowOffset += 1;
                rowOffset += appendSingleSignatureBlock(
                    ws,
                    rowOffset,
                    colCount,
                    "Recommending Approval:",
                    "Schools Division Superintendent"
                );
                rowOffset += 1;
            }

            if (section.title === "V. DEPED REGIONAL OFFICE ACTION") {
                rowOffset += appendTwoSignatureBlock(
                    ws,
                    rowOffset,
                    colCount,
                    "Evaluated by:",
                    "Teachers Credential Evaluator",
                    "Certified Correct:",
                    "Chief, Administrative Division"
                );
                rowOffset += 1;
                rowOffset += appendSingleSignatureBlock(
                    ws,
                    rowOffset,
                    colCount,
                    "Approved:",
                    "Regional Director"
                );
                rowOffset += 1;
            }
        });

        ws["!ref"] = XLSX.utils.encode_range({
            s: { r: 0, c: 0 },
            e: { r: Math.max(rowOffset - 1, 0), c: 6 },
        });

        ws["!pageSetup"] = {
            orientation: "landscape",
            fitToWidth: 1,
            fitToHeight: 0,
        };
        ws["!margins"] = {
            left: 0.3,
            right: 0.3,
            top: 0.4,
            bottom: 0.4,
            header: 0.2,
            footer: 0.2,
        };
        ws["!cols"] = [
            { wch: 18 },
            { wch: 18 },
            { wch: 20 },
            { wch: 18 },
            { wch: 22 },
            { wch: 22 },
            { wch: 14 },
        ];
        ws["!rows"] = Array.from({ length: Math.max(rowOffset, 1) }, () => ({ hpt: 18 }));

        const wb = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(wb, ws, "Reclassification Form");
        XLSX.writeFile(wb, "reclassification_form.xlsx");
    };

    const filterApplicants = () => {
        const term = applicantsSearch.value.trim().toLowerCase();
        const rows = Array.from(document.querySelectorAll("#applicants-body tr"));
        rows.forEach((row) => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(term) ? "" : "none";
        });
    };

    [...vsInputs, ...oInputs].forEach((input) => {
        input.addEventListener("input", () => {
            updateTotals();
            updateActionTables();
        });
    });
    const enforcePpstSingleSelection = (changed) => {
        if (!changed.checked) {
            return;
        }
        const parts = changed.name.split("_");
        const rowNumber = parts[2];
        const oppositeName = parts[1] === "o" ? "ppst_vs_" + rowNumber : "ppst_o_" + rowNumber;
        const opposite = document.querySelector(`input[name="${oppositeName}"]`);
        if (opposite) {
            opposite.checked = false;
        }
    };

    ppstCheckboxes.forEach((checkbox) => {
        checkbox.addEventListener("change", (event) => {
            enforcePpstSingleSelection(event.target);
            updatePerformanceFromPpst();
            saveDraft();
        });
    });
    form.addEventListener("submit", (event) => {
        clearValidationHints();
        if (!form.checkValidity() || applyValidationHints()) {
            event.preventDefault();
            form.reportValidity();
            const firstInvalid = requiredInputs.find((input) => !input.checkValidity());
            if (firstInvalid) {
                firstInvalid.focus();
            } else if (levelRadios.length) {
                const levelGroup = levelRadios[0];
                if (levelGroup) {
                    levelGroup.focus();
                }
            }
            return;
        }

        const nameValue = document.getElementById("name").value;
        const itemNumberValue = document.getElementById("item_number").value;
        if (isDuplicateApplicant(nameValue, itemNumberValue)) {
            event.preventDefault();
            validationHint.textContent = "Applicant with the same name and item number already exists.";
            validationHint.classList.add("is-visible");
            document.getElementById("name").classList.add("is-invalid");
            document.getElementById("item_number").classList.add("is-invalid");
            document.getElementById("name").focus();
            return;
        }

        if (positionSelect.value !== "" && positionSelect.selectedOptions.length) {
            const selected = positionSelect.selectedOptions[0];
            if (selected.disabled) {
                positionSelect.setCustomValidity("Select a position within 1 to 3 levels higher.");
                positionSelect.reportValidity();
                positionSelect.setCustomValidity("");
                event.preventDefault();
                return;
            }
        }

        saveDraft();
    });
    form.addEventListener("input", () => {
        clearValidationHints();
        saveDraft();
    });
    form.addEventListener("change", () => {
        clearValidationHints();
        saveDraft();
    });
    formTypeSelect.addEventListener("change", () => {
        updateFormScope();
        updatePositionOptions();
        updateAppliedOptions();
        updatePerformanceTable();
        updateQsFields();
        updateActionTables();
        saveDraft();
    });
    currentSelect.addEventListener("change", () => {
        updateAppliedOptions();
        updateActionTables();
    });
    positionSelect.addEventListener("change", () => {
        updateQsFields();
        updateActionTables();
    });
    applicantsSearch.addEventListener("input", filterApplicants);
    autofillLastButton.addEventListener("click", autofillLastApplicant);

    applicantsTable.addEventListener("click", async (event) => {
        const target = event.target;
        if (!(target instanceof HTMLElement) || !isAdminUser) {
            return;
        }

        if (target.classList.contains("edit-record")) {
            const id = parseInt(target.dataset.id || "0", 10);
            const record = evaluationRecords.find((item) => Number(item.id) === id);
            if (!record) {
                return;
            }
            applyRecordToForm(record);
            setEditingState(id);
            window.scrollTo({ top: 0, behavior: "smooth" });
            return;
        }

        if (target.classList.contains("delete-record")) {
            const id = parseInt(target.dataset.id || "0", 10);
            if (!id) {
                return;
            }
            if (!confirm("Delete this evaluation record?")) {
                return;
            }
            try {
                await deleteEvaluationRecord(id);
                if (editingEvaluationId === id) {
                    setEditingState(0);
                }
            } catch (error) {
                alert(error.message || "Failed to delete record.");
            }
        }
    });

    clearFormButton.addEventListener("click", () => {
        if (!confirm("Clear the form? This will not delete applicants.")) {
            return;
        }
        form.reset();
        setEditingState(0);
        updateAppliedOptions();
        updateQsFields();
        clearPerformance();
        clearValidationHints();
        saveDraft();
    });
    printButton.addEventListener("click", () => window.print());
    exportButton.addEventListener("click", exportExcel);

    updateFormScope();
    updatePositionOptions();
    updateAppliedOptions();
    updatePerformanceTable();
    updateQsFields();
    updatePerformanceFromPpst();
    updateTotals();
    restoreDraft();
    updateAppliedOptions();
    updateQsFields();
    updatePerformanceFromPpst();
    updateTotals();
    updateActionTables();
    setEditingState(editingEvaluationId);
    
    // Load evaluation records only for admin users
    if (isAdminUser) {
        loadEvaluationRecords();
    }
    
    const banners = Array.from(document.querySelectorAll(".banner"));
    if (banners.length) {
        setTimeout(() => {
            banners.forEach((banner) => {
                banner.style.display = "none";
            });
        }, 3000);
    }
</script>
</body>
</html>