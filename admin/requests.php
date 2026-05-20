<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

include '../includes/config.php';

// Handle search and filter
$search = isset($_GET['search']) ? $_GET['search'] : '';
$status_filter = isset($_GET['status']) ? $_GET['status'] : '';

// Build query
$sql = "SELECT dr.*, dt.document_name, CONCAT(u.firstname, ' ', u.lastname) as user_name, u.contact_number
        FROM document_requests dr
        JOIN document_types dt ON dr.document_type_id = dt.id
        JOIN users u ON dr.user_id = u.id
        WHERE 1=1";

$params = [];
$types = "";

if ($search) {
    $sql .= " AND (u.firstname LIKE ? OR u.lastname LIKE ? OR dt.document_name LIKE ?)";
    $search_param = "%$search%";
    $params[] = $search_param;
    $params[] = $search_param;
    $params[] = $search_param;
    $types .= "sss";
}

if ($status_filter) {
    $sql .= " AND dr.status = ?";
    $params[] = $status_filter;
    $types .= "s";
}

$sql .= " ORDER BY 
          CASE dr.status 
            WHEN 'pending' THEN 1 
            WHEN 'processing' THEN 2 
            WHEN 'approved' THEN 3 
            WHEN 'rejected' THEN 4 
          END,
          dr.request_date DESC";

$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$requests = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Requests - Admin Panel</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🏛️ Barangay Document Request System</h1>
            <p>Admin Control Panel</p>
        </div>
        
        <div class="navbar">
            <div>
                <a href="dashboard.php">Dashboard</a>
                <a href="requests.php">All Requests</a>
                <a href="users.php">Users</a>
                <a href="documents.php">Document Types</a>
            </div>
            <a href="../logout.php" class="logout">Logout</a>
        </div>
        
        <div class="content">
            <h2 style="margin-bottom: 20px;">📋 All Document Requests</h2>
            
            <!-- Search and Filter Form -->
            <form method="GET" style="background: #f7fafc; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
                <div style="display: grid; grid-template-columns: 1fr 1fr 150px; gap: 15px; align-items: end;">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label>Search</label>
                        <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Search by user name or document">
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
                <div style="overflow-x: auto;">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>User</th>
                                <th>Contact</th>
                                <th>Document</th>
                                <th>Purpose</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $requests->fetch_assoc()): ?>
                            <tr>
                                <td><strong>#<?php echo str_pad($row['id'], 4, '0', STR_PAD_LEFT); ?></strong></td>
                                <td><?php echo htmlspecialchars($row['user_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['contact_number']); ?></td>
                                <td><?php echo htmlspecialchars($row['document_name']); ?></td>
                                <td><?php echo htmlspecialchars(substr($row['purpose'], 0, 40)) . (strlen($row['purpose']) > 40 ? '...' : ''); ?></td>
                                <td>
                                    <span class="status-badge status-<?php echo $row['status']; ?>">
                                        <?php echo ucfirst($row['status']); ?>
                                    </span>
                                </td>
                                <td><?php echo date('M d, Y', strtotime($row['request_date'])); ?></td>
                                <td>
                                    <a href="view-request.php?id=<?php echo $row['id']; ?>" class="btn btn-primary" style="padding: 6px 12px; font-size: 12px;">Manage</a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="no-data">
                    <span style="font-size: 48px;">📭</span>
                    <p>No requests found matching your criteria.</p>
                    <?php if ($search || $status_filter): ?>
                        <a href="requests.php" class="btn btn-secondary" style="margin-top: 15px;">Clear Filters</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
