<style>
    /* Default styling for the status dropdown */
    .status-field {
        font-weight: bold;
        text-align: center;
        border-radius: 5px;
        padding: 5px;
        border: none;
        width: 100px;
    }

    /* Green text for Active status */
    .status-active {
        color: #28a745 !important;
        /* Green */
    }

    /* Red text for Blocked status */
    .status-blocked {
        color: #dc3545 !important;
        /* Red */
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
<div class="container-fluid" style="margin-top:98px">
    <div class="row">
        <div class="col-lg-12">
            <button class="btn btn-primary float-right btn-sm" data-toggle="modal" data-target="#newUser"><i
                    class="fa fa-plus"></i> New user</button>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="card col-lg-12">
            <div class="card-body">
                <table class="table-striped table-bordered col-md-12 text-center">
                    <thead style="background-color: rgb(111 202 203);">
                        <tr>
                            <th>UserId</th>
                            <th style="width:7%">Photo</th>
                            <th>Username</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone No.</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        include '_dbconnect.php'; // Ensure correct database connection
                        // Pagination logic
                        $limit = 5; // Number of  users per page
                        $current_page = isset($_GET['p']) ? (int) $_GET['p'] : 1; // Changed to 'p' to match your links
                        if ($current_page < 1)
                            $current_page = 1;

                        // Calculate offset
                        $offset = ($current_page - 1) * $limit;

                        $sql = "SELECT * FROM users ORDER BY id DESC LIMIT $limit OFFSET $offset";
                        $result = mysqli_query($conn, $sql);

                        $countQuery = "SELECT COUNT(*) as total FROM  users";
                        $countResult = $conn->query($countQuery);
                        $totalUsers = $countResult->fetch_assoc()['total'];
                        $totalPages = ceil($totalUsers / $limit);

                        // Ensure current page doesn't exceed total pages
                        if ($current_page > $totalPages && $totalPages > 0) {
                            $current_page = $totalPages;
                        }

                        while ($row = mysqli_fetch_assoc($result)) {
                            $Id = $row['id'];
                            $username = $row['username'];
                            $firstName = $row['firstName'];
                            $lastName = $row['lastName'];
                            $email = $row['email'];
                            $phone = $row['phone'];
                            $status = $row['status'];


                            echo '<tr>
                                <td>' . $Id . '</td>
                                <td><img src="/OnlinePizzaDelivery/img/person-' . $username . '.jpg" alt="image for this user" onError="this.src =/OnlinePizzaDelivery/img/profilePic.jpg" width="100px" height="100px"></td>
                                <td>' . $username . '</td>
                                <td>
                                    <p>First Name: <b>' . $firstName . '</b></p>
                                    <p>Last Name: <b>' . $lastName . '</b></p>
                                </td>
                                <td>' . $email . '</td>
                                <td>' . $phone . '</td>
                                
                                <td class="text-center">
                                    <form action="partials/_userManage.php" method="POST">
                                        <input type="hidden" name="Id" value="' . $Id . '">
                                        <select name="status" class="form-control form-control-sm status-field ' . ($status == 'active' ? 'status-active' : 'status-blocked') . '" onchange="this.form.submit()">
                                            <option value="active" ' . ($status == 'active' ? 'selected' : '') . '>Active</option>
                                            <option value="blocked" ' . ($status == 'blocked' ? 'selected' : '') . '>Blocked</option>
                                        </select>
                                    </form>
                                </td>
                                <td class="text-center">
                                    <div class="row mx-auto" style="width:150px">
                                        <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#editUser' . $Id . '" type="button">Edit</button>';

                            if ($Id == 1) {
                                echo '<button class="btn btn-sm btn-danger" disabled style="margin-left:9px;">Delete</button>';
                            } else {
                                echo '<form action="partials/_userManage.php" method="POST">
                                                    <input type="hidden" name="Id" value="' . $Id . '">
                                                    <button name="removeUser" class="btn btn-sm btn-danger" style="margin-left:9px;">Delete</button>
                                                </form>';
                            }

                            echo '</div>
                                </td>
                            </tr>';
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
                                    <a href="index.php?page=userManage&p=<?php echo $current_page - 1; ?>">«
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
                                        echo '<a href="index.php?page=userManage&p=1">1</a>';
                                        if ($start > 2)
                                            echo '<span>...</span>';
                                    }

                                    // Show page numbers in range
                                    for ($i = $start; $i <= $end; $i++) {
                                        if ($i == $current_page) {
                                            echo '<a href="index.php?page=userManage&p=' . $i . '" class="active">' . $i . '</a>';
                                        } else {
                                            echo '<a href="index.php?page=userManage&p=' . $i . '">' . $i . '</a>';
                                        }
                                    }

                                    // Always show last page
                                    if ($end < $totalPages) {
                                        if ($end < $totalPages - 1)
                                            echo '<span>...</span>';
                                        echo '<a href="index.php?page=userManage&p=' . $totalPages . '">' . $totalPages . '</a>';
                                    }
                                    ?>
                                </div>

                                <!-- Next Button -->
                                <?php if ($current_page >= $totalPages): ?>
                                    <span class="disabled">Next »</span>
                                <?php else: ?>
                                    <a href="index.php?page=userManage&p=<?php
                                    echo $current_page + 1;
                                    ?>">Next »</a>
                                <?php endif; ?>
                            </div>

                            <div class="pagination-info">
                                Showing <?php echo (($current_page - 1) * $limit + 1); ?> to
                                <?php echo min($current_page * $limit, $totalUsers); ?> of
                                <?php echo $totalUsers; ?> Users
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div><br>
</div>

<!-- newUser Modal -->
<div class="modal fade" id="newUser" tabindex="-1" role="dialog" aria-labelledby="newUser" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: rgb(111 202 203);">
                <h5 class="modal-title" id="newUser">Create New User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="partials/_userManage.php" method="post">
                    <div class="form-group">
                        <b><label for="username">Username</label></b>
                        <input class="form-control" id="username" name="username" placeholder="Choose a unique Username"
                            type="text" required minlength="3" maxlength="11">
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <b><label for="firstName">First Name:</label></b>
                            <input type="text" class="form-control" id="firstName" name="firstName"
                                placeholder="First Name" required>
                        </div>
                        <div class="form-group col-md-6">
                            <b><label for="lastName">Last name:</label></b>
                            <input type="text" class="form-control" id="lastName" name="lastName"
                                placeholder="Last name" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <b><label for="email">Email:</label></b>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Enter Your Email"
                            required>
                    </div>
                    <div class="form-group row my-0">
                        <div class="form-group col-md-6 my-0">
                            <b><label for="phone">Phone No:</label></b>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon">+91</span>
                                </div>
                                <input type="tel" class="form-control" id="phone" name="phone"
                                    placeholder="Enter Phone No" required pattern="[0-9]{10}" maxlength="10">
                            </div>
                        </div>

                    </div>
                    <div class="form-group">
                        <b><label for="password">Password:</label></b>
                        <input class="form-control" id="password" name="password" placeholder="Enter Password"
                            type="password" required data-toggle="password" minlength="4" maxlength="21">
                    </div>
                    <div class="form-group">
                        <b><label for="password1">Renter Password:</label></b>
                        <input class="form-control" id="cpassword" name="cpassword" placeholder="Renter Password"
                            type="password" required data-toggle="password" minlength="4" maxlength="21">
                    </div>
                    <button type="submit" name="createUser" class="btn btn-success">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
$usersql = "SELECT * FROM `users`";
$userResult = mysqli_query($conn, $usersql);
while ($userRow = mysqli_fetch_assoc($userResult)) {
    $Id = $userRow['id'];
    $name = $userRow['username'];
    $firstName = $userRow['firstName'];
    $lastName = $userRow['lastName'];
    $email = $userRow['email'];
    $phone = $userRow['phone'];

    ?>
    <!-- editUser Modal -->
    <div class="modal fade" id="editUser<?php echo $Id; ?>" tabindex="-1" role="dialog"
        aria-labelledby="editUser<?php echo $Id; ?>" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background-color: rgb(111 202 203);">
                    <h5 class="modal-title" id="editUser<?php echo $Id; ?>">User Id: <b><?php echo $Id; ?></b></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="text-left my-2 row" style="border-bottom: 2px solid #dee2e6;">
                        <div class="form-group col-md-8">
                            <form action="partials/_userManage.php" method="post" enctype="multipart/form-data">
                                <b><label for="image">Profile Picture</label></b>
                                <input type="file" name="userimage" id="userimage" accept=".jpg" class="form-control"
                                    required style="border:none;">
                                <small id="Info" class="form-text text-muted mx-3">Please .jpg file upload.</small>
                                <input type="hidden" id="userId" name="userId" value="<?php echo $Id; ?>">
                                <button type="submit" class="btn btn-success mt-3" name="updateProfilePhoto">Update
                                    Img</button>
                            </form>
                        </div>
                        <div class="form-group col-md-4">
                            <img src="/OnlinePizzaDelivery/img/person-<?php echo $name; ?>.jpg" alt="Profile Photo"
                                width="100" height="100" onError="this.src ='/OnlinePizzaDelivery/img/profilePic.jpg'">
                            <form action="partials/_userManage.php" method="post">
                                <input type="hidden" id="userId" name="userId" value="<?php echo $Id; ?>">
                                <button type="submit" class="btn btn-success mt-2" name="removeProfilePhoto">Remove
                                    Img</button>
                            </form>
                        </div>
                    </div>

                    <form action="partials/_userManage.php" method="post">
                        <div class="form-group">
                            <b><label for="username">Username</label></b>
                            <input class="form-control" id="username" name="username" value="<?php echo $name; ?>"
                                type="text" disabled>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <b><label for="firstName">First Name:</label></b>
                                <input type="text" class="form-control" id="firstName" name="firstName"
                                    value="<?php echo $firstName; ?>" required>
                            </div>
                            <div class="form-group col-md-6">
                                <b><label for="lastName">Last name:</label></b>
                                <input type="text" class="form-control" id="lastName" name="lastName"
                                    value="<?php echo $lastName; ?>" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <b><label for="email">Email:</label></b>
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo $email; ?>"
                                required>
                        </div>
                        <div class="form-group row my-0">
                            <div class="form-group col-md-6 my-0">
                                <b><label for="phone">Phone No:</label></b>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon">+91</span>
                                    </div>
                                    <input type="tel" class="form-control" id="phone" name="phone"
                                        value="<?php echo $phone; ?>" required pattern="[0-9]{10}" maxlength="10">
                                </div>
                            </div>
                        </div>
                        <input type="hidden" id="userId" name="userId" value="<?php echo $Id; ?>">
                        <button type="submit" name="editUser" class="btn btn-success">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php
}
?>