<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

require_once "config.php";
$user_id = $_SESSION["id"];
$total_price = 0;
$selected_requests = [];
$error_message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['requests']) && !empty($_POST['requests'])) {
        foreach ($_POST['requests'] as $request_id) {
            $sql = "SELECT price FROM request_types WHERE id = :request_id";
            if ($stmt = $pdo->prepare($sql)) {
                $stmt->bindParam(":request_id", $request_id, PDO::PARAM_INT);
                if ($stmt->execute()) {
                    $row = $stmt->fetch();
                    $total_price += $row['price'];
                    $selected_requests[] = $request_id;
                }
            }
        }

        $description = isset($_POST['description']) ? trim($_POST['description']) : null;
        $request_ids_string = implode(',', $selected_requests);
        $sql = "INSERT INTO user_requests (user_id, request_id, status, total_price, description) 
                VALUES (:user_id, :request_ids, 'pending', :total_price, :description)";
        
        if ($stmt = $pdo->prepare($sql)) {
            $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
            $stmt->bindParam(":request_ids", $request_ids_string, PDO::PARAM_STR);
            $stmt->bindParam(":total_price", $total_price, PDO::PARAM_STR);
            $stmt->bindParam(":description", $description, PDO::PARAM_STR);
            
            if (!$stmt->execute()) {
                $error_message = "Oops! Something went wrong. Please try again later.";
            } else {
                header("location: dashboard.php");
                exit;
            }
        }

    } else {
        $error_message = "Please select at least one request.";
    }
}
$sql = "SELECT * FROM request_types";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$request_types = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Request</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script>
        function updateTotalPrice() {
            var totalPrice = 0;
            var checkboxes = document.querySelectorAll('input[type="checkbox"]:checked');
            
            checkboxes.forEach(function(checkbox) {
                var price = parseFloat(checkbox.getAttribute('data-price'));
                totalPrice += price;
            });

            document.getElementById('totalPrice').innerText = 'Total Price: $' + totalPrice.toFixed(2);
            document.getElementById('totalPriceInput').value = totalPrice.toFixed(2);
        }
    </script>
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
    }
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
<head>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>

<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="dashboard.php">Dashboard</a>
        
        < <a class="btn btn-email" href="email.php">
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
        </div>
    </div>
</nav>

    
    <div class="container mt-5">
        <h2>Create Request</h2>
        <p>Please select the items you want to request.</p>

        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="row">
                <?php 
                $half_count = ceil(count($request_types) / 2);
                $i = 0;
                foreach ($request_types as $request): 
                    if ($i == $half_count) {
                        echo '</div><div class="row">';
                    }
                ?>
                    <div class="col-md-6 request-item">
                        <input type="checkbox" name="requests[]" value="<?php echo $request['id']; ?>" 
                               data-price="<?php echo $request['price']; ?>" class="mr-2" 
                               onclick="updateTotalPrice()"> 
                        <?php echo $request['request_name']; ?> - $<?php echo number_format($request['price'], 2); ?>
                    </div>
                <?php 
                    $i++;
                endforeach; 
                ?>
            </div>

            <div class="mt-3">
                <h4 id="totalPrice">Total Price: $0.00</h4>
                <input type="hidden" name="total_price" id="totalPriceInput" value="0.00">
            </div>

       
            <div class="mt-3">
                <h4>Please provide a description for your request (optional):</h4>
                <textarea name="description" class="form-control"></textarea>
            </div>

            <div class="mt-3">
                <button type="submit" class="btn btn-primary">Send Request</button>
            </div>
        </form>
    </div>
</body>
</html>
