<?php
@session_start();
include 'partials/_dbconnect.php'; // Ensure the database connection is included

// Restore session if lost
if (!isset($_SESSION['loggedin'])) {
    if (isset($_COOKIE['deliveryboy_id'])) {
        $_SESSION['loggedin'] = true;
        $_SESSION['role'] = 'delivery_boy';
        $_SESSION['deliveryboy_id'] = $_COOKIE['deliveryboy_id'];
    }
}

// Check if the user is logged in as a delivery boy
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true && isset($_SESSION['role']) && $_SESSION['role'] === 'delivery_boy') {
    $deliveryboy_loggedin = true;
    $deliveryboyId = $_SESSION['deliveryboy_id'];
} else {
    header("location: /OnlinePizzaDelivery/index.php");
    exit();
}

// Fetch orders assigned to this delivery boy
$sql = "SELECT o.orderId, o.userId, o.address, o.zipCode, o.phoneNo, o.amount, 
               o.paymentMode, o.orderStatus, o. createdAt, o.delivery_boy_id, 
               u.username 
        FROM orders o 
        JOIN users u ON o.userId = u.id 
        WHERE o.delivery_boy_id = ? 
        ORDER BY o.createdAt DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $deliveryboyId);
$stmt->execute();
$result = $stmt->get_result();

// Define categories for orders
$orders = [
    'pending' => [],
    'completed' => [],
    'cancelled' => []
];

// Define status mapping
$orderStatusMap = [
    '0' => 'Order Placed',
    '1' => 'Order Confirmed',
    '2' => 'Preparing your Order',
    '3' => 'Your order is on the way!',
    '4' => 'Order Delivered',
    '5' => 'Order Denied',
    '6' => 'Order Cancelled'
];

// Organize orders by category
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $status = $row['orderStatus'];

        if (in_array($status, ['0', '1', '2', '3'])) {
            $orders['pending'][] = $row;
        } elseif ($status == '4') {
            $orders['completed'][] = $row;
        } elseif (in_array($status, ['5', '6'])) {
            $orders['cancelled'][] = $row;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details</title>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.min.js"></script>
    <style>
        .container {
            margin-top: 80px;
        }
        .table thead {
            background-color: #76c7c0;
            color: black;
            font-weight: bold;
        }
        .bg-pending { 
            background-color: rgb(111, 202, 203); 
        }
        .bg-completed { background-color: rgb(111, 202, 203); }
        .bg-cancelled { background-color: rgb(111, 202, 203); }
        .card {
            border-radius: 10px;
            overflow: hidden;
        }
        .table-bordered {
            border-color: #ddd;
        }
    </style>
</head>
<body>

<div class="container">
    <h2 class="text-center">Your Assigned Orders</h2>

    <?php foreach ($orders as $status => $orderList): ?>
        <div class="card mb-4">
            <div class="card-header text-white 
                <?php echo ($status == 'pending') ? 'bg-pending' : 
                    (($status == 'completed') ? 'bg-completed' : 'bg-cancelled'); ?>">
                <h4 class="text-center" style="color: black;"><?php echo ucfirst($status); ?> Orders</h4>
            </div>

            <div class="card-body">
                <table class="table table-bordered text-center">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Address</th>
                            <th>Zip Code</th>
                            <th>Phone No</th>
                            <th>Amount</th>
                            <th>Payment Mode</th>
                            <th>Order Status</th>
                            <th>Order Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($orderList)): ?>
                            <?php foreach ($orderList as $order): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($order['orderId']); ?></td>
                                    <td><?php echo htmlspecialchars($order['username']); ?></td>
                                    <td><?php echo htmlspecialchars($order['address']); ?></td>
                                    <td><?php echo htmlspecialchars($order['zipCode']); ?></td>
                                    <td><?php echo htmlspecialchars($order['phoneNo']); ?></td>
                                    <td>₹<?php echo htmlspecialchars($order['amount']); ?></td>
                                    <td><?php echo ($order['paymentMode'] == '0') ? 'Cash on Delivery' : 'Online'; ?></td>
                                    <td><?php echo htmlspecialchars($orderStatusMap[$order['orderStatus']]); ?></td>
                                    <td><?php echo htmlspecialchars($order['createdAt']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="9" class="text-center">No orders in this category</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>