<?php
/**
 * Admin Applicants Dashboard
 * Manage all applicants with archiving and restoration features
 * DepEd HRMPSB Evaluation System
 * PROTECTED: Admin authentication required
 */

session_start();
require_once(__DIR__ . '/../classes/DBConnection.php');
require_once(__DIR__ . '/../classes/AuthenticationHelper.php');
require_once(__DIR__ . '/../classes/ApplicantManager.php');
require_once(__DIR__ . '/../includes/banners.php');

$conn = DBConnection::getConnection();
if (!$conn) {
    die('Database connection failed');
}

// Require admin authentication
$auth = new AuthenticationHelper($conn);
$auth->requireAdmin('/admin/login');

// Get current user info
$currentUser = $auth->getCurrentUser();

$manager = new ApplicantManager($conn);

// Get initial statistics
$stats = $manager->getStatistics();
$currentTab = isset($_GET['tab']) ? $_GET['tab'] : 'active';
$searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';
$positionGroup = isset($_GET['group']) ? trim($_GET['group']) : '';
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$itemsPerPage = 25;
$offset = ($page - 1) * $itemsPerPage;

if ($currentTab === 'active') {
    $applicants = $manager->getActiveApplicants($searchTerm, $positionGroup, $itemsPerPage, $offset);
    $totalCount = $manager->getActiveApplicantsCount($searchTerm, $positionGroup);
} else {
    $applicants = $manager->getArchivedApplicants($searchTerm, $positionGroup, $itemsPerPage, $offset);
    $totalCount = $manager->getArchivedApplicantsCount($searchTerm, $positionGroup);
}

$totalPages = ceil($totalCount / $itemsPerPage);

function positionGroupSlug($group) {
    $group = strtolower(trim((string)$group));
    $group = preg_replace('/[^a-z0-9]+/', '-', $group);
    return trim($group, '-');
}

function positionGroupLabel($group) {
    $label = trim((string)$group);
    return $label !== '' ? $label : 'Unspecified';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Applicants Management - Admin Dashboard</title>
    <?php require_once(__DIR__ . '/../includes/favicon.php'); ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/banners.css">
    <link rel="stylesheet" href="../css/design-system.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f5f5;
            color: #333;
            padding: 20px;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            background: linear-gradient(135deg, #E04040 0%, #E06060 100%);
            color: white;
            padding: 30px;
            border-radius: 8px 8px 0 0;
        }

        .header h1 {
            font-size: 28px;
            margin-bottom: 10px;
        }

        .header p {
            font-size: 14px;
            opacity: 0.95;
        }

        .statistics {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 12px;
            margin: 20px 30px;
            padding: 0;
        }

        .stat-card {
            background: white;
            padding: 12px 15px;
            border-radius: 6px;
            border-left: 3px solid #E04040;
            text-align: center;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            box-shadow: 0 2px 6px rgba(0,0,0,0.15);
            transform: translateY(-2px);
        }

        .stat-card h3 {
            color: #666;
            font-size: 11px;
            margin: 0 0 6px 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
        }

        .stat-card .number {
            font-size: 22px;
            font-weight: bold;
            color: #E04040;
            line-height: 1;
        }

        .stat-card.archived {
            border-left-color: #999;
        }

        .stat-card.archived .number {
            color: #999;
        }

        @media (max-width: 768px) {
            .statistics {
                grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
                gap: 10px;
                margin: 15px 15px;
            }
            
            .stat-card {
                padding: 10px 12px;
            }
            
            .stat-card h3 {
                font-size: 10px;
                margin-bottom: 5px;
            }
            
            .stat-card .number {
                font-size: 18px;
            }
        }

        .tabs {
            display: flex;
            border-bottom: 2px solid #f0f0f0;
            margin: 0 30px;
        }

        .tab {
            padding: 15px 20px;
            cursor: pointer;
            border: none;
            background: none;
            font-size: 15px;
            font-weight: 500;
            color: #666;
            transition: all 0.3s ease;
            border-bottom: 3px solid transparent;
            margin-bottom: -2px;
        }

        .tab:hover {
            color: #E04040;
        }

        .tab.active {
            color: #E04040;
            border-bottom-color: #E04040;
        }

        .controls {
            padding: 20px 30px;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            align-items: center;
            background: #f8f9fa;
            border-bottom: 1px solid #e0e0e0;
        }

        .search-box {
            display: flex;
            gap: 10px;
            flex: 1;
            min-width: 300px;
            align-items: center;
        }

        .search-box input {
            flex: 1;
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        .search-box input:focus {
            outline: none;
            border-color: #E04040;
            box-shadow: 0 0 5px rgba(224, 64, 64, 0.3);
        }

        .search-box select {
            padding: 10px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            background: white;
            color: #333;
            font-weight: 500;
            min-width: 140px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .search-box select option {
            color: #333;
            background: white;
            padding: 8px;
        }
        
        .search-box select:hover {
            border-color: #E04040;
        }
        
        .search-box select:focus {
            outline: none;
            border-color: #E04040;
            box-shadow: 0 0 5px rgba(224, 64, 64, 0.3);
        }

        /* Searchable dropdown wrapper */
        .searchable-dropdown-wrapper {
            position: relative;
            display: inline-block;
            min-width: 140px;
        }

        .searchable-dropdown-wrapper select {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            background: white;
            color: #333;
            font-weight: 500;
            cursor: pointer;
            appearance: none;
            padding-right: 30px;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23333' stroke-width='2'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 8px center;
            background-size: 18px;
        }

        .searchable-dropdown-wrapper select:hover {
            border-color: #E04040;
        }

        .searchable-dropdown-wrapper select:focus {
            outline: none;
            border-color: #E04040;
            box-shadow: 0 0 5px rgba(224, 64, 64, 0.3);
        }

        .button-group {
            display: flex;
            gap: 10px;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .btn-primary {
            background: #E04040;
            color: white;
        }

        .btn-primary:hover {
            background: #c83030;
        }

        .btn-secondary {
            background: #f0f0f0;
            color: #333;
        }

        .btn-secondary:hover {
            background: #e0e0e0;
        }

        .btn-success {
            background: #28a745;
            color: white;
        }

        .btn-success:hover {
            background: #218838;
        }

        .btn-danger {
            background: #dc3545;
            color: white;
        }

        .btn-danger:hover {
            background: #c82333;
        }

        .btn-small {
            padding: 6px 12px;
            font-size: 12px;
        }

        .table-container {
            padding: 30px;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background: #f8f9fa;
        }

        th {
            padding: 12px;
            text-align: left;
            font-weight: 600;
            color: #333;
            border-bottom: 2px solid #e0e0e0;
            font-size: 13px;
        }

        td {
            padding: 12px;
            border-bottom: 1px solid #f0f0f0;
            font-size: 14px;
        }

        tbody tr:hover {
            background: #f8f9fa;
        }

        .checkbox {
            cursor: pointer;
        }

        .applicant-name {
            font-weight: 500;
            color: #E04040;
        }

        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }

        .badge-group-teaching {
            background: #d4edda;
            color: #155724;
        }

        .badge-group-non-teaching-level-i {
            background: #d1ecf1;
            color: #0c5460;
        }

        .badge-group-non-teaching-level-ii {
            background: #ffe8a1;
            color: #7a5b00;
        }

        .badge-group-related-teaching {
            background: #e7d9f9;
            color: #4b1f7a;
        }

        .badge-group-higher-teaching {
            background: #f8d7da;
            color: #721c24;
        }

        .badge-group-school-administration {
            background: #fff3cd;
            color: #856404;
        }

        .badge-archived {
            background: #e2e3e5;
            color: #383d41;
        }

        .timestamp {
            color: #999;
            font-size: 13px;
        }

        .actions {
            display: flex;
            gap: 5px;
            flex-wrap: wrap;
        }

        .pagination {
            display: flex;
            gap: 5px;
            justify-content: center;
            margin: 30px 0;
            align-items: center;
        }

        .pagination a,
        .pagination span {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            text-decoration: none;
            color: #333;
            cursor: pointer;
        }

        .pagination a:hover {
            background: #f0f0f0;
        }

        .pagination .active {
            background: #E04040;
            color: white;
            border-color: #E04040;
        }

        .pagination .disabled {
            color: #ccc;
            cursor: not-allowed;
        }

        .empty-state {
            text-align: center;
            padding: 60px 30px;
            color: #999;
        }

        .empty-state svg {
            width: 80px;
            height: 80px;
            margin-bottom: 20px;
            opacity: 0.5;
        }

        .empty-state h3 {
            font-size: 18px;
            margin-bottom: 10px;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal.show {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            max-width: 900px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            position: relative;
        }
        
        .ies-section {
            margin-bottom: 25px;
        }
        
        .ies-section h4 {
            color: #E04040;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #f0f0f0;
            font-size: 16px;
        }
        
        .ies-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 13px;
        }
        
        .ies-table th {
            background: #f8f9fa;
            padding: 10px;
            text-align: left;
            font-weight: 600;
            border: 1px solid #e0e0e0;
            font-size: 12px;
        }
        
        .ies-table td {
            padding: 10px;
            border: 1px solid #e0e0e0;
        }
        
        .ies-table tr:hover {
            background: #f8f9fa;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .info-item {
            background: #f8f9fa;
            padding: 12px;
            border-radius: 4px;
        }
        
        .info-item strong {
            display: block;
            color: #666;
            font-size: 11px;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        
        .info-item span {
            color: #333;
            font-size: 14px;
            font-weight: 500;
        }
        
        .total-score {
            background: linear-gradient(135deg, #E04040 0%, #E06060 100%);
            color: white;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            margin: 20px 0;
        }
        
        .total-score h3 {
            margin: 0 0 10px 0;
            font-size: 16px;
        }
        
        .total-score .score {
            font-size: 36px;
            font-weight: bold;
        }

        .modal-header {
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #e0e0e0;
        }

        .modal-header h2 {
            margin: 0;
            color: #333;
        }

        .modal-body {
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
            color: #333;
        }

        .form-group textarea,
        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-family: inherit;
            font-size: 14px;
        }

        .form-group textarea:focus,
        .form-group input:focus {
            outline: none;
            border-color: #E04040;
            box-shadow: 0 0 5px rgba(224, 64, 64, 0.3);
        }

        .modal-footer {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
        }

        .close-btn {
            position: absolute;
            right: 20px;
            top: 15px;
            cursor: pointer;
            font-size: 28px;
            color: #999;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: all 0.3s ease;
            line-height: 1;
        }

        .close-btn:hover {
            color: #dc3545;
            background: #f8f9fa;
            transform: scale(1.1);
        }

        .close-btn:active {
            transform: scale(0.95);
        }

        .bulk-actions {
            background: #fff3cd;
            padding: 15px 30px;
            border-top: 1px solid #e0e0e0;
            border-bottom: 1px solid #e0e0e0;
            display: none;
            align-items: center;
            gap: 15px;
        }

        .bulk-actions.show {
            display: flex;
        }

        .bulk-actions .count {
            font-weight: 500;
            color: #856404;
        }

        .loading {
            display: none;
            text-align: center;
            padding: 40px;
            color: #999;
        }

        .loading.show {
            display: block;
        }

        /* Enhanced Banner Styles */
        .banner {
            animation: slideIn 0.3s ease-in-out;
        }
        
        .banner-success {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            color: #155724;
            border-left: 5px solid #28a745;
            box-shadow: 0 4px 12px rgba(40, 167, 69, 0.2);
        }
        
        .banner-success .banner-icon {
            color: #28a745;
            font-size: 20px;
        }
        
        .banner-success .banner-close:hover {
            color: #28a745;
        }

        .banner-error {
            background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
            color: #721c24;
            border-left: 5px solid #dc3545;
            box-shadow: 0 4px 12px rgba(220, 53, 69, 0.2);
        }
        
        .banner-error .banner-icon {
            color: #dc3545;
            font-size: 20px;
        }
        
        .banner-error .banner-close:hover {
            color: #dc3545;
        }

        .history-item {
            padding: 10px;
            margin-bottom: 10px;
            background: #f8f9fa;
            border-radius: 4px;
            border-left: 3px solid #E04040;
        }

        .history-action {
            font-weight: 500;
            color: #333;
        }

        .history-date {
            font-size: 12px;
            color: #999;
        }

        .archive-reason {
            background: #f0f0f0;
            padding: 10px;
            border-radius: 4px;
            margin-top: 5px;
            font-size: 13px;
            color: #666;
        }

        @media (max-width: 768px) {
            .statistics {
                grid-template-columns: 1fr;
            }

            .controls {
                flex-direction: column;
            }

            .search-box {
                flex-direction: column;
                min-width: unset;
            }

            .search-box input,
            .search-box select {
                width: 100%;
            }

            .table-container {
                padding: 15px;
            }

            th, td {
                padding: 8px;
                font-size: 12px;
            }

            .actions {
                flex-direction: column;
            }

            .btn-small {
                width: 100%;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div style="display: flex; justify-content: space-between; align-items: center; width: 100%; margin-bottom: 10px;">
                <div>
                    <h1>Applicants Management Dashboard</h1>
                    <p>Manage all applicants efficiently with archiving and restoration features</p>
                </div>
                <div style="text-align: right;">
                    <p style="margin: 0 0 8px 0; font-size: 12px; color: rgba(255,255,255,0.9);">
                        Logged in as: <strong><?php echo htmlspecialchars($currentUser['full_name'] ?? $currentUser['username']); ?></strong>
                    </p>
                    <a href="logout.php" class="btn" style="background: rgba(255,255,255,0.2); color: white; padding: 6px 12px; font-size: 12px; text-decoration: none;">Logout</a>
                </div>
            </div>
        </div>

        <?php displayBannerFromSession(); ?>

        <div class="statistics">
            <div class="stat-card">
                <h3>Total Applicants</h3>
                <div class="number"><?php echo $stats['total']; ?></div>
            </div>
            <div class="stat-card">
                <h3>Active Applicants</h3>
                <div class="number"><?php echo $stats['active_total']; ?></div>
            </div>
            <div class="stat-card archived">
                <h3>Archived Applicants</h3>
                <div class="number"><?php echo $stats['archived_total']; ?></div>
            </div>
            <div class="stat-card">
                <h3>Teaching</h3>
                <div class="number"><?php echo isset($stats['active_by_group']['TEACHING']) ? $stats['active_by_group']['TEACHING'] : 0; ?></div>
            </div>
            <div class="stat-card">
                <h3>Non-Teaching I</h3>
                <div class="number"><?php echo isset($stats['active_by_group']['NON-TEACHING LEVEL I']) ? $stats['active_by_group']['NON-TEACHING LEVEL I'] : 0; ?></div>
            </div>
            <div class="stat-card">
                <h3>Non-Teaching II</h3>
                <div class="number"><?php echo isset($stats['active_by_group']['NON-TEACHING LEVEL II']) ? $stats['active_by_group']['NON-TEACHING LEVEL II'] : 0; ?></div>
            </div>
            <div class="stat-card">
                <h3>Related Teaching</h3>
                <div class="number"><?php echo isset($stats['active_by_group']['RELATED TEACHING']) ? $stats['active_by_group']['RELATED TEACHING'] : 0; ?></div>
            </div>
            <div class="stat-card">
                <h3>Higher Teaching</h3>
                <div class="number"><?php echo isset($stats['active_by_group']['HIGHER TEACHING']) ? $stats['active_by_group']['HIGHER TEACHING'] : 0; ?></div>
            </div>
            <div class="stat-card">
                <h3>School Admin</h3>
                <div class="number"><?php echo isset($stats['active_by_group']['SCHOOL ADMINISTRATION']) ? $stats['active_by_group']['SCHOOL ADMINISTRATION'] : 0; ?></div>
            </div>
        </div>

        <div class="tabs">
            <button class="tab <?php echo $currentTab === 'active' ? 'active' : ''; ?>" 
                    onclick="switchTab('active')">
                <i class="fas fa-clipboard-list" style="margin-right: 8px;"></i>Active Applicants (<?php echo $stats['active_total']; ?>)
            </button>
            <button class="tab <?php echo $currentTab === 'archived' ? 'active' : ''; ?>" 
                    onclick="switchTab('archived')">
                <i class="fas fa-archive" style="margin-right: 8px;"></i>Archived Applicants (<?php echo $stats['archived_total']; ?>)
            </button>
        </div>

        <div class="controls">
            <form class="search-box" method="get" id="searchForm">
                <input type="hidden" name="tab" value="<?php echo htmlspecialchars($currentTab); ?>">
                <input type="text" name="search" placeholder="Search applicant name..." 
                       value="<?php echo htmlspecialchars($searchTerm); ?>"
                       aria-label="Search applicants">
                <div class="searchable-dropdown-wrapper">
                    <select name="group" id="groupFilter" aria-label="Filter by group">
                        <option value="">All Groups</option>
                        <option value="TEACHING" <?php echo $positionGroup === 'TEACHING' ? 'selected' : ''; ?>>Teaching</option>
                        <option value="NON-TEACHING LEVEL I" <?php echo $positionGroup === 'NON-TEACHING LEVEL I' ? 'selected' : ''; ?>>Non-Teaching Level I</option>
                        <option value="NON-TEACHING LEVEL II" <?php echo $positionGroup === 'NON-TEACHING LEVEL II' ? 'selected' : ''; ?>>Non-Teaching Level II</option>
                        <option value="RELATED TEACHING" <?php echo $positionGroup === 'RELATED TEACHING' ? 'selected' : ''; ?>>Related Teaching</option>
                        <option value="HIGHER TEACHING" <?php echo $positionGroup === 'HIGHER TEACHING' ? 'selected' : ''; ?>>Higher Teaching</option>
                        <option value="SCHOOL ADMINISTRATION" <?php echo $positionGroup === 'SCHOOL ADMINISTRATION' ? 'selected' : ''; ?>>School Administration</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-secondary"><i class="fas fa-search" style="margin-right: 6px;"></i>Search</button>
            </form>
            <div class="button-group">
                <?php if ($currentTab === 'active' && count($applicants) > 0): ?>
                    <button class="btn btn-danger" onclick="showBulkArchiveModal()"><i class="fas fa-archive" style="margin-right: 6px;"></i>Bulk Archive</button>
                <?php elseif ($currentTab === 'archived' && count($applicants) > 0): ?>
                    <button class="btn btn-success" onclick="showBulkRestoreModal()"><i class="fas fa-undo" style="margin-right: 6px;"></i>Bulk Restore</button>
                <?php endif; ?>
                <a href="../admin/applicants.php" class="btn btn-secondary"><i class="fas fa-sync" style="margin-right: 6px;"></i>Reset</a>
            </div>
        </div>

        <div class="bulk-actions" id="bulkActions">
            <span class="count" id="bulkCount">0 selected</span>
            <button class="btn btn-danger btn-small" onclick="performBulkAction()">
                <?php echo $currentTab === 'active' ? '<i class="fas fa-archive" style="margin-right: 4px;"></i>Archive Selected' : '<i class="fas fa-undo" style="margin-right: 4px;"></i>Restore Selected'; ?>
            </button>
            <button class="btn btn-secondary btn-small" onclick="clearSelection()"><i class="fas fa-times" style="margin-right: 4px;"></i>Clear Selection</button>
        </div>

        <div class="table-container">
            <div class="loading" id="loading">
                <p>Loading applicants...</p>
            </div>

            <?php if (empty($applicants)): ?>
                <div class="empty-state">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                    </svg>
                    <h3>No applicants found</h3>
                    <p><?php echo $currentTab === 'active' ? 'No active applicants yet.' : 'No archived applicants yet.'; ?></p>
                </div>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th style="width: 40px;">
                                <input type="checkbox" id="selectAll" onchange="toggleSelectAll(this)">
                            </th>
                            <th>Name</th>
                            <th>Position Group</th>
                            <th>Position</th>
                            <?php if ($currentTab === 'archived'): ?>
                                <th>Archived Date</th>
                                <th>Reason</th>
                            <?php else: ?>
                                <th>Created Date</th>
                            <?php endif; ?>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($applicants as $applicant): ?>
                            <tr>
                                <td>
                                    <input type="checkbox" class="checkbox applicant-check" value="<?php echo $applicant['id']; ?>" onchange="updateBulkCount()">
                                </td>
                                <td class="applicant-name"><?php echo htmlspecialchars($applicant['name']); ?></td>
                                <td>
                                    <span class="badge badge-group-<?php echo positionGroupSlug($applicant['position_group']); ?>">
                                        <?php echo htmlspecialchars(positionGroupLabel($applicant['position_group'])); ?>
                                    </span>
                                </td>
                                <td><?php echo htmlspecialchars($applicant['position_name'] ?? '-'); ?></td>
                                <td class="timestamp">
                                    <?php 
                                    $dateField = $currentTab === 'archived' ? 'archived_at' : 'created_at';
                                    echo date('M d, Y', strtotime($applicant[$dateField])); 
                                    ?>
                                </td>
                                <?php if ($currentTab === 'archived'): ?>
                                    <td>
                                        <?php if (!empty($applicant['archive_reason'])): ?>
                                            <span class="archive-reason"><?php echo htmlspecialchars($applicant['archive_reason']); ?></span>
                                        <?php else: ?>
                                            <span style="color: #999;">-</span>
                                        <?php endif; ?>
                                    </td>
                                <?php endif; ?>
                                <td>
                                    <div class="actions">
                                        <button class="btn btn-secondary btn-small" onclick="viewDetails(<?php echo $applicant['id']; ?>)"><i class="fas fa-eye" style="margin-right: 4px;"></i>View</button>
                                        <?php if ($currentTab === 'active'): ?>
                                            <button class="btn btn-danger btn-small" 
                                                data-id="<?php echo $applicant['id']; ?>" 
                                                data-name="<?php echo htmlspecialchars($applicant['name'], ENT_QUOTES); ?>"
                                                onclick="archiveApplicant(this.dataset.id, this.dataset.name)">
                                                <i class="fas fa-archive" style="margin-right: 4px;"></i>Archive
                                            </button>
                                        <?php else: ?>
                                            <button class="btn btn-success btn-small" 
                                                data-id="<?php echo $applicant['id']; ?>" 
                                                data-name="<?php echo htmlspecialchars($applicant['name'], ENT_QUOTES); ?>"
                                                onclick="restoreApplicant(this.dataset.id, this.dataset.name)">
                                                <i class="fas fa-undo" style="margin-right: 4px;"></i>Restore
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <?php if ($totalPages > 1): ?>
                    <div class="pagination">
                        <?php if ($page > 1): ?>
                            <a href="?tab=<?php echo urlencode($currentTab); ?>&search=<?php echo urlencode($searchTerm); ?>&group=<?php echo urlencode($positionGroup); ?>&page=1">First</a>
                            <a href="?tab=<?php echo urlencode($currentTab); ?>&search=<?php echo urlencode($searchTerm); ?>&group=<?php echo urlencode($positionGroup); ?>&page=<?php echo $page - 1; ?>">← Previous</a>
                        <?php endif; ?>

                        <?php
                        $start = max(1, $page - 2);
                        $end = min($totalPages, $page + 2);
                        
                        for ($i = $start; $i <= $end; $i++):
                        ?>
                            <?php if ($i == $page): ?>
                                <span class="active"><?php echo $i; ?></span>
                            <?php else: ?>
                                <a href="?tab=<?php echo urlencode($currentTab); ?>&search=<?php echo urlencode($searchTerm); ?>&group=<?php echo urlencode($positionGroup); ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                            <?php endif; ?>
                        <?php endfor; ?>

                        <?php if ($page < $totalPages): ?>
                            <a href="?tab=<?php echo urlencode($currentTab); ?>&search=<?php echo urlencode($searchTerm); ?>&group=<?php echo urlencode($positionGroup); ?>&page=<?php echo $page + 1; ?>">Next →</a>
                            <a href="?tab=<?php echo urlencode($currentTab); ?>&search=<?php echo urlencode($searchTerm); ?>&group=<?php echo urlencode($positionGroup); ?>&page=<?php echo $totalPages; ?>">Last</a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- Archive Modal -->
    <div id="archiveModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeModal('archiveModal')">&times;</span>
            <div class="modal-header">
                <h2>Archive Applicant</h2>
            </div>
            <div class="modal-body">
                <p id="archiveApplicantName" style="margin-bottom: 20px; font-weight: 500;"></p>
                <div class="form-group">
                    <label for="archiveReason">Reason for archiving (optional):</label>
                    <textarea id="archiveReason" placeholder="e.g., Hired, Withdrew, etc." rows="3"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="closeModal('archiveModal')">Cancel</button>
                <button class="btn btn-danger" onclick="confirmArchive()"><i class="fas fa-archive" style="margin-right: 6px;"></i>Archive</button>
            </div>
        </div>
    </div>

    <!-- Restore Modal -->
    <div id="restoreModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeModal('restoreModal')">&times;</span>
            <div class="modal-header">
                <h2>Restore Applicant</h2>
            </div>
            <div class="modal-body">
                <p id="restoreApplicantName" style="margin-bottom: 20px; font-weight: 500;"></p>
                <p style="color: #666; font-size: 14px;">This applicant will be moved back to the active list.</p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="closeModal('restoreModal')">Cancel</button>
                <button class="btn btn-success" onclick="confirmRestore()"><i class="fas fa-undo" style="margin-right: 6px;"></i>Restore</button>
            </div>
        </div>
    </div>

    <!-- Bulk Archive Modal -->
    <div id="bulkArchiveModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeModal('bulkArchiveModal')">&times;</span>
            <div class="modal-header">
                <h2>Bulk Archive Applicants</h2>
            </div>
            <div class="modal-body">
                <p id="bulkArchiveCount" style="margin-bottom: 20px; font-weight: 500;"></p>
                <div class="form-group">
                    <label for="bulkArchiveReason">Reason for archiving (optional):</label>
                    <textarea id="bulkArchiveReason" placeholder="e.g., End of batch processing, etc." rows="3"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="closeModal('bulkArchiveModal')">Cancel</button>
                <button class="btn btn-danger" onclick="confirmBulkArchive()"><i class="fas fa-archive" style="margin-right: 6px;"></i>Archive All</button>
            </div>
        </div>
    </div>

    <!-- Bulk Restore Modal -->
    <div id="bulkRestoreModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeModal('bulkRestoreModal')">&times;</span>
            <div class="modal-header">
                <h2>Bulk Restore Applicants</h2>
            </div>
            <div class="modal-body">
                <p id="bulkRestoreCount" style="margin-bottom: 20px; font-weight: 500;"></p>
                <p style="color: #666; font-size: 14px;">These applicants will be moved back to the active list.</p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="closeModal('bulkRestoreModal')">Cancel</button>
                <button class="btn btn-success" onclick="confirmBulkRestore()"><i class="fas fa-undo" style="margin-right: 6px;"></i>Restore All</button>
            </div>
        </div>
    </div>

    <!-- Details Modal -->
    <div id="detailsModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeModal('detailsModal')">&times;</span>
            <div class="modal-header">
                <h2>Applicant Details</h2>
            </div>
            <div class="modal-body" id="detailsModalBody">
                <p>Loading...</p>
            </div>
        </div>
    </div>

    <script>
        let currentArchiveApplicantId = null;
        let currentRestoreApplicantId = null;
        const currentTab = '<?php echo addslashes($currentTab); ?>';

        function switchTab(tab) {
            const searchParams = new URLSearchParams();
            searchParams.set('tab', tab);
            const search = document.querySelector('input[name="search"]').value;
            const group = document.querySelector('select[name="group"]').value;
            if (search) searchParams.set('search', search);
            if (group) searchParams.set('group', group);
            window.location.search = '?' + searchParams.toString();
        }

        function toggleSelectAll(checkbox) {
            const checkboxes = document.querySelectorAll('.applicant-check');
            checkboxes.forEach(cb => cb.checked = checkbox.checked);
            updateBulkCount();
        }

        function updateBulkCount() {
            const checked = document.querySelectorAll('.applicant-check:checked').length;
            const bulkActions = document.getElementById('bulkActions');
            const bulkCount = document.getElementById('bulkCount');
            
            if (checked > 0) {
                bulkActions.classList.add('show');
                bulkCount.textContent = checked + ' selected';
            } else {
                bulkActions.classList.remove('show');
            }
        }

        function clearSelection() {
            document.querySelectorAll('.applicant-check').forEach(cb => cb.checked = false);
            document.getElementById('selectAll').checked = false;
            updateBulkCount();
        }

        function slugifyPositionGroup(group) {
            return (group || '')
                .toLowerCase()
                .replace(/[^a-z0-9]+/g, '-')
                .replace(/(^-|-$)/g, '');
        }

        function formatPositionGroupLabel(group) {
            const label = (group || '').trim();
            return label !== '' ? label : 'Unspecified';
        }

        function archiveApplicant(id, name) {
            // Convert ID to integer to ensure it's valid
            const applicantId = parseInt(id, 10);
            
            if (isNaN(applicantId) || applicantId <= 0) {
                console.error('Invalid applicant ID:', id);
                showBanner('error', 'Invalid applicant ID');
                return;
            }
            
            currentArchiveApplicantId = applicantId;
            document.getElementById('archiveApplicantName').textContent = `Archive "${name}"?`;
            openModal('archiveModal');
        }

        function restoreApplicant(id, name) {
            // Convert ID to integer to ensure it's valid
            const applicantId = parseInt(id, 10);
            
            if (isNaN(applicantId) || applicantId <= 0) {
                console.error('Invalid applicant ID:', id);
                showBanner('error', 'Invalid applicant ID');
                return;
            }
            
            currentRestoreApplicantId = applicantId;
            document.getElementById('restoreApplicantName').textContent = `Restore "${name}"?`;
            openModal('restoreModal');
        }

        function confirmArchive() {
            if (!currentArchiveApplicantId) return;
            
            const reason = document.getElementById('archiveReason').value;
            performArchive(currentArchiveApplicantId, reason);
            closeModal('archiveModal');
        }

        function confirmRestore() {
            if (!currentRestoreApplicantId) return;
            
            performRestore(currentRestoreApplicantId);
            closeModal('restoreModal');
        }

        function showBulkArchiveModal() {
            const count = document.querySelectorAll('.applicant-check:checked').length;
            if (count === 0) {
                alert('Please select at least one applicant');
                return;
            }
            document.getElementById('bulkArchiveCount').textContent = 'Archive ' + count + ' applicant(s)?';
            openModal('bulkArchiveModal');
        }

        function showBulkRestoreModal() {
            const count = document.querySelectorAll('.applicant-check:checked').length;
            if (count === 0) {
                alert('Please select at least one applicant');
                return;
            }
            document.getElementById('bulkRestoreCount').textContent = 'Restore ' + count + ' applicant(s)?';
            openModal('bulkRestoreModal');
        }

        function confirmBulkArchive() {
            const reason = document.getElementById('bulkArchiveReason').value;
            performBulkAction('archive', reason);
            closeModal('bulkArchiveModal');
        }

        function confirmBulkRestore() {
            performBulkAction('restore');
            closeModal('bulkRestoreModal');
        }

        function performArchive(id, reason) {
            fetch('../api/archive_applicant.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    applicant_id: id,
                    reason: reason,
                    archived_by: 'Admin'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showBanner('success', data.message);
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showBanner('error', data.message || 'Failed to archive applicant');
                }
            })
            .catch(error => {
                showBanner('error', 'Error: ' + error.message);
            });
        }

        function performRestore(id) {
            fetch('../api/restore_applicant.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    applicant_id: id,
                    restored_by: 'Admin'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showBanner('success', data.message);
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showBanner('error', data.message || 'Failed to restore applicant');
                }
            })
            .catch(error => {
                showBanner('error', 'Error: ' + error.message);
            });
        }

        function performBulkAction(action = null, reason = '') {
            const checked = document.querySelectorAll('.applicant-check:checked');
            const ids = Array.from(checked).map(cb => parseInt(cb.value));
            
            if (ids.length === 0) {
                alert('Please select at least one applicant');
                return;
            }

            const actionType = action || (currentTab === 'active' ? 'archive' : 'restore');
            
            if (actionType === 'archive') {
                fetch('../api/bulk_archive_applicants.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        applicant_ids: ids,
                        reason: reason,
                        archived_by: 'Admin'
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showBanner('success', data.message);
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        showBanner('error', data.message || 'Failed to archive applicants');
                    }
                })
                .catch(error => {
                    showBanner('error', 'Error: ' + error.message);
                });
            } else {
                // Restore one by one
                let restored = 0;
                ids.forEach((id, index) => {
                    setTimeout(() => {
                        fetch('../api/restore_applicant.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                applicant_id: id,
                                restored_by: 'Admin'
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            restored++;
                            if (restored === ids.length) {
                                showBanner('success', 'Restored ' + restored + ' applicant(s)');
                                setTimeout(() => location.reload(), 1500);
                            }
                        });
                    }, index * 100);
                });
            }
        }

        function viewDetails(id) {
            console.log('=== START viewDetails Function ===');
            console.log('Button clicked for applicant ID:', id);
            console.log('Step 1: Getting modal body element');
            
            const modalBody = document.getElementById('detailsModalBody');
            if (!modalBody) {
                console.error('ERROR: Modal body element not found!');
                return;
            }
            console.log('Step 2: Modal body element found:', modalBody);
            
            console.log('Step 3: Setting loading state');
            modalBody.innerHTML = '<div style="text-align: center; padding: 40px; color: #999;"><i class="fas fa-spinner fa-spin" style="font-size: 32px; margin-bottom: 15px;"></i><p>Loading evaluation details...</p></div>';
            
            console.log('Step 4: Opening modal');
            openModal('detailsModal');
            
            // Check if modal actually has show class
            const modal = document.getElementById('detailsModal');
            console.log('Modal element:', modal);
            console.log('Modal classes:', modal ? modal.className : 'NOT FOUND');
            
            console.log('Step 5: Calling API for applicant details with ID:', id);
            const apiUrl = '../api/get_applicant_evaluation.php?id=' + id;
            console.log('API URL:', apiUrl);
            
            fetch(apiUrl)
            .then(response => {
                console.log('Step 6: API Response received');
                console.log('API Response Status:', response.status);
                console.log('API Response OK:', response.ok);
                return response.json();
            })
            .then(data => {
                console.log('Step 7: API Response parsed as JSON');
                console.log('API Response Data:', data);
                console.log('Response success:', data.success);
                console.log('Response applicant:', data.applicant);
                console.log('Response evaluation:', data.evaluation);
                
                if (data.success) {
                    console.log('Step 8: API returned success. Building HTML...');
                    let html = `
                        <div class="ies-section">
                            <h3 style="margin-bottom: 15px; color: #E04040;">
                                <i class="fas fa-user-circle" style="margin-right: 10px;"></i>
                                ${data.applicant.name}
                            </h3>
                            
                            <div class="info-grid">
                                <div class="info-item">
                                    <strong>Position Group</strong>
                                    <span class="badge badge-group-${slugifyPositionGroup(data.applicant.position_group)}">${formatPositionGroupLabel(data.applicant.position_group)}</span>
                                </div>
                                <div class="info-item">
                                    <strong>Position Applied</strong>
                                    <span>${data.applicant.position_name || 'Not specified'}</span>
                                </div>
                                <div class="info-item">
                                    <strong>Status</strong>
                                    <span class="badge ${data.applicant.archive_status === 'archived' ? 'badge-archived' : 'badge-group-' + slugifyPositionGroup(data.applicant.position_group)}">
                                        ${data.applicant.archive_status === 'archived' ? 'Archived' : 'Active'}
                                    </span>
                                </div>
                                <div class="info-item">
                                    <strong>Created Date</strong>
                                    <span>${new Date(data.applicant.created_at).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' })}</span>
                                </div>
                            </div>
                        </div>
                    `;
                    
                    console.log('Step 9: Checking if evaluation exists...');
                    console.log('Has evaluation:', !!data.evaluation);
                    console.log('Evaluation data:', data.evaluation);
                    
                    // Display evaluation data if available
                    if (data.evaluation) {
                        console.log('Step 10: Building IES table with evaluation data');
                        html += `
                            <div class="ies-section">
                                <h4><i class="fas fa-clipboard-check" style="margin-right: 8px;"></i>Individual Evaluation Sheet (IES)</h4>
                                
                                <div class="info-grid" style="margin-bottom: 20px;">
                                    <div class="info-item">
                                        <strong>Evaluation Date</strong>
                                        <span>${new Date(data.evaluation.evaluation_date).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' })}</span>
                                    </div>
                                    <div class="info-item">
                                        <strong>Evaluator</strong>
                                        <span>${data.evaluation.evaluator_name || 'System'}</span>
                                    </div>
                                    <div class="info-item">
                                        <strong>Status</strong>
                                        <span class="badge" style="background: ${data.evaluation.status === 'approved' ? '#28a745' : data.evaluation.status === 'rejected' ? '#dc3545' : '#ffc107'}; color: white;">
                                            ${data.evaluation.status.charAt(0).toUpperCase() + data.evaluation.status.slice(1)}
                                        </span>
                                    </div>
                                    <div class="info-item">
                                        <strong>Position Evaluated</strong>
                                        <span>${data.evaluation.position_name || 'Not specified'}</span>
                                    </div>
                                </div>
                        `;
                        
                        // Display evaluation details (criteria breakdown)
                        console.log('Step 11: Checking evaluation details count:', data.details ? data.details.length : 0);
                        if (data.details && data.details.length > 0) {
                            console.log('Step 12: Building criteria table with', data.details.length, 'criteria');
                            html += `
                                <table class="ies-table">
                                    <thead>
                                        <tr>
                                            <th style="width: 25%;">Criterion</th>
                                            <th>Applicant Qualification</th>
                                            <th style="width: 10%; text-align: center;">Level</th>
                                            <th>Baseline</th>
                                            <th style="width: 10%; text-align: center;">Increment</th>
                                            <th style="width: 10%; text-align: center;">Weight</th>
                                            <th style="width: 10%; text-align: center;">Points</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                            `;
                            
                            data.details.forEach((detail, idx) => {
                                console.log('  Detail', idx + 1, ':', detail.criterion, '- Score:', detail.final_score);
                                html += `
                                    <tr>
                                        <td style="font-weight: 600; color: #555;">${detail.criterion}</td>
                                        <td>${detail.applicant_qualification || '-'}</td>
                                        <td style="text-align: center;">${detail.applicant_level}</td>
                                        <td>${detail.baseline_qualification || '-'}</td>
                                        <td style="text-align: center;">${detail.increment}</td>
                                        <td style="text-align: center;">${detail.weight}</td>
                                        <td style="text-align: center; font-weight: 600; color: #E04040;">${parseFloat(detail.final_score).toFixed(2)}</td>
                                    </tr>
                                `;
                            });
                            
                            html += `
                                    </tbody>
                                </table>
                            `;
                        }
                        
                        // Display total score
                        console.log('Step 13: Adding total score:', data.evaluation.total_score);
                        html += `
                                <div class="total-score">
                                    <h3>TOTAL EVALUATION SCORE</h3>
                                    <div class="score">${parseFloat(data.evaluation.total_score).toFixed(2)} / 100</div>
                                </div>
                            </div>
                        `;
                        
                        // Display notes if available
                        if (data.evaluation.notes) {
                            console.log('Step 14: Adding notes section');
                            html += `
                                <div class="ies-section">
                                    <h4><i class="fas fa-sticky-note" style="margin-right: 8px;"></i>Notes</h4>
                                    <div style="background: #f8f9fa; padding: 15px; border-radius: 4px; border-left: 4px solid #E04040;">
                                        ${data.evaluation.notes}
                                    </div>
                                </div>
                            `;
                        }
                    } else if (data.qualifications) {
                        console.log('Step 10: No evaluation found, showing qualifications summary instead');
                        // Show qualifications if no evaluation exists yet
                        html += `
                            <div class="ies-section">
                                <h4><i class="fas fa-user-graduate" style="margin-right: 8px;"></i>Qualifications Summary</h4>
                                <div class="info-grid">
                                    <div class="info-item">
                                        <strong>Education</strong>
                                        <span>${data.qualifications.education_degree || 'Not specified'}</span>
                                    </div>
                                    <div class="info-item">
                                        <strong>Training Hours</strong>
                                        <span>${data.qualifications.training_hours || 0} hours</span>
                                    </div>
                                    <div class="info-item">
                                        <strong>Experience</strong>
                                        <span>${data.qualifications.experience_months ? (data.qualifications.experience_months / 12).toFixed(1) + ' years' : 'Not specified'}</span>
                                    </div>
                                    <div class="info-item">
                                        <strong>Performance Rating</strong>
                                        <span>${data.qualifications.performance_rating || 'N/A'}</span>
                                    </div>
                                </div>
                                <div style="background: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; border-radius: 4px; margin-top: 15px;">
                                    <strong style="color: #856404;"><i class="fas fa-info-circle" style="margin-right: 6px;"></i>No Evaluation</strong>
                                    <p style="color: #856404; margin: 5px 0 0 0; font-size: 13px;">This applicant has not been evaluated yet. Complete an evaluation to generate the Individual Evaluation Sheet (IES).</p>
                                </div>
                            </div>
                        `;
                    } else {
                        console.log('Step 10: No evaluation or qualifications found');
                        html += `
                            <div style="background: #f8d7da; border-left: 4px solid #dc3545; padding: 15px; border-radius: 4px; margin-top: 15px;">
                                <strong style="color: #721c24;"><i class="fas fa-exclamation-triangle" style="margin-right: 6px;"></i>No Data Available</strong>
                                <p style="color: #721c24; margin: 5px 0 0 0; font-size: 13px;">No evaluation or qualification data found for this applicant.</p>
                            </div>
                        `;
                    }
                    
                    // Display archive history
                    if (data.history && data.history.length > 0) {
                        console.log('Step 15: Adding archive history with', data.history.length, 'entries');
                        html += `
                            <div class="ies-section">
                                <h4><i class="fas fa-history" style="margin-right: 8px;"></i>Archive History</h4>
                                ${data.history.map(h => `
                                    <div class="history-item">
                                        <div class="history-action">${h.action === 'archived' ? '<i class="fas fa-archive" style="margin-right: 6px;"></i>Archived' : '<i class="fas fa-undo" style="margin-right: 6px;"></i>Restored'}</div>
                                        <div class="history-date">${new Date(h.archived_at).toLocaleString('en-US', { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' })}</div>
                                        ${h.reason ? `<div style="margin-top: 5px; font-size: 12px; color: #666;">${h.reason}</div>` : ''}
                                        ${h.archived_by ? `<div style="margin-top: 3px; font-size: 11px; color: #999;">By: ${h.archived_by}</div>` : ''}
                                    </div>
                                `).join('')}
                            </div>
                        `;
                    }
                    
                    console.log('Step 16: HTML generated, length:', html.length);
                    console.log('Step 17: Inserting HTML into modal body');
                    modalBody.innerHTML = html;
                    
                    console.log('Step 18: Verifying HTML was inserted');
                    console.log('Modal body innerHTML length:', modalBody.innerHTML.length);
                    console.log('Modal body innerHTML preview:', modalBody.innerHTML.substring(0, 200) + '...');
                    console.log('=== viewDetails Function SUCCESS ===');
                } else {
                    console.log('Step 8: API returned failure');
                    console.log('Error message:', data.message);
                    modalBody.innerHTML = `
                        <div style="background: #f8d7da; border-left: 4px solid #dc3545; padding: 20px; border-radius: 4px;">
                            <strong style="color: #721c24;"><i class="fas fa-exclamation-circle" style="margin-right: 6px;"></i>Error</strong>
                            <p style="color: #721c24; margin: 10px 0 0 0;">${data.message || 'Failed to load applicant details'}</p>
                        </div>
                    `;
                }
            })
            .catch(error => {
                console.error('=== viewDetails Function ERROR ===');
                console.error('Error type:', error.name);
                console.error('Error message:', error.message);
                console.error('Error stack:', error.stack);
                modalBody.innerHTML = `
                    <div style="background: #f8d7da; border-left: 4px solid #dc3545; padding: 20px; border-radius: 4px;">
                        <strong style="color: #721c24;"><i class="fas fa-exclamation-circle" style="margin-right: 6px;"></i>Connection Error</strong>
                        <p style="color: #721c24; margin: 10px 0 0 0;">Error: ${error.message}</p>
                    </div>
                `;
            });
        }

        function openModal(modalId) {
            document.getElementById(modalId).classList.add('show');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.remove('show');
            if (modalId === 'archiveModal') {
                document.getElementById('archiveReason').value = '';
                currentArchiveApplicantId = null;
            } else if (modalId === 'restoreModal') {
                currentRestoreApplicantId = null;
            } else if (modalId === 'bulkArchiveModal') {
                document.getElementById('bulkArchiveReason').value = '';
            }
        }

        function showBanner(type, message) {
            // Create banner element
            const bannerDiv = document.createElement('div');
            bannerDiv.className = `banner banner-${type}`;
            
            // Determine icon based on type
            let iconClass = 'fa-info-circle';
            if (type === 'success') iconClass = 'fa-check-circle';
            else if (type === 'error') iconClass = 'fa-exclamation-circle';
            else if (type === 'warning') iconClass = 'fa-exclamation-triangle';
            
            bannerDiv.innerHTML = `
                <div class="banner-content">
                    <span class="banner-icon"><i class="fas ${iconClass}"></i></span>
                    <span class="banner-text">${message}</span>
                    <button class="banner-close" onclick="this.parentElement.parentElement.style.display='none';" aria-label="Close message">&times;</button>
                </div>
            `;
            
            // Add to page before first content
            const container = document.querySelector('.container') || document.body;
            container.insertBefore(bannerDiv, container.firstChild);
            
            // Auto-hide success messages
            if (type === 'success') {
                setTimeout(() => {
                    bannerDiv.style.display = 'none';
                }, 5000);
            }
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                event.target.classList.remove('show');
            }
        }

        // Initialize
        updateBulkCount();
    </script>
</body>
</html>
