<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require 'partials/_dbconnect.php';

// Check if delivery boy is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] !== 'delivery_boy') {
    header("location: /OnlinePizzaDelivery/index.php");
    exit();
}

$deliveryboy_id = $_SESSION['deliveryboy_id'];

// Fetch delivery boy's name from `delivery_boys` table
$query = "SELECT delivery_boy_name FROM delivery_boys WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $deliveryboy_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$delivery_boy_name = $row['delivery_boy_name'] ?? 'Delivery Boy';

// Fetch delivery statistics from `orders` table
$query = "SELECT 
    SUM(orderStatus IN ('0','1','2','3')) AS pending_count,
    SUM(orderStatus = '4') AS delivered_count,
    SUM(orderStatus IN ('5','6')) AS failed_count
    FROM orders WHERE delivery_boy_id = ?";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $deliveryboy_id);
$stmt->execute();
$result = $stmt->get_result();
$stats = $result->fetch_assoc();

$pending_count = $stats['pending_count'] ?? 0;
$delivered_count = $stats['delivered_count'] ?? 0;
$failed_count = $stats['failed_count'] ?? 0;

$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delivery Boy Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.min.js"></script>
    <style>
        .delivery-container {
            margin-top: 50px;
            max-width: 900px;
            margin-left: auto;
            margin-right: auto;
            text-align: center;
        }
        .dashboard {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 20px;
        }
        .card {
            width: 48%;
            background-color: #4723d9;
            color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.2);
        }
        .card i {
            font-size: 30px;
            margin-right: 15px;
        }
    </style>
</head>
<body>
    <div class="delivery-container" style="padding: 40px">
        <h2>Welcome, <?php echo htmlspecialchars($delivery_boy_name); ?>!</h2>
        <div class="dashboard">
            <div class="card">
                <i class="bx bx-time-five delivery-card-icon"></i>
                <div>
                    <h3>Pending Deliveries</h3>
                    <p><?php echo $pending_count; ?></p>
                </div>
            </div>
            <div class="card">
                <i class="bx bx-check-circle delivery-card-icon"></i>
                <div>
                    <h3>Delivered Orders</h3>
                    <p><?php echo $delivered_count; ?></p>
                </div>
            </div>
            <div class="card">
                <i class="bx bx-x-circle delivery-card-icon"></i>
                <div>
                    <h3>Failed Deliveries</h3>
                    <p><?php echo $failed_count; ?></p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>