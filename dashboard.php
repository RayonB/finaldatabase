<?php
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}
require_once "config.php";

$user_id = $_SESSION["id"];
$sql = "SELECT * FROM users WHERE id = :user_id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch();

if ($user === false) {
    header("location: login.php");
    exit;
}
$sql_requests = "SELECT ur.id, ur.request_id, ur.status, ur.date_requested, ur.total_price, ur.description, rt.request_name 
                 FROM user_requests ur 
                 JOIN request_types rt ON ur.request_id = rt.id
                 WHERE ur.user_id = :user_id";
$stmt_requests = $pdo->prepare($sql_requests);
$stmt_requests->bindParam(":user_id", $user_id, PDO::PARAM_INT);
$stmt_requests->execute();
$requests = $stmt_requests->fetchAll();
$sql_feedbacks = "SELECT f.feedback, f.rating, f.created_at, fr.reply
                  FROM feedbacks f
                  LEFT JOIN feedback_replies fr ON f.id = fr.feedback_id
                  WHERE f.user_id = :user_id";
$stmt_feedbacks = $pdo->prepare($sql_feedbacks);
$stmt_feedbacks->bindParam(":user_id", $user_id, PDO::PARAM_INT);
$stmt_feedbacks->execute();
$feedbacks = $stmt_feedbacks->fetchAll();
if (isset($_POST['logout'])) {
    session_destroy();
    header("location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
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
    .navbar .btn-email,
    .navbar .btn-logout {
        background-color: #007bff; 
        color: white;
        padding: 8px 16px; 
        border-radius: 30px;
        font-size: 1rem; 
        border: none;
        display: inline-flex;
        align-items: center;
        transition: all 0.3s ease;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); 
    }

    .navbar .btn-email:hover,
    .navbar .btn-logout:hover {
        background-color: #0056b3;
        transform: scale(1.05); 
    }

    .navbar .btn-email {
        background-color: #28a745; 
    }

    .navbar .btn-email:hover {
        background-color: #218838;
    }

    .navbar .btn-logout {
        background-color: #dc3545;
    }

    .navbar .btn-logout:hover {
        background-color: #c82333;
    }

    .navbar .btn-email i,
    .navbar .btn-logout i {
        margin-right: 8px;
    }/
    .ml-auto {
        margin-left: auto;
    }
    @media (max-width: 767px) {
        .navbar .navbar-brand {
            font-size: 20px; 
        }

        .navbar .btn-email,
        .navbar .btn-logout {
            font-size: 0.9rem;
            padding: 6px 12px; 
        }
    }
</style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="dashboard.php">Dashboard</a>
            <div class="ml-auto">
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
                    <a class="btn btn-email" href="email.php">
                        <i class="fas fa-paper-plane"></i> Email
                    </a>
                    <a class="btn btn-email" href="create_request.php">
                        <i class="fas fa-plus-circle"></i> Create Request
                    </a>
                    <a class="btn btn-email" href="sendfeedbacks.php">
                        <i class="fas fa-comments"></i> Share Feedbacks
                    </a>
                    <button type="submit" name="logout" class="btn btn-danger btn-logout">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    Welcome to Your Dashboard!
                </div>
                <div class="card-body">
                    <h5 class="card-title">Hello, <?php echo htmlspecialchars($user['full_name']); ?>!</h5>
                    <p class="card-text">This is your dashboard where you can manage your account and requests.</p>
                    <ul class="list-group">
                        <li class="list-group-item"><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></li>
                        <li class="list-group-item"><strong>Course:</strong> <?php echo htmlspecialchars($user['course']); ?></li>
                        <li class="list-group-item"><strong>Cellphone Number:</strong> <?php echo htmlspecialchars($user['cellphone_number']); ?></li>
                        <li class="list-group-item"><strong>Gender:</strong> <?php echo htmlspecialchars($user['gender']); ?></li>
                        <li class="list-group-item"><strong>Status:</strong> <?php echo htmlspecialchars($user['status']); ?></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    Your Requests
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Request Name</th>
                                <th>Status</th>
                                <th>Date Requested</th>
                                <th>Total Price</th>
                                <th>Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($requests) > 0): ?>
                                <?php foreach ($requests as $request): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($request['request_name']); ?></td>
                                        <td><?php echo ucfirst(htmlspecialchars($request['status'])); ?></td>
                                        <td><?php echo htmlspecialchars($request['date_requested']); ?></td>
                                        <td>$<?php echo number_format($request['total_price'], 2); ?></td>
                                        <td><?php echo htmlspecialchars($request['description']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center">No requests found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    Your Feedbacks
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Feedback</th>
                                <th>Rating</th>
                                <th>Submitted At</th>
                                <th>Reply</th> 
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($feedbacks) > 0): ?>
                                <?php foreach ($feedbacks as $feedback): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($feedback['feedback']); ?></td>
                                        <td><?php echo htmlspecialchars($feedback['rating']); ?></td>
                                        <td><?php echo htmlspecialchars($feedback['created_at']); ?></td>
                                        <td>
                                            <?php 
                                                if ($feedback['reply']) {
                                                    echo htmlspecialchars($feedback['reply']); 
                                                } else {
                                                    echo "No reply yet"; 
                                                }
                                            ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center">No feedbacks found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
