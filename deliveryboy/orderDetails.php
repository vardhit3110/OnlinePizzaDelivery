<?php
@session_start();
include 'partials/_dbconnect.php';
// Ensure the database connection is included

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

// Fetch all orders assigned to the logged-in delivery boy
$query = "SELECT o.*, u.firstName, u.lastName, d.first_name AS deliveryBoyFirstName, d.last_name AS deliveryBoyLastName 
          FROM orders o
          INNER JOIN users u ON o.userId = u.id
          INNER JOIN delivery_boys d ON o.delivery_boy_id = d.id
          WHERE o.delivery_boy_id = ?
          ORDER BY o.orderId DESC"; // Ordering by Order ID in descending order

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $deliveryboyId);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details</title>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.min.js"></script>
</head>
<body>
    <div class="container" style="margin-top:98px; background: aliceblue; padding: 20px; border-radius: 10px;">
        <div class="table-wrapper">
            <div class="table-title" style="border-radius: 14px; background: #3b8996; padding: 10px; color: white;">
                <div class="row">
                    <div class="col-sm-4">
                        <h2>Order <b>Details</b></h2>
                    </div>
                    <div class="col-sm-8 text-right">
                        <a href="#" onclick="window.print()" class="btn btn-info"><i class="fa fa-print"></i> <span>Print</span></a>
                    </div>
                </div>
            </div>
            <table class="table table-striped table-hover text-center position-center">
                <thead style="background-color: rgb(111, 202, 203);">
                    <tr>
                        <th>Order ID</th>
                        <th>User Name</th> <!-- New Column for User Name -->
                        <th>Full Address</th> <!-- Updated Column for Full Address -->
                        <th>Zip Code</th>
                        <th>Phone No</th>
                        <th>Amount</th>
                        <th>Payment Mode</th>
                        <th>Status</th>
                        <th>Items</th>
                    </tr>
                </thead>
                <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($order = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($order['orderId']); ?></td>
                            <td><?php echo htmlspecialchars($order['firstName'] . ' ' . $order['lastName']); ?></td> <!-- Display User Name -->
                            <td><?php echo htmlspecialchars($order['address']); ?></td> <!-- Display Full Address -->
                            <td><?php echo htmlspecialchars($order['zipCode']); ?></td>
                            <td><?php echo htmlspecialchars($order['phoneNo']); ?></td>
                            <td>₹<?php echo htmlspecialchars($order['amount']); ?></td>
                            <td><?php echo ($order['paymentMode'] == '0') ? 'Cash on Delivery' : 'Online Payment'; ?></td>
                            <td><a href="#" data-toggle="modal" data-target="#orderStatus<?php echo $order['orderId']; ?>" class="view"><i class="material-icons">&#xE5C8;</i></a></td>
                            <td><a href="#" data-toggle="modal" data-target="#orderItem<?php echo $order['orderId']; ?>" class="view"><i class="material-icons">&#xE5C8;</i></a></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9" class="text-center">No orders found</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php include 'partials/_orderItemModal.php';?>
    <?php include 'partials/_orderStatusModal.php';?>
    <style>
    .tooltip.show {
        top: -62px !important;
    }
    
    .table-wrapper .btn {
        float: right;
        color: #333;
        background-color: #fff;
        border-radius: 3px;
        border: none;
        outline: none !important;
        margin-left: 10px;
    }
    .table-wrapper .btn:hover {
        color: #333;
        background: #f2f2f2;
    }
    .table-wrapper .btn.btn-primary {
        color: #fff;
        background: #03A9F4;
    }
    .table-wrapper .btn.btn-primary:hover {
        background: #03a3e7;
    }
    .table-title .btn {		
        font-size: 13px;
        border: none;
    }
    .table-title .btn i {
        float: left;
        font-size: 21px;
        margin-right: 5px;
    }
    .table-title .btn span {
        float: left;
        margin-top: 2px;
    }
    .table-title {
        color: #fff;
        background: #4b5366;		
        padding: 16px 25px;
        margin: -20px -25px 10px;
        border-radius: 3px 3px 0 0;
    }
    .table-title h2 {
        margin: 5px 0 0;
        font-size: 24px;
    }
    table.table tr th, table.table tr td {
        border-color: #e9e9e9;
        padding: 12px 15px;
        vertical-align: middle;
    }
    table.table tr th:first-child {
        width: 60px;
    }
    table.table tr th:last-child {
        width: 80px;
    }
    table.table-striped tbody tr:nth-of-type(odd) {
        /* background-color: #fcfcfc; */
    }
    table.table-striped.table-hover tbody tr:hover {
        /* background: #f5f5f5; */
    }
    table.table th i {
        font-size: 13px;
        margin: 0 5px;
        cursor: pointer;
    }	
    table.table td a {
        font-weight: bold;
        color: #566787;
        display: inline-block;
        text-decoration: none;
    }
    table.table td a:hover {
        color: #2196F3;
    }
    table.table td a.view {        
        width: 30px;
        height: 30px;
        color: #2196F3;
        border: 2px solid;
        border-radius: 30px;
        text-align: center;
    }
    table.table td a.view i {
        font-size: 22px;
        margin: 2px 0 0 1px;
    }   
    table.table .avatar {
        border-radius: 50%;
        vertical-align: middle;
        margin-right: 10px;
    }
    table {
        counter-reset: section;
    }

    .count:before {
        counter-increment: section;
        content: counter(section);
    }
    /* Style for the form inside the table cell */
    td form {
        display: flex;
        align-items: center;
        gap: 8px; /* Space between select and button */
    }

    /* Style for the select dropdown */
    td form select {
        width: auto;
        min-width: 150px;
        padding: 6px 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 14px;
    }

    /* Style for the submit button */
    td form button {
        padding: 6px 12px;
        font-size: 14px;
        background-color: #28a745; /* Bootstrap success color */
        border: none;
        color: white;
        border-radius: 5px;
        cursor: pointer;
        transition: background 0.3s ease-in-out;
    }

    /* Hover effect for the button */
    td form button:hover {
        background-color: #218838;
    }
    </style>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
</body>
</html>