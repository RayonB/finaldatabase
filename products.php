<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header('Location: index.php');
    exit;
}

$adminUsername = $_SESSION['admin'];
require 'config.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product'])) {
    $requestName = $_POST['request_name'];
    $price = $_POST['price'];

    $stmt = $pdo->prepare("INSERT INTO request_types (request_name, price) VALUES (?, ?)");
    $stmt->execute([$requestName, $price]);

    $message = "Product added successfully!";
    echo "<script>
        setTimeout(function() {
            document.querySelector('.alert').style.display = 'none';
        }, 2000);
    </script>";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_product'])) {
    $productId = $_POST['product_id'];

    $stmt = $pdo->prepare("DELETE FROM request_types WHERE id = ?");
    $stmt->execute([$productId]);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['modify_product'])) {
    $productId = $_POST['product_id'];
    $requestName = $_POST['request_name'];
    $price = $_POST['price'];

    $stmt = $pdo->prepare("UPDATE request_types SET request_name = ?, price = ? WHERE id = ?");
    $stmt->execute([$requestName, $price, $productId]);
}

$stmt = $pdo->prepare("SELECT * FROM request_types ORDER BY id ASC");
$stmt->execute();
$products = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Products | NBSC</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f0f4f8;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .navbar {
            background: linear-gradient(135deg, #1f4e79, #ff6f20); 
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .navbar-brand, .nav-link {
            color: #fff !important;
            font-weight: 600;
            font-size: 18px;
            letter-spacing: 1px;
        }
        .navbar-brand:hover, .nav-link:hover {
            text-decoration: underline;
        }

        .navbar-nav .nav-item .nav-link i {
            margin-right: 5px;
        }

        .container {
            margin-top: 40px;
        }

        .card {
            border: none;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .card h5 {
            font-size: 20px;
            font-weight: 600;
        }
        .card .btn {
            background-color: #ff6f20;
            color: white;
            font-weight: bold;
            border: none;
        }
        .card .btn:hover {
            background-color: #ff8a42;
        }

        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #1f4e79;
            color: white;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark">
    <a class="navbar-brand" href="#"><i class="fas fa-box"></i> Add Products</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link" href="admindashboard.php"><i class="fas fa-home"></i> Home</a>
            </li>
        </ul>
        <span class="navbar-text text-white mr-3">Welcome, <?php echo htmlspecialchars($adminUsername); ?></span>
        <a href="logout.php" class="btn btn-danger"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>
</nav>

<div class="container">
    <h1 class="text-center my-4">Add New Product</h1>
    <?php if (isset($message)) : ?>
        <div class="alert alert-success text-center"> <?php echo $message; ?> </div>
    <?php endif; ?>
    <form method="POST" action="">
        <div class="form-group">
            <label for="request_name">Product Name</label>
            <input type="text" id="request_name" name="request_name" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="price">Price</label>
            <input type="number" id="price" name="price" class="form-control" step="0.01" required>
        </div>
        <button type="submit" name="add_product" class="btn btn-primary btn-block">Add Product</button>
    </form>

    <h2 class="text-center my-4">Existing Products</h2>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Product Name</th>
                <th>Price</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $product) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($product['id']); ?></td>
                    <td><?php echo htmlspecialchars($product['request_name']); ?></td>
                    <td><?php echo htmlspecialchars(number_format($product['price'], 2)); ?></td>
                    <td>
                        <form method="POST" action="" style="display:inline;">
                            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                            <button type="submit" name="delete_product" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                        <form method="POST" action="" style="display:inline;">
                            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                            <input type="text" name="request_name" value="<?php echo htmlspecialchars($product['request_name']); ?>" required>
                            <input type="number" name="price" value="<?php echo htmlspecialchars($product['price']); ?>" step="0.01" required>
                            <button type="submit" name="modify_product" class="btn btn-warning btn-sm">Modify</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

</body>
</html>
