<?php
/**
 * ========================================
 * DATABASE CONFIGURATION
 * ========================================
 * Connection settings for MySQL database
 */

// Database credentials
$servername = "localhost";      // Server hostname
$username = "root";             // MySQL username (default for XAMPP)
$password = "";                 // MySQL password (blank for XAMPP)
$dbname = "barangay_system";    // Database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("❌ Connection failed: " . $conn->connect_error);
}

// Set charset to UTF-8 for proper character encoding
$conn->set_charset("utf8mb4");

// Optional: Uncomment to see success message
// echo "✅ Database connected successfully!";
?>
