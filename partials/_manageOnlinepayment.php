<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '_dbconnect.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_SESSION['userId'])) {
        echo "<script>alert('Please log in first!'); window.location.href='login.php';</script>";
        exit();
    }

    $userId = $_SESSION['userId'];

    // Get form inputs & sanitize
    $address = mysqli_real_escape_string($conn, trim($_POST['address']));
    $zipcode = mysqli_real_escape_string($conn, trim($_POST['zipcode']));
    $phone = mysqli_real_escape_string($conn, trim($_POST['phone']));
    $password = mysqli_real_escape_string($conn, trim($_POST['password']));
    $amount = isset($_POST['amount']) ? mysqli_real_escape_string($conn, trim($_POST['amount'])) : 0;

    // Validate inputs
    if (empty($address) || empty($zipcode) || empty($phone) || empty($password) || empty($amount)) {
        echo "<script>alert('All required fields must be filled.'); window.history.back();</script>";
        exit();
    }

    if (!preg_match('/^[0-9]{6}$/', $zipcode)) {
        echo "<script>alert('Invalid Zip Code.'); window.history.back();</script>";
        exit();
    }

    if (!preg_match('/^[0-9]{10}$/', $phone)) {
        echo "<script>alert('Invalid Phone Number.'); window.history.back();</script>";
        exit();
    }

    if (!is_numeric($amount) || $amount <= 0) {
        echo "<script>alert('Invalid amount.'); window.history.back();</script>";
        exit();
    }

    // Fetch total cart amount with size-based pricing
    $cartTotalQuery = "SELECT SUM(i.itemPrice * v.itemQuantity * 
                            CASE 
                                WHEN v.size = 'M' THEN 2 
                                WHEN v.size = 'L' THEN 3 
                                ELSE 1 
                            END) AS totalPrice 
                       FROM viewcart v 
                       JOIN item i ON v.itemId = i.itemId 
                       WHERE v.userId = ?";

    $stmt = $conn->prepare($cartTotalQuery);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $cartResult = $stmt->get_result();
    $cartRow = $cartResult->fetch_assoc();
    $totalPrice = $cartRow['totalPrice'] ?? 0; // Default to 0 if no items

    if ($totalPrice <= 0) {
        echo "<script>alert('Your cart is empty!'); window.location.href='cart.php';</script>";
        exit();
    }

    // Calculate final amount
    $shippingCharge = 20;
    $gstPercentage = 5;
    $gstAmount = ($totalPrice * $gstPercentage) / 100;
    $finalTotal = $totalPrice + $gstAmount + $shippingCharge;

    // Verify user password
    $passSql = "SELECT password FROM users WHERE id = ?";
    $stmt = $conn->prepare($passSql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $passResult = $stmt->get_result();

    if ($passResult->num_rows === 1) {
        $passRow = $passResult->fetch_assoc();

        if (password_verify($password, $passRow['password'])) {
            // Begin transaction
            $conn->begin_transaction();

            // Insert order into `orders`
            $sql = "INSERT INTO `orders` (`userId`, `address`, `zipCode`, `phoneNo`, `amount`, `paymentMode`, `orderStatus`,  `createdAt`, `status`) 
                    VALUES (?, ?, ?, ?, ?, '1', '0', current_timestamp(), 'pending')";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("issii", $userId, $address, $zipcode, $phone, $finalTotal);

            if ($stmt->execute()) {
                $orderId = $conn->insert_id;

                // Transfer cart items to `orderitems`
                $addSql = "INSERT INTO `orderitems` (`orderId`, `itemId`, `itemQuantity`, `size`)
                           SELECT ?, itemId, itemQuantity, size FROM `viewcart` WHERE userId = ?";

                $stmt = $conn->prepare($addSql);
                $stmt->bind_param("ii", $orderId, $userId);
                $stmt->execute();

                // Clear the cart
                $deletesql = "DELETE FROM `viewcart` WHERE userId = ?";
                $stmt = $conn->prepare($deletesql);
                $stmt->bind_param("i", $userId);
                $stmt->execute();

                // Commit transaction
                $conn->commit();

                // ✅ Redirect to payment page with order details
                header("Location:payment.php?order_id=$orderId&amount=$finalTotal");
                exit();
            } else {
                echo "<script>alert('Error placing order. Please try again.'); window.history.back();</script>";
                exit();
            }
        } else {
            echo "<script>alert('Incorrect Password! Please try again.'); window.history.back();</script>";
            exit();
        }
    } else {
        echo "<script>alert('User  not found! Please try again.'); window.history.back();</script>";
        exit();
    }
}
?>