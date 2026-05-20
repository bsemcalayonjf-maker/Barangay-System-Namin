<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

include '../includes/config.php';

// Handle search
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Build query
$sql = "SELECT u.*, 
        (SELECT COUNT(*) FROM document_requests WHERE user_id = u.id) as total_requests,
        (SELECT COUNT(*) FROM document_requests WHERE user_id = u.id AND status = 'pending') as pending_requests
        FROM users u
        WHERE 1=1";

$params = [];
$types = "";

if ($search) {
    $sql .= " AND (u.firstname LIKE ? OR u.lastname LIKE ? OR u.email LIKE ? OR u.contact_number LIKE ?)";
    $search_param = "%$search%";
    $params[] = $search_param;
    $params[] = $search_param;
    $params[] = $search_param;
    $params[] = $search_param;
    $types .= "ssss";
}

$sql .= " ORDER BY u.created_at DESC";

$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$users = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users Management - Admin Panel</title>
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
            <h2 style="margin-bottom: 20px;">👥 Registered Users</h2>
            
            <!-- Search Form -->
            <form method="GET" style="background: #f7fafc; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
                <div style="display: grid; grid-template-columns: 1fr 150px; gap: 15px; align-items: end;">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label>Search Users</label>
                        <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Search by name, email, or contact number">
                    </div>
                    
                    <div>
                        <button type="submit" class="btn btn-primary" style="width: 100%;">🔍 Search</button>
                    </div>
                </div>
            </form>
            
            <?php if ($users->num_rows > 0): ?>
                <div style="overflow-x: auto;">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Contact</th>
                                <th>Address</th>
                                <th>Total Requests</th>
                                <th>Pending</th>
                                <th>Registered</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $users->fetch_assoc()): ?>
                            <tr>
                                <td><strong>#<?php echo $row['id']; ?></strong></td>
                                <td><?php echo htmlspecialchars($row['firstname'] . ' ' . $row['lastname']); ?></td>
                                <td><?php echo htmlspecialchars($row['email']); ?></td>
                                <td><?php echo htmlspecialchars($row['contact_number']); ?></td>
                                <td><?php echo htmlspecialchars(substr($row['address'], 0, 30)) . (strlen($row['address']) > 30 ? '...' : ''); ?></td>
                                <td style="text-align: center;">
                                    <?php if ($row['total_requests'] > 0): ?>
                                        <span style="background: #e6f2ff; color: #0066cc; padding: 4px 8px; border-radius: 4px; font-weight: 600;">
                                            <?php echo $row['total_requests']; ?>
                                        </span>
                                    <?php else: ?>
                                        <span style="color: #718096;">0</span>
                                    <?php endif; ?>
                                </td>
                                <td style="text-align: center;">
                                    <?php if ($row['pending_requests'] > 0): ?>
                                        <span style="background: #fed7d7; color: #c53030; padding: 4px 8px; border-radius: 4px; font-weight: 600;">
                                            <?php echo $row['pending_requests']; ?>
                                        </span>
                                    <?php else: ?>
                                        <span style="color: #718096;">0</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo date('M d, Y', strtotime($row['created_at'])); ?></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="no-data">
                    <span style="font-size: 48px;">👥</span>
                    <p>No users found matching your criteria.</p>
                    <?php if ($search): ?>
                        <a href="users.php" class="btn btn-secondary" style="margin-top: 15px;">Clear Search</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
