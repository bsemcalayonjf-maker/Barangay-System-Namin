<?php
/**
 * ========================================
 * HELPER FUNCTIONS
 * ========================================
 * Reusable functions for the application
 */

/**
 * Sanitize user input to prevent XSS attacks
 */
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

/**
 * Redirect to another page
 */
function redirect($url) {
    header("Location: $url");
    exit();
}

/**
 * Check if user is logged in
 */
function is_user_logged_in() {
    return isset($_SESSION['user_id']);
}

/**
 * Check if admin is logged in
 */
function is_admin_logged_in() {
    return isset($_SESSION['admin_id']);
}

/**
 * Format date for display
 */
function format_date($date, $format = 'M d, Y') {
    return date($format, strtotime($date));
}

/**
 * Format date with time
 */
function format_datetime($date, $format = 'M d, Y h:i A') {
    return date($format, strtotime($date));
}

/**
 * Generate request ID with leading zeros
 */
function format_request_id($id) {
    return '#' . str_pad($id, 4, '0', STR_PAD_LEFT);
}

/**
 * Get status badge HTML
 */
function get_status_badge($status) {
    $status_lower = strtolower($status);
    return "<span class='status-badge status-{$status_lower}'>" . ucfirst($status) . "</span>";
}

/**
 * Shorten text with ellipsis
 */
function truncate_text($text, $length = 50) {
    if (strlen($text) > $length) {
        return substr($text, 0, $length) . '...';
    }
    return $text;
}

/**
 * Display success message
 */
function show_success($message) {
    return "<div class='alert alert-success'>{$message}</div>";
}

/**
 * Display error message
 */
function show_error($message) {
    return "<div class='alert alert-error'>{$message}</div>";
}

/**
 * Display info message
 */
function show_info($message) {
    return "<div class='alert alert-info'>{$message}</div>";
}

/**
 * Validate email format
 */
function is_valid_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

/**
 * Validate phone number (Philippine format)
 */
function is_valid_phone($phone) {
    // Philippine mobile number: 09XXXXXXXXX (11 digits)
    return preg_match('/^09[0-9]{9}$/', $phone);
}

/**
 * Hash password securely
 */
function hash_password($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

/**
 * Verify password
 */
function verify_password($password, $hash) {
    return password_verify($password, $hash);
}
?>
