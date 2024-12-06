<?php
function sanitize_input($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

function get_all_products() {
    global $conn;
    $stmt = $conn->query("SELECT * FROM products ORDER BY created_at DESC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function get_all_categories() {
    global $conn;
    $stmt = $conn->query("SELECT * FROM categories ORDER BY name");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function get_low_stock_products($threshold = 10) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM products WHERE quantity < ? ORDER BY quantity ASC");
    $stmt->execute([$threshold]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function get_dashboard_stats() {
    global $conn;
    
    $stats = [];
    
    // Total products
    $stmt = $conn->query("SELECT COUNT(*) as count FROM products");
    $stats['total_products'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    // Low stock items
    $stmt = $conn->query("SELECT COUNT(*) as count FROM products WHERE quantity < 10");
    $stats['low_stock'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    // Total categories
    $stmt = $conn->query("SELECT COUNT(*) as count FROM categories");
    $stats['total_categories'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    return $stats;
}

function format_currency($amount) {
    return '$' . number_format($amount, 2);
}