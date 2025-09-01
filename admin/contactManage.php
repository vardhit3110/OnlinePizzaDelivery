<style>
    .status-select {
        font-weight: bold;
    }

    /* Style the select dropdown based on value */
    .status-select.new {
        color: red !important;
    }

    .status-select.resolved {
        color: green !important;
    }
</style><br>
<div class="alert alert-info alert-dismissible fade show" role="alert" style="width:100%" id='notempty'>
    <strong>Info!</strong> If problem is not related to the order then order id = 0
    <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span></button>
</div>
<script>
    // Auto slide up the alert after 3 seconds
    $(document).ready(function () {
        setTimeout(function () {
            $('#notempty').slideUp(400);
        }, 4000);
    });
</script>
<style>
    .btn-danger-gradiant {
        background: linear-gradient(to right, #ff4d7e 0%, #ff6a5b 100%);
    }

    .btn-danger-gradiant:hover {
        background: linear-gradient(to right, #ff6a5b 0%, #ff4d7e 100%);
    }

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
<div style="margin-right: 32px;display: table;margin-left: auto;">
    <button type="button" class="btn btn-danger-gradiant text-white border-0 py-2 px-3 mx-2" data-toggle="modal"
        data-target="#history"><span> HISTORY <i class="ti-arrow-right"></i></span></button>
</div><br>
<div class="container-fluid" id="empty">
    <div class="row">
        <div class="card col-lg-12">
            <div class="card-body">
                <table class="table table-striped table-bordered text-center">
                    <thead style="background-color: rgb(111 202 203);">
                        <tr>
                            <th>Id</th>
                            <th>UserId</th>
                            <th>Email</th>
                            <th>Phone No</th>
                            <th>Order Id</th>
                            <th>Message</th>
                            <th>Datetime</th>
                            <th>Status</th>
                            <th>Reply</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Pagination logic
                        $limit = 5; // Number of reviews per page
                        $current_page = isset($_GET['p']) ? (int) $_GET['p'] : 1; // Changed to 'p' to match your links
                        if ($current_page < 1)
                            $current_page = 1;

                        // Calculate offset
                        $offset = ($current_page - 1) * $limit;
                        $sql = "SELECT * FROM contact ORDER BY contactId DESC  LIMIT $limit OFFSET $offset";
                        $result = mysqli_query($conn, $sql);
                        $count = 0;
                        // Get total count for pagination
                        $countQuery = "SELECT COUNT(*) as total FROM contact";
                        $countResult = $conn->query($countQuery);
                        $totalReviews = $countResult->fetch_assoc()['total'];
                        $totalPages = ceil($totalReviews / $limit);

                        // Ensure current page doesn't exceed total pages
                        if ($current_page > $totalPages && $totalPages > 0) {
                            $current_page = $totalPages;
                        }

                        while ($row = mysqli_fetch_assoc($result)) {
                            $contactId = $row['contactId'];
                            $userId = $row['userId'];
                            $email = $row['email'];
                            $phoneNo = $row['phoneNo'];
                            $orderId = $row['orderId'];
                            $message = $row['message'];
                            $time = $row['time'];
                            $status = $row['status']; // Fetch status from database
                            $count++;

                            // Determine the class for status styling
                            $statusClass = ($status == 'new') ? 'new' : 'resolved';

                            echo '<tr>
                                        <td>' . htmlspecialchars($contactId) . '</td>
                                        <td>' . htmlspecialchars($userId) . '</td>
                                        <td>' . htmlspecialchars($email) . '</td>
                                        <td>' . htmlspecialchars($phoneNo) . '</td>
                                        <td>' . htmlspecialchars($orderId) . '</td>
                                        <td>' . htmlspecialchars($message) . '</td>
                                        <td>' . htmlspecialchars($time) . '</td>
                                        <td class="text-center">
                                            <form method="POST" action="partials/_contactManage.php">
                                                <input type="hidden" name="contactId" value="' . htmlspecialchars($contactId) . '">
                                                <select name="status" class="form-control status-select ' . $statusClass . '" onchange="this.form.submit();">
                                                    <option value="new" ' . ($status == 'new' ? 'selected' : '') . '>New</option>
                                                    <option value="resolved" ' . ($status == 'resolved' ? 'selected' : '') . '>Resolved</option>
                                                </select>
                                            </form>
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-primary" type="button" data-toggle="modal" data-target="#reply' . htmlspecialchars($contactId) . '">Reply</button>
                                        </td>
                                    </tr>';
                        }

                        if ($count == 0) {
                            echo '<script>
                                    document.getElementById("notempty").innerHTML = 
                                        \'<div class="alert alert-info alert-dismissible fade show" role="alert" style="width:100%"> 
                                            You have not received any messages! 
                                        </div>\';
                                    document.getElementById("empty").innerHTML = "";
                                </script>';
                        }
                        ?>
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
                                    <a href="index.php?page=contactManage&p=<?php echo $current_page - 1; ?>">«
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
                                        echo '<a href="index.php?page=contactManage&p=1">1</a>';
                                        if ($start > 2)
                                            echo '<span>...</span>';
                                    }

                                    // Show page numbers in rang
                                    for ($i = $start; $i <= $end; $i++) {
                                        if ($i == $current_page) {
                                            echo '<a href="index.php?page=contactManage&p=' . $i . '" class="active">' . $i . '</a>';
                                        } else {
                                            echo '<a href="index.php?page=contactManage&p=' . $i . '">' . $i . '</a>';
                                        }
                                    }

                                    // Always show last page
                                    if ($end < $totalPages) {
                                        if ($end < $totalPages - 1)
                                            echo '<span>...</span>';
                                        echo '<a href="index.php?page=contactManage&p=' . $totalPages . '">' . $totalPages . '</a>';
                                    }
                                    ?>
                                </div>

                                <!-- Next Button -->
                                <?php if ($current_page >= $totalPages): ?>
                                    <span class="disabled">Next »</span>
                                <?php else: ?>
                                    <a href="index.php?page=contactManage&p=<?php
                                    echo $current_page + 1;
                                    ?>">Next »</a>
                                <?php endif; ?>
                            </div>

                            <div class="pagination-info">
                                Showing <?php echo (($current_page - 1) * $limit + 1); ?> to
                                <?php echo min($current_page * $limit, $totalReviews); ?> of
                                <?php echo $totalReviews; ?> Contact
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php
$contactsql = "SELECT * FROM `contact`";
$contactResult = mysqli_query($conn, $contactsql);
while ($contactRow = mysqli_fetch_assoc($contactResult)) {
    $contactId = $contactRow['contactId'];
    $Id = $contactRow['userId'];
    ?>

    <!-- Reply Modal -->
    <div class="modal fade" id="reply<?php echo $contactId; ?>" tabindex="-1" role="dialog"
        aria-labelledby="reply<?php echo $contactId; ?>" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background-color: rgb(111 202 203);">
                    <h5 class="modal-title" id="reply<?php echo $contactId; ?>">Reply (Contact Id:
                        <?php echo $contactId; ?>)
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="partials/_contactManage.php" method="post">
                        <div class="text-left my-2">
                            <b><label for="message">Message: </label></b>
                            <textarea class="form-control" id="message" name="message" rows="2" required
                                minlength="5"></textarea>
                        </div>
                        <input type="hidden" id="contactId" name="contactId" value="<?php echo $contactId; ?>">
                        <input type="hidden" id="userId" name="userId" value="<?php echo $Id; ?>">
                        <button type="submit" class="btn btn-success" name="contactReply">Reply</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php
}
?>
<!-- history Modal -->
<div class="modal fade" id="history" tabindex="-1" role="dialog" aria-labelledby="history" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: rgb(187 188 189);">
                <h5 class="modal-title" id="history">Your Sent Message</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="notReply">
                <table class="table-striped table-bordered col-md-12 text-center">
                    <thead style="background-color: rgb(111 202 203);">
                        <tr>
                            <th>Contact Id</th>
                            <th>Reply Message</th>
                            <th>datetime</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT * FROM `contactreply`";
                        $result = mysqli_query($conn, $sql);
                        $totalReply = 0;
                        while ($row = mysqli_fetch_assoc($result)) {
                            $contactId = $row['contactId'];
                            $message = $row['message'];
                            $datetime = $row['datetime'];
                            $totalReply++;

                            echo '<tr>
                                <td>' . $contactId . '</td>
                                <td>' . $message . '</td>
                                <td>' . $datetime . '</td>
                              </tr>';
                        }

                        if ($totalReply == 0) {
                            ?>
                            <script> document.getElementById("notReply").innerHTML = '<div class="alert alert-info alert-dismissible fade show" role="alert" style="width:100%"> You have not Reply any message</div>';</script> <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>