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

    if (isset($_POST['addToCart'])) {
        $itemId = intval($_POST["itemId"]);
        $size = isset($_POST["size"]) ? mysqli_real_escape_string($conn, $_POST["size"]) : 'M'; // Default size M

        // Check if item already exists in the cart
        $existSql = "SELECT * FROM `viewcart` WHERE itemId = ? AND userId = ? AND size = ?";
        $stmt = $conn->prepare($existSql);
        $stmt->bind_param("iis", $itemId, $userId, $size);
        $stmt->execute();
        $result = $stmt->get_result();
        $numExistRows = $result->num_rows;

        if ($numExistRows > 0) {
            echo "<script>alert('Item Already Added.'); window.history.back();</script>";
        } else {
            $sql = "INSERT INTO `viewcart` (`itemId`, `itemQuantity`, `userId`, `addedDate`, `size`) VALUES (?, 1, ?, current_timestamp(), ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iis", $itemId, $userId, $size);
            $stmt->execute();
            echo "<script>window.history.back();</script>";
        }
    }

    if (isset($_POST['removeItem'])) {
        $itemId = intval($_POST["itemId"]);
        $sql = "DELETE FROM `viewcart` WHERE itemId = ? AND userId = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $itemId, $userId);
        $stmt->execute();
        echo "<script>alert('Item Removed'); window.history.back();</script>";
    }

    if (isset($_POST['removeAllItem'])) {
        $sql = "DELETE FROM `viewcart` WHERE userId = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        echo "<script>alert('All Items Removed'); window.history.back();</script>";
    }

    if (isset($_POST['checkout'])) {
        $size = isset($_POST["size"]) ? mysqli_real_escape_string($conn, $_POST["size"]) : 'M';
        $address1 = mysqli_real_escape_string($conn, $_POST["address"]);
        $address2 = mysqli_real_escape_string($conn, $_POST["address1"]);
        $phone = intval($_POST["phone"]);
        $zipcode = intval($_POST["zipcode"]);
        $password = $_POST["password"];
        $address = $address1 . ", " . $address2;
    
        // Fetch total price from the cart with size-based pricing
        $cartTotalQuery = "SELECT SUM(i.itemPrice * v.itemQuantity * 
                                CASE 
                                    WHEN v.size = 'M' THEN 2 
                                    WHEN v.size = 'L' THEN 3 
                                    ELSE 1 
                                END) AS totalPrice 
                            FROM viewcart v 
                            JOIN item i ON v.itemId = i.itemId 
                            WHERE v.userId = ?;";
                                
        $stmt = $conn->prepare($cartTotalQuery);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $cartResult = $stmt->get_result();
        $cartRow = $cartResult->fetch_assoc();
        $totalPrice = $cartRow['totalPrice'];
    
        // Calculate GST and Final Total
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
        $passRow = $passResult->fetch_assoc();
    
        if (password_verify($password, $passRow['password'])) {
            // Insert into `orders` with final total
            $sql = "INSERT INTO `orders` (`userId`, `address`, `zipCode`, `phoneNo`, `amount`, `paymentMode`, `orderStatus`, `size`, `createdAt`, `status`) 
            VALUES (?, ?, ?, ?, ?, '0', '0', ?, current_timestamp(), 'pending')";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("isiiis", $userId, $address, $zipcode, $phone, $finalTotal, $size);
            if ($stmt->execute()) {
                $orderId = $conn->insert_id;
                // Insert into `orderitems`
                $addSql = "SELECT * FROM `viewcart` WHERE userId = ?";
                $stmt = $conn->prepare($addSql);
                $stmt->bind_param("i", $userId);
                $stmt->execute();
                $addResult = $stmt->get_result();
                
                while ($addrow = $addResult->fetch_assoc()) {
                    $itemId = $addrow['itemId'];
                    $itemQuantity = $addrow['itemQuantity'];
                    $size = $addrow['size'];
                
                    $itemSql = "INSERT INTO `orderitems` (`orderId`, `itemId`, `itemQuantity`, `size`) VALUES (?, ?, ?, ?)";
                    $stmt = $conn->prepare($itemSql);
                    $stmt->bind_param("iiis", $orderId, $itemId, $itemQuantity, $size);
                
                    if (!$stmt->execute()) {
                        die("Error inserting into orderitems: " . $stmt->error);
                    }
                }
    
                // Clear the cart after checkout
                $deletesql = "DELETE FROM `viewcart` WHERE userId = ?";
                $stmt = $conn->prepare($deletesql);
                $stmt->bind_param("i", $userId);
                $stmt->execute();
    
                echo "<script>alert('Thanks for ordering! Your order ID is $orderId.');
                        window.location.href='http://localhost/OnlinePizzaDelivery/index.php';</script>";
                exit();
            }
        } else {
            echo "<script>alert('Incorrect Password! Please try again.'); window.history.back();</script>";
            exit();
        }
    }
    // AJAX Request for Updating Cart Quantity
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        $itemId = intval($_POST['itemId']);
        $qty = intval($_POST['quantity']);

        // Ensure qty does not exceed 7
        $qty = min($qty, 7);

        $updatesql = "UPDATE `viewcart` SET `itemQuantity` = ? WHERE `itemId` = ? AND `userId` = ?";
        $stmt = $conn->prepare($updatesql);
        $stmt->bind_param("iii", $qty, $itemId, $userId);
        $stmt->execute();
    }
}
?>