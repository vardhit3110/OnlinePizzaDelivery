<div class="container" style="margin-top:98px;background: aliceblue;">
    <div class="table-wrapper">
        <div class="table-title" style="border-radius: 14px;">
            <div class="row">
                <div class="col-sm-4">
                    <h2>Order <b>Details</b></h2>
                </div>
                <div class="col-sm-8">
                    <a href="" class="btn btn-primary"><i class="material-icons">&#xE863;</i> <span>Refresh
                            List</span></a>
                    <a href="#" onclick="window.print()" class="btn btn-info"><i class="material-icons">&#xE24D;</i>
                        <span>Print</span></a>
                </div>
            </div>
        </div>

        <!-- Current Orders Section -->
        <table class="table table-striped table-hover text-center position-center">
            <thead style="background-color: rgb(111 202 203);">
                <tr>
                    <th>Order Id</th>
                    <th>User Id</th>
                    <th>Address</th>
                    <th>Phone No</th>
                    <th>Amount</th>
                    <th>Payment Mode</th>
                    <th>Order Date</th>
                    <th>Status</th>
                    <th>Items</th>
                    <th>Delivery Boy</th>
                </tr>
            </thead>
            <tbody>
                <?php
                include '_dbconnect.php'; // Ensure the database connection
                
                // Fetch all delivery boys
                $delivery_boys = [];
                $resultBoys = mysqli_query($conn, "SELECT * FROM delivery_boys WHERE status='approved'");
                while ($boy = mysqli_fetch_assoc($resultBoys)) {
                    $delivery_boys[] = $boy;
                }

                // Fetch current orders (excluding completed and cancelled)
                $sql = "SELECT * FROM orders WHERE orderStatus NOT IN ('4', '6') ORDER BY orderId DESC";
                $result = mysqli_query($conn, $sql);
                while ($row = mysqli_fetch_assoc($result)) {
                    $orderId = $row['orderId'];
                    $Id = $row['userId'];
                    $address = $row['address'];
                    $phoneNo = $row['phoneNo'];
                    $amount = $row['amount'];
                    $orderDate = $row['createdAt'];
                    $paymentMode = $row['paymentMode'] == 0 ? "Cash on Delivery" : "Online";
                    $orderStatus = $row['orderStatus'];
                    $deliveryBoyId = $row['delivery_boy_id'];
                    $statusText = [
                        "0" => "pending",
                        "1" => "completed",
                        "2" => "pending",
                        "3" => "pending",
                        "4" => "completed",
                        "5" => "cancelled",
                        "6" => "cancelled"
                    ];

                    // Determine badge color based on status
                    $badgeClass = '';
                    if ($orderStatus == 4 || $orderStatus == 1) {
                        $badgeClass = 'badge-success'; // Green for completed
                    } elseif ($orderStatus == 5 || $orderStatus == 6) {
                        $badgeClass = 'badge-danger'; // Red for cancelled
                    } else {
                        $badgeClass = 'badge-warning'; // Yellow for pending
                    }

                    echo '<tr>
                                <td>' . $orderId . '</td>
                                <td>' . $Id . '</td>
                                <td data-toggle="tooltip" title="' . $address . '">' . $address . '</td>
                                <td>' . $phoneNo . '</td>
                                <td>' . $amount . '</td>
                                <td>' . $paymentMode . '</td>
                                <td>' . $orderDate . '</td>
                                <td><span class="badge ' . $badgeClass . '">' . $statusText[$orderStatus] . '</span></td>
                                <td><a href="#" data-toggle="modal" data-target="#orderItem' . $orderId . '" class="view"><i class="material-icons">&#xE5C8;</i></a></td>
                                <td>
                                    <form action="partials/assign_delivery.php" method="POST">
                                        <input type="hidden" name="order_id" value="' . $orderId . '">
                                        <select name="delivery_boy_id" class="form-control" required>
                                            <option value="">Select</option>';

                    foreach ($delivery_boys as $boy) {
                        $selected = ($boy['id'] == $deliveryBoyId) ? "selected" : "";
                        echo '<option value="' . $boy['id'] . '" ' . $selected . '>' . $boy['first_name'] . ' ' . $boy['last_name'] . '</option>';
                    }

                    echo '</select>
                                        <button type="submit" class="btn btn-sm btn-success">Assign</button>
                                    </form>
                                </td>
                                </tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
    <!-- Previous Orders Section -->
    <div class="table-title" style="border-radius: 14px; margin-top: 20px;">
        <h2>Previous <b>Orders</b></h2>
    </div>
    <table class="table table-striped table-hover text-center position-center">
        <thead style="background-color: rgb(111 202 203);">
            <tr>
                <th>Order Id</th>
                <th>User Id</th>
                <th>Address</th>
                <th>Phone No</th>
                <th>Amount</th>
                <th>Payment Mode</th>
                <th>Order Date</th>
                <th>Status</th>
                <th>Items</th>
                <th>Delivery Boy</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Fetch previous orders (statuses 4 and 6)
            $previousSql = "SELECT * FROM orders WHERE orderStatus IN ('4', '6') ORDER BY orderId DESC";
            $previousResult = mysqli_query($conn, $previousSql);
            while ($row = mysqli_fetch_assoc($previousResult)) {
                $orderId = $row['orderId'];
                $Id = $row['userId'];
                $address = $row['address'];
                $phoneNo = $row['phoneNo'];
                $amount = $row['amount'];
                $orderDate = $row['createdAt'];
                $paymentMode = $row['paymentMode'] == 0 ? "Cash on Delivery" : "Online";
                $orderStatus = $row['orderStatus'];
                $deliveryBoyId = $row['delivery_boy_id'];
                $statusText = [
                    "0" => "pending",
                    "1" => "completed",
                    "2" => "pending",
                    "3" => "pending",
                    "4" => "completed",
                    "5" => "cancelled",
                    "6" => "cancelled"
                ];

                // Determine badge color based on status
                $badgeClass = '';
                if ($orderStatus == 4 || $orderStatus == 1) {
                    $badgeClass = 'badge-success'; // Green for completed
                } elseif ($orderStatus == 5 || $orderStatus == 6) {
                    $badgeClass = 'badge-danger'; // Red for cancelled
                } else {
                    $badgeClass = 'badge-warning'; // Yellow for pending
                }

                // Fetch the delivery boy's name
                $deliveryBoyName = '';
                if ($deliveryBoyId) {
                    $deliveryBoySql = "SELECT first_name, last_name FROM delivery_boys WHERE id = '$deliveryBoyId'";
                    $deliveryBoyResult = mysqli_query($conn, $deliveryBoySql);
                    if ($deliveryBoyRow = mysqli_fetch_assoc($deliveryBoyResult)) {
                        $deliveryBoyName = $deliveryBoyRow['first_name'] . ' ' . $deliveryBoyRow['last_name'];
                    }
                }

                echo '<tr>
                    <td>' . $orderId . '</td>
                    <td>' . $Id . '</td>
                    <td data-toggle="tooltip" title="' . $address . '">' . $address . '</td>
                    <td>' . $phoneNo . '</td>
                    <td>' . $amount . '</td>
                    <td>' . $paymentMode . '</td>
                    <td>' . $orderDate . '</td>
                    <td><span class="badge ' . $badgeClass . '">' . $statusText[$orderStatus] . '</span></td>
                    <td><a href="#" data-toggle="modal" data-target="#orderItem' . $orderId . '" class="view"><i class="material-icons">&#xE5C8;</i></a></td>
                    <td>' . $deliveryBoyName . '</td>
                </tr>';
            }
            ?>
        </tbody>
    </table>
</div>

<?php
include 'partials/assign_delivery.php';
include 'partials/_orderItemModal.php';
?>

<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
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
        gap: 8px;
        /* Space between select and button */
    }

    /* Style for the select dropdown */
    td form select {
        width: auto;
        min-width: 100px;
        padding: 6px 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 14px;
    }

    /* Style for the submit button */
    td form button {
        padding: 6px 12px;
        font-size: 14px;
        background-color: #28a745;
        /* Bootstrap success color */
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

    /* Badge colors */
    .badge-success {
        background-color: #28a745;
        color: white;
    }

    .badge-danger {
        background-color: #dc3545;
        color: white;
    }

    .badge-warning {
        background-color: #ffc107;
        color: #212529;
    }

    .badge-info {
        background-color: #17a2b8;
        color: white;
    }
</style>
<script>
    $(document).ready(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>