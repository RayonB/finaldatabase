<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NBSC | Processing Request System</title>
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
            font-size: 20px;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        .navbar-brand:hover, .nav-link:hover {
            color: #fff !important;
            text-decoration: underline;
        }
        .navbar-toggler-icon {
            background-color: #fff;
        }
        .navbar-nav {
            font-size: 18px;
        }
        .nav-item {
            margin-left: 20px;
        }

        .hero-section {
            text-align: center;
            background: linear-gradient(135deg, #1f4e79, #ff6f20);
            color: #fff;
            padding: 120px 20px;
            border-bottom: 10px solid #ff6f20;
        }
        .hero-title {
            font-size: 70px;
            font-weight: 700;
            letter-spacing: 4px;
            text-transform: uppercase;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.5);
        }
        .hero-subtitle {
            font-size: 26px;
            margin-top: 20px;
            font-weight: 300;
            text-shadow: 2px 2px #ff6f20;
        }
        .hero-text {
            font-size: 22px;
            margin-top: 30px;
            font-weight: 400;
        }

        .btn {
            background-color: #ff6f20; 
            color: #fff;
            border: none;
            padding: 14px 28px;
            font-size: 18px;
            font-weight: 600;
            border-radius: 5px;
            transition: background-color 0.3s ease, transform 0.3s ease;
            margin: 10px;
        }
        .btn:hover {
            background-color: #ff8a42;
            transform: translateY(-3px); 
        }

        footer {
            background-color: #1f4e79;
            color: #fff;
            text-align: center;
            padding: 15px;
        }

        @media (max-width: 768px) {
            .hero-title {
                font-size: 60px;
            }
            .hero-subtitle {
                font-size: 20px;
            }
            .hero-text {
                font-size: 18px;
            }
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark">
    <a class="navbar-brand" href="#">NBSC</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="login.php">Login</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="sign_up.php">Sign Up</a>
            </li>
        </ul>
    </div>
</nav>

<section class="hero-section">
    <h1 class="hero-title">Processing Request System</h1>
    <p class="hero-subtitle">Streamlining Your Requests</p>
    <p class="hero-text">At NBSC, we make the process of handling your requests fast, easy, and efficient. Your satisfaction is our priority, and we are here to help you at every step!</p>
</section>

<footer>
    <p>© 2024 Northern Bukidnon State College. All Rights Reserved.</p>
</footer>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
