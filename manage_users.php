<?php
$host = 'localhost';
$username = 'root';  
$password = '';      
$database = 'mydb'; 
$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

session_start();

$delete_message = "";
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $delete_query = "DELETE FROM users WHERE id = $delete_id";
    if (mysqli_query($conn, $delete_query)) {
        $delete_message = "User deleted successfully!";
    } else {
        $delete_message = "Error deleting user: " . mysqli_error($conn);
    }
}
$query = "SELECT * FROM users";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <style>
      body {
        font-family: 'Arial', sans-serif;
        background-color: #f4f7fc;
        margin: 0;
        padding: 0;
        color: #333;
    }

    nav {
        background: linear-gradient(135deg, #1f4e79, #ff6f20);
        padding: 10px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    nav ul {
        list-style-type: none;
        padding: 0;
        margin: 0;
    }

    nav li {
        display: inline;
        margin-right: 15px;
    }

    nav a {
        color: #fff;
        text-decoration: none;
        font-weight: bold;
        padding: 8px 15px;
        border-radius: 4px;
        transition: background-color 0.3s ease;
    }

    nav a:hover {
        background-color: rgba(255, 255, 255, 0.2);
    }

    .container {
        max-width: 1200px;
        margin: 30px auto;
        padding: 20px;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    h1 {
        font-size: 2.5rem;
        text-align: center;
        color: #333;
        margin-bottom: 20px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 30px;
        background-color: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    th, td {
        padding: 15px;
        border: 1px solid #ddd;
        text-align: left;
        font-size: 16px;
    }

    th {
        background-color: #4CAF50;
        color: white;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    td {
        background-color: #f9f9f9;
        color: #555;
    }

    tr:nth-child(even) {
        background-color: #f1f1f1;
    }

    tr:hover {
        background-color: #e7f7e7;
    }

    /* Success message styling */
    .success-message {
        background-color: #28a745;
        color: white;
        padding: 15px;
        text-align: center;
        font-size: 16px;
        border-radius: 5px;
        margin-bottom: 20px;
        display: none;
        animation: fadeInOut 3s ease-in-out forwards;
    }

    @keyframes fadeInOut {
        0% { opacity: 0; }
        10% { opacity: 1; }
        90% { opacity: 1; }
        100% { opacity: 0; }
    }

    /* Cool delete button styling */
    .btn-delete {
        background-color: #dc3545;
        color: white;
        padding: 10px 20px;
        font-size: 14px;
        font-weight: bold;
        border-radius: 50px; /* pill-shaped button */
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
    }

    .btn-delete:hover {
        background-color: #c82333;
        transform: scale(1.05); /* Subtle scaling effect */
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2); /* Add shadow on hover */
    }

    .btn-delete i {
        font-size: 16px; /* Adjust icon size */
    }

    /* Buttons styling */
    .actions {
        display: flex;
        gap: 10px;
    }

    /* Responsive design for smaller screens */
    @media (max-width: 768px) {
        nav {
            padding: 10px 15px;
        }

        nav a {
            font-size: 16px;
        }

        h1 {
            font-size: 2rem;
        }

        table th, table td {
            padding: 12px;
            font-size: 14px;
        }

        .container {
            padding: 15px;
        }
    }

</style>
</head>

<body>
<nav class="navbar navbar-expand-lg navbar-dark">
    <a class="navbar-brand" href="#">Admin Dashboard</a>
    <div class="ml-auto">
        <a href="admindashboard.php" class="btn btn-danger">Home</a>
        <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>
</nav>

<h1>Manage Users</h1>

<?php if ($delete_message): ?>
    <div class="success-message" id="success-message"><?php echo $delete_message; ?></div>
<?php endif; ?>

<table border="1">
    <thead>
        <tr>
            <th>ID</th>
            <th>Email</th>
            <th>Status</th>
            <th>Full Name</th>
            <th>Course</th>
            <th>Cellphone Number</th>
            <th>Gender</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['id']) . "</td>";
            echo "<td>" . htmlspecialchars($row['email']) . "</td>";
            echo "<td>" . htmlspecialchars($row['status']) . "</td>";
            echo "<td>" . htmlspecialchars($row['full_name']) . "</td>";
            echo "<td>" . htmlspecialchars($row['course']) . "</td>";
            echo "<td>" . htmlspecialchars($row['cellphone_number']) . "</td>";
            echo "<td>" . htmlspecialchars($row['gender']) . "</td>";
            // Enhanced delete button with an icon
            echo "<td><a href='?delete_id=" . $row['id'] . "' class='btn-delete' onclick='return confirm(\"Are you sure you want to delete this user?\")'>
                    <i class='fas fa-trash'></i> Delete</a></td>";
            echo "</tr>";
        }
        ?>
    </tbody>
</table>

<script>
    if (document.getElementById('success-message')) {
        setTimeout(function() {
            document.getElementById('success-message').style.display = 'none';
        }, 2000); 
    }
</script>

<script src="https://kit.fontawesome.com/a076d05399.js"></script>

</body>
</html>

<?php
mysqli_close($conn);
?>
