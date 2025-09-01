<style>
    .card {
        position: relative;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-orient: vertical;
        -webkit-box-direction: normal;
        -ms-flex-direction: column;
        flex-direction: column;
        min-width: 0;
        word-wrap: break-word;
        background-color: #fff;
        background-clip: border-box;
        border: 1px solid rgba(0, 0, 0, 0.1);
        border-radius: 0.10rem;
    }

    .card-header:first-child {
        border-radius: calc(0.37rem - 1px) calc(0.37rem - 1px) 0 0;
    }

    .card-header {
        padding: 0.75rem 1.25rem;
        margin-bottom: 0;
        background-color: #fff;
        border-bottom: 1px solid rgba(0, 0, 0, 0.1);
    }

    .track {
        position: relative;
        background-color: #ddd;
        height: 7px;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        margin-bottom: 60px;
        margin-top: 50px;
    }

    .track .step {
        -webkit-box-flex: 1;
        -ms-flex-positive: 1;
        flex-grow: 1;
        width: 25%;
        margin-top: -18px;
        text-align: center;
        position: relative;
    }

    .track .step.active:before {
        background: #FF5722;
    }

    .track .step::before {
        height: 7px;
        position: absolute;
        content: "";
        width: 100%;
        left: 0;
        top: 18px;
    }

    .track .step.active .icon {
        background: #ee5435;
        color: #fff;
    }

    .track .step.deactive:before {
        background: #030303;
    }

    .track .step.deactive .icon {
        background: #030303;
        color: #fff;
    }

    .track .icon {
        display: inline-block;
        width: 40px;
        height: 40px;
        line-height: 40px;
        position: relative;
        border-radius: 100%;
        background: #ddd;
    }

    .track .step.active .text {
        font-weight: 400;
        color: #000;
    }

    .track .text {
        display: block;
        margin-top: 7px;
    }

    .btn-warning {
        color: #ffffff;
        background-color: #ee5435;
        border-color: #ee5435;
        border-radius: 1px;
    }

    .btn-warning:hover {
        color: #ffffff;
        background-color: #ff2b00;
        border-color: #ff2b00;
        border-radius: 1px;
    }
</style>

<?php
include '_dbconnect.php'; // Ensure database connection is included

$statusmodalsql = "SELECT * FROM `orders` WHERE `userId`= ?";
$stmt = $conn->prepare($statusmodalsql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$statusmodalresult = $stmt->get_result();

while ($statusmodalrow = $statusmodalresult->fetch_assoc()) {
    $orderid = $statusmodalrow['orderId'];
    $status = $statusmodalrow['orderStatus'];
    $delivery_boy_id = $statusmodalrow['delivery_boy_id'];
    $deliveryTime = $statusmodalrow['deliveryTime'] ? $statusmodalrow['deliveryTime'] . " minutes" : "N/A";
    $finalDeliveryTime = $statusmodalrow['finalDeliveryTime'] ? $statusmodalrow['finalDeliveryTime'] . " minutes" : "N/A"; // Fetch final delivery time

    // Define order status text
    $statusText = [
        "Order Placed.", "Order Confirmed.", "Preparing your Order.",
        "Your order is on the way!", "Order Delivered.",
        "Order Denied.", "Order Cancelled."
    ];
    $tstatus = isset($statusText[$status]) ? $statusText[$status] : "Unknown Status";

    // Fetch delivery boy details if assigned
    $deliveryBoyName = "Not Assigned";
    $deliveryBoyPhone = "N/A";

    if (!empty($delivery_boy_id)) {
        $deliveryBoySql = "SELECT first_name, last_name, phone FROM `delivery_boys` WHERE id = ?";
        $stmtDelivery = $conn->prepare($deliveryBoySql);
        $stmtDelivery->bind_param("i", $delivery_boy_id);
        $stmtDelivery->execute();
        $deliveryResult = $stmtDelivery->get_result();

        if ($deliveryRow = $deliveryResult->fetch_assoc()) {
            $deliveryBoyName = $deliveryRow['first_name'] . " " . $deliveryRow['last_name'];
            $deliveryBoyPhone = $deliveryRow['phone'];
        }
    }
?>

    <div class="modal fade" id="orderStatus<?php echo $orderid; ?>" tabindex="-1" role="dialog"
        aria-labelledby="orderStatus<?php echo $orderid; ?>" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Order Status</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container">
                        <article class="card">
                            <div class="card-body">
                                <h6><strong>Order ID:</strong> #<?php echo $orderid; ?></h6>
                                <article class="card">
                                    <div class="card-body row">
                                    <div class="col"> <strong>Status:</strong> <br>
                                            <?php echo $tstatus; ?>
                                        </div>
                                    </div>
                                </article>

                                <div class="track">
                                <?php
                                if ($status == 0) {
                                    echo '<div class="step active"> <span class="icon"> <i class="fa fa-check"></i> </span> <span class="text">Order Placed</span> </div>
                                        <div class="step"> <span class="icon"> <i class="fa fa-times"></i> </span> <span class="text">Order Confirmed</span> </div>
                                        <div class="step"> <span class="icon"> <i class="fa fa-times"></i> </span> <span class="text"> Preparing Order</span> </div>
                                        <div class="step"> <span class="icon"> <i class="fa fa-truck"></i> </span> <span class="text"> On the way </span> </div>
                                        <div class="step"> <span class="icon"> <i class="fa fa-box"></i> </span> <span class="text">Order Delivered</span> </div>';
                                } elseif ($status == 1) {
                                    echo '<div class="step active"> <span class="icon"> <i class="fa fa-check"></i> </span> <span class="text">Order Placed</span> </div>
                                      <div class="step active"> <span class="icon"> <i class="fa fa-check"></i> </span> <span class="text">Order Confirmed</span> </div>
                                      <div class="step"> <span class="icon"> <i class="fa fa-times"></i> </span> <span class="text"> Preparing Order</span> </div>
                                      <div class="step"> <span class="icon"> <i class="fa fa-truck"></i> </span> <span class="text"> On the way </span> </div>
                                      <div class="step"> <span class="icon"> <i class="fa fa-box"></i> </span> <span class="text">Order Delivered</span> </div>';
                                } elseif ($status == 2) {
                                    echo '<div class="step active"> <span class="icon"> <i class="fa fa-check"></i> </span> <span class="text">Order Placed</span> </div>
                                      <div class="step active"> <span class="icon"> <i class="fa fa-check"></i> </span> <span class="text">Order Confirmed</span> </div>
                                      <div class="step active"> <span class="icon"> <i class="fa fa-check"></i> </span> <span class="text"> Preparing Order</span> </div>
                                      <div class="step"> <span class="icon"> <i class="fa fa-truck"></i> </span> <span class="text"> On the way </span> </div>
                                      <div class="step"> <span class="icon"> <i class="fa fa-box"></i> </span> <span class="text">Order Delivered</span> </div>';
                                } elseif ($status == 3) {
                                    echo '<div class="step active"> <span class="icon"> <i class="fa fa-check"></i> </span> <span class="text">Order Placed</span> </div>
                                      <div class="step active"> <span class="icon"> <i class="fa fa-check"></i> </span> <span class="text">Order Confirmed</span> </div>
                                      <div class="step active"> <span class="icon"> <i class="fa fa-check"></i> </span> <span class="text"> Preparing Order</span> </div>
                                      <div class="step active"> <span class="icon"> <i class="fa fa-truck"></i> </span> <span class="text"> On the way </span> </div>
                                      <div class="step"> <span class="icon"> <i class="fa fa-box"></i> </span> <span class="text">Order Delivered</span> </div>';
                                } elseif ($status == 4) {
                                    echo '<div class="step active"> <span class="icon"> <i class="fa fa-check"></i> </span> <span class="text">Order Placed</span> </div>
                                      <div class="step active"> <span class="icon"> <i class="fa fa-check"></i> </span> <span class="text">Order Confirmed</span> </div>
                                      <div class="step active"> <span class="icon"> <i class="fa fa-check"></i> </span> <span class="text"> Preparing Order</span> </div>
                                      <div class="step active"> <span class="icon"> <i class="fa fa-truck"></i> </span> <span class="text"> On the way </span> </div>
                                      <div class="step active"> <span class="icon"> <i class="fa fa-box"></i> </span> <span class="text">Order Delivered</span> </div>';
                                } elseif ($status == 5) {
                                    echo '<div class="step active"> <span class="icon"> <i class="fa fa-check"></i> </span> <span class="text">Order Placed</span> </div>
                                      <div class="step deactive"> <span class="icon"> <i class="fa fa-times"></i> </span> <span class="text">Order Denied.</span> </div>';
                                } else {
                                    echo '<div class="step deactive"> <span class="icon"> <i class="fa fa-times"></i> </span> <span class="text">Order Cancelled.</span> </div>';
                                }
                                ?>
                            </div>

                                <div class="row mt-4">
                                <div class="col-md-6">
                                    <h6><strong>Estimated Delivery Time:</strong></h6>
                                    <p><?php echo $deliveryTime; ?></p>
                                    <h6><strong>Final Delivery Time:</strong></h6>
                                        <p><?php echo $finalDeliveryTime; ?></p> <!-- Display final delivery time -->
                                </div>
                                <div class="col-md-6">
                                    <h6><strong>Delivery Person:</strong></h6>
                                    <p><?php echo $deliveryBoyName; ?></p>
                                    <h6><strong>Contact:</strong></h6>
                                    <p><?php echo $deliveryBoyPhone; ?></p>
                                </div>
                            </div>
                                
                                <div class="row mt-4">
                                    <div class="col-md-12 text-center">
                                        <a href="contact.php" class="btn btn-primary btn-sm">Need Help?</a>
                                    </div>
                                </div>
                            </div>
                        </article>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php
} // End while loop
$stmt->close();
?>