<?php
/**
 * Modern Single-Page Admin Dashboard
 * Navigation: Applicants Management | Saved Drafts | Return to Main
 * PROTECTED: Admin authentication required
 */

ini_set('session.cookie_httponly', '1');
ini_set('session.cookie_samesite', 'Lax');
ini_set('session.gc_maxlifetime', '28800');

session_start();
require_once(__DIR__ . '/../classes/DBConnection.php');
require_once(__DIR__ . '/../classes/AuthenticationHelper.php');
require_once(__DIR__ . '/../classes/ApplicantManager.php');
require_once(__DIR__ . '/../includes/banners.php');

$conn = DBConnection::getConnection();
if (!$conn) die('Database connection failed');

// Require admin authentication
$auth = new AuthenticationHelper($conn);
$auth->requireAdmin('login.php');

// Get current user info
$currentUser = $auth->getCurrentUser();
$manager = new ApplicantManager($conn);
$stats = $manager->getStatistics();

// Initial load - get active applicants
$searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';
$positionGroup = isset($_GET['group']) ? trim($_GET['group']) : '';
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$itemsPerPage = 20;
$offset = ($page - 1) * $itemsPerPage;

$applicants = $manager->getActiveApplicants($searchTerm, $positionGroup, $itemsPerPage, $offset);
$totalCount = $manager->getActiveApplicantsCount($searchTerm, $positionGroup);
$totalPages = ceil($totalCount / $itemsPerPage);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - DepEd HRMPSB</title>
    <?php require_once(__DIR__ . '/../includes/favicon.php'); ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/banners.css">
    <style>
        :root {
            --admin-primary: #055489;
            --admin-secondary: #333;
            --sidebar-width: 260px;
            --header-height: 70px;
            --transition-speed: 0.3s;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
            background: #f5f5f5;
            color: #333;
            overflow-x: hidden;
        }

        /* Dashboard Layout */
        .dashboard-layout {
            display: flex;
            height: 100vh;
        }

        /* Sidebar Navigation */
        .sidebar {
            width: var(--sidebar-width);
            background: linear-gradient(180deg, #055489 0%, #044073 100%);
            color: white;
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
            left: 0;
            top: 0;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
            z-index: 1000;
            transition: transform var(--transition-speed) ease;
        }

        .sidebar-header {
            padding: 20px;
            border-bottom: 1px solid rgba(255,255,255,0.2);
        }

        .sidebar-header h1 {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 5px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sidebar-header p {
            font-size: 12px;
            opacity: 0.9;
        }

        .sidebar-nav {
            flex: 1;
            padding: 20px 0;
            overflow-y: auto;
        }

        .nav-item {
            padding: 15px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            color: white;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
            user-select: none;
        }

        .nav-item:hover {
            background: rgba(255,255,255,0.1);
            border-left-color: white;
        }

        .nav-item.active {
            background: rgba(255,255,255,0.2);
            border-left-color: white;
            font-weight: 600;
        }

        .nav-item i {
            font-size: 16px;
            width: 20px;
            text-align: center;
        }

        .nav-item-text {
            flex: 1;
        }

        .nav-badge {
            background: rgba(255,255,255,0.3);
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
        }

        .sidebar-footer {
            padding: 20px;
            border-top: 1px solid rgba(255,255,255,0.2);
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 15px;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(255,255,255,0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }

        .user-details {
            flex: 1;
        }

        .user-name {
            font-size: 14px;
            font-weight: 600;
        }

        .user-role {
            font-size: 11px;
            opacity: 0.8;
        }

        .btn-logout {
            width: 100%;
            padding: 10px;
            background: rgba(255,255,255,0.2);
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-logout:hover {
            background: rgba(255,255,255,0.3);
        }

        /* Mobile Menu Toggle */
        .mobile-menu-toggle {
            display: none;
            position: fixed;
            top: 15px;
            left: 15px;
            z-index: 1001;
            width: 40px;
            height: 40px;
            background: var(--admin-primary);
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 18px;
        }

        /* Main Content Area */
        .main-content {
            flex: 1;
            margin-left: var(--sidebar-width);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Top Header */
        .top-header {
            height: var(--header-height);
            background: white;
            border-bottom: 1px solid #e0e0e0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 30px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        .header-title {
            font-size: 20px;
            font-weight: 600;
            color: var(--admin-secondary);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .header-actions {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        /* Content Sections */
        .content-wrapper {
            flex: 1;
            padding: 30px;
            overflow-y: auto;
        }

        .content-section {
            display: none;
            animation: fadeIn 0.4s ease;
        }

        .content-section.active {
            display: block;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Stat Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            gap: 15px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 15px 18px;
            border-radius: 8px;
            border-left: 3px solid var(--admin-primary);
            box-shadow: 0 2px 4px rgba(0,0,0,0.06);
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            box-shadow: 0 4px 8px rgba(0,0,0,0.12);
            transform: translateY(-2px);
        }

        .stat-label {
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
            font-weight: 600;
            margin-bottom: 8px;
            letter-spacing: 0.5px;
        }

        .stat-value {
            font-size: 26px;
            font-weight: bold;
            color: var(--admin-primary);
        }

        /* Card */
        .card {
            background: white;
            border-radius: 8px;
            padding: 25px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.06);
            margin-bottom: 20px;
        }

        /* Table */
        .table-container {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 6px rgba(0,0,0,0.06);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background: #f8f9fa;
        }

        th {
            padding: 14px;
            text-align: left;
            font-weight: 600;
            color: #333;
            font-size: 13px;
            border-bottom: 2px solid #e0e0e0;
        }

        td {
            padding: 14px;
            border-bottom: 1px solid #f0f0f0;
            font-size: 14px;
        }

        tbody tr:hover {
            background: #f8f9fa;
        }

        /* Buttons */
        .btn {
            padding: 10px 18px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary {
            background: var(--admin-primary);
            color: white;
        }

        .btn-primary:hover {
            background: #044073;
            box-shadow: 0 2px 8px rgba(5, 84, 137, 0.3);
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background: #5a6268;
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

        /* Search and Filters */
        .controls {
            display: flex;
            gap: 12px;
            margin-bottom: 20px;
            flex-wrap: wrap;
            align-items: center;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.06);
        }

        .search-box {
            flex: 1;
            min-width: 250px;
            display: flex;
            gap: 10px;
        }

        .search-box input,
        .search-box select {
            padding: 10px 14px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
        }

        .search-box input {
            flex: 1;
        }

        .search-box select {
            min-width: 140px;
        }

        .search-box input:focus,
        .search-box select:focus {
            outline: none;
            border-color: var(--admin-primary);
            box-shadow: 0 0 0 3px rgba(224, 64, 64, 0.1);
        }

        /* Loading State */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255,255,255,0.9);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }

        .loading-overlay.active {
            display: flex;
        }

        .spinner {
            width: 50px;
            height: 50px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid var(--admin-primary);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Banner */
        .banner {
            padding: 15px 20px;
            border-radius: 6px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .banner-success {
            background: #d4edda;
            color: #155724;
            border-left: 4px solid #28a745;
        }

        .banner-error {
            background: #f8d7da;
            color: #721c24;
            border-left: 4px solid #dc3545;
        }

        .banner-warning {
            background: #fff3cd;
            color: #856404;
            border-left: 4px solid #ffc107;
        }

        .banner-close {
            margin-left: auto;
            background: none;
            border: none;
            font-size: 20px;
            cursor: pointer;
            color: inherit;
            opacity: 0.7;
        }

        .banner-close:hover {
            opacity: 1;
        }

        /* Badges */
        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 12px;
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

        /* Responsive Design */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.mobile-open {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .mobile-menu-toggle {
                display: block;
            }

            .top-header {
                padding-left: 70px;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .controls {
                flex-direction: column;
                align-items: stretch;
            }

            .search-box {
                flex-direction: column;
                width: 100%;
            }

            .search-box input,
            .search-box select {
                width: 100%;
            }
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #999;
        }

        .empty-state i {
            font-size: 64px;
            margin-bottom: 20px;
            opacity: 0.5;
        }

        .empty-state h3 {
            font-size: 20px;
            margin-bottom: 10px;
            color: #666;
        }

        .empty-state p {
            font-size: 14px;
        }

        /* Pagination */
        .pagination {
            display: flex;
            justify-content: center;
            gap: 8px;
            margin-top: 20px;
            flex-wrap: wrap;
        }

        .pagination a,
        .pagination span {
            padding: 8px 14px;
            border: 1px solid #ddd;
            border-radius: 6px;
            text-decoration: none;
            color: #333;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .pagination a:hover {
            background: #f0f0f0;
            border-color: var(--admin-primary);
        }

        .pagination .active {
            background: var(--admin-primary);
            color: white;
            border-color: var(--admin-primary);
        }

        /* Actions Column */
        .actions {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
        }

        /* Badge Styles */
        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .badge-group-teaching {
            background: #e3f2fd;
            color: #1976d2;
        }

        .badge-group-non-teaching-level-i {
            background: #e0f7fa;
            color: #006064;
        }

        .badge-group-non-teaching-level-ii {
            background: #fff3e0;
            color: #f57c00;
        }

        .badge-group-related-teaching {
            background: #ede7f6;
            color: #5e35b1;
        }

        .badge-group-higher-teaching {
            background: #f3e5f5;
            color: #7b1fa2;
        }

        .badge-group-school-administration {
            background: #fff8e1;
            color: #8d6e63;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 10000;
            overflow-y: auto;
        }

        .modal.show {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .modal-content {
            background: white;
            border-radius: 8px;
            width: 100%;
            max-width: 600px;
            max-height: 90vh;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .modal-large {
            max-width: 1000px;
        }

        .modal-header {
            padding: 20px;
            border-bottom: 1px solid #e0e0e0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-header h2 {
            margin: 0;
            font-size: 20px;
            color: var(--admin-primary);
        }

        .modal-close {
            background: none;
            border: none;
            font-size: 28px;
            cursor: pointer;
            color: #999;
            line-height: 1;
            padding: 0;
            width: 30px;
            height: 30px;
        }

        .modal-close:hover {
            color: #333;
        }

        .modal-body {
            padding: 20px;
            overflow-y: auto;
            flex: 1;
        }

        /* IES Styles */
        .ies-section {
            margin-bottom: 30px;
        }

        .ies-section h3 {
            margin-bottom: 15px;
            font-size: 18px;
        }

        .ies-section h4 {
            margin-bottom: 15px;
            font-size: 16px;
            color: #555;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }

        .info-item {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .info-item strong {
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
        }

        .info-item span {
            font-size: 14px;
            color: #333;
        }

        .ies-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }

        .ies-table th,
        .ies-table td {
            padding: 10px;
            border: 1px solid #e0e0e0;
            text-align: left;
            font-size: 13px;
        }

        .ies-table th {
            background: #f8f9fa;
            font-weight: 600;
        }

        .total-score {
            background: linear-gradient(135deg, #055489 0%, #044073 100%);
            color: white;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            margin-top: 20px;
        }

        .total-score h3 {
            margin: 0 0 10px 0;
            font-size: 14px;
            opacity: 0.9;
        }

        .total-score .score {
            font-size: 36px;
            font-weight: bold;
        }

        .history-item {
            padding: 10px;
            border-left: 3px solid var(--admin-primary);
            background: #f8f9fa;
            margin-bottom: 10px;
            border-radius: 4px;
        }

        .history-action {
            font-weight: 600;
            color: #333;
        }

        .history-date {
            font-size: 12px;
            color: #666;
            margin-top: 3px;
        }

        /* Form Group */
        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }

        .form-group textarea,
        .form-group input[type="text"] {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 6px;
            font-family: inherit;
            font-size: 14px;
            transition: border-color 0.3s;
        }

        .form-group textarea:focus,
        .form-group input[type="text"]:focus {
            outline: none;
            border-color: var(--admin-primary);
        }

        /* Theme Toggle */
        .theme-toggle {
            padding: 8px 12px;
            background: #111827;
            color: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 9999px;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.02em;
            cursor: pointer;
            transition: transform 0.2s ease, background-color 0.2s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .theme-toggle:hover {
            background: #1f2937;
            transform: translateY(-1px);
        }

        .theme-toggle-icon {
            width: 14px;
            height: 14px;
            display: inline-block;
        }

        .theme-toggle-icon.is-hidden {
            display: none;
        }

        .theme-toggle-label {
            font-size: 12px;
            line-height: 1;
        }

        /* Dark Mode */
        body.dark-mode {
            background: #0f172a;
            color: #e5e7eb;
        }

        body.dark-mode .sidebar {
            background: linear-gradient(180deg, #1f2937 0%, #111827 100%);
            box-shadow: 2px 0 12px rgba(0, 0, 0, 0.45);
        }

        body.dark-mode .main-content,
        body.dark-mode .content-wrapper {
            background: #0f172a;
        }

        body.dark-mode .top-header {
            background: #111827;
            border-bottom-color: #374151;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.35);
        }

        body.dark-mode .header-title,
        body.dark-mode .stat-label,
        body.dark-mode .user-role,
        body.dark-mode .empty-state,
        body.dark-mode .empty-state p,
        body.dark-mode .history-date {
            color: #cbd5e1 !important;
        }

        body.dark-mode .stat-card,
        body.dark-mode .card,
        body.dark-mode .controls,
        body.dark-mode .table-container,
        body.dark-mode .modal-content {
            background: #111827;
            border-color: #374151;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.35);
        }

        body.dark-mode .stat-card {
            border-left-color: #055489;
        }

        body.dark-mode .stat-value,
        body.dark-mode td,
        body.dark-mode .info-item span,
        body.dark-mode .history-action,
        body.dark-mode .form-group label,
        body.dark-mode .empty-state h3,
        body.dark-mode .ies-section h3,
        body.dark-mode .ies-section h4,
        body.dark-mode .modal-close,
        body.dark-mode .modal-header h2 {
            color: #f3f4f6 !important;
        }

        body.dark-mode thead,
        body.dark-mode .ies-table th {
            background: #1f2937;
        }

        body.dark-mode th,
        body.dark-mode .ies-table th,
        body.dark-mode .info-item strong {
            color: #d1d5db !important;
            border-bottom-color: #374151;
        }

        body.dark-mode td,
        body.dark-mode .ies-table td {
            border-bottom-color: #1f2937;
            border-color: #374151;
        }

        body.dark-mode tbody tr:hover {
            background: #1f2937;
        }

        body.dark-mode .search-box input,
        body.dark-mode .search-box select,
        body.dark-mode .form-group textarea,
        body.dark-mode .form-group input[type="text"] {
            background: #0b1220;
            color: #e5e7eb;
            border-color: #4b5563;
        }

        body.dark-mode .search-box input::placeholder,
        body.dark-mode .form-group textarea::placeholder {
            color: #94a3b8;
        }

        body.dark-mode .search-box input:focus,
        body.dark-mode .search-box select:focus,
        body.dark-mode .form-group textarea:focus,
        body.dark-mode .form-group input[type="text"]:focus {
            border-color: #055489;
            box-shadow: 0 0 0 3px rgba(5, 84, 137, 0.28);
        }

        body.dark-mode .btn-secondary {
            background: #374151;
            color: #e5e7eb;
        }

        body.dark-mode .btn-secondary:hover {
            background: #4b5563;
        }

        body.dark-mode [data-tab] {
            color: #cbd5e1 !important;
        }

        body.dark-mode .active-tab-btn,
        body.dark-mode [data-tab].active-tab-btn {
            color: #93c5fd !important;
            border-bottom-color: #055489 !important;
            background: transparent !important;
        }

        body.dark-mode td code {
            background: #1f2937 !important;
            color: #f3f4f6 !important;
            border: 1px solid #374151;
        }

        body.dark-mode .badge-group-teaching {
            background: #1e3a2f;
            color: #a7f3d0;
        }

        body.dark-mode .badge-group-non-teaching-level-i {
            background: #153344;
            color: #bae6fd;
        }

        body.dark-mode .badge-group-non-teaching-level-ii {
            background: #3f3418;
            color: #fde68a;
        }

        body.dark-mode .badge-group-related-teaching {
            background: #312e81;
            color: #ddd6fe;
        }

        body.dark-mode .badge-group-higher-teaching {
            background: #4c1d95;
            color: #e9d5ff;
        }

        body.dark-mode .badge-group-school-administration {
            background: #3f2d22;
            color: #fed7aa;
        }

        body.dark-mode .history-item {
            background: #1f2937;
            border-left-color: #055489;
        }

        body.dark-mode .loading-overlay {
            background: rgba(2, 6, 23, 0.78);
        }

        body.dark-mode .mobile-menu-toggle {
            background: #055489;
            color: #f3f4f6;
        }

        body.dark-mode .theme-toggle {
            background: #055489;
            color: #f3f4f6;
            border-color: rgba(17, 24, 39, 0.35);
        }

        body.dark-mode .theme-toggle:hover {
            background: #0668aa;
        }

        body.dark-mode .pagination a,
        body.dark-mode .pagination span {
            background: #111827;
            color: #e5e7eb;
            border-color: #4b5563;
        }

        body.dark-mode .pagination a:hover {
            background: #1f2937;
            border-color: #055489;
        }

        body.dark-mode .modal {
            background: rgba(2, 6, 23, 0.72);
        }

        body.dark-mode .modal-header {
            border-bottom-color: #374151;
        }

        body.dark-mode p[style*="color: #666"],
        body.dark-mode strong[style*="color: #666"],
        body.dark-mode div[style*="color: #666"] {
            color: #cbd5e1 !important;
        }

        body.dark-mode td[style*="color: #555"],
        body.dark-mode td[style*="color: #055489"],
        body.dark-mode h3[style*="color: #055489"] {
            color: #f3f4f6 !important;
        }

        body.dark-mode div[style*="background: #f8f9fa"],
        body.dark-mode div[style*="background:#f8f9fa"] {
            background: #1f2937 !important;
            color: #e5e7eb !important;
        }

        body.dark-mode div[style*="background: #fff3cd"],
        body.dark-mode div[style*="background:#fff3cd"] {
            background: #3f3418 !important;
            border-left-color: #d39e00 !important;
            color: #fef3c7 !important;
        }
    </style>
</head>
<body>
    <!-- Mobile Menu Toggle -->
    <button class="mobile-menu-toggle" onclick="toggleMobileMenu()">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="spinner"></div>
    </div>

    <!-- Banner Container -->
    <div id="bannerContainer" style="position: fixed; top: 20px; right: 20px; z-index: 10000; max-width: 400px;"></div>

    <div class="dashboard-layout">
        <!-- Sidebar Navigation -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <h1><i class="fas fa-user-shield"></i> Admin Panel</h1>
                <p>DepEd HRMPSB System</p>
            </div>

            <nav class="sidebar-nav">
                <a class="nav-item active" data-section="applicants" onclick="switchSection('applicants')">
                    <i class="fas fa-users"></i>
                    <span class="nav-item-text">Applicants Management</span>
                    <span class="nav-badge"><?php echo $stats['active_total']; ?></span>
                </a>
                <a class="nav-item" data-section="drafts" onclick="switchSection('drafts')">
                    <i class="fas fa-file-alt"></i>
                    <span class="nav-item-text">Saved Drafts</span>
                </a>
                <a class="nav-item" onclick="returnToMain()">
                    <i class="fas fa-arrow-circle-left"></i>
                    <span class="nav-item-text">Return to Main</span>
                </a>
            </nav>

            <div class="sidebar-footer">
                <div class="user-info">
                    <div class="user-avatar">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="user-details">
                        <div class="user-name"><?php echo htmlspecialchars($currentUser ? ($currentUser['full_name'] ?? $currentUser['username'] ?? 'Admin') : 'Admin'); ?></div>
                        <div class="user-role">Administrator</div>
                    </div>
                </div>
                <button class="btn-logout" onclick="window.location.href='logout.php'">
                    <i class="fas fa-sign-out-alt"></i>
                    Logout
                </button>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Top Header -->
            <header class="top-header">
                <div class="header-title" id="headerTitle">
                    <i class="fas fa-users"></i>
                    <span>Applicants Management</span>
                </div>
                <div class="header-actions">
                    <button type="button" id="themeToggle" class="theme-toggle" aria-pressed="false" aria-label="Switch to dark mode" title="Switch to dark mode">
                        <svg id="themeSunIcon" class="theme-toggle-icon is-hidden" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M12 4a1 1 0 011 1v1a1 1 0 11-2 0V5a1 1 0 011-1zm0 13a4 4 0 100-8 4 4 0 000 8zm7-5a1 1 0 011 1h1a1 1 0 110 2h-1a1 1 0 110-2zM3 12a1 1 0 011-1h1a1 1 0 110 2H4a1 1 0 01-1-1zm14.364 5.95a1 1 0 011.414 0l.707.707a1 1 0 11-1.414 1.414l-.707-.707a1 1 0 010-1.414zM4.515 4.515a1 1 0 011.414 0l.707.707A1 1 0 015.222 6.636l-.707-.707a1 1 0 010-1.414zm13.435 0a1 1 0 010 1.414l-.707.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM6.636 17.364a1 1 0 010 1.414l-.707.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0z"/>
                        </svg>
                        <svg id="themeMoonIcon" class="theme-toggle-icon" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M21 12.8A9 9 0 1111.2 3a1 1 0 01.97 1.24 7 7 0 008.59 8.59A1 1 0 0121 12.8z"/>
                        </svg>
                        <span id="themeToggleLabel" class="theme-toggle-label">Dark</span>
                    </button>
                    <button class="btn btn-secondary btn-small" onclick="refreshCurrentSection()">
                        <i class="fas fa-sync"></i> Refresh
                    </button>
                </div>
            </header>

            <!-- Content Wrapper -->
            <div class="content-wrapper">
                <!-- Applicants Section -->
                <section id="applicants-section" class="content-section active">
                    <!-- Statistics -->
                    <div class="stats-grid">
                        <div class="stat-card">
                            <div class="stat-label">Total Applicants</div>
                            <div class="stat-value" id="stat-total"><?php echo $stats['total']; ?></div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-label">Active</div>
                            <div class="stat-value" id="stat-active"><?php echo $stats['active_total']; ?></div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-label">Archived</div>
                            <div class="stat-value" id="stat-archived"><?php echo $stats['archived_total']; ?></div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-label">Teaching</div>
                            <div class="stat-value"><?php echo $stats['active_by_group']['TEACHING'] ?? 0; ?></div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-label">Non-Teaching I</div>
                            <div class="stat-value"><?php echo $stats['active_by_group']['NON-TEACHING LEVEL I'] ?? 0; ?></div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-label">Non-Teaching II</div>
                            <div class="stat-value"><?php echo $stats['active_by_group']['NON-TEACHING LEVEL II'] ?? 0; ?></div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-label">Related Teaching</div>
                            <div class="stat-value"><?php echo $stats['active_by_group']['RELATED TEACHING'] ?? 0; ?></div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-label">Higher Teaching</div>
                            <div class="stat-value"><?php echo $stats['active_by_group']['HIGHER TEACHING'] ?? 0; ?></div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-label">School Admin</div>
                            <div class="stat-value"><?php echo $stats['active_by_group']['SCHOOL ADMINISTRATION'] ?? 0; ?></div>
                        </div>
                    </div>

                    <!-- Tabs -->
                    <div class="card" style="margin-bottom: 20px;">
                        <div style="display: flex; gap: 10px; border-bottom: 2px solid #f0f0f0; padding-bottom: 0;">
                            <button class="btn btn-secondary active-tab-btn" data-tab="active" style="border-bottom: 3px solid var(--admin-primary); margin-bottom: -2px;">
                                <i class="fas fa-clipboard-list"></i> Active (<?php echo $stats['active_total']; ?>)
                            </button>
                            <button class="btn btn-secondary" data-tab="archived" style="border-bottom: 3px solid transparent; margin-bottom: -2px; background: none; color: #666;">
                                <i class="fas fa-archive"></i> Archived (<?php echo $stats['archived_total']; ?>)
                            </button>
                        </div>
                    </div>

                    <!-- Search and Filters -->
                    <div class="controls">
                        <div class="search-box">
                            <input type="text" id="searchInput" placeholder="Search applicant name..." value="<?php echo htmlspecialchars($searchTerm); ?>">
                            <select id="groupFilter">
                                <option value="">All Groups</option>
                                <option value="TEACHING">Teaching</option>
                                <option value="NON-TEACHING LEVEL I">Non-Teaching Level I</option>
                                <option value="NON-TEACHING LEVEL II">Non-Teaching Level II</option>
                                <option value="RELATED TEACHING">Related Teaching</option>
                                <option value="HIGHER TEACHING">Higher Teaching</option>
                                <option value="SCHOOL ADMINISTRATION">School Administration</option>
                            </select>
                            <button class="btn btn-primary" onclick="searchApplicants()">
                                <i class="fas fa-search"></i> Search
                            </button>
                        </div>
                        <button class="btn btn-secondary" onclick="resetFilters()">
                            <i class="fas fa-undo"></i> Reset
                        </button>
                    </div>

                    <!-- Applicants Table -->
                    <div class="table-container" id="applicantsTableContainer">
                        <!-- Content loaded dynamically -->
                    </div>
                </section>

                <!-- Drafts Section -->
                <section id="drafts-section" class="content-section">
                    <div class="card">
                        <h2 style="margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                            <i class="fas fa-file-alt"></i>
                            Saved Application Drafts
                        </h2>
                        <p style="color: #666; margin-bottom: 20px;">View and manage all saved application drafts. Load drafts back into the system or delete them permanently.</p>
                        
                        <div class="table-container" id="draftsTableContainer">
                            <!-- Content loaded dynamically -->
                        </div>
                    </div>
                </section>
            </div>
        </main>
    </div>

    <!-- Archive Modal -->
    <div id="archiveModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2><i class="fas fa-archive" style="margin-right: 10px;"></i>Archive Applicant</h2>
                <button class="modal-close" onclick="closeModal('archiveModal')">&times;</button>
            </div>
            <div class="modal-body">
                <p style="margin-bottom: 20px; color: #666;">You are about to archive: <strong id="archiveApplicantName" style="color: #055489;"></strong></p>
                
                <div class="form-group">
                    <label for="archiveReason" style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">
                        <i class="fas fa-comment-alt" style="margin-right: 6px;"></i>Reason for Archiving
                    </label>
                    <textarea 
                        id="archiveReason" 
                        rows="4" 
                        placeholder="Enter the reason for archiving this applicant (optional)..."
                        style="width: 100%; padding: 12px; border: 2px solid #e0e0e0; border-radius: 6px; font-family: inherit; font-size: 14px; resize: vertical; transition: border-color 0.3s;"
                        onfocus="this.style.borderColor='#055489'"
                        onblur="this.style.borderColor='#e0e0e0'"
                    ></textarea>
                </div>
                
                <div style="display: flex; gap: 10px; margin-top: 20px;">
                    <button class="btn btn-secondary" onclick="closeModal('archiveModal')" style="flex: 1;">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                    <button class="btn btn-danger" onclick="confirmArchive()" style="flex: 1;">
                        <i class="fas fa-archive"></i> Archive Applicant
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- View Details Modal -->
    <div id="detailsModal" class="modal">
        <div class="modal-content modal-large">
            <div class="modal-header">
                <h2><i class="fas fa-user-check" style="margin-right: 10px;"></i>Applicant Details & IES</h2>
                <button class="modal-close" onclick="closeModal('detailsModal')">&times;</button>
            </div>
            <div class="modal-body" id="detailsModalBody">
                <!-- Content loaded dynamically -->
            </div>
        </div>
    </div>

    <script>
        // Global state
        let currentSection = 'applicants';
        let currentTab = 'active';
        let currentPage = 1;
        let searchTerm = '<?php echo addslashes($searchTerm); ?>';
        let groupFilter = '<?php echo addslashes($positionGroup); ?>';
        const themeStorageKey = 'deped_admin_dashboard_theme_mode';

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            initThemeToggle();
            loadApplicants();
            setupTabSwitching();
            setupEnterKeySearch();
        });

        function initThemeToggle() {
            const themeToggle = document.getElementById('themeToggle');
            if (!themeToggle) return;

            const themeSunIcon = document.getElementById('themeSunIcon');
            const themeMoonIcon = document.getElementById('themeMoonIcon');
            const themeToggleLabel = document.getElementById('themeToggleLabel');

            function applyTheme(mode) {
                const isDark = mode === 'dark';
                document.body.classList.toggle('dark-mode', isDark);

                themeToggle.setAttribute('aria-pressed', isDark ? 'true' : 'false');
                themeToggle.setAttribute('title', isDark ? 'Switch to light mode' : 'Switch to dark mode');
                themeToggle.setAttribute('aria-label', isDark ? 'Switch to light mode' : 'Switch to dark mode');

                if (themeSunIcon && themeMoonIcon) {
                    themeSunIcon.classList.toggle('is-hidden', !isDark);
                    themeMoonIcon.classList.toggle('is-hidden', isDark);
                }

                if (themeToggleLabel) {
                    themeToggleLabel.textContent = isDark ? 'Light' : 'Dark';
                }
            }

            let initialMode = 'light';
            try {
                initialMode = localStorage.getItem(themeStorageKey) === 'dark' ? 'dark' : 'light';
            } catch (error) {
                initialMode = 'light';
            }

            applyTheme(initialMode);

            themeToggle.addEventListener('click', function() {
                const nextTheme = document.body.classList.contains('dark-mode') ? 'light' : 'dark';
                applyTheme(nextTheme);

                try {
                    localStorage.setItem(themeStorageKey, nextTheme);
                } catch (error) {
                    // Ignore storage errors and keep the current runtime theme.
                }
            });
        }

        // Section Switching
        function switchSection(section) {
            console.log('Switching to section:', section);
            currentSection = section;
            
            // Update nav items
            document.querySelectorAll('.nav-item').forEach(item => {
                item.classList.remove('active');
                if (item.dataset.section === section) {
                    item.classList.add('active');
                }
            });

            // Update content sections
            document.querySelectorAll('.content-section').forEach(sec => {
                sec.classList.remove('active');
            });
            document.getElementById(section + '-section').classList.add('active');

            // Update header
            const headers = {
                'applicants': '<i class="fas fa-users"></i><span>Applicants Management</span>',
                'drafts': '<i class="fas fa-file-alt"></i><span>Saved Drafts</span>'
            };
            document.getElementById('headerTitle').innerHTML = headers[section] || '';

            // Load section content
            if (section === 'applicants') {
                loadApplicants();
            } else if (section === 'drafts') {
                loadDrafts();
            }

            // Close mobile menu
            closeMobileMenu();
        }

        // Tab Switching
        function setupTabSwitching() {
            document.querySelectorAll('[data-tab]').forEach(btn => {
                btn.addEventListener('click', function() {
                    const tab = this.dataset.tab;
                    switchTab(tab);
                });
            });
        }

        function switchTab(tab) {
            currentTab = tab;
            currentPage = 1;
            
            // Update button styles
            document.querySelectorAll('[data-tab]').forEach(btn => {
                if (btn.dataset.tab === tab) {
                    btn.classList.add('active-tab-btn');
                    btn.style.borderBottom = '3px solid var(--admin-primary)';
                    btn.style.background = 'none';
                    btn.style.color = 'var(--admin-primary)';
                } else {
                    btn.classList.remove('active-tab-btn');
                    btn.style.borderBottom = '3px solid transparent';
                    btn.style.background = 'none';
                    btn.style.color = '#666';
                }
            });

            loadApplicants();
        }

        // Load Applicants via AJAX
        function loadApplicants() {
            showLoading();
            
            const params = new URLSearchParams({
                action: 'load_applicants',
                tab: currentTab,
                search: searchTerm,
                group: groupFilter,
                page: currentPage
            });

            fetch('dashboard_ajax.php?' + params)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('applicantsTableContainer').innerHTML = data.html;
                        updateStats(data.stats);
                    } else {
                        showBanner('error', data.message || 'Failed to load applicants');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showBanner('error', 'Failed to load applicants');
                })
                .finally(() => {
                    hideLoading();
                });
        }

        // Load Drafts via AJAX
        function loadDrafts() {
            showLoading();
            
            fetch('dashboard_ajax.php?action=load_drafts')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('draftsTableContainer').innerHTML = data.html;
                    } else {
                        showBanner('error', data.message || 'Failed to load drafts');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showBanner('error', 'Failed to load drafts');
                })
                .finally(() => {
                    hideLoading();
                });
        }

        // Search Applicants
        function searchApplicants() {
            searchTerm = document.getElementById('searchInput').value.trim();
            groupFilter = document.getElementById('groupFilter').value;
            currentPage = 1;
            loadApplicants();
        }

        // Reset Filters
        function resetFilters() {
            document.getElementById('searchInput').value = '';
            document.getElementById('groupFilter').value = '';
            searchTerm = '';
            groupFilter = '';
            currentPage = 1;
            loadApplicants();
        }

        // Setup Enter Key Search
        function setupEnterKeySearch() {
            document.getElementById('searchInput').addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    searchApplicants();
                }
            });
        }

        // Archive Applicant - Global variables
        let currentArchiveId = null;
        let currentArchiveName = '';

        function archiveApplicant(id, name) {
            console.log('Archive called:', { id, name, type_id: typeof id, type_name: typeof name });
            
            // Convert ID to integer to ensure it's a valid number
            currentArchiveId = parseInt(id, 10);
            currentArchiveName = name;
            
            if (isNaN(currentArchiveId) || currentArchiveId <= 0) {
                console.error('Invalid applicant ID:', id);
                showBanner('error', 'Invalid applicant ID');
                return;
            }
            
            document.getElementById('archiveApplicantName').textContent = name;
            document.getElementById('archiveReason').value = '';
            
            openModal('archiveModal');
        }

        function confirmArchive() {
            console.log('Confirm archive:', { currentArchiveId, currentArchiveName, type: typeof currentArchiveId });
            
            if (!currentArchiveId || currentArchiveId <= 0) {
                console.error('No valid currentArchiveId set!', currentArchiveId);
                showBanner('error', 'Invalid applicant ID');
                return;
            }
            
            const reason = document.getElementById('archiveReason').value.trim();
            
            // Don't close modal or reset variables yet - need them for the fetch request
            showLoading();
            
            const formData = new FormData();
            formData.append('action', 'archive');
            formData.append('id', currentArchiveId);  // Already parsed to int in archiveApplicant()
            formData.append('reason', reason);
            
            console.log('FormData being sent:', { action: 'archive', id: currentArchiveId, reason, type: typeof currentArchiveId });

            fetch('dashboard_ajax.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                console.log('Archive response:', data);
                
                // Close modal and reset after getting response
                closeModal('archiveModal');
                
                if (data.success) {
                    showBanner('success', 'Applicant archived successfully');
                    loadApplicants();
                } else {
                    console.error('Archive failed:', data);
                    showBanner('error', data.message || 'Failed to archive applicant');
                    if (data.debug) {
                        console.error('Debug info:', data.debug);
                    }
                }
            })
            .catch(error => {
                console.error('Archive error:', error);
                closeModal('archiveModal');
                showBanner('error', 'Failed to archive applicant');
            })
            .finally(() => {
                hideLoading();
            });
        }

        // Restore Applicant
        function restoreApplicant(id, name) {
            // Convert ID to integer
            const applicantId = parseInt(id, 10);
            
            if (isNaN(applicantId) || applicantId <= 0) {
                console.error('Invalid applicant ID:', id);
                showBanner('error', 'Invalid applicant ID');
                return;
            }
            
            if (!confirm(`Restore "${name}"?`)) return;
            
            showLoading();
            
            const formData = new FormData();
            formData.append('action', 'restore');
            formData.append('id', applicantId);

            fetch('dashboard_ajax.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showBanner('success', 'Applicant restored successfully');
                    loadApplicants();
                } else {
                    showBanner('error', data.message || 'Failed to restore applicant');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showBanner('error', 'Failed to restore applicant');
            })
            .finally(() => {
                hideLoading();
            });
        }

        // Load Draft
        function loadDraft(id) {
            if (!confirm('Load this draft? This will redirect to the main form.')) return;
            
            showLoading();
            window.location.href = 'dashboard_ajax.php?action=load_draft&id=' + id;
        }

        // Delete Draft
        function deleteDraft(id) {
            if (!confirm('Permanently delete this draft?')) return;
            
            showLoading();
            
            const formData = new FormData();
            formData.append('action', 'delete_draft');
            formData.append('id', id);

            fetch('dashboard_ajax.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showBanner('success', 'Draft deleted successfully');
                    loadDrafts();
                } else {
                    showBanner('error', data.message || 'Failed to delete draft');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showBanner('error', 'Failed to delete draft');
            })
            .finally(() => {
                hideLoading();
            });
        }

        // Pagination
        function goToPage(page) {
            currentPage = page;
            loadApplicants();
            window.scrollTo(0, 0);
        }

        // Update Statistics
        function updateStats(stats) {
            if (stats) {
                document.getElementById('stat-total').textContent = stats.total || 0;
                document.getElementById('stat-active').textContent = stats.active_total || 0;
                document.getElementById('stat-archived').textContent = stats.archived_total || 0;
                
                // Update nav badge
                document.querySelector('[data-section="applicants"] .nav-badge').textContent = stats.active_total || 0;
                
                // Update tab counts
                document.querySelectorAll('[data-tab]').forEach(btn => {
                    const tab = btn.dataset.tab;
                    if (tab === 'active') {
                        btn.innerHTML = `<i class="fas fa-clipboard-list"></i> Active (${stats.active_total || 0})`;
                    } else if (tab === 'archived') {
                        btn.innerHTML = `<i class="fas fa-archive"></i> Archived (${stats.archived_total || 0})`;
                    }
                });
            }
        }

        // Refresh Current Section
        function refreshCurrentSection() {
            if (currentSection === 'applicants') {
                loadApplicants();
            } else if (currentSection === 'drafts') {
                loadDrafts();
            }
        }

        // Return to Main
        function returnToMain() {
            if (confirm('Return to main evaluation system?')) {
                window.location.href = '../index.php';
            }
        }

        // Mobile Menu
        function toggleMobileMenu() {
            document.getElementById('sidebar').classList.toggle('mobile-open');
        }

        function closeMobileMenu() {
            document.getElementById('sidebar').classList.remove('mobile-open');
        }

        // Loading State
        function showLoading() {
            document.getElementById('loadingOverlay').classList.add('active');
        }

        function hideLoading() {
            document.getElementById('loadingOverlay').classList.remove('active');
        }

        // Banner Notifications
        function showBanner(type, message) {
            const banner = document.createElement('div');
            banner.className = `banner banner-${type}`;
            banner.innerHTML = `
                <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'}"></i>
                <span>${message}</span>
                <button class="banner-close" onclick="this.parentElement.remove()">&times;</button>
            `;
            
            document.getElementById('bannerContainer').appendChild(banner);
            
            if (type === 'success') {
                setTimeout(() => banner.remove(), 5000);
            }
        }

        // Modal Functions
        function openModal(modalId) {
            document.getElementById(modalId).classList.add('show');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.remove('show');
            
            // Reset archive modal state
            if (modalId === 'archiveModal') {
                currentArchiveId = null;
                currentArchiveName = '';
                document.getElementById('archiveReason').value = '';
            }
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

        // View Applicant Details & IES
        function viewDetails(id) {
            console.log('Opening details for applicant ID:', id);
            
            const modalBody = document.getElementById('detailsModalBody');
            if (!modalBody) {
                console.error('Modal body not found');
                return;
            }
            
            modalBody.innerHTML = '<div style="text-align: center; padding: 40px; color: #999;"><i class="fas fa-spinner fa-spin" style="font-size: 32px; margin-bottom: 15px;"></i><p>Loading evaluation details...</p></div>';
            openModal('detailsModal');
            
            const apiUrl = '../api/get_applicant_evaluation.php?id=' + id;
            console.log('Fetching from:', apiUrl);
            
            fetch(apiUrl)
            .then(response => response.json())
            .then(data => {
                console.log('API Response:', data);
                
                if (data.success) {
                    let html = `
                        <div class="ies-section">
                            <h3 style="margin-bottom: 15px; color: #055489;">
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
                    
                    if (data.evaluation) {
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
                        
                        if (data.details && data.details.length > 0) {
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
                            
                            data.details.forEach(detail => {
                                html += `
                                    <tr>
                                        <td style="font-weight: 600; color: #555;">${detail.criterion}</td>
                                        <td>${detail.applicant_qualification || '-'}</td>
                                        <td style="text-align: center;">${detail.applicant_level}</td>
                                        <td>${detail.baseline_qualification || '-'}</td>
                                        <td style="text-align: center;">${detail.increment}</td>
                                        <td style="text-align: center;">${detail.weight}</td>
                                        <td style="text-align: center; font-weight: 600; color: #055489;">${parseFloat(detail.final_score).toFixed(2)}</td>
                                    </tr>
                                `;
                            });
                            
                            html += `
                                    </tbody>
                                </table>
                            `;
                        }
                        
                        html += `
                                <div class="total-score">
                                    <h3>TOTAL EVALUATION SCORE</h3>
                                    <div class="score">${parseFloat(data.evaluation.total_score).toFixed(2)} / 100</div>
                                </div>
                            </div>
                        `;
                        
                        if (data.evaluation.notes) {
                            html += `
                                <div class="ies-section">
                                    <h4><i class="fas fa-sticky-note" style="margin-right: 8px;"></i>Notes</h4>
                                    <div style="background: #f8f9fa; padding: 15px; border-radius: 4px; border-left: 4px solid #055489;">
                                        ${data.evaluation.notes}
                                    </div>
                                </div>
                            `;
                        }
                    } else if (data.qualifications) {
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
                        html += `
                            <div style="background: #f8d7da; border-left: 4px solid #dc3545; padding: 15px; border-radius: 4px; margin-top: 15px;">
                                <strong style="color: #721c24;"><i class="fas fa-exclamation-triangle" style="margin-right: 6px;"></i>No Data Available</strong>
                                <p style="color: #721c24; margin: 5px 0 0 0; font-size: 13px;">No evaluation or qualification data found for this applicant.</p>
                            </div>
                        `;
                    }
                    
                    if (data.history && data.history.length > 0) {
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
                    
                    modalBody.innerHTML = html;
                } else {
                    modalBody.innerHTML = `
                        <div style="background: #f8d7da; border-left: 4px solid #dc3545; padding: 20px; border-radius: 4px;">
                            <strong style="color: #721c24;"><i class="fas fa-exclamation-circle" style="margin-right: 6px;"></i>Error</strong>
                            <p style="color: #721c24; margin: 10px 0 0 0;">${data.message || 'Failed to load applicant details'}</p>
                        </div>
                    `;
                }
            })
            .catch(error => {
                console.error('Error loading details:', error);
                modalBody.innerHTML = `
                    <div style="background: #f8d7da; border-left: 4px solid #dc3545; padding: 20px; border-radius: 4px;">
                        <strong style="color: #721c24;"><i class="fas fa-exclamation-circle" style="margin-right: 6px;"></i>Connection Error</strong>
                        <p style="color: #721c24; margin: 10px 0 0 0;">Error: ${error.message}</p>
                    </div>
                `;
            });
        }
    </script>
</body>
</html>
