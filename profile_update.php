<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

require_once "config.php";

$full_name = $course = $cellphone_number = $gender = $status = "";
$full_name_err = $course_err = $cellphone_number_err = $gender_err = $status_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
 
    if (empty(trim($_POST["full_name"]))) {
        $full_name_err = "Please enter your full name.";
    } else {
        $full_name = trim($_POST["full_name"]);
    }

    if (empty(trim($_POST["course"]))) {
        $course_err = "Please enter your course.";
    } else {
        $course = trim($_POST["course"]);
    }

    if (empty(trim($_POST["cellphone_number"]))) {
        $cellphone_number_err = "Please enter your cellphone number.";
    } else {
        $cellphone_number = trim($_POST["cellphone_number"]);
    }

    if (empty($_POST["gender"])) {
        $gender_err = "Please select your gender.";
    } else {
        $gender = $_POST["gender"];
    }

    if (empty($_POST["status"])) {
        $status_err = "Please select your role.";
    } else {
        $status = $_POST["status"];
    }

    if (empty($full_name_err) && empty($course_err) && empty($cellphone_number_err) && empty($gender_err) && empty($status_err)) {

        $sql = "UPDATE users SET full_name = :full_name, course = :course, cellphone_number = :cellphone_number, gender = :gender, status = :status WHERE id = :id";
        
        if ($stmt = $pdo->prepare($sql)) {
            $stmt->bindParam(":full_name", $full_name, PDO::PARAM_STR);
            $stmt->bindParam(":course", $course, PDO::PARAM_STR);
            $stmt->bindParam(":cellphone_number", $cellphone_number, PDO::PARAM_STR);
            $stmt->bindParam(":gender", $gender, PDO::PARAM_STR);
            $stmt->bindParam(":status", $status, PDO::PARAM_STR);
            $stmt->bindParam(":id", $_SESSION["id"], PDO::PARAM_INT);
            
            if ($stmt->execute()) {
               
                header("location: dashboard.php");
                exit;
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
    }

    unset($pdo);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile Update</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2>Complete Your Profile</h2>
        <p>Please fill in the information below.</p>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="full_name" class="form-control <?php echo (!empty($full_name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $full_name; ?>">
                <span class="invalid-feedback"><?php echo $full_name_err; ?></span>
            </div>    
            <div class="form-group">
                <label>Course</label>
                <input type="text" name="course" class="form-control <?php echo (!empty($course_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $course; ?>">
                <span class="invalid-feedback"><?php echo $course_err; ?></span>
            </div>
            <div class="form-group">
                <label>Cellphone Number</label>
                <input type="text" name="cellphone_number" class="form-control <?php echo (!empty($cellphone_number_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $cellphone_number; ?>">
                <span class="invalid-feedback"><?php echo $cellphone_number_err; ?></span>
            </div>
            <div class="form-group">
                <label>Gender</label>
                <select name="gender" class="form-control <?php echo (!empty($gender_err)) ? 'is-invalid' : ''; ?>">
                    <option value="male" <?php echo ($gender == 'male') ? 'selected' : ''; ?>>Male</option>
                    <option value="female" <?php echo ($gender == 'female') ? 'selected' : ''; ?>>Female</option>
                    <option value="other" <?php echo ($gender == 'other') ? 'selected' : ''; ?>>Other</option>
                </select>
                <span class="invalid-feedback"><?php echo $gender_err; ?></span>
            </div>
            <div class="form-group">
                <label>Role</label>
                <select name="status" class="form-control <?php echo (!empty($status_err)) ? 'is-invalid' : ''; ?>">
                    <option value="student" <?php echo ($status == 'student') ? 'selected' : ''; ?>>Student</option>
                    <option value="instructor" <?php echo ($status == 'instructor') ? 'selected' : ''; ?>>Instructor</option>
                    <option value="staff" <?php echo ($status == 'staff') ? 'selected' : ''; ?>>Staff</option>
                </select>
                <span class="invalid-feedback"><?php echo $status_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Update Profile">
            </div>
        </form>
    </div>
</body>
</html>
