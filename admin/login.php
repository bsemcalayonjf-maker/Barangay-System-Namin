<?php
/**
 * ========================================
 * ADMIN LOGIN PAGE
 * ========================================
 */
session_start();
include '../includes/config.php';
include '../includes/functions.php';

$error = '';

// Handle admin login
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = sanitize_input($_POST['username']);
    $password = $_POST['password'];
    
    // Check if admin exists
    $stmt = $conn->prepare("SELECT * FROM admins WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $admin = $result->fetch_assoc();
        
        // Verify password (demo: admin123, or hashed password)
        if ($password == 'admin123' || password_verify($password, $admin['password'])) {
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_name'] = $admin['fullname'];
            $_SESSION['admin_username'] = $admin['username'];
            redirect("dashboard.php");
        } else {
            $error = "Invalid password!";
        }
    } else {
        $error = "Admin username not found!";
    }
}

$page_title = "Admin Login - Barangay System";
$css_path = "../";
include '../includes/header.php';
?>

<div class="login-container">
    <div class="logo">🔐</div>
    <h2>Admin Login</h2>
    
    <?php if ($error): ?>
        <?php echo show_error($error); ?>
    <?php endif; ?>
    
    <form method="POST" action="">
        <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" required placeholder="Enter admin username">
        </div>
        
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" required placeholder="Enter admin password">
        </div>
        
        <button type="submit" class="btn btn-primary" style="width: 100%; margin-bottom: 15px;">
            Login as Admin
        </button>
        
        <p style="text-align: center; margin-top: 20px;">
            <a href="../index.php" style="color: #718096; text-decoration: none; font-size: 14px;">← Back to Home</a>
        </p>
    </form>
    
    <!-- Demo Admin Account -->
    <div style="margin-top: 30px; padding: 15px; background: #f7fafc; border-radius: 6px;">
        <p style="font-size: 12px; color: #718096; margin-bottom: 5px;"><strong>Demo Admin Account:</strong></p>
        <p style="font-size: 12px; color: #718096; margin-bottom: 3px;">Username: admin</p>
        <p style="font-size: 12px; color: #718096;">Password: admin123</p>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
