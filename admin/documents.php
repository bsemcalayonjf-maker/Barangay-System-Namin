<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

include '../includes/config.php';

$success = '';
$error = '';

// Handle add/edit document type
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action']) && $_POST['action'] == 'add') {
        $doc_name = $_POST['document_name'];
        $requirements = $_POST['requirements'];
        $processing_days = $_POST['processing_days'];
        
        $stmt = $conn->prepare("INSERT INTO document_types (document_name, requirements, processing_days) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $doc_name, $requirements, $processing_days);
        
        if ($stmt->execute()) {
            $success = "Document type added successfully!";
        } else {
            $error = "Failed to add document type.";
        }
    }
}

// Handle delete
if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    
    // Check if document type has requests
    $check_stmt = $conn->prepare("SELECT COUNT(*) as count FROM document_requests WHERE document_type_id = ?");
    $check_stmt->bind_param("i", $delete_id);
    $check_stmt->execute();
    $count = $check_stmt->get_result()->fetch_assoc()['count'];
    
    if ($count > 0) {
        $error = "Cannot delete this document type. It has existing requests.";
    } else {
        $delete_stmt = $conn->prepare("DELETE FROM document_types WHERE id = ?");
        $delete_stmt->bind_param("i", $delete_id);
        if ($delete_stmt->execute()) {
            $success = "Document type deleted successfully!";
        } else {
            $error = "Failed to delete document type.";
        }
    }
}

// Get all document types
$doc_types = $conn->query("SELECT dt.*, 
                           (SELECT COUNT(*) FROM document_requests WHERE document_type_id = dt.id) as total_requests
                           FROM document_types dt
                           ORDER BY dt.document_name");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document Types Management - Admin Panel</title>
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
            <h2 style="margin-bottom: 20px;">📄 Document Types Management</h2>
            
            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-bottom: 30px;">
                <!-- Left: Add New Document Type -->
                <div style="background: #f7fafc; padding: 25px; border-radius: 8px;">
                    <h3 style="margin-bottom: 20px; color: #2d3748;">➕ Add New Document Type</h3>
                    
                    <form method="POST" action="">
                        <input type="hidden" name="action" value="add">
                        
                        <div class="form-group">
                            <label>Document Name *</label>
                            <input type="text" name="document_name" required placeholder="e.g., Barangay Clearance">
                        </div>
                        
                        <div class="form-group">
                            <label>Requirements *</label>
                            <textarea name="requirements" required placeholder="e.g., Valid ID, Barangay ID, Cedula"></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label>Processing Days *</label>
                            <input type="number" name="processing_days" required min="1" max="30" value="3">
                        </div>
                        
                        <button type="submit" class="btn btn-primary" style="width: 100%;">
                            ➕ Add Document Type
                        </button>
                    </form>
                </div>
                
                <!-- Right: Info -->
                <div>
                    <div style="background: #e6f2ff; padding: 20px; border-radius: 8px; border-left: 4px solid #0066cc; margin-bottom: 20px;">
                        <h4 style="margin-bottom: 10px; color: #0066cc;">📌 Tips:</h4>
                        <ul style="font-size: 14px; color: #0066cc; margin-left: 20px; line-height: 1.8;">
                            <li>Be specific with document names</li>
                            <li>List all required documents clearly</li>
                            <li>Set realistic processing times</li>
                            <li>Cannot delete types with existing requests</li>
                        </ul>
                    </div>
                    
                    <div style="background: #f7fafc; padding: 20px; border-radius: 8px;">
                        <h4 style="margin-bottom: 15px; color: #2d3748;">Common Document Types:</h4>
                        <ul style="font-size: 14px; color: #718096; margin-left: 20px; line-height: 2;">
                            <li>Barangay Clearance</li>
                            <li>Certificate of Indigency</li>
                            <li>Certificate of Residency</li>
                            <li>Barangay ID</li>
                            <li>Business Permit</li>
                            <li>Good Moral Certificate</li>
                            <li>First Time Job Seeker Certificate</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <h3 style="margin-bottom: 20px;">📋 Current Document Types</h3>
            
            <?php if ($doc_types->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Document Name</th>
                            <th>Requirements</th>
                            <th>Processing Days</th>
                            <th>Total Requests</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $doc_types->fetch_assoc()): ?>
                        <tr>
                            <td><strong>#<?php echo $row['id']; ?></strong></td>
                            <td><strong><?php echo htmlspecialchars($row['document_name']); ?></strong></td>
                            <td><?php echo htmlspecialchars($row['requirements']); ?></td>
                            <td style="text-align: center;">
                                <span style="background: #e6f2ff; color: #0066cc; padding: 4px 12px; border-radius: 4px; font-weight: 600;">
                                    <?php echo $row['processing_days']; ?> days
                                </span>
                            </td>
                            <td style="text-align: center;">
                                <?php if ($row['total_requests'] > 0): ?>
                                    <span style="background: #c6f6d5; color: #22543d; padding: 4px 12px; border-radius: 4px; font-weight: 600;">
                                        <?php echo $row['total_requests']; ?> requests
                                    </span>
                                <?php else: ?>
                                    <span style="color: #718096;">0 requests</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($row['total_requests'] == 0): ?>
                                    <a href="?delete=<?php echo $row['id']; ?>" 
                                       class="btn btn-danger" 
                                       style="padding: 6px 12px; font-size: 12px;"
                                       onclick="return confirm('Are you sure you want to delete this document type?')">
                                        🗑️ Delete
                                    </a>
                                <?php else: ?>
                                    <span style="color: #718096; font-size: 12px;">Has requests</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="no-data">
                    <span style="font-size: 48px;">📄</span>
                    <p>No document types available yet.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
