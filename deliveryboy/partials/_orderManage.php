<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '_dbconnect.php'; // Ensure database connection is included

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // ✅ Update Order Status
    if (isset($_POST['updateStatus'])) {
        $orderId = mysqli_real_escape_string($conn, $_POST['orderId']);
        $status = mysqli_real_escape_string($conn, $_POST['status']);

        $sql = "UPDATE orders SET orderStatus='$status' WHERE orderId='$orderId'";   
        $result = mysqli_query($conn, $sql);
        
        if ($result) {
            // Automatically update `status` if orderStatus changes
            if ($status == '4') {
                $updateStatusQuery = "UPDATE orders SET status='completed' WHERE orderId='$orderId'";
                mysqli_query($conn, $updateStatusQuery);
            } elseif ($status == '6') {
                $updateStatusQuery = "UPDATE orders SET status='cancelled' WHERE orderId='$orderId'";
                mysqli_query($conn, $updateStatusQuery);
            }

            echo "<script>alert('Order status updated successfully');
                window.location=document.referrer;</script>";
        } else {
            echo "<script>alert('Failed to update order status');
                window.location=document.referrer;</script>";
        }
    }

    // ✅ Update Delivery Details
    if (isset($_POST['updateDeliveryDetails'])) {
        $orderId = mysqli_real_escape_string($conn, $_POST['orderId']);
        $trackId = mysqli_real_escape_string($conn, $_POST['trackId']);
        $time = mysqli_real_escape_string($conn, $_POST['time']); 
    
        if ($trackId == NULL) {
            echo "<script>alert('Error: Invalid delivery boy ID');
                window.location=document.referrer;
                </script>";
            exit();
        }
    
        // ✅ Update `orders` table with delivery time in minutes
        $sql = "UPDATE orders SET deliveryTime='$time', delivery_boy_id='$trackId' WHERE orderId='$orderId'";   
        $result = mysqli_query($conn, $sql);
    
        if ($result) {
            echo "<script>alert('Delivery time updated successfully');
                window.location=document.referrer;
                </script>";
        } else {
            echo "<script>alert('Failed to update delivery time');
                window.location=document.referrer;
                </script>";
        }
    }

    // ✅ Update Final Delivery Time
    if (isset($_POST['updateFinalDeliveryTime'])) {
        $orderId = mysqli_real_escape_string($conn, $_POST['orderId']);
        $finalDeliveryTime = mysqli_real_escape_string($conn, $_POST['finalDeliveryTime']);

        // Update the final delivery time in the orders table
        $sql = "UPDATE orders SET finalDeliveryTime='$finalDeliveryTime' WHERE orderId='$orderId'";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            echo "<script>alert('Final delivery time updated successfully');
                window.location=document.referrer;
                </script>";
        } else {
            echo "<script>alert('Failed to update final delivery time');
                window.location=document.referrer;
                </script>";
        }
    }
}
?>