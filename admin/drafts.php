<?php
session_start();
require_once(__DIR__ . '/../classes/DBConnection.php');
require_once(__DIR__ . '/../classes/AuthenticationHelper.php');
require_once(__DIR__ . '/../includes/banners.php');

$conn = DBConnection::getConnection();
if (!$conn) die('DB connection failed');

// Require admin authentication
$auth = new AuthenticationHelper($conn);
$auth->requireAdmin('/admin/login');

// Get current user info
$currentUser = $auth->getCurrentUser();

// Handle load/delete actions
if (isset($_GET['load'])) {
    $id = intval($_GET['load']);
    $row = $conn->query("SELECT data FROM drafts WHERE id={$id} LIMIT 1");
    if ($row && $row->num_rows) {
        $d = $row->fetch_assoc();
        $_SESSION['loaded_draft'] = json_decode($d['data'], true);
        setBannerMessage('success', 'Draft loaded into session. Visit the form to restore.');
        header('Location: ../index.php'); exit;
    } else {
        setBannerMessage('error', 'Draft not found');
        header('Location: drafts.php'); exit;
    }
}

if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM drafts WHERE id={$id} LIMIT 1");
    setBannerMessage('success', 'Draft deleted');
    header('Location: drafts.php'); exit;
}

$sql = "SELECT id, session_id, application_code, created_at, updated_at, data FROM drafts ORDER BY updated_at DESC LIMIT 200";
$res = $conn->query($sql);
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Drafts - Admin</title>
    <?php require_once(__DIR__ . '/../includes/favicon.php'); ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/banners.css">
    <link rel="stylesheet" href="../css/design-system.css">
    <link rel="stylesheet" href="../css/admin-enhanced.css">
</head>
<body>
    <div class="admin-container">
        <div class="admin-header">
            <h1><i class="fas fa-file-alt" style="margin-right: 12px;"></i>Saved Drafts</h1>
            <p>Manage all application drafts and restore them back into the system</p>
        </div>

        <?php displayBannerFromSession(); ?>

        <div class="card mb-lg">
            <a href="../admin/index.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i>Back to Admin Dashboard</a>
        </div>

        <div class="card">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Draft ID</th>
                            <th>Session</th>
                            <th>Application Code</th>
                            <th>Last Updated</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $res->fetch_assoc()): ?>
                            <tr>
                                <td><strong>#<?php echo $row['id']; ?></strong></td>
                                <td><code style="background: var(--bg-tertiary); padding: 4px 8px; border-radius: 4px; font-size: var(--font-size-xs);"><?php echo htmlspecialchars(substr($row['session_id'], 0, 20)); ?>...</code></td>
                                <td><?php echo htmlspecialchars($row['application_code']); ?></td>
                                <td><?php echo date('M d, Y H:i', strtotime($row['updated_at'])); ?></td>
                                <td>
                                    <div class="d-flex gap-sm">
                                        <a href="?load=<?php echo $row['id']; ?>" class="btn btn-primary btn-sm"><i class="fas fa-download"></i>Load</a>
                                        <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this draft?');"><i class="fas fa-trash"></i>Delete</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
