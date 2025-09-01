<?php
include '_dbconnect.php'; // Ensure correct database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $order_id = $_POST['order_id'];
    $delivery_boy_id = $_POST['delivery_boy_id'];

    $sql = "UPDATE orders SET delivery_boy_id = ? WHERE orderId = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $delivery_boy_id, $order_id);

    if (mysqli_stmt_execute($stmt)) {
        echo "<script>alert('Delivery Boy Assigned Successfully'); window.location.href='../index.php?page=orderManage';</script>";
    } else {
        echo "<script>alert('Failed to Assign'); window.location.href='../index.php?page=orderManage';</script>";
    }
}
?>