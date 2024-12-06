<?php
session_start();
require_once 'config/database.php';
require_once 'Admin/includes/functions.php';

// Handle report generation
$report_type = $_GET['type'] ?? 'all';
$date_from = $_GET['date_from'] ?? date('Y-m-01'); // First day of current month
$date_to = $_GET['date_to'] ?? date('Y-m-d'); // Current date

// Get report data
$sql = "SELECT p.*, c.name as category_name 
        FROM products p 
        LEFT JOIN categories c ON p.category = c.name 
        WHERE 1=1 ";

switch($report_type) {
    case 'low_stock':
        $sql .= "AND p.quantity < 10 ";
        $report_title = "Low Stock Report";
        break;
    case 'out_of_stock':
        $sql .= "AND p.quantity = 0 ";
        $report_title = "Out of Stock Report";
        break;
    case 'category':
        $sql .= "ORDER BY p.category, p.name";
        $report_title = "Category-wise Inventory Report";
        break;
    default:
        $sql .= "ORDER BY p.name";
        $report_title = "Complete Inventory Report";
}

$stmt = $conn->prepare($sql);
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Calculate totals
$total_value = array_sum(array_map(function($product) {
    return $product['quantity'] * $product['price'];
}, $products));

// Handle PDF export if requested
if (isset($_GET['export']) && $_GET['export'] === 'pdf') {
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="inventory_report.pdf"');
    // PDF generation code would go here
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports - Inventory Management</title>
    <link rel="stylesheet" href="css/style.css">

</head>
<body>
    <?php include 'Admin/includes/navbar.php'; ?>

    <div class="container mt-4">
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title"><?php echo $report_title; ?></h2>
                    </div>
                    <div class="card-body">
                        <!-- Report Filters -->
                        <form method="GET" class="row g-3 mb-4">
                            <div class="col-md-3">
                                <label class="form-label">Report Type</label>
                                <select name="type" class="form-select" onchange="this.form.submit()">
                                    <option value="all" <?php echo $report_type === 'all' ? 'selected' : ''; ?>>Complete Inventory</option>
                                    <option value="low_stock" <?php echo $report_type === 'low_stock' ? 'selected' : ''; ?>>Low Stock Items</option>
                                    <option value="out_of_stock" <?php echo $report_type === 'out_of_stock' ? 'selected' : ''; ?>>Out of Stock Items</option>
                                    <option value="category" <?php echo $report_type === 'category' ? 'selected' : ''; ?>>Category-wise</option>
                                </select>
                            </div>
                        </form>

                        <!-- Report Summary -->
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h5 class="card-title">Total Items</h5>
                                        <p class="card-text h2"><?php echo count($products); ?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h5 class="card-title">Total Value</h5>
                                        <p class="card-text h2">$<?php echo number_format($total_value, 2); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Report Data -->
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead class="table-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Category</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                        <th>Total Value</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($products as $product): ?>
                                        <tr>
                                            <td><?php echo $product['id']; ?></td>
                                            <td><?php echo htmlspecialchars($product['name']); ?></td>
                                            <td><?php echo htmlspecialchars($product['category']); ?></td>
                                            <td><?php echo $product['quantity']; ?></td>
                                            <td>$<?php echo number_format($product['price'], 2); ?></td>
                                            <td>$<?php echo number_format($product['quantity'] * $product['price'], 2); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot class="table-dark">
                                    <tr>
                                        <td colspan="5" class="text-end"><strong>Total Inventory Value:</strong></td>
                                        <td><strong>$<?php echo number_format($total_value, 2); ?></strong></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>