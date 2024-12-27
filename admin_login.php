<?php
session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = :username AND password = :password");
    $stmt->execute(['username' => $username, 'password' => $password]);
    $admin = $stmt->fetch();

    if ($admin) {
        $_SESSION['admin'] = $admin['username'];
        header('Location: admindashboard.php');
        exit;
    } else {
        echo "<script>alert('Invalid Admin Username or Password!'); window.location.href='index.php';</script>";
    }
}
?>
