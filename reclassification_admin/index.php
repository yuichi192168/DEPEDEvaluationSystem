<?php
/**
 * Reclassification Form - Admin Dashboard
 * Dedicated admin interface for managing performance evaluation records
 * Requires Admin authentication
 */

session_start();

require_once(__DIR__ . '/../classes/DBConnection.php');
require_once(__DIR__ . '/../classes/AuthenticationHelper.php');
require_once(__DIR__ . '/../classes/PerformanceEvaluationManager.php');

$authConn = DBConnection::getConnection();
$auth = new AuthenticationHelper($authConn);
$isAuthenticated = $auth->isAuthenticated();
$isAdmin = $auth->isAdmin();
$currentUser = $auth->getCurrentUser();

// Initialize variables for JavaScript
$currentUserId = $isAuthenticated ? $currentUser['id'] : 0;
$currentUserRole = $isAuthenticated ? $currentUser['role'] : 'guest';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require_once(__DIR__ . '/../includes/favicon.php'); ?>
    <title>RFTP Admin</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #055489;
            /* background-image: linear-gradient(rgba(5, 84, 137, 0.62), rgba(5, 84, 137, 0.62)), url('../images/sdocabuyao-cover.svg'); */
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            min-height: 100vh;
            padding: 20px;
        }
        .admin-container {
            max-width: 1400px;
            margin: 0 auto;
            background: rgba(255, 255, 255, 0.96);
            backdrop-filter: blur(2px);
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }
        .admin-header {
            background: #055489;
            color: white;
            padding: 24px 32px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .admin-header-left {
            display: flex;
            align-items: center;
            gap: 16px;
        }
        .admin-header img {
            width: 60px;
            height: 60px;
        }
        .admin-header h1 {
            font-size: 24px;
            font-weight: 600;
        }
        .admin-header p {
            font-size: 14px;
            opacity: 0.9;
            margin-top: 4px;
        }
        .admin-header-right {
            display: flex;
            align-items: center;
            gap: 16px;
        }
        .user-info {
            text-align: right;
        }
        .user-info .username {
            font-weight: 600;
            font-size: 15px;
        }
        .user-info .role {
            font-size: 13px;
            opacity: 0.9;
        }
        .logout-btn {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.2s;
        }
        .logout-btn:hover {
            background: rgba(255, 255, 255, 0.3);
        }
        .admin-content {
            padding: 32px;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 32px;
        }
        .stat-card {
            background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #055489;
        }
        .stat-card.passed {
            border-left-color: #10b981;
        }
        .stat-card.failed {
            border-left-color: #ef4444;
        }
        .stat-card .stat-label {
            font-size: 13px;
            color: #6b7280;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .stat-card .stat-value {
            font-size: 32px;
            font-weight: 700;
            color: #1f2937;
            margin-top: 8px;
        }
        .toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            gap: 16px;
            flex-wrap: wrap;
        }
        .search-box {
            flex: 1;
            max-width: 400px;
            position: relative;
        }
        .search-box input {
            width: 100%;
            padding: 10px 16px 10px 40px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 14px;
        }
        .search-box::before {
            content: "";
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 16px;
        }
        .toolbar-actions {
            display: flex;
            gap: 12px;
        }
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s;
        }
        .btn-primary {
            background: #055489;
            color: white;
        }
        .btn-primary:hover {
            background: #044073;
        }
        .btn-secondary {
            background: #e5e7eb;
            color: #374151;
        }
        .btn-secondary:hover {
            background: #d1d5db;
        }
        .records-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            overflow: hidden;
        }
        .records-table thead {
            background: #f9fafb;
        }
        .records-table th {
            padding: 12px 16px;
            text-align: left;
            font-size: 13px;
            font-weight: 600;
            color: #374151;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #e5e7eb;
        }
        .records-table td {
            padding: 12px 16px;
            font-size: 14px;
            color: #1f2937;
            border-bottom: 1px solid #f3f4f6;
        }
        .records-table tbody tr:hover {
            background: #f9fafb;
        }
        .records-table tbody tr:last-child td {
            border-bottom: none;
        }
        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }
        .badge-passed {
            background: #d1fae5;
            color: #065f46;
        }
        .badge-failed {
            background: #fee2e2;
            color: #991b1b;
        }
        .badge-na {
            background: #e5e7eb;
            color: #4b5563;
        }
        .action-btns {
            display: flex;
            gap: 8px;
        }
        .action-btn {
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
            font-weight: 500;
            transition: all 0.2s;
        }
        .action-btn-edit {
            background: #E3D2C4;
            color: #055489;
        }
        .action-btn-edit:hover {
            background: #bfdbfe;
        }
        .action-btn-view {
            background: #f3e8ff;
            color: #6b21a8;
        }
        .action-btn-view:hover {
            background: #e9d5ff;
        }
        .action-btn-delete {
            background: #fee2e2;
            color: #991b1b;
        }
        .action-btn-delete:hover {
            background: #fecaca;
        }
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #6b7280;
        }
        .empty-state-icon {
            font-size: 64px;
            margin-bottom: 16px;
        }
        .empty-state h3 {
            font-size: 20px;
            color: #374151;
            margin-bottom: 8px;
        }
        .empty-state p {
            font-size: 14px;
        }
        
        /* Login Modal Styles */
        .login-modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(8px);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }
        .login-modal {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
            width: 90%;
            max-width: 420px;
            padding: 32px;
            position: relative;
        }
        .login-modal-header {
            text-align: center;
            margin-bottom: 24px;
        }
        .login-modal-header img {
            width: 80px;
            margin-bottom: 12px;
        }
        .login-modal-header h2 {
            margin: 0 0 8px;
            color: #333;
            font-size: 24px;
        }
        .login-modal-header p {
            margin: 0;
            color: #666;
            font-size: 14px;
        }
        .login-form-group {
            margin-bottom: 20px;
        }
        .login-form-group label {
            display: block;
            margin-bottom: 6px;
            color: #333;
            font-weight: 500;
            font-size: 14px;
        }
        .login-form-group input {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 15px;
            box-sizing: border-box;
            transition: all 0.2s;
        }
        .login-form-group input:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }
        .login-btn {
            width: 100%;
            padding: 12px;
            background: #055489;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }
        .login-btn:hover {
            background: #044073;
        }
        .login-btn:disabled {
            background: #7ba3c4;
            cursor: not-allowed;
        }
        .login-error {
            background: #fee2e2;
            border: 1px solid #ef4444;
            color: #991b1b;
            padding: 10px 12px;
            border-radius: 6px;
            margin-bottom: 16px;
            font-size: 14px;
            display: none;
        }
        .login-error.show {
            display: block;
        }
        .back-link {
            text-align: center;
            margin-top: 16px;
        }
        .back-link a {
            color: #055489;
            text-decoration: none;
            font-size: 14px;
        }
        .back-link a:hover {
            text-decoration: underline;
        }
        .dashboard-content {
            display: none;
        }
        .dashboard-content.authenticated {
            display: block;
        }
        .theme-toggle {
            position: fixed;
            right: 24px;
            bottom: 24px;
            padding: 10px 14px;
            background: #111827;
            color: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.25);
            border-radius: 9999px;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.02em;
            cursor: pointer;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.25);
            z-index: 10002;
            transition: transform 0.2s ease, background-color 0.2s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        .theme-toggle:hover {
            background: #1f2937;
            transform: translateY(-2px);
        }
        .theme-toggle-icon {
            width: 16px;
            height: 16px;
            display: inline-block;
        }
        .theme-toggle-icon.is-hidden {
            display: none;
        }
        .theme-toggle-label {
            font-size: 12px;
            line-height: 1;
        }
        body.dark-mode {
            background-image: linear-gradient(rgba(15, 23, 42, 0.82), rgba(15, 23, 42, 0.82)), url('../images/sdocabuyao-cover.svg');
            color: #e5e7eb;
        }
        body.dark-mode .theme-toggle {
            background: #055489;
            color: #f3f4f6;
        }
        body.dark-mode .theme-toggle:hover {
            background: #0668aa;
        }
        body.dark-mode .admin-container {
            background: rgba(17, 24, 39, 0.92);
            border: 1px solid rgba(75, 85, 99, 0.85);
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.45);
        }
        body.dark-mode .admin-content,
        body.dark-mode .admin-header p,
        body.dark-mode .user-info .role {
            color: #cbd5e1;
        }
        body.dark-mode .stat-card {
            background: #1f2937;
            border-left-color: #055489;
        }
        body.dark-mode .stat-card.passed {
            border-left-color: #10b981;
        }
        body.dark-mode .stat-card.failed {
            border-left-color: #ef4444;
        }
        body.dark-mode .stat-card .stat-label {
            color: #94a3b8;
        }
        body.dark-mode .stat-card .stat-value {
            color: #f3f4f6;
        }
        body.dark-mode .search-box input {
            background: #111827;
            color: #e5e7eb;
            border-color: #4b5563;
        }
        body.dark-mode .search-box input::placeholder {
            color: #9ca3af;
        }
        body.dark-mode .btn-secondary {
            background: #374151;
            color: #e5e7eb;
        }
        body.dark-mode .btn-secondary:hover {
            background: #4b5563;
        }
        body.dark-mode .records-table {
            background: #111827;
            border-color: #374151;
        }
        body.dark-mode .records-table thead {
            background: #1f2937;
        }
        body.dark-mode .records-table th {
            color: #f3f4f6;
            border-bottom-color: #374151;
        }
        body.dark-mode .records-table td {
            color: #e5e7eb;
            border-bottom-color: #1f2937;
        }
        body.dark-mode .records-table tbody tr:hover {
            background: #1f2937;
        }
        body.dark-mode .badge-na {
            background: #374151;
            color: #e5e7eb;
        }
        body.dark-mode .action-btn-edit {
            background: #3f2a1a;
            color: #f5c58f;
        }
        body.dark-mode .action-btn-edit:hover {
            background: #5a3a22;
        }
        body.dark-mode .action-btn-view {
            background: #312e81;
            color: #ddd6fe;
        }
        body.dark-mode .action-btn-view:hover {
            background: #4338ca;
        }
        body.dark-mode .action-btn-delete {
            background: #7f1d1d;
            color: #fecaca;
        }
        body.dark-mode .action-btn-delete:hover {
            background: #991b1b;
        }
        body.dark-mode .empty-state,
        body.dark-mode .empty-state p {
            color: #9ca3af;
        }
        body.dark-mode .empty-state h3 {
            color: #f3f4f6;
        }
        body.dark-mode .login-modal {
            background: #111827;
            border: 1px solid #374151;
        }
        body.dark-mode .login-modal-header h2,
        body.dark-mode .login-form-group label,
        body.dark-mode .back-link a {
            color: #f3f4f6;
        }
        body.dark-mode .login-modal-header p {
            color: #cbd5e1;
        }
        body.dark-mode .login-form-group input {
            background: #1f2937;
            color: #e5e7eb;
            border-color: #4b5563;
        }
        body.dark-mode .login-form-group input:focus {
            border-color: #055489;
            box-shadow: 0 0 0 3px rgba(5, 84, 137, 0.3);
        }
    </style>
</head>
<body>

<button type="button" id="themeToggle" class="theme-toggle" aria-pressed="false" aria-label="Switch to dark mode" title="Switch to dark mode">
    <svg id="themeSunIcon" class="theme-toggle-icon is-hidden" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
        <path d="M12 4a1 1 0 011 1v1a1 1 0 11-2 0V5a1 1 0 011-1zm0 13a4 4 0 100-8 4 4 0 000 8zm7-5a1 1 0 011 1h1a1 1 0 110 2h-1a1 1 0 110-2zM3 12a1 1 0 011-1h1a1 1 0 110 2H4a1 1 0 01-1-1zm14.364 5.95a1 1 0 011.414 0l.707.707a1 1 0 11-1.414 1.414l-.707-.707a1 1 0 010-1.414zM4.515 4.515a1 1 0 011.414 0l.707.707A1 1 0 015.222 6.636l-.707-.707a1 1 0 010-1.414zm13.435 0a1 1 0 010 1.414l-.707.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM6.636 17.364a1 1 0 010 1.414l-.707.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0z"/>
    </svg>
    <svg id="themeMoonIcon" class="theme-toggle-icon" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
        <path d="M21 12.8A9 9 0 1111.2 3a1 1 0 01.97 1.24 7 7 0 008.59 8.59A1 1 0 0121 12.8z"/>
    </svg>
    <span id="themeToggleLabel" class="theme-toggle-label">Dark</span>
</button>

<!-- Login Modal (shown when not authenticated or not admin) -->
<div class="login-modal-overlay" id="loginModalOverlay">
    <div class="login-modal">
        <div class="login-modal-header">
            <img src="../images/sdocabuyao_logo.svg" alt="DepEd Logo">
            <h2>Admin Login Required</h2>
            <p>Please log in with admin credentials to access the dashboard</p>
        </div>
        <div class="login-error" id="loginError"></div>
        <form id="loginForm">
            <div class="login-form-group">
                <label for="loginUsername">Username or Email</label>
                <input type="text" id="loginUsername" name="username" required autocomplete="username">
            </div>
            <div class="login-form-group">
                <label for="loginPassword">Password</label>
                <input type="password" id="loginPassword" name="password" required autocomplete="current-password">
            </div>
            <button type="submit" class="login-btn" id="loginBtn">Login as Admin</button>
        </form>
        <div class="back-link">
            <a href="../reclassification_form.php">< Back to Evaluation Form</a>
        </div>
    </div>
</div>

<!-- Admin Dashboard Content -->
<div class="dashboard-content" id="dashboardContent">
    <div class="admin-container">
        <div class="admin-header">
            <div class="admin-header-left">
                <img src="../images/sdocabuyao_logo.svg" alt="DepEd Logo">
                <div>
                    <h1>Reclassification Admin Dashboard</h1>
                    <p>Manage Performance Evaluation Records</p>
                </div>
            </div>
            <div class="admin-header-right">
                <div class="user-info">
                    <div class="username"><?php echo $isAuthenticated ? htmlspecialchars($currentUser['full_name'] ?? $currentUser['username']) : 'Guest'; ?></div>
                    <div class="role">Administrator</div>
                </div>
                <button class="logout-btn" id="logoutBtn">Logout</button>
            </div>
        </div>

        <div class="admin-content">
            <!-- Statistics Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-label">Total Records</div>
                    <div class="stat-value" id="statTotal">0</div>
                </div>
                <div class="stat-card passed">
                    <div class="stat-label">Passed</div>
                    <div class="stat-value" id="statPassed">0</div>
                </div>
                <div class="stat-card failed">
                    <div class="stat-label">Failed</div>
                    <div class="stat-value" id="statFailed">0</div>
                </div>
                <div class="stat-card">
                    <div class="stat-label">Pending Review</div>
                    <div class="stat-value" id="statPending">0</div>
                </div>
            </div>

            <!-- Toolbar -->
            <div class="toolbar">
                <div class="search-box">
                    <input type="text" id="searchInput" placeholder="Search by name, position, station...">
                </div>
                <div class="toolbar-actions">
                    <button class="btn btn-secondary" id="refreshBtn">Refresh</button>
                    <button class="btn btn-primary" id="newRecordBtn">+ New Evaluation</button>
                </div>
            </div>

            <!-- Records Table -->
            <table class="records-table" id="recordsTable">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Current Position</th>
                        <th>Applied Position</th>
                        <th>Station</th>
                        <th>Item Number</th>
                        <th>Result</th>
                        <th>Created By</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="recordsBody">
                    <tr>
                        <td colspan="9">
                            <div class="empty-state">
                                <div class="empty-state-icon">-</div>
                                <h3>Loading records...</h3>
                                <p>Please wait while we fetch the evaluation records</p>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
// Configuration
const isAuthenticated = <?php echo json_encode($isAuthenticated); ?>;
const isAdmin = <?php echo json_encode($isAdmin); ?>;
const currentUserId = <?php echo json_encode($currentUserId); ?>;
const currentUserRole = <?php echo json_encode($currentUserRole); ?>;

// DOM Elements
const loginModalOverlay = document.getElementById('loginModalOverlay');
const loginForm = document.getElementById('loginForm');
const loginBtn = document.getElementById('loginBtn');
const loginError = document.getElementById('loginError');
const dashboardContent = document.getElementById('dashboardContent');
const recordsBody = document.getElementById('recordsBody');
const searchInput = document.getElementById('searchInput');
const refreshBtn = document.getElementById('refreshBtn');
const newRecordBtn = document.getElementById('newRecordBtn');
const logoutBtn = document.getElementById('logoutBtn');
const themeToggle = document.getElementById('themeToggle');
const themeSunIcon = document.getElementById('themeSunIcon');
const themeMoonIcon = document.getElementById('themeMoonIcon');
const themeToggleLabel = document.getElementById('themeToggleLabel');
const themeStorageKey = 'rftp_admin_theme_mode';

// State
let allRecords = [];
let filteredRecords = [];

// Initialize
(function init() {
    applyTheme(getSavedTheme());

    if (isAuthenticated && isAdmin) {
        // User is authenticated as admin
        loginModalOverlay.style.display = 'none';
        dashboardContent.classList.add('authenticated');
        loadRecords();
    } else {
        // Show login modal
        loginModalOverlay.style.display = 'flex';
        dashboardContent.classList.remove('authenticated');
    }
})();

function applyTheme(mode) {
    const isDark = mode === 'dark';
    document.body.classList.toggle('dark-mode', isDark);

    if (themeToggle) {
        themeToggle.setAttribute('aria-pressed', isDark ? 'true' : 'false');
        themeToggle.setAttribute('title', isDark ? 'Switch to light mode' : 'Switch to dark mode');
        themeToggle.setAttribute('aria-label', isDark ? 'Switch to light mode' : 'Switch to dark mode');
    }
    if (themeSunIcon && themeMoonIcon) {
        themeSunIcon.classList.toggle('is-hidden', !isDark);
        themeMoonIcon.classList.toggle('is-hidden', isDark);
    }
    if (themeToggleLabel) {
        themeToggleLabel.textContent = isDark ? 'Light' : 'Dark';
    }
}

function getSavedTheme() {
    try {
        const savedTheme = localStorage.getItem(themeStorageKey);
        return savedTheme === 'dark' ? 'dark' : 'light';
    } catch (error) {
        return 'light';
    }
}

if (themeToggle) {
    themeToggle.addEventListener('click', () => {
        const nextTheme = document.body.classList.contains('dark-mode') ? 'light' : 'dark';
        applyTheme(nextTheme);
        try {
            localStorage.setItem(themeStorageKey, nextTheme);
        } catch (error) {
            // Ignore storage errors.
        }
    });
}

// Login Handler
loginForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const username = document.getElementById('loginUsername').value.trim();
    const password = document.getElementById('loginPassword').value;
    
    if (!username || !password) {
        showLoginError('Please enter both username and password');
        return;
    }
    
    loginBtn.disabled = true;
    loginBtn.textContent = 'Logging in...';
    loginError.classList.remove('show');
    
    try {
        const response = await fetch('../api/login.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ username, password })
        });
        
        const data = await response.json();
        
        if (data.success) {
            if (data.isAdmin) {
                // Reload page to update session
                window.location.reload();
            } else {
                showLoginError('Access denied. Admin credentials required.');
                loginBtn.disabled = false;
                loginBtn.textContent = 'Login as Admin';
            }
        } else {
            showLoginError(data.message || 'Login failed');
            loginBtn.disabled = false;
            loginBtn.textContent = 'Login as Admin';
        }
    } catch (error) {
        console.error('Login error:', error);
        showLoginError('An error occurred. Please try again.');
        loginBtn.disabled = false;
        loginBtn.textContent = 'Login as Admin';
    }
});

function showLoginError(message) {
    loginError.textContent = message;
    loginError.classList.add('show');
}

// Logout Handler
logoutBtn.addEventListener('click', async () => {
    if (confirm('Are you sure you want to logout?')) {
        try {
            await fetch('../admin/logout.php');
            window.location.reload();
        } catch (error) {
            console.error('Logout error:', error);
            window.location.reload();
        }
    }
});

// Load Records
async function loadRecords() {
    try {
        const response = await fetch('../api/performance_evaluations_list.php');
        const data = await response.json();
        
        if (data.success) {
            allRecords = data.records || [];
            filteredRecords = [...allRecords];
            renderRecords();
            updateStats();
        } else {
            showError('Failed to load records: ' + (data.message || 'Unknown error'));
        }
    } catch (error) {
        console.error('Load records error:', error);
        showError('Failed to load records. Please try again.');
    }
}

// Render Records
function renderRecords() {
    if (filteredRecords.length === 0) {
        recordsBody.innerHTML = `
            <tr>
                <td colspan="9">
                    <div class="empty-state">
                        <div class="empty-state-icon">-</div>
                        <h3>No records found</h3>
                        <p>${allRecords.length === 0 ? 'No evaluation records have been created yet' : 'Try adjusting your search criteria'}</p>
                    </div>
                </td>
            </tr>
        `;
        return;
    }
    
    recordsBody.innerHTML = filteredRecords.map(record => `
        <tr>
            <td><strong>${escapeHtml(record.name)}</strong></td>
            <td>${escapeHtml(record.current_position)}</td>
            <td>${escapeHtml(record.position_applied)}</td>
            <td>${escapeHtml(record.station || 'N/A')}</td>
            <td>${escapeHtml(record.item_number)}</td>
            <td>
                <span class="badge ${getBadgeClass(record.result)}">
                    ${escapeHtml(record.result || 'N/A')}
                </span>
            </td>
            <td>${escapeHtml(record.created_by_username || 'Unknown')}</td>
            <td>${formatDate(record.created_at)}</td>
            <td>
                <div class="action-btns">
                    <button class="action-btn action-btn-view" onclick="viewRecord(${record.id})">View</button>
                    <button class="action-btn action-btn-edit" onclick="editRecord(${record.id})">Edit</button>
                    <button class="action-btn action-btn-delete" onclick="deleteRecord(${record.id})">Delete</button>
                </div>
            </td>
        </tr>
    `).join('');
}

// Update Statistics
function updateStats() {
    const total = allRecords.length;
    const passed = allRecords.filter(r => r.result === 'PASSED').length;
    const failed = allRecords.filter(r => r.result === 'FAILED').length;
    const pending = allRecords.filter(r => !r.result || r.result === 'N/A').length;
    
    document.getElementById('statTotal').textContent = total;
    document.getElementById('statPassed').textContent = passed;
    document.getElementById('statFailed').textContent = failed;
    document.getElementById('statPending').textContent = pending;
}

// Search Handler
searchInput.addEventListener('input', (e) => {
    const query = e.target.value.toLowerCase().trim();
    
    if (!query) {
        filteredRecords = [...allRecords];
    } else {
        filteredRecords = allRecords.filter(record => 
            (record.name && record.name.toLowerCase().includes(query)) ||
            (record.current_position && record.current_position.toLowerCase().includes(query)) ||
            (record.position_applied && record.position_applied.toLowerCase().includes(query)) ||
            (record.station && record.station.toLowerCase().includes(query)) ||
            (record.item_number && record.item_number.toLowerCase().includes(query)) ||
            (record.result && record.result.toLowerCase().includes(query))
        );
    }
    
    renderRecords();
});

// Action Handlers
function viewRecord(id) {
    // Open record in view mode on main form
    window.open(`../reclassification_form.php?view=${id}`, '_blank');
}

function editRecord(id) {
    // Open record in edit mode on main form
    window.location.href = `../reclassification_form.php?edit=${id}`;
}

async function deleteRecord(id) {
    const record = allRecords.find(r => r.id === id);
    if (!record) return;
    
    if (!confirm(`Are you sure you want to delete the evaluation record for "${record.name}"?\n\nThis action cannot be undone.`)) {
        return;
    }
    
    try {
        const response = await fetch('../api/performance_evaluations_delete.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id })
        });
        
        const data = await response.json();
        
        if (data.success) {
            alert('Record deleted successfully');
            loadRecords();
        } else {
            alert('Failed to delete record: ' + (data.message || 'Unknown error'));
        }
    } catch (error) {
        console.error('Delete error:', error);
        alert('An error occurred while deleting the record');
    }
}

// Utility Functions
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function getBadgeClass(result) {
    if (!result || result === 'N/A') return 'badge-na';
    if (result === 'PASSED') return 'badge-passed';
    if (result === 'FAILED') return 'badge-failed';
    return 'badge-na';
}

function formatDate(dateString) {
    if (!dateString) return 'N/A';
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
}

function showError(message) {
    recordsBody.innerHTML = `
        <tr>
            <td colspan="9">
                <div class="empty-state">
                    <div class="empty-state-icon">!</div>
                    <h3>Error</h3>
                    <p>${escapeHtml(message)}</p>
                </div>
            </td>
        </tr>
    `;
}

// Event Listeners
refreshBtn.addEventListener('click', loadRecords);
newRecordBtn.addEventListener('click', () => {
    window.location.href = '../reclassification_form.php';
});
</script>

</body>
</html>
