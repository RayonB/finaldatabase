<?php
$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "mydb"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['reply'])) {
    $feedback_id = $_POST['feedback_id'];
    $reply = $_POST['reply'];
    
    $admin_id = 1; 
    $sql = "INSERT INTO feedback_replies (feedback_id, reply, admin_id) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isi", $feedback_id, $reply, $admin_id);
    $stmt->execute();
    $stmt->close();
}

$sql = "SELECT f.id, f.feedback, f.rating, f.created_at, u.full_name FROM feedbacks f 
        JOIN users u ON f.user_id = u.id 
        ORDER BY f.created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Feedbacks</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f9fafb;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .navbar {
            background: linear-gradient(135deg, #1f4e79, #ff6f20);
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border-bottom: 2px solid #ccc;
        }

        .navbar h1 {
            margin: 0;
            color: white;
            font-size: 26px;
            font-weight: 600;
            letter-spacing: 1.2px;
        }

        .navbar a {
            text-decoration: none;
            font-size: 16px;
            font-weight: 600;
            color: white;
            margin-left: 25px;
        }

        .navbar a:hover {
            text-decoration: underline;
            background-color: rgba(255, 255, 255, 0.1);
            padding: 5px;
            border-radius: 4px;
        }

        .dashboard-header {
            text-align: center;
            margin: 50px 0;
        }

        .dashboard-header h1 {
            font-size: 42px;
            font-weight: 700;
            color: #1f4e79;
        }

        .container {
            margin-top: 40px;
            padding: 0 20px;
        }

        .card {
            background-color: white;
            border: none;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
            padding: 25px;
            border-radius: 10px;
        }

        .card h5 {
            font-size: 22px;
            font-weight: 600;
            color: #333;
            margin-bottom: 20px;
        }

        .feedback-list {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .feedback-item {
            background-color: #fff;
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .feedback-item h3 {
            margin: 0;
            font-size: 20px;
            font-weight: 600;
        }

        .feedback-item p {
            margin: 12px 0;
            font-size: 16px;
            line-height: 1.6;
        }

        .reply-form {
            margin-top: 20px;
        }

        .reply-form textarea {
            width: 100%;
            height: 100px;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .reply-form button {
            background-color: #ff6f20;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .reply-form button:hover {
            background-color: #ff8a42;
        }

        @media (max-width: 768px) {
            .navbar h1 {
                font-size: 20px;
            }
            .feedback-list {
                margin: 10px;
            }
            .container {
                padding: 0 10px;
            }
            .card {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="navbar">
        <h1>View Feedbacks</h1>
        <div>
            <a href="admindashboard.php">Home</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>
    <div class="dashboard-header">
        <h1>Feedback Dashboard</h1>
    </div>

    <div class="container">
        <div class="card">
            <h5>Recent Feedbacks</h5>
            <div class="feedback-list">
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<div class='feedback-item'>";
                        echo "<h3>Feedback from " . htmlspecialchars($row['full_name']) . " (Rating: " . $row['rating'] . ")</h3>";
                        echo "<p>" . htmlspecialchars($row['feedback']) . "</p>";
                        echo "<p><strong>Submitted on:</strong> " . date("F j, Y, g:i a", strtotime($row['created_at'])) . "</p>";
                        
                        echo "<form method='POST' class='reply-form'>";
                        echo "<input type='hidden' name='feedback_id' value='" . $row['id'] . "'>";
                        echo "<textarea name='reply' placeholder='Type your reply here'></textarea><br>";
                        echo "<button type='submit'>Reply</button>";
                        echo "</form>";

                        $feedback_id = $row['id'];
                        $reply_sql = "SELECT r.reply, r.created_at, u.full_name FROM feedback_replies r 
                                      JOIN users u ON r.admin_id = u.id
                                      WHERE r.feedback_id = $feedback_id";
                        $replies_result = $conn->query($reply_sql);
                        
                        if ($replies_result->num_rows > 0) {
                            while ($reply = $replies_result->fetch_assoc()) {
                                echo "<div class='feedback-item'>";
                                echo "<strong>Admin Reply:</strong><br>";
                                echo "<p>" . htmlspecialchars($reply['reply']) . "</p>";
                                echo "<p><strong>Replied on:</strong> " . date("F j, Y, g:i a", strtotime($reply['created_at'])) . "</p>";
                                echo "</div>";
                            }
                        }

                        echo "</div>";
                    }
                } else {
                    echo "<p>No feedbacks available.</p>";
                }
                $conn->close();
                ?>
            </div>
        </div>
    </div>

</body>
</html>
