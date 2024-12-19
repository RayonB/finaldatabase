<?php
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

$sql = "SELECT * FROM requests";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Requests</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <h1>Request Form</h1>
    <form id="requestForm" action="submit_request.php" method="POST">
        <div>
            <h4>Select the requests you need:</h4>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" name="requests[]" value="<?php echo $row['id']; ?>" data-amount="<?php echo $row['amount']; ?>">
                    <label class="form-check-label"><?php echo $row['request_name']; ?> - $<?php echo number_format($row['amount'], 2); ?></label>
                </div>
            <?php } ?>
        </div>
        <div>
            <h5>Total Amount: $<span id="totalAmount">0.00</span></h5>
        </div>
        <button type="submit" class="btn btn-primary">Submit Request</button>
    </form>

    <script>
       
        $("input[type='checkbox']").on("change", function() {
            let totalAmount = 0;
            $("input[type='checkbox']:checked").each(function() {
                totalAmount += parseFloat($(this).data("amount"));
            });
            $("#totalAmount").text(totalAmount.toFixed(2));
        });
    </script>
</body>
</html>
