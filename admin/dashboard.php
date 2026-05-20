<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

include '../includes/config.php';

// Get statistics
$stats_sql = "SELECT 
    COUNT(*) as total,
    SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
    SUM(CASE WHEN status = 'processing' THEN 1 ELSE 0 END) as processing,
    SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as approved,
    SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) as rejected
FROM document_requests";
$stats = $conn->query($stats_sql)->fetch_assoc();

// Get total users
$total_users = $conn->query("SELECT COUNT(*) as count FROM users")->fetch_assoc()['count'];

// Get recent requests
$recent_sql = "SELECT dr.*, dt.document_name, CONCAT(u.firstname, ' ', u.lastname) as user_name
               FROM document_requests dr
               JOIN document_types dt ON dr.document_type_id = dt.id
               JOIN users u ON dr.user_id = u.id
               ORDER BY dr.request_date DESC
               LIMIT 10";
$recent_requests = $conn->query($recent_sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Barangay Document Request System</title>
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
            <div class="welcome-box">
                <h2>Welcome, <?php echo htmlspecialchars($_SESSION['admin_name']); ?>! 👨‍💼</h2>
                <p>Manage document requests and system settings here.</p>
            </div>
            
            <div class="stats-grid">
                <div class="stat-card">
                    <h3><?php echo $stats['total']; ?></h3>
                    <p>Total Requests</p>
                </div>
                
                <div class="stat-card warning">
                    <h3><?php echo $stats['pending']; ?></h3>
                    <p>Pending</p>
                </div>
                
                <div class="stat-card" style="background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%);">
                    <h3><?php echo $stats['processing']; ?></h3>
                    <p>Processing</p>
                </div>
                
                <div class="stat-card success">
                    <h3><?php echo $stats['approved']; ?></h3>
                    <p>Approved</p>
                </div>
                
                <div class="stat-card danger">
                    <h3><?php echo $stats['rejected']; ?></h3>
                    <p>Rejected</p>
                </div>
                
                <div class="stat-card" style="background: linear-gradient(135deg, #9f7aea 0%, #805ad5 100%);">
                    <h3><?php echo $total_users; ?></h3>
                    <p>Total Users</p>
                </div>
            </div>
            
            <h3 style="margin-bottom: 20px;">Recent Requests</h3>
            
            <?php if ($recent_requests->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>Document</th>
                            <th>Purpose</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $recent_requests->fetch_assoc()): ?>
                        <tr>
                            <td><strong>#<?php echo str_pad($row['id'], 4, '0', STR_PAD_LEFT); ?></strong></td>
                            <td><?php echo htmlspecialchars($row['user_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['document_name']); ?></td>
                            <td><?php echo htmlspecialchars(substr($row['purpose'], 0, 50)) . (strlen($row['purpose']) > 50 ? '...' : ''); ?></td>
                            <td>
                                <span class="status-badge status-<?php echo $row['status']; ?>">
                                    <?php echo ucfirst($row['status']); ?>
                                </span>
                            </td>
                            <td><?php echo date('M d, Y', strtotime($row['request_date'])); ?></td>
                            <td>
                                <a href="view-request.php?id=<?php echo $row['id']; ?>" class="btn btn-primary" style="padding: 6px 12px; font-size: 12px;">View</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                
                <div style="margin-top: 20px; text-align: center;">
                    <a href="requests.php" class="btn btn-secondary">View All Requests</a>
                </div>
            <?php else: ?>
                <div class="no-data">
                    <span style="font-size: 48px;">📋</span>
                    <p>No requests yet.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
