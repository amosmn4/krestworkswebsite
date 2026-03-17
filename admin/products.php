<?php
session_start();

// Check if user is logged in as admin
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

// Database connection
$conn = new mysqli('localhost', 'root', '', 'krestworks');
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

// Handle delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM products WHERE id = $id");
    header('Location: products.php');
    exit;
}

// Fetch all products
$result = $conn->query("SELECT * FROM products ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products - Admin</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background-color: #4CAF50; color: white; }
        tr:hover { background-color: #f5f5f5; }
        .btn { padding: 6px 12px; margin: 2px; text-decoration: none; border-radius: 4px; }
        .btn-add { background-color: #4CAF50; color: white; }
        .btn-edit { background-color: #2196F3; color: white; }
        .btn-delete { background-color: #f44336; color: white; }
        .btn:hover { opacity: 0.8; }
    </style>
</head>
<body>
    <h1>Products Management</h1>
    <a href="add_product.php" class="btn btn-add">+ Add New Product</a>
    
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Category</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td><?php echo htmlspecialchars($row['category']); ?></td>
                <td>$<?php echo number_format($row['price'], 2); ?></td>
                <td><?php echo $row['stock']; ?></td>
                <td>
                    <a href="edit_product.php?id=<?php echo $row['id']; ?>" class="btn btn-edit">Edit</a>
                    <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-delete" onclick="return confirm('Delete this product?');">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>

<?php $conn->close(); ?>