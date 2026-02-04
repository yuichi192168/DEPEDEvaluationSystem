<?php
session_start();
require_once(__DIR__ . '/../classes/DBConnection.php');
require_once(__DIR__ . '/../includes/banners.php');

$conn = DBConnection::getConnection();
if (!$conn) die('DB connection failed');

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
    <link rel="stylesheet" href="../css/banners.css">
    <style>body{font-family:Segoe UI,Arial;padding:20px}</style>
</head>
<body>
    <h1>Saved Drafts</h1>
    <?php displayBannerFromSession(); ?>
    <table border="1" cellpadding="6" cellspacing="0">
        <thead><tr><th>ID</th><th>Session</th><th>Application Code</th><th>Updated</th><th>Actions</th></tr></thead>
        <tbody>
        <?php while($row = $res->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo htmlspecialchars($row['session_id']); ?></td>
                <td><?php echo htmlspecialchars($row['application_code']); ?></td>
                <td><?php echo $row['updated_at']; ?></td>
                <td><a href="?load=<?php echo $row['id']; ?>">Load</a> | <a href="?delete=<?php echo $row['id']; ?>">Delete</a></td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>
