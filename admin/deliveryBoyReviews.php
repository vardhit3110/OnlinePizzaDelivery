<?php
include '_dbconnect.php'; // Database connection file

// Pagination logic
$limit = 5; // Number of reviews per page
$current_page = isset($_GET['p']) ? (int) $_GET['p'] : 1; // Changed to 'p' to match your links
if ($current_page < 1)
    $current_page = 1;

// Calculate offset
$offset = ($current_page - 1) * $limit;

// Fetch reviews with pagination
$sql = "SELECT r.*, d.first_name, d.last_name 
        FROM reviews r 
        LEFT JOIN delivery_boys d ON r.delivery_boy_id = d.id 
        ORDER BY r.reviewDate DESC
        LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);

// Get total count for pagination
$countQuery = "SELECT COUNT(*) as total FROM reviews";
$countResult = $conn->query($countQuery);
$totalReviews = $countResult->fetch_assoc()['total'];
$totalPages = ceil($totalReviews / $limit);

// Ensure current page doesn't exceed total pages
if ($current_page > $totalPages && $totalPages > 0) {
    $current_page = $totalPages;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reviews & Complaints</title>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.min.js"></script>
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

    <?php if (isset($_GET['msg'])): ?>
        <div class="alert <?php echo ($_GET['msg'] == 'deleted') ? 'alert-success' : 'alert-danger'; ?> text-center">
            <?php echo ($_GET['msg'] == 'deleted') ? 'Review deleted successfully!' : 'Error deleting review!'; ?>
        </div>
    <?php endif; ?>

    <div class="container-fluid" id="empty" style="margin-top: 20px; padding-top: 20px;">
        <div class="row">
            <div class="card col-lg-12">
                <div class="card-body">
                    <h2 class="text-center mb-4">User Reviews & Complaints</h2>

                    <table class="table table-striped table-bordered text-center">
                        <thead style="background-color: rgb(111 202 203);">
                            <tr>
                                <th>Review ID</th>
                                <th>User ID</th>
                                <th>Order ID</th>
                                <th>Rating</th>
                                <th>Complaint</th>
                                <th>Delivery Boy Name</th>
                                <th>Review Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($result->num_rows > 0): ?>
                                <?php while ($row = $result->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($row['reviewId']); ?></td>
                                        <td><?php echo htmlspecialchars($row['userId']); ?></td>
                                        <td><?php echo htmlspecialchars($row['orderId']); ?></td>
                                        <td><?php echo htmlspecialchars($row['rating']) . "/5"; ?></td>
                                        <td><?php echo !empty($row['complain']) ? htmlspecialchars($row['complain']) : '<span class="text-muted">No Complaint</span>'; ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></td>
                                        <td><?php echo htmlspecialchars($row['reviewDate']); ?></td>
                                        <td>
                                            <form action="partials/delete_review.php" method="POST"
                                                onsubmit="return confirm('Are you sure you want to delete this review?');">
                                                <input type="hidden" name="reviewId" value="<?php echo $row['reviewId']; ?>">
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i class="fas fa-trash"></i> Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="8">No reviews or complaints found</td>
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
                                        <a href="index.php?page=deliveryBoyReviews&p=<?php echo $current_page - 1; ?>">«
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
                                            echo '<a href="index.php?page=deliveryBoyReviews&p=1">1</a>';
                                            if ($start > 2)
                                                echo '<span>...</span>';
                                        }

                                        // Show page numbers in range
                                        for ($i = $start; $i <= $end; $i++) {
                                            if ($i == $current_page) {
                                                echo '<a href="index.php?page=deliveryBoyReviews&p=' . $i . '" class="active">' . $i . '</a>';
                                            } else {
                                                echo '<a href="index.php?page=deliveryBoyReviews&p=' . $i . '">' . $i . '</a>';
                                            }
                                        }

                                        // Always show last page
                                        if ($end < $totalPages) {
                                            if ($end < $totalPages - 1)
                                                echo '<span>...</span>';
                                            echo '<a href="index.php?page=deliveryBoyReviews&p=' . $totalPages . '">' . $totalPages . '</a>';
                                        }
                                        ?>
                                    </div>

                                    <!-- Next Button -->
                                    <?php if ($current_page >= $totalPages): ?>
                                        <span class="disabled">Next »</span>
                                    <?php else: ?>
                                        <a href="index.php?page=deliveryBoyReviews&p=<?php
                                        echo $current_page + 1;
                                        ?>">Next »</a>
                                    <?php endif; ?>
                                </div>

                                <div class="pagination-info">
                                    Showing <?php echo (($current_page - 1) * $limit + 1); ?> to
                                    <?php echo min($current_page * $limit, $totalReviews); ?> of
                                    <?php echo $totalReviews; ?> Reviews & Complaints
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div><br>

</body>

</html>
<?php $conn->close(); ?>