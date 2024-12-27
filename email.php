<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

require_once "config.php";

$user_id = $_SESSION["id"];

$sql = "SELECT ur.id, ur.request_id, ur.status, ur.date_requested, ur.total_price, ur.description, rt.request_name 
        FROM user_requests ur 
        JOIN request_types rt ON ur.request_id = rt.id
        WHERE ur.user_id = :user_id AND ur.status = 'pending'
        ORDER BY ur.date_requested DESC";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
$stmt->execute();
$pending_requests = $stmt->fetchAll();
$sql_history = "SELECT ur.id, ur.request_id, ur.status, ur.date_requested, ur.total_price, ur.description, rt.request_name 
                FROM user_requests ur 
                JOIN request_types rt ON ur.request_id = rt.id
                WHERE ur.user_id = :user_id
                ORDER BY ur.date_requested DESC";
$stmt_history = $pdo->prepare($sql_history);
$stmt_history->bindParam(":user_id", $user_id, PDO::PARAM_INT);
$stmt_history->execute();
$request_history = $stmt_history->fetchAll();
if (isset($_POST['update_status'])) {
    $request_id = $_POST['request_id'];
    sleep(10); 
    $status = (rand(0, 1) == 0) ? 'accepted' : 'declined';
    $sql = "UPDATE user_requests SET status = :status WHERE id = :request_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":status", $status, PDO::PARAM_STR);
    $stmt->bindParam(":request_id", $request_id, PDO::PARAM_INT);
    
    if ($stmt->execute()) {
        header("Refresh:0");
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Management</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
    body {
        font-family: 'Arial', sans-serif;
        background-color: #f0f2f5;
        margin: 0;
        padding: 0;
    }
    .navbar {
        background-color: #343a40; 
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        padding: 0.75rem 1.5rem; 
        transition: background-color 0.3s ease;
    }

    .navbar .navbar-brand {
        font-size: 22px; 
        font-weight: bold;
        color: #ff6600;
        text-transform: uppercase;
        letter-spacing: 1.2px;
        transition: color 0.3s ease;
    }

    .navbar .navbar-brand:hover {
        color: #ff9900;
    }

    /* Button Styling (Email and Logout) */
    .navbar .btn-email,
    .navbar .btn-logout {
        background-color: #007bff; /* Blue button color */
        color: white;
        padding: 8px 16px; /* Smaller padding */
        border-radius: 30px;
        font-size: 1rem; /* Smaller font size */
        border: none;
        display: inline-flex;
        align-items: center;
        transition: all 0.3s ease;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); /* Subtle shadow effect */
    }

    .navbar .btn-email:hover,
    .navbar .btn-logout:hover {
        background-color: #0056b3; /* Darker blue on hover */
        transform: scale(1.05); /* Slight scale-up effect */
    }

    .navbar .btn-email {
        background-color: #28a745; /* Green button for email */
    }

    .navbar .btn-email:hover {
        background-color: #218838;
    }

    .navbar .btn-logout {
        background-color: #dc3545; /* Red button for logout */
    }

    .navbar .btn-logout:hover {
        background-color: #c82333;
    }

    /* Button Icons */
    .navbar .btn-email i,
    .navbar .btn-logout i {
        margin-right: 8px; /* Space between icon and text */
    }

    /* Align items to the right */
    .ml-auto {
        margin-left: auto;
    }

    /* Make sure the buttons don't stack on small screens */
    @media (max-width: 767px) {
        .navbar .navbar-brand {
            font-size: 20px; /* Even smaller brand text on mobile */
        }

        .navbar .btn-email,
        .navbar .btn-logout {
            font-size: 0.9rem;
            padding: 6px 12px; /* Even smaller buttons on mobile */
        }
    }
</style>


    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>

<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="dashboard.php">Dashboard</a>
        
        <div class="ml-auto">
        <a class="btn btn-email" href="email.php">
                        <i class="fas fa-paper-plane"></i> Email
                    </a>
                    <a class="btn btn-email" href="create_request.php">
                        <i class="fas fa-plus-circle"></i> Create Request
                    </a>
                    <a class="btn btn-email" href="sendfeedbacks.php">
                        <i class="fas fa-comments"></i> Share Feedbacks
                    </a>
                    <a href="logout.php" class="btn btn-danger btn-logout">
    <i class="fas fa-sign-out-alt"></i> Logout
</a>


        </div>
    </div>
</nav>


    <div class="container mt-5">
        <h2>Pending Requests</h2>

        <?php if (count($pending_requests) > 0): ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Request Name</th>
                        <th>Status</th>
                        <th>Date Requested</th>
                        <th>Total Price</th>
                        <th>Description</th>
                        
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pending_requests as $request): ?>
                        <tr>
                            <td><?php echo $request['id']; ?></td>
                            <td><?php echo htmlspecialchars($request['request_name']); ?></td>
                            <td><?php echo ucfirst($request['status']); ?></td>
                            <td><?php echo date("F j, Y, g:i a", strtotime($request['date_requested'])); ?></td>
                            <td>Php<?php echo number_format($request['total_price'], 2); ?></td>
                            <td><?php echo htmlspecialchars($request['description']); ?></td>
                          
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No pending requests found.</p>
        <?php endif; ?>

        <hr>

        <h3>Request History</h3>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Request Name</th>
                    <th>Status</th>
                    <th>Date Requested</th>
                    <th>Total Price</th>
                    <th>Description</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($request_history) > 0): ?>
                    <?php foreach ($request_history as $history): ?>
                        <tr>
                            <td><?php echo $history['id']; ?></td>
                            <td><?php echo htmlspecialchars($history['request_name']); ?></td>
                            <td><?php echo ucfirst($history['status']); ?></td>
                            <td><?php echo date("F j, Y, g:i a", strtotime($history['date_requested'])); ?></td>
                            <td>Php<?php echo number_format($history['total_price'], 2); ?></td>
                            <td><?php echo htmlspecialchars($history['description']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">No request history found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</body>
</html>
