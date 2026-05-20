<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

include '../includes/config.php';

$request_id = isset($_GET['id']) ? $_GET['id'] : 0;
$success = '';
$error = '';

// Get request details
$stmt = $conn->prepare("SELECT dr.*, dt.document_name, dt.requirements, dt.processing_days,
                        CONCAT(u.firstname, ' ', u.lastname) as user_name,
                        u.email, u.address, u.contact_number
                        FROM document_requests dr
                        JOIN document_types dt ON dr.document_type_id = dt.id
                        JOIN users u ON dr.user_id = u.id
                        WHERE dr.id = ?");
$stmt->bind_param("i", $request_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    header("Location: requests.php");
    exit();
}

$request = $result->fetch_assoc();

// Handle status update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_status = $_POST['status'];
    $remarks = $_POST['remarks'];
    $admin_id = $_SESSION['admin_id'];
    
    $update_stmt = $conn->prepare("UPDATE document_requests 
                                   SET status = ?, remarks = ?, processed_by = ?, processed_date = NOW() 
                                   WHERE id = ?");
    $update_stmt->bind_param("ssii", $new_status, $remarks, $admin_id, $request_id);
    
    if ($update_stmt->execute()) {
        $success = "Request status updated successfully!";
        
        // Refresh request data
        $stmt->execute();
        $request = $stmt->get_result()->fetch_assoc();
    } else {
        $error = "Failed to update request status.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Request #<?php echo str_pad($request['id'], 4, '0', STR_PAD_LEFT); ?> - Admin Panel</title>
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
            <div style="margin-bottom: 20px;">
                <a href="requests.php" style="color: #667eea; text-decoration: none; font-size: 14px;">← Back to All Requests</a>
            </div>
            
            <h2 style="margin-bottom: 20px;">📋 Request Details #<?php echo str_pad($request['id'], 4, '0', STR_PAD_LEFT); ?></h2>
            
            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 30px;">
                <!-- Left Column: Request Info -->
                <div>
                    <div style="background: #f7fafc; padding: 25px; border-radius: 8px; margin-bottom: 20px;">
                        <h3 style="margin-bottom: 20px; color: #2d3748;">User Information</h3>
                        
                        <div style="display: grid; grid-template-columns: 150px 1fr; gap: 15px; margin-bottom: 15px;">
                            <div style="font-weight: 600; color: #718096;">Name:</div>
                            <div><?php echo htmlspecialchars($request['user_name']); ?></div>
                            
                            <div style="font-weight: 600; color: #718096;">Email:</div>
                            <div><?php echo htmlspecialchars($request['email']); ?></div>
                            
                            <div style="font-weight: 600; color: #718096;">Contact:</div>
                            <div><?php echo htmlspecialchars($request['contact_number']); ?></div>
                            
                            <div style="font-weight: 600; color: #718096;">Address:</div>
                            <div><?php echo htmlspecialchars($request['address']); ?></div>
                        </div>
                    </div>
                    
                    <div style="background: #f7fafc; padding: 25px; border-radius: 8px; margin-bottom: 20px;">
                        <h3 style="margin-bottom: 20px; color: #2d3748;">Document Information</h3>
                        
                        <div style="display: grid; grid-template-columns: 150px 1fr; gap: 15px; margin-bottom: 15px;">
                            <div style="font-weight: 600; color: #718096;">Document Type:</div>
                            <div><strong><?php echo htmlspecialchars($request['document_name']); ?></strong></div>
                            
                            <div style="font-weight: 600; color: #718096;">Purpose:</div>
                            <div><?php echo htmlspecialchars($request['purpose']); ?></div>
                            
                            <div style="font-weight: 600; color: #718096;">Requirements:</div>
                            <div><?php echo htmlspecialchars($request['requirements']); ?></div>
                            
                            <div style="font-weight: 600; color: #718096;">Processing Days:</div>
                            <div><?php echo $request['processing_days']; ?> days</div>
                            
                            <div style="font-weight: 600; color: #718096;">Request Date:</div>
                            <div><?php echo date('F d, Y h:i A', strtotime($request['request_date'])); ?></div>
                            
                            <?php if ($request['processed_date']): ?>
                            <div style="font-weight: 600; color: #718096;">Processed Date:</div>
                            <div><?php echo date('F d, Y h:i A', strtotime($request['processed_date'])); ?></div>
                            <?php endif; ?>
                            
                            <div style="font-weight: 600; color: #718096;">Current Status:</div>
                            <div>
                                <span class="status-badge status-<?php echo $request['status']; ?>" style="font-size: 14px; padding: 8px 16px;">
                                    <?php echo ucfirst($request['status']); ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <?php if ($request['remarks']): ?>
                    <div style="background: #fff3cd; padding: 20px; border-radius: 8px; border-left: 4px solid #ffc107;">
                        <h4 style="margin-bottom: 10px; color: #856404;">Current Remarks:</h4>
                        <p style="color: #856404;"><?php echo htmlspecialchars($request['remarks']); ?></p>
                    </div>
                    <?php endif; ?>
                </div>
                
                <!-- Right Column: Update Status -->
                <div>
                    <div style="background: #f7fafc; padding: 25px; border-radius: 8px; position: sticky; top: 20px;">
                        <h3 style="margin-bottom: 20px; color: #2d3748;">Update Status</h3>
                        
                        <form method="POST" action="">
                            <div class="form-group">
                                <label>Change Status</label>
                                <select name="status" required>
                                    <option value="pending" <?php echo $request['status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                                    <option value="processing" <?php echo $request['status'] == 'processing' ? 'selected' : ''; ?>>Processing</option>
                                    <option value="approved" <?php echo $request['status'] == 'approved' ? 'selected' : ''; ?>>Approved</option>
                                    <option value="rejected" <?php echo $request['status'] == 'rejected' ? 'selected' : ''; ?>>Rejected</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label>Remarks (Optional)</label>
                                <textarea name="remarks" placeholder="Add any notes or instructions for the user"><?php echo htmlspecialchars($request['remarks']); ?></textarea>
                            </div>
                            
                            <button type="submit" class="btn btn-primary" style="width: 100%;">
                                💾 Update Request
                            </button>
                        </form>
                        
                        <hr style="margin: 20px 0; border: none; border-top: 1px solid #e2e8f0;">
                        
                        <div style="background: white; padding: 15px; border-radius: 6px;">
                            <p style="font-size: 12px; color: #718096; margin-bottom: 10px;"><strong>Status Guide:</strong></p>
                            <ul style="font-size: 12px; color: #718096; margin-left: 20px; line-height: 1.8;">
                                <li><strong>Pending:</strong> Just received</li>
                                <li><strong>Processing:</strong> Being reviewed</li>
                                <li><strong>Approved:</strong> Ready for pickup</li>
                                <li><strong>Rejected:</strong> Cannot process</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
