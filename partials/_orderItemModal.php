<?php 
$itemModalSql = "SELECT * FROM `orders` WHERE `userId`= ?";
$stmt = mysqli_prepare($conn, $itemModalSql);
mysqli_stmt_bind_param($stmt, "i", $userId);
mysqli_stmt_execute($stmt);
$itemModalResult = mysqli_stmt_get_result($stmt);

while($itemModalRow = mysqli_fetch_assoc($itemModalResult)){
    $orderid = $itemModalRow['orderId'];
?>

<!-- Modal -->
<div class="modal fade" id="orderItem<?php echo $orderid; ?>" tabindex="-1" role="dialog" aria-labelledby="orderItem<?php echo $orderid; ?>" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Order Items</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            
                <div class="container">
                    <div class="row">
                        <!-- Shopping cart table -->
                        <div class="table-responsive">
                            <table class="table text">
                                <thead>
                                    <tr>
                                        <th scope="col" class="border-0 bg-light">
                                            <div class="px-3">Item</div>
                                        </th>
                                        <th scope="col" class="border-0 bg-light">
                                            <div class="text-center">Quantity</div>
                                        </th>
                                        <th scope="col" class="border-0 bg-light">
                                            <div class="text-center">Size</div>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        // Get order items
                                        $mysql = "SELECT * FROM `orderitems` WHERE orderId = ?";
                                        $stmt2 = mysqli_prepare($conn, $mysql);
                                        mysqli_stmt_bind_param($stmt2, "i", $orderid);
                                        mysqli_stmt_execute($stmt2);
                                        $myresult = mysqli_stmt_get_result($stmt2);

                                        while($myrow = mysqli_fetch_assoc($myresult)){
                                            $itemId = $myrow['itemId'];
                                            $itemQuantity = $myrow['itemQuantity'];
                                            $size = $myrow['size']; // Added size column

                                            // Get item details
                                            $itemsql = "SELECT * FROM `item` WHERE itemId = ?";
                                            $stmt3 = mysqli_prepare($conn, $itemsql);
                                            mysqli_stmt_bind_param($stmt3, "i", $itemId);
                                            mysqli_stmt_execute($stmt3);
                                            $itemresult = mysqli_stmt_get_result($stmt3);
                                            $itemrow = mysqli_fetch_assoc($itemresult);

                                            $itemName = $itemrow['itemName'];
                                            $itemPrice = $itemrow['itemPrice'];

                                            // Modify price according to size
                                            if ($size == "M") {
                                                $itemPrice *= 2; // Double the price for Medium
                                            } elseif ($size == "L") {
                                                $itemPrice *= 3; // Triple the price for Large
                                            }

                                            echo '<tr>
                                                    <th scope="row">
                                                        <div class="p-2">
                                                            <img src="img/pizza-'.$itemId. '.jpg" alt="" width="70" class="img-fluid rounded shadow-sm">
                                                            <div class="ml-3 d-inline-block align-middle">
                                                                <h5 class="mb-0"> <a href="#" class="text-dark d-inline-block align-middle">'.$itemName. '</a></h5>
                                                                <span class="text-muted font-weight-normal font-italic d-block">Rs. ' .$itemPrice. '/-</span>
                                                            </div>
                                                        </div>
                                                    </th>
                                                    <td class="align-middle text-center"><strong>' .$itemQuantity. '</strong></td>
                                                    <td class="align-middle text-center"><strong>' .$size. '</strong></td>
                                                </tr>';
                                        }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <!-- End -->
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<?php
    }
?>