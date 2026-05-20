<?php
/**
 * ========================================
 * USER REGISTRATION PAGE
 * ========================================
 */
session_start();
include 'includes/config.php';
include 'includes/functions.php';

$success = '';
$error = '';

// Handle registration form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $firstname = sanitize_input($_POST['firstname']);
    $lastname = sanitize_input($_POST['lastname']);
    $email = sanitize_input($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $address = sanitize_input($_POST['address']);
    $contact_number = sanitize_input($_POST['contact_number']);
    
    // Validation
    if ($password !== $confirm_password) {
        $error = "Passwords do not match!";
    } elseif (!is_valid_email($email)) {
        $error = "Invalid email format!";
    } elseif (!is_valid_phone($contact_number)) {
        $error = "Invalid phone number! Use format: 09XXXXXXXXX";
    } else {
        // Check if email already exists
        $check_stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $check_stmt->bind_param("s", $email);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        
        if ($check_result->num_rows > 0) {
            $error = "Email already registered!";
        } else {
            // Hash password for security
            $hashed_password = hash_password($password);
            
            // Insert new user
            $stmt = $conn->prepare("INSERT INTO users (firstname, lastname, email, password, address, contact_number) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssss", $firstname, $lastname, $email, $hashed_password, $address, $contact_number);
            
            if ($stmt->execute()) {
                $success = "Registration successful! You can now login.";
            } else {
                $error = "Registration failed. Please try again.";
            }
        }
    }
}

$page_title = "Register - Barangay System";
$css_path = "";
include 'includes/header.php';
?>

<div class="login-container" style="max-width: 500px; margin: 50px auto;">
    <div class="logo">📝</div>
    <h2>Resident Registration</h2>
    
    <?php if ($success): ?>
        <div class="alert alert-success">
            <?php echo $success; ?>
            <br><a href="login.php" style="color: #22543d; font-weight: 600;">Click here to login</a>
        </div>
    <?php endif; ?>
    
    <?php if ($error): ?>
        <?php echo show_error($error); ?>
    <?php endif; ?>
    
    <form method="POST" action="">
        <!-- Name Fields -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
            <div class="form-group">
                <label>First Name *</label>
                <input type="text" name="firstname" required placeholder="Juan">
            </div>
            
            <div class="form-group">
                <label>Last Name *</label>
                <input type="text" name="lastname" required placeholder="Dela Cruz">
            </div>
        </div>
        
        <!-- Email -->
        <div class="form-group">
            <label>Email Address *</label>
            <input type="email" name="email" required placeholder="juan@gmail.com">
        </div>
        
        <!-- Address -->
        <div class="form-group">
            <label>Complete Address *</label>
            <input type="text" name="address" required placeholder="Blk 5 Lot 10 Phase 3, Quezon City">
        </div>
        
        <!-- Contact Number -->
        <div class="form-group">
            <label>Contact Number *</label>
            <input type="text" name="contact_number" required placeholder="09123456789" pattern="[0-9]{11}" maxlength="11">
            <small style="color: #718096; font-size: 12px;">Format: 09XXXXXXXXX (11 digits)</small>
        </div>
        
        <!-- Password Fields -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
            <div class="form-group">
                <label>Password *</label>
                <input type="password" name="password" required placeholder="••••••••" minlength="6">
            </div>
            
            <div class="form-group">
                <label>Confirm Password *</label>
                <input type="password" name="confirm_password" required placeholder="••••••••" minlength="6">
            </div>
        </div>
        
        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary" style="width: 100%; margin-bottom: 15px;">
            Register
        </button>
        
        <!-- Links -->
        <p style="text-align: center; color: #718096; font-size: 14px;">
            Already have an account? <a href="login.php" style="color: #667eea; text-decoration: none; font-weight: 600;">Login here</a>
        </p>
        
        <p style="text-align: center; margin-top: 20px;">
            <a href="index.php" style="color: #718096; text-decoration: none; font-size: 14px;">← Back to Home</a>
        </p>
    </form>
</div>

<?php include 'includes/footer.php'; ?>
