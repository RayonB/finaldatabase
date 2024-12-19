<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli('localhost', 'root', '', 'your_database_name'); 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $course = $_POST['course'];
    $cellphone_number = $_POST['cellphone_number'];
    $gender = $_POST['gender'];

    $sql = "UPDATE users SET course = '$course', cellphone_number = '$cellphone_number', gender = '$gender' WHERE user_id = '$user_id'";

    if ($conn->query($sql) === TRUE) {
        echo "Information saved successfully!";
 
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complete Your Information</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="info-container">
        <h2>Please fill out your information</h2>
        <form method="POST" action="">
            <label for="course">Course:</label>
            <input type="text" name="course" required>
            
            <label for="cellphone_number">Cellphone Number:</label>
            <input type="text" name="cellphone_number" required>
            
            <label for="gender">Gender:</label>
            <select name="gender" required>
                <option value="male">Male</option>
                <option value="female">Female</option>
                <option value="other">Other</option>
            </select>
            
            <input type="submit" value="Save Information">
        </form>
    </div>
</body>
</html>
