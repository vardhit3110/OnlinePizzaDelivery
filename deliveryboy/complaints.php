<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include 'partials/_dbconnect.php'; // Ensure database connection is included

// Ensure the delivery boy is logged in
if (!isset($_SESSION['deliveryboy_id'])) {
    die("Unauthorized access");
}

$deliveryboyId = $_SESSION['deliveryboy_id'];

// Fetch complaints for orders assigned to this delivery boy
$sql = "SELECT r.reviewId, u.username, r.orderId, 
               COALESCE(NULLIF(r.complain, ''), 'No complaint') AS complain, 
               r.reviewDate 
        FROM reviews r
        JOIN orders o ON r.orderId = o.orderId
        JOIN users u ON r.userId = u.id
        WHERE o.delivery_boy_id = ?
        ORDER BY r.reviewDate DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $deliveryboyId);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Order Complaints</title>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.min.js"></script>
</head>
<body>

<div class="container-fluid" style="margin-top: 20px; padding-top: 20px;">    
    <div class="row">
        <div class="card col-lg-12">
            <div class="card-body">
                <h2 class="text-center mb-4">Your Order Complaints</h2>

                <table class="table table-striped table-bordered text-center">
                    <thead style="background-color: rgb(111 202 203);">
                        <tr>
                            <th>Review ID</th>
                            <th>Customer</th>
                            <th>Order ID</th>
                            <th>Complaint</th>
                            <th>Review Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result->num_rows > 0): ?>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['reviewId']); ?></td>
                                    <td><?= htmlspecialchars($row['username']); ?></td>
                                    <td><?= htmlspecialchars($row['orderId']); ?></td>
                                    <td><?= htmlspecialchars($row['complain']); ?></td>
                                    <td><?= date("d-m-Y H:i:s", strtotime($row['reviewDate'])); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5">No complaints found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

</body>
</html>
<?php $conn->close(); ?>