<?php
/**
 * ========================================
 * INDEX PAGE (Landing Page)
 * ========================================
 */
$page_title = "Home - Barangay Document Request System";
$css_path = "";
include 'includes/header.php';
?>

<div class="container">
    <!-- Header Section -->
    <div class="header">
        <h1>🏛️ Barangay Document Request System</h1>
        <p>Quezon City - Online Document Request Platform</p>
    </div>
    
    <!-- Content Section -->
    <div class="content" style="text-align: center; padding: 60px 30px;">
        <h2 style="color: #2d3748; margin-bottom: 20px;">Welcome to Our Online Service</h2>
        <p style="color: #718096; font-size: 18px; margin-bottom: 40px;">
            Request barangay documents online. No need to fall in line!
        </p>
        
        <!-- Features Grid -->
        <div style="display: flex; gap: 20px; justify-content: center; flex-wrap: wrap; margin-bottom: 50px;">
            <div style="flex: 1; min-width: 250px; max-width: 300px; background: #f7fafc; padding: 30px; border-radius: 8px;">
                <div style="font-size: 48px; margin-bottom: 15px;">📝</div>
                <h3 style="color: #2d3748; margin-bottom: 10px;">Submit Request</h3>
                <p style="color: #718096; font-size: 14px;">Request documents online anytime, anywhere</p>
            </div>
            
            <div style="flex: 1; min-width: 250px; max-width: 300px; background: #f7fafc; padding: 30px; border-radius: 8px;">
                <div style="font-size: 48px; margin-bottom: 15px;">📊</div>
                <h3 style="color: #2d3748; margin-bottom: 10px;">Track Status</h3>
                <p style="color: #718096; font-size: 14px;">Monitor your request status in real-time</p>
            </div>
            
            <div style="flex: 1; min-width: 250px; max-width: 300px; background: #f7fafc; padding: 30px; border-radius: 8px;">
                <div style="font-size: 48px; margin-bottom: 15px;">✅</div>
                <h3 style="color: #2d3748; margin-bottom: 10px;">Get Notified</h3>
                <p style="color: #718096; font-size: 14px;">Receive updates when documents are ready</p>
            </div>
        </div>
        
        <!-- Available Documents -->
        <h3 style="color: #2d3748; margin-bottom: 30px;">Available Documents</h3>
        <div style="background: #f7fafc; padding: 30px; border-radius: 8px; margin-bottom: 40px; text-align: left; max-width: 600px; margin-left: auto; margin-right: auto;">
            <ul style="list-style: none; padding: 0;">
                <li style="padding: 10px 0; border-bottom: 1px solid #e2e8f0;">✓ Barangay Clearance</li>
                <li style="padding: 10px 0; border-bottom: 1px solid #e2e8f0;">✓ Certificate of Indigency</li>
                <li style="padding: 10px 0; border-bottom: 1px solid #e2e8f0;">✓ Barangay ID</li>
                <li style="padding: 10px 0; border-bottom: 1px solid #e2e8f0;">✓ Business Permit</li>
                <li style="padding: 10px 0;">✓ Certificate of Residency</li>
            </ul>
        </div>
        
        <!-- Action Buttons -->
        <div style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
            <a href="login.php" class="btn btn-primary" style="font-size: 16px; padding: 15px 40px;">
                🔐 Login
            </a>
            <a href="register.php" class="btn btn-secondary" style="font-size: 16px; padding: 15px 40px;">
                📝 Register
            </a>
        </div>
        
        <p style="margin-top: 30px; color: #718096; font-size: 14px;">
            For admin access, please use the admin login.
        </p>
    </div>
    
    <!-- Footer -->
    <div style="background: #2d3748; color: white; padding: 20px; text-align: center;">
        <p style="font-size: 14px;">© 2024 Barangay Document Request System | Quezon City</p>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
