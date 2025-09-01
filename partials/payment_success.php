<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '_dbconnect.php';
session_start();

if (!isset($_GET['order_id']) || !isset($_GET['payment_id'])) {
    echo "<script>alert('Invalid access!'); window.location.href='index.php';</script>";
    exit();
}

$order_id = (int) $_GET['order_id'];  
$payment_id = $_GET['payment_id'];
$userId = $_SESSION['userId']; 

$conn->begin_transaction(); // Start transaction

try {
    // ✅ Check if order exists
    $check_order_sql = "SELECT * FROM orders WHERE orderId = ?";
    $check_stmt = $conn->prepare($check_order_sql);
    $check_stmt->bind_param("i", $order_id);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows === 0) {
        throw new Exception('Order not found! Please contact support.');
    }

    // ✅ Update order status
    $sql = "UPDATE orders SET orderStatus = '1', payment_id = ? WHERE orderId = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $payment_id, $order_id);
    if (!$stmt->execute()) {
        throw new Exception('Failed to update order status.');
    }

    // ✅ Move items from `viewcart` to `orderitems`
    $cart_items_sql = "SELECT itemId, itemQuantity, size FROM viewcart WHERE userId = ?";
    $stmt = $conn->prepare($cart_items_sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $cart_items_result = $stmt->get_result();

    $insert_orderitems_sql = "INSERT INTO orderitems (orderId, itemId, itemQuantity, size) VALUES (?, ?, ?, ?)";
    $stmt_insert = $conn->prepare($insert_orderitems_sql);

    while ($row = $cart_items_result->fetch_assoc()) {
        $pizzaId = $row['pizzaId'];
        $itemQuantity = $row['itemQuantity'];
        $size = trim($row['size']); // Trim any whitespace

        if (!$stmt_insert) {
            throw new Exception('Insert statement preparation failed: ' . $conn->error);
        }

        $stmt_insert->bind_param("iiis", $order_id, $pizzaId, $itemQuantity, $size);
        
        if (!$stmt_insert->execute()) {
            throw new Exception('Failed to insert order items: ' . $stmt_insert->error);
        }
    }

    // ✅ Clear `viewcart`
    $delete_cart_sql = "DELETE FROM viewcart WHERE userId = ?";
    $stmt = $conn->prepare($delete_cart_sql);
    $stmt->bind_param("i", $userId);
    if (!$stmt->execute()) {
        throw new Exception('Failed to clear cart.');
    }

    // ✅ Commit transaction
    $conn->commit();

    echo "<script>
        alert('Payment successful!\\nOrder ID: $order_id\\nPayment ID: $payment_id');
        window.location.href='http://localhost/OnlinePizzaDelivery/index.php';
    </script>";

} catch (Exception $e) {
    $conn->rollback(); // Rollback in case of failure
    echo "<script>
        alert('Error: " . $e->getMessage() . "');
        window.location.href='http://localhost/OnlinePizzaDelivery/index.php';
    </script>";
}

// Close statements and connection
$stmt->close();
$conn->close();
?>
