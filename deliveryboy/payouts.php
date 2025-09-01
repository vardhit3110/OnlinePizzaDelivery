<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
@session_start();
include 'partials/_dbconnect.php';

// Restore session if lost
if (!isset($_SESSION['loggedin'])) {
    if (isset($_COOKIE['deliveryboy_id'])) {
        $_SESSION['loggedin'] = true;
        $_SESSION['role'] = 'delivery_boy';
        $_SESSION['deliveryboy_id'] = $_COOKIE['deliveryboy_id'];
    }
}

// Check if the user is logged in as a delivery boy
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] !== 'delivery_boy') {
    header("location: /OnlinePizzaDelivery/index.php");
    exit();
}

$deliveryboyId = $_SESSION['deliveryboy_id'];
$sql = "SELECT orderId, amount, createdAt, (amount * 0.1) AS payout FROM orders WHERE delivery_boy_id = ? AND orderStatus = '4' ORDER BY createdAt DESC";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Query preparation failed: " . $conn->error);
}
$stmt->bind_param("i", $deliveryboyId);
$stmt->execute();
$result = $stmt->get_result();

$totalPayout = 0;
$orders = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $totalPayout += $row['payout'];
        $orders[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delivery Boy Payouts</title>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.min.js"></script>
    <style>
        .print-button {
            position: absolute;
            right: 20px;
            top: 20px;
            font-size: 14px;
            padding: 5px 10px;
            float: right;
            /* Align to the right */
        }

        .total-payout-btn {
            margin-top: 20px;
            width: auto;
            font-size: 14px;
            font-weight: bold;
            padding: 8px 12px;
            float: right;
            /* Align to the right */
        }

        .print-out-btn {
            display: block;
            width: auto;
            text-align: center;
            margin-top: 10px;
            font-size: 14px;
            padding: 8px 12px;
            float: right;
            /* Align to the right */
        }
    </style>

</head>

<body>
    <div class="container-fluid" style="margin-top: 20px; padding-top: 20px; position: relative;">

        <div class="row">
            <div class="card col-lg-12">
                <div class="card-body">
                    <button class="btn btn-warning btn-sm print-out-btn" onclick="window.print();">
                        <i class="fas fa-print"></i> Print Payouts
                    </button><br>
                    <h2 class="text-center mb-4">My Payouts</h2>
                    <table class="table table-striped table-bordered text-center">
                        <thead style="background-color: rgb(111 202 203);">
                            <tr>
                                <th>Order ID</th>
                                <th>Amount (₹)</th>
                                <th>Delivery Date</th>
                                <th>Payout (₹)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($orders)): ?>
                                <?php foreach ($orders as $row): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($row['orderId']); ?></td>
                                        <td>₹<?= number_format($row['amount'], 2); ?></td>
                                        <td><?= date("d-m-Y H:i:s", strtotime($row['createdAt'])); ?></td>
                                        <td>₹<?= number_format($row['payout'], 2); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4">No payouts available. Complete orders to earn payouts!</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                    <div style="text-align: right;">
                        <button class="btn btn-success btn-sm total-payout-btn">
                            Total Payout: ₹<?= number_format($totalPayout, 2); ?>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>
<?php $conn->close(); ?>