<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include 'partials/_dbconnect.php'; // Database connection


// Pagination logic
$limit = 5; // Number of reviews per page
$current_page = isset($_GET['p']) ? (int) $_GET['p'] : 1; // Changed to 'p' to match your links
if ($current_page < 1)
    $current_page = 1;

// Calculate offset
$offset = ($current_page - 1) * $limit;

// Fetch feedbacks sorted by feedback_id in descending order
$sql = "SELECT f.feedback_id, f.user_id, u.email, f.rating, f.comment, f.submission_date, f.status 
        FROM feedback f
        JOIN users u ON f.user_id = u.id
        ORDER BY f.feedback_id DESC
        LIMIT $limit OFFSET $offset";
$result = mysqli_query($conn, $sql);

// Get total count for pagination
$countQuery = "SELECT COUNT(*) as total FROM feedback";
$countResult = $conn->query($countQuery);
$feedback = $countResult->fetch_assoc()['total'];
$totalPages = ceil($feedback / $limit);

// Ensure current page doesn't exceed total pages
if ($current_page > $totalPages && $totalPages > 0) {
    $current_page = $totalPages;
}
// Check if a message is set in the URL
$alertMessage = "";
$alertClass = "";
if (isset($_GET['msg'])) {
    switch ($_GET['msg']) {
        case "deleted":
            $alertMessage = "Feedback deleted successfully!";
            $alertClass = "alert-success";
            break;
        case "error":
            $alertMessage = "An error occurred. Please try again!";
            $alertClass = "alert-danger";
            break;
        case "status_updated":
            $alertMessage = "Feedback status updated successfully!";
            $alertClass = "alert-info";
            break;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback Management</title>
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

    <div class="container-fluid" id="empty" style="margin-top: 20px; padding-top: 20px;">

        <?php if (!empty($alertMessage)): ?>
            <div class="alert <?php echo $alertClass; ?> text-center">
                <?php echo $alertMessage; ?>
            </div>
        <?php endif; ?>

        <div class="container-fluid" id="empty">
            <div class="row">
                <div class="card col-lg-12">
                    <div class="card-body">
                        <h2>
                            <center>Feedback Management</center>
                        </h2>
                        <table class="table table-striped table-bordered text-center">
                            <thead style="background-color: rgb(111 202 203);">
                                <tr>
                                    <th>ID</th>
                                    <th>Email</th>
                                    <th>Rating</th>
                                    <th>Comment</th>
                                    <th>Submission Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (mysqli_num_rows($result) > 0): ?>
                                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($row['feedback_id']); ?></td>
                                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                                            <td><?php echo htmlspecialchars($row['rating']); ?> / 5</td>
                                            <td><?php echo htmlspecialchars($row['comment']); ?></td>
                                            <td><?php echo htmlspecialchars($row['submission_date']); ?></td>
                                            <td class="text-center">
                                                <form action="delete_feedback.php" method="POST"
                                                    onsubmit="return confirm('Are you sure you want to delete this feedback?');">
                                                    <input type="hidden" name="feedback_id"
                                                        value="<?php echo $row['feedback_id']; ?>">
                                                    <button type="submit" class="btn btn-danger btn-sm"
                                                        style="height:30px; width:76px;">
                                                        <i class="fas fa-trash"></i> Delete
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="text-center text-muted">No feedback received yet!</td>
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
                                            <a href="index.php?page=feedbackManage&p=<?php echo $current_page - 1; ?>">«
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
                                                echo '<a href="index.php?page=feedbackManage&p=1">1</a>';
                                                if ($start > 2)
                                                    echo '<span>...</span>';
                                            }

                                            // Show page numbers in range
                                            for ($i = $start; $i <= $end; $i++) {
                                                if ($i == $current_page) {
                                                    echo '<a href="index.php?page=feedbackManage&p=' . $i . '" class="active">' . $i . '</a>';
                                                } else {
                                                    echo '<a href="index.php?page=feedbackManage&p=' . $i . '">' . $i . '</a>';
                                                }
                                            }

                                            // Always show last page
                                            if ($end < $totalPages) {
                                                if ($end < $totalPages - 1)
                                                    echo '<span>...</span>';
                                                echo '<a href="index.php?page=feedbackManage&p=' . $totalPages . '">' . $totalPages . '</a>';
                                            }
                                            ?>
                                        </div>

                                        <!-- Next Button -->
                                        <?php if ($current_page >= $totalPages): ?>
                                            <span class="disabled">Next »</span>
                                        <?php else: ?>
                                            <a href="index.php?page=feedbackManage&p=<?php
                                            echo $current_page + 1;
                                            ?>">Next »</a>
                                        <?php endif; ?>
                                    </div>

                                    <div class="pagination-info">
                                        Showing <?php echo (($current_page - 1) * $limit + 1); ?> to
                                        <?php echo min($current_page * $limit, $feedback); ?> of
                                        <?php echo $feedback; ?> Feedback Manage
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
</body>

</html>