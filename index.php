<?php
session_start();
require_once 'config/database.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Management System</title>
    
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include 'Admin/includes/navbar.php'; ?>
    
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-12">
                <h2>Dashboard</h2>
                <div class="row mt-4">
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Total Products</h5>
                                <?php
                                $stmt = $conn->query("SELECT COUNT(*) as count FROM products");
                                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                                ?>
                                <h2 class="card-text"><?php echo $result['count']; ?></h2>
                                <a href="products.php" class="btn btn-primary">View Products</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Low Stock Items</h5>
                                <?php
                                $stmt = $conn->query("SELECT COUNT(*) as count FROM products WHERE quantity < 10");
                                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                                ?>
                                <h2 class="card-text"><?php echo $result['count']; ?></h2>
                                <a href="low_stock.php" class="btn btn-warning">View Low Stock</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Categories</h5>
                                <?php
                                $stmt = $conn->query("SELECT COUNT(*) as count FROM categories");
                                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                                ?>
                                <h2 class="card-text"><?php echo $result['count']; ?></h2>
                                <a href="categories.php" class="btn btn-success">View Categories</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>