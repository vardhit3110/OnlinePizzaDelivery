<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'partials/_dbconnect.php';
date_default_timezone_set('Asia/Kolkata'); // Set your timezone

if (isset($_GET['orderId'])) {
    $orderId = $_GET['orderId'];

    // Fetch the order details including payment mode
    $sql = "SELECT createdAt, orderStatus, paymentMode FROM orders WHERE orderId = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $orderId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (!$result) {
        die("Database query failed: " . mysqli_error($conn));
    }

    $row = mysqli_fetch_assoc($result);
    
    if ($row) {
        $orderTime = strtotime($row['createdAt']); // Convert order created time to timestamp
        $currentTime = time();

        // Check if the order can be canceled (within 10 minutes)
        if (($currentTime - $orderTime) <= 600) { 
            if ($row['orderStatus'] == '4') { // If order is already delivered
                echo "<script>alert('This order has already been delivered and cannot be canceled.'); window.location.href='viewOrder.php';</script>";
            } else {
                // Update orderStatus to '6' (Cancelled) and status to 'cancelled'
                $updateSql = "UPDATE orders SET orderStatus = '6', status = 'cancelled' WHERE orderId = ?";
                $updateStmt = mysqli_prepare($conn, $updateSql);
                mysqli_stmt_bind_param($updateStmt, "i", $orderId);
                mysqli_stmt_execute($updateStmt);

                if (mysqli_stmt_affected_rows($updateStmt) > 0) {
                    // Check if the payment mode was online (paymentMode == '1')
                    if ($row['paymentMode'] == '1') {
                        echo "<script>alert('Order canceled successfully. Your amount will be refunded soon. It will be deleted automatically in 2 minutes.'); window.location.href='viewOrder.php';</script>";
                    } else {
                        echo "<script>alert('Order canceled successfully. It will be deleted automatically in 2 minutes.'); window.location.href='viewOrder.php';</script>";
                    }

                    // Insert into a separate table or log for deletion processing
                    $scheduleDeleteSql = "INSERT INTO order_deletion_queue (orderId, delete_after) VALUES (?, DATE_ADD(NOW(), INTERVAL 2 MINUTE))";
                    $deleteStmt = mysqli_prepare($conn, $scheduleDeleteSql);
                    mysqli_stmt_bind_param($deleteStmt, "i", $orderId);
                    mysqli_stmt_execute($deleteStmt);
                } else {
                    echo "<script>alert('Error canceling order. Please try again.'); window.location.href='viewOrder.php';</script>";
                }
            }
        } else {
            echo "<script>alert('You can only cancel your order within 10 minutes.'); window.location.href='viewOrder.php';</script>";
        }
    } else {
        echo "<script>alert('Order not found.'); window.location.href='viewOrder.php';</script>";
    }
} else {
    echo "<script>alert('Invalid request.'); window.location.href='viewOrder.php';</script>";
}
?>
