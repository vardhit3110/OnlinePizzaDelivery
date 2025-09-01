<!doctype html>
<html lang="en">

<head>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
        integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css">
    <!-- Latest Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

    <title>Your Order</title>
    <link rel="icon" href="img/logo.jpg" type="image/x-icon">
    <style>
        .footer {
            position: fixed;
            bottom: 0;
        }

        .table-wrapper {
            background: #fff;
            padding: 20px 25px;
            margin: 30px auto;
            border-radius: 3px;
            box-shadow: 0 1px 1px rgba(0, 0, 0, .05);
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

        table.table tr th,
        table.table tr td {
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
            background-color: #fcfcfc;
        }

        table.table-striped.table-hover tbody tr:hover {
            background: #f5f5f5;
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

        table {
            counter-reset: section;
        }

        .count:before {
            counter-increment: section;
            content: counter(section);
        }
    </style>
</head>

<body>
    <?php include 'partials/_dbconnect.php'; ?>
    <?php include 'partials/_nav.php'; ?>
    <?php
    if ($loggedin) {
        ?>

        <div class="container">
            <div class="table-wrapper" id="empty">
                <div class="table-title">
                    <div class="row">
                        <div class="col-sm-4">
                            <h2>Order <b>Details</b></h2>
                        </div>
                        <div class="col-sm-8">
                            <a href="" class="btn btn-primary"><i class="material-icons">&#xE863;</i> <span>Refresh List</span></a>
                            <a href="#" onclick="window.print()" class="btn btn-info"><i class="material-icons">&#xE24D;</i>
                            <span>Print</span></a>
                            <button class="btn btn-secondary" data-toggle="modal" data-target="#history"><i class="material-icons">&#xE8B8;</i> <span>View Transaction History</span></button>
                        </div>
                    </div>
                </div>
                <table class="table table-striped table-hover text-center">
                    <thead>
                        <tr>
                            <th>Order Id</th>
                            <th>Address</th>
                            <th>Phone No</th>
                            <th>Amount</th>
                            <th>Payment Mode</th>
                            <th>Order Date</th>
                            <th>Status</th>
                            <th>Items</th>
                            <th>Cancel Order</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Fetch current orders (excluding completed and cancelled)
                        $sql = "SELECT * FROM `orders` WHERE `userId`= $userId AND `orderStatus` NOT IN ('4', '6') ORDER BY `createdAt` DESC";
                        $result = mysqli_query($conn, $sql);
                        $counter = 0;

                        while ($row = mysqli_fetch_assoc($result)) {
                            $orderId = $row['orderId'];
                            $address = $row['address'];
                            $phoneNo = $row['phoneNo'];
                            $amount = $row['amount'];
                            $paymentMode = $row['paymentMode'] == 0 ? "Cash on Delivery" : "Online";
                            $createdAt = $row['createdAt'];
                            $orderStatus = $row['orderStatus'];

                            echo '<tr>
                            <td>' . $orderId . '</td>
                            <td>' . substr($address, 0, 20) . '...</td>
                            <td>' . $phoneNo . '</td>
                            <td>₹' . $amount . '</td>
                            <td>' . $paymentMode . '</td>
                            <td>' . $createdAt . '</td>
                            <td><a href="#" data-toggle="modal" data-target="#orderStatus' . $orderId . '" class="view"><i class="material-icons">&#xE5C8;</i></a></td>
                            <td><a href="#" data-toggle="modal" data-target="#orderItem' . $orderId . '" class="view" title="View Details"><i class="material-icons">&#xE5C8;</i></a></td>
                            <td>
                                <a href="cancel_order.php?orderId=' . $orderId . '" class="btn btn-danger" onclick="return confirm(\'Are you sure you want to cancel this order?\');">Cancel</a>
                            </td>
                        </tr>';
                            $counter++;
                        }

                        if ($counter == 0) {
                            ?>
                            <script>
                                document.getElementById("empty").innerHTML = '<div class="col-md-12 my-5"><div class="card"><div class="card-body cart"><div class="col-sm-12 empty-cart-cls text-center"> <img src="https://i.imgur.com/dCdflKN.png" width="130" height="130" class="img-fluid mb-4 mr-3"><h3><strong>You have not ordered any items.</strong></h3><h4>Please order to make me happy :)</h4> <a href="index.php" class="btn btn-primary cart-btn-transform m-3" data-abc="true">continue shopping</a> </div></div></div></div>';
                            </script>
                            <?php
                        }
                        ?>
                    </tbody>                
                </table>
            </div>

            <!-- Previous Orders Section -->
<div class="table-wrapper">
    <div class="table-title">
        <h2>Previous <b>Orders</b></h2>
    </div>
    <table class="table table-striped table-hover text-center">
        <thead>
            <tr>
                <th>Order Id</th>
                <th>Address</th>
                <th>Phone No</th>
                <th>Amount</th>
                <th>Payment Mode</th>
                <th>Delivery Person Name</th>
                <th>Delivery Person Phone</th>
                <th>Status</th>
                <th>Final Delivery Time</th>
                <th>Items</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Fetch previous orders (statuses 4 and 6) with delivery boy details
            $previousSql = "
                SELECT o.*, db.delivery_boy_name, db.phone AS delivery_boy_phone 
                FROM `orders` o
                LEFT JOIN `delivery_boys` db ON o.delivery_boy_id = db.id
                WHERE o.userId = $userId AND o.orderStatus IN ('4', '6') 
                ORDER BY o.createdAt DESC
            ";
            $previousResult = mysqli_query($conn, $previousSql);
            $previousCounter = 0;

            while ($row = mysqli_fetch_assoc($previousResult)) {
                $orderId = $row['orderId'];
                $address = $row['address'];
                $phoneNo = $row['phoneNo'];
                $amount = $row['amount'];
                $paymentMode = $row['paymentMode'] == 0 ? "Cash on Delivery" : "Online";
                $deliveryBoyName = $row['delivery_boy_name'] ? $row['delivery_boy_name'] : 'N/A';
                $deliveryBoyPhone = $row['delivery_boy_phone'] ? $row['delivery_boy_phone'] : 'N/A';
                $orderStatus = $row['orderStatus'];
                $finalDeliveryTime = $row['finalDeliveryTime'];

                // Check if a review already exists for this order
                $reviewCheckSql = "SELECT * FROM `reviews` WHERE `orderId` = ? AND `userId` = ?";
                $stmtReviewCheck = $conn->prepare($reviewCheckSql);
                $stmtReviewCheck->bind_param("ii", $orderId, $userId);
                $stmtReviewCheck->execute();
                $reviewResult = $stmtReviewCheck->get_result();
                $reviewExists = $reviewResult->num_rows > 0;

                echo '<tr id="order-' . $orderId . '">
                        <td>' . $orderId . '</td>
                        <td>' . $address . '</td>
                        <td>' . $phoneNo . '</td>
                        <td>₹' . $amount . '</td>
                        <td>' . $paymentMode . '</td>
                        <td>' . $deliveryBoyName . '</td>
                        <td>' . $deliveryBoyPhone . '</td>
                        <td>' . ($orderStatus == 4 ? "Completed" : "Cancelled") . '</td>
                        <td>' . ($finalDeliveryTime ? $finalDeliveryTime . ' minutes' : 'N/A') . '</td>
                        <td>
                            <a href="#" data-toggle="modal" data-target="#orderItem' . $orderId . '" class="view" title="View Details"><i class="material-icons">&#xE5C8;</i></a>
                        </td>
                        <td>';
                // Show delete button if a review exists
                if ($orderStatus == 4 && $reviewExists) {
                    echo '<button class="btn btn-danger" onclick="deleteOrder(' . $orderId . ')">Delete</button>';
                } else if ($orderStatus == 4 && !$reviewExists) {
                    echo '<button class="btn btn-warning" data-toggle="modal" data-target="#reviewModal' . $orderId . '">Feedback</button>';
                }
                echo '</td>
                    </tr>';

                // Close the prepared statement
                $stmtReviewCheck->close();
                $previousCounter++;
            }

            if ($previousCounter == 0) {
                echo '<tr><td colspan="11">You have no previous orders.</td></tr>'; // Adjusted colspan to 11
            }
            ?>
        </tbody>
    </table>
</div>

<!-- Review Modal -->
<?php
// Reset the previous result pointer to fetch again for the review modal
$previousResult->data_seek(0);
while ($row = mysqli_fetch_assoc($previousResult)) {
    $orderId = $row['orderId'];
    $delivery_boy_id = $row['delivery_boy_id']; // Assuming this is available
    ?>
    <div class="modal fade" id="reviewModal<?php echo $orderId; ?>" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Rate Your Delivery Experience</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="submit_review.php" method="POST">
                        <input type="hidden" name="orderId" value="<?php echo $orderId; ?>">
                        <input type="hidden" name="userId" value="<?php echo $userId; ?>">
                        <input type="hidden" name="delivery_boy_id" value="<?php echo $delivery_boy_id; ?>">
                        <label>Rate Delivery:</label>
                        <select name="rating" class="form-control" required>
                            <option value="5">5 - Excellent</option>
                            <option value="4">4 - Good</option>
                            <option value="3">3 - Average</option>
                            <option value="2">2 - Poor</option>
                            <option value="1">1 - Bad</option>
                        </select>
                        <label>Complaint (Optional):</label>
                        <textarea name="complain" class="form-control" rows="3"></textarea>
                        <button type="submit" name="submit" class="btn btn-primary mt-2">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php
}
?>

<script>
    function deleteOrder(orderId) {
        // Find the row with the specified orderId and remove it from the table
        var row = document.getElementById('order-' + orderId);
        if (row) {
            row.parentNode.removeChild(row); // Remove the row from the DOM
        }
        alert('Record deleted successfully!'); // Optional: Show a confirmation message
    }

    function showDeleteButton(orderId) {
        // Show the delete button after feedback is submitted
        var deleteButton = document.querySelector('#order-' + orderId + ' .btn-danger');
        if (deleteButton) {
            deleteButton.style.display = 'block'; // Make the delete button visible
        }
    }
</script>
</div>
<?php
} else {
    echo '<div class="container" style="min-height : 610px;">
    <div class="alert alert-info my-3">
        <font style="font-size:22px"><center>Check your Order. You need to <strong><a class="alert-link" data-toggle="modal" data-target="#loginModal">Login</a></strong></center></font>
    </div></div>';
}
?>
<!-- Transaction History Modal -->
<div class="modal fade" id="history" tabindex="-1" role="dialog" aria-labelledby="history" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: rgb(187 188 189);">
                <h5 class="modal-title" id="history">Transaction History</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="bd">
                <table class="table-striped table-bordered col-md-12 text-center">
                    <thead style="background-color: rgb(111 202 203);">
                        <tr>
                            <th>Order ID</th>
                            <th>Transaction Key</th>
                            <th>Created At</th>
                            <th>Payment Source</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Fetching transaction history for both online and cash on delivery payments, excluding statuses 4 and 6
                        $transactionSql = "SELECT orderId, payment_id, createdAt, paymentMode 
                                           FROM `orders` 
                                           WHERE `userId`= $userId 
                                           AND orderStatus NOT IN (4, 6) ORDER BY `createdAt` DESC"; 
                        $transactionResult = mysqli_query($conn, $transactionSql);
                        
                        if (!$transactionResult) {
                            echo "Error: " . mysqli_error($conn); // Display the error
                        }

                        $count = 0;

                        while ($transactionRow = mysqli_fetch_assoc($transactionResult)) {
                            $count++;
                            $paymentSource = ($transactionRow['paymentMode'] == 1) ? "Razorpay" : "Cash on Delivery"; // Determine payment source
                            $transactionKey = ($transactionRow['paymentMode'] == 1) ? $transactionRow['payment_id'] : 'N/A'; // Display transaction key or N/A

                            echo '<tr>
                                    <td>' . $transactionRow['orderId'] . '</td>
                                    <td>' . $transactionKey . '</td>
                                    <td>' . $transactionRow['createdAt'] . '</td>
                                    <td>' . $paymentSource . '</td>
                                  </tr>';
                        }
                        if ($count == 0) {
                            echo '<tr><td colspan="4">You have no transaction history.</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php include 'partials/_signupModal.php'; ?>
<?php include 'partials/_loginModal.php'; ?>
<?php include 'partials/_forgot_pass.php'; ?>
<?php include 'partials/_deliveryboySignupModal.php'; ?>
<?php
include 'partials/_orderItemModal.php';
include 'partials/_orderStatusModal.php';
?>
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.4.1.min.js"
    integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
    integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
    crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"
    integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6"
    crossorigin="anonymous"></script>
<script src="https://unpkg.com/bootstrap-show-password@1.2.1/dist/bootstrap-show-password.min.js"></script>
</body>
</html>