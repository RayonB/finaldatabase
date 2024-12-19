<?php

require_once "config.php";
 
$email = $password = $confirm_password = "";
$email_err = $password_err = $confirm_password_err = "";
 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
 
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter an email.";
    } elseif (!filter_var(trim($_POST["email"]), FILTER_VALIDATE_EMAIL)) {
        $email_err = "Please enter a valid email address.";
    } else {
        $sql = "SELECT id FROM users WHERE email = :email";
        
        if ($stmt = $pdo->prepare($sql)) {
            $stmt->bindParam(":email", $param_email, PDO::PARAM_STR);
            
            $param_email = trim($_POST["email"]);
            
            if ($stmt->execute()) {
                if ($stmt->rowCount() == 1) {
                    $email_err = "This email is already taken.";
                } else {
                    $email = trim($_POST["email"]);
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            unset($stmt);
        }
    }
    
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter a password.";     
    } elseif (strlen(trim($_POST["password"])) < 6) {
        $password_err = "Password must have at least 6 characters.";
    } else {
        $password = trim($_POST["password"]);
    }
    
    if (empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "Please confirm password.";     
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if (empty($password_err) && ($password != $confirm_password)) {
            $confirm_password_err = "Password did not match.";
        }
    }
    
    if (empty($email_err) && empty($password_err) && empty($confirm_password_err)) {
        
        $sql = "INSERT INTO users (email, password) VALUES (:email, :password)";
         
        if ($stmt = $pdo->prepare($sql)) {
          
            $stmt->bindParam(":email", $param_email, PDO::PARAM_STR);
            $stmt->bindParam(":password", $param_password, PDO::PARAM_STR);
            
            $param_email = $email;
            $param_password = password_hash($password, PASSWORD_DEFAULT); 
            
            if ($stmt->execute()) {
             
                header("location: login.php");
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
    <title>Sign Up</title>
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
            background-color: #ff6600; 
            border: none;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #ff9900;
            transform: scale(1.05); 
        }
        .alert-danger {
            background-color: #ff6600;
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
        <h2>Sign Up</h2>
        <p>Please fill out this form to create an account.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>">
                <span class="invalid-feedback"><?php echo $email_err; ?></span>
            </div>    
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>">
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_password; ?>">
                <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <input type="reset" class="btn btn-secondary ml-2" value="Reset">
            </div>
            <p>Already have an account? <a href="login.php">Login here</a>.</p>
            <p>Or would you like to go <a href="index.php">back</a>.</p>
        </form>
    </div>    
</body>
</html>
