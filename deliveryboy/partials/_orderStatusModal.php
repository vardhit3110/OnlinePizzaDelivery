<?php 
error_reporting(E_ERROR | E_PARSE);
$itemModalSql = "SELECT * FROM `orders`";
$itemModalResult = mysqli_query($conn, $itemModalSql);
while($itemModalRow = mysqli_fetch_assoc($itemModalResult)){
    $orderid = $itemModalRow['orderId'];
    $userid = $itemModalRow['userId'];
    $orderStatus = $itemModalRow['orderStatus'];
?>
<!-- Modal -->
<div class="modal fade" id="orderStatus<?php echo $orderid; ?>" tabindex="-1" role="dialog" aria-labelledby="orderStatus<?php echo $orderid; ?>" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background-color: rgb(111 202 203);">
        <h5 class="modal-title" id="orderStatus<?php echo $orderid; ?>">Order Status and Delivery Details</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="partials/_orderManage.php" method="post" style="border-bottom: 2px solid #dee2e6;">
            <div class="text-left my-2">    
                <b><label for="name">Order Status</label></b>
                <div class="row mx-2">
                <input class="form-control col-md-3" id="status" name="status" value="<?php echo $orderStatus; ?>" type="number" min="0" max="6" required>    
                <button type="button" class="btn btn-secondary ml-1" data-container="body" data-toggle="popover" title="User  Types" data-placement="bottom" data-html="true" data-content="0=Order Placed.<br> 1=Order Confirmed.<br> 2=Preparing your Order.<br> 3=Your order is on the way!<br> 4=Order Delivered.<br> 5=Order Denied.<br> 6=Order Cancelled.">
                    <i class="fas fa-info"></i>
                </button>
                </div>
            </div>
            <input type="hidden" id="orderId" name="orderId" value="<?php echo $orderid; ?>">
            <button type="submit" class="btn btn-success mb-2" name="updateStatus">Update</button>
        </form>
        
        <?php 
        // Fetch delivery details including delivery time from orders table
        $deliveryDetailSql = "SELECT d.id AS deliveryBoyId, d.first_name, d.last_name, d.phone, o.deliveryTime 
                              FROM `delivery_boys` d 
                              INNER JOIN `orders` o ON d.id = o.delivery_Boy_Id 
                              WHERE o.orderId = $orderid";

        $deliveryDetailResult = mysqli_query($conn, $deliveryDetailSql);
        $deliveryDetailRow = mysqli_fetch_assoc($deliveryDetailResult);

        if ($deliveryDetailRow) {
            $trackId = $deliveryDetailRow['deliveryBoyId'];
            $deliveryBoyName = $deliveryDetailRow['first_name'] . " " . $deliveryDetailRow['last_name'];
            $deliveryBoyPhoneNo = $deliveryDetailRow['phone'];
            $deliveryTime = $deliveryDetailRow['deliveryTime']; // Get delivery time from orders table
        } else {
            $trackId = NULL;
            $deliveryBoyName = NULL;
            $deliveryBoyPhoneNo = NULL;
            $deliveryTime = NULL;
        }    

        if ($orderStatus > 0 && $orderStatus < 3) { 
        ?>
        <form action="partials/_orderManage.php" method="post">
            <div class="text-left my-2">
                <b><label for="time">Estimate Time (minute)</label></b>
                <input class="form-control" id="time" name="time" value="<?php echo $deliveryTime; ?>" type="number" min="1" max="120" required>
            </div>
            <input type="hidden" id="trackId" name="trackId" value="<?php echo $trackId; ?>">
            <input type="hidden" id="orderId" name="orderId" value="<?php echo $orderid; ?>">
            <button type="submit" class="btn btn-success" name="updateDeliveryDetails">Update</button>
        </form>
        <?php } ?>

        <?php if ($orderStatus == 4) { // Check if order status is 4 ?>
        <form action="partials/_orderManage.php" method="post">
            <div class="text-left my-2">
                <b><label for="finalDeliveryTime">Final Delivery Time (minute)</label></b>
                <input class="form-control" id="finalDeliveryTime" name="finalDeliveryTime" value="<?php echo $deliveryTime; ?>" type="number" min="1" max="120" required>
            </div>
            <input type="hidden" id="orderId" name="orderId" value="<?php echo $orderid; ?>">
            <button type="submit" class="btn btn-success" name="updateFinalDeliveryTime">Update Final Delivery Time</button>
        </form>
        <?php } ?>

      </div>
    </div>
  </div>
</div>

<?php
}
?>

<style>
    .popover {
        top: -77px !important;
    }
</style>

<script>
    $(function () {
        $('[data-toggle="popover"]').popover();
    });
</script>