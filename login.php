<?php
session_start();

if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {

    require_once "config.php";

    $sql = "SELECT full_name, course, cellphone_number, gender FROM users WHERE id = :id";
    if ($stmt = $pdo->prepare($sql)) {
        $stmt->bindParam(":id", $_SESSION["id"], PDO::PARAM_INT);
        
        if ($stmt->execute()) {
            $user = $stmt->fetch();
         
            if (empty($user['full_name']) || empty($user['course']) || empty($user['cellphone_number']) || empty($user['gender'])) {
                header("location: profile_update.php");
                exit;
            } else {
        
                header("location: dashboard.php");
                exit;
            }
        }
    }
}

require_once "config.php";

$email = $password = "";
$email_err = $password_err = $login_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter your email.";
    } else {
        $email = trim($_POST["email"]);
    }

    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter your password.";
    } else {
        $password = trim($_POST["password"]);
    }

    if (empty($email_err) && empty($password_err)) {
        $sql = "SELECT id, email, password FROM users WHERE email = :email";

        if ($stmt = $pdo->prepare($sql)) {
            $stmt->bindParam(":email", $param_email, PDO::PARAM_STR);
            $param_email = trim($_POST["email"]);

            if ($stmt->execute()) {
                if ($stmt->rowCount() == 1) {
                    if ($row = $stmt->fetch()) {
                        $id = $row["id"];
                        $email = $row["email"];
                        $hashed_password = $row["password"];
                        if (password_verify($password, $hashed_password)) {
                            // Start the session and set session variables
                            session_start();
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["email"] = $email;
                            
                            header("location: login.php");
                        } else {
                            $login_err = "Invalid email or password.";
                        }
                    }
                } else {
                    $login_err = "Invalid email or password.";
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            unset($stmt);
        }
    }

    unset($pdo);
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font: 14px sans-serif;
            background: linear-gradient(135deg, #00aaff, #ff6600); 
            color: #ffffff; 
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .wrapper {
            width: 360px;
            padding: 30px;
            background-color: rgba(0, 0, 0, 0.7); 
            border-radius: 12px;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.3); 
            transition: all 0.3s ease;
        }
        .wrapper:hover {
            transform: translateY(-5px); 
        }
        h2, p {
            color: #ff6600; 
            font-weight: bold;
        }
        .form-control {
            background-color: #333333; 
            color: #ffffff; 
            border: 1px solid #ff6600; 
            transition: all 0.3s ease;
        }
        .form-control:focus {
            border-color: #ff9900; 
            box-shadow: 0 0 8px rgba(255, 165, 0, 0.6);
        }
        .btn-primary {
            background-color: #ff6600; /* Bright orange button */
            border: none;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #ff9900; /* Light orange on hover */
            transform: scale(1.05); 
        }
        .alert-danger {
            background-color: #ff6600; /* Orange background for error messages */
            color: #ffffff;
            border: none;
            font-weight: bold;
        }
        a {
            color: #ffffff; 
            text-decoration: underline;
            transition: all 0.3s ease;
        }
        a:hover {
            color: #ff6600; 
            text-decoration: none;
        }
        .invalid-feedback {
            color: #ff6600;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Login</h2>
        <p>Please fill in your credentials to login.</p>

        <?php 
        if(!empty($login_err)){
            echo '<div class="alert alert-danger">' . $login_err . '</div>';
        }        
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>">
                <span class="invalid-feedback"><?php echo $email_err; ?></span>
            </div>    
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary btn-block" value="Login">
            </div>
            <p>Don't have an account? <a href="sign_up.php">Sign up now</a>.</p>
            <p>Or would you like to go <a href="index.php">back</a>.</p>
        </form>
    </div>
</body>
</html>
