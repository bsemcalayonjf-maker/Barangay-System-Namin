<?php
/**
 * ========================================
 * SUBMIT NEW REQUEST PAGE
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
$success = '';
$error = '';

// Get all document types
$doc_types = $conn->query("SELECT * FROM document_types ORDER BY document_name");

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $document_type_id = $_POST['document_type_id'];
    $purpose = sanitize_input($_POST['purpose']);
    
    // Insert request
    $stmt = $conn->prepare("INSERT INTO document_requests (user_id, document_type_id, purpose) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $user_id, $document_type_id, $purpose);
    
    if ($stmt->execute()) {
        $request_id = $stmt->insert_id;
        $success = "Request submitted successfully! Request ID: " . format_request_id($request_id);
    } else {
        $error = "Failed to submit request. Please try again.";
    }
}

$page_title = "New Request - Barangay System";
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
        <h2 style="margin-bottom: 20px;">📝 Submit New Document Request</h2>
        
        <?php if ($success): ?>
            <?php echo show_success($success); ?>
            <a href="my-requests.php" style="color: #22543d; font-weight: 600;">View My Requests</a>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <?php echo show_error($error); ?>
        <?php endif; ?>
        
        <div style="max-width: 600px;">
            <form method="POST" action="">
                <div class="form-group">
                    <label>Document Type *</label>
                    <select name="document_type_id" required id="docType">
                        <option value="">Select document type</option>
                        <?php while ($doc = $doc_types->fetch_assoc()): ?>
                            <option value="<?php echo $doc['id']; ?>" 
                                    data-requirements="<?php echo htmlspecialchars($doc['requirements']); ?>"
                                    data-days="<?php echo $doc['processing_days']; ?>">
                                <?php echo htmlspecialchars($doc['document_name']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                
                <div id="docInfo" style="display: none; background: #f7fafc; padding: 15px; border-radius: 6px; margin-bottom: 20px; border-left: 4px solid #667eea;">
                    <p style="margin-bottom: 10px; font-weight: 600; color: #2d3748;">Document Information:</p>
                    <p style="margin-bottom: 5px; font-size: 14px; color: #718096;"><strong>Requirements:</strong> <span id="reqText"></span></p>
                    <p style="font-size: 14px; color: #718096;"><strong>Processing Time:</strong> <span id="daysText"></span> days</p>
                </div>
                
                <div class="form-group">
                    <label>Purpose of Request *</label>
                    <textarea name="purpose" required placeholder="Example: For employment requirement, For passport application, etc."></textarea>
                </div>
                
                <div style="background: #fff3cd; padding: 15px; border-radius: 6px; margin-bottom: 20px; border-left: 4px solid #ffc107;">
                    <p style="font-size: 14px; color: #856404; margin-bottom: 5px;"><strong>⚠️ Important Reminders:</strong></p>
                    <ul style="font-size: 13px; color: #856404; margin-left: 20px;">
                        <li>Make sure to provide accurate information</li>
                        <li>Bring required documents when claiming</li>
                        <li>Processing may take 2-7 days depending on document type</li>
                        <li>Check your request status regularly</li>
                    </ul>
                </div>
                
                <div style="display: flex; gap: 10px;">
                    <button type="submit" class="btn btn-primary">Submit Request</button>
                    <a href="dashboard.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Show document information when selected
const docType = document.getElementById('docType');
const docInfo = document.getElementById('docInfo');
const reqText = document.getElementById('reqText');
const daysText = document.getElementById('daysText');

docType.addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    
    if (this.value) {
        const requirements = selectedOption.dataset.requirements;
        const days = selectedOption.dataset.days;
        
        reqText.textContent = requirements;
        daysText.textContent = days;
        docInfo.style.display = 'block';
    } else {
        docInfo.style.display = 'none';
    }
});
</script>

<?php include '../includes/footer.php'; ?>
