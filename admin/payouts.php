<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include 'partials/_dbconnect.php'; // Ensure the database connection is included
// Pagination logic
$limit = 5; // Number of reviews per page
$current_page = isset($_GET['p']) ? (int) $_GET['p'] : 1; // Changed to 'p' to match your links
if ($current_page < 1)
    $current_page = 1;

// Calculate offset
$offset = ($current_page - 1) * $limit;

$sql = "SELECT o.orderId, o.amount, o.createdAt AS orderDate, 
               COALESCE(CONCAT(d.first_name, ' ', d.last_name), 'N/A') AS delivery_boy, 
               (o.amount * 0.1) AS payout 
        FROM orders o
        LEFT JOIN delivery_boys d ON o.delivery_boy_id = d.id
        WHERE o.orderStatus = '4' 
        ORDER BY o.createdAt DESC
        LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);
// Get total count for pagination
$countQuery = "SELECT COUNT(*) as total FROM orders";
$countResult = $conn->query($countQuery);
$totalorders = $countResult->fetch_assoc()['total'];
$totalPages = ceil($totalorders / $limit);

// Ensure current page doesn't exceed total pages
if ($current_page > $totalPages && $totalPages > 0) {
    $current_page = $totalPages;
}


$totalPayoutsSql = "SELECT COALESCE(CONCAT(d.first_name, ' ', d.last_name), 'N/A') AS delivery_boy,
                            SUM(o.amount * 0.1) AS total_payout 
                     FROM orders o
                     LEFT JOIN delivery_boys d ON o.delivery_boy_id = d.id
                     WHERE o.orderStatus = '4' 
                     GROUP BY d.id";
$totalPayoutsResult = $conn->query($totalPayoutsSql);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Payouts</title>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.min.js"></script>
    <script>
        function printPage() {
            window.print();
        }
    </script>
    <style>
        /* Pagination CSS */
        .pagination-container {
            display: flex;
            justify-content: flex-end;
            /* Align to right */
            margin: 20px 0;
        }

        .pagination {
            display: flex;
            gap: 5px;
            flex-wrap: wrap;
        }

        .pagination a {
            padding: 8px 12px;
            border: 1px solid #ddd;
            color: rgb(116, 101, 179);
            text-decoration: none;
            border-radius: 3px;
            transition: all 0.3s;
        }

        .pagination span {
            padding: 8px 12px;
            border: 1px solid #ddd;
            color: rgb(0, 0, 0);
            text-decoration: none;
            border-radius: 3px;
        }

        .pagination a:hover {
            background: rgb(236, 236, 246);
        }

        .pagination .active {
            background: rgb(60, 185, 187);
            color: white;
            border-color: rgb(60, 185, 187);
        }

        .pagination .disabled {
            color: #aaa;
            border-color: #eee;
            cursor: not-allowed;
        }

        .pagination-info {
            text-align: right;
            margin-top: 10px;
            color: #666;
        }

        /* Show more page numbers */
        .pagination .page-numbers {
            display: flex;
            gap: 5px;
        }
    </style>
</head>

<body>

    <div class="container-fluid" style="margin-top: 20px; padding-top: 20px;">
        <div class="row">
            <div class="card col-lg-12">
                <div class="card-body">
                    <h2 class="text-center mb-4">Total Payouts Per Order</h2>

                    <table class="table table-striped table-bordered text-center">
                        <thead style="background-color: rgb(111 202 203);">
                            <tr>
                                <th>Order ID</th>
                                <th>Amount (₹)</th>
                                <th>Delivery Date</th>
                                <th>Payout (₹)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($result->num_rows > 0): ?>
                                <?php while ($row = $result->fetch_assoc()): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($row['orderId']); ?></td>
                                        <td>₹<?= number_format($row['amount'], 2); ?></td>
                                        <td><?= date("d-m-Y H:i:s", strtotime($row['orderDate'])); ?></td>
                                        <td>₹<?= number_format($row['payout'], 2); ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5">No payouts available.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>

                    <!-- Pagination -->
                    <?php if ($totalPages > 1): ?>
                        <div class="pagination-container">
                            <div>
                                <div class="pagination">
                                    <!-- Previous Button -->
                                    <?php if ($current_page <= 1): ?>
                                        <span class="disabled">« Previous</span>
                                    <?php else: ?>
                                        <a href="index.php?page=payouts&p=<?php echo $current_page - 1; ?>">«
                                            Previous</a>
                                    <?php endif; ?>

                                    <!-- Page Numbers -->
                                    <div class="page-numbers">
                                        <?php
                                        // Show more page numbers (current page ± 3)
                                        $start = max(1, $current_page - 1);
                                        $end = min($totalPages, $current_page + 1);

                                        // Always show first page
                                        if ($start > 1) {
                                            echo '<a href="index.php?page=payouts&p=1">1</a>';
                                            if ($start > 2)
                                                echo '<span>...</span>';
                                        }

                                        // Show page numbers in range
                                        for ($i = $start; $i <= $end; $i++) {
                                            if ($i == $current_page) {
                                                echo '<a href="index.php?page=payouts&p=' . $i . '" class="active">' . $i . '</a>';
                                            } else {
                                                echo '<a href="index.php?page=payouts&p=' . $i . '">' . $i . '</a>';
                                            }
                                        }

                                        // Always show last page
                                        if ($end < $totalPages) {
                                            if ($end < $totalPages - 1)
                                                echo '<span>...</span>';
                                            echo '<a href="index.php?page=payouts&p=' . $totalPages . '">' . $totalPages . '</a>';
                                        }
                                        ?>
                                    </div>

                                    <!-- Next Button -->
                                    <?php if ($current_page >= $totalPages): ?>
                                        <span class="disabled">Next »</span>
                                    <?php else: ?>
                                        <a href="index.php?page=payouts&p=<?php
                                        echo $current_page + 1;
                                        ?>">Next »</a>
                                    <?php endif; ?>
                                </div>

                                <div class="pagination-info">
                                    Showing <?php echo (($current_page - 1) * $limit + 1); ?> to
                                    <?php echo min($current_page * $limit, $totalorders); ?> Total PayOuts
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>

    <div class="container" style="margin-top: 20px; margin-bottom: 20px; max-width: 500px;">
        <div class="row">
            <div class="card col-lg-12">
                <div class="card-body">
                    <h2 class="text-center mb-4" style="font-size: 1.5rem;">Total Payouts Per Delivery Boy</h2>

                    <table class="table table-striped table-bordered text-center">
                        <thead style="background-color: rgb(111 202 203);">
                            <tr>
                                <th>Delivery Boy</th>
                                <th>Total Payout (₹)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($totalPayoutsResult->num_rows > 0): ?>
                                <?php while ($row = $totalPayoutsResult->fetch_assoc()): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($row['delivery_boy']); ?></td>
                                        <td>₹<?= number_format($row['total_payout'], 2); ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="2">No payouts available.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                    <div class="text-center mt-3">
                        <button class="btn btn-primary" onclick="printPage()">Print</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>
<?php $conn->close(); ?>