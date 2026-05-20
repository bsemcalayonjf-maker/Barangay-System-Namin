<?php
/**
 * ========================================
 * MY REQUESTS PAGE (with Search/Filter)
 * ========================================
 */
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

include '../includes/config.php';
include '../includes/functions.php';

$user_id = $_SESSION['user_id'];

// Handle search and filter
$search = isset($_GET['search']) ? sanitize_input($_GET['search']) : '';
$status_filter = isset($_GET['status']) ? $_GET['status'] : '';

// Build query
$sql = "SELECT dr.*, dt.document_name 
        FROM document_requests dr 
        JOIN document_types dt ON dr.document_type_id = dt.id 
        WHERE dr.user_id = ?";

$params = [$user_id];
$types = "i";

if ($search) {
    $sql .= " AND (dt.document_name LIKE ? OR dr.purpose LIKE ?)";
    $search_param = "%$search%";
    $params[] = $search_param;
    $params[] = $search_param;
    $types .= "ss";
}

if ($status_filter) {
    $sql .= " AND dr.status = ?";
    $params[] = $status_filter;
    $types .= "s";
}

$sql .= " ORDER BY dr.request_date DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$requests = $stmt->get_result();

$page_title = "My Requests - Barangay System";
$css_path = "../";
include '../includes/header.php';
?>

<div class="container">
    <div class="header">
        <h1>🏛️ Barangay Document Request System</h1>
        <p>Resident Portal</p>
    </div>
    
    <div class="navbar">
        <div>
            <a href="dashboard.php">Dashboard</a>
            <a href="request.php">New Request</a>
            <a href="my-requests.php">My Requests</a>
        </div>
        <a href="../logout.php" class="logout">Logout</a>
    </div>
    
    <div class="content">
        <h2 style="margin-bottom: 20px;">📋 My Document Requests</h2>
        
        <!-- Search and Filter Form -->
        <form method="GET" style="background: #f7fafc; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
            <div style="display: grid; grid-template-columns: 1fr 1fr 150px; gap: 15px; align-items: end;">
                <div class="form-group" style="margin-bottom: 0;">
                    <label>Search</label>
                    <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Search by document or purpose">
                </div>
                
                <div class="form-group" style="margin-bottom: 0;">
                    <label>Status Filter</label>
                    <select name="status">
                        <option value="">All Status</option>
                        <option value="pending" <?php echo $status_filter == 'pending' ? 'selected' : ''; ?>>Pending</option>
                        <option value="processing" <?php echo $status_filter == 'processing' ? 'selected' : ''; ?>>Processing</option>
                        <option value="approved" <?php echo $status_filter == 'approved' ? 'selected' : ''; ?>>Approved</option>
                        <option value="rejected" <?php echo $status_filter == 'rejected' ? 'selected' : ''; ?>>Rejected</option>
                    </select>
                </div>
                
                <div>
                    <button type="submit" class="btn btn-primary" style="width: 100%;">🔍 Search</button>
                </div>
            </div>
        </form>
        
        <?php if ($requests->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Request ID</th>
                        <th>Document Type</th>
                        <th>Purpose</th>
                        <th>Status</th>
                        <th>Date Requested</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $requests->fetch_assoc()): ?>
                    <tr>
                        <td><strong><?php echo format_request_id($row['id']); ?></strong></td>
                        <td><?php echo htmlspecialchars($row['document_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['purpose']); ?></td>
                        <td><?php echo get_status_badge($row['status']); ?></td>
                        <td><?php echo format_datetime($row['request_date']); ?></td>
                        <td>
                            <?php if ($row['remarks']): ?>
                                <?php echo htmlspecialchars($row['remarks']); ?>
                            <?php else: ?>
                                <span style="color: #718096;">-</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="no-data">
                <span>📭</span>
                <p>No requests found matching your criteria.</p>
                <?php if ($search || $status_filter): ?>
                    <a href="my-requests.php" class="btn btn-secondary" style="margin-top: 15px;">Clear Filters</a>
                <?php else: ?>
                    <a href="request.php" class="btn btn-primary" style="margin-top: 15px;">Submit New Request</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
