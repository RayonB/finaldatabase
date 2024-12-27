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
</head>
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

    /* Buttons styling */
    .actions {
        display: flex;
        gap: 10px;
    }

    .btn {
        padding: 8px 12px;
        text-decoration: none;
        color: #fff;
        border-radius: 4px;
        font-size: 14px;
        cursor: pointer;
        border: none;
    }

    .btn-approve {
        background-color: #28a745;
    }

    .btn-approve:hover {
        background-color: #218838;
    }

    .btn-disapprove {
        background-color: #dc3545;
    }

    .btn-disapprove:hover {
        background-color: #c82333;
    }
    .btn-remove {
    background-color: #dc3545;
    color: #fff;
    padding: 8px 12px;
    text-decoration: none;
    border-radius: 4px;
    font-size: 14px;
    cursor: pointer;
}

.btn-remove:hover {
    background-color: #c82333;
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
<body>
<nav class="navbar navbar-expand-lg navbar-dark">
    <a class="navbar-brand" href="#">Admin Dashboard</a>
    <div class="ml-auto">
       
        <a href="admindashboard.php" class="btn btn-danger">Home</a>
        <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>
</nav>


    <h1>Manage Users</h1>
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
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>

</body>
</html>

<?php
mysqli_close($conn);
?>
