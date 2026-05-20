<?php
/**
 * ========================================
 * USER DASHBOARD
 * ========================================
 */
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

include '../includes/config.php';
include '../includes/functions.php';

$user_id = $_SESSION['user_id'];

// Get user information
$user_stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$user_stmt->bind_param("i", $user_id);
$user_stmt->execute();
$user = $user_stmt->get_result()->fetch_assoc();

// Get request statistics
$stats_sql = "SELECT 
    COUNT(*) as total,
    SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
    SUM(CASE WHEN status = 'processing' THEN 1 ELSE 0 END) as processing,
    SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as approved,
    SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) as rejected
FROM document_requests WHERE user_id = ?";
$stats_stmt = $conn->prepare($stats_sql);
$stats_stmt->bind_param("i", $user_id);
$stats_stmt->execute();
$stats = $stats_stmt->get_result()->fetch_assoc();

// Get recent requests (last 5)
$requests_sql = "SELECT dr.*, dt.document_name 
                 FROM document_requests dr 
                 JOIN document_types dt ON dr.document_type_id = dt.id 
                 WHERE dr.user_id = ? 
                 ORDER BY dr.request_date DESC 
                 LIMIT 5";
$requests_stmt = $conn->prepare($requests_sql);
$requests_stmt->bind_param("i", $user_id);
$requests_stmt->execute();
$requests = $requests_stmt->get_result();

$page_title = "User Dashboard - Barangay System";
$css_path = "../";
include '../includes/header.php';
?>

<div class="container">
    <!-- Header -->
    <div class="header">
        <h1>🏛️ Barangay Document Request System</h1>
        <p>Resident Portal</p>
    </div>
    
    <!-- Navigation -->
    <div class="navbar">
        <div>
            <a href="dashboard.php">Dashboard</a>
            <a href="request.php">New Request</a>
            <a href="my-requests.php">My Requests</a>
        </div>
        <a href="../logout.php" class="logout">Logout</a>
    </div>
    
    <!-- Content -->
    <div class="content">
        <!-- Welcome Box -->
        <div class="welcome-box">
            <h2>Welcome, <?php echo htmlspecialchars($user['firstname'] . ' ' . $user['lastname']); ?>! 👋</h2>
            <p>Manage your document requests here.</p>
        </div>
        
        <!-- Statistics Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <h3><?php echo $stats['total']; ?></h3>
                <p>Total Requests</p>
            </div>
            
            <div class="stat-card warning">
                <h3><?php echo $stats['pending']; ?></h3>
                <p>Pending</p>
            </div>
            
            <div class="stat-card info">
                <h3><?php echo $stats['processing']; ?></h3>
                <p>Processing</p>
            </div>
            
            <div class="stat-card success">
                <h3><?php echo $stats['approved']; ?></h3>
                <p>Approved</p>
            </div>
        </div>
        
        <!-- Recent Requests -->
        <h3 style="margin-bottom: 20px;">Recent Requests</h3>
        
        <?php if ($requests->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Request ID</th>
                        <th>Document Type</th>
                        <th>Purpose</th>
                        <th>Status</th>
                        <th>Date Requested</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $requests->fetch_assoc()): ?>
                    <tr>
                        <td><strong><?php echo format_request_id($row['id']); ?></strong></td>
                        <td><?php echo htmlspecialchars($row['document_name']); ?></td>
                        <td><?php echo truncate_text($row['purpose'], 50); ?></td>
                        <td><?php echo get_status_badge($row['status']); ?></td>
                        <td><?php echo format_date($row['request_date']); ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            
            <div style="margin-top: 20px; text-align: center;">
                <a href="my-requests.php" class="btn btn-secondary">View All Requests</a>
            </div>
        <?php else: ?>
            <div class="no-data">
                <span>📋</span>
                <p>No requests yet. Click "New Request" to submit your first document request.</p>
                <a href="request.php" class="btn btn-primary" style="margin-top: 15px;">Submit New Request</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
