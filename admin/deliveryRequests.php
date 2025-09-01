<?php
include '_dbconnect.php'; // Database connection

// Pagination settings
$limit = 5; // Number of items per page

// Pending Delivery Requests Pagination
$pendingCurrentPage = isset($_GET['p_pending']) ? (int) $_GET['p_pending'] : 1;
if ($pendingCurrentPage < 1)
    $pendingCurrentPage = 1;
$pendingOffset = ($pendingCurrentPage - 1) * $limit;

// Approved Delivery Boys Pagination
$approvedCurrentPage = isset($_GET['p_approved']) ? (int) $_GET['p_approved'] : 1;
if ($approvedCurrentPage < 1)
    $approvedCurrentPage = 1;
$approvedOffset = ($approvedCurrentPage - 1) * $limit;

// Rejected Delivery Boys Pagination
$rejectedCurrentPage = isset($_GET['p_rejected']) ? (int) $_GET['p_rejected'] : 1;
if ($rejectedCurrentPage < 1)
    $rejectedCurrentPage = 1;
$rejectedOffset = ($rejectedCurrentPage - 1) * $limit;

// Fetch pending delivery requests with pagination
$pendingQuery = "SELECT id, delivery_boy_name, first_name, last_name, email, phone, vehicle_type FROM delivery_boys WHERE status='pending' LIMIT $limit OFFSET $pendingOffset";
$pendingResult = mysqli_query($conn, $pendingQuery);

// Get total pending count
$pendingCountQuery = "SELECT COUNT(*) as total FROM delivery_boys WHERE status='pending'";
$pendingCountResult = mysqli_query($conn, $pendingCountQuery);
$totalPending = mysqli_fetch_assoc($pendingCountResult)['total'];
$totalPendingPages = ceil($totalPending / $limit);

// Fetch approved delivery boys with pagination
$approvedQuery = "SELECT id, delivery_boy_name, first_name, last_name, email, phone, vehicle_type FROM delivery_boys WHERE status='approved' LIMIT $limit OFFSET $approvedOffset";
$approvedResult = mysqli_query($conn, $approvedQuery);

// Get total approved count
$approvedCountQuery = "SELECT COUNT(*) as total FROM delivery_boys WHERE status='approved'";
$approvedCountResult = mysqli_query($conn, $approvedCountQuery);
$totalApproved = mysqli_fetch_assoc($approvedCountResult)['total'];
$totalApprovedPages = ceil($totalApproved / $limit);

// Fetch rejected delivery boys with pagination
$rejectedQuery = "SELECT id, delivery_boy_name, first_name, last_name, email, phone, vehicle_type FROM delivery_boys WHERE status='rejected' LIMIT $limit OFFSET $rejectedOffset";
$rejectedResult = mysqli_query($conn, $rejectedQuery);

// Get total rejected count
$rejectedCountQuery = "SELECT COUNT(*) as total FROM delivery_boys WHERE status='rejected'";
$rejectedCountResult = mysqli_query($conn, $rejectedCountQuery);
$totalRejected = mysqli_fetch_assoc($rejectedCountResult)['total'];
$totalRejectedPages = ceil($totalRejected / $limit);

$alertMessage = "";
if (isset($_GET['msg'])) {
    $msg = htmlspecialchars($_GET['msg']); // Prevent XSS attacks
    if ($msg == "approved") {
        $alertMessage = "Delivery request approved successfully!";
    } elseif ($msg == "rejected") {
        $alertMessage = "Delivery request rejected!";
    } elseif ($msg == "removed") {
        $alertMessage = "Delivery boy removed successfully!";
    } elseif ($msg == "error") {
        $alertMessage = "Error removing delivery boy!";
    }
}
?>

<!-- JavaScript for Alert Message -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        let message = "<?php echo $alertMessage; ?>";
        if (message) {
            alert(message);
            window.location.href = "index.php?page=deliveryRequests";
        }
    });
</script>

<style>
    /* Pagination CSS */
    .pagination-container {
        display: flex;
        justify-content: flex-end;
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

<div class="container-fluid" id="empty" style="margin-top: 20px; padding-top: 20px;">

    <div class="row">
        <div class="card col-lg-12">
            <div class="card-body">
                <h3 class="text-center">Pending Delivery Requests</h3>
                <table class="table table-striped table-bordered col-md-12 text-center">
                    <thead style="background-color: rgb(111 202 203);">
                        <tr>
                            <th>ID</th>
                            <th>Delivery Boy Name</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Vehicle</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $count = 0;
                        while ($row = mysqli_fetch_assoc($pendingResult)) {
                            $count++;
                            echo '<tr>
                                        <td>' . htmlspecialchars($row['id']) . '</td>
                                        <td>' . htmlspecialchars($row['delivery_boy_name']) . '</td>
                                        <td>' . htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) . '</td>
                                        <td>' . htmlspecialchars($row['email']) . '</td>
                                        <td>' . htmlspecialchars($row['phone']) . '</td>
                                        <td>' . htmlspecialchars($row['vehicle_type'] ? $row['vehicle_type'] : 'N/A') . '</td>
                                        <td>
                                            <a href="partials/_manageDeliveryBoy.php?id=' . urlencode($row['id']) . '&action=approve" class="btn btn-success btn-sm">Accept</a>
                                            <a href="partials/_manageDeliveryBoy.php?id=' . urlencode($row['id']) . '&action=reject" class="btn btn-danger btn-sm">Reject</a>
                                        </td>
                                    </tr>';
                        }
                        if ($count == 0) {
                            echo '<tr><td colspan="7" class="text-center"><div class="alert alert-danger">No pending delivery requests!</div></td></tr>';
                        }
                        ?>
                    </tbody>
                </table>

                <!-- Pending Pagination -->
                <?php if ($totalPendingPages > 1): ?>
                    <div class="pagination-container">
                        <div>
                            <div class="pagination">
                                <!-- Previous Button -->
                                <?php if ($pendingCurrentPage <= 1): ?>
                                    <span class="disabled">« Previous</span>
                                <?php else: ?>
                                    <a href="index.php?page=deliveryRequests&p_pending=<?php echo $pendingCurrentPage - 1; ?>">«
                                        Previous</a>
                                <?php endif; ?>

                                <!-- Page Numbers -->
                                <div class="page-numbers">
                                    <?php
                                    $start = max(1, $pendingCurrentPage - 1);
                                    $end = min($totalPendingPages, $pendingCurrentPage + 1);

                                    if ($start > 1) {
                                        echo '<a href="index.php?page=deliveryRequests&p_pending=1">1</a>';
                                        if ($start > 2)
                                            echo '<span>...</span>';
                                    }

                                    for ($i = $start; $i <= $end; $i++) {
                                        if ($i == $pendingCurrentPage) {
                                            echo '<a href="index.php?page=deliveryRequests&p_pending=' . $i . '" class="active">' . $i . '</a>';
                                        } else {
                                            echo '<a href="index.php?page=deliveryRequests&p_pending=' . $i . '">' . $i . '</a>';
                                        }
                                    }

                                    if ($end < $totalPendingPages) {
                                        if ($end < $totalPendingPages - 1)
                                            echo '<span>...</span>';
                                        echo '<a href="index.php?page=deliveryRequests&p_pending=' . $totalPendingPages . '">' . $totalPendingPages . '</a>';
                                    }
                                    ?>
                                </div>

                                <!-- Next Button -->
                                <?php if ($pendingCurrentPage >= $totalPendingPages): ?>
                                    <span class="disabled">Next »</span>
                                <?php else: ?>
                                    <a href="index.php?page=deliveryRequests&p_pending=<?php echo $pendingCurrentPage + 1; ?>">Next
                                        »</a>
                                <?php endif; ?>
                            </div>

                            <div class="pagination-info">
                                Showing <?php echo (($pendingCurrentPage - 1) * $limit + 1); ?> to
                                <?php echo min($pendingCurrentPage * $limit, $totalPending); ?> of
                                <?php echo $totalPending; ?> Pending Requests
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Approved Delivery Boys Section -->
    <div class="row mt-5">
        <div class="card col-lg-12">
            <div class="card-body">
                <h3 class="text-center">Approved Delivery Boys</h3>
                <table class="table table-striped table-bordered col-md-12 text-center">
                    <thead style="background-color: rgb(111 202 203);">
                        <tr>
                            <th>ID</th>
                            <th>Delivery Boy Name</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Vehicle</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $count = 0;
                        while ($row = mysqli_fetch_assoc($approvedResult)) {
                            $count++;
                            echo '<tr>
                                        <td>' . htmlspecialchars($row['id']) . '</td>
                                        <td>' . htmlspecialchars($row['delivery_boy_name']) . '</td>
                                        <td>' . htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) . '</td>
                                        <td>' . htmlspecialchars($row['email']) . '</td>
                                        <td>' . htmlspecialchars($row['phone']) . '</td>
                                        <td>' . htmlspecialchars($row['vehicle_type'] ? $row['vehicle_type'] : 'N/A') . '</td>
                                        <td>
                                            <a href="partials/_manageDeliveryBoy.php?id=' . urlencode($row['id']) . '&action=remove" class="btn btn-warning btn-sm">Remove</a>
                                        </td>
                                    </tr>';
                        }
                        if ($count == 0) {
                            echo '<tr><td colspan="7" class="text-center"><div class="alert alert-danger">No approved delivery boys yet!</div></td></tr>';
                        }
                        ?>
                    </tbody>
                </table>

                <!-- Approved Pagination -->
                <?php if ($totalApprovedPages > 1): ?>
                    <div class="pagination-container">
                        <div>
                            <div class="pagination">
                                <!-- Previous Button -->
                                <?php if ($approvedCurrentPage <= 1): ?>
                                    <span class="disabled">« Previous</span>
                                <?php else: ?>
                                    <a
                                        href="index.php?page=deliveryRequests&p_approved=<?php echo $approvedCurrentPage - 1; ?>">«
                                        Previous</a>
                                <?php endif; ?>

                                <!-- Page Numbers -->
                                <div class="page-numbers">
                                    <?php
                                    $start = max(1, $approvedCurrentPage - 1);
                                    $end = min($totalApprovedPages, $approvedCurrentPage + 1);

                                    if ($start > 1) {
                                        echo '<a href="index.php?page=deliveryRequests&p_approved=1">1</a>';
                                        if ($start > 2)
                                            echo '<span>...</span>';
                                    }

                                    for ($i = $start; $i <= $end; $i++) {
                                        if ($i == $approvedCurrentPage) {
                                            echo '<a href="index.php?page=deliveryRequests&p_approved=' . $i . '" class="active">' . $i . '</a>';
                                        } else {
                                            echo '<a href="index.php?page=deliveryRequests&p_approved=' . $i . '">' . $i . '</a>';
                                        }
                                    }

                                    if ($end < $totalApprovedPages) {
                                        if ($end < $totalApprovedPages - 1)
                                            echo '<span>...</span>';
                                        echo '<a href="index.php?page=deliveryRequests&p_approved=' . $totalApprovedPages . '">' . $totalApprovedPages . '</a>';
                                    }
                                    ?>
                                </div>

                                <!-- Next Button -->
                                <?php if ($approvedCurrentPage >= $totalApprovedPages): ?>
                                    <span class="disabled">Next »</span>
                                <?php else: ?>
                                    <a
                                        href="index.php?page=deliveryRequests&p_approved=<?php echo $approvedCurrentPage + 1; ?>">Next
                                        »</a>
                                <?php endif; ?>
                            </div>

                            <div class="pagination-info">
                                Showing <?php echo (($approvedCurrentPage - 1) * $limit + 1); ?> to
                                <?php echo min($approvedCurrentPage * $limit, $totalApproved); ?> of
                                <?php echo $totalApproved; ?> Approved Delivery Boys
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Rejected Delivery Boys Section -->
    <div class="row mt-5">
        <div class="card col-lg-12">
            <div class="card-body">
                <h3 class="text-center">Rejected Delivery Boys</h3>
                <table class="table table-striped table-bordered col-md-12 text-center">
                    <thead style="background-color: rgb(111 202 203);">
                        <tr>
                            <th>ID</th>
                            <th>Delivery Boy Name</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Vehicle</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $count = 0;
                        while ($row = mysqli_fetch_assoc($rejectedResult)) {
                            $count++;
                            echo '<tr>
                                        <td>' . htmlspecialchars($row['id']) . '</td>
                                        <td>' . htmlspecialchars($row['delivery_boy_name']) . '</td>
                                        <td>' . htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) . '</td>
                                        <td>' . htmlspecialchars($row['email']) . '</td>
                                        <td>' . htmlspecialchars($row['phone']) . '</td>
                                        <td>' . htmlspecialchars($row['vehicle_type'] ? $row['vehicle_type'] : 'N/A') . '</td>
                                    </tr>';
                        }
                        if ($count == 0) {
                            echo '<tr><td colspan="6" class="text-center"><div class="alert alert-danger">No rejected delivery boys yet!</div></td></tr>';
                        }
                        ?>
                    </tbody>
                </table>

                <!-- Rejected Pagination -->
                <?php if ($totalRejectedPages > 1): ?>
                    <div class="pagination-container">
                        <div>
                            <div class="pagination">
                                <!-- Previous Button -->
                                <?php if ($rejectedCurrentPage <= 1): ?>
                                    <span class="disabled">« Previous</span>
                                <?php else: ?>
                                    <a
                                        href="index.php?page=deliveryRequests&p_rejected=<?php echo $rejectedCurrentPage - 1; ?>">«
                                        Previous</a>
                                <?php endif; ?>

                                <!-- Page Numbers -->
                                <div class="page-numbers">
                                    <?php
                                    $start = max(1, $rejectedCurrentPage - 1);
                                    $end = min($totalRejectedPages, $rejectedCurrentPage + 1);

                                    if ($start > 1) {
                                        echo '<a href="index.php?page=deliveryRequests&p_rejected=1">1</a>';
                                        if ($start > 2)
                                            echo '<span>...</span>';
                                    }

                                    for ($i = $start; $i <= $end; $i++) {
                                        if ($i == $rejectedCurrentPage) {
                                            echo '<a href="index.php?page=deliveryRequests&p_rejected=' . $i . '" class="active">' . $i . '</a>';
                                        } else {
                                            echo '<a href="index.php?page=deliveryRequests&p_rejected=' . $i . '">' . $i . '</a>';
                                        }
                                    }

                                    if ($end < $totalRejectedPages) {
                                        if ($end < $totalRejectedPages - 1)
                                            echo '<span>...</span>';
                                        echo '<a href="index.php?page=deliveryRequests&p_rejected=' . $totalRejectedPages . '">' . $totalRejectedPages . '</a>';
                                    }
                                    ?>
                                </div>

                                <!-- Next Button -->
                                <?php if ($rejectedCurrentPage >= $totalRejectedPages): ?>
                                    <span class="disabled">Next »</span>
                                <?php else: ?>
                                    <a
                                        href="index.php?page=deliveryRequests&p_rejected=<?php echo $rejectedCurrentPage + 1; ?>">Next
                                        »</a>
                                <?php endif; ?>
                            </div>

                            <div class="pagination-info">
                                Showing <?php echo (($rejectedCurrentPage - 1) * $limit + 1); ?> to
                                <?php echo min($rejectedCurrentPage * $limit, $totalRejected); ?> of
                                <?php echo $totalRejected; ?> Rejected Delivery Boys
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<br><br>