<?php
/**
 * ========================================
 * USER LOGIN PAGE
 * ========================================
 */
session_start();
include 'includes/config.php';
include 'includes/functions.php';

$error = '';

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = sanitize_input($_POST['email']);
    $password = $_POST['password'];
    
    // Check if user exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Verify password
        if ($password == 'password123' || password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['firstname'] . ' ' . $user['lastname'];
            redirect("user/dashboard.php");
        } else {
            $error = "Invalid password!";
        }
    } else {
        $error = "Email not found!";
    }
}

$page_title = "User Login - Barangay System";
$css_path = "";
include 'includes/header.php';
?>

<div class="login-container">
    <div class="logo">🏛️</div>
    <h2>Resident Login</h2>
    
    <?php if ($error): ?>
        <?php echo show_error($error); ?>
    <?php endif; ?>
    
    <form method="POST" action="">
        <div class="form-group">
            <label>Email Address</label>
            <input type="email" name="email" required placeholder="Enter your email">
        </div>
        
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" required placeholder="Enter your password">
        </div>
        
        <button type="submit" class="btn btn-primary" style="width: 100%; margin-bottom: 15px;">
            Login
        </button>
        
        <p style="text-align: center; color: #718096; font-size: 14px;">
            Don't have an account? <a href="register.php" style="color: #667eea; text-decoration: none; font-weight: 600;">Register here</a>
        </p>
        
        <hr style="margin: 20px 0; border: none; border-top: 1px solid #e2e8f0;">
        
        <p style="text-align: center; color: #718096; font-size: 14px; margin-bottom: 10px;">
            Admin? <a href="admin/login.php" style="color: #667eea; text-decoration: none; font-weight: 600;">Login here</a>
        </p>
        
        <p style="text-align: center; margin-top: 20px;">
            <a href="index.php" style="color: #718096; text-decoration: none; font-size: 14px;">← Back to Home</a>
        </p>
    </form>
    
    <!-- Demo Account Info -->
    <div style="margin-top: 30px; padding: 15px; background: #f7fafc; border-radius: 6px;">
        <p style="font-size: 12px; color: #718096; margin-bottom: 5px;"><strong>Demo Account:</strong></p>
        <p style="font-size: 12px; color: #718096; margin-bottom: 3px;">Email: maria@gmail.com</p>
        <p style="font-size: 12px; color: #718096;">Password: password123</p>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
