<?php

$servername = "localhost";
$username = "root";        
$password = "";           
$dbname = "mydb";  

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

session_start();
if (!isset($_SESSION['id'])) {
    header("Location: login.php");  
    exit();
}
$user_id = $_SESSION['id'];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($conn) {
        $feedback = mysqli_real_escape_string($conn, $_POST['feedback']);
        $rating = (int)$_POST['rating'];
        if (empty($feedback) || $rating == 0) {
            $message = "Please provide both feedback and a rating.";
        } else {
            $query = "INSERT INTO feedbacks (user_id, feedback, rating, created_at) VALUES ('$user_id', '$feedback', '$rating', NOW())";
            if (mysqli_query($conn, $query)) {
                $message = "Feedback submitted successfully!";
            } else {
                $message = "Error: " . mysqli_error($conn);
            }
        }
    } else {
        $message = "Database connection failed.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send Feedback</title>
    <style>
       
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            padding: 0;
        }
        .navbar {
            background: linear-gradient(135deg, #6e7fbe, #2d4b88);
            padding: 12px 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .navbar a {
            color: #fff;
            text-decoration: none;
            font-weight: bold;
            padding: 10px 20px;
            border-radius: 6px;
            transition: background-color 0.3s;
        }

        .navbar a:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }
        .container {
            max-width: 800px;
            margin: 40px auto;
            padding: 25px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        h1 {
            font-size: 32px;
            text-align: center;
            margin-bottom: 25px;
            color: #333;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        label {
            font-size: 18px;
            color: #444;
        }

        textarea {
            padding: 15px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 10px;
            resize: vertical;
            min-height: 150px;
            transition: border-color 0.3s;
        }

        textarea:focus {
            border-color: #6e7fbe;
        }

        .rating {
            display: flex;
            justify-content: left;
            gap: 10px;
            margin-top: 10px;
        }

        .rating input[type="radio"] {
            display: none;
        }

        .rating label {
            font-size: 32px;
            cursor: pointer;
            color: #ddd; 
            transition: color 0.3s;
        }

        .rating input[type="radio"]:checked ~ label {
            color: #ff9900; 
        }

        .rating label:hover,
        .rating label:active {
            color: #ff9900;
        }

        button {
            background: linear-gradient(45deg, #28a745, #218838);
            color: white;
            border: none;
            padding: 12px 25px;
            font-size: 20px;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.3s, transform 0.2s;
        }

        button:hover {
            background: linear-gradient(45deg, #218838, #28a745);
            transform: scale(1.05);
        }

        button:active {
            transform: scale(1);
        }

        .message {
            margin: 20px 0;
            padding: 15px;
            background-color: #e7f7e7;
            color: #28a745;
            border: 1px solid #28a745;
            border-radius: 6px;
            text-align: center;
            font-size: 18px;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
            border-color: #f5c6cb;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <a href="dashboard.php">Home</a>
        <a href="logout.php">Logout</a>
    </div>
    <div class="container">
        <h1>Share Your Feedback</h1>

        <?php if (isset($message)) { ?>
            <div class="message <?php echo isset($message) && strpos($message, 'Error') !== false ? 'error' : ''; ?>" id="feedbackMessage">
                <?php echo $message; ?>
            </div>
        <?php } ?>

        <form method="POST" action="sendfeedbacks.php">
            <label for="feedback">Your Thoughts:</label>
            <textarea name="feedback" id="feedback" required></textarea>

            <div class="rating">
                <input type="radio" id="star1" name="rating" value="5">
                <label for="star1">&#9733;</label>

                <input type="radio" id="star2" name="rating" value="4">
                <label for="star2">&#9733;</label>

                <input type="radio" id="star3" name="rating" value="3">
                <label for="star3">&#9733;</label>

                <input type="radio" id="star4" name="rating" value="2">
                <label for="star4">&#9733;</label>

                <input type="radio" id="star5" name="rating" value="1">
                <label for="star5">&#9733;</label>
            </div>

            <button type="submit">Submit Feedback</button>
        </form>
    </div>
    <script>
        window.onload = function() {
            const message = document.getElementById('feedbackMessage');
            if (message) {
                setTimeout(function() {
                    message.style.display = 'none';
                }, 2000); 
            }
        };
    </script>

</body>
</html>
