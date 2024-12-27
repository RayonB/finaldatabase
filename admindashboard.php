<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header('Location: index.php'); 
    exit;
}

$adminUsername = $_SESSION['admin'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | NBSC</title>
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

        .dashboard-header {
            text-align: center;
            margin: 20px 0;
        }
        .dashboard-header h1 {
            font-size: 40px;
            font-weight: bold;
            color: #1f4e79;
        }

        .container {
            margin-top: 40px;
        }

        .card {
            border: none;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
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
        .card-body {
            padding: 30px;
        }

        .row .col-md-4 {
            display: flex;
            justify-content: center;
        }

        @media (max-width: 768px) {
            .col-md-4 {
                flex: 1 1 100%; 
                margin-bottom: 20px;
            }
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark">
    <a class="navbar-brand" href="#">Admin Dashboard</a>
    <div class="ml-auto">
        <span class="navbar-text text-white mr-3">Welcome, <?php echo htmlspecialchars($adminUsername); ?></span>
        <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>
</nav>

<div class="container">
    <div class="dashboard-header">
        <h1>Admin Dashboard</h1>
        <p>Manage users, requests, feedback, and system settings.</p>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <h5>Manage Users</h5>
                    <p>You can view all the users who have registered in the system.</p>
                    <a href="manage_users.php" class="btn">Go to Users</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <h5>View Requests</h5>
                    <p>You can review and process user requests efficiently.</p>
                    <a href="ViewRequests.php" class="btn">View Requests</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <h5>Add Products</h5>
                    <p>You can add, modify, and delete products in the system.</p>
                    <a href="products.php" class="btn">Add Products</a>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <h5>View Feedbacks</h5>
                    <p>You can view and manage feedbacks provided by the users.</p>
                    <a href="view_feedbacks.php" class="btn">View Feedbacks</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
