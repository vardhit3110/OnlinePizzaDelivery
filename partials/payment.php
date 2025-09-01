<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '_dbconnect.php';
session_start();

if (!isset($_GET['order_id']) || !isset($_GET['amount'])) {
    echo "<script>alert('Invalid payment request!'); window.location.href='index.php';</script>";
    exit();
}

$order_id = $_GET['order_id'];
$finalTotal = filter_var($_GET['amount'], FILTER_VALIDATE_FLOAT);
if ($finalTotal === false || $finalTotal <= 0) {
    echo "<script>alert('Invalid amount!'); window.location.href='index.php';</script>";
    exit();
}

?>
<?php
$apikey = 'rzp_test_PGGSxwk35pyoHg';
// old api key :-rzp_test_cXtUFZJ1mIbI9S

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Razorpay Payment</title>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.0.js"></script>
    <link rel="icon" href="/OnlinePizzaDelivery/img/logo.jpg" type="image/x-icon">
</head>

<body>
    <button onclick="startPayment()" class="razorpay-button">Pay Now</button>
    <script>
        function startPayment() {
            var options = {
                "key": "<?php echo $apikey; ?>",
                "amount": <?php echo $finalTotal * 100; ?>,
                "currency": "INR",
                "name": "Pizza World",
                "description": "Online Food Delivery",
                "image": "http://localhost/OnlinePizzaDelivery/pigga/assets/imgs/logo.svg",
                "handler": function (response) {
                    //  Redirect to success page with payment ID
                    window.location.href = "payment_success.php?order_id=<?php echo $order_id; ?>&payment_id=" + response.razorpay_payment_id;
                },
                "prefill": {
                    "name": "<?php echo isset($_SESSION['username']) ? $_SESSION['username'] : ''; ?>",
                    "email": "<?php echo isset($_SESSION['useremail']) ? $_SESSION['useremail'] : ''; ?>",
                    "contact": "<?php echo isset($_SESSION['userphone']) ? $_SESSION['userphone'] : ''; ?>"
                },
                "theme": {
                    "color": "#3399cc"
                }
            };

            var rzp1 = new Razorpay(options);
            rzp1.open();
        }
    </script>
    <style>
        .razorpay-button {
            display: none;
        }
    </style>
    <script>
        $(document).ready(function () {
            $('.razorpay-button').click();
        })
    </script>
</body>

</html>