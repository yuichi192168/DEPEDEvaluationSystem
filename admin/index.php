<?php
/**
 * Admin Dashboard Redirect
 * Redirects to new single-page dashboard
 * PROTECTED: Admin authentication required
 */

// Configure session settings before starting session
ini_set('session.cookie_httponly', '1');
ini_set('session.cookie_samesite', 'Lax');
ini_set('session.gc_maxlifetime', '28800');

session_start();
require_once(__DIR__ . '/../classes/DBConnection.php');
require_once(__DIR__ . '/../classes/AuthenticationHelper.php');

$conn = DBConnection::getConnection();
if (!$conn) die('Database connection failed');

// Require admin authentication
$auth = new AuthenticationHelper($conn);
$auth->requireAdmin('/admin/login');

// Redirect to new dashboard
header('Location: dashboard.php');
exit;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - DepEd HRMPSB Evaluation System</title>
    <?php require_once(__DIR__ . '/../includes/favicon.php'); ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/banners.css">
    <link rel="stylesheet" href="../css/design-system.css">
    <link rel="stylesheet" href="../css/admin-enhanced.css">
</head>
<body>
    <div class="admin-container">
        <div class="admin-header">
            <div style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
                <div>
                    <h1><i class="fas fa-sliders-h" style="margin-right: 12px;"></i>Admin Dashboard</h1>
                    <p>Manage applicants, drafts, and system settings</p>
                </div>
                <div style="text-align: right;">
                    <p style="margin: 0 0 8px 0; font-size: var(--font-size-sm); opacity: 0.95;">
                        Logged in as: <strong><?php echo htmlspecialchars($currentUser['full_name'] ?? $currentUser['username']); ?></strong>
                    </p>
                    <a href="logout.php" class="btn btn-sm" style="background: rgba(255,255,255,0.2); color: white;">
                        <i class="fas fa-sign-out-alt"></i>Logout
                    </a>
                </div>
            </div>
        </div>

        <?php displayBannerFromSession(); ?>

        <div class="alert alert-success" style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 30px;">
            <div>
                <h4 style="margin: 0 0 8px 0;"><i class="fas fa-star" style="margin-right: 8px;"></i>New: Single-Page Dashboard Available!</h4>
                <p style="margin: 0;">Experience the modern, redesigned admin interface with smooth navigation and no page reloads.</p>
            </div>
            <a href="dashboard.php" class="btn btn-primary" style="white-space: nowrap;">
                <i class="fas fa-rocket"></i> Open New Dashboard
            </a>
        </div>

        <div class="alert alert-info">
            <p>Welcome to the Admin Dashboard. Use the tools below to manage applicants, view saved drafts, and monitor system activity.</p>
        </div>

        <h2 class="mb-lg"><i class="fas fa-tasks" style="margin-right: 10px; color: var(--admin-primary);"></i>Core Management</h2>

        <div class="grid grid-auto">
            <div class="card">
                <h3 class="card-title"><span class="card-icon"><i class="fas fa-users"></i></span>Applicants Management</h3>
                <p class="card-text">View, search, filter, and archive applicants. Manage the active applicants list efficiently with bulk archiving and restoration features.</p>
                <div class="d-flex gap-sm">
                    <a href="applicants.php" class="btn btn-primary">Open Dashboard</a>
                    <a href="applicants.php?tab=active" class="btn btn-secondary btn-sm">Active List</a>
                    <a href="applicants.php?tab=archived" class="btn btn-secondary btn-sm">Archived</a>
                </div>
            </div>

            <div class="card">
                <h3 class="card-title"><span class="card-icon"><i class="fas fa-file-alt"></i></span>Saved Drafts</h3>
                <p class="card-text">View and manage all saved application drafts. Load drafts back into the system or delete them permanently.</p>
                <div class="d-flex gap-sm">
                    <a href="drafts.php" class="btn btn-primary">View Drafts</a>
                </div>
            </div>

            <div class="card">
                <h3 class="card-title"><span class="card-icon"><i class="fas fa-arrow-circle-left"></i></span>Return to Main</h3>
                <p class="card-text">Go back to the main evaluation system interface to continue with applicant evaluations and assessments.</p>
                <div class="d-flex gap-sm">
                    <a href="../index.php" class="btn btn-primary">Go to Main System</a>
                </div>
            </div>
        </div>

        <h2 class="mb-lg mt-xl"><i class="fas fa-book" style="margin-right: 10px; color: var(--admin-primary);"></i>About Applicant Management</h2>

        <div class="card">
            <h3 class="mb-lg">Features Overview</h3>
            
            <div class="grid grid-auto">
                <div>
                    <h4 style="color: var(--admin-primary); margin-bottom: var(--spacing-sm); font-size: var(--font-size-lg);"><i class="fas fa-search" style="margin-right: 8px;"></i>Search & Filter</h4>
                    <p class="text-secondary" style="font-size: var(--font-size-sm);">Search applicants by name and filter by position group (Teaching, Non-Teaching Level I/II, Related Teaching, Higher Teaching, School Administration).</p>
                </div>

                <div>
                    <h4 style="color: var(--admin-primary); margin-bottom: var(--spacing-sm); font-size: var(--font-size-lg);"><i class="fas fa-archive" style="margin-right: 8px;"></i>Archiving</h4>
                    <p class="text-secondary" style="font-size: var(--font-size-sm);">Archive inactive or processed applicants to keep the active list clean and manageable with large datasets.</p>
                </div>

                <div>
                    <h4 style="color: var(--admin-primary); margin-bottom: var(--spacing-sm); font-size: var(--font-size-lg);"><i class="fas fa-undo" style="margin-right: 8px;"></i>Restoration</h4>
                    <p class="text-secondary" style="font-size: var(--font-size-sm);">Restore archived applicants back to the active list at any time without losing their evaluation history.</p>
                </div>

                <div>
                    <h4 style="color: var(--admin-primary); margin-bottom: var(--spacing-sm); font-size: var(--font-size-lg);"><i class="fas fa-chart-bar" style="margin-right: 8px;"></i>Statistics</h4>
                    <p class="text-secondary" style="font-size: var(--font-size-sm);">View comprehensive statistics about active and archived applicants across all position groups.</p>
                </div>

                <div>
                    <h4 style="color: var(--admin-primary); margin-bottom: var(--spacing-sm); font-size: var(--font-size-lg);"><i class="fas fa-tasks" style="margin-right: 8px;"></i>Bulk Operations</h4>
                    <p class="text-secondary" style="font-size: var(--font-size-sm);">Archive or restore multiple applicants at once with a single reason, saving time on batch processing.</p>
                </div>

                <div>
                    <h4 style="color: var(--admin-primary); margin-bottom: var(--spacing-sm); font-size: var(--font-size-lg);"><i class="fas fa-history" style="margin-right: 8px;"></i>Audit Trail</h4>
                    <p class="text-secondary" style="font-size: var(--font-size-sm);">Every archive and restore action is logged with timestamps and reasons for complete traceability.</p>
                </div>
            </div>

            <h3 class="mt-xl mb-md">Database Setup</h3>
            <p class="text-secondary mb-sm" style="font-size: var(--font-size-sm);">
                Before using the Applicants Management Dashboard, you need to run the database migration script to add the archiving tables and columns.
            </p>
            <p class="text-secondary" style="font-size: var(--font-size-sm);">
                Navigate to your database management tool (phpMyAdmin, MySQL Workbench, etc.) and execute the SQL script located at:
                <br><code style="background: var(--bg-tertiary); padding: 2px 6px; border-radius: 3px;">database/migration_add_archiving.sql</code>
            </p>

            <div class="alert alert-warning mt-lg">
                <strong><i class="fas fa-exclamation-triangle" style="margin-right: 6px;"></i>Important:</strong> Run the migration script in your database before using the archiving features.
                This will add the necessary columns and tables to support applicant archiving.
            </div>
        </div>

        <div class="text-center mt-2xl" style="padding: var(--spacing-lg); color: var(--text-muted); font-size: var(--font-size-sm);">
            <p>DepEd HRMPSB Evaluation System - Admin Dashboard</p>
            <p>© 2026 | System Version 2.0</p>
        </div>
    </div>
</body>
</html>
