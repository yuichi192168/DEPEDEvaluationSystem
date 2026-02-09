<?php
/**
 * Dashboard AJAX Handler
 * Processes all AJAX requests for the single-page admin dashboard
 */

session_start();
require_once(__DIR__ . '/../classes/DBConnection.php');
require_once(__DIR__ . '/../classes/AuthenticationHelper.php');
require_once(__DIR__ . '/../classes/ApplicantManager.php');

header('Content-Type: application/json');

$conn = DBConnection::getConnection();
if (!$conn) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit;
}

// Require admin authentication
$auth = new AuthenticationHelper($conn);
if (!$auth->isAdmin()) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$manager = new ApplicantManager($conn);
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';

function positionGroupSlug($group) {
    $group = strtolower(trim((string)$group));
    $group = preg_replace('/[^a-z0-9]+/', '-', $group);
    return trim($group, '-');
}

function positionGroupLabel($group) {
    $label = trim((string)$group);
    return $label !== '' ? $label : 'Unspecified';
}

try {
    switch ($action) {
        case 'load_applicants':
            loadApplicants();
            break;
            
        case 'load_drafts':
            loadDrafts();
            break;
            
        case 'archive':
            archiveApplicant();
            break;
            
        case 'restore':
            restoreApplicant();
            break;
            
        case 'load_draft':
            loadDraft();
            break;
            
        case 'delete_draft':
            deleteDraft();
            break;
            
        default:
            echo json_encode(['success' => false, 'message' => 'Invalid action']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}

/**
 * Load Applicants Table
 */
function loadApplicants() {
    global $manager;
    
    $tab = isset($_GET['tab']) ? $_GET['tab'] : 'active';
    $search = isset($_GET['search']) ? trim($_GET['search']) : '';
    $group = isset($_GET['group']) ? trim($_GET['group']) : '';
    $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
    $itemsPerPage = 20;
    $offset = ($page - 1) * $itemsPerPage;
    
    // Get applicants
    if ($tab === 'archived') {
        $applicants = $manager->getArchivedApplicants($search, $group, $itemsPerPage, $offset);
        $totalCount = $manager->getArchivedApplicantsCount($search, $group);
    } else {
        $applicants = $manager->getActiveApplicants($search, $group, $itemsPerPage, $offset);
        $totalCount = $manager->getActiveApplicantsCount($search, $group);
    }
    
    $totalPages = ceil($totalCount / $itemsPerPage);
    
    // Generate HTML
    ob_start();
    ?>
    <?php if (count($applicants) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Position Applied</th>
                    <th>Group</th>
                    <th>Created Date</th>
                    <?php if ($tab === 'archived'): ?>
                        <th>Archive Reason</th>
                    <?php endif; ?>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($applicants as $applicant): ?>
                    <tr>
                        <td><strong>#<?php echo $applicant['id']; ?></strong></td>
                        <td style="font-weight: 500; color: var(--admin-primary);">
                            <?php echo htmlspecialchars($applicant['name']); ?>
                        </td>
                        <td><?php echo htmlspecialchars($applicant['position_name'] ?? 'Not specified'); ?></td>
                        <td>
                            <span class="badge badge-group-<?php echo positionGroupSlug($applicant['actual_group']); ?>">
                                <?php echo htmlspecialchars(positionGroupLabel($applicant['actual_group'])); ?>
                            </span>
                        </td>
                        <td><?php echo date('M d, Y', strtotime($applicant['created_at'])); ?></td>
                        <?php if ($tab === 'archived'): ?>
                            <td>
                                <?php if (!empty($applicant['archive_reason'])): ?>
                                    <?php echo htmlspecialchars($applicant['archive_reason']); ?>
                                <?php else: ?>
                                    <span style="color: #999;">-</span>
                                <?php endif; ?>
                            </td>
                        <?php endif; ?>
                        <td>
                            <div class="actions">
                                <button class="btn btn-secondary btn-small" onclick="viewDetails(<?php echo $applicant['id']; ?>)">
                                    <i class="fas fa-eye"></i> View
                                </button>
                                <?php if ($tab === 'active'): ?>
                                    <button class="btn btn-danger btn-small" 
                                        data-id="<?php echo $applicant['id']; ?>" 
                                        data-name="<?php echo htmlspecialchars($applicant['name'], ENT_QUOTES); ?>"
                                        onclick="archiveApplicant(this.dataset.id, this.dataset.name)">
                                        <i class="fas fa-archive"></i> Archive
                                    </button>
                                <?php else: ?>
                                    <button class="btn btn-success btn-small" 
                                        data-id="<?php echo $applicant['id']; ?>" 
                                        data-name="<?php echo htmlspecialchars($applicant['name'], ENT_QUOTES); ?>"
                                        onclick="restoreApplicant(this.dataset.id, this.dataset.name)">
                                        <i class="fas fa-undo"></i> Restore
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
                    <a onclick="goToPage(1)">First</a>
                    <a onclick="goToPage(<?php echo $page - 1; ?>)">← Previous</a>
                <?php endif; ?>
                
                <?php
                $start = max(1, $page - 2);
                $end = min($totalPages, $page + 2);
                
                for ($i = $start; $i <= $end; $i++):
                ?>
                    <?php if ($i === $page): ?>
                        <span class="active"><?php echo $i; ?></span>
                    <?php else: ?>
                        <a onclick="goToPage(<?php echo $i; ?>)"><?php echo $i; ?></a>
                    <?php endif; ?>
                <?php endfor; ?>
                
                <?php if ($page < $totalPages): ?>
                    <a onclick="goToPage(<?php echo $page + 1; ?>)">Next →</a>
                    <a onclick="goToPage(<?php echo $totalPages; ?>)">Last</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    <?php else: ?>
        <div class="empty-state">
            <i class="fas fa-inbox"></i>
            <h3>No Applicants Found</h3>
            <p><?php echo $tab === 'active' ? 'No active applicants yet.' : 'No archived applicants yet.'; ?></p>
        </div>
    <?php endif; ?>
    <?php
    $html = ob_get_clean();
    
    // Get updated stats
    $stats = $manager->getStatistics();
    
    echo json_encode([
        'success' => true,
        'html' => $html,
        'stats' => $stats,
        'totalPages' => $totalPages,
        'currentPage' => $page,
        'totalCount' => $totalCount
    ]);
}

/**
 * Load Drafts Table
 */
function loadDrafts() {
    global $conn;
    
        $sql = "SELECT id, session_id, application_code, created_at, updated_at, data 
            FROM drafts 
            ORDER BY updated_at DESC 
            LIMIT 200";
    $result = $conn->query($sql);
    
    ob_start();
    ?>
    <?php if ($result && $result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Draft ID</th>
                    <th>Session</th>
                    <th>Applicant Name</th>
                    <th>Last Updated</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><strong>#<?php echo $row['id']; ?></strong></td>
                        <td>
                            <code style="background: #f8f9fa; padding: 4px 8px; border-radius: 4px; font-size: 12px;">
                                <?php echo htmlspecialchars(substr($row['session_id'], 0, 20)); ?>...
                            </code>
                        </td>
                        <td>
                            <?php
                                $applicantName = '';
                                if (!empty($row['data'])) {
                                    $decoded = json_decode($row['data'], true);
                                    if (is_array($decoded) && !empty($decoded['applicant_name'])) {
                                        $applicantName = $decoded['applicant_name'];
                                    }
                                }
                                $displayName = $applicantName ?: ($row['application_code'] ?: 'Unknown');
                                echo htmlspecialchars($displayName);
                            ?>
                        </td>
                        <td><?php echo date('M d, Y H:i', strtotime($row['updated_at'])); ?></td>
                        <td>
                            <div class="actions">
                                <button class="btn btn-primary btn-small" onclick="loadDraft(<?php echo $row['id']; ?>)">
                                    <i class="fas fa-download"></i> Load
                                </button>
                                <button class="btn btn-danger btn-small" onclick="deleteDraft(<?php echo $row['id']; ?>)">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="empty-state">
            <i class="fas fa-inbox"></i>
            <h3>No Drafts Found</h3>
            <p>No saved application drafts available.</p>
        </div>
    <?php endif; ?>
    <?php
    $html = ob_get_clean();
    
    echo json_encode([
        'success' => true,
        'html' => $html
    ]);
}

/**
 * Archive Applicant
 */
function archiveApplicant() {
    global $manager;
    
    // Debug logging - check what we receive
    error_log("=== ARCHIVE REQUEST START ===");
    error_log("POST data received: " . print_r($_POST, true));
    
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $reason = isset($_POST['reason']) ? trim($_POST['reason']) : '';
    $archivedBy = isset($_POST['archived_by']) ? trim($_POST['archived_by']) : 'Admin';
    
    error_log("Parsed - ID: $id (type: " . gettype($id) . "), Reason: '$reason', ArchivedBy: '$archivedBy'");
    
    if ($id <= 0) {
        error_log("FAILED: Invalid ID - received ID value: " . var_export($_POST['id'] ?? 'NOT SET', true));
        echo json_encode([
            'success' => false, 
            'message' => 'Invalid applicant ID',
            'debug' => [
                'received_id' => $_POST['id'] ?? 'NOT SET',
                'parsed_id' => $id,
                'post_data' => $_POST
            ]
        ]);
        return;
    }
    
    error_log("Calling archiveApplicant($id, '$reason', '$archivedBy')");
    
    // Correct parameter order: archiveApplicant($id, $reason, $archivedBy)
    $result = $manager->archiveApplicant($id, $reason, $archivedBy);
    
    error_log("Result: " . ($result['success'] ? 'SUCCESS' : 'FAILED - ' . ($result['message'] ?? 'unknown error')));
    error_log("=== ARCHIVE REQUEST END ===");
    
    if ($result['success']) {
        echo json_encode(['success' => true, 'message' => 'Applicant archived successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => $result['message'] ?? 'Failed to archive applicant']);
    }
}

/**
 * Restore Applicant
 */
function restoreApplicant() {
    global $manager;
    
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    
    if ($id <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid applicant ID']);
        return;
    }
    
    $result = $manager->restoreApplicant($id, 'Admin');
    
    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Applicant restored successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to restore applicant']);
    }
}

/**
 * Load Draft into Session
 */
function loadDraft() {
    global $conn;
    
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    
    if ($id <= 0) {
        header('Location: dashboard.php');
        exit;
    }
    
    $stmt = $conn->prepare("SELECT data FROM drafts WHERE id = ? LIMIT 1");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION['loaded_draft'] = json_decode($row['data'], true);
        $_SESSION['banner_type'] = 'success';
        $_SESSION['banner_message'] = 'Draft loaded successfully. Visit the form to restore.';
        header('Location: ../index.php');
    } else {
        $_SESSION['banner_type'] = 'error';
        $_SESSION['banner_message'] = 'Draft not found';
        header('Location: dashboard.php');
    }
    exit;
}

/**
 * Delete Draft
 */
function deleteDraft() {
    global $conn;
    
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    
    if ($id <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid draft ID']);
        return;
    }
    
    $stmt = $conn->prepare("DELETE FROM drafts WHERE id = ? LIMIT 1");
    $stmt->bind_param('i', $id);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Draft deleted successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to delete draft']);
    }
}
?>
