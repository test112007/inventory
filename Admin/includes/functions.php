<?php
// Security functions
function secure_page() {
    session_start();
    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit();
    }
}

// Validation functions
function validate_product($data) {
    $errors = [];
    
    if (empty($data['name'])) {
        $errors[] = "Product name is required";
    }
    
    if (!is_numeric($data['quantity']) || $data['quantity'] < 0) {
        $errors[] = "Quantity must be a positive number";
    }
    
    if (!is_numeric($data['price']) || $data['price'] < 0) {
        $errors[] = "Price must be a positive number";
    }
    
    return $errors;
}

function validate_category($data) {
    $errors = [];
    
    if (empty($data['name'])) {
        $errors[] = "Category name is required";
    }
    
    return $errors;
}

// Utility functions
function generate_report($type = 'all') {
    global $conn;
    
    switch($type) {
        case 'low_stock':
            $sql = "SELECT * FROM products WHERE quantity < 10";
            break;
        case 'out_of_stock':
            $sql = "SELECT * FROM products WHERE quantity = 0";
            break;
        default:
            $sql = "SELECT * FROM products";
    }
    
    $stmt = $conn->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function get_product_value() {
    global $conn;
    $stmt = $conn->query("SELECT SUM(quantity * price) as total_value FROM products");
    return $stmt->fetch(PDO::FETCH_ASSOC)['total_value'] ?? 0;
}

// Date formatting
function format_date($date) {
    return date('M d, Y', strtotime($date));
}

// Error handling
function display_errors($errors) {
    if (!empty($errors)) {
        echo '<div class="alert alert-danger"><ul class="mb-0">';
        foreach ($errors as $error) {
            echo '<li>' . htmlspecialchars($error) . '</li>';
        }
        echo '</ul></div>';
    }
}

// Success message
function display_success($message) {
    if (!empty($message)) {
        echo '<div class="alert alert-success">' . htmlspecialchars($message) . '</div>';
    }
}

// Activity logging
function log_activity($action, $details) {
    global $conn;
    $user_id = $_SESSION['user_id'] ?? 0;
    $stmt = $conn->prepare("INSERT INTO activity_log (user_id, action, details) VALUES (?, ?, ?)");
    $stmt->execute([$user_id, $action, $details]);
}